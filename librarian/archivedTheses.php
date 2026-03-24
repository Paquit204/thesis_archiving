<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header('Location: ../login.php');
    exit;
}

$fullName = htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);

require_once __DIR__ . '/data/functions.php';

$archived = getArchivedTheses();
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

if ($search) {
    $archived = array_filter($archived, function($thesis) use ($search) {
        return stripos($thesis['title'], $search) !== false || stripos($thesis['author'], $search) !== false;
    });
}
if ($filter && $filter !== 'all') {
    $archived = array_filter($archived, function($thesis) use ($filter) {
        return $thesis['year'] == $filter;
    });
}
$years = array_unique(array_column($archived, 'year'));
sort($years);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Theses | Thesis Archiving System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/archivedTheses.css">
</head>
<body>
    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header"><h2>Thesis<span>Archive</span></h2></div>
            <nav class="sidebar-nav">
                <a href="librarianDashboard.php" class="nav-item"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                <a href="archiveThesis.php" class="nav-item"><i class="fas fa-archive"></i><span>Archive Thesis</span></a>
                <a href="archivedTheses.php" class="nav-item active"><i class="fas fa-folder-open"></i><span>Archived Theses</span></a>
                <a href="notification.php" class="nav-item"><i class="fas fa-bell"></i><span>Notifications</span></a>
                <a href="librarianProfile.php" class="nav-item"><i class="fas fa-user-circle"></i><span>Profile</span></a>
                <a href="logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <nav class="top-navbar">
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div class="navbar-title"><h1>Archived Theses</h1></div>
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

            <div class="archived-container">
                <div class="search-filter-bar">
                    <form method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search); ?>">
                        <select name="filter">
                            <option value="all">All Years</option>
                            <?php foreach ($years as $year): ?>
                                <option value="<?php echo $year; ?>" <?php echo $filter == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit"><i class="fas fa-search"></i> Filter</button>
                        <a href="archivedTheses.php" class="reset-btn"><i class="fas fa-sync-alt"></i> Reset</a>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="archived-table">
                        <thead><tr><th>Title</th><th>Author</th><th>Date Archived</th><th>Year</th></tr></thead>
                        <tbody>
                            <?php if (empty($archived)): ?>
                                <tr><td colspan="4" class="empty-row">No archived theses found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($archived as $thesis): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($thesis['title']); ?></td>
                                        <td><?php echo htmlspecialchars($thesis['author']); ?></td>
                                        <td><?php echo htmlspecialchars($thesis['archived_date']); ?></td>
                                        <td><?php echo htmlspecialchars($thesis['year']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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