<?php
session_start();

if(empty($_SESSION['user_id']))
{
    header("Location: index.php");
}

require ('../config/db.php');
$db = DB();
require ('../lib/functions.php');
$app = new RunLib();
$user_id = $_SESSION['user_id'];
$user = $app->UserDetails($user_id);
$user_timed_in = $app->CheckAttendance($user_id);
$date = new DateTime(date(''), new DateTimeZone('Asia/Manila'));
//echo $date->format('Y-m-d H:i:sA');

if(isset($_POST['time_in']) && isset($_SERVER['REQUEST_URI'])) {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $statement = $conn->prepare('INSERT INTO pmi_timesheet (user_id, emp_timein, emp_timeout, date, emp_workdone, addtl_workdone)
    VALUES (:user_id, :emp_timein, :emp_timeout, :date, :emp_workdone, :addtl_workdone)');

    try {
        $statement->execute([
        'user_id' => $user_id,
        'emp_timein' => $date->format('H:i:sA'),
        'emp_timeout' => '',
        'date' => date('m/d/Y'),
        'emp_workdone' => '',
        'addtl_workdone' => '',
    ]);
    echo "<script>alert('Clocked-In Success!');</script>";  
        header ('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
}
?>
<html>
<head>
<title>PMI Attendance System</title>
<script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
</head>
<body>
<a href="logout.php" class="btn btn-primary">Logout</a>
<form action="" method="POST" id="attendance_form" autocomplete="off">
    <label for="fullname">Employee Name:</label>
    <input type="text" id="fullname" name="fullname" value="<?= $user->emp_name; ?>">
    
    <label for="position">Position: </label>
    <input type="text" id="position" name="position" value="<?= $user->emp_position; ?>">
    
    <label for="department">Department:</label>
    <input type="text" id="department" name="department" value="<?= $user->emp_dept; ?>">
        
    <label for="category">Category:</label>   
    <input type="text" id="category" name="category" value="<?= $user->emp_category; ?>">
    
    <input type="text" id="start-time" name="start-time" value="<?= $user_timed_in->emp_timein; ?>" style="display:none;">
    
    <?php
        if (is_null($user_timed_in)){
    ?>
        <input type="submit" name="time_in" id="time_in" value="Clock In" />
    <?php
        } else {
    ?>
        <input type="submit" name="time_out" id="time_out" value="Clock Out" />
    <?php
        }
    ?>
    
</form>
</body>
</html>