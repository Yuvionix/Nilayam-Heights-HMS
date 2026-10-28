<?php
// Include config file
require_once "Includes/config.php";
require_once "Includes/session.php";

// If already logged in, redirect
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

// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = $address = $phone = "";
$username_err = $email_err = $password_err = $confirm_password_err = $general_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    $input_username = trim(isset($_POST["username"]) ? $_POST["username"] : "");
    if (empty($input_username)) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_ ]{3,50}$/', $input_username)) {
        $username_err = "Username can only contain letters, numbers, spaces, and underscores (3-50 chars).";
    } else {
        // Prepare a select statement to check if username exists in member
        $sql = "SELECT id FROM member WHERE username = ?";
        if ($stmt = mysqli_prepare($con, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $input_username);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = $input_username;
                }
            } else {
                $general_err = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate email
    $input_email = trim(isset($_POST["email"]) ? $_POST["email"] : "");
    if (empty($input_email)) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        // Check if email already registered
        $sql = "SELECT id FROM member WHERE email = ?";
        if ($stmt = mysqli_prepare($con, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $input_email);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = $input_email;
                }
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    $input_password = isset($_POST["password"]) ? $_POST["password"] : "";
    if (empty($input_password)) {
        $password_err = "Please enter a password.";
    } elseif (strlen($input_password) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = $input_password;
    }

    // Validate confirm password
    $input_confirm = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";
    if (empty($input_confirm)) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = $input_confirm;
        if (empty($password_err) && ($password !== $confirm_password)) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // Optional fields
    $address = trim(isset($_POST["address"]) ? $_POST["address"] : "");
    $phone = trim(isset($_POST["phone"]) ? $_POST["phone"] : "");
    $phone_num = !empty($phone) && is_numeric($phone) ? (int)$phone : null;

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($general_err)) {
        
        // Hash password securely with BCrypt
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an insert statement into member table
        $sql = "INSERT INTO member (username, email, password, address, phone) VALUES (?, ?, ?, ?, ?)";
         
        if ($stmt = mysqli_prepare($con, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $hashed_password, $address, $phone_num);
            
            if (mysqli_stmt_execute($stmt)) {
                $new_mem_id = mysqli_insert_id($con);
                mysqli_stmt_close($stmt);

                // Initialize placeholder in society_kyc
                $user_esc = mysqli_real_escape_string($con, $username);
                $addr_esc = mysqli_real_escape_string($con, $address);
                $phone_esc = mysqli_real_escape_string($con, $phone);
                mysqli_query($con, "INSERT INTO society_kyc (member_id, full_name, permanent_address, emergency_phone, status, submitted_at) VALUES ($new_mem_id, '$user_esc', '$addr_esc', '$phone_esc', 'Pending', NOW())");

                // Auto login to member session and redirect to Resident KYC
                $_SESSION['logged'] = true;
                $_SESSION['account'] = "member";
                $_SESSION['member_id'] = $new_mem_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                header("Location: members/kyc.php?new=1");
                exit;
            } else {
                $general_err = "Failed to register account. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Registration | Nilayam Heights HMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="Logo3.jpg"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-5 offset-md-3 form" style="background:#fff;padding:30px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.08);">
                <div class="text-center mb-3">
                    <span style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; padding: 5px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #fcd34d; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);">
                        🙏 जय श्री कृष्णा &bull; Jai Shree Krishna
                    </span>
                </div>
                <h2 class="text-center font-weight-bold">Resident Sign Up</h2>
                <p class="text-center text-muted">Join Nilayam Heights Society Portal</p>

                <?php if(!empty($general_err)): ?>
                    <div class="alert alert-danger text-center p-2 mb-3"><?php echo htmlspecialchars($general_err); ?></div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label class="small text-muted mb-1">Full Name / Username</label>
                        <input type="text" name="username" placeholder="e.g. Rahul Sharma" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>" required>
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="small text-muted mb-1">Email Address</label>
                        <input type="email" name="email" placeholder="e.g. rahul@example.com" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>" required>
                        <span class="invalid-feedback"><?php echo $email_err; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="small text-muted mb-1">Flat / Address</label>
                        <input type="text" name="address" placeholder="e.g. Flat 302, Tower B" class="form-control" value="<?php echo htmlspecialchars($address); ?>">
                    </div>

                    <div class="form-group">
                        <label class="small text-muted mb-1">Contact Phone</label>
                        <input type="tel" name="phone" placeholder="e.g. 9835123456" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>

                    <div class="form-group">
                        <label class="small text-muted mb-1">Password</label>
                        <input type="password" name="password" placeholder="Minimum 6 characters" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required autocomplete="new-password">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>

                    <div class="form-group">
                        <label class="small text-muted mb-1">Confirm Password</label>
                        <input type="password" name="confirm_password" placeholder="Confirm your password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" required autocomplete="new-password">
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </div>

                    <div class="form-group mt-3">
                        <input class="form-control button btn-primary font-weight-bold" type="submit" name="signup" value="Create Resident Account">
                    </div>
                    <hr>
                    <div class="link login-link text-center">Already registered? <a href="login.php">Log in here</a></div>
                    <div style="background: #e0f2fe; border: 1px dashed #38bdf8; border-radius: 8px; padding: 10px; text-align: center; margin-top: 14px;">
                        <span style="font-size: 12px; color: #0284c7; font-weight: 600;">Student seeking Boy's PG Hostel?</span><br>
                        <a href="pg/register.php" style="font-size: 12px; font-weight: 700; color: #0369a1; text-decoration: underline;">
                            Register for Yadav PG Bed &rarr;
                        </a>
                    </div>
                    <div class="link login-link text-center mt-2"><a href="home/home.php" class="text-secondary small">← Back To Home</a></div>
                </form>
            </div> 
        </div>
    </div>
</body>
</html>
