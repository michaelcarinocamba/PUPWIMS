<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['coordinator']))
    {
        header("Location: Coordinator_Login.php?LoginFirst");
    }

    $coordnum = $_SESSION['coordinator'];

    //Query for Company Count
    $comp_count = mysqli_query($conn, "SELECT * FROM company_list");
    $comp_count_row = mysqli_num_rows($comp_count);

    //Query for Active Companies Count
    $act_count = mysqli_query($conn, "SELECT * FROM company_list WHERE company_status = 'Active'");
    $act_count_row = mysqli_num_rows($act_count);

    //Query for Inactive Companies Count
    $inact_count = mysqli_query($conn, "SELECT * FROM company_list WHERE company_status = 'Inactive'");
    $inact_count_row = mysqli_num_rows($inact_count);

    //Query for Defunct Companies Count
    $def_count = mysqli_query($conn, "SELECT * FROM company_list WHERE company_status = 'Expiring'");
    $def_count_row = mysqli_num_rows($def_count);

    //Query for Students
    $stud_reg= mysqli_query($conn, "SELECT * FROM student_info");
    if(mysqli_num_rows($stud_reg) > 0)
    {
        while($row = mysqli_fetch_array($stud_reg))
        {
            $student = $row['studentnum'];
        }
    }
    $stud_reg_row = mysqli_num_rows($stud_reg);

    //Query for Advisers
    $adviser_reg = mysqli_query($conn, "SELECT *FROM adviser_info");
    $adviser_reg_row = mysqli_num_rows($adviser_reg);

    // $get_class = mysqli_query($conn, "SELECT * FROM create_class WHERE student_list LIKE '%$student%'");
    // if(mysqli_num_rows($get_class) > 0)
    // {
    //     while($class = mysqli_fetch_array($get_class))
    //     {
    //         $class_code = $class['class_code'];
    //     }
    // }

    $stud_unreg = 0;

    $student_unreg = mysqli_query($conn,"SELECT t1.* FROM student_info t1 WHERE NOT EXISTS(SELECT * FROM student_record t2 WHERE t2.studentnum = t1.studentnum)");
    if(mysqli_num_rows($student_unreg) > 0)
    {
        while(mysqli_fetch_array($student_unreg))
        {
            $stud_unreg++;
        }
    }

    $stud_reg = 0;

    $student_reg = mysqli_query($conn,"SELECT t1.* FROM student_info t1 WHERE EXISTS(SELECT * FROM student_record t2 WHERE t2.studentnum = t1.studentnum)");
    if(mysqli_num_rows($student_reg) > 0)
    {
        while(mysqli_fetch_array($student_reg))
        {
            $stud_reg++;
        }
    }




    if(isset($_REQUEST['id']))
    {
        $notif_id = $_REQUEST['id'];

        $update_notif = mysqli_query($conn, "UPDATE notification SET notif_read = 'yes' WHERE notif_id = '$notif_id'");
    }

    $name = $_SESSION['name'];
    $notification = mysqli_query($conn, "SELECT * FROM notification WHERE added_to = '$name' AND notif_read = 'no' ORDER BY date_added DESC");

    $notification_count = mysqli_num_rows($notification);

