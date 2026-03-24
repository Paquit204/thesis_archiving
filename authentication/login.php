<?php
session_start();
include("../config/db.php");

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = "Username and password are required.";
        $message_type = "error";
    } else {

        $stmt = $conn->prepare("
            SELECT user_id, role_id, first_name, last_name, username, password, status
            FROM user_table
            WHERE username = ? OR email = ?
            LIMIT 1
        ");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            $status = (string)($row['status'] ?? 'Pending');
            if ($status !== "Active") {
                $message = "Your account is inactive/pending. Contact admin.";
                $message_type = "error";
            } else {

                if (password_verify($password, $row['password'])) {

                    $_SESSION['user_id'] = (int)$row['user_id'];
                    $_SESSION['role_id'] = (int)$row['role_id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name']  = $row['last_name'];
                    $_SESSION['login_time'] = date('Y-m-d H:i:s');
                    
                    // Set role in session for easy access
                    if ($row['role_id'] == 1) $_SESSION['role'] = 'admin';
                    elseif ($row['role_id'] == 2) $_SESSION['role'] = 'student';
                    elseif ($row['role_id'] == 3) $_SESSION['role'] = 'faculty';
                    elseif ($row['role_id'] == 4) $_SESSION['role'] = 'coordinator';
                    elseif ($row['role_id'] == 5) $_SESSION['role'] = 'dean';
                    elseif ($row['role_id'] == 6) $_SESSION['role'] = 'librarian';
                    $message = "✓ Login successful! Redirecting...";
                    $message_type = "success";

                    if ((int)$row['role_id'] === 1) {
                        $redirect = "/thesis_archiving/admin/admin_dashboard.php";
                    } elseif ((int)$row['role_id'] === 2) {
                        $redirect = "/thesis_archiving/student/student_dashboard.php"; 
                    } elseif ((int)$row['role_id'] === 3) {
                        $redirect = "/thesis_archiving/faculty/facultyDashboard.php"; 
                    } elseif ((int)$row['role_id'] === 4) {
                        $redirect = "/thesis_archiving/coordinator/coordinatorDashboard.php";  
                    }elseif ((int)$row['role_id'] === 5) {
                     $redirect = "/thesis_archiving/dean/deanDashboard.php";
                    }elseif ((int)$row['role_id'] === 6) {                   
                    $redirect = "/thesis_archiving/librarian/librarianDashboard.php";
                    }

                    header("Location: $redirect");
                    exit;

                } else {
                    $message = "Invalid username/email or password.";
                    $message_type = "error";
                }
            }

        } else {
            $message = "Invalid username/email or password.";
            $message_type = "error";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Thesis Archiving System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f5f5;
            color: #000000;
            line-height: 1.6;
        }

        body.dark-mode {
            background: #2d2d2d;
            color: #e0e0e0;
        }
        .navbar {
            background: linear-gradient(135deg, #FE4853 0%, #732529 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .logo .material-symbols-outlined {
            font-size: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            list-style: none;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            opacity: 0.9;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255, 255, 255, 0.2);
            opacity: 1;
        }

        .nav-links a.active {
            background: rgba(255, 255, 255, 0.3);
            font-weight: 600;
        }

        /* Container */
        .container {
            max-width: 460px;
            margin: 4rem auto 6rem;
            padding: 0 1.5rem;
        }

        /* Login Card - red themed */
        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(110, 110, 110, 0.1);
            border-left: 4px solid #FE4853;
        }

        body.dark-mode .login-container {
            background: #3a3a3a;
            color: #e0e0e0;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-icon {
            font-size: 3rem;
            color: #FE4853;
            margin-bottom: 1rem;
        }

        .login-header h2 {
            color: #732529;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        body.dark-mode .login-header h2 {
            color: #FE4853;
        }

        .login-header p {
            color: #6E6E6E;
            font-size: 1rem;
        }

        body.dark-mode .login-header p {
            color: #e0e0e0;
        }

        /* Message */
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        body.dark-mode .message.success {
            background: #1a3a2a;
            color: #86efac;
        }

        body.dark-mode .message.error {
            background: #3a1a1a;
            color: #fca5a5;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #732529;
            font-size: 0.95rem;
        }

        body.dark-mode .form-group label {
            color: #FE4853;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background: white;
        }

        body.dark-mode .input-wrapper input {
            background: #4a4a4a;
            border-color: #6E6E6E;
            color: #e0e0e0;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #FE4853;
            box-shadow: 0 0 0 3px rgba(254, 72, 83, 0.1);
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #FE4853;
            font-size: 1.2rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6E6E6E;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #FE4853;
        }

        /* Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0 1.5rem;
            font-size: 0.95rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6E6E6E;
        }

        body.dark-mode .checkbox-label {
            color: #e0e0e0;
        }

        .forgot-link {
            color: #FE4853;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #732529;
            text-decoration: underline;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: #FE4853;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(254, 72, 83, 0.2);
        }

        .btn-login:hover {
            background: #732529;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(254, 72, 83, 0.3);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #6E6E6E;
            margin: 2rem 0 1.5rem;
            font-size: 0.95rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        body.dark-mode .divider::before,
        body.dark-mode .divider::after {
            background: #6E6E6E;
        }

        /* Quick Login Roles - optional, pwedeng tanggalin */
        .role-selection {
            margin-bottom: 1.5rem;
        }

        .role-title {
            color: #732529;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            text-align: center;
            font-weight: 500;
        }

        body.dark-mode .role-title {
            color: #FE4853;
        }

        .role-buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.8rem;
        }

        .role-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            color: #6E6E6E;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.95rem;
            width: 100%;
        }

        body.dark-mode .role-btn {
            background: #4a4a4a;
            border-color: #6E6E6E;
            color: #e0e0e0;
        }

        .role-btn:hover {
            border-color: #FE4853;
            color: #FE4853;
            transform: translateY(-2px);
        }

        .role-icon {
            font-size: 1.2rem;
            color: #FE4853;
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
            color: #6E6E6E;
        }

        body.dark-mode .register-link {
            color: #e0e0e0;
        }

        .register-link a {
            color: #FE4853;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: #732529;
            text-decoration: underline;
        }

        /* Dark mode toggle */
        .theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: #FE4853;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(254, 72, 83, 0.3);
            z-index: 1000;
            transition: all 0.3s;
        }

        .theme-toggle:hover {
            transform: scale(1.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                width: 100%;
                justify-content: flex-start;
            }

            .container {
                margin: 2rem auto 4rem;
                padding: 0 1rem;
            }

            .login-container {
                padding: 2rem;
            }

            .login-header h2 {
                font-size: 1.5rem;
            }

            .role-buttons {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }
            
            .form-options {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Dark Mode Toggle -->
    <div class="theme-toggle" id="themeToggle">
        <i class="material-symbols-outlined">dark_mode</i>
    </div>

    <nav class="navbar">
        <div class="nav-container">
            <a href="homepage.php" class="logo">
                <span class="material-symbols-outlined">book</span>
                Web-Based Thesis Archiving System
            </a>
            <ul class="nav-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="browse.php">Browse Thesis</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="login.php" class="active">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <span class="material-symbols-outlined login-icon">lock</span>
                <h2>Login to Your Account</h2>
                <p>Enter your credentials to access the system</p>
            </div>

            <?php if (!empty($message)) : ?>
                <div class="message <?php echo $message_type; ?>">
                    <span class="message-icon">
                        <?php echo ($message_type === 'success') ? '✓' : '✕'; ?>
                    </span>
                    <span class="message-text"><?php echo htmlspecialchars($message); ?></span>
                </div>
            <?php endif; ?>

            <form class="login-form" id="loginForm" method="post">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon">person</span>
                        <input type="text" id="username" name="username" placeholder="Enter your username or email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon">lock</span>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <span class="material-symbols-outlined password-toggle" id="login-toggle">visibility_off</span>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login">Login</button>

                <div class="divider">
                    <span>OR</span>
                </div>

                <!-- Optional: Quick Login Buttons - pwedeng tanggalin kung ayaw -->
                <div class="role-selection">
                    <p class="role-title">Quick Login As:</p>
                    <div class="role-buttons">
                        <button type="button" class="role-btn" onclick="quickLogin('student')">
                            <span class="material-symbols-outlined role-icon">school</span>
                            <span>Student</span>
                        </button>
                        <button type="button" class="role-btn" onclick="quickLogin('faculty')">
                            <span class="material-symbols-outlined role-icon">person</span>
                            <span>Faculty</span>
                        </button>
                        <button type="button" class="role-btn" onclick="quickLogin('dean')">
                            <span class="material-symbols-outlined role-icon">account_balance</span>
                            <span>Dean</span>
                        </button>
                        <button type="button" class="role-btn" onclick="quickLogin('admin')">
                            <span class="material-symbols-outlined role-icon">settings</span>
                            <span>Admin</span>
                        </button>
                    </div>
                </div>

                <div class="register-link">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Quick login function (optional)
        function quickLogin(role) {
            switch(role) {
                case 'student':
                    // Set student credentials
                    document.getElementById('username').value = 'student';
                    document.getElementById('password').value = 'student123';
                    break;
                case 'faculty':
                    document.getElementById('username').value = 'faculty';
                    document.getElementById('password').value = 'faculty123';
                    break;
                case 'dean':
                    document.getElementById('username').value = 'dean';
                    document.getElementById('password').value = 'dean123';
                    break;
                case 'admin':
                    document.getElementById('username').value = 'admin';
                    document.getElementById('password').value = 'admin123';
                    break;
            }
        }

        // Password toggle
        const loginToggle = document.getElementById('login-toggle');
        const loginPass = document.getElementById('password');

        if (loginToggle && loginPass) {
            loginToggle.addEventListener('click', () => {
                if (loginPass.type === 'password') {
                    loginPass.type = 'text';
                    loginToggle.textContent = 'visibility';
                } else {
                    loginPass.type = 'password';
                    loginToggle.textContent = 'visibility_off';
                }
            });
        }

        // Dark mode toggle
        const toggle = document.getElementById('themeToggle');
        if (toggle) {
            toggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
                const icon = toggle.querySelector('i');
                if (document.body.classList.contains('dark-mode')) {
                    icon.textContent = 'light_mode';
                    localStorage.setItem('darkMode', 'true');
                } else {
                    icon.textContent = 'dark_mode';
                    localStorage.setItem('darkMode', 'false');
                }
            });
            
            if (localStorage.getItem('darkMode') === 'true') {
                document.body.classList.add('dark-mode');
                toggle.querySelector('i').textContent = 'light_mode';
            }
        }
    </script>
</body>
</html>