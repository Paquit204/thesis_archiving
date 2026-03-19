<?php
session_start();
$current_page = 'faculty';

$user_name = "Admin User";
$institution = "University Administration";

$faculty_members = [
    ['id'=>1,'name'=>'Prof. Juan Dela Cruz','department'=>'Computer Science','specialization'=>'AI','projects_supervised'=>8,'status'=>'active','email'=>'juan.dc@univ.edu','phone'=>'123-4567'],
    ['id'=>2,'name'=>'Dr. Ana Lopez','department'=>'Mathematics','specialization'=>'Algebra','projects_supervised'=>6,'status'=>'active','email'=>'ana.lopez@univ.edu','phone'=>'123-4568'],
    ['id'=>3,'name'=>'Prof. Pedro Reyes','department'=>'Physics','specialization'=>'Quantum','projects_supervised'=>4,'status'=>'active','email'=>'pedro.reyes@univ.edu','phone'=>'123-4569'],
    ['id'=>4,'name'=>'Dr. Lisa Garcia','department'=>'Chemistry','specialization'=>'Organic','projects_supervised'=>5,'status'=>'on-leave','email'=>'lisa.garcia@univ.edu','phone'=>'123-4570'],
    ['id'=>5,'name'=>'Prof. Mark Santiago','department'=>'Biology','specialization'=>'Genetics','projects_supervised'=>7,'status'=>'active','email'=>'mark.santiago@univ.edu','phone'=>'123-4571'],
    ['id'=>6,'name'=>'Dr. Karen Villanueva','department'=>'Literature','specialization'=>'Modern Poetry','projects_supervised'=>3,'status'=>'active','email'=>'karen.v@univ.edu','phone'=>'123-4572'],
    ['id'=>7,'name'=>'Prof. Michael Sy','department'=>'History','specialization'=>'Asian History','projects_supervised'=>2,'status'=>'active','email'=>'michael.sy@univ.edu','phone'=>'123-4573'],
];

$total_faculty = count($faculty_members);
$active_faculty = count(array_filter($faculty_members, fn($f)=>$f['status']=='active'));
$on_leave = count(array_filter($faculty_members, fn($f)=>$f['status']=='on-leave'));
$avg_projects = array_sum(array_column($faculty_members,'projects_supervised'))/$total_faculty;

