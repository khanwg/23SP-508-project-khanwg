<?php
require_once('connection.php');
session_start();

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are empty
    if (empty(trim($_POST["username"]))) {
        $username_err = 'Please enter EID';
    } elseif (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $username = trim($_POST["username"]);
        $password = trim($_POST['password']);

        // Check if the database connection was successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Select the user from the database with the matching EID
        $stmt = $conn->prepare("SELECT V_number, EID, password_hash FROM Student_Login WHERE EID = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $vNumber = $row['V_number'];
            $eid = $row['EID'];
            $hashed_password = $row['password_hash'];

            // Check if the stored hash matches the password entered 
            if (password_verify($password, $hashed_password)) {
                // Password is correct, so start a new session
                session_start();

                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["V_number"] = $vNumber;
                $_SESSION["EID"] = $eid;

                // Redirect user to profile page
                header("location: profile.php");
                exit;
            } else {
                // Display an error message if password is not valid
                $password_err = 'Invalid Password. Please try again.';
            }
        } else {
            // Message for if username does not exist
            $username_err = 'No existing account matches username.';
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>


 
 
 
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VCU eID Login</title>
    <style type="text/css">
        body {
          font: 18px Arial;
          margin: 0;
          padding: 0;
          background-color: #e6b800;
        }
       
        img {
          display: block;
          margin: 0 auto;
          max-width: 70%;
        
        }
    </style>
</head>

<body>
	
	
<img src="VCU Building.jpg" alt="Image Description">

<h1>VCU eID Login</h1>
<p>Please enter your eID and password to log in.</p>

<form method="post">
    <div>
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $username; ?>">
        <span><?php echo $username_err; ?></span>
    </div>

    <div>
        <label>Password:</label>
        <input type="password" name="password">
        <span><?php echo $password_err; ?></span>
    </div>

    <div>
        <input type="submit" value="Login">
    </div>
</form>
		
</body>
</html>

 
                

