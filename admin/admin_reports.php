<?php
session_start();
$current_page = 'reports';

$user_name = "Admin User";
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

$faculty_workload = [
    ['name' => 'Prof. Dela Cruz', 'projects' => 8],
    ['name' => 'Dr. Lopez', 'projects' => 6],
    ['name' => 'Prof. Reyes', 'projects' => 4],
    ['name' => 'Dr. Garcia', 'projects' => 5],
    ['name' => 'Prof. Santiago', 'projects' => 7],
    ['name' => 'Dr. Villanueva', 'projects' => 3],
];

$report_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    $report_type = $_POST['report_type'] ?? '';
    $date_from = $_POST['date_from'] ?? '';
    $date_to = $_POST['date_to'] ?? '';
    $format = $_POST['format'] ?? 'pdf';

    if (empty($report_type) || empty($date_from) || empty($date_to)) {
        $report_message = '<span style="color: red;">All fields except format are required.</span>';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
        $report_message = '<span style="color: red;">Invalid date format (use YYYY-MM-DD).</span>';
    } elseif (strtotime($date_from) > strtotime($date_to)) {
        $report_message = '<span style="color: red;">Start date cannot be after end date.</span>';
    } else {
        $report_message = '<span style="color: green;">Report generated successfully (demo).</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ========== EXACT SAME CSS AS ADMIN DASHBOARD ========== */
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

        .section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .section-title { font-size:1.3rem; font-weight:700; color:#333; }

        .stats-mini-grid { display:grid; grid-template-columns:repeat(6,1fr); gap:20px; margin-bottom:30px; }
        .stat-mini-card { background:white; border-radius:15px; padding:20px; text-align:center; border:1px solid #ffcdd2; transition:all 0.3s ease; }
        .stat-mini-card:hover { transform:translateY(-3px); box-shadow:0 10px 20px rgba(211,47,47,0.1); border-color:#d32f2f; }
        .stat-mini-value { font-size:2.5rem; font-weight:700; color:#d32f2f; line-height:1.2; }
        .stat-mini-label { color:#666; font-size:1rem; font-weight:500; }

        .charts-section { display:grid; grid-template-columns:1fr 1fr; gap:25px; margin-bottom:30px; }
        .chart-card { background:white; border-radius:20px; padding:25px; border:1px solid #ffcdd2; }
        .chart-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .chart-header h3 { color:#333; font-size:1.1rem; font-weight:600; }
        .chart-container { height:250px; position:relative; }

        .form-container { background:white; border-radius:20px; padding:25px; border:1px solid #ffcdd2; }
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; margin-bottom:5px; font-weight:500; color:#333; }
        .form-group input, .form-group select { width:100%; padding:10px; border:1px solid #ffcdd2; border-radius:10px; font-size:0.95rem; }
        .form-group input:focus { outline:none; border-color:#d32f2f; }
        .btn-submit { background:#d32f2f; color:white; border:none; padding:12px 25px; border-radius:10px; font-weight:600; cursor:pointer; transition:all 0.3s ease; }
        .btn-submit:hover { background:#b71c1c; transform:translateY(-2px); }
        .message { margin:15px 0; padding:10px; border-radius:8px; }

        /* Responsive */
        @media (max-width:1200px) { .stats-mini-grid { grid-template-columns:repeat(3,1fr); } }
        @media (max-width:768px) {
            body { flex-direction:column; height:auto; overflow:auto; }
            .sidebar { width:100%; height:auto; padding:15px; }
            .nav-menu { display:flex; flex-wrap:wrap; }
            .nav-item { flex:1 0 auto; margin:2px; }
            .main-content { padding:15px; }
            .top-bar { flex-direction:column; gap:10px; }
            .search-area { width:100%; }
            .stats-mini-grid { grid-template-columns:repeat(2,1fr); }
            .charts-section { grid-template-columns:1fr; }
            .dept-banner { flex-direction:column; text-align:center; gap:10px; }
            .dean-info { text-align:center; }
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
            <div class="search-area"><i class="fas fa-search"></i><input type="text" placeholder="Search reports..."></div>
            <div class="user-profile">
                <div class="notification-icon"><i class="far fa-bell"></i><span class="notification-badge">2</span></div>
                <div class="user-info"><div class="user-details"><div class="user-name"><?php echo $user_name; ?></div><div class="user-role">ADMIN</div></div><div class="avatar">AD</div></div>
            </div>
        </div>

        <!-- BANNER -->
        <div class="dept-banner">
            <div class="dept-info"><h1>Reports & Analytics</h1><p><?php echo $institution; ?></p></div>
            <div class="dean-info"><div class="dean-name">Admin User</div><div class="dean-since">System Administrator</div></div>
        </div>

        <!-- STATS MINI CARDS -->
        <div class="stats-mini-grid">
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $stats['total_projects']; ?></div><div class="stat-mini-label">Total Projects</div></div>
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $stats['completed_projects']; ?></div><div class="stat-mini-label">Completed</div></div>
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $stats['ongoing_projects']; ?></div><div class="stat-mini-label">Ongoing</div></div>
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $stats['pending_reviews']; ?></div><div class="stat-mini-label">Pending</div></div>
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $stats['total_faculty']; ?></div><div class="stat-mini-label">Faculty</div></div>
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $stats['total_students']; ?></div><div class="stat-mini-label">Students</div></div>
        </div>

        <!-- CHARTS -->
        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-header"><h3>Project Status Distribution</h3></div>
                <div class="chart-container"><canvas id="projectStatusChart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-header"><h3>Faculty Workload</h3></div>
                <div class="chart-container"><canvas id="workloadChart"></canvas></div>
            </div>
        </div>

        <!-- REPORT GENERATION FORM -->
        <div class="form-container">
            <h3 style="margin-bottom:20px; color:#333;">Generate Report</h3>
            <?php if ($report_message): ?><div class="message"><?php echo $report_message; ?></div><?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label>Report Type *</label>
                    <select name="report_type" required>
                        <option value="">-- Select --</option>
                        <option value="summary">Summary Report</option>
                        <option value="faculty">Faculty Workload</option>
                        <option value="student">Student Progress</option>
                        <option value="project">Project Status</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date From *</label>
                    <input type="date" name="date_from" required>
                </div>
                <div class="form-group">
                    <label>Date To *</label>
                    <input type="date" name="date_to" required>
                </div>
                <div class="form-group">
                    <label>Format</label>
                    <select name="format">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
                <button type="submit" name="generate_report" class="btn-submit">Generate Report</button>
            </form>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('projectStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'In Progress', 'Completed', 'Archived'],
                datasets: [{
                    data: [<?php echo $stats['pending_reviews']; ?>, <?php echo $stats['ongoing_projects']; ?>, <?php echo $stats['completed_projects']; ?>, <?php echo $stats['archived_count']; ?>],
                    backgroundColor: ['#ef9a9a', '#d32f2f', '#81c784', '#b71c1c'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, pointStyle: 'circle', color: '#333' } }
                },
                cutout: '70%'
            }
        });

        new Chart(document.getElementById('workloadChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($faculty_workload, 'name')); ?>,
                datasets: [{
                    label: 'Projects Supervised',
                    data: <?php echo json_encode(array_column($faculty_workload, 'projects')); ?>,
                    backgroundColor: '#d32f2f',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 10, grid: { color: 'rgba(183,28,28,0.05)' }, ticks: { color: '#333' } },
                    x: { ticks: { color: '#333' } }
                }
            }
        });
    </script>
</body>
</html>