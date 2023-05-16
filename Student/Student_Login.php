<?php
session_start();

include "../db_conn.php";


if(isset($_POST['studentnumber']) && isset($_POST['password']) && isset($_POST['month']) && isset($_POST['day']) && isset($_POST['year']))
{
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


$studnum = validate($_POST['studentnumber']);
$pass = validate($_POST['password']);
$month = validate($_POST['month']);
$day = validate($_POST['day']);
$year = validate($_POST['year']);

if(empty($studnum) && empty($pass) && $month == "Birth Month" && $day == "Birth Day" && $year == "Birth Year")
{
    header ("Location: Student_Login.php?error4=Information is Required");
    exit();
    
}

else if(empty($studnum))
{
    header ("Location: Student_Login.php?error1=Student Number is Required");
    exit();
    
}
else if ($month == "Birth Month" || $day == "Birth Day" || $year == "Birth Year")
{
    header ("Location: Student_Login.php?error3=Birthdate is Required");
    exit();
}
else if (empty($pass))
{
    header ("Location: Student_Login.php?error2=Password is Required");
    exit();
}



$sql = "SELECT * FROM student_info WHERE studentnum='$studnum' AND password='$pass' AND month='$month' AND day='$day' AND year='$year'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) === 1) 
{
    $row = mysqli_fetch_assoc($result);
    
    if($row['studentnum'] === $studnum && $row['password'] === $pass && $row['month'] === $month && $row['day'] === $day && $row['year'] === $year)
    {

        $data_query = mysqli_query($conn, "SELECT * FROM student_info WHERE studentnum LIKE '$studnum' AND password LIKE '$pass' AND change_pass LIKE 'yes'");
        if (mysqli_num_rows($data_query) === 0) 
        {

            $_SESSION['studnum'] = $row['studentnum'];
            $_SESSION['month'] = $row['month'];
            $_SESSION['day'] = $row['day'];
            $_SESSION['year'] = $row['year'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            header ("Location: Student_ChangePassword.php");
            exit();
        }
        else if (mysqli_num_rows($data_query) === 1) 
        {
            $sql1 = "SELECT * FROM create_class WHERE student_list LIKE '%$studnum%'";
            $result1 = mysqli_query($conn, $sql1);
            $row1 = mysqli_fetch_assoc($result1);

            $_SESSION['studnum'] = $row['studentnum'];
            $_SESSION['month'] = $row['month'];
            $_SESSION['day'] = $row['day'];
            $_SESSION['year'] = $row['year'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['yrsection'] = $row1['year_section'];
            
            header("Location: Student_ClassList.php?loginsuccess");
            exit();
        }
    }
}

if(mysqli_num_rows($result) === 0) 
    {
        header("Location: Student_Login.php?error4=Incorrect Student Number or Password");
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
    
    <title>PUPSJ Intern Login</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
    
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
                
            <h1>Log in</h1><br><h4>Student Module</h4><br><br>
            
            <h3>Please log in to your account</h3>
            </center>
            
            <form method="post">
            
            
                <div class = "input">
                
                <?php if(isset($_GET['error4'])){?>
                <p class="error4"> <?php echo$_GET['error4']; ?></p>
                <?php } ?><br>
                    <span>Student Number</span>
                    <input type="text" name="studentnumber" id="studentnumber" placeholder="Student Number">
                    <br><?php if(isset($_GET['error1'])){?>
                <p class="error1"> <?php echo$_GET['error1']; ?></p>
                <?php } ?><br>
                    <span>Birthday</span>
                    <br>
                    <select name="month" id="month">
                    <option value="Birth Month">Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                    </select>
                    <select name="day" id="day">
                    <option value="Birth Day">Day</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    </select>


                    <select name="year" id="year">
                    <option value="Birth Year">Year</option>
                    <option value="2012">2012</option>
                    <option value="2011">2011</option>
                    <option value="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                    <option value="2004">2004</option>
                    <option value="2003">2003</option>
                    <option value="2002">2002</option>
                    <option value="2001">2001</option>
                    <option value="2000">2000</option>
                    <option value="1999">1999</option>
                    <option value="1998">1998</option>
                    <option value="1997">1997</option>
                    <option value="1996">1996</option>
                    <option value="1995">1995</option>
                    <option value="1994">1994</option>
                    <option value="1993">1993</option>
                    <option value="1992">1992</option>
                    <option value="1991">1991</option>
                    <option value="1990">1990</option>
                    <option value="1989">1989</option>
                    <option value="1988">1988</option>
                    <option value="1987">1987</option>
                    <option value="1986">1986</option>
                    <option value="1985">1985</option>
                    <option value="1984">1984</option>
                    <option value="1983">1983</option>
                    <option value="1982">1982</option>
                    <option value="1981">1981</option>
                    <option value="1980">1980</option>
                    </select>
                    <br>
                    
                    <?php if(isset($_GET['error3'])){?>
                    <p class="error3"> <?php echo$_GET['error3']; ?></p>
                    <?php } ?><br>


                    <span>Password</span><br> <input type="password" name="password" id="password" placeholder="Password">
                    <br><?php if(isset($_GET['error2'])){?>
                    <p class="error2"> <?php echo$_GET['error2']; ?></p>
                    <?php } ?><br>
            
                    <center>
                    <button type="submit" class="button" style="background-color:maroon; color: white;">Login</button> <br><br>
                    

                    <p><a href="../ForgotPass/Student.php?change">Forget Password?</a><br><br></p>
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