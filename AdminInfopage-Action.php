<?php
require_once ('connection.php');
global $conn;

function listStudents(){
    global $conn;
    
    $sqlQuery = "SELECT s.V_number as `V-Number`,d.department_name,
                        concat(a.first_name,' ',a.last_name) as `advisor_name`,
                         EID as `eID`,
                         concat(s.first_name,' ',s.last_name) as `Name`,
                         DOB as `DOB`,
                         total_credits as `Total Credits`,
                         enrollment_date as `Enrollment Date`,
                         expected_graduation_date as `Expected Graduation Date`
                FROM student s 
                INNER JOIN department d ON(s.department_id = d.department_id)
                INNER JOIN academic_advisor a ON(a.academic_advisor_id = s.advisor)";
    
    if (! empty($_POST["search"]["value"])) {
        $sqlQuery .= ' WHERE (s.V_number LIKE "%' . $_POST["search"]["value"] . '%"
                   OR s.first_name LIKE "%' . $_POST["search"]["value"] . '%"
                   OR s.last_name LIKE "%' . $_POST["search"]["value"] . '%"
                   OR a.first_name LIKE "%' . $_POST["search"]["value"] . '%"
                   OR a.last_name LIKE "%' . $_POST["search"]["value"] . '%"
                   OR d.department_name LIKE "%' . $_POST["search"]["value"] . '%")';
    }
    
    if (! empty($_POST["order"])) {
        $sqlQuery .= ' ORDER BY ' . ($_POST['order']['0']['column'] + 1) . ' ' . $_POST['order']['0']['dir'];
    } else {
        $sqlQuery .= ' ORDER BY s.V_number DESC';
    }
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->execute();
    
    $numberRows = $stmt->rowCount();
    
    if ($_POST["length"] != - 1) {
        $sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->execute();
    
    $dataTable = array();
    
    while ($sqlRow = $stmt->fetch()) {
        $dataRow = array();
        
        $dataRow[] = $sqlRow['V-Number'];
        $dataRow[] = $sqlRow['department_name'];
        $dataRow[] = $sqlRow['advisor_name'];
        $dataRow[] = $sqlRow['eID'];
        $dataRow[] = $sqlRow['Name'];
        $dataRow[] = $sqlRow['DOB'];
        $dataRow[] = $sqlRow['Total Credits'];
        $dataRow[] = $sqlRow['Enrollment Date'];
        $dataRow[] = $sqlRow['Expected Graduation Date'];
        
        $dataRow[] = '<button type="button" name="update" student_id="' . $sqlRow["eID"] . '" class="btn btn-warning btn-sm update">Update</button>
                  <button type="button" name="delete" student_id="' . $sqlRow["eID"] . '" class="btn btn-danger btn-sm delete" >Delete</button>';
        
        $dataTable[] = $dataRow;
    }
    
    $output = array(
        "recordsTotal" => $numberRows,
        "recordsFiltered" => $numberRows,
        "data" => $dataTable
    );
    
    echo json_encode($output);
}

function addStudent()
{
    global $conn;
    
    $sqlQuery = "INSERT INTO student
                 (V_number, department, advisor, EID, first_name,last_name,DOB,total_credits,enrollment_date,expected_graduation_date,preferred_name)
                 VALUES
                 (:V_number, :department, :advisor, :EID, :first_name, :last_name, :DOB, :total_credits, :enrollment_date, :expected_graduation_date,:preferred_name)";
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->bindValue(':V_number', $_POST["V_number"]);
    $stmt->bindValue(':department', $_POST["department"]);
    $stmt->bindValue(':advisor', $_POST["advisor"]);
    $stmt->bindValue(':EID', $_POST["EID"]);
    $stmt->bindValue(':first_name', $_POST["first_name"]);
    $stmt->bindValue(':last_name', $_POST["last_name"]);
    $stmt->bindValue(':DOB', $_POST["DOB"]);
    $stmt->bindValue(':total_credits', $_POST["total_credits"]);
    $stmt->bindValue(':enrollment_date', $_POST["enrollment_date"]);
    $stmt->bindValue(':expected_graduation_date', $_POST["expected_graduation_date"]);
    $stmt->bindValue(':preferred_name', $_POST["preferred_name"]);
    $stmt->execute();
}

function getStudent()
{
    global $conn;
    
    if ($_POST["V_Number"]) {
        
        $sqlQuery = "SELECT V_number as `V-Number`,
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
                 
                     FROM student
                     WHERE V_number = :V_Number";
        
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':V_Number', $_POST["V_Number"]);
        $stmt->execute();
        
        echo json_encode($stmt->fetch());
    }
}

function updateStudent()
{
    global $conn;
    
    if ($_POST['V-Number']) {
        
        $sqlQuery = "UPDATE student
                        SET
                        V_number = :V_Number, 
                        department = :department, 
                        advisor = :advisor, 
                        EID = :EID, 
                        first_name = :first_name,
                        last_name = :last_name,
                        DOB = :DOB,
                        total_credits = :total_credits,
                        enrollment_date = :enrollment_date,
                        expected_graduation_date = :expected_graduation_date,
                        preferred_name = :preferred_name
                    WHERE V_Number = :V_number";
        
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':V_number', $_POST["V_number"]);
        $stmt->bindValue(':department', $_POST["department"]);
        $stmt->bindValue(':advisor', $_POST["advisor"]);
        $stmt->bindValue(':EID', $_POST["EID"]);
        $stmt->bindValue(':first_name', $_POST["first_name"]);
        $stmt->bindValue(':last_name', $_POST["last_name"]);
        $stmt->bindValue(':DOB', $_POST["DOB"]);
        $stmt->bindValue(':total_credits', $_POST["total_credits"]);
        $stmt->bindValue(':enrollment_date', $_POST["enrollment_date"]);
        $stmt->bindValue(':expected_graduation_date', $_POST["expected_graduation_date"]);
        $stmt->bindValue(':preferred_name', $_POST["preferred_name"]);
        $stmt->execute();
    }
}

function deleteStudent()
{
    global $conn;
    
    if (!empty($_POST["eID"])) {
        
        
        
        $sqlQuery = "DELETE FROM student WHERE EID = :EID";
        
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':EID', $_POST["eID"]);
        $stmt->execute();
    }
}

if(!empty($_POST['action']) && $_POST['action'] == 'listStudents') {
    listStudents();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addStudent') {
    addStudent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getStudent') {
    getStudent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateStudent') {
    updateStudent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteStudent') {
    deleteStudent();
}

?>
