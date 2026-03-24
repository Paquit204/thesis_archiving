<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header('Location: ../login.php');
    exit;
}

$fullName = htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);

require_once __DIR__ . '/data/functions.php';

$message = '';
$error = '';
$thesis = null;
$pending = getPendingTheses();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    foreach ($pending as $t) {
        if ($t['id'] === $id) {
            $thesis = $t;
            break;
        }
    }
    if (!$thesis) $error = 'Thesis not found or already archived.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive_id'])) {
    $archiveId = (int)$_POST['archive_id'];
    if (archiveThesis($archiveId)) {
        $message = 'Thesis archived successfully!';
        header('Refresh: 2; URL=librarianDashboard.php');
    } else {
        $error = 'Failed to archive thesis.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Thesis | Thesis Archiving System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/archiveThesis.css">
</head>
<body>
    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header"><h2>Thesis<span>Archive</span></h2></div>
            <nav class="sidebar-nav">
                <a href="librarianDashboard.php" class="nav-item"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                <a href="archiveThesis.php" class="nav-item active"><i class="fas fa-archive"></i><span>Archive Thesis</span></a>
                <a href="archivedTheses.php" class="nav-item"><i class="fas fa-folder-open"></i><span>Archived Theses</span></a>
                <a href="notification.php" class="nav-item"><i class="fas fa-bell"></i><span>Notifications</span></a>
                <a href="librarianProfile.php" class="nav-item"><i class="fas fa-user-circle"></i><span>Profile</span></a>
                <a href="logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <nav class="top-navbar">
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div class="navbar-title"><h1>Archive Thesis</h1></div>
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

            <div class="archive-container">
                <?php if ($message): ?><div class="alert success"><?php echo $message; ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>

                <?php if ($thesis): ?>
                    <div class="thesis-detail-card">
                        <h2><i class="fas fa-file-alt"></i> Thesis Details</h2>
                        <div class="detail-group"><label>Title:</label><p><?php echo htmlspecialchars($thesis['title']); ?></p></div>
                        <div class="detail-group"><label>Author:</label><p><?php echo htmlspecialchars($thesis['author']); ?></p></div>
                        <div class="detail-group"><label>Date Submitted:</label><p><?php echo htmlspecialchars($thesis['date']); ?></p></div>
                        <form method="POST" class="archive-form">
                            <input type="hidden" name="archive_id" value="<?php echo $thesis['id']; ?>">
                            <button type="submit" class="btn-archive-confirm"><i class="fas fa-archive"></i> Confirm Archive</button>
                            <a href="librarianDashboard.php" class="btn-cancel">Cancel</a>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="pending-list">
                        <h2><i class="fas fa-list"></i> Select a Thesis to Archive</h2>
                        <div class="theses-grid">
                            <?php if (empty($pending)): ?><p>No pending theses to archive.</p>
                            <?php else: ?>
                                <?php foreach ($pending as $p): ?>
                                    <div class="thesis-card">
                                        <h3><?php echo htmlspecialchars($p['title']); ?></h3>
                                        <p><strong>Author:</strong> <?php echo htmlspecialchars($p['author']); ?></p>
                                        <p><strong>Date:</strong> <?php echo htmlspecialchars($p['date']); ?></p>
                                        <a href="archiveThesis.php?id=<?php echo $p['id']; ?>" class="btn-view">View Details</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
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