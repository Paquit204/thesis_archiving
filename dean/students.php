<?php
session_start();
$current_page = 'students';

$user_name = "Dr. Maria Santos";
$department = "College of Arts and Sciences";

$students = [
    ['id'=>1,'name'=>'Maria Santos','program'=>'BS CS','year'=>4,'project_title'=>'AI-Powered Thesis','adviser'=>'Prof. Dela Cruz','status'=>'ongoing'],
    ['id'=>2,'name'=>'Juan Dela Cruz','program'=>'BS IT','year'=>4,'project_title'=>'Mobile App','adviser'=>'Dr. Lopez','status'=>'ongoing'],
    ['id'=>3,'name'=>'Ana Lopez','program'=>'BS Math','year'=>4,'project_title'=>'E-Learning Platform','adviser'=>'Prof. Reyes','status'=>'completed'],
    ['id'=>4,'name'=>'Pedro Reyes','program'=>'BS Physics','year'=>4,'project_title'=>'IoT-Based Monitoring','adviser'=>'Dr. Garcia','status'=>'pending'],
    ['id'=>5,'name'=>'Lisa Garcia','program'=>'BS Chem','year'=>4,'project_title'=>'Blockchain Records','adviser'=>'Prof. Santiago','status'=>'archived'],
    ['id'=>6,'name'=>'Mark Santiago','program'=>'BS Biology','year'=>4,'project_title'=>'VR Campus Tour','adviser'=>'Dr. Villanueva','status'=>'ongoing'],
    ['id'=>7,'name'=>'Karen Villanueva','program'=>'BS Lit','year'=>4,'project_title'=>'Automated Grading','adviser'=>'Prof. Dela Cruz','status'=>'pending'],
];

