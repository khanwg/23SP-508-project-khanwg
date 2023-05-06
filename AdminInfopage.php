<?php
require_once('AdminInfopage-Action.php');

// Call the listStudents() function here
listStudents();

// Start the HTML table
echo "<table class='table table-bordered table-striped'>";
echo "<thead class='thead-dark'>";
echo "<tr>";
echo "<th scope='col'>V-Number</th>";
echo "<th scope='col'>Department Name</th>";
echo "<th scope='col'>Advisor Name</th>";
echo "<th scope='col'>eID</th>";
echo "<th scope='col'>Name</th>";
echo "<th scope='col'>DOB</th>";
echo "<th scope='col'>Total Credits</th>";
echo "<th scope='col'>Enrollment Date</th>";
echo "<th scope='col'>Expected Graduation Date</th>";
echo "<th scope='col'>Action</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

// Check if $output is defined and not empty
if (isset($output) && !empty($output['data'])) {
    // Loop through the data and create a row for each record
    foreach ($output['data'] as $row) {
        echo "<tr>";
        echo "<td>" . $row[0] . "</td>";
        echo "<td>" . $row[1] . "</td>";
        echo "<td>" . $row[2] . "</td>";
        echo "<td>" . $row[3] . "</td>";
        echo "<td>" . $row[4] . "</td>";
        echo "<td>" . $row[5] . "</td>";
        echo "<td>" . $row[6] . "</td>";
        echo "<td>" . $row[7] . "</td>";
        echo "<td>" . $row[8] . "</td>";
        echo "<td>" . $row[9] . "</td>";
        echo "</tr>";
    }
} else {
    // If $output is not defined or empty, display a message
    echo "<tr><td colspan='10'>No data found.</td></tr>";
}

// End the HTML table
echo "</tbody>";
echo "</table>";
?>