<?php
    session_start();
    include "../db_conn.php";
    if(!isset($_SESSION['faculty']))
    {
        header("Location: Adviser_Login.php?LoginFirst");
    }

    $name = $_SESSION['name'];
    $faculty = $_SESSION['faculty'];
    $code = $_GET['classCode'];

    $stud_details_query = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
    $stud_row = mysqli_fetch_array($stud_details_query);
    if(!empty($stud_row)){
    $studname = $stud_row['name'];
    }

    $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    if(mysqli_num_rows($section) > 0)
    {
        $get_section = mysqli_fetch_array($section);

        $course = $get_section['course'];
        $year_section = $get_section['year_section'];
    }

    $adv_details_query = mysqli_query($conn, "SELECT * FROM adviser_info WHERE facultynum = '$faculty'");
    $adv_row = mysqli_fetch_array($adv_details_query);
    if(!empty($adv_row)){
    $facultyname = $adv_row['name'];
    $user_to = $adv_row['facultynum'];
    }


    //Query for Student Count
    $stud_count = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
    $stud_count_row = mysqli_num_rows($stud_count);

    //Query for On Going Count
    $inprocess_count_row_add = 0;
    $inprocess_count = mysqli_query($conn, "SELECT * FROM student_record WHERE status = 'Incomplete' AND class_code = '$code'");
    if(mysqli_num_rows($inprocess_count)>0) 
    {
        while(mysqli_fetch_array($inprocess_count))
        {
            $inprocess_count_row_add++;
        }
    }
   

    //Query for Completed Count
    $completed_count = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code' AND status = 'Completed'");
    $completed_count_row = mysqli_num_rows($completed_count);

    //Query for Returned Task
    $ongoing_count = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code' AND status = 'On Going'");
    $ongoing_count_row = mysqli_num_rows($ongoing_count);

    //Query for Ungraded Task
    $ungraded_count = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE type = 'Not Graded' AND class_code = '$code' AND remove = 'no'");
    $ungraded_count_row = mysqli_num_rows($ungraded_count);

    //Query for Graded Task
    $graded_count = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE type = 'Graded' AND class_code = '$code' AND remove = 'no'");
    $graded_count_row = mysqli_num_rows($graded_count);



    if(isset($_REQUEST['id']))
    {
        $notif_id = $_REQUEST['id'];

        $update_notif = mysqli_query($conn, "UPDATE notification SET notif_read = 'yes' WHERE notif_id = '$notif_id'");
    }
    
    $notification = mysqli_query($conn, "SELECT * FROM notification WHERE class_code = '$code' AND added_to = '$name' AND notif_read = 'no'");

    $notification_count = mysqli_num_rows($notification);

?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Adviser Dashboard</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <link rel = "stylesheet" href = "../css/dashboard.css">

    <style>
        .notify 
        {
        float: right;
        background-color: #F0EEED;
        padding: 3px 8px;
        border-radius: 5px;
        color: black;
        font-size: 22px;

        }


        .notify:hover 
        {
            color: white;
            background-color: maroon;
            border-radius: 5px;
            padding: 3px 8px;

        }

        .dropdown-menu
        {
            background-color: white;
            z-index: 1;
        }

        .dropdown-item
        {
            color:black;
            width:auto;height:50px;
            z-index: 1;
        }

        .dropdown-empty
        {
            color:black;
            text-align: center;
            width:300px;height:28px;
            font-size: 16px;

        }

        .dropdown-item:hover 
        {
            color: black;
            background-color:#F0EEED;
            font-weight: 550;
        }

        .dropdown-right
        {
            text-align: right;
        }

        </style>
</head>

