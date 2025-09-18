<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';   // PHPMailer
require 'dboperation.php';       // DB functions

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname   = trim($_POST['fullname']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirmPwd = $_POST['confirm-password'];

    // Check password match
    if ($password !== $confirmPwd) {
        $message = "❌ Passwords do not match.";
    } else {
        // Register user in DB
        $result = registerUser($fullname, $email, $password);

        if (strpos($result, "✅") !== false) {
            // ✅ Send verification email
            $verifyLink = "http://localhost/TaskApp/verify.php?email=" . urlencode($email);

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'yourgmail@gmail.com';    // your Gmail
                $mail->Password   = 'your-app-password';      // Gmail App password
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('yourgmail@gmail.com', 'ICS 2.2');
                $mail->addAddress($email, $fullname);

                $mail->isHTML(true);
                $mail->Subject = "Welcome to ICS 2.2! Account Verification";
                $mail->Body    = "
                    <p>Hello <b>$fullname</b>,</p>
                    <p>Thank you for signing up on <b>ICS 2.2</b>.</p>
                    <p>To activate your account, please click the link below:</p>
                    <p><a href='$verifyLink'>Verify My Account</a></p>
                    <br>
                    <p>Regards,<br>Systems Admin<br>ICS 2.2</p>
                ";

                $mail->send();
                $message = "✅ Registration successful! A verification email has been sent to <b>$email</b>.";
            } catch (Exception $e) {
                $message = "⚠️ User saved but email could not be sent. Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = $result; // DB error message
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up Result</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>Sign Up</h2>
    <p style="text-align:center; color: 
       <?php echo strpos($message, '✅') !== false ? 'green' : 'red'; ?>;">
       <?php echo $message; ?>
    </p>
    <a href="index.html">⬅ Back to Sign Up</a>
  </div>
</body>
</html>
