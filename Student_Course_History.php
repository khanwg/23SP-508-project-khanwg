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
echo '<body style = "background-color: #e6b800">';
?>

<?php
    // Dispay student course history coloumn below.. 
    $sqlQuery ="
    SELECT 
        c.course_name,
        s.section_id, 
        CONCAT(i.first_name, ' ', i.last_name) AS instructor,
        d.department_name, 
        GROUP_CONCAT(
            (SELECT c2.course_name 
                FROM course c2 
                WHERE c2.course_id = p.prerequisite_id) /*Prerequisite class.*/ 
                SEPARATOR ', '
            ) AS prereqs
    FROM student stu
    	INNER JOIN student_courses sc ON sc.V_number = 	
        	stu.V_number
    	INNER JOIN course c ON c.course_id = sc.course_id
        INNER JOIN course_section s ON s.course_id = c.course_id
    	INNER JOIN instructor_courses ic ON ic.course_id = sc.course_id
        INNER JOIN instructor i ON i.instructor_id = ic.instructor_id
        LEFT JOIN prerequisite p ON p.course_id = c.course_id
        INNER JOIN department d ON c.department_id = d.department_id
    WHERE stu.V_number = :session_V_number
	GROUP BY c.course_id, s.section_id, i.instructor_id;
    ";
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->bindParam(':session_V_number', $session_V_number, PDO:: PARAM_STR);
    $stmt->execute();
    $query = $stmt->fetchALL(PDO::FETCH_ASSOC); 

    
    echo "<p>
            <span style = 'font-size: 12px; float: right;'>
                Student View
            </span><br>
            <span style = 'font-size: 50px;'>
                " . $user_firstName . "'s Course History...
            </span><br>";
    if (empty($query)) {
        echo "<span style = 'font-size: 20px;'>"
            . $user_firstName . " hasn't taken any courses :(
                  </span><br>";
    }
    else {
        echo"---------------------------------------------------------------------------------------------------------------<br>";
        foreach ($query as $q_row) {
            $course_Name = $q_row["course_name"];
            $section_ID = $q_row["section_id"];
            $instructor = $q_row["instructor"];
            $department_Name = $q_row["department_name"];
            $prereqs = $q_row["prereqs"];
            
            echo "<span style = 'font-size: 20px;'>
                    Course Name - Section: " . $course_Name . 
                    "-" . $section_ID . "
                    <br>Instructor: " . $instructor . "
                    <br>Department: " . $department_Name . "
                    <br>Prerequisite: " . $prereqs . 
                    "</span><br>
                    ---------------------------------------------------------------------------------------------------------------<br>"; 
        }
    }     
    echo"</p>";
    
    // Options...
    echo "<p>
            <a href = 'studentProfile.php'>
                View Homepage
            </a>
            <a href = 'Student_Info.php'>
                | View Student Information
            </a>
            <a href = 'Student_Clubs.php'>
                | Veiw Clubs
            </a>
            <a href = 'login.php'>
                | Logout
            </a>
          </p>";
?>