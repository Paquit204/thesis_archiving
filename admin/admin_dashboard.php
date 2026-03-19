<?php
session_start();
$current_page = 'dashboard';

$user_name = "Admin User";
$user_email = "admin@thesismanager.edu";
$institution = "University Administration";

$stats = [
    'total_students' => 1842,
    'total_faculty' => 156,
    'total_projects' => 487,
    'ongoing_projects' => 134,
    'completed_projects' => 242,
    'pending_reviews' => 71,
    'archived_count' => 40,
    'theses_approved' => 123,
    'theses_pending' => 48
];

$faculty_members = [
    ['id' => 1, 'name' => 'Prof. Juan Dela Cruz', 'department' => 'Computer Science', 'projects_supervised' => 8, 'status' => 'active'],
    ['id' => 2, 'name' => 'Dr. Ana Lopez', 'department' => 'Mathematics', 'projects_supervised' => 6, 'status' => 'active'],
    ['id' => 3, 'name' => 'Prof. Pedro Reyes', 'department' => 'Physics', 'projects_supervised' => 4, 'status' => 'active'],
    ['id' => 4, 'name' => 'Dr. Lisa Garcia', 'department' => 'Chemistry', 'projects_supervised' => 5, 'status' => 'on-leave'],
    ['id' => 5, 'name' => 'Prof. Mark Santiago', 'department' => 'Biology', 'projects_supervised' => 7, 'status' => 'active'],
    ['id' => 6, 'name' => 'Dr. Karen Villanueva', 'department' => 'Literature', 'projects_supervised' => 3, 'status' => 'active'],
];

$projects = [
    ['id'=>1,'title'=>'AI-Powered Thesis Recommendation','student'=>'Maria Santos','adviser'=>'Prof. Dela Cruz','submitted'=>'2024-03-15','status'=>'pending','defense_date'=>null,'dept'=>'CAS'],
    ['id'=>2,'title'=>'Mobile App for Campus Navigation','student'=>'Juan Dela Cruz','adviser'=>'Dr. Lopez','submitted'=>'2024-03-14','status'=>'in-progress','defense_date'=>'2024-04-20','dept'=>'CAS'],
    ['id'=>3,'title'=>'E-Learning Platform','student'=>'Ana Lopez','adviser'=>'Prof. Reyes','submitted'=>'2024-03-13','status'=>'completed','defense_date'=>'2024-03-30','dept'=>'CAS'],
    ['id'=>4,'title'=>'IoT-Based Monitoring','student'=>'Pedro Reyes','adviser'=>'Dr. Garcia','submitted'=>'2024-03-12','status'=>'pending','defense_date'=>null,'dept'=>'CAS'],
    ['id'=>5,'title'=>'Blockchain Records','student'=>'Lisa Garcia','adviser'=>'Prof. Santiago','submitted'=>'2024-03-11','status'=>'archived','defense_date'=>'2024-02-15','dept'=>'CAS'],
    ['id'=>6,'title'=>'VR Campus Tour','student'=>'Mark Santiago','adviser'=>'Dr. Villanueva','submitted'=>'2024-03-10','status'=>'in-progress','defense_date'=>'2024-05-10','dept'=>'CAS'],
    ['id'=>7,'title'=>'Grading System','student'=>'Karen Villanueva','adviser'=>'Prof. Dela Cruz','submitted'=>'2024-03-09','status'=>'pending','defense_date'=>null,'dept'=>'CAS'],
    ['id'=>8,'title'=>'Portfolio Generator','student'=>'Paul Mendoza','adviser'=>'Dr. Lopez','submitted'=>'2024-03-08','status'=>'completed','defense_date'=>'2024-03-25','dept'=>'CAS'],
    ['id'=>9,'title'=>'Data Mining','student'=>'Jose Rizal','adviser'=>'Prof. Reyes','submitted'=>'2024-03-07','status'=>'in-progress','defense_date'=>'2024-05-05','dept'=>'CAS'],
    ['id'=>10,'title'=>'Mobile Learning App','student'=>'Gabriela Silang','adviser'=>'Dr. Garcia','submitted'=>'2024-03-06','status'=>'pending','defense_date'=>null,'dept'=>'CAS'],
];

