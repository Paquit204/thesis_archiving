<?php
session_start();

// Check if the user is logged in and has role 'librarian'
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: ../login.php");
    exit;
}

// Get full name from session
$fullName = htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);

// Include data functions (temporary JSON-based)
require_once __DIR__ . '/data/functions.php';

$pending = getPendingTheses();
$archived = getArchivedTheses();
$notifications = getNotifications();

$pendingCount = count($pending);
$archivedCount = count($archived);
$totalTheses = $pendingCount + $archivedCount;
$unreadCount = count(array_filter($notifications, function($n) { return !$n['read']; }));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard | Thesis Archiving System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/librarianDashboard.css">
</head>
<body>
    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header"><h2>Thesis<span>Archive</span></h2></div>
            <nav class="sidebar-nav">
                <a href="librarianDashboard.php" class="nav-item active"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                <a href="archiveThesis.php" class="nav-item"><i class="fas fa-archive"></i><span>Archive Thesis</span></a>
                <a href="archivedTheses.php" class="nav-item"><i class="fas fa-folder-open"></i><span>Archived Theses</span></a>
                <a href="notification.php" class="nav-item"><i class="fas fa-bell"></i><span>Notifications</span><?php if ($unreadCount > 0): ?><span class="badge"><?php echo $unreadCount; ?></span><?php endif; ?></a>
                <a href="librarianProfile.php" class="nav-item"><i class="fas fa-user-circle"></i><span>Profile</span></a>
                <a href="logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <nav class="top-navbar">
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div class="navbar-title"><h1>Librarian Dashboard</h1></div>
                <div class="navbar-actions">
                    <div class="notification-icon">
                        <a href="notification.php"><i class="fas fa-bell"></i><?php if ($unreadCount > 0): ?><span class="notification-badge"><?php echo $unreadCount; ?></span><?php endif; ?></a>
                    </div>
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

            <div class="dashboard-container">
                <div class="welcome-banner">
                    <h2>Welcome, <?php echo $fullName; ?></h2>
                    <p>Here's an overview of your advising and review activities.</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card pending-card">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-info"><h3><?php echo $pendingCount; ?></h3><p>Pending Archive (from Dean)</p></div>
                    </div>
                    <div class="stat-card archived-card">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-info"><h3><?php echo $archivedCount; ?></h3><p>Archived</p></div>
                    </div>
                    <div class="stat-card total-card">
                        <div class="stat-icon"><i class="fas fa-book"></i></div>
                        <div class="stat-info"><h3><?php echo $totalTheses; ?></h3><p>Total Theses</p></div>
                    </div>
                </div>

                <div class="pending-table-section">
                    <div class="section-header"><h3><i class="fas fa-inbox"></i> Theses Forwarded by Dean</h3><span class="pending-count"><?php echo $pendingCount; ?> pending</span></div>
                    <div class="table-responsive">
                        <table class="theses-table">
                            <thead><tr><th>Title</th><th>Author</th><th>Date Submitted</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php if (empty($pending)): ?>
                                    <tr><td colspan="4" class="empty-row">No pending theses for archiving.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($pending as $thesis): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($thesis['title']); ?></td>
                                            <td><?php echo htmlspecialchars($thesis['author']); ?></td>
                                            <td><?php echo htmlspecialchars($thesis['date']); ?></td>
                                            <td><a href="archiveThesis.php?id=<?php echo $thesis['id']; ?>" class="btn-archive">Archive</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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