<?php
require ('../config/db.php');

$db = DB();
?>

<html>
<head>
    <title>HR - Viewing Reports Page</title>
</head>
<body>
    <form action="" method="POST">
        <label for="departments">Select Department:</label>
        <select name="departments" id="departments">
            <option name="default" value="default" selected="selected">--Please Choose Department--</option>
            <option name="Sales" value="Sales Department">Sales</option>
            <option name="Operations" value="Operations Department">Operations</option>
            <option name="Bidding" value="Bidding Department">Bidding</option>
            <option name="Planning" value="Planning & Design Department">Planning & Design</option>
            <option name="CSR" value="CSR Department">Corporate Social Responsibility</option>
            <option name="IT" value="IT Department">IT</option>
        </select>
        <input type="date" name="start_date" id="start_date" default="<?= date(); ?>">
        <input type="date" name="end_date" id="end_date" default="<?= date(); ?>">

        <input type="submit" name="get_reports" id="get_reports" value="Get Reports" />
    </form>

<?php
if (isset($_POST['get_reports'])) {
    $department = $_POST['departments'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    try {
        $db = DB();
        $query = $db->prepare("SELECT * FROM pmi_employees LEFT JOIN pmi_timesheet ON pmi_employees.user_id=pmi_timesheet.user_id WHERE emp_dept=:emp_dept AND date_in >= :date_in AND date_out <= :date_out");
        $query->bindParam("emp_dept", $department, PDO::PARAM_STR);
        $query->bindParam("date_in", $start_date, PDO::PARAM_STR);
        $query->bindParam("date_out", $end_date, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {
            echo "<table><th>Date</th>";
            echo "<th>Name</th>";
            echo "<th>Position</th>";
            echo "<th>Department</th>";
            echo "<th>Time-In</th>";
            echo "<th>Time-Out</th>";
            echo "<th>Work Done</th>";
            foreach ($query->fetchAll() as $row) {
                echo "<tr>";
                echo "<td>".$row['date_in']."</td>";
                echo "<td>".$row['emp_name']."</td>";
                echo "<td>".$row['emp_position']."</td>";
                echo "<td>".$row['emp_dept']."</td>";
                echo "<td>".$row['emp_timein']."</td>";
                echo "<td>".$row['emp_timeout']."</td>";
                echo "<td>".$row['emp_workdone']."</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}
?>
</body>
</html>