<?php // set up phase..
require_once('connection.php');
session_start();
// if the user is logged in, store their name and V number and display their name along with the page.
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    $session_V_number = $_SESSION["V_number"];// user ID.
    $sqlQuery =
    "SELECT first_name
                FROM student WHERE V_number = :session_V_number;";
    $stmt = $conn->prepare($sqlQuery);
    $stmt->bindParam(':session_V_number', $session_V_number, PDO:: PARAM_STR);
    $stmt->execute();
    $user_firstName = $stmt->fetchColumn();
}
else {
    // if user is not logged in, redirect them to the login page.
    header("location: login.php");
    exit;
}
// PHP colors: https://brandpalettes.com/php-logo-colors/#:~:text=The%20official%20colors%20of%20PHP%20are%20light,blue%2C%20blue%2C%20dark%20blue%2C%20white%2C%20and%20black.
echo '<body style = "background-color: #777BB3">';
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Student Clubs </title>
	</head>
	<body>
    <?php
        // Dispay student's clubs coloumn below.. 
    
        // Options...
        echo "<p>
                <span style = 'font-size: 12px; float: right;'>
                    Student View
                </span><br>
                <span style = 'font-size: 50px;'>
                " . $user_firstName . "'s Clubs...
                </span>
                
                
             </p>";
        
        echo "<p>
                <a href = 'studentProfile.php'>
                    View Homepage
                </a>
                <a href = 'Student_Course_History.php'>
                    | View Course History
                </a>
                <a href = 'Student_Info.php'>
                    | View Student Information
                </a>
                | Logout (NOT YET IMPLEMENTED!!!!)
              </p>";
    ?>
	</body>
</html>
