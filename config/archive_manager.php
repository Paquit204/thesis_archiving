<?php
/**
 * ARCHIVE MANAGEMENT SYSTEM
 * For handling thesis archiving process
 */

class ArchiveManager {
    private $conn;
    private $errors = [];
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    /**
     * ARCHIVE A THESIS
     * Set is_archived = 1 and fill archive details
     */
    public function archiveThesis($thesis_id, $user_id, $notes = '', $retention_years = 5) {
        // Check if thesis exists and user owns it (for students) or faculty can archive any
        $check = $this->conn->prepare("SELECT thesis_id, title, student_id FROM thesis_table WHERE thesis_id = ?");
        $check->bind_param("i", $thesis_id);
        $check->execute();
        $result = $check->get_result();
        
        if($result->num_rows == 0) {
            $this->errors[] = "Thesis not found.";
            return false;
        }
        
        $thesis = $result->fetch_assoc();
        
        // Update thesis to archived
        $query = "UPDATE thesis_table 
                  SET is_archived = 1, 
                      archived_date = NOW(), 
                      archived_by = ?,
                      archive_notes = ?,
                      retention_period = ?
                  WHERE thesis_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isii", $user_id, $notes, $retention_years, $thesis_id);
        
        if($stmt->execute()) {
            // Optional: Send notification to student
            $this->sendArchiveNotification($thesis['student_id'], $thesis['title']);
            
            return true;
        } else {
            $this->errors[] = "Failed to archive thesis: " . $this->conn->error;
            return false;
        }
    }
    
    /**
     * RESTORE AN ARCHIVED THESIS
     * Set is_archived = 0 and clear archive fields
     */
    public function restoreThesis($thesis_id, $user_id) {
        // Check permission (faculty only or admin)
        $query = "UPDATE thesis_table 
                  SET is_archived = 0, 
                      archived_date = NULL,
                      archived_by = NULL,
                      archive_notes = NULL,
                      retention_period = NULL
                  WHERE thesis_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $thesis_id);
        
        if($stmt->execute()) {
            return true;
        } else {
            $this->errors[] = "Failed to restore thesis: " . $this->conn->error;
            return false;
        }
    }
    
    /**
     * GET ARCHIVED THESES
     * Fetch all theses with is_archived = 1
     */
    public function getArchivedTheses($filters = []) {
        $query = "SELECT t.*, 
                  u.first_name, u.last_name, 
                  a.first_name as archived_by_name,
                  a.last_name as archived_by_lastname
                  FROM thesis_table t
                  JOIN user_table u ON t.student_id = u.user_id
                  LEFT JOIN user_table a ON t.archived_by = a.user_id
                  WHERE t.is_archived = 1";
        
        $params = [];
        $types = "";
        
        // Filter by department
        if(!empty($filters['department'])) {
            $query .= " AND t.department = ?";
            $params[] = $filters['department'];
            $types .= "s";
        }
        
        // Filter by year
        if(!empty($filters['year'])) {
            $query .= " AND t.year = ?";
            $params[] = $filters['year'];
            $types .= "i";
        }
        
        // Filter by archived date range
        if(!empty($filters['archived_from'])) {
            $query .= " AND DATE(t.archived_date) >= ?";
            $params[] = $filters['archived_from'];
            $types .= "s";
        }
        
        if(!empty($filters['archived_to'])) {
            $query .= " AND DATE(t.archived_date) <= ?";
            $params[] = $filters['archived_to'];
            $types .= "s";
        }
        
        $query .= " ORDER BY t.archived_date DESC";
        
        $stmt = $this->conn->prepare($query);
        if(!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * GET ACTIVE THESES
     * Fetch all theses with is_archived = 0
     */
    public function getActiveTheses($filters = []) {
        $query = "SELECT t.*, u.first_name, u.last_name 
                  FROM thesis_table t
                  JOIN user_table u ON t.student_id = u.user_id
                  WHERE t.is_archived = 0";
        
        // Add filters similar to above
        if(!empty($filters['department'])) {
            $query .= " AND t.department = ?";
        }
        
        if(!empty($filters['year'])) {
            $query .= " AND t.year = ?";
        }
        
        $query .= " ORDER BY t.date_submitted DESC";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind params if any
        if(!empty($filters['department']) && !empty($filters['year'])) {
            $stmt->bind_param("ss", $filters['department'], $filters['year']);
        } elseif(!empty($filters['department'])) {
            $stmt->bind_param("s", $filters['department']);
        } elseif(!empty($filters['year'])) {
            $stmt->bind_param("s", $filters['year']);
        }
        
        $stmt->execute();
        return $stmt->get_result();
    }
    
    /**
     * GET SINGLE THESIS
     * Check if archived or active
     */
    public function getThesis($thesis_id) {
        $query = "SELECT t.*, u.first_name, u.last_name,
                  a.first_name as archived_by_first, a.last_name as archived_by_last
                  FROM thesis_table t
                  JOIN user_table u ON t.student_id = u.user_id
                  LEFT JOIN user_table a ON t.archived_by = a.user_id
                  WHERE t.thesis_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $thesis_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * CHECK IF THESIS IS ARCHIVED
     */
    public function isArchived($thesis_id) {
        $query = "SELECT is_archived FROM thesis_table WHERE thesis_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $thesis_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['is_archived'] == 1;
    }
    
    /**
     * SEND NOTIFICATION TO STUDENT
     */
    private function sendArchiveNotification($student_id, $thesis_title) {
        // Check if notification table exists
        $query = "INSERT INTO notifications (user_id, message, type, created_at) 
                  VALUES (?, ?, 'archive', NOW())";
        
        $message = "Your thesis '" . $thesis_title . "' has been archived.";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $student_id, $message);
        $stmt->execute();
    }
    
    public function getRetentionSummary() {
        $query = "SELECT 
                    COUNT(*) as total_archived,
                    SUM(CASE WHEN retention_period = 5 THEN 1 ELSE 0 END) as five_year,
                    SUM(CASE WHEN retention_period = 10 THEN 1 ELSE 0 END) as ten_year,
                    AVG(retention_period) as avg_retention
                  FROM thesis_table 
                  WHERE is_archived = 1";
        
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
    
    public function getErrors() {
        return $this->errors;
    }
}
?>