<html>
<head>
    <title>Admin database - Students</title>
    <?php require_once('header.php'); ?>
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include DataTables plugin library -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <!-- Include your JS code that initializes DataTable -->
    <script type="text/javascript" src="AdminInfopage.js"></script>
</head>
<body>
    <?php require_once('connection.php'); ?>
    <?php require_once('AdminInfopage-Action.php'); ?>
    <?php 
    $sql = "SELECT s.V_number as `V-Number`,
               d.department_name AS `Department Name`,
               concat(a.first_name,' ',a.last_name) as `Advisor Name`,
               s.eID as `eID`,
               concat(s.first_name,' ',s.last_name) as `Name`,
               s.DOB as `DOB`,
               s.total_credits as `Total Credits`,
               s.enrollment_date as `Enrollment Date`,
               s.expected_graduation_date as `Expected Graduation Date`
        FROM student s
        INNER JOIN department d ON s.department = d.department_id
        INNER JOIN academic_advisor a ON a.academic_advisor_id = s.advisor";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    ?>

<div class="container-fluid mt-3 mb-3">
    <h4>Students</h4>
    <div>
        <table id="table-students" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>V# (V-Number)</th>
                    <th>Department Name</th>
                    <th>Advisor Name</th>
                    <th>eID</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Total Credits</th>
                    <th>Enrollment Date</th>
                    <th>Expected Graduation Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['V-Number'] . "</td>";
                    echo "<td>" . $row['Department Name'] . "</td>";
                    echo "<td>" . $row['Advisor Name'] . "</td>";
                    echo "<td>" . $row['eID'] . "</td>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td>" . $row['DOB'] . "</td>";
                    echo "<td>" . $row['Total Credits'] . "</td>";
                    echo "<td>" . $row['Enrollment Date'] . "</td>";
                    echo "<td>" . $row['Expected Graduation Date'] . "</td>";
                    echo "</tr>";
                } ?>
            </tbody>
        </table>
    </div>
</div>
    


