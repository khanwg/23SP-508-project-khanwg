<?php // set up phase..
require_once('connection.php');
// PHP colors: https://brandpalettes.com/php-logo-colors/#:~:text=The%20official%20colors%20of%20PHP%20are%20light,blue%2C%20blue%2C%20dark%20blue%2C%20white%2C%20and%20black.
echo '<body style = "background-color: #777BB3">';
echo "<p style='text-align: right;'>Student View...
                </p>"; // admim view if user is a registerd admin
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Homepage </title>
	</head>
	<body> 
		<?php
		// $userName = "DEFAULT_USER"; // get actual user name of signed in host.
            echo "<p style = 'font-size: 50px; margin-bottom: 0.125px;'>
                Welcome, " . $userName . "! 
                </p>";
            
            // to make a text a link use: <a href = "name.php"> text </a>
            echo "<p style = 'font-size: 30px';'> 
                <strong>Resources:</strong>
                    <p style = 'font-size: 15px;'>
                        
                        <a href = 'Student_Info.php'> 
                            <strong>Student information</strong> 
                        </a>
                        <br>

                        <a href = 'Student_Course_History.php'> 
                            Course History 
                        </a>
                        <br>

                        <a href = 'Student_Clubs.php'> 
                            Club Involvment 
                        </a>
                        <br>
                    </p>                
                </p>";
            // Add this later: "<" means you can edit..
        ?>
	</body>
</html>