$total = count($students);
$ongoing = count(array_filter($students, fn($s)=>$s['status']=='ongoing'));
$completed = count(array_filter($students, fn($s)=>$s['status']=='completed'));
$pending = count(array_filter($students, fn($s)=>$s['status']=='pending'));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students | Dean</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Copy all CSS from faculty.php (same) – omitted for brevity, but must be included in actual file */
        /* For brevity, use the exact same CSS block as in faculty.php */
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; } body { background:#f8f9fa; display:flex; height:100vh; overflow:hidden; } ::-webkit-scrollbar { width:8px; height:8px; } ::-webkit-scrollbar-track { background:#f1f1f1; } ::-webkit-scrollbar-thumb { background:#ef9a9a; border-radius:10px; } ::-webkit-scrollbar-thumb:hover { background:#d32f2f; } .sidebar { width:280px; background:linear-gradient(180deg,#b71c1c 0%,#d32f2f 50%,#e57373 100%); color:white; padding:25px 0; display:flex; flex-direction:column; overflow-y:auto; box-shadow:4px 0 20px rgba(211,47,47,0.3); } .logo-container { padding:0 25px; margin-bottom:40px; } .logo { font-size:24px; font-weight:800; background:linear-gradient(135deg,#ffcdd2 0%,#ffebee 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; letter-spacing:-0.5px; } .logo span { background:linear-gradient(135deg,#ffffff 0%,#ffebee 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; } .logo-sub { font-size:12px; color:#ffcdd2; margin-top:5px; letter-spacing:1px; } .nav-menu { flex:1; } .nav-item { display:flex; align-items:center; padding:12px 25px; margin:5px 10px; border-radius:12px; color:#ffebee; transition:all 0.3s ease; cursor:pointer; text-decoration:none; } .nav-item i { width:24px; font-size:1.2rem; margin-right:15px; } .nav-item span { font-size:0.95rem; font-weight:500; } .nav-item:hover { background:rgba(255,255,255,0.15); color:white; transform:translateX(5px); } .nav-item.active { background:#b71c1c; color:white; box-shadow:0 10px 20px rgba(183,28,28,0.4); } .nav-footer { padding:20px 25px; border-top:1px solid rgba(255,255,255,0.15); } .logout-btn { display:flex; align-items:center; color:#ffebee; text-decoration:none; padding:10px; border-radius:10px; transition:all 0.3s ease; } .logout-btn i { margin-right:10px; font-size:1.1rem; } .logout-btn:hover { background:rgba(211,47,47,0.5); color:white; transform:translateX(5px); } .main-content { flex:1; padding:25px 35px; overflow-y:auto; background:#f8f9fa; } .top-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:30px; background:white; padding:10px 20px; border-radius:20px; box-shadow:0 5px 15px rgba(211,47,47,0.08); border:1px solid #ffcdd2; } .search-area { display:flex; align-items:center; background:#f8f9fa; padding:10px 20px; border-radius:15px; width:350px; } .search-area i { color:#d32f2f; margin-right:10px; font-size:0.9rem; } .search-area input { border:none; background:transparent; outline:none; width:100%; font-size:0.95rem; color:#333; } .search-area input::placeholder { color:#999; } .user-profile { display:flex; align-items:center; gap:15px; } .notification-icon { position:relative; width:45px; height:45px; background:#f8f9fa; border-radius:12px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.3s ease; } .notification-icon:hover { background:#ffcdd2; } .notification-badge { position:absolute; top:-5px; right:-5px; background:#d32f2f; color:white; font-size:10px; padding:3px 6px; border-radius:10px; font-weight:600; } .user-info { display:flex; align-items:center; gap:15px; padding:5px 5px 5px 15px; background:#f8f9fa; border-radius:15px; cursor:pointer; transition:all 0.3s ease; } .user-info:hover { background:#ffcdd2; } .user-details { text-align:right; } .user-name { font-weight:600; color:#333; font-size:0.95rem; } .user-role { font-size:0.75rem; color:#666; text-transform:uppercase; letter-spacing:0.5px; } .avatar { width:45px; height:45px; background:#d32f2f; color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.2rem; box-shadow:0 5px 15px rgba(211,47,47,0.3); } .dept-banner { background:linear-gradient(135deg,#b71c1c 0%,#d32f2f 100%); border-radius:25px; padding:30px 35px; margin-bottom:30px; color:white; display:flex; justify-content:space-between; align-items:center; box-shadow:0 20px 30px rgba(211,47,47,0.2); } .dept-info h1 { font-size:2rem; font-weight:700; margin-bottom:5px; color:white; } .dept-info p { font-size:1rem; opacity:0.9; color:white; } .dean-info { text-align:right; } .dean-name { font-size:1.2rem; font-weight:600; margin-bottom:5px; color:white; } .dean-since { font-size:0.9rem; opacity:0.8; color:white; } .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:25px; margin-bottom:30px; } .stat-card { background:white; border-radius:20px; padding:25px; display:flex; align-items:center; gap:20px; box-shadow:0 5px 15px rgba(211,47,47,0.08); transition:all 0.3s ease; border:1px solid #ffcdd2; } .stat-card:hover { transform:translateY(-5px); box-shadow:0 15px 30px rgba(211,47,47,0.15); border-color:#d32f2f; } .stat-icon { width:60px; height:60px; background:#d32f2f; border-radius:15px; display:flex; align-items:center; justify-content:center; color:white; font-size:1.8rem; } .stat-details h3 { font-size:2rem; font-weight:700; color:#333; margin-bottom:5px; } .stat-details p { color:#666; font-size:0.9rem; font-weight:500; } .section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; } .section-title { font-size:1.3rem; font-weight:700; color:#333; } .view-all { color:#d32f2f; text-decoration:none; font-size:0.9rem; font-weight:600; display:flex; align-items:center; gap:5px; transition:all 0.3s ease; } .view-all:hover { gap:10px; color:#ef9a9a; } .table-responsive { overflow-x:auto; } table { width:100%; border-collapse:collapse; } th { text-align:left; padding:15px 10px; color:#666; font-weight:600; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px; border-bottom:2px solid #ffcdd2; } td { padding:15px 10px; border-bottom:1px solid #ffebee; color:#333; font-size:0.95rem; } .status-badge { padding:3px 8px; border-radius:12px; font-size:0.7rem; font-weight:600; } .status-badge.ongoing { background:#ffecb3; color:#b76e1c; } .status-badge.completed { background:#c8e6c9; color:#2e7d32; } .status-badge.pending { background:#ffcdd2; color:#b71c1c; } .status-badge.archived { background:#e0e0e0; color:#616161; } .btn-view { padding:6px 12px; border-radius:8px; font-size:0.85rem; text-decoration:none; font-weight:500; transition:all 0.3s ease; background:#ffebee; color:#333; display:inline-flex; align-items:center; gap:5px; } .btn-view:hover { transform:translateY(-2px); box-shadow:0 5px 10px rgba(211,47,47,0.2); background:#ffcdd2; } .projects-section { background:white; border-radius:20px; padding:25px; margin-bottom:30px; border:1px solid #ffcdd2; } @media (max-width:1200px) { .stats-grid { grid-template-columns:repeat(2,1fr); } } @media (max-width:768px) { body { flex-direction:column; height:auto; overflow:auto; } .sidebar { width:100%; height:auto; padding:15px; } .nav-menu { display:flex; flex-wrap:wrap; } .nav-item { flex:1 0 auto; margin:2px; } .main-content { padding:15px; } .top-bar { flex-direction:column; gap:10px; } .search-area { width:100%; } .stats-grid { grid-template-columns:1fr; } .dept-banner { flex-direction:column; text-align:center; gap:10px; } .dean-info { text-align:center; } } @media (max-width:480px) { .user-info .user-details { display:none; } .nav-item span { font-size:0.8rem; } .nav-item i { margin-right:5px; } }
    </style>
</head>
<body>
    <div class="sidebar"> <!-- same as faculty.php sidebar -->
        <div class="logo-container"><div class="logo">Thesis<span>Manager</span></div><div class="logo-sub">DEPARTMENT DEAN</div></div>
        <div class="nav-menu">
            <a href="dean.php" class="nav-item <?php echo $current_page=='dashboard'?'active':''; ?>"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
            <a href="faculty.php" class="nav-item <?php echo $current_page=='faculty'?'active':''; ?>"><i class="fas fa-users"></i><span>Faculty</span></a>
            <a href="students.php" class="nav-item <?php echo $current_page=='students'?'active':''; ?>"><i class="fas fa-user-graduate"></i><span>Students</span></a>
            <a href="projects.php" class="nav-item <?php echo $current_page=='projects'?'active':''; ?>"><i class="fas fa-project-diagram"></i><span>Projects</span></a>
            <a href="defenses.php" class="nav-item <?php echo $current_page=='defenses'?'active':''; ?>"><i class="fas fa-calendar-check"></i><span>Defenses</span></a>
            <a href="archive.php" class="nav-item <?php echo $current_page=='archive'?'active':''; ?>"><i class="fas fa-archive"></i><span>Archive</span></a>
            <a href="reports.php" class="nav-item <?php echo $current_page=='reports'?'active':''; ?>"><i class="fas fa-chart-bar"></i><span>Reports</span></a>
            <a href="settings.php" class="nav-item <?php echo $current_page=='settings'?'active':''; ?>"><i class="fas fa-cog"></i><span>Settings</span></a>
        </div>
        <div class="nav-footer"><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></div>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="search-area"><i class="fas fa-search"></i><input type="text" placeholder="Search students..."></div>
            <div class="user-profile">
                <div class="notification-icon"><i class="far fa-bell"></i><span class="notification-badge">2</span></div>
                <div class="user-info"><div class="user-details"><div class="user-name"><?php echo $user_name; ?></div><div class="user-role">DEAN</div></div><div class="avatar">MS</div></div>
            </div>
        </div>

        <div class="dept-banner">
            <div class="dept-info"><h1>Student Management</h1><p><?php echo $department; ?></p></div>
            <div class="dean-info"><div class="dean-name">Dr. Maria Santos</div><div class="dean-since">Dean</div></div>
        </div>

        <div class="stats-grid">
            <div class="stat-card"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-details"><h3><?php echo $total; ?></h3><p>Total Students</p></div></div>
            <div class="stat-card"><div class="stat-icon"><i class="fas fa-spinner"></i></div><div class="stat-details"><h3><?php echo $ongoing; ?></h3><p>Ongoing</p></div></div>
            <div class="stat-card"><div class="stat-icon"><i class="fas fa-check-circle"></i></div><div class="stat-details"><h3><?php echo $completed; ?></h3><p>Completed</p></div></div>
            <div class="stat-card"><div class="stat-icon"><i class="fas fa-clock"></i></div><div class="stat-details"><h3><?php echo $pending; ?></h3><p>Pending</p></div></div>
        </div>

        <div class="projects-section">
            <div class="section-header">
                <h2 class="section-title">All Students</h2>
                <a href="#" class="view-all">Export <i class="fas fa-download"></i></a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead><tr><th>Name</th><th>Program</th><th>Year</th><th>Project Title</th><th>Adviser</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach($students as $s): ?>
                        <tr>
                            <td><?php echo $s['name']; ?></td>
                            <td><?php echo $s['program']; ?></td>
                            <td><?php echo $s['year']; ?></td>
                            <td><?php echo $s['project_title']; ?></td>
                            <td><?php echo $s['adviser']; ?></td>
                            <td><span class="status-badge <?php echo $s['status']; ?>"><?php echo ucfirst($s['status']); ?></span></td>
                            <td><a href="#" class="btn-view"><i class="fas fa-eye"></i> View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>