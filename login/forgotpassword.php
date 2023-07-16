<?php 
require("connection.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function sendMail($email,$reset_token){
    require ("PHPMailer/PHPMailer.php");
    require ("PHPMailer/SMTP.php");
    require ("PHPMailer/Exception.php");
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'chitrakarnoil@gmail.com';                     //SMTP username
        $mail->Password   = 'rzzdlkmhyudlwkpg';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('chitrakarnoil@gmail.com', 'BeatboxNp');
        $mail->addAddress($email);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Password Reset Link from BeatboxNp';
        $mail->Body    = "We got a request from you to reset your password! <br>Click the link below </br>
        <a href='http://localhost/login/updatepassword.php?email=$email&reset_token=$reset_token'>Reset Password</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if(isset($_POST['send-reset-link'])){
    $query="SELECT * FROM `registered_users` WHERE `email`='$_POST[email]'";
    $reuslt=mysqli_query($con,$query);
    if($reuslt)
    {
        if(mysqli_num_rows($reuslt)==1){
            $reset_token=bin2hex(random_bytes(16));
            date_default_timezone_set('Asia/kathmandu');
            $date=date("Y-m-d");
            $query="UPDATE `registered_users` SET `resettoken`='$reset_token',`resettokenexpire`='$date' WHERE `email`='$_POST[email]'";
            if(mysqli_query($con,$query) && sendMail($_POST['email'],$reset_token)){
                echo "
                <script>
                alert('password reset link sent to mail');
                window.location.href='index.php';
                </script>
                ";
            }
            else{
                echo "
                <script>
                alert('server down or try agian later');
                window.location.href='index.php';
                </script>
                ";
            }
        }
        else{
            echo "
            <script>
            alert('Email not found');
            window.location.href='index.php';
            </script>
            ";
        }
    }
    else
    {
        echo "
        <script>
        alert('Cannot run query');
        window.location.href='index.php';
        </script>
        ";
    }
}
?>