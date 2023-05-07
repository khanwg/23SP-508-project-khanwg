<?php

require_once ('connection.php');
require_once('AdminInfopage-Action.php');

global $conn;
$v_number = $_GET['v_number'];
$stmt = $conn->prepare("CALL deleteStudent($v_number)");

if ($stmt->execute()) {
    header("Location: AdminInfopage.php");
    exit;
} else {
    echo "Failed to delete student";
}
?>