<?php
require_once ('connection.php');
global $conn;

function listStudents(){
    global $conn;
    
    $sqlQuery = "SELECT s.V_number as `V-Number`,d.department_name,
                        concat(a.first_name,' ',a.last_name) as `advisor_name`,
                         s.EID as `eID`,
                         concat(s.first_name,' ',s.last_name) as `Name`,
                         DOB as `DOB`,
                         total_credits as `Total Credits`,
                         enrollment_date as `Enrollment Date`,
                         expected_graduation_date as `Expected Graduation Date`
                FROM student s
                INNER JOIN department d ON(s.department = d.department_id)
                INNER JOIN academic_advisor a ON(a.academic_advisor_id = s.advisor)";
    
    if (! empty($_POST["search"]["value"])) {
        $sqlQuery .= ' WHERE (s.first_name LIKE "%' . $_POST["search"]["value"] . '%" OR s.last_name LIKE "%' . $_POST["search"]["value"] . '%" ) ';
    }
    
    if (! empty($_POST["order"]) && isset($_POST['order'][0]['column']) && isset($_POST['order'][0]['dir'])) {
        $sqlQuery .= ' ORDER BY ' . ($_POST['order'][0]['column'] + 1) . ' ' . $_POST['order'][0]['dir'] . ' ';
    } else {
        $sqlQuery .= ' ORDER BY s.V_Number DESC ';
    }
    $stmt = $conn->prepare($sqlQuery);
    $stmt->execute();
    
    $numberRows = $stmt->rowCount();
    
    if (isset($_POST["length"]) && $_POST["length"] != -1 && isset($_POST['start'])) {
        $sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->execute();
    
    $dataTable = array();
    
    while ($sqlRow = $stmt->fetch()) {
        $dataRow = array(
            $sqlRow['V-Number'],
            $sqlRow['department_name'],
            $sqlRow['advisor_name'],
            $sqlRow['eID'],
            $sqlRow['Name'],
            $sqlRow['DOB'],
            $sqlRow['Total Credits'] ?? "NA",
            $sqlRow['Enrollment Date'] ?? "Unknown",
            $sqlRow['Expected Graduation Date'] ?? "Unknown"
          
        );
        
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

function updateStudent($conn, $V_number)
{
    if (isset($_POST['V_number'])) {
        $sqlQuery = "UPDATE student
                        SET
                        V_number = :V_number,
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
                        WHERE V_number = :V_number";
        
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
        if (!empty($_POST["expected_graduation_date"])) {
            $stmt->bindValue(':expected_graduation_date', $_POST["expected_graduation_date"]);
        } else {
            $stmt->bindValue(':expected_graduation_date', null, PDO::PARAM_NULL);
        }
        if (!empty($_POST["total_credits"])) {
            $stmt->bindValue(':total_credits', $_POST["total_credits"]);
        } else {
            $stmt->bindValue(':total_credits', null, PDO::PARAM_NULL);
        }
        $stmt->bindValue(':preferred_name', $_POST["preferred_name"]);
        $stmt->bindValue(':V_number', $V_number);
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
function sortByNameASC(){
    global $conn;
    
    $order = $_GET['order'];
    
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
    ORDER BY `Name` $order";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $data = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    
    echo json_encode($data);
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
if(!empty($_POST['action']) && $_POST['action'] == 'deleteStudent') {
    sortByNameASC();
}


?>
