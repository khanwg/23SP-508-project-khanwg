<?php
require_once('connection.php');
session_start();

// if the user is logged in, store their name and EID and display their name along with the page.
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    $session_EID = $_SESSION["EID"];// user ID.
    $sqlQuery =
    "SELECT first_name
                FROM student WHERE EID = :session_EID;";
    $stmt = $conn->prepare($sqlQuery);
    $stmt->bindParam(':session_EID', $session_EID, PDO::PARAM_STR);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the username and passwords from the form
    $username = $_SESSION['EID'] ?? null;
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];
    
    // Check if the current password is correct
    $stmt = $conn->prepare("SELECT password_hash FROM Student_Login WHERE EID = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!password_verify($currentPassword, $row['password_hash'])) {
        $error = 'The current password is incorrect.';
    } elseif ($newPassword !== $confirmNewPassword) {
        $error = 'The new passwords do not match.';
    } else {
        // Update the user's password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE Student_Login SET password_hash = ? WHERE EID = ?");
        $stmt->execute([$hashedPassword, $username]);
        
        // Redirect to a success page
        header('Location: successful_password_change.php');
        exit();
    }
}
?>






<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
</head>
<body>
    <h1>Change Password</h1>

    <?php if (isset($error)): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" id="current_password" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required><br>

        <label for="confirm_new_password">Confirm New Password:</label>
        <input type="password" name="confirm_new_password" id="confirm_new_password" required><br>

        <input type="submit" value="Change Password">
    </form>
</body>
</html>
   
    