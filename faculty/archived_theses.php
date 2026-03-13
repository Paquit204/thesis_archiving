<?php
session_start();
include("../config/db.php");
include("../config/archive_manager.php");

$archive = new ArchiveManager($conn);

if(isset($_POST['restore_thesis'])) {
    $thesis_id = $_POST['thesis_id'];
    
    if($archive->restoreThesis($thesis_id, $_SESSION['user_id'])) {
        $_SESSION['success'] = "Thesis restored successfully!";
        header("Location: archived_theses.php");
        exit();
    }
}

$filters = [
    'department' => $_GET['department'] ?? '',
    'year' => $_GET['year'] ?? '',
    'archived_from' => $_GET['archived_from'] ?? '',
    'archived_to' => $_GET['archived_to'] ?? ''
];

$archived = $archive->getArchivedTheses($filters);
$summary = $archive->getRetentionSummary();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Archived Theses</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f8fafc; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #FE4853; margin-bottom: 20px; }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 5px solid #FE4853;
        }
        
        .card h3 { font-size: 2rem; color: #FE4853; }
        
        .filter-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .filter-form input, .filter-form select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .filter-form button {
            background: #FE4853;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        th {
            background: #FE4853;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .archived-badge {
            background: #FE4853;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .btn {
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .btn-restore { background: #28a745; }
        .btn-view { background: #007bff; }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Archived Theses</h1>
        
        <div class="summary-cards">
            <div class="card">
                <h3><?php echo $summary['total_archived']; ?></h3>
                <p>Total Archived</p>
            </div>
            <div class="card">
                <h3><?php echo round($summary['avg_retention']); ?> yrs</h3>
                <p>Average Retention</p>
            </div>
            <div class="card">
                <h3><?php echo $summary['five_year']; ?></h3>
                <p>5-Year Retention</p>
            </div>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <form method="GET" class="filter-form">
            <select name="department">
                <option value="">All Departments</option>
                <option value="CS" <?php echo $filters['department'] == 'CS' ? 'selected' : ''; ?>>Computer Science</option>
                <option value="IT" <?php echo $filters['department'] == 'IT' ? 'selected' : ''; ?>>IT</option>
                <option value="ENG" <?php echo $filters['department'] == 'ENG' ? 'selected' : ''; ?>>Engineering</option>
            </select>
            
            <select name="year">
                <option value="">All Years</option>
                <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                    <option value="<?php echo $y; ?>" <?php echo $filters['year'] == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                <?php endfor; ?>
            </select>
            
            <input type="date" name="archived_from" placeholder="From" value="<?php echo $filters['archived_from']; ?>">
            <input type="date" name="archived_to" placeholder="To" value="<?php echo $filters['archived_to']; ?>">
            
            <button type="submit">Apply Filters</button>
            <a href="archived_theses.php" style="padding:8px 20px; background:#6c757d; color:white; text-decoration:none; border-radius:5px;">Reset</a>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Thesis Title</th>
                    <th>Student</th>
                    <th>Department</th>
                    <th>Archived Date</th>
                    <th>Retention</th>
                    <th>Archived By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($archived->num_rows > 0): ?>
                    <?php while($thesis = $archived->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($thesis['title']); ?>
                            <div><small class="archived-badge">Archived</small></div>
                        </td>
                        <td><?php echo $thesis['first_name'] . ' ' . $thesis['last_name']; ?></td>
                        <td><?php echo $thesis['department']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($thesis['archived_date'])); ?></td>
                        <td><?php echo $thesis['retention_period']; ?> years</td>
                        <td><?php echo $thesis['archived_by_name'] . ' ' . $thesis['archived_by_lastname']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="thesis_id" value="<?php echo $thesis['thesis_id']; ?>">
                                <button type="submit" name="restore_thesis" class="btn btn-restore" onclick="return confirm('Restore this thesis?')">Restore</button>
                            </form>
                            <a href="view_archived.php?id=<?php echo $thesis['thesis_id']; ?>" class="btn btn-view">View</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px;">No archived theses found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
