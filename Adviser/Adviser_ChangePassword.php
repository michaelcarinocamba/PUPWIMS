<?php
    session_start();
    include "../db_conn.php";
    $facultynum = $_SESSION['faculty'];
    if(!isset($_SESSION['faculty']))
    {
        header("Location: Adviser_Login.php?LoginFirst");
    }



    if(isset($_POST["password"]))
    {
        $password = $_POST["password"];
        $conf_password = $_POST["conf_password"];
        
            if(strlen($password) < 8)
            {
                header("Location: Adviser_ChangePassword.php?error2=Password must contain atleast 8 characters.");
            }
            else if(!preg_match('/[A-Z]/', $password))
            {
                header("Location: Adviser_ChangePassword.php?error3=Password must contain atleast 1 Capital Letter.");
            }
            else if(!preg_match('/[0-9]/', $password)) 
            {
                header("Location: Adviser_ChangePassword.php?error4=Password must contain atleast 1 Number.");
            }
            else if($password === $conf_password)
            {
                
                $query = mysqli_query($conn, "UPDATE adviser_info SET password = '$password', change_pass = 'yes' WHERE facultynum = '$facultynum'");
                
                header("Location: Adviser_ClassList.php?loginsuccess");
                exit();
            }
            else
            {
                header("Location: Adviser_ChangePassword.php?error1=Password and Confirm Password doesn't match");
                exit();
            }
            
        

    }

    ?>

    <!DOCTYPE html>
    <html lang="en" style = "height: auto;">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PUPSJ Intern Change Password</title>
        <link rel = "stylesheet" href = "../css/student_loginstyle.css">
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
                    
                <h1>Change Password</h1><br>
                
                <h3>Please enter your new password</h3>
                </center>
                
                <form method="post">
                
                
                <div class = "input">
                <?php if(isset($_GET['error1'])){?>
                    <p class="error1"> <?php echo$_GET['error1']; ?></p>
                    <?php } ?>
                    <?php if(isset($_GET['error2'])){?>
                    <p class="error2"> <?php echo$_GET['error2']; ?></p>
                    <?php } ?>
                    <?php if(isset($_GET['error3'])){?>
                    <p class="error3"> <?php echo$_GET['error3']; ?></p>
                    <?php } ?>
                    <?php if(isset($_GET['error4'])){?>
                    <p class="error4"> <?php echo$_GET['error4']; ?></p>
                    <?php } ?><br>
                    <span>Password</span><input type="password" name="password" placeholder="New Password"><br><br>
                    <span>Confirm Password</span><input type="password" name="conf_password" placeholder="Confirm Password"><br><br>
                    <center>
                    <button type="submit" name="submit" class="button" style="background-color:maroon; color: white; width: auto;">Change Password</button> <br><br>
                    
                    <p>Go back to <a href="Adviser_Login.php">Sign in</a><br><br></p>
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