<body>
<div class = "container-fluid" style = "margin-left: -15px; overflow-x:scroll;-webkit-overflow-scrolling:touch"> <!--CSS IS FIX IN BOOTSTRAP-->

    <!--SIDE BAR-->
    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <!--Background Side Bar-->
        
            <br><br>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>

            <h6 style="color: white;font-size:20px;"><b> Adviser </b></h6> <br><p style="text-align:center;font-size:12px;color:white;"><?php echo "$course" . " $year_section"; ?></p><hr style="background-color:white;width:200px;">
            <a href="Adviser_Dashboard.php?classCode=<?php echo $code ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp; Dashboard</a>
            <a href="Adviser_DashHome.php?classCode=<?php echo $code ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments" style="font-size: 1.3em;"></i>&emsp;Discussion</a>
            <a href="Adviser_Calendar.php?classCode=<?php echo $code ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-clock-o" style="font-size: 1.3em;"></i>&emsp;Schedule</a> 
            
            <a href="Adviser_ClassList.php" style="margin-top:80%;">
            <i class = "fa fa-arrow-circle-left fa-1x" style="font-size: 1em;"></i> &nbsp; Back to Class List
            </a> 
            
        </center>
    </div>

    
    <!--MAIN CONTENT -->
    <div class = "main">
        <!--TOP NAV-->
        <div class = "topnavbar" style="z-index:5;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
        <a href="Adviser_Dashboard.php?classCode=<?php echo $code ?>" style="font-size:13.5px;" id="topnav1"><i class="fa fa-home" style="width:15px;"></i>&emsp;Home </a>
            <a href="Adviser_Classwork.php?classCode=<?php echo $code ?>" style="font-size:13.5px;" id="topnav2"><i class="fa fa-book" style="width:15px;"> </i>&emsp;Classwork </a>
            <a href="Adviser_People.php?classCode=<?php echo $code ?>&Home" style="font-size:13.5px;" id="topnav3"><i class="fa fa-user" style="width:15px;"></i>&emsp;Students </a>
            <a href="Adviser_PartnerList.php?classCode=<?php echo $code ?>&Home" style="font-size:13.5px;" id="topnav4"><i class="fa fa-university" style="width:15px;"></i>&emsp;Partner Company</a>
            
            <div class="topnavbar-right" id="topnav_right">
            <a target = "_self" href="../Logout.php?Logout=<?php echo $faculty ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->

        <br><br><br><br>
        <div class="notify">
        <!-- Notification -->
        <div class="dropdown">
                <a  data-bs-toggle="dropdown" aria-expanded="false"> <i  class = "fa fa-bell"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color:maroon; color:white;">
                    <?php echo $notification_count; ?>
                </span>
                </a>
                <ul class="dropdown-menu" >
                    <?php
                    $return = "";
                $notification = mysqli_query($conn, "SELECT * FROM notification WHERE added_to = '$name' AND class_code = '$code' AND notif_read = 'no' ORDER BY date_added DESC");
                if(mysqli_num_rows($notification) > 0)
                { 
                    while($row = mysqli_fetch_array($notification))
                    {
                        $notif_id = $row['notif_id'];
                        $notification1 = mysqli_query($conn, "SELECT * FROM notification WHERE notif_id = '$notif_id' AND description LIKE '%classwork%'");
                        if(mysqli_num_rows($notification1) > 0)
                        { 
                            while($notif_fetch = mysqli_fetch_array($notification1))
                            {
                                $id = $notif_fetch['notif_id'];
                                $notif_desc = $notif_fetch['description'];
                                $notif_link = $notif_fetch['link'];
                                $notif_by = $notif_fetch['added_by'];
                                $date_added = $notif_fetch['date_added'];
                                $notif_added = date("M j Y, h:i:s A", strtotime($date_added));
                                
                                $return .= "
                                <li ><a class='dropdown-item' href='$notif_link' data-id='$id'><i class = 'fa fa-pencil-square-o'></i> $notif_by $notif_desc<br><div class='dropdown-right' style='opacity:0.5;'> $notif_added</div></a></li>
                            ";

                            
                            ?>
                                <script>
                        $(document).ready(function() {
                            $("a").click(function() {
                            var id = $(this).attr("data-id");
                            $.ajax({
                                url: "Adviser_DashHome.php",
                                type: "POST",
                                data: { id: id },
                                success: function(data) {
                                // Do something with the response data
                                }
                            });
                            });
                        });
                        </script>
                                <?php

                            } 
                           
                        } 
                        $notification2 = mysqli_query($conn, "SELECT * FROM notification WHERE notif_id = '$notif_id' AND description LIKE  '%discussion%'");
                        if(mysqli_num_rows($notification2) > 0)
                        {
                            while($notif_fetch = mysqli_fetch_array($notification2))
                            {
                                $id = $notif_fetch['notif_id'];
                                $notif_desc = $notif_fetch['description'];
                                $notif_link = $notif_fetch['link'];
                                $notif_by = $notif_fetch['added_by'];
                                $date_added = $notif_fetch['date_added'];
                                $notif_added = date("M j Y, h:i:s A", strtotime($date_added));
                                
                                $return .= "
                                    <li ><a class='dropdown-item' href='$notif_link' data-id='$id'><i class = '	fa fa-comments-o'></i> $notif_by $notif_desc<br><div class='dropdown-right' style='opacity:0.5;'> $notif_added</div></a></li>
                                ";
        
                            ?>
                                 <script>
                        $(document).ready(function() {
                            $("a").click(function() {
                            var id = $(this).attr("data-id");
                            $.ajax({
                                url: "Adviser_DashHome.php",
                                type: "POST",
                                data: { id: id },
                                success: function(data) {
                                // Do something with the response data
                                }
                            });
                            });
                        });
                        </script>
                                <?php
                            
                            } 
                            
                        }
                            
                        
                    }
                    echo $return;
                    
                }
                else
                {?>
                    <li ><div class='dropdown-empty'> No notification to show!</div></a></li>
                    <?php
                
                }
                ?>
                
            </ul>
                
                
         </div>
        </div>
        <div>
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Dashboard</h1>
            <hr style="background-color:maroon;border-width:2px;">
        </div>

        <br>
        <style>
            .row {
                margin: 0 -5px;
            }
        </style>
        
        <div class="card-body stylebox" style="color:white; border-width:2px;"> <br>
        
        
        
        <center>
            <h4 style="font-size:28px; color:maroon;">Classwork</h4> <br> 
        </center>
        <div class="row content">
            <div class="col-sm-12">
                <div class="row" >

                
                <div class="col-sm-4" id="classwork1">
                    <a style="text-decoration:none; color:white;"href="Adviser_People.php?classCode=<?php echo $code ?>&Home"> 
                    <div class="style" style="background-color: #0049B4;">
                        <h5 class = "text" style="text-align:center;color:white;"><i class = "fa fa-user-o"></i> &nbsp; Students</h5>           
                        <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $stud_count_row; ?></h2>
                    </div>
                    </a>
                </div>
                
                
                <div class="col-sm-4" id="classwork2">
                    <a style="text-decoration:none; color:white;" href="Adviser_DashClass.php?graded&classCode=<?php echo $code; ?>"> 
                    <div class="style"  style="background-color: #329C08;">
                        <h5 class = "text" style="text-align:center;color:white; "><i class = "fa fa-check-square-o fa-1x"></i> &nbsp; Graded Task</h5>           
                        <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $graded_count_row; ?></h2>
                    </div>
                    </a>
                </div>


                
                <div class="col-sm-4" id="classwork3">
                    <a style="text-decoration:none; color:white;" href="Adviser_DashClass.php?ungraded&classCode=<?php echo $code; ?>"> 
                    <div class="style" style="background-color: #CC0000;">
                        <h5 class = "text" style="text-align:center; color:white;"><i class = "fa fa-cogs"></i> &nbsp; Ungraded  Task</h5>           
                        <h2 class="font-weight-bold" style="text-align:center; color:white;"> <?php echo $ungraded_count_row; ?> </h2>
                    </div>
                    </a>
                </div>
        </div>
        </div>
        </div>
        <br><br>
        <hr style="background-color:maroon;border-width:2px;"> <br>
        <center>
                <h4 style="font-size:28px; color:maroon;">On the Job Training Process</h4> <br>
                </center>
        <div class="row content">
        <div class="col-sm-12">
        <div class="row">

                
                <div class="col-sm-4" id="classwork4">
                <a style="text-decoration:none; color:white;"  href="Adviser_DashClass.php?inprocess&classCode=<?php echo $code; ?>"> 
                    <div class="style" style="background-color: #609EA2;">
                        <h5 class = "text" style="text-align:center;color:white;"><i class = "fa fa-clone"></i> &nbsp; In Process (Documents)</h5>           
                        <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $inprocess_count_row_add;  ?> </h2>
                    </div>
                    </a>
                </div>

                
                <div class="col-sm-4" id="classwork5">
                <a style="text-decoration:none; color:black;"  href="Adviser_DashClass.php?ongoing&classCode=<?php echo $code; ?>"> 
                    <div class="style" style="background-color: #EA8000;">
                        <h5 class = "text" style="text-align:center;color:white;"><i class = "fa fa-envelope"></i> &nbsp; On going (OJT)</h5>           
                        <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $ongoing_count_row;  ?> </h2>
                    </div>
                    </a>
                </div>
                <div class="col-sm-4" id="classwork6">
                    <a style="text-decoration:none; color:white;" href="Adviser_DashClass.php?completed&classCode=<?php echo $code; ?>"> 
                    <div class="style" style="background-color: #329C08;">
                        <h5 class = "text" style="text-align:center;color:white;"><i class = "fa fa-check-circle"></i> &nbsp; Completed</h5>           
                        <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $completed_count_row; ?> </h2>
                    </div>
                    </a>
                </div>
            
            </div>

        </div>
        <script>
            
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize) {
            if (tabletSize.matches) 
            {
               
                document.getElementById('classwork1').classList.remove('col-md-4');
                document.getElementById('classwork1').classList.add('col-md-12');
                document.getElementById('classwork2').classList.remove('col-md-4');
                document.getElementById('classwork2').classList.add('col-md-12');
                document.getElementById('classwork3').classList.remove('col-md-4');
                document.getElementById('classwork3').classList.add('col-md-12');
                document.getElementById('classwork4').classList.remove('col-md-4');
                document.getElementById('classwork4').classList.add('col-md-12');
                document.getElementById('classwork5').classList.remove('col-md-4');
                document.getElementById('classwork5').classList.add('col-md-12');
                document.getElementById('classwork6').classList.remove('col-md-4');
                document.getElementById('classwork6').classList.add('col-md-12');
                document.getElementById('topnav_right').style.float = "left";
                document.getElementById('topnav_right').style.fontSize = "12px";
                document.getElementById('topnav1').style.fontSize = "12px";
                document.getElementById('topnav2').style.fontSize = "12px";
                document.getElementById('topnav3').style.fontSize = "12px";
                document.getElementById('topnav4').style.fontSize = "12px";
                document.getElementById('topnav1').style.padding = "14px 35px";
                document.getElementById('topnav2').style.padding = "14px 35px";
                document.getElementById('topnav3').style.padding = "14px 35px";
                document.getElementById('topnav4').style.padding = "14px 35px";
                
            } 
            else
            {
                document.getElementById('classwork1').classList.remove('col-md-12');
                document.getElementById('classwork1').classList.add('col-md-4');
                document.getElementById('classwork2').classList.remove('col-md-12');
                document.getElementById('classwork2').classList.add('col-md-4');
                document.getElementById('classwork3').classList.remove('col-md-12');
                document.getElementById('classwork3').classList.add('col-md-4');
                document.getElementById('classwork4').classList.remove('col-md-12');
                document.getElementById('classwork4').classList.add('col-md-4');
                document.getElementById('classwork5').classList.remove('col-md-12');
                document.getElementById('classwork5').classList.add('col-md-4');
                document.getElementById('classwork6').classList.remove('col-md-12');
                document.getElementById('classwork6').classList.add('col-md-4');
                document.getElementById('topnav_right').style.fontSize = "13.5px";
                document.getElementById('topnav1').style.fontSize = "13.5px";
                document.getElementById('topnav2').style.fontSize = "13.5px";
                document.getElementById('topnav3').style.fontSize = "13.5px";
                document.getElementById('topnav4').style.fontSize = "13.5px";
                document.getElementById('topnav1').style.padding = "14px 50px";
                document.getElementById('topnav2').style.padding = "14px 50px";
                document.getElementById('topnav3').style.padding = "14px 50px";
                document.getElementById('topnav4').style.padding = "14px 50px";
            }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>


    </div> <!-- end of card-body-->


        <br><br>


    </div> <!--end of main-->
    
</div> <!--end of container-fluid-->
    
</body>
</html>

