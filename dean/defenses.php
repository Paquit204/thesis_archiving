<?php
session_start();
$current_page = 'defenses';

$user_name = "Dr. Maria Santos";
$department = "College of Arts and Sciences";

$upcoming_defenses = [
    ['id'=>1,'student'=>'Juan Dela Cruz','title'=>'Mobile App for Campus Navigation','date'=>'2024-04-20','time'=>'10:00 AM','panelists'=>'Dr. Ana Lopez, Prof. Pedro Reyes'],
    ['id'=>2,'student'=>'Mark Santiago','title'=>'Virtual Reality Campus Tour','date'=>'2024-05-10','time'=>'2:00 PM','panelists'=>'Dr. Karen Villanueva, Prof. Juan Dela Cruz'],
    ['id'=>3,'student'=>'Jose Rizal','title'=>'Data Mining for Student Performance','date'=>'2024-05-05','time'=>'1:30 PM','panelists'=>'Prof. Pedro Reyes, Dr. Lisa Garcia'],
    ['id'=>4,'student'=>'Gabriela Silang','title'=>'Mobile Learning App','date'=>'2024-05-15','time'=>'9:00 AM','panelists'=>'Dr. Lisa Garcia, Prof. Mark Santiago'],
];

$schedule_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_defense'])) {
    $student = trim($_POST['student'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $panelists = trim($_POST['panelists'] ?? '');
    if (empty($student) || empty($title) || empty($date) || empty($time) || empty($panelists)) {
        $schedule_message = '<span style="color: red;">All fields are required.</span>';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $schedule_message = '<span style="color: red;">Invalid date format.</span>';
    } else {
        $schedule_message = '<span style="color: green;">Defense scheduled (demo).</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Defense Management | ThesisManager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ===== BASE STYLES (same as projects.php) ===== */
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
        body { background:#f8f9fa; display:flex; height:100vh; overflow:hidden; }
        ::-webkit-scrollbar { width:8px; height:8px; }
        ::-webkit-scrollbar-track { background:#f1f1f1; }
        ::-webkit-scrollbar-thumb { background:#ef9a9a; border-radius:10px; }
        ::-webkit-scrollbar-thumb:hover { background:#d32f2f; }

        /* ===== SIDEBAR ===== */
        .sidebar { width:280px; background:linear-gradient(180deg,#b71c1c 0%,#d32f2f 50%,#e57373 100%); color:white; padding:25px 0; display:flex; flex-direction:column; overflow-y:auto; box-shadow:4px 0 20px rgba(211,47,47,0.3); }
        .logo-container { padding:0 25px; margin-bottom:40px; }
        .logo { font-size:24px; font-weight:800; background:linear-gradient(135deg,#ffcdd2 0%,#ffebee 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; letter-spacing:-0.5px; }
        .logo span { background:linear-gradient(135deg,#ffffff 0%,#ffebee 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .logo-sub { font-size:12px; color:#ffcdd2; margin-top:5px; letter-spacing:1px; }
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

        /* ===== MAIN CONTENT ===== */
        .main-content { flex:1; padding:25px 35px; overflow-y:auto; background:#f8f9fa; }

        /* ===== TOP BAR ===== */
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

        /* ===== BANNER ===== */
        .dept-banner { background:linear-gradient(135deg,#b71c1c 0%,#d32f2f 100%); border-radius:25px; padding:30px 35px; margin-bottom:30px; color:white; display:flex; justify-content:space-between; align-items:center; box-shadow:0 20px 30px rgba(211,47,47,0.2); }
        .dept-info h1 { font-size:2rem; font-weight:700; margin-bottom:5px; color:white; }
        .dept-info p { font-size:1rem; opacity:0.9; color:white; }
        .dean-info { text-align:right; }
        .dean-name { font-size:1.2rem; font-weight:600; margin-bottom:5px; color:white; }
        .dean-since { font-size:0.9rem; opacity:0.8; color:white; }

        /* ===== SECTION HEADER ===== */
        .section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .section-title { font-size:1.3rem; font-weight:700; color:#333; }

        /* ===== DEFENSES LIST ===== */
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

        /* ===== BUTTONS ===== */
        .btn-view { padding:6px 12px; border-radius:8px; font-size:0.85rem; text-decoration:none; font-weight:500; transition:all 0.3s ease; background:#ffebee; color:#333; display:inline-flex; align-items:center; gap:5px; }
        .btn-view:hover { transform:translateY(-2px); box-shadow:0 5px 10px rgba(211,47,47,0.2); background:#ffcdd2; }

        /* ===== FORM ===== */
        .form-container { background:white; border-radius:20px; padding:25px; border:1px solid #ffcdd2; }
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; margin-bottom:5px; font-weight:500; color:#333; }
        .form-group input, .form-group select, .form-group textarea { width:100%; padding:10px; border:1px solid #ffcdd2; border-radius:10px; font-size:0.95rem; }
        .form-group input:focus { outline:none; border-color:#d32f2f; }
        .btn-submit { background:#d32f2f; color:white; border:none; padding:12px 25px; border-radius:10px; font-weight:600; cursor:pointer; transition:all 0.3s ease; }
        .btn-submit:hover { background:#b71c1c; transform:translateY(-2px); }
        .message { margin:15px 0; padding:10px; border-radius:8px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width:768px) {
            body { flex-direction:column; height:auto; overflow:auto; }
            .sidebar { width:100%; height:auto; padding:15px; }
            .nav-menu { display:flex; flex-wrap:wrap; }
            .nav-item { flex:1 0 auto; margin:2px; }
            .main-content { padding:15px; }
            .top-bar { flex-direction:column; gap:10px; }
            .search-area { width:100%; }
            .dept-banner { flex-direction:column; text-align:center; gap:10px; }
            .dean-info { text-align:center; }
            .defense-item { flex-direction:column; align-items:flex-start; }
            .defense-date-box { align-self:flex-start; }
        }
        @media (max-width:480px) { .user-info .user-details { display:none; } .nav-item span { font-size:0.8rem; } .nav-item i { margin-right:5px; } }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
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

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOP BAR -->
        <div class="top-bar">
            <div class="search-area"><i class="fas fa-search"></i><input type="text" placeholder="Search defenses..."></div>
            <div class="user-profile">
                <div class="notification-icon"><i class="far fa-bell"></i><span class="notification-badge">2</span></div>
                <div class="user-info"><div class="user-details"><div class="user-name"><?php echo $user_name; ?></div><div class="user-role">DEAN</div></div><div class="avatar">MS</div></div>
            </div>
        </div>

        <!-- BANNER -->
        <div class="dept-banner">
            <div class="dept-info"><h1>Defense Management</h1><p><?php echo $department; ?></p></div>
            <div class="dean-info"><div class="dean-name">Dr. Maria Santos</div><div class="dean-since">Dean</div></div>
        </div>

        <!-- UPCOMING DEFENSES LIST -->
        <div class="defenses-section">
            <div class="section-header">
                <h2 class="section-title">Upcoming Defenses</h2>
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

        <!-- SCHEDULE DEFENSE FORM -->
        <div class="form-container">
            <h3 style="margin-bottom:20px; color:#333;">Schedule a Defense</h3>
            <?php if ($schedule_message): ?><div class="message"><?php echo $schedule_message; ?></div><?php endif; ?>
            <form method="post">
                <div class="form-group"><label>Student Name</label><input type="text" name="student" required></div>
                <div class="form-group"><label>Thesis Title</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Defense Date</label><input type="date" name="date" required></div>
                <div class="form-group"><label>Defense Time</label><input type="time" name="time" required></div>
                <div class="form-group"><label>Panelists (comma separated)</label><input type="text" name="panelists" required></div>
                <button type="submit" name="schedule_defense" class="btn-submit">Schedule Defense</button>
            </form>
        </div>
    </div>
</body>
</html>