
<?php
require_once ('connection.php');
require_once('AdminInfopage-Action.php');

global $conn;
$v_number = $_GET['v_number'];
$stmt = $conn->prepare("SELECT V_number,
                            department,
                            advisor,
                            EID,
                            first_name,
                            last_name,
                            DOB,
                            total_credits,
                            enrollment_date,
                            expected_graduation_date,
                            preferred_name
                            FROM student WHERE V_number = ?");
// Execute the SQL query and fetch the row
$stmt->execute([$v_number]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);


$student_info = array(
"V_number" => $row['V_number'],
"department" => $row['department'],
"advisor" => $row['advisor'],
"EID" => $row['EID'],
"first_name" => $row['first_name'],
"last_name" => $row['last_name'],
"DOB" => $row['DOB'],
"total_credits" => $row['total_credits'],
"enrollment_date" => $row['enrollment_date'],
"expected_graduation_date" => $row['expected_graduation_date'],
"preferred_name" => $row['preferred_name']
);

?><table>
<h1>CURRENT STUDENT INFORMATION </h1>
<tr>
<td>V-Number:</td>
<td><?php echo $student_info['V_number']; ?></td>
  </tr>
  <tr>
    <td>Department:</td>
    <td><?php echo $student_info['department']; ?></td>
  </tr>
  <tr>
    <td>Academic Advisor:</td>
    <td><?php echo $student_info['advisor']; ?></td>
  </tr>
  <tr>
    <td>EID:</td>
    <td><?php echo $student_info['EID']; ?></td>
  </tr>
  <tr>
    <td>First Name:</td>
    <td><?php echo $student_info['first_name']; ?></td>
  </tr>
  <tr>
    <td>Last Name:</td>
    <td><?php echo $student_info['last_name']; ?></td>
  </tr>
  <tr>
    <td>DOB:</td>
    <td><?php echo $student_info['DOB']; ?></td>
  </tr>
  <tr>
    <td>Total Credits:</td>
    <td><?php echo $student_info['total_credits']; ?></td>
  </tr>
  <tr>
    <td>Enrollment Date:</td>
    <td><?php echo $student_info['enrollment_date']; ?></td>
  </tr>
  <tr>
    <td>Expected Graduation Date:</td>
    <td><?php echo $student_info['expected_graduation_date']; ?></td>
  </tr>
  <tr>
    <td>Preferred Name:</td>
    <td><?php echo $student_info['preferred_name']; ?></td>
  </tr>
</table>

<?php 
    
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo "<h1 style='text-align: center; font-size: 36px;'>Update Student ({$student_info['first_name']} {$student_info['last_name']}) in the VCU Student Database</h1>";
        
        echo "<form method='post' action='updateStudentAdmin.php'>";
        echo "<table style='border: solid 1px black;'>";
        echo "<tbody>";
        echo "<tr><td>V-Number <span style='color: red;'>*</span></td><td><input name='V_number' type='text' size='25' value='" . $student_info['V_number'] . "' readonly></td></tr>";
        
        
        // Retrieve list of Departments as potential Departments of the new Student
        $stmt = $conn->prepare("SELECT department_id, building_id, department_name FROM department");
        $stmt->execute();
        
        echo "<tr><td>Select Department <span style='color: red;'>*</span> </td><td>";
        echo "<select name='department_id'>";
        echo "<option value='-1'>Select Department</option>";
        while ($row = $stmt->fetch()) {
            echo "<option value='$row[department_id]'>$row[department_name]</option>";
        }
        echo "</select></td></tr>";
        
        // Retrieve list of Advisors as potential Advisors of the new Student
        $stmt = $conn->prepare("SELECT academic_advisor_id, first_name,last_name FROM academic_advisor");
        $stmt->execute();
        
        echo "<tr><td>Academic Advisor <span style='color: red;'>*</span> </td><td>";
        echo "<select name='academic_advisor_id'>";
        echo "<option value='-1'>Select Advisor</option>";
        while ($row = $stmt->fetch()) {
            echo "<option value='$row[academic_advisor_id]'>$row[first_name] $row[last_name]</option>";
        }
        echo "</select></td></tr>";
        
        echo "<tr><td>EID <span style='color: red;'>*</span> </td><td><input name='EID' type='text' size='25'></td></tr>";
        echo "<tr><td>First Name <span style='color: red;'>*</span> </td><td><input name='first_name' type='text' size='25'></td></tr>";
        echo "<tr><td>Last Name <span style='color: red;'>*</span> </td><td><input name='last_name' type='text' size='25'></td></tr>";
        echo "<tr><td>DOB <span style='color: red;'>*</span> </td><td><input name='DOB' type='date' min='" . date('Y-m-d', strtotime('-100 years')) . "' max='" . date('Y-m-d') . "'></td></tr>";
        
        echo "<tr><td>Total Credits</td><td><input name='total_credits' type='text'></td></tr>";
        echo "<tr><td>Enrollment Date <span style='color: red;'>*</span> </td><td><input name='enrollment_date' type='date' value='" . date('Y-m-d') . "'></td></tr>";
        echo "<tr><td>Expected Graduation Date </td><td><input name='expected_graduation_date' type='date' value='" . date('Y-m-d') . "'></td></tr>";
        
        echo "<tr><td>Preferred Name </td><td><input name='preferred_name' type='text' size='25'></td></tr>";
        echo "<tr><td><span style='color: red;'>*</span> Field is required";
        
        echo "</tbody>";
        echo "</table>";
        echo "<br>";
        echo "<input type='submit' value='Submit'>";
        echo "</form>";
    }
    // Process form data and insert a new student into the database
    
    else {
        
        try {
            $stmt = $conn->prepare("CALL updateStudent(:V_numberStudent, :department, :advisor, :EID, :first_name, :last_name, :DOB, :total_credits, :enrollment_date, :expected_graduation_date, :preferred_name)");
            
            $stmt->bindValue(':V_numberStudent', $_POST["V_number"]);
            $stmt->bindValue(':department', $_POST["department_id"]);
            $stmt->bindValue(':advisor', $_POST["academic_advisor_id"]);
            $stmt->bindValue(':EID', $_POST["EID"]);
            $stmt->bindValue(':first_name', $_POST["first_name"]);
            $stmt->bindValue(':last_name', $_POST["last_name"]);
            $stmt->bindValue(':DOB', $_POST["DOB"]);
            if (!empty($_POST["total_credits"])) {
                $stmt->bindValue(':total_credits', $_POST["total_credits"]);
            } else {
                $stmt->bindValue(':total_credits', null, PDO::PARAM_NULL);
            }
            if (!empty($_POST["enrollment_date"])) {
                $stmt->bindValue(':enrollment_date', $_POST["enrollment_date"]);
            } else {
                $stmt->bindValue(':enrollment_date', null, PDO::PARAM_NULL);
            }
            
            if (!empty($_POST["expected_graduation_date"])) {
                $stmt->bindValue(':expected_graduation_date', $_POST["expected_graduation_date"]);
            } else {
                $stmt->bindValue(':expected_graduation_date', null, PDO::PARAM_NULL);
            }
            
            if (!empty($_POST["preferred_name"])) {
                $stmt->bindValue(':preferred_name', $_POST["preferred_name"]);
            } else {
                $stmt->bindValue(':preferred_name', null, PDO::PARAM_NULL);
            }
            $stmt->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }header("Location: AdminInfopage.php");
        exit;
        
        
        
        
        
        
    }
   
   
    
   // echo "Success!! Changes have been made!";

?>
<style>
  body {
    background-color: #e6b800;
  } 
</style>
<form action="AdminInfopage.php" method="post">
    <button type="submit">Back to Database</button>
    </form>