$add_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_faculty'])) {
    $name = trim($_POST['name']??'');
    $dept = trim($_POST['department']??'');
    $email = trim($_POST['email']??'');
    $phone = trim($_POST['phone']??'');
    if (empty($name) || empty($dept) || empty($email)) {
        $add_message = '<span style="color: red;">Name, department, and email are required.</span>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $add_message = '<span style="color: red;">Invalid email format.</span>';
    } else {
        $add_message = '<span style="color: green;">Faculty added (demo).</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Management | Admin</title>
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
        .status-badge { padding:3px 8px; border-radius:12px; font-size:0.7rem; font-weight:600; }
        .status-badge.active { background:#c8e6c9; color:#2e7d32; }
        .status-badge.on-leave { background:#ffecb3; color:#b76e1c; }
        .btn-view { padding:6px 12px; border-radius:8px; font-size:0.85rem; text-decoration:none; font-weight:500; transition:all 0.3s ease; background:#ffebee; color:#333; display:inline-flex; align-items:center; gap:5px; }
        .btn-view:hover { transform:translateY(-2px); box-shadow:0 5px 10px rgba(211,47,47,0.2); background:#ffcdd2; }

        .form-container { background:white; border-radius:20px; padding:25px; border:1px solid #ffcdd2; margin-top:25px; }
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; margin-bottom:5px; font-weight:500; color:#333; }
        .form-group input, .form-group select, .form-group textarea { width:100%; padding:10px; border:1px solid #ffcdd2; border-radius:10px; font-size:0.95rem; }
        .form-group input:focus { outline:none; border-color:#d32f2f; }
        .btn-submit { background:#d32f2f; color:white; border:none; padding:12px 25px; border-radius:10px; font-weight:600; cursor:pointer; transition:all 0.3s ease; }
        .btn-submit:hover { background:#b71c1c; transform:translateY(-2px); }
        .message { margin:15px 0; padding:10px; border-radius:8px; }

        @media (max-width:1200px) { .stats-grid { grid-template-columns:repeat(2,1fr); } }
        @media (max-width:768px) {
            body { flex-direction:column; height:auto; overflow:auto; }
            .sidebar { width:100%; height:auto; padding:15px; }
            .nav-menu { display:flex; flex-wrap:wrap; }
            .nav-item { flex:1 0 auto; margin:2px; }
            .main-content { padding:15px; }
            .top-bar { flex-direction:column; gap:10px; }
            .search-area { width:100%; }
            .stats-grid { grid-template-columns:1fr; }
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
            <div class="search-area"><i class="fas fa-search"></i><input type="text" placeholder="Search faculty..."></div>
            <div class="user-profile">
                <div class="notification-icon"><i class="far fa-bell"></i><span class="notification-badge">3</span></div>
                <div class="user-info"><div class="user-details"><div class="user-name"><?php echo $user_name; ?></div><div class="user-role">ADMIN</div></div><div class="avatar">AD</div></div>
            </div>
        </div>

        <!-- BANNER -->
        <div class="dept-banner">
            <div class="dept-info"><h1>Faculty Management</h1><p><?php echo $institution; ?></p></div>
            <div class="dean-info"><div class="dean-name">Admin User</div><div class="dean-since">System Administrator</div></div>
        </div>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-details"><h3><?php echo $total_faculty; ?></h3><p>Total Faculty</p></div></div>
            <div class="stat-card"><div class="stat-icon"><i class="fas fa-user-check"></i></div><div class="stat-details"><h3><?php echo $active_faculty; ?></h3><p>Active</p></div></div>
            <div class="stat-card"><div class="stat-icon"><i class="fas fa-user-clock"></i></div><div class="stat-details"><h3><?php echo $on_leave; ?></h3><p>On Leave</p></div></div>
            <div class="stat-card"><div class="stat-icon"><i class="fas fa-chart-line"></i></div><div class="stat-details"><h3><?php echo number_format($avg_projects,1); ?></h3><p>Avg Projects</p></div></div>
        </div>

        <!-- FACULTY TABLE -->
        <div class="projects-section">
            <div class="section-header">
                <h2 class="section-title">All Faculty Members</h2>
                <a href="#" class="view-all" onclick="document.getElementById('addForm').scrollIntoView(); return false;"><i class="fas fa-plus"></i> Add New</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead><tr><th>Name</th><th>Department</th><th>Specialization</th><th>Email</th><th>Phone</th><th>Projects</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach($faculty_members as $f): ?>
                        <tr>
                            <td><?php echo $f['name']; ?></td>
                            <td><?php echo $f['department']; ?></td>
                            <td><?php echo $f['specialization']; ?></td>
                            <td><?php echo $f['email']; ?></td>
                            <td><?php echo $f['phone']; ?></td>
                            <td><?php echo $f['projects_supervised']; ?></td>
                            <td><span class="status-badge <?php echo $f['status']; ?>"><?php echo ucfirst($f['status']); ?></span></td>
                            <td><a href="#" class="btn-view"><i class="fas fa-eye"></i> View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ADD FACULTY FORM -->
        <div id="addForm" class="form-container">
            <h3 style="margin-bottom:20px; color:#333;">Add New Faculty</h3>
            <?php if ($add_message): ?><div class="message"><?php echo $add_message; ?></div><?php endif; ?>
            <form method="post">
                <div class="form-group"><label>Full Name *</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Department *</label><input type="text" name="department" required></div>
                <div class="form-group"><label>Specialization</label><input type="text" name="specialization"></div>
                <div class="form-group"><label>Email *</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Phone</label><input type="text" name="phone"></div>
                <button type="submit" name="add_faculty" class="btn-submit">Add Faculty</button>
            </form>
        </div>
    </div>
</body>
</html>