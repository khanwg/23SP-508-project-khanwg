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
		<title> Student Information </title>
	</head>
	<body> 
    	<?php 
        	// Dispay student info coloumn below..
        	$sqlQuery =
        	"SELECT 
                CONCAT(s.first_name, ' ', s.last_name), 
                s.preferred_name, 
                V_number, 
                d.department_name,
                CONCAT(ad.first_name, ' ', ad.last_name ),
                s.EID,
                s.DOB, 
                s.total_credits,
                s.enrollment_date, 
                s.expected_graduation_date   
            FROM student s
	           INNER JOIN department d ON d.department_id = s.department
               INNER JOIN academic_advisor ad ON ad.academic_advisor_id = s.advisor    
            WHERE V_number = :session_V_number;";
        	$stmt = $conn->prepare($sqlQuery);
        	$stmt->bindParam(':session_V_number', $session_V_number, PDO:: PARAM_STR);
        	$stmt->execute();
        	$query = $stmt->fetch(PDO::FETCH_ASSOC);
        	
        	$user_FullName = $query["CONCAT(s.first_name, ' ', s.last_name)"]; 
        	$preferred_name = $query["preferred_name"];
        	$student_V_number = $query["V_number"]; 
        	$deparment = $query["department_name"]; 
        	$advisor = $query["CONCAT(ad.first_name, ' ', ad.last_name )"]; 
        	$eid = $query["EID"]; 
        	$dob = $query["DOB"]; 
        	$total_credits = $query["total_credits"]; 
        	$enrollment_year = $query["enrollment_date"]; 
        	$expected_grad_date = $query["expected_graduation_date"]; 
        	
        	// Add this later: "edit" means you can edit a data field..
        	echo "<p> 
                    <span style = 'font-size: 50px;'>".
                        $user_firstName . "'s Student Information...</span><br>
                    <span style = 'font-size: 25px;'>
                        Full Name: " . $user_FullName . " <br>
                        Preferred Name: " . $preferred_name . " <br>
                        V Number: " . $student_V_number . " <br>
                        Phone Number(s): MISSING!!<br>
                        Department: " . $deparment . " <br>
                        Advisor: " . $advisor . " <br>
                        EID: " . $eid . " <br>
                        Current Password: 
                            | <a href = 'changePswrd.php'>
                                <span style = 'font-size: 14px;'>
                                    edit
                                </span>
                              </a> <br>
                        DOB: " . $dob . " <br>
                        Toal Credits: " . $total_credits . " <br>
                        Enrollment year: " . $enrollment_year . " <br>
                        Expected graduation date: " . $expected_grad_date . "
                    </span>
                </p>";
        	
        	// Options
        	echo "<p>
                   <a href = 'studentProfile.php'>
                        View Homepage
                   </a>    
                   <a href = 'Student_Course_History.php'>
                        | View Course History
                   </a>    
                   <a href = 'Student_Clubs.php'>
                        | Veiw Clubs
                   </a>    
                   | Logout (NOT YET IMPLEMENTED!!!!) 
                 </p>";
    	?>
	</body>
</html>

