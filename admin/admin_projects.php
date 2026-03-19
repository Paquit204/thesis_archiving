<?php
session_start();
$current_page = 'projects';

$user_name = "Admin User";
$institution = "University Administration";

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

$status_counts = [
    'pending' => count(array_filter($projects, fn($p)=>$p['status']=='pending')),
    'in-progress' => count(array_filter($projects, fn($p)=>$p['status']=='in-progress')),
    'completed' => count(array_filter($projects, fn($p)=>$p['status']=='completed')),
    'archived' => count(array_filter($projects, fn($p)=>$p['status']=='archived')),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Projects | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

        .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:25px; margin-bottom:30px; }
        .stat-card { background:white; border-radius:20px; padding:25px; display:flex; align-items:center; gap:20px; box-shadow:0 5px 15px rgba(211,47,47,0.08); transition:all 0.3s ease; border:1px solid #ffcdd2; }
        .stat-card:hover { transform:translateY(-5px); box-shadow:0 15px 30px rgba(211,47,47,0.15); border-color:#d32f2f; }
        .stat-icon { width:60px; height:60px; background:#d32f2f; border-radius:15px; display:flex; align-items:center; justify-content:center; color:white; font-size:1.8rem; }
        .stat-icon.secondary { background:#ef9a9a; }
        .stat-details h3 { font-size:2rem; font-weight:700; color:#333; margin-bottom:5px; }
        .stat-details p { color:#666; font-size:0.9rem; font-weight:500; }

        .section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .section-title { font-size:1.3rem; font-weight:700; color:#333; }
        .view-all { color:#d32f2f; text-decoration:none; font-size:0.9rem; font-weight:600; display:flex; align-items:center; gap:5px; transition:all 0.3s ease; }
        .view-all:hover { gap:10px; color:#ef9a9a; }

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

        .stats-mini-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:30px; }
        .stat-mini-card { background:white; border-radius:15px; padding:20px; text-align:center; border:1px solid #ffcdd2; transition:all 0.3s ease; }
        .stat-mini-card:hover { transform:translateY(-3px); box-shadow:0 10px 20px rgba(211,47,47,0.1); border-color:#d32f2f; }
        .stat-mini-value { font-size:2.5rem; font-weight:700; color:#d32f2f; line-height:1.2; }
        .stat-mini-label { color:#666; font-size:1rem; font-weight:500; }

        select { padding:8px 15px; border:1px solid #ffcdd2; border-radius:10px; outline:none; font-size:0.9rem; color:#333; background:#f8f9fa; }

        /* Responsive */
        @media (max-width:1200px) { .stats-grid, .stats-mini-grid { grid-template-columns:repeat(2,1fr); } }
        @media (max-width:768px) {
            body { flex-direction:column; height:auto; overflow:auto; }
            .sidebar { width:100%; height:auto; padding:15px; }
            .nav-menu { display:flex; flex-wrap:wrap; }
            .nav-item { flex:1 0 auto; margin:2px; }
            .main-content { padding:15px; }
            .top-bar { flex-direction:column; gap:10px; }
            .search-area { width:100%; }
            .stats-grid, .stats-mini-grid { grid-template-columns:1fr; }
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
            <div class="search-area"><i class="fas fa-search"></i><input type="text" placeholder="Search projects..."></div>
            <div class="user-profile">
                <div class="notification-icon"><i class="far fa-bell"></i><span class="notification-badge">3</span></div>
                <div class="user-info"><div class="user-details"><div class="user-name"><?php echo $user_name; ?></div><div class="user-role">ADMIN</div></div><div class="avatar">AD</div></div>
            </div>
        </div>

        <!-- BANNER -->
        <div class="dept-banner">
            <div class="dept-info"><h1>All Projects</h1><p><?php echo $institution; ?></p></div>
            <div class="dean-info"><div class="dean-name">Admin User</div><div class="dean-since">System Administrator</div></div>
        </div>

        <!-- STATS MINI CARDS (like on reports page) -->
        <div class="stats-mini-grid">
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $status_counts['pending']; ?></div><div class="stat-mini-label">Pending</div></div>
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $status_counts['in-progress']; ?></div><div class="stat-mini-label">In Progress</div></div>
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $status_counts['completed']; ?></div><div class="stat-mini-label">Completed</div></div>
            <div class="stat-mini-card"><div class="stat-mini-value"><?php echo $status_counts['archived']; ?></div><div class="stat-mini-label">Archived</div></div>
        </div>

        <!-- PROJECTS TABLE -->
        <div class="projects-section">
            <div class="section-header">
                <h2 class="section-title">All Projects</h2>
                <div>
                    <select onchange="filterStatus(this.value)">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="projectsTable">
                    <thead><tr><th>Title</th><th>Student</th><th>Adviser</th><th>Department</th><th>Submitted</th><th>Defense Date</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach($projects as $p): ?>
                        <tr data-status="<?php echo $p['status']; ?>">
                            <td><?php echo $p['title']; ?></td>
                            <td><?php echo $p['student']; ?></td>
                            <td><?php echo $p['adviser']; ?></td>
                            <td><?php echo $p['dept']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($p['submitted'])); ?></td>
                            <td><?php echo $p['defense_date'] ? date('M d, Y', strtotime($p['defense_date'])) : 'Not scheduled'; ?></td>
                            <td>
                                <div class="status">
                                    <span class="status-dot <?php echo $p['status']; ?>"></span>
                                    <span class="status-text"><?php echo ucfirst(str_replace('-',' ',$p['status'])); ?></span>
                                </div>
                            </td>
                            <td><a href="#" class="btn-view"><i class="fas fa-eye"></i> View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterStatus(status) {
            const rows = document.querySelectorAll('#projectsTable tbody tr');
            rows.forEach(row => {
                if (!status || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>