$upcoming_defenses = [
    ['id'=>1,'student'=>'Juan Dela Cruz','title'=>'Mobile App','date'=>'2024-04-20','time'=>'10:00 AM','panelists'=>'Dr. Lopez, Prof. Reyes'],
    ['id'=>2,'student'=>'Mark Santiago','title'=>'VR Tour','date'=>'2024-05-10','time'=>'2:00 PM','panelists'=>'Dr. Villanueva, Prof. Dela Cruz'],
    ['id'=>3,'student'=>'Jose Rizal','title'=>'Data Mining','date'=>'2024-05-05','time'=>'1:30 PM','panelists'=>'Prof. Reyes, Dr. Garcia'],
    ['id'=>4,'student'=>'Gabriela Silang','title'=>'Mobile App','date'=>'2024-05-15','time'=>'9:00 AM','panelists'=>'Dr. Garcia, Prof. Santiago'],
];

$activities = [
    ['icon'=>'check-circle','description'=>'New department: College of Engineering','user'=>'System','created_at'=>'2024-03-15 10:30 AM'],
    ['icon'=>'calendar-check','description'=>'System backup completed','user'=>'Admin','created_at'=>'2024-03-15 09:15 AM'],
    ['icon'=>'file-pdf','description'=>'Monthly report generated','user'=>'System','created_at'=>'2024-03-15 08:00 AM'],
    ['icon'=>'user-graduate','description'=>'Bulk student import','user'=>'Admin','created_at'=>'2024-03-14 04:20 PM'],
    ['icon'=>'comment','description'=>'Feedback submitted','user'=>'Dr. Garcia','created_at'=>'2024-03-14 11:45 AM'],
    ['icon'=>'award','description'=>'Project completed','user'=>'Prof. Reyes','created_at'=>'2024-03-14 10:30 AM'],
];

