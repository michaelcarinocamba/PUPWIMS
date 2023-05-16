<?php
session_start();

include "../db_conn.php";

if(isset($_POST['coordinatornumber']) && isset($_POST['password']))
{
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


$coordinator = validate($_POST['coordinatornumber']);
$pass = validate($_POST['password']);

if(empty($coordinator) && empty($pass))
{
    header ("Location: Coordinator_Login.php?error3=Information is Required");
    exit();
    
}

else if(empty($coordinator))
{
    header ("Location: Coordinator_Login.php?error1=Coordinator number is Required");
    exit();
    
}

else if (empty($pass))
{
    header ("Location:  Coordinator_Login.php?error2=Password is Required");
    exit();
}

//changes

$sql = "SELECT * FROM coordinator_info WHERE coordinatornum = '$coordinator' AND password = '$pass'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) === 1) 
{
    $row = mysqli_fetch_assoc($result);
    if($row['coordinatornum'] === $coordinator && $row['password'] === $pass)
    {

        $_SESSION['coordinator'] = $row['coordinatornum'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['dept'] = $row['department'];

        header("Location: Coordinator_Dashboard.php?LoginSuccess");
        exit();
    }
}
if(mysqli_num_rows($result) === 0) 
    {
        header("Location: Coordinator_Login.php?error3=Incorrect Coordinator number or Password");
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
    <title>PUPSJ Coordinator Login</title><link rel="shortcut icon" href= "../images/pupsjlogo.png">
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
                
            <h1><b>PUP-WIMS</b></h1> <br> <h4>Coordinator Module</h4><br><br>
            
            
            <h3>Please log in to your account</h3>
            </center>
            
            <form method="post">
                <div class = "input">
                <?php if(isset($_GET['error3'])){?>
                <p class="error3"> <?php echo$_GET['error3']; ?></p>
                <?php } ?><br>
                    <span>Coordinator Number</span>
                    <input type="text" name="coordinatornumber" id="coordinatornumber" placeholder="Coordinator Number">
                    <br><?php if(isset($_GET['error1'])){?>
                <p class="error1"> <?php echo$_GET['error1']; ?></p>
                <?php } ?><br>
                    <span>Password</span><br> <input type="password" name="password" id="password" placeholder="Password">
                    <br><?php if(isset($_GET['error2'])){?>
                <p class="error2"> <?php echo$_GET['error2']; ?></p>
                <?php } ?><br>
            
                    <center>
                    <button type="submit" class="button" style="background-color:maroon; color: white;">Login</button></a> <br><br>
                    

                    <p><a href="../ForgotPass/Coordinator.php?change">Forget Password?</a><br><br></p>
                    <h6> By using this service, you understood and agree to the PUP Online Services <a href="https://www.pup.edu.ph/terms/" target="_blank">Terms of Use</a> and <a href="https://www.pup.edu.ph/privacy/" target="_blank"> Privacy Statement</a></h6>
                
                    </center>
                </div>
            </div>
            </form>
        </div>
        </div>
        
    </section>
</body>
</html>