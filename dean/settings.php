<?php
session_start();
$current_page = 'settings';

$user_name = "Dr. Maria Santos";
$user_email = "maria.santos@dean.cas.edu";
$department = "College of Arts and Sciences";
$dean_since = "2022-06-15";

$settings_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    $dept_name = trim($_POST['dept_name'] ?? '');
    $dean_name = trim($_POST['dean_name'] ?? '');
    $dean_email = trim($_POST['dean_email'] ?? '');
    $office_phone = trim($_POST['office_phone'] ?? '');
    $office_location = trim($_POST['office_location'] ?? '');

    if (empty($dept_name) || empty($dean_name) || empty($dean_email)) {
        $settings_message = '<span style="color: red;">Department name, dean name, and email are required.</span>';
    } elseif (!filter_var($dean_email, FILTER_VALIDATE_EMAIL)) {
        $settings_message = '<span style="color: red;">Invalid email format.</span>';
    } else {
        $settings_message = '<span style="color: green;">Settings updated successfully (demo).</span>';
    }
}

$password_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($current) || empty($new) || empty($confirm)) {
        $password_message = '<span style="color: red;">All password fields are required.</span>';
    } elseif ($new !== $confirm) {
        $password_message = '<span style="color: red;">New password and confirmation do not match.</span>';
    } elseif (strlen($new) < 6) {
        $password_message = '<span style="color: red;">Password must be at least 6 characters.</span>';
    } else {
        $password_message = '<span style="color: green;">Password changed (demo).</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | ThesisManager</title>
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

        /* ===== SETTINGS GRID ===== */
        .settings-grid { display:grid; grid-template-columns:1fr 1fr; gap:25px; margin-bottom:25px; }

        /* ===== FORM CONTAINERS ===== */
        .form-container { background:white; border-radius:20px; padding:25px; border:1px solid #ffcdd2; }
        .form-container h3 { margin-bottom:20px; color:#333; }

        .form-group { margin-bottom:15px; }
        .form-group label { display:block; margin-bottom:5px; font-weight:500; color:#333; }
        .form-group input { width:100%; padding:10px; border:1px solid #ffcdd2; border-radius:10px; font-size:0.95rem; }
        .form-group input:focus { outline:none; border-color:#d32f2f; }

        .btn-submit { background:#d32f2f; color:white; border:none; padding:12px 25px; border-radius:10px; font-weight:600; cursor:pointer; transition:all 0.3s ease; }
        .btn-submit:hover { background:#b71c1c; transform:translateY(-2px); }

        .message { margin:15px 0; padding:10px; border-radius:8px; }

        /* ===== CHECKBOX GROUP ===== */
        .checkbox-group { display:flex; flex-direction:column; gap:12px; margin:15px 0; }
        .checkbox-group label { display:flex; align-items:center; gap:8px; font-weight:400; color:#333; cursor:pointer; }
        .checkbox-group input[type="checkbox"] { width:16px; height:16px; accent-color:#d32f2f; }

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
            .settings-grid { grid-template-columns:1fr; }
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
            <div class="search-area"><i class="fas fa-search"></i><input type="text" placeholder="Search settings..."></div>
            <div class="user-profile">
                <div class="notification-icon"><i class="far fa-bell"></i><span class="notification-badge">1</span></div>
                <div class="user-info"><div class="user-details"><div class="user-name"><?php echo $user_name; ?></div><div class="user-role">DEAN</div></div><div class="avatar">MS</div></div>
            </div>
        </div>

        <!-- BANNER -->
        <div class="dept-banner">
            <div class="dept-info"><h1>Department Settings</h1><p>Manage your department profile and account</p></div>
            <div class="dean-info"><div class="dean-name">Dr. Maria Santos</div><div class="dean-since">Dean</div></div>
        </div>

        <!-- SETTINGS GRID -->
        <div class="settings-grid">
            <!-- DEPARTMENT INFORMATION -->
            <div class="form-container">
                <h3>Department Information</h3>
                <?php if ($settings_message): ?><div class="message"><?php echo $settings_message; ?></div><?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label>Department Name *</label>
                        <input type="text" name="dept_name" value="<?php echo htmlspecialchars($department); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Dean's Full Name *</label>
                        <input type="text" name="dean_name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="dean_email" value="<?php echo htmlspecialchars($user_email); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Office Phone</label>
                        <input type="text" name="office_phone" value="(02) 1234-5678">
                    </div>
                    <div class="form-group">
                        <label>Office Location</label>
                        <input type="text" name="office_location" value="Room 201, Arts & Sciences Building">
                    </div>
                    <button type="submit" name="update_settings" class="btn-submit">Update Information</button>
                </form>
            </div>

            <!-- CHANGE PASSWORD -->
            <div class="form-container">
                <h3>Change Password</h3>
                <?php if ($password_message): ?><div class="message"><?php echo $password_message; ?></div><?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn-submit">Change Password</button>
                </form>
            </div>
        </div>

        <!-- NOTIFICATION PREFERENCES -->
        <div class="form-container">
            <h3>Notification Preferences</h3>
            <div class="checkbox-group">
                <label><input type="checkbox" checked> Email me when a defense is scheduled</label>
                <label><input type="checkbox" checked> Email me when a thesis is submitted</label>
                <label><input type="checkbox"> Weekly summary report</label>
            </div>
            <button class="btn-submit" onclick="alert('Preferences saved (demo).');">Save Preferences</button>
        </div>
    </div>
</body>
</html>