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
        
        // Checks for EIDs in database that match user entered username
        $sql = "SELECT V_number, EID, password_hash, ACCESS_TYPE FROM EID_Login WHERE EID = :username";
        
        if ($stmt = $conn->prepare($sql)) {
            
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            
            $param_username = trim($_POST["username"]);
            
           
            if ($stmt->execute()) {
                // Check if username exists, if yes then check for password
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $hashed_password = $row['password_hash'];
                        //checks if the stored hash matches the password entered 
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["V_number"] = $row['V_number'];
                            $_SESSION["EID"] = $row['EID'];
                            $_SESSION["ACCESS_TYPE"]=$row['ACCESS_TYPE'];
                            
                            if ($row['ACCESS_TYPE'] == 'STUDENT') {
                                header("location: studentProfile.php");
                                exit;
                            } elseif ($row['ACCESS_TYPE'] == 'ADMIN') {
                                header("location: AdminInfopage.php");
                                exit;
                            }
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'Invalid Password. Please try again.';
                        }
                    }
                } else {
                    // Message for if username does not exist
                    $username_err = 'No existing account matches username.';
                }
            } else {
                // Handle errors during statement execution
                echo "Error: " . $stmt->error;
            }
        }
        
        
        $stmt = null;
    }
    
    // Close connection
    $conn = null;
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

 
                

