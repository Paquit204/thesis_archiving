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
$profile = getLibrarianProfile();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $office = trim($_POST['office']);
    
    if (empty($name) || empty($email)) {
        $error = 'Name and email are required.';
    } else {
        $updated = updateLibrarianProfile([
            'name' => $name,
            'email' => $email,
            'department' => $department,
            'office' => $office,
            'role' => $profile['role'],
            'member_since' => $profile['member_since']
        ]);
        if ($updated) {
            // Optionally update session name (first/last) but for simplicity we keep as is
            $_SESSION['librarian_name'] = $name; // kept for compatibility
            $message = 'Profile updated successfully!';
            $profile = getLibrarianProfile();
        } else {
            $error = 'Failed to update profile.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Thesis Archiving System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/librarianEditProfile.css">
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
                <a href="librarianProfile.php" class="nav-item"><i class="fas fa-user-circle"></i><span>Profile</span></a>
                <a href="logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <nav class="top-navbar">
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div class="navbar-title"><h1>Edit Profile</h1></div>
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

            <div class="edit-profile-container">
                <div class="edit-card">
                    <h2><i class="fas fa-user-edit"></i> Edit Your Information</h2>
                    <?php if ($message): ?><div class="alert success"><?php echo $message; ?></div><?php endif; ?>
                    <?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>
                    <form method="POST" class="edit-form">
                        <div class="form-group"><label for="name">Full Name</label><input type="text" id="name" name="name" value="<?php echo htmlspecialchars($profile['name']); ?>" required></div>
                        <div class="form-group"><label for="email">Email Address</label><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($profile['email']); ?>" required></div>
                        <div class="form-group"><label for="department">Department</label><input type="text" id="department" name="department" value="<?php echo htmlspecialchars($profile['department']); ?>"></div>
                        <div class="form-group"><label for="office">Office</label><input type="text" id="office" name="office" value="<?php echo htmlspecialchars($profile['office']); ?>"></div>
                        <div class="form-actions"><button type="submit" class="btn-save"><i class="fas fa-save"></i> Save Changes</button><a href="librarianProfile.php" class="btn-cancel">Cancel</a></div>
                    </form>
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