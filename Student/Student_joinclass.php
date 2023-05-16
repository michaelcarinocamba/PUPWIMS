<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['studnum']))
    {
        header("Location: StudentLogin.php?LoginFirst");
    }

$student = $_SESSION['studnum'];
$name = $_SESSION['name'];

if(isset($_POST['joinclass'])){

    if(isset($_POST['code'])){
        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    
    $code = validate($_POST['code']);
    if(empty($code))
    {
        header ("Location: Student_joinclass.php?error=Class Code is Required");
        exit();
    }

    $sql = "SELECT * FROM create_class WHERE class_code ='$code'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) === 1) 
    {
        $row = mysqli_fetch_assoc($result);
        if($row['class_code'] === $code)
        {
            $classcode = strip_tags($_POST['code']); 
            $classcode = str_replace(' ', '', $classcode);
        
            $sql1 = "SELECT * FROM create_class WHERE student_list like '%$student%'";
            $result1 = mysqli_query($conn, $sql1);
            if(mysqli_num_rows($result1) === 0)
            {
                $data_query = mysqli_query($conn, "UPDATE create_class SET student_list=CONCAT(student_list,'$student ,') WHERE class_code='$classcode'");

                $student_query = mysqli_query($conn, "UPDATE student_info SET class_code = '$classcode' WHERE studentnum = '$student'");

                $query3 = mysqli_query($conn, "INSERT INTO join_class VALUES('$student','$classcode', '$name')");

                $_SESSION['code'] = $row['class_code'];
                $_SESSION['faculty']  = $row['facultynum']; 
                $_SESSION['course'] = $row['course'];
                $_SESSION['yrsection'] = $row['year_section'];
                $_SESSION['subjname'] = $row['subject_name'];
                header("Location: Student_DashHome.php");
                exit();
            }
            else
            {
                header("Location: Student_joinclass.php?error='You've already joined this class.'");
            }
        }
}
    if(mysqli_num_rows($result) === 0) 
    {
        echo "Error";
        header("Location: Student_joinclass.php?error='Wrong Class Code'");
    }
}

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Join Class</title> <link rel="shortcut icon" href= "images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel = "stylesheet" href = "../css/student_joinclass.css">
</head>

 
<body>
<div class = "container-fluid" id = "grad1"> <!--CSS IS FIX IN BOOTSTRAP-->

    <!--TOP NAV-->
    <div class = "topnavbar">
        <div class="topnavbar-right">
            <a target = "_self" href="../Homepage/Homepage.php" > 
                Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
            </a>
        </div>
    </div> <!--end of topnavbar-->

        <br><br><br><br><br>

    <div class = "container">
        <div class="row">
           
            <div class="col-4 fixed">
                <div class="card codestyle">
                    <div class="card-body text-center text">
                        <i class = " fa fa-user-plus fa-5x"></i>
                        <h2 class="font-weight-bold">JOIN CLASS</h2>
                        
                        <hr class="dashed">  
                        <h4> Class Code </h4> 
                        <p> Ask your OJT Adviser for the class code. Then, enter it here.</p> <br>
                            <form method="POST">
                                <input type="text" name="code" id="code" placeholder="Class code">
                                    <br><?php if(isset($_GET['error'])){?>
                                    <p class="error"> <?php echo$_GET['error']; ?></p>
                                        <?php } ?> 
                                <br>
                                
                                <button  type="submit" name="joinclass" class="button hbutton" id="create_class_button">Join</button>                                  
                            </form>
                            <br>  

                    </div>
                    
                </div>               
                
            </div>

            <div class="col-8 fixed">
                <div class="card imgstyle">
                    <div class="card-body text-center ">
                        <img alt="Join Class" class = "img-join" src="../images/joinimage.jpg"><br><br>
                    </div>
                </div>
                
            </div>
        </div> <!--end of row-->
        


    </div> <!--end of container-->
</div> <!--end of container-fluid-->
<br><br>
</body>

</html>