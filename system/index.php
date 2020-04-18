<html>
<head>
<title>PMI Attendance System</title>
<script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
</head>
<body>
    <form action="" method="POST" id="attendance_form">
        <input type="text" id="fullname" name="fullname" value="FullName">
        <input type="text" id="position" name="position" value="Position">
        <input type="text" id="department" name="department" value="Department">
        <input type="text" id="start-time" name="start-time" value="" style="display:none;">
        <input type="submit" name="time_in" id="time_in" value="Clock In" />
        <input type="submit" name="time_out" id="time_out" value="Clock Out" />
    </form>
</body>

<script>
    $(document).ready(function() {
            if ($('#start-time').val().length===0){
                document.getElementById("time_in").style.display = "block";
                document.getElementById("time_out").style.display = "none";
            } else if ($('#start-time').val().length>0) {
                document.getElementById("time_in").style.display = "none";
                document.getElementById("time_out").style.display = "block";
        };
    });
</script>
</html>

<?php
    include(dirname(__FILE__) . "/config/db.php");
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        echo "Connected to $dbname at $host successfully.";
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }
?>