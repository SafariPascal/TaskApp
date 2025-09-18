<?php
$servername = "localhost";
$username = "root";   
$password = "1224";  
$dbname = "taskapp";    


  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
  }
  echo "Connected successfully";
  echo '<form action="Plugins/PHPMailer/Mail.php" method="get">
          <button type="submit">Verify</button>
        </form>';
  return $conn;
?>
