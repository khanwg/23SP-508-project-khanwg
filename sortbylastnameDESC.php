<html>
<head>
<title>Admin database - Students</title>
<!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include DataTables plugin library -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <!-- Include your JS code that initializes DataTable -->
    <script type="text/javascript" src="AdminInfopage.js"></script>
</head>
<body>
<?php

// Connect to the database
require_once('connection.php');
require_once('header.php'); 
// Check if the submit button was clicked
if (isset($_POST['submit'])) {

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
        INNER JOIN academic_advisor a ON a.academic_advisor_id = s.advisor
        ORDER BY s.last_name DESC";
  
  $stmt = $conn->prepare($sql);
  $stmt->execute();


?>
<?php 
session_start();
// if the user is logged in, store their name and V number to welcome them.
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    $session_V_number = $_SESSION["V_number"];// user ID.
    $sqlQuery =
    "SELECT CONCAT(first_name, ' ' , last_name)
                FROM admin WHERE V_number = :session_V_number;";
    $stmt2 = $conn->prepare($sqlQuery);
    $stmt2->bindParam(':session_V_number', $session_V_number, PDO:: PARAM_STR);
    $stmt2->execute();
    $user_FullName = $stmt2->fetchColumn();
}
?>

<!-- adds buttons for the user to interact with the database -->
<div class="container-fluid mt-3 mb-3">
    <h4>Welcome, <?php echo $user_FullName; ?>!</h4>
    <div>
        <table id="table-students" class="table table-bordered table-striped">
            <thead>
            <form action="AddStudentPage.php" method="get">
                <button type="submit">Add Student Into Database</button>
            </form>
            <form method="post" action="sortbyfirstnameASC.php">
                <button type="submit" name="submit">Sort By First Name (ASC)</button>
            </form>
            <form method="post" action="sortbyfirstnameDESC.php">
                <button type="submit" name="submit">Sort By First Name (DESC)</button>
            </form>
            <form method="post" action="sortbylastnameASC.php">
                <button type="submit" name="submit">Sort By Last Name (ASC)</button>
            </form>
             <form method="post" action="sortbylastnameDESC.php">
                <button type="submit" name="submit">Sort By Last Name (DESC)</button>
            </form>
            <form method="post" action="logout.php">
              <button type="submit" name="submit">Logout</button>
            </form>
			
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
                <th>Actions</th> 
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
                    echo "<td>" . ($row['Total Credits'] ? $row['Total Credits'] : "N/A") . "</td>";
                    echo "<td>" . ($row['Enrollment Date'] ? $row['Enrollment Date'] : "N/A") . "</td>";
                    echo "<td>" . ($row['Expected Graduation Date'] ? $row['Expected Graduation Date'] : "N/A") . "</td>";
                    echo "<td><a href='updateStudentAdmin.php?v_number=" . $row['V-Number'] . "'>Update</a> | <a href='#' onclick=\"if(confirm('Are you sure you would like to delete this student?')){window.location.href='deleteStudent.php?v_number=" . $row['V-Number'] . "';}\">Delete</a></td>";
                    echo "</tr>";
}
} else{
    echo "PLEASE GO BACK TO DATABASE.";
   ?> <form action="AdminInfopage.php" method="get">
    <button type="submit">Back to Database</button>
    
    </form>
<?php }
echo "<p>
                <span style = 'font-size: 12px; float: right;'>
                    Admin View
                </span><br><p>";
?>

</tbody>
</table>
</div>

</div>