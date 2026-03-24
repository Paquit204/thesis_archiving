<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header('Location: ../login.php');
    exit;
}

$fullName = htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);

require_once __DIR__ . '/data/functions.php';

$notifications = getNotifications();
if (isset($_GET['mark_read'])) {
    markNotificationRead((int)$_GET['mark_read']);
    header('Location: notification.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | Thesis Archiving System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/notification.css">
</head>
<body>
    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header"><h2>Thesis<span>Archive</span></h2></div>
            <nav class="sidebar-nav">
                <a href="librarianDashboard.php" class="nav-item"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                <a href="archiveThesis.php" class="nav-item"><i class="fas fa-archive"></i><span>Archive Thesis</span></a>
                <a href="archivedTheses.php" class="nav-item"><i class="fas fa-folder-open"></i><span>Archived Theses</span></a>
                <a href="notification.php" class="nav-item active"><i class="fas fa-bell"></i><span>Notifications</span></a>
                <a href="librarianProfile.php" class="nav-item"><i class="fas fa-user-circle"></i><span>Profile</span></a>
                <a href="logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <nav class="top-navbar">
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div class="navbar-title"><h1>Notifications</h1></div>
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

            <div class="notifications-container">
                <h2><i class="fas fa-bell"></i> Your Notifications</h2>
                <div class="notifications-list">
                    <?php if (empty($notifications)): ?>
                        <div class="empty-state"><i class="fas fa-inbox"></i><p>No notifications yet.</p></div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-card <?php echo $notification['read'] ? 'read' : 'unread'; ?>">
                                <div class="notification-icon"><i class="fas <?php echo $notification['icon'] ?? 'fa-info-circle'; ?>"></i></div>
                                <div class="notification-content"><p><?php echo htmlspecialchars($notification['message']); ?></p><small><?php echo htmlspecialchars($notification['date']); ?></small></div>
                                <?php if (!$notification['read']): ?><a href="notification.php?mark_read=<?php echo $notification['id']; ?>" class="mark-read-btn">Mark as read</a><?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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