?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Coordinator Dashboard</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <script>
        function detectMobile() {
        var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

        if (isMobile) {
            // The user is on a mobile device
            $('#mobileModal').modal('show');
        } else {
            // The user is on a desktop device
            $('#mobileModal').modal('hide');
        }
        }

        window.onload = detectMobile;
    </script>
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
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

    <!--SIDE BAR-->
    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <!--Background Side Bar-->

            <br><br>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>

            <h6 style="color: white;"> Coordinator </h6>
            <br><br><hr style="background-color:white;width:200px;">
            <a href="Coordinator_Dashboard.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp;Dashboard</a>
            <a href="Coordinator_DashHome.php?Home" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-building" style="font-size: 1.3em;"></i>&emsp;Partner Company List </a>
            <a href="Coordinator_Record_Adviser.php?coordnum=<?php echo $coordnum ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-users" style="font-size: 1.3em;"></i>&emsp;Record </a>
            <a href="Coordinator_Classwork.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments-o" style="font-size: 1.3em;"></i>&emsp;Announcement </a>
            <a href="Coordinator_Document.php?Home" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-file-text" style="font-size: 1.3em;"></i>&emsp;Document </a>
            <a href="Coordinator_Graph.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp;Company Evaluation</a>
            
        </center>
    </div>

    
    <!--MAIN CONTENT -->
    <div class = "main">
        <!--TOP NAV-->
        <div class = "topnavbar">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
            <div class="topnavbar-right" id="topnav_right">
            <a target = "_self" href="../Logout.php?Logout=<?php echo $coordnum ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->

        <br><br><br><br>

        <?php
        if(isset($_REQUEST['LoginSuccess']))
        { ?>
            <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            
            Toast.fire({
                icon: 'success',
                title: 'Signed in successfully'
            })
            </script> <?php
            } ?>
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
                    if(mysqli_num_rows($notification) > 0){ 
                        while($notif_fetch = mysqli_fetch_array($notification))
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
                                    url: "Coordinator_Dashboard.php",
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

        
        <style>
            .row {
                margin: 0 -5px;
            }
            
            .card-body stylebox{
                background-color: white;
            }
        
        </style>
    <div class='modal fade' id='mobileModal' tabindex='-1' role='dialog' aria-labelledby='mobileModalLabel' aria-hidden='true'>
        <div class='modal-dialog  modal-dialog' role='document'>
            <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id = 'mobileModalLabel'><b>MOBILE USER</b></h5>
            </div>
            
            <div class='modal-body'>
            
                <h5>  To have better experience, please read the instruction below: </h5><br>
                <h6> [For IOS]<br>
                        &emsp;1. Go to your browser settings [<i class="fa fa-ellipsis-h"></i>].<br>
                        &emsp;2. Adjust text to "50%".<br>
                        &emsp;3. Click "Request Desktop Website".<br><br>
                     [For Android]<br>
                        &emsp;1. Go to your browser settings [ <i class="fa fa-cog"></i> ]. <br>
                        &emsp;2. Turn on/Activate "Desktop site". <br>
                        &emsp;</h6>
            </div>
            <div class='modal-footer'>
                
            
            </div>
            
            </div>
        </div>
        </div>

        <br>
        


        <div class="card-body stylebox">
        <center>
            <h4 style="font-size:28px; color:maroon;">Company List</h4> <br> 
        </center>

        <div class="row content" style= "white-space: nowrap;">
        <div class="col-sm-12">
        <div class="row" >
            <div class="col-sm-3" id="classwork1">
            <a style="text-decoration:none; color:white" href="Coordinator_DashHome.php?Home"> 
            <div class="style" style="background-color: #0049B4;">
                    <h6 class = "text" style="text-align:center;color:white;"><i class="fa fa-building"></i> &nbsp; Number of Company</h6>           
                    <h2 class="font-weight-bold" style="text-align:center;"> <?php echo $comp_count_row; ?></h2>
                </div>
                </a>
            </div>

            <div class="col-sm-3" id="classwork2">
            <a style="text-decoration:none; color:white" href="Coordinator_DashCompany.php?active&Home&coordnum=<?php echo $coordnum; ?>"> 
            <div class="style" style="background-color: #609EA2;">
                    <h6 class = "text" style="text-align:center; color:white;"><i class="fa fa-building"></i> &nbsp; Active Companies</h6>           
                    <h2 class="font-weight-bold" style="text-align:center;"> <?php echo $act_count_row; ?></h2>
                </div>
                </a>
            </div>

            <div class="col-sm-3" id="classwork3">
            <a style="text-decoration:none; color:white" href="Coordinator_DashCompany.php?defunct&Home&coordnum=<?php echo $coordnum; ?>"> 
            <div class="style" style="background-color: #EA8000;">
                    <h6 class = "text" style="text-align:center; color:white;"><i class="fa fa-building"></i> &nbsp; Expiring Companies</h6>           
                    <h2 class="font-weight-bold" style="text-align:center;"> <?php echo $def_count_row; ?></h2>
                </div>
                </a>
            </div>

            
            <div class="col-sm-3" id="classwork4">
            <a style="text-decoration:none; color:white" href="Coordinator_DashCompany.php?inactive&Home&coordnum=<?php echo $coordnum; ?>"> 
            <div class="style" style="background-color: #329C08;">
                    <h6 class = "text" style="text-align:center; color:white;"><i class="fa fa-building"></i></i> &nbsp; Inactive Companies</h6>           
                    <h2 class="font-weight-bold" style="text-align:center;"> <?php echo $inact_count_row; ?></h2>
                </div>
                </a>
            </div>


        </div>
        </div>
        </div>
        <br><br>
        <hr style="background-color:maroon;border-width:2px;"> <br>
        <center>
            <h4 style="font-size:28px; color:maroon;">Record</h4> <br> 
        </center>
        <div class="row content" style= "white-space: nowrap;">
            <div class="col-sm-12">
                <div class="row">

                <div class="col-sm-3" id="classwork5">
                <a style="text-decoration:none; color:white" href="Coordinator_Record_Adviser.php?coordnum=<?php echo $coordnum?>"> 
                <div class="style" style="background-color: #CC0000;">
                        <h6 class = "text" style="text-align:center; color:white;"><i class = "fa fa-user-o"></i> &nbsp; Advisers Registered</h6>           
                        <h2 class="font-weight-bold" style="text-align:center;"> <?php echo $adviser_reg_row; ?></h2>
                 </div>
                 </a>
                </div>
                
                <div class="col-sm-3" id="classwork6">
                <a style="text-decoration:none; color:white" href="Coordinator_Record_Student.php?coordnum=<?php echo $coordnum?>"> 
                <div class="style" style="background-color: #019267;">
                        <h6 class = "text" style="text-align:center;color:white;"><i class = "fa fa-user-o"></i> &nbsp; Students Registered</h6>           
                        <h2 class="font-weight-bold" style="text-align:center;"> <?php echo $stud_reg_row; ?></h2>
                 </div>
                 </a>
                </div>


                <div class="col-sm-3" id="classwork7">
                <a style="text-decoration:none; color:white" href="Coordinator_DashCompany.php?enrolled&Home&coordnum=<?php echo $coordnum; ?>"> 
                <div class="style" style="background-color: #2F416D;">
                        <h6 class = "text" style="text-align:center;color:white;"><i class="fa fa-building"></i> &nbsp;  Student Enrolled</h6>           
                        <h2 class="font-weight-bold" style="text-align:center;"> <?php echo $stud_reg; ?></h2>
                 </div>
                 </a>
                </div>

                <div class="col-sm-3" id="classwork8">
                <a style="text-decoration:none; color:white" href="Coordinator_DashCompany.php?unenrolled&Home&coordnum=<?php echo $coordnum; ?>"> 
                <div class="style" style="background-color: #774181;">
                        <h6 class = "text" style="text-align:center;color:white;"><i class="fa fa-building"></i> &nbsp; Student Unenrolled</h6>           
                        <h2 class="font-weight-bold" style="text-align:center;"> <?php echo $stud_unreg; ?></h2>
                 </div>
                 </a>
                </div>
        </div>

       


        <script>
            
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize) {
            if (tabletSize.matches) 
            {
               
                document.getElementById('classwork1').classList.remove('col-md-3');
                document.getElementById('classwork1').classList.add('col-md-12');
                document.getElementById('classwork2').classList.remove('col-md-3');
                document.getElementById('classwork2').classList.add('col-md-12');
                document.getElementById('classwork3').classList.remove('col-md-3');
                document.getElementById('classwork3').classList.add('col-md-12');
                document.getElementById('classwork4').classList.remove('col-md-3');
                document.getElementById('classwork4').classList.add('col-md-12');
                document.getElementById('classwork5').classList.remove('col-md-3');
                document.getElementById('classwork5').classList.add('col-md-12');
                document.getElementById('classwork6').classList.remove('col-md-3');
                document.getElementById('classwork6').classList.add('col-md-12');
                document.getElementById('classwork7').classList.remove('col-md-3');
                document.getElementById('classwork7').classList.add('col-md-12');
                document.getElementById('classwork8').classList.remove('col-md-3');
                document.getElementById('classwork8').classList.add('col-md-12');
                document.getElementById('topnav_right').classList.remove('topnavbar-right');
                
            } 
            else
            {
                document.getElementById('classwork1').classList.remove('col-md-12');
                document.getElementById('classwork1').classList.add('col-md-3');
                document.getElementById('classwork2').classList.remove('col-md-12');
                document.getElementById('classwork2').classList.add('col-md-3');
                document.getElementById('classwork3').classList.remove('col-md-12');
                document.getElementById('classwork3').classList.add('col-md-3');
                document.getElementById('classwork4').classList.remove('col-md-12');
                document.getElementById('classwork4').classList.add('col-md-3');
                document.getElementById('classwork5').classList.remove('col-md-12');
                document.getElementById('classwork5').classList.add('col-md-3');
                document.getElementById('classwork6').classList.remove('col-md-12');
                document.getElementById('classwork6').classList.add('col-md-3');
                document.getElementById('classwork7').classList.remove('col-md-12');
                document.getElementById('classwork7').classList.add('col-md-3');
                document.getElementById('classwork8').classList.remove('col-md-12');
                document.getElementById('classwork8').classList.add('col-md-3');
                document.getElementById('topnav_right').classList.add('topnavbar-right');
            }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>

         
         <div class="modal" id="mobileModal" tabindex="-1" aria-hidden="true">
         <div class='modal-dialog modal-dialog-bottom modal-dialog-centered modal-dialog' role='document'>
                    <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="exampleModalLabel">Mobile User</h2>
                        
                    </div>
                    <div class="modal-body">
                        <h7>
                            <p> To have better experience, please read the instruction below:</p>

                            <p>1. Go to your chrome settings and Turn on/Activate "Desktop site".</p>
                            
                        </h7>

                       

        
                    </div>
                    
                    </div>
                </div>
            </div>

        

    </div> <!--end of main-->
    
</div> <!--end of container-fluid-->
    
</body>
</html>
