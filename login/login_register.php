<?php
require("connection.php");
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email,$v_code){
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
        $mail->Subject = 'Email verification from BeatboxRecorder';
        $mail->Body    = "Thanks for registration!
        Click the link below to verify yourself in the beatbox community
        <a href='http://localhost/login/verify.php?email=$email&v_code=$v_code'>Verify</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
#for login
if(isset($_POST['login']))
{
    $query="SELECT * FROM `registered_users` WHERE `email`='$_POST[email_username]' OR `username`='$_POST[email_username]'";
    $result=mysqli_query($con,$query);

    if($result)
    {
        if(mysqli_num_rows($result)==1)
        {
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['is_verified']==1){
                if(password_verify($_POST['password'],$result_fetch['password']))
                {
                    #if password macthes
                    $_SESSION['logged_in']=true;
                    $_SESSION['username']=$result_fetch['username'];
                    if(isset($_POST['remember_me'])){
                        setcookie('email_username',$_POST['email_username'],time()+(60*60*24));
                        setcookie('password',$_POST['password'],time()+(60*60*24));
                    }
                    else{
                        setcookie('email_username','',time()-(60*60*24));
                        setcookie('password','',time()-(60*60*24));
                    }
                    header("location:index.php");
                }
                else{
                    #if incorrect password
                    echo "+
                    <script>
                    alert('incorrect password');
                    window.location.href='index.php';
                    </script>
                    ";
                }
            }
            else{
                echo "
                <script>
                alert('Email NOT verified');
                window.location.href='index.php';
                </script>
                ";
            }
        }
        else
        {
            echo "
            <script>
            alert('Email or username Not Registered');
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

#for resgistration

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $user_exist_query = "SELECT * FROM `registered_users` WHERE `username`='$username' OR `email`='$email'";
    $result = mysqli_query($con, $user_exist_query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $result_fetch = mysqli_fetch_assoc($result);

            if ($result_fetch['username'] == $_POST['username']) { // Use strict comparison (===)
                echo "
                <script>
                alert('".$result_fetch['username']." - username is already taken');
                window.location.href='index.php';
                </script>
                ";
            } else {
                echo "
                <script>
                alert('".$result_fetch['email']." - Email is already registered');
                window.location.href='index.php';
                </script>
                ";
            }
        } else {
            $full_name = mysqli_real_escape_string($con, $_POST['fullname']);
            $password = password_hash($_POST['password'],PASSWORD_BCRYPT);
            $v_code=bin2hex(random_bytes(16));
            $query = "INSERT INTO `registered_users`(`full_name`, `username`, `email`, `password`,`verification_code`, `is_verified`) VALUES ('$full_name','$username','$email','$password','$v_code','0')";
            
            if (mysqli_query($con, $query) && sendMail($_POST['email'],$v_code)) {
                echo "
                <script>
                alert('Registration successful');
                window.location.href='index.php';
                </script>
                ";
            }
        }
    } else {
        echo "
        <script>
        alert('Cannot run query');
        window.location.href='index.php';
        </script>
        ";
    }
}
?>
