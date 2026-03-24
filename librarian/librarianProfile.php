<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header('Location: ../login.php');
    exit;
}

$fullName = htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);

require_once __DIR__ . '/data/functions.php';
$profile = getLibrarianProfile();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Thesis Archiving System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/librarianProfile.css">
</head>
<body>
    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header"><h2>Thesis<span>Archive</span></h2></div>
            <nav class="sidebar-nav">
                <a href="librarianDashboard.php" class="nav-item"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                <a href="archiveThesis.php" class="nav-item"><i class="fas fa-archive"></i><span>Archive Thesis</span></a>
                <a href="archivedTheses.php" class="nav-item"><i class="fas fa-folder-open"></i><span>Archived Theses</span></a>
                <a href="notification.php" class="nav-item"><i class="fas fa-bell"></i><span>Notifications</span></a>
                <a href="librarianProfile.php" class="nav-item active"><i class="fas fa-user-circle"></i><span>Profile</span></a>
                <a href="logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <nav class="top-navbar">
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div class="navbar-title"><h1>My Profile</h1></div>
                <div class="navbar-actions">
                    <div class="avatar-dropdown">
                        <div class="avatar" id="avatarBtn"><i class="fas fa-user-circle"></i><span><?php echo $fullName; ?></span><i class="fas fa-chevron-down"></i></div>
                        <div class="dropdown-menu" id="dropdownMenu">
                            <a href="librarianProfile.php"><i class="fas fa-user"></i> Profile</a>
                            <a href="librarianEditProfile.php"><i class="fas fa-edit"></i> Edit Profile</a>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="profile-container">
                <div class="profile-card">
                    <div class="profile-header">
                        <i class="fas fa-user-circle fa-4x"></i>
                        <h2><?php echo htmlspecialchars($profile['name']); ?></h2>
                        <p><?php echo htmlspecialchars($profile['role']); ?></p>
                    </div>
                    <div class="profile-details">
                        <div class="detail-row"><label>Email:</label><p><?php echo htmlspecialchars($profile['email']); ?></p></div>
                        <div class="detail-row"><label>Department:</label><p><?php echo htmlspecialchars($profile['department']); ?></p></div>
                        <div class="detail-row"><label>Office:</label><p><?php echo htmlspecialchars($profile['office']); ?></p></div>
                        <div class="detail-row"><label>Member Since:</label><p><?php echo htmlspecialchars($profile['member_since']); ?></p></div>
                    </div>
                    <div class="profile-actions"><a href="librarianEditProfile.php" class="btn-edit"><i class="fas fa-edit"></i> Edit Profile</a></div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        menuToggle.addEventListener('click', () => sidebar.classList.toggle('active'));
        const avatarBtn = document.getElementById('avatarBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        avatarBtn.addEventListener('click', (e) => { e.stopPropagation(); dropdownMenu.classList.toggle('show'); });
        document.addEventListener('click', () => dropdownMenu.classList.remove('show'));
    </script>
</body>
</html>