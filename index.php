<?php
session_start();
require ('config/db.php');
require ('lib/functions.php');

$app = new RunLib();
 
$login_error_message = '';
$register_error_message = '';

    if (!empty($_POST['btnLogin'])) {
        //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_name = trim($_POST['user_name']);
        $user_pass = trim($_POST['user_pass']);
    
        if ($user_name == "") {
            $login_error_message = 'Username field is required!';
        } else if ($user_pass == "") {
            $login_error_message = 'Password field is required!';
        } else {
            $user_id = $app->Login($user_name, $user_pass); // check user login
            if($user_id > 0)
            {
                $_SESSION['user_id'] = $user_id; // Set Session
                header("Location: system/index.php"); // Redirect user to the profile.php
            }
            else
            {
                $login_error_message = 'Invalid login details!';
            }
        }
    }
?>
<html>
<head>
    <title>PMI Attendance System - Login Page</title>
</head>
<body>
    <h4>Login</h4>
        <?php
            if ($login_error_message != "") {
                echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $login_error_message . '</div>';
            }
            ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Username/Email</label>
                <input type="text" name="user_name" class="form-control"/>
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" name="user_pass" class="form-control"/>
            </div>
            <div class="form-group">
                <input type="submit" name="btnLogin" class="btn btn-primary" value="Login"/>
            </div>
        </form>
</body>
</html>