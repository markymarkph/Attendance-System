<?php
session_start();

if(empty($_SESSION['user_id']))
{
    header("Location: ../index.php");
}

require ('../config/db.php');
$db = DB();
require ('../lib/functions.php');
$app = new RunLib();
$user_id = $_SESSION['user_id'];
$date = new DateTime(date(''), new DateTimeZone('Asia/Manila'));
$date = $date->format('Y-m-d');
$user = $app->UserDetails($user_id);
$user_attendance = $app->CheckAttendance($user_id, $date);
$date = new DateTime(date(''), new DateTimeZone('Asia/Manila'));


if(isset($_POST['time_in']) && isset($_SERVER['REQUEST_URI'])) {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $statement = $conn->prepare('INSERT INTO pmi_timesheet (user_id, emp_timein, emp_timeout, date_in, date_out, emp_workdone)
    VALUES (:user_id, :emp_timein, :emp_timeout, :date_in, :date_out, :emp_workdone)');

    try {
        $statement->execute([
        'user_id' => $user_id,
        'emp_timein' => $date->format('H:i:sA'),
        'emp_timeout' => '',
        'date_in' => $date->format('Y-m-d'),
        'date_out' => '',
        'emp_workdone' => '',
    ]);
        echo "<script>alert('Clock In Success!');
        window.location='index.php';
        </script>";
        exit();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
}


if(isset($_POST['time_out']) && isset($_SERVER['REQUEST_URI'])) {
    $workdone = $_POST['emp_workdone'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $statement = $conn->prepare('UPDATE pmi_timesheet SET emp_timeout=:emp_timeout, emp_workdone=:emp_workdone, date_out=:date_out WHERE user_id=:user_id AND date_in=:date_in');

    try {
        $statement->execute([
        'user_id' => $user_id,
        'emp_timeout' => $date->format('H:i:sA'),
        'emp_workdone' => strip_tags($workdone),
        'date_out' => $date->format('Y-m-d'),
        'date_in' => $date->format('Y-m-d'),
    ]);
        echo "<script>alert('Clock Out Success!');
        window.location='index.php';
        </script>";
        exit();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
}

if(isset($_POST['update_workdone']) && isset($_SERVER['REQUEST_URI'])) {
    $workdone = $_POST['emp_workdone'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $statement = $conn->prepare('UPDATE pmi_timesheet SET emp_workdone=:emp_workdone WHERE user_id=:user_id AND date_in=:date_in');

    try {
        $statement->execute([
        'user_id' => $user_id,
        'emp_workdone' => strip_tags($workdone),
        'date_in' => $date->format('Y-m-d'),
    ]);
        echo "<script>alert('Updated Successfully!');
        window.location='index.php';
        </script>";
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
<div id="clockbox"></div>

<form action="" method="POST" id="attendance_form" autocomplete="off">
    <label for="fullname">Employee Name:</label>
    <input type="text" id="fullname" name="fullname" value="<?= $user->emp_name; ?>" disabled>
    </br>
    <label for="position">Position: </label>
    <input type="text" id="position" name="position" value="<?= $user->emp_position; ?>" disabled>
    </br>
    <label for="department">Department:</label>
    <input type="text" id="department" name="department" value="<?= $user->emp_dept; ?>" disabled>
    </br>
    <label for="category">Category:</label>   
    <input type="text" id="category" name="category" value="<?= $user->emp_category; ?>" disabled>
    </br>
    <?php
        if (is_null($user_attendance)){
    ?>
        <input type="submit" name="time_in" id="time_in" value="Clock In" />
    <?php
        } elseif (!empty($user_attendance->emp_timeout)){
    ?>
        <label for="workdone">Work Done Today:</label>
        <textarea id="emp_workdone" name="emp_workdone" rows="4" cols="50"><?= $user_attendance->emp_workdone; ?></textarea>
        </br>
        <input type="submit" name="update_workdone" id="update_workdone" value="Update" />
    <?php
        } elseif (!empty($user_attendance->emp_timein)){
    ?>
    <label for="Start Time">Start Time:</label>
    <input type="text" id="start-time" name="start-time" value="<?= $user_attendance->emp_timein; ?>" disabled>
    </br>
    <label for="workdone">Work Done Today:</label>
    <textarea id="emp_workdone" name="emp_workdone" rows="4" cols="50"></textarea>
    </br>
    <input type="submit" name="time_out" id="time_out" value="Clock Out" />
    <?php
        }
    ?>
    
</form>
    <label for="fullname">Employee Username:</label>
    <input type="text" id="emp_username" name="emp_username" value="<?= $user->user_name; ?>" disabled>
<a href="logout.php" class="btn btn-primary">Logout</a>
</body>

<script type="text/javascript">
    var tday=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    var tmonth=["January","February","March","April","May","June","July","August","September","October","November","December"];

    function GetClock(){
    var d=new Date();
    var nday=d.getDay(),nmonth=d.getMonth(),ndate=d.getDate(),nyear=d.getFullYear();
    var nhour=d.getHours(),nmin=d.getMinutes(),nsec=d.getSeconds(),ap;

    if(nhour==0){ap=" AM";nhour=12;}
    else if(nhour<12){ap=" AM";}
    else if(nhour==12){ap=" PM";}
    else if(nhour>12){ap=" PM";nhour-=12;}

    if(nmin<=9) nmin="0"+nmin;
    if(nsec<=9) nsec="0"+nsec;

    var clocktext="<h2>Date Today: "+tday[nday]+", "+tmonth[nmonth]+" "+ndate+", "+nyear+" </br>Current Time: "+nhour+":"+nmin+":"+nsec+ap+"</h2>";
    document.getElementById('clockbox').innerHTML=clocktext;
    }

    GetClock();
    setInterval(GetClock,1000);
</script>
<script>
    $(document).ready(function() {
    $('#time_out').attr('disabled', true);
    
        $('textarea').on('keyup',function() {
            var textarea_value = $("#emp_workdone").val();

            
            if(textarea_value != '') {
                $('#time_out').attr('disabled', false);
            } else {
                $('time_out').attr('disabled', true);
            }
        });
    });

</script>
</html>