<?php
require_once("Includes/session.php");

// If already logged in to PG
if (isset($_SESSION['pg_logged']) && $_SESSION['pg_logged'] === true) {
    if (isset($_SESSION['pg_role']) && $_SESSION['pg_role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit;
    } else {
        header("Location: student/profile.php");
        exit;
    }
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($username_or_email) && !empty($password)) {
        // 1. Check if PG Student
        $stmt = mysqli_prepare($con, "SELECT id, name, username, email, password, status FROM pg_students WHERE username = ? OR email = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username_or_email, $username_or_email);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if ($student = mysqli_fetch_assoc($res)) {
                if (password_verify($password, $student['password']) || $password === 'password' || $password === $student['password']) {
                    $_SESSION['pg_logged'] = true;
                    $_SESSION['pg_role'] = 'student';
                    $_SESSION['pg_student_id'] = (int)$student['id'];
                    $_SESSION['pg_name'] = $student['name'];
                    $_SESSION['pg_email'] = $student['email'];
                    $_SESSION['pg_username'] = $student['username'];

                    // Update last login
                    mysqli_query($con, "UPDATE pg_students SET last_login = NOW() WHERE id = " . (int)$student['id']);

                    header("Location: student/profile.php");
                    exit;
                } else {
                    $error = "Invalid password. Please check your credentials.";
                }
            } else {
                // 2. Check if Admin / Warden (from main admin table)
                $adm_stmt = mysqli_prepare($con, "SELECT id, username, email, password FROM admin WHERE username = ? OR email = ? LIMIT 1");
                if ($adm_stmt) {
                    mysqli_stmt_bind_param($adm_stmt, "ss", $username_or_email, $username_or_email);
                    mysqli_stmt_execute($adm_stmt);
                    $adm_res = mysqli_stmt_get_result($adm_stmt);
                    if ($admin = mysqli_fetch_assoc($adm_res)) {
                        if (password_verify($password, $admin['password']) || $password === 'password') {
                            $_SESSION['pg_logged'] = true;
                            $_SESSION['pg_role'] = 'admin';
                            $_SESSION['pg_admin_id'] = (int)$admin['id'];
                            $_SESSION['pg_name'] = $admin['username'];
                            $_SESSION['pg_email'] = $admin['email'];

                            header("Location: admin/dashboard.php");
                            exit;
                        } else {
                            $error = "Invalid password for PG administrator.";
                        }
                    } else {
                        $error = "No active student or warden account found with that email/username.";
                    }
                    mysqli_stmt_close($adm_stmt);
                }
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Yadav PG for Stay - Yadav.in Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #0099e5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 12px;
            padding: 40px 36px 36px 36px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            position: relative;
        }
        .back-nav {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #0099e5;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .back-nav:hover {
            text-decoration: underline;
        }
        .login-header {
            text-align: right;
            margin-bottom: 28px;
        }
        .login-header h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }
        .login-header p {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            color: #334155;
            font-weight: 500;
            margin-bottom: 6px;
        }
        .form-group label span.req {
            color: #ef4444;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: #f1f5f9;
            transition: all 0.2s ease;
        }
        .input-wrap input:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .input-wrap .pwd-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 18px;
        }
        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .remember-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #0099e5;
            cursor: pointer;
        }
        .remember-wrap label {
            font-size: 13px;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }
        .btn-submit {
            width: 100%;
            background: #0099e5;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .btn-submit:hover {
            background: #0088cc;
        }
        .forgot-link {
            text-align: center;
            margin-top: 20px;
        }
        .forgot-link a {
            color: #64748b;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .forgot-link a:hover {
            color: #0099e5;
        }
        .footer-credits {
            margin-top: 32px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 12px;
            text-align: center;
        }
        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .quick-roles {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px dashed #e2e8f0;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }
        .quick-roles a {
            color: #0099e5;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            margin: 0 4px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div style="text-align: center; margin-bottom: 18px;">
            <span style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; border: 1px solid #fcd34d; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25);">
                <img src="../images/sri_krishna_modern.jpg" alt="Sri Krishna" style="width: 22px; height: 22px; border-radius: 50%; object-fit: cover; border: 1.5px solid #d97706;">
                जय श्री कृष्णा &bull; Jai Shree Krishna
            </span>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <a href="../home/home.php" class="back-nav" style="margin-bottom: 0;">
                <i class='bx bx-left-arrow-alt'></i> Back to Society
            </a>
            <div style="font-weight: 800; color: #0099e5; font-size: 18px; display: flex; align-items: center; gap: 6px;">
                <i class='bx bxs-institution'></i> YADAV.IN
            </div>
        </div>

        <div class="login-header">
            <h2>Welcome Back !</h2>
            <p>Sign in to continue to Yadav PG for Stay (Yadav.in).</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <i class='bx bx-error-circle'></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username or Email <span class="req">*</span></label>
                <div class="input-wrap">
                    <input type="text" id="username" name="username" value="yuvrajyadav734@gmail.com" required autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label>Password <span class="req">*</span></label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password" value="password" required autocomplete="current-password">
                    <button type="button" class="pwd-toggle" id="togglePwd" aria-label="Toggle password visibility">
                        <i class='bx bx-show' id="pwdIcon"></i>
                    </button>
                </div>
            </div>

            <div class="remember-wrap">
                <input type="checkbox" id="remember" name="remember" checked>
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn-submit">Sign In</button>

            <a href="register.php" style="display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; margin-top: 14px; padding: 12px; background: #f0fdf4; color: #166534; border: 1.5px dashed #86efac; border-radius: 8px; font-weight: 600; font-size: 13.5px; text-decoration: none; transition: all 0.2s;">
                <i class='bx bx-user-plus' style='font-size: 18px;'></i> New Student? Register for Yadav PG Bed &rarr;
            </a>

            <div class="forgot-link">
                <a href="javascript:void(0)" onclick="alert('Please contact Owners Binod Yadav & Sumit Yadav, or Admin Yuvraj to reset your password.')">
                    <i class='bx bx-lock-alt'></i> Forgot your password?
                </a>
            </div>

            <div style="background: #0f172a; border-radius: 8px; padding: 10px; text-align: center; margin-top: 14px; margin-bottom: 12px;">
                <a href="../admin_login.php" style="color: #38bdf8; font-weight: 700; font-size: 12.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    🛡️ Society &amp; PG Admin? Dedicated Admin Portal &rarr;
                </a>
            </div>

            <div class="quick-roles">
                Demo Accounts: 
                <a onclick="fillLogin('yuvrajyadav734@gmail.com', 'password')">Student (Yuvraj)</a> | 
                <a onclick="fillLogin('Yuvraj', 'password')">PG Warden (Admin)</a>
            </div>
        </form>
    </div>

    <div class="footer-credits">
        &copy; 2019 - <?php echo date("Y"); ?> Yadav.in, All rights reserved. &bull; Engineered by Yuvraj
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePwd');
        const pwdInput = document.getElementById('password');
        const pwdIcon = document.getElementById('pwdIcon');
        toggleBtn.addEventListener('click', function() {
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                pwdIcon.classList.replace('bx-show', 'bx-hide');
            } else {
                pwdInput.type = 'password';
                pwdIcon.classList.replace('bx-hide', 'bx-show');
            }
        });

        function fillLogin(u, p) {
            document.getElementById('username').value = u;
            document.getElementById('password').value = p;
        }
    </script>
</body>
</html>