$workload_stats = [
    'max_supervised' => 8,
    'avg_supervised' => 5.5,
    'under_load' => 2,
    'over_load' => 1
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ThesisManager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ========== EXACT SAME CSS AS DEAN DASHBOARD (with minor label adjustments) ========== */
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
        body { background:#f8f9fa; display:flex; height:100vh; overflow:hidden; }
        ::-webkit-scrollbar { width:8px; height:8px; }
        ::-webkit-scrollbar-track { background:#f1f1f1; }
        ::-webkit-scrollbar-thumb { background:#ef9a9a; border-radius:10px; }
        ::-webkit-scrollbar-thumb:hover { background:#d32f2f; }

        .sidebar { width:280px; background:linear-gradient(180deg,#b71c1c 0%,#d32f2f 50%,#e57373 100%); color:white; padding:25px 0; display:flex; flex-direction:column; overflow-y:auto; box-shadow:4px 0 20px rgba(211,47,47,0.3); }
        .logo-container { padding:0 25px; margin-bottom:40px; text-align:center; }
        .logo { font-size:24px; font-weight:800; background:linear-gradient(135deg,#ffcdd2 0%,#ffebee 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; letter-spacing:-0.5px; margin-bottom:8px; display:block; }
        .logo span { background:linear-gradient(135deg,#ffffff 0%,#ffebee 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .admin-label { font-size:14px; font-weight:600; color:#ffebee; letter-spacing:1px; text-transform:uppercase; display:block; text-align:center; }
        .nav-menu { flex:1; }
        .nav-item { display:flex; align-items:center; padding:12px 25px; margin:5px 10px; border-radius:12px; color:#ffebee; transition:all 0.3s ease; text-decoration:none; }
        .nav-item i { width:24px; font-size:1.2rem; margin-right:15px; }
        .nav-item span { font-size:0.95rem; font-weight:500; }
        .nav-item:hover { background:rgba(255,255,255,0.15); color:white; transform:translateX(5px); }
        .nav-item.active { background:#b71c1c; color:white; box-shadow:0 10px 20px rgba(183,28,28,0.4); }
        .nav-footer { padding:20px 25px; border-top:1px solid rgba(255,255,255,0.15); }
        .logout-btn { display:flex; align-items:center; color:#ffebee; text-decoration:none; padding:10px; border-radius:10px; transition:all 0.3s ease; }
        .logout-btn i { margin-right:10px; font-size:1.1rem; }
        .logout-btn:hover { background:rgba(211,47,47,0.5); color:white; transform:translateX(5px); }

        .main-content { flex:1; padding:25px 35px; overflow-y:auto; background:#f8f9fa; }
        .top-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:30px; background:white; padding:10px 20px; border-radius:20px; box-shadow:0 5px 15px rgba(211,47,47,0.08); border:1px solid #ffcdd2; }
        .search-area { display:flex; align-items:center; background:#f8f9fa; padding:10px 20px; border-radius:15px; width:350px; }
        .search-area i { color:#d32f2f; margin-right:10px; font-size:0.9rem; }
        .search-area input { border:none; background:transparent; outline:none; width:100%; font-size:0.95rem; color:#333; }
        .search-area input::placeholder { color:#999; }
        .user-profile { display:flex; align-items:center; gap:15px; }
        .notification-icon { position:relative; width:45px; height:45px; background:#f8f9fa; border-radius:12px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.3s ease; }
        .notification-icon:hover { background:#ffcdd2; }
        .notification-badge { position:absolute; top:-5px; right:-5px; background:#d32f2f; color:white; font-size:10px; padding:3px 6px; border-radius:10px; font-weight:600; }
        .user-info { display:flex; align-items:center; gap:15px; padding:5px 5px 5px 15px; background:#f8f9fa; border-radius:15px; cursor:pointer; transition:all 0.3s ease; }
        .user-info:hover { background:#ffcdd2; }
        .user-details { text-align:right; }
        .user-name { font-weight:600; color:#333; font-size:0.95rem; }
        .user-role { font-size:0.75rem; color:#666; text-transform:uppercase; letter-spacing:0.5px; }
        .avatar { width:45px; height:45px; background:#d32f2f; color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.2rem; box-shadow:0 5px 15px rgba(211,47,47,0.3); }

        .dept-banner { background:linear-gradient(135deg,#b71c1c 0%,#d32f2f 100%); border-radius:25px; padding:30px 35px; margin-bottom:30px; color:white; display:flex; justify-content:space-between; align-items:center; box-shadow:0 20px 30px rgba(211,47,47,0.2); }
        .dept-info h1 { font-size:2rem; font-weight:700; margin-bottom:5px; color:white; }
        .dept-info p { font-size:1rem; opacity:0.9; color:white; }
        .dean-info { text-align:right; }
        .dean-name { font-size:1.2rem; font-weight:600; margin-bottom:5px; color:white; }
        .dean-since { font-size:0.9rem; opacity:0.8; color:white; }

        .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:25px; margin-bottom:30px; }
        .stat-card { background:white; border-radius:20px; padding:25px; display:flex; align-items:center; gap:20px; box-shadow:0 5px 15px rgba(211,47,47,0.08); transition:all 0.3s ease; border:1px solid #ffcdd2; }
        .stat-card:hover { transform:translateY(-5px); box-shadow:0 15px 30px rgba(211,47,47,0.15); border-color:#d32f2f; }
        .stat-icon { width:60px; height:60px; background:#d32f2f; border-radius:15px; display:flex; align-items:center; justify-content:center; color:white; font-size:1.8rem; }
        .stat-icon.secondary { background:#ef9a9a; }
        .stat-details h3 { font-size:2rem; font-weight:700; color:#333; margin-bottom:5px; }
        .stat-details p { color:#666; font-size:0.9rem; font-weight:500; }

        .dept-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:25px; margin-bottom:30px; }
        .dept-stat-card { background:white; border-radius:20px; padding:20px; box-shadow:0 5px 15px rgba(211,47,47,0.08); border:1px solid #ffcdd2; }
        .dept-stat-header { display:flex; align-items:center; gap:10px; margin-bottom:15px; color:#333; font-weight:600; }
        .dept-stat-header i { color:#d32f2f; }
        .dept-stat-value { font-size:2rem; font-weight:700; color:#333; }
        .dept-stat-label { color:#666; font-size:0.85rem; }

        .charts-section { display:grid; grid-template-columns:1fr 1fr; gap:25px; margin-bottom:30px; }
        .chart-card { background:white; border-radius:20px; padding:25px; box-shadow:0 5px 15px rgba(211,47,47,0.08); border:1px solid #ffcdd2; }
        .chart-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .chart-header h3 { color:#333; font-size:1.1rem; font-weight:600; }
        .chart-header select { padding:8px 15px; border:1px solid #ffcdd2; border-radius:10px; outline:none; font-size:0.9rem; color:#333; background:#f8f9fa; }
        .chart-container { height:250px; position:relative; }

        .section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .section-title { font-size:1.3rem; font-weight:700; color:#333; }
        .view-all { color:#d32f2f; text-decoration:none; font-size:0.9rem; font-weight:600; display:flex; align-items:center; gap:5px; transition:all 0.3s ease; }
        .view-all:hover { gap:10px; color:#ef9a9a; }

        .faculty-section { background:white; border-radius:20px; padding:25px; margin-bottom:30px; border:1px solid #ffcdd2; }
        .faculty-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
        .faculty-card { background:#f8f9fa; border-radius:15px; padding:20px; border:1px solid #ffebee; transition:all 0.3s ease; }
        .faculty-card:hover { border-color:#d32f2f; transform:translateY(-3px); box-shadow:0 10px 20px rgba(211,47,47,0.1); }
        .faculty-header { display:flex; align-items:center; gap:15px; margin-bottom:15px; }
        .faculty-avatar { width:50px; height:50px; background:#d32f2f; border-radius:12px; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:1.2rem; }
        .faculty-name { font-weight:600; color:#333; margin-bottom:3px; }
        .faculty-spec { font-size:0.85rem; color:#666; }
        .faculty-stats { display:flex; justify-content:space-between; margin-top:10px; padding-top:10px; border-top:1px solid #ffebee; }
        .faculty-stat { text-align:center; }
        .faculty-stat-value { font-weight:700; color:#333; }
        .faculty-stat-label { font-size:0.7rem; color:#999; }
        .status-badge { padding:3px 8px; border-radius:12px; font-size:0.7rem; font-weight:600; }
        .status-badge.active { background:#c8e6c9; color:#2e7d32; }
        .status-badge.on-leave { background:#ffecb3; color:#b76e1c; }

        .projects-section { background:white; border-radius:20px; padding:25px; margin-bottom:30px; border:1px solid #ffcdd2; }
        .table-responsive { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        th { text-align:left; padding:15px 10px; color:#666; font-weight:600; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px; border-bottom:2px solid #ffcdd2; }
        td { padding:15px 10px; border-bottom:1px solid #ffebee; color:#333; font-size:0.95rem; }
        .status { display:flex; align-items:center; gap:8px; }
        .status-dot { width:10px; height:10px; border-radius:50%; }
        .status-dot.pending { background:#ef9a9a; box-shadow:0 0 0 3px rgba(239,154,154,0.2); }
        .status-dot.in-progress { background:#d32f2f; box-shadow:0 0 0 3px rgba(211,47,47,0.2); }
        .status-dot.completed { background:#81c784; box-shadow:0 0 0 3px rgba(129,199,132,0.2); }
        .status-dot.archived { background:#b71c1c; box-shadow:0 0 0 3px rgba(183,28,28,0.2); }
        .status-text { font-size:0.9rem; font-weight:500; color:#333; }
        .defense-date { font-size:0.85rem; color:#666; }
        .defense-date i { color:#d32f2f; margin-right:5px; }
        .btn-view { padding:6px 12px; border-radius:8px; font-size:0.85rem; text-decoration:none; font-weight:500; transition:all 0.3s ease; background:#ffebee; color:#333; display:inline-flex; align-items:center; gap:5px; }
        .btn-view:hover { transform:translateY(-2px); box-shadow:0 5px 10px rgba(211,47,47,0.2); background:#ffcdd2; }

        .defenses-section { background:white; border-radius:20px; padding:25px; margin-bottom:30px; border:1px solid #ffcdd2; }
        .defense-item { display:flex; align-items:center; gap:20px; padding:15px; border-bottom:1px solid #ffebee; }
        .defense-item:last-child { border-bottom:none; }
        .defense-date-box { min-width:70px; text-align:center; background:#ffebee; padding:10px 5px; border-radius:12px; border:1px solid #ffcdd2; }
        .defense-day { font-size:1.8rem; font-weight:700; color:#b71c1c; line-height:1.2; }
        .defense-month { font-size:0.8rem; color:#666; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; }
        .defense-details { flex:1; }
        .defense-title { font-weight:600; color:#333; margin-bottom:8px; font-size:1.1rem; }
        .defense-meta { display:flex; gap:20px; font-size:0.9rem; color:#666; margin-bottom:5px; }
        .defense-meta i { color:#d32f2f; margin-right:5px; width:16px; }
        .defense-panel { font-size:0.85rem; color:#666; background:#f8f9fa; padding:5px 10px; border-radius:20px; display:inline-block; }

        .bottom-grid { display:grid; grid-template-columns:1fr 1fr; gap:25px; margin-bottom:30px; }
        .activities-section { background:white; border-radius:20px; padding:25px; border:1px solid #ffcdd2; }
        .workload-section { background:white; border-radius:20px; padding:25px; border:1px solid #ffcdd2; }
        .activity-item { display:flex; align-items:center; gap:15px; padding:12px 0; border-bottom:1px solid #ffebee; }
        .activity-item:last-child { border-bottom:none; }
        .activity-icon { width:40px; height:40px; background:#ffebee; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#d32f2f; font-size:1.2rem; }
        .activity-details { flex:1; }
        .activity-text { color:#333; font-size:0.95rem; font-weight:500; margin-bottom:3px; }
        .activity-meta { display:flex; gap:15px; color:#999; font-size:0.8rem; }
        .activity-user { color:#d32f2f; font-weight:500; }

        .workload-item { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #ffebee; }
        .workload-label { color:#666; }
        .workload-value { font-weight:700; color:#333; }
        .progress-bar { width:100%; height:8px; background:#ffebee; border-radius:4px; margin-top:5px; }
        .progress-fill { height:8px; background:#d32f2f; border-radius:4px; }

        .quick-actions { display:flex; gap:15px; margin-top:20px; }
        .quick-action-btn { flex:1; background:white; border:1px solid #ffcdd2; border-radius:15px; padding:15px; display:flex; flex-direction:column; align-items:center; gap:10px; cursor:pointer; transition:all 0.3s ease; text-decoration:none; color:#333; }
        .quick-action-btn:hover { border-color:#d32f2f; transform:translateY(-3px); box-shadow:0 10px 20px rgba(211,47,47,0.15); background:#fff5f5; }
        .quick-action-btn i { font-size:1.8rem; color:#d32f2f; }
        .quick-action-btn span { font-size:0.9rem; font-weight:600; color:#333; }

        /* Stats mini cards (for reports etc.) */
        .stats-mini-grid { display:grid; grid-template-columns:repeat(6,1fr); gap:20px; margin-bottom:30px; }
        .stat-mini-card { background:white; border-radius:15px; padding:20px; text-align:center; border:1px solid #ffcdd2; transition:all 0.3s ease; }
        .stat-mini-card:hover { transform:translateY(-3px); box-shadow:0 10px 20px rgba(211,47,47,0.1); border-color:#d32f2f; }
        .stat-mini-value { font-size:2.5rem; font-weight:700; color:#d32f2f; line-height:1.2; }
        .stat-mini-label { color:#666; font-size:1rem; font-weight:500; }

        /* Forms */
        .form-container { background:white; border-radius:20px; padding:25px; border:1px solid #ffcdd2; margin-top:25px; }
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; margin-bottom:5px; font-weight:500; color:#333; }
        .form-group input, .form-group select, .form-group textarea { width:100%; padding:10px; border:1px solid #ffcdd2; border-radius:10px; font-size:0.95rem; }
        .form-group input:focus { outline:none; border-color:#d32f2f; }
        .btn-submit { background:#d32f2f; color:white; border:none; padding:12px 25px; border-radius:10px; font-weight:600; cursor:pointer; transition:all 0.3s ease; }
        .btn-submit:hover { background:#b71c1c; transform:translateY(-2px); }
        .message { margin:15px 0; padding:10px; border-radius:8px; }

        .settings-grid { display:grid; grid-template-columns:1fr 1fr; gap:25px; margin-bottom:25px; }
        .checkbox-group { display:flex; flex-direction:column; gap:12px; margin:15px 0; }
        .checkbox-group label { display:flex; align-items:center; gap:8px; font-weight:400; color:#333; cursor:pointer; }
        .checkbox-group input[type="checkbox"] { width:16px; height:16px; accent-color:#d32f2f; }

        /* Responsive */
        @media (max-width:1200px) {
            .stats-grid, .dept-stats { grid-template-columns:repeat(2,1fr); }
            .faculty-grid { grid-template-columns:repeat(2,1fr); }
            .stats-mini-grid { grid-template-columns:repeat(3,1fr); }
        }
        @media (max-width:768px) {
            body { flex-direction:column; height:auto; overflow:auto; }
            .sidebar { width:100%; height:auto; padding:15px; }
            .nav-menu { display:flex; flex-wrap:wrap; }
            .nav-item { flex:1 0 auto; margin:2px; }
            .main-content { padding:15px; }
            .top-bar { flex-direction:column; gap:10px; }
            .search-area { width:100%; }
            .stats-grid, .dept-stats, .charts-section, .bottom-grid, .stats-mini-grid, .settings-grid { grid-template-columns:1fr; }
            .faculty-grid { grid-template-columns:1fr; }
            .dept-banner { flex-direction:column; text-align:center; gap:10px; }
            .dean-info { text-align:center; }
            .quick-actions { flex-wrap:wrap; }
        }
        @media (max-width:480px) {
            .user-info .user-details { display:none; }
            .nav-item span { font-size:0.8rem; }
            .nav-item i { margin-right:5px; }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo-container">
            <div class="logo">Thesis<span>Manager</span></div>
            <div class="admin-label">ADMIN</div>
        </div>
        <div class="nav-menu">
            <a href="admin_dashboard.php" class="nav-item <?php echo $current_page=='dashboard'?'active':''; ?>"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
            <a href="admin_faculty.php" class="nav-item <?php echo $current_page=='faculty'?'active':''; ?>"><i class="fas fa-users"></i><span>Faculty</span></a>
            <a href="admin_students.php" class="nav-item <?php echo $current_page=='students'?'active':''; ?>"><i class="fas fa-user-graduate"></i><span>Students</span></a>
            <a href="admin_projects.php" class="nav-item <?php echo $current_page=='projects'?'active':''; ?>"><i class="fas fa-project-diagram"></i><span>Projects</span></a>
            <a href="admin_defenses.php" class="nav-item <?php echo $current_page=='defenses'?'active':''; ?>"><i class="fas fa-calendar-check"></i><span>Defenses</span></a>
            <a href="admin_archive.php" class="nav-item <?php echo $current_page=='archive'?'active':''; ?>"><i class="fas fa-archive"></i><span>Archive</span></a>
            <a href="admin_reports.php" class="nav-item <?php echo $current_page=='reports'?'active':''; ?>"><i class="fas fa-chart-bar"></i><span>Reports</span></a>
            <a href="admin_settings.php" class="nav-item <?php echo $current_page=='settings'?'active':''; ?>"><i class="fas fa-cog"></i><span>Settings</span></a>
        </div>
        <div class="nav-footer">
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOP BAR -->
        <div class="top-bar">
            <div class="search-area">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>
            <div class="user-profile">
                <div class="notification-icon">
                    <i class="far fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="user-info">
                    <div class="user-details">
                        <div class="user-name"><?php echo $user_name; ?></div>
                        <div class="user-role">ADMIN</div>
                    </div>
                    <div class="avatar">AD</div>
                </div>
            </div>
        </div>

        <!-- BANNER -->
        <div class="dept-banner">
            <div class="dept-info">
                <h1>Admin Dashboard</h1>
                <p><?php echo $institution; ?> • System Overview</p>
            </div>
            <div class="dean-info">
                <div class="dean-name">Admin User</div>
                <div class="dean-since">System Administrator</div>
            </div>
        </div>

        <!-- STATS CARDS - Row 1 -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-details"><h3><?php echo $stats['total_students']; ?></h3><p>Students</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-details"><h3><?php echo $stats['total_faculty']; ?></h3><p>Faculty</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-project-diagram"></i></div>
                <div class="stat-details"><h3><?php echo $stats['total_projects']; ?></h3><p>Total Projects</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon secondary"><i class="fas fa-clock"></i></div>
                <div class="stat-details"><h3><?php echo $stats['pending_reviews']; ?></h3><p>Pending Reviews</p></div>
            </div>
        </div>

        <!-- Department Stats - Row 2 -->
        <div class="dept-stats">
            <div class="dept-stat-card">
                <div class="dept-stat-header"><i class="fas fa-check-circle"></i><span>Completed</span></div>
                <div class="dept-stat-value"><?php echo $stats['completed_projects']; ?></div>
                <div class="dept-stat-label">theses & projects</div>
            </div>
            <div class="dept-stat-card">
                <div class="dept-stat-header"><i class="fas fa-spinner"></i><span>Ongoing</span></div>
                <div class="dept-stat-value"><?php echo $stats['ongoing_projects']; ?></div>
                <div class="dept-stat-label">active projects</div>
            </div>
            <div class="dept-stat-card">
                <div class="dept-stat-header"><i class="fas fa-gavel"></i><span>Defenses</span></div>
                <div class="dept-stat-value"><?php echo count($upcoming_defenses); ?></div>
                <div class="dept-stat-label">upcoming defenses</div>
            </div>
            <div class="dept-stat-card">
                <div class="dept-stat-header"><i class="fas fa-check-double"></i><span>Approved</span></div>
                <div class="dept-stat-value"><?php echo $stats['theses_approved']; ?></div>
                <div class="dept-stat-label">theses this sem</div>
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-header"><h3>Project Status Distribution</h3></div>
                <div class="chart-container"><canvas id="projectStatusChart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-header"><h3>Faculty Workload</h3><select><option>This Semester</option><option>Last Semester</option></select></div>
                <div class="chart-container"><canvas id="workloadChart"></canvas></div>
            </div>
        </div>

        <!-- FACULTY SECTION -->
        <div class="faculty-section">
            <div class="section-header">
                <h2 class="section-title">Faculty Snapshot</h2>
                <a href="admin_faculty.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="faculty-grid">
                <?php foreach (array_slice($faculty_members, 0, 3) as $faculty): ?>
                <div class="faculty-card">
                    <div class="faculty-header">
                        <div class="faculty-avatar"><?php echo strtoupper(substr($faculty['name'], 0, 1) . substr(explode(' ', $faculty['name'])[1] ?? '', 0, 1)); ?></div>
                        <div>
                            <div class="faculty-name"><?php echo $faculty['name']; ?></div>
                            <div class="faculty-spec"><?php echo $faculty['department']; ?></div>
                        </div>
                    </div>
                    <div class="faculty-stats">
                        <div class="faculty-stat"><div class="faculty-stat-value"><?php echo $faculty['projects_supervised']; ?></div><div class="faculty-stat-label">Projects</div></div>
                        <div class="faculty-stat"><div class="faculty-stat-value"><span class="status-badge <?php echo $faculty['status']; ?>"><?php echo ucfirst($faculty['status']); ?></span></div><div class="faculty-stat-label">Status</div></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- RECENT PROJECTS -->
        <div class="projects-section">
            <div class="section-header">
                <h2 class="section-title">Recent Projects</h2>
                <a href="admin_projects.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead><tr><th>Title</th><th>Student</th><th>Adviser</th><th>Defense Date</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach (array_slice($projects, 0, 5) as $project): ?>
                        <tr>
                            <td><?php echo $project['title']; ?></td>
                            <td><?php echo $project['student']; ?></td>
                            <td><?php echo $project['adviser']; ?></td>
                            <td><?php if ($project['defense_date']): ?><span class="defense-date"><i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($project['defense_date'])); ?></span><?php else: ?><span class="defense-date">Not scheduled</span><?php endif; ?></td>
                            <td><div class="status"><span class="status-dot <?php echo $project['status']; ?>"></span><span class="status-text"><?php echo ucfirst(str_replace('-', ' ', $project['status'])); ?></span></div></td>
                            <td><a href="#" class="btn-view"><i class="fas fa-eye"></i> View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- UPCOMING DEFENSES -->
        <div class="defenses-section">
            <div class="section-header">
                <h2 class="section-title">Upcoming Defenses</h2>
                <a href="admin_defenses.php" class="view-all">Schedule New <i class="fas fa-plus"></i></a>
            </div>
            <?php foreach ($upcoming_defenses as $defense): ?>
            <div class="defense-item">
                <div class="defense-date-box">
                    <div class="defense-day"><?php echo date('d', strtotime($defense['date'])); ?></div>
                    <div class="defense-month"><?php echo strtoupper(date('M', strtotime($defense['date']))); ?></div>
                </div>
                <div class="defense-details">
                    <div class="defense-title"><?php echo $defense['title']; ?></div>
                    <div class="defense-meta"><span><i class="fas fa-user-graduate"></i> <?php echo $defense['student']; ?></span><span><i class="far fa-clock"></i> <?php echo $defense['time']; ?></span></div>
                    <div class="defense-panel"><i class="fas fa-users"></i> Panel: <?php echo $defense['panelists']; ?></div>
                </div>
                <a href="#" class="btn-view"><i class="fas fa-calendar-check"></i> Details</a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- BOTTOM GRID -->
        <div class="bottom-grid">
            <div class="activities-section">
                <div class="section-header"><h2 class="section-title">System Activities</h2><a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a></div>
                <?php foreach ($activities as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-<?php echo $activity['icon']; ?>"></i></div>
                    <div class="activity-details">
                        <div class="activity-text"><?php echo $activity['description']; ?></div>
                        <div class="activity-meta"><span><i class="far fa-clock"></i> <?php echo $activity['created_at']; ?></span><span class="activity-user"><i class="fas fa-user"></i> <?php echo $activity['user']; ?></span></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="workload-section">
                <div class="section-header"><h2 class="section-title">Faculty Workload Summary</h2><a href="admin_reports.php" class="view-all">Details <i class="fas fa-arrow-right"></i></a></div>
                <div class="workload-item"><span class="workload-label">Average Projects per Faculty</span><span class="workload-value"><?php echo $workload_stats['avg_supervised']; ?></span></div>
                <div class="workload-item"><span class="workload-label">Maximum Projects Supervised</span><span class="workload-value"><?php echo $workload_stats['max_supervised']; ?></span></div>
                <div class="workload-item"><span class="workload-label">Faculty Under Load (&lt; 3 projects)</span><span class="workload-value"><?php echo $workload_stats['under_load']; ?></span></div>
                <div class="workload-item"><span class="workload-label">Faculty Over Load (&gt; 6 projects)</span><span class="workload-value"><?php echo $workload_stats['over_load']; ?></span></div>
                <div style="margin-top:20px;"><div style="display:flex; justify-content:space-between; margin-bottom:5px;"><span class="workload-label">Workload Distribution</span><span class="workload-value">70%</span></div><div class="progress-bar"><div class="progress-fill" style="width:70%;"></div></div></div>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="quick-actions">
            <a href="admin_defenses.php" class="quick-action-btn"><i class="fas fa-calendar-plus"></i><span>Schedule Defense</span></a>
            <a href="admin_reports.php" class="quick-action-btn"><i class="fas fa-file-pdf"></i><span>Generate Report</span></a>
            <a href="admin_reports.php" class="quick-action-btn"><i class="fas fa-chart-line"></i><span>View Analytics</span></a>
            <a href="admin_faculty.php" class="quick-action-btn"><i class="fas fa-user-plus"></i><span>Add Faculty</span></a>
            <a href="#" class="quick-action-btn"><i class="fas fa-envelope"></i><span>Announcement</span></a>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('projectStatusChart'), {
            type:'doughnut',
            data:{ labels:['Pending','In Progress','Completed','Archived'], datasets:[{ data:[<?php echo $stats['pending_reviews']; ?>,<?php echo $stats['ongoing_projects']; ?>,<?php echo $stats['completed_projects']; ?>,<?php echo $stats['archived_count']; ?>], backgroundColor:['#ef9a9a','#d32f2f','#81c784','#b71c1c'], borderWidth:0 }] },
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{ padding:20, usePointStyle:true, pointStyle:'circle', color:'#333' } } }, cutout:'70%' }
        });
        new Chart(document.getElementById('workloadChart'), {
            type:'bar',
            data:{ labels:['Prof. Dela Cruz','Dr. Lopez','Prof. Reyes','Dr. Garcia','Prof. Santiago','Dr. Villanueva'], datasets:[{ label:'Projects Supervised', data:[8,6,4,5,7,3], backgroundColor:'#d32f2f', borderRadius:6 }] },
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, max:10, grid:{ color:'rgba(183,28,28,0.05)' }, ticks:{ color:'#333' } }, x:{ ticks:{ color:'#333' } } } }
        });
    </script>
</body>
</html>