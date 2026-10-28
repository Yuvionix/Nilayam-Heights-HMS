<?php 
require_once("Includes/config.php");
require_once("Includes/session.php");

if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    if (isset($_SESSION['account']) && $_SESSION['account'] === "admin") {
        header("Location: admin/Dashboard/admin.php");
        exit;
    } elseif (isset($_SESSION['account']) && $_SESSION['account'] === "member") {
        header("Location: members/Dashboard/member.php");
        exit;
    } elseif (isset($_SESSION['account']) && $_SESSION['account'] === "staff") {
        header("Location: staff/Dashboard/staff.php");
        exit;
    }
}

$error_msg = "";
if (isset($_SESSION['login_error'])) {
    $error_msg = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Nilayam Heights HMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="Logo3.jpg"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
                <div class="text-center mb-3">
                    <span style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; border: 1px solid #fcd34d; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);">
                        <img src="images/sri_krishna_modern.jpg" alt="Sri Krishna" style="width: 22px; height: 22px; border-radius: 50%; object-fit: cover; border: 1.5px solid #d97706;">
                        🙏 जय श्री कृष्णा &bull; Jai Shree Krishna
                    </span>
                </div>
                <h2 class="text-center font-weight-bold">Login Portal</h2>
                <p class="text-center text-muted">Welcome to Nilayam Heights HMS</p>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger text-center p-2 mb-3" style="font-size: 14px;">
                        <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label class="small text-muted mb-1">Username or Email</label>
                        <input type="text" name="username" placeholder="Enter username or email" class="form-control" required autocomplete="username">
                    </div>    
                    <div class="form-group">
                        <label class="small text-muted mb-1">Password</label>
                        <input type="password" name="password" placeholder="Enter password" class="form-control" required autocomplete="current-password">
                    </div>
                    <div class="form-group">
                       <input class="form-control button btn-primary font-weight-bold" type="submit" name="login" value="Log In">
                    </div>
                    <hr>
                    <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 8px; padding: 10px; text-align: center; margin-bottom: 14px;">
                        <span style="font-size: 12px; color: #0369a1; font-weight: 600;">Student in Boy's PG (Yadav PG for Stay)?</span><br>
                        <a href="pg/login.php" style="font-size: 12px; font-weight: 700; color: #0284c7; text-decoration: underline;">
                            Access Yadav PG Portal &rarr;
                        </a> &bull; 
                        <a href="pg/register.php" style="font-size: 12px; font-weight: 700; color: #16a34a; text-decoration: underline;">
                            New Student Sign Up &rarr;
                        </a>
                    </div>
                    <div class="text-center mb-2">
                        <span class="text-muted small">Resident without an account? </span>
                        <a href="register.php" class="small font-weight-bold">Register as Member</a>
                    </div>
                    <div style="background: #0f172a; border-radius: 8px; padding: 10px; text-align: center; margin-top: 14px; margin-bottom: 12px;">
                        <a href="admin_login.php" style="color: #38bdf8; font-weight: 700; font-size: 12.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                            🛡️ Society Administrator? Dedicated Admin Login &rarr;
                        </a>
                    </div>
                    <div class="link login-link text-center">
                        <a href="home/home.php" class="text-secondary small">← Back To Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
