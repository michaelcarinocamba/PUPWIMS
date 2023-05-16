<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require '../db_conn.php';

if(isset($_POST["email"]))
{
    $emailTo = $_POST['email'];
    $checkemail = mysqli_query($conn, "SELECT * FROM student_info WHERE email = '$emailTo'");
    if(mysqli_num_rows($checkemail) === 1)
    {
        $code = uniqid(true);
        $query = mysqli_query($conn, "INSERT INTO student_reset(code, email) VALUES('$code' , '$emailTo')");
        if(!$query)
        {
            exit("Error");
        }
        $fetch = mysqli_fetch_array($checkemail);
        $name = $fetch['name'];
    
        $mail = new PHPMailer(true);
    
        try {
            //Server settings
                    //Enable verbose debug output
            $mail->isSMTP();
            $mail->Host       = 'sg2plzcpnl493881.prod.sin2.secureserver.net';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'pupwims_mailer@pup-wims.site';
            $mail->Password   = 'pupwims123!';
            $mail->SMTPSecure = 'ssl';     
            $mail->Port       = 465;    
            //Recipients
            $mail->setFrom('pupwims_mailer@pup-wims.site', 'PUP WIMS Admin');
            $mail->addAddress($emailTo);       //Add a recipient           
        
        
            //Content
            $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/Student.php?code=$code";
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Polytechnic University of the Philippines account email forgot password';
            $mail->Body    = "
                                <table style='border-collapse: collapse; min-width: 100%; font-family: Arial, Helvetica, sans-serif;'>
                                    <thead>
                                        <tr style='background-color:#AA5656;'>
                                            <th style='color:white; font-size:25px; padding: 14px 30px;'> PUP WIMS Forgot Password </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style='background-color: #FFFBF5;border: 1px solid #ddd; font-family: Helvetica; font-size: 18px;margin-left:3%;'>  <br><p>&emsp;Hi, $name! <br><br>&emsp;&emsp; Click <a href='$url' style='text-decoration:none;'>this link</a> for you to proceed in updating your password.</p><br>
                                            <hr style='background-color:maroon;border-width:2px;'>
                                            <i style='font-size:15px; font-weight: 0.5px;'>&emsp;Sincerely, <br>
                                            &emsp;Polytechnic University of the Philippines, Internship</i><br></td>
                                        </tr>
                                    </tbody>
                                </table>
                               
                                
                                ";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            echo "<script> location.href='../ForgotPass/SuccessRequest.php'; </script>";
                exit;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        exit();
    }
    else
    {
        $error = "The email address provided was not found.";
        header ("Location: Student.php?change&error=$error");
        exit();
    }
   
}

if(isset($_REQUEST["code"]))
{
    $uniqcode = $_GET["code"];

    $getEmailQuery = mysqli_query($conn, "SELECT email FROM student_reset WHERE code = '$uniqcode'");

    if(mysqli_num_rows($getEmailQuery) == 0)
    {
        exit("Can't find page");
    }

    if(isset($_POST["password"]))
    {
        $pw = $_POST["password"];
        

        $row = mysqli_fetch_array($getEmailQuery);
        $email = $row["email"];

        $query = mysqli_query($conn, "UPDATE student_info SET password = '$pw' WHERE email = '$email'");
        if($query)
        {
            $query = mysqli_query($conn, "DELETE FROM student_reset WHERE code = '$uniqcode'");
        }
        else{
            exit("Something went wrong");
        }

        
        echo "<script> location.href='Student.php?successpassword'; </script>";
            exit;

    }

    ?>

    <!DOCTYPE html>
    <html lang="en" style = "height: auto;">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PUPSJ Intern Reset Password</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
        <link rel = "stylesheet" href = "../css/student_loginstyle.css">
        <style>
                    section .Background:before {
            content: '';
            position:absolute;
            top:0;
            Left:0;
            width:100%;
            height:100%;
            background:linear-gradient(225deg,#2f2323,#6d1d35);
            z-index:1;
            mix-blend-mode:screen;
        }
        </style>
    </head>
    <body>
        <section>
            <div class = "Background">
                <img src = "../images/bgsj.jpg">
            </div>
            <div class = "Content">
                <div class = "form" >
                    <center>
                <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br>
                    
                <h1>New Password</h1><br>
                
                <h3>Please enter your new password</h3>
                </center>
                
                <form method="post">
                
                
                <div class = "input">
                    <span>Password</span><input type="password" name="password" placeholder="New Password"><br><br>
                    <center>
                    <button type="submit" name="submit" class="button" style="width:auto;color:white;">Update Password</button> <br><br>
                    
                    <p>Go back to <a href="../Student/Student_Login.php">Sign in</a><br><br></p>
                    <h6> By using this service, you understood and agree to the PUP Online Services <a href="https://www.pup.edu.ph/terms/" target="_blank">Terms of Use</a> and <a href="https://www.pup.edu.ph/privacy/" target="_blank"> Privacy Statement</a></h6>
                    </center>
                </div>
                </form>
            </div>
            </div>
            
        </section>
    </body>
    </html>
 <?php
}

if(isset($_REQUEST['successpassword']))
{
    ?>
        
    <!DOCTYPE html>
    <html lang="en" style = "height: auto;">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Password</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
        <link rel = "stylesheet" href = "../css/student_loginstyle.css">
        <style>
                    section .Background:before {
            content: '';
            position:absolute;
            top:0;
            Left:0;
            width:100%;
            height:100%;
            background:linear-gradient(225deg,#2f2323,#6d1d35);
            z-index:1;
            mix-blend-mode:screen;
        }
        </style>
    </head>
    <body>
        <section>
            <div class = "Background">
                <img src = "../images/bgsj.jpg">
            </div>
            <div class = "Content">
                <div class = "form">
                    <center>
                <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br>
                    
                <h1>Password Updated</h1><br>
                
                <h3>Please proceed to the login</h3><br><br>

                
                <form action="../Student/Student_Login.php" method="post">
                
                <button type="submit" name="submit" class="button" style="color:white; background-color:maroon;">Login</button> <br><br><br>
                
                        
                <h6> By using this service, you understood and agree to the PUP Online Services <a href="https://www.pup.edu.ph/terms/" target="_blank">Terms of Use</a> and <a href="https://www.pup.edu.ph/privacy/" target="_blank"> Privacy Statement</a></h6>
                    </center>
                </div>
                </form>
            </div>
            </div>
            
        </section>
    </body>
    </html>
    <?php
}
if(isset($_REQUEST['change']))
{
?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title><link rel="shortcut icon" href= "../images/pupsjlogo.png">
    <link rel = "stylesheet" href = "../css/student_loginstyle.css">

    <style>
                section .Background:before {
            content: '';
            position:absolute;
            top:0;
            Left:0;
            width:100%;
            height:100%;        
            background:linear-gradient(225deg,#2f2323,#6d1d35);
            z-index:1;
            mix-blend-mode:screen;
        }
        .error {
            color:maroon;
            text-indent: 5px;
            margin-bottom: 10px;
            display:inline-block;
            Letter-spacing:1px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <section>
        <div class = "Background">
            <img src = "../images/bgsj.jpg">
        </div>
        <div class = "Content">
            <div class = "form" >
                <center>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br>
                
            <h1>Forgot Password</h1><br>
            
            <h3>Please enter your email account</h3>
            </center>
            
            <form method="post">
            
            
            <div class = "input">
                <span>Email Address</span><input type="email" name="email" autocomplete="off" required><br>
                <center>
                <?php if(isset($_GET['error']))
                    {
                        ?>
                        <p class="error"><?php echo $_GET['error']; ?></p>
                        <?php
                    }
                    ?>
                    <br>
                    
                <button type="submit" name="submit" class="button" style="width:auto;color:white;">Request new password</button> <br>
                
                <p>Go back to <a href="../Student/Student_Login.php">Sign in</a><br><br></p>
                <h6> By using this service, you understood and agree to the PUP Online Services <a href="https://www.pup.edu.ph/terms/" target="_blank">Terms of Use</a> and <a href="https://www.pup.edu.ph/privacy/" target="_blank"> Privacy Statement</a></h6>
                </center>
            </div>
            </form>
        </div>
        </div>
        
    </section>
</body>
</html>
<?php
}
?>