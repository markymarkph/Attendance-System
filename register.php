<?php

session_start();

if(!empty($_SESSION['user_id']))
{
    header("Location: system/index.php");
}

require ('config/db.php');
$db = DB();
require ('lib/functions.php');
$register_error_message = '';
$app = new RunLib();

if (!empty($_POST['btnRegister'])) {
    if ($_POST['fname'] == "") {
        $register_error_message = 'First Name field is required!';
    } else if ($_POST['lname'] == "") {
        $register_error_message = 'Last Name field is required!';
    } else if ($_POST['password'] == "") {
        $register_error_message = 'Password field is required!';
    } else if ($_POST['departments'] == "default") {
        $register_error_message = 'Department field is required!';
    } else if ($_POST['position'] == "default") {
        $register_error_message = 'Position field is required!';
    } else if ($_POST['category'] == "default") {
        $register_error_message = 'Category field is required!';
    } else {
        $user_id = $app->Register($_POST['fname'], $_POST['lname'], $_POST['password'], $_POST['departments'], $_POST['position'], $_POST['category']);
        $_SESSION['user_id'] = $user_id;
        echo "<script>alert('Registration Success!');
        window.location='index.php';
        </script>";
        $_SESSION['user_id'] = $user_id;
        //header("Location: system/index.php");
    }
}
?>

<html>
<head>
<title>PMI Attendance System - Register</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<div class="container">
    <div class="row">
            <div class="col-md-6 offset-md-3">
                <h4>Register</h4>
                <?php
                if ($register_error_message != "") {
                    echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $register_error_message . '</div>';
                }
                ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="">First Name</label>
                        <input type="text" name="fname" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Last Name</label>
                        <input type="text" name="lname" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Position</label>
                        <input type="text" name="position" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Department</label>
                        <select name="departments" id="departments">
                            <option name="default" value="default" selected="selected">--Please Choose Department--</option>
                            <option name="Sales" value="Sales Department">Sales</option>
                            <option name="Operations" value="Operations Department">Operations</option>
                            <option name="Bidding" value="Bidding Department">Bidding</option>
                            <option name="Planning" value="Planning & Design Department">Planning & Design</option>
                            <option name="CSR" value="CSR Department">Corporate Social Responsibility</option>
                            <option name="IT" value="IT Department">IT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Category</label>
                        <select name="category" id="category">
                            <option name="default" value="default" selected="selected">--Please Choose Category--</option>
                            <option name="Employee" value="Employee">Employee</option>
                            <option name="Manager" value="Manager">Manager</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" name="btnRegister" class="btn btn-primary" value="Register"/>
                        <a class="btn btn-primary" href="index.php">Login</a>
                    </div>
                </form>
            </div>
        </div>
</div>
</html>