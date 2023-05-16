<?php
    session_start();
    include "../db_conn.php";

    $code = $_GET['classCode'];

    $name = $_SESSION['name'];
    $studentnum = $_SESSION['studnum'];    
    
    $query = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row = mysqli_fetch_array($query);
    $HRS_Rendered = $row['HRS_Rendered'];

    $stud_query = mysqli_query($conn, "SELECT * FROM student_info WHERE studentnum = '$studentnum'");
    $fetch = mysqli_fetch_array($stud_query);
    $month = $fetch['month'];
    $day = $fetch['day'];
    $year = $fetch['year'];
    $email = $fetch['email'];
    $number = $fetch['contact_number'];


?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <link rel = "stylesheet" href = "../css/student_dashboard1.css">

    <style> 
        .container 
        {
            font-size:17px;
        }
    </style>

</head>

<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

     <!--SIDE BAR-->
     <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <!--Background Side Bar-->

            <br><br>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>

            <h6 style="color: white;font-size:20px;"><b> Student</b></h6> <br><br><hr style="background-color:white;width:200px;">
            
            <a href="Student_Dashboard.php?classCode=<?php echo $code; ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp; Dashboard </a>
            <a href="Student_DashHome.php?classCode=<?php echo $code; ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments" style="font-size: 1.3em;"></i>&emsp;Discussion </a>
            <a href="Student_Calendar.php?classCode=<?php echo $code; ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-clock-o" style="font-size: 1.3em;"></i>&emsp;Schedule </a>
            <a href="Student_Profile.php?classCode=<?php echo $code; ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-user" style="font-size: 1.3em;"></i>&emsp;Profile </a>
            
            <a href="Student_ClassList.php" style="margin-top:80%;">
                <i class = "fa fa-arrow-circle-left fa-1x" style="font-size: 1em;"></i> &nbsp; Back to Class List
            </a> 
            
            
        </center>
    </div>

    
    <!--MAIN CONTENT -->
    <div class = "main">
        <!--TOP NAV-->
        <div class = "topnavbar" style="z-index:3;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
        
            <div class="topnavbar-right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                 <center>Logout  <i class = "fa fa-sign-out fa-1x"></i></center>
                </a>
            </div>
        </div> <!--end of topnavbar-->
        <br><br><br><br>
        <img src="../images/GREY-BG.png" alt="background" class = "sidebar_bg">
        <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;"> Student Profile</h1>
        <hr style="background-color:maroon;border-width:2px;">

        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                <div class="container">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Student Number</label>
                            <input type="text" class = "form-control" placeholder="<?php echo $studentnum; ?>" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Name</label>
                            <input type="text" class = "form-control" placeholder="<?php echo $name; ?>" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Contact Number</label>
                            <input type="text" class = "form-control" placeholder="<?php echo $number; ?>" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Email</label>
                            <input type="text" class = "form-control" placeholder="<?php echo $email; ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Month</label>
                            <input type="text" class = "form-control" placeholder="<?php echo $month; ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Day</label>
                            <input type="text" class = "form-control" placeholder="<?php echo $day; ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Year</label>
                            <input type="text" class = "form-control" placeholder="<?php echo $year; ?>" disabled>
                        </div>
                        
                    </div>
                    </form>
                    
                </div>
            </div>
            </div>
        </div>

    </div> <!--end of main-->

    
</div> <!--end of container-fluid-->
    
</body>
</html>

