<?php
    session_start();
    include "../db_conn.php";



    $code = $_REQUEST['classCode'];

    $name = $_SESSION['name'];
    $studentnum = $_SESSION['studnum'];    

    $query = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row = mysqli_fetch_array($query);
    $HRS_Rendered = $row['HRS_Rendered'];

    $adviser = mysqli_query($conn, "SELECT adviser_info.name FROM adviser_info JOIN create_class ON adviser_info.facultynum = create_class.facultynum WHERE create_class.class_code = '$code'");  
    if(mysqli_num_rows($adviser) > 0)
    {
        $get = mysqli_fetch_assoc($adviser);
        $faculty_name = $get['name'];
    }
    else
    {
        $faculty_name = "";
    }


    $stud_details_query = mysqli_query($conn, "SELECT * FROM student_record");
    $stud_row = mysqli_fetch_array($stud_details_query);
    if(!empty($stud_row)){
    $studname = $stud_row['name'];
    }

    $adv_details_query = mysqli_query($conn, "SELECT * FROM adviser_info");
    $adv_row = mysqli_fetch_array($adv_details_query);
    if(!empty($adv_row)){
    $facultyname = $adv_row['name'];
    $user_to = $adv_row['facultynum'];
    }

    $get_assignment = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE class_code = '$code'");
    $unfinishedinc = 0;
    $stud_ass_row2 = 0;
    $stud_ass_row3 = 0;
    $missingincrement = 0;
    if(mysqli_num_rows($get_assignment) > 0)
    {
        $stud_ass_finished = mysqli_query($conn, "SELECT * FROM student_assignment WHERE studentnum LIKE '$studentnum' AND class_code = '$code'");
        while(mysqli_fetch_array($stud_ass_finished))
        {
            if(mysqli_num_rows($stud_ass_finished) > 0)
            {
                $stud_ass_row2++;
            }
            else
            {
                $stud_ass_row2 = 0;
            }
        }
        

        $stud_ass_returned = mysqli_query($conn, "SELECT * FROM student_assignment WHERE studentnum = '$studentnum' AND grade = '1' AND status = 'Need to resubmit' AND class_code = '$code'");
        $stud_ass_row3 = mysqli_num_rows($stud_ass_returned);

            
    }
    else
    {
        $stud_ass_row2 = 0;
        $unfinishedinc = 0;
        $stud_ass_row2 = 0;
        $missingincrement = 0;
        $stud_ass_row3 = 0;
    }
    date_default_timezone_set('Asia/Singapore');
    $datenow = date("Y-m-d H:i:s");
    $nodate = "0000-00-00 00:00:00";
    $unfinished = 0;
    
    $get_assignment_unf1 = mysqli_query($conn, "SELECT t1.* FROM adviser_assignment t1 WHERE NOT EXISTS (SELECT * FROM student_assignment t2 WHERE t2.assignment_id = t1.assignment_id AND t2.studentnum = '$studentnum' )AND t1.class_code = '$code'  AND t1.due_date = '$nodate' ");
    if(mysqli_num_rows($get_assignment_unf1)>0) 
    {
        while(mysqli_fetch_array($get_assignment_unf1))
        {
            $unfinished++;
        }
    }
    $get_assignment_unf2 = mysqli_query($conn, "SELECT t1.* FROM adviser_assignment t1 WHERE NOT EXISTS (SELECT * FROM student_assignment t2 WHERE t2.assignment_id = t1.assignment_id AND t2.studentnum = '$studentnum' )  AND t1.class_code = '$code' AND t1.due_date > '$datenow'");
    if(mysqli_num_rows($get_assignment_unf2)>0) 
    {
        while(mysqli_fetch_array($get_assignment_unf2))
        {
            $unfinished++;
        }
    }

    $get_assignment2 = mysqli_query($conn, "SELECT t1.* FROM adviser_assignment t1 WHERE NOT EXISTS (SELECT * FROM student_assignment t2 WHERE t2.assignment_id = t1.assignment_id AND t2.studentnum = '$studentnum' ) AND t1.class_code = '$code' AND t1.due_date < '$datenow' AND t1.due_date != '$nodate'");
    $missingincrement = mysqli_num_rows($get_assignment2);


    // Query for Hours_rendered
    $get_hrs = 0;
    $sql_hrs = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND class_code = '$code'");
    if(mysqli_num_rows($sql_hrs) === 1)
    {
         $row = mysqli_fetch_array($sql_hrs);
        $get_hrs = $row['HRS_rendered'];
    }
   

    if(isset($_POST["insert"])){

        if(isset($_POST['check']))
        {
             $checkbox_value = "yes";

             $query = mysqli_query($conn, "UPDATE student_record SET confirm = '$checkbox_value' WHERE studentnum = '$studentnum' AND class_code = '$code'");
        }
        else if(!isset($_POST['check']))
        {
            $checkbox_value = "no";

            $query = mysqli_query($conn, "UPDATE student_record SET confirm = '$checkbox_value' WHERE studentnum = '$studentnum' AND class_code = '$code'");
        }



    }
    
    $checkbox_value = "";
    $checked = "";

    $check_confirm = mysqli_query($conn, "SELECT confirm FROM student_record WHERE studentnum = '$studentnum' AND class_code = '$code'");
    $get_confirm = mysqli_fetch_array($check_confirm);
    if(!empty($get_confirm))
    {
        $checkbox_value = $get_confirm['confirm'];
        if($checkbox_value === "yes")
        {
            $checked = "checked";
        }
        else
        {
            $checked = "";
        }
    }
    

    if(isset($_REQUEST['id']))
    {
        $notif_id = $_REQUEST['id'];

        $update_notif = mysqli_query($conn, "UPDATE notification SET notif_read = 'yes' WHERE notif_id = '$notif_id'");
    }

    $notification = mysqli_query($conn, "SELECT * FROM notification WHERE added_to = '$name' AND class_code = '$code' AND notif_read LIKE 'no' ORDER BY date_added DESC");

    $notification_count = mysqli_num_rows($notification);
    
    if (isset($_POST['checking_editbtn']))
    { 
        $s_num = $_POST['student_num'];
        $result_array = [];

        
        $query = "SELECT * FROM student_info WHERE studentnum = '$s_num'";
        $query_run = mysqli_query($conn, $query);


        if(mysqli_num_rows($query_run) > 0)
        {
            foreach($query_run as $row)
            {
                array_push($result_array, $row);
                header('Content-type: application/json');
                echo json_encode($result_array);
            }
        }   

        else
        {
            $data = null;
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }




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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-item
        {
            color:black;
            width:auto;height:50px;
            z-index: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
    
            <br><br>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>

            <h6 style="color: white;font-size:20px;"><b> Student </b></h6> <br><br><hr style="background-color:white;width:200px;">
            

            <a href="Student_Dashboard.php?classCode=<?php echo $code; ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp; Dashboard </a>
            <a href="Student_DashHome.php?classCode=<?php echo $code; ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments" style="font-size: 1.3em;"></i>&emsp;Discussion </a>
            <a href="Student_Calendar.php?classCode=<?php echo $code; ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-clock-o" style="font-size: 1.3em;"></i>&emsp;Schedule </a>
            
            
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
       
        <a href="Student_Dashboard.php?classCode=<?php echo $code; ?>"  style="font-size:13.5px;" id="topnav1"><i class="fa fa-home" style="width:15px;"></i> &emsp;Home </a>
        <a href="Student_DashClasswork.php?classCode=<?php echo $code; ?>"  style="font-size:13.5px;" id="topnav2"><i class="fa fa-book" style="width:15px;"> </i> &emsp;Classwork </a>
        <a href="Student_DashDocument.php?classCode=<?php echo $code; ?>"  style="font-size:13.5px;" id="topnav3"><i class="fa fa-file-text" style="width:15px;"></i>  &emsp;Documents </a>
            
            <?php
            $stud_query = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND status = 'Completed' AND HRS_rendered = '$HRS_Rendered' AND class_code = '$code'");

            if(mysqli_num_rows($stud_query)>0)
            {
                while($fetch = mysqli_fetch_array($stud_query))
                {
                    $company = $fetch['company']; 
                    
                    $query = mysqli_query($conn, "SELECT * FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company' AND class_code = '$code'");
                    if(mysqli_num_rows($query) === 0)
                    { 
                       
                            ?>
                            <a href="Student_Evaluation.php?classCode=<?php echo $code ?>" style="font-size:13.5px;" id="topnav4"><i class="fa fa-line-chart" style="font-size: 15px;"></i>  &emsp;Evaluation </a>
                            <?php
                        
                         } 
                }
            }
            else
            {
                $company = "";
            }

            
               
                
            
?>
    
            <div class="topnavbar-right" id="topnav_right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                 <center>Logout  <i class = "fa fa-sign-out fa-1x"></i></center>
                </a>
            </div>
        </div> <!--end of topnavbar-->
        <br><br><br><br>
        <?php
        if(isset($_REQUEST['loginsuccess']))
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
            </script>
      <?php
        }
?>
                    

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
                                $max_text = 35;
                                $displayed_desc = substr($notif_desc, 0, $max_text);
                                if (strlen($notif_desc) > $max_text) {
                                    $displayed_desc .= "...";
                                }

                                
                                
                                $return .= "
                                <li ><a class='dropdown-item' href='$notif_link' data-id='$id'><i class = 'fa fa-pencil-square-o'></i> $notif_by $displayed_desc <br><div class='dropdown-right' style='opacity:0.5;'> $notif_added</div></a></li>
                            ";

                            
                            ?>
                                <script>
                                $(document).ready(function() {
                                    $("a").click(function() {
                                    var id = $(this).attr("data-id");
                                    $.ajax({
                                        url: "Student_Dashboard.php",
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
                                $max_text = 35;
                                $displayed_desc = substr($notif_desc, 0, $max_text);
                                if (strlen($notif_desc) > $max_text) {
                                    $displayed_desc .= "...";
                                }
                                $return .= "
                                    <li ><a class='dropdown-item' href='$notif_link' data-id='$id'><i class = '	fa fa-comments-o'></i> $notif_by $displayed_desc<br><div class='dropdown-right' style='opacity:0.5;'> $notif_added</div></a></li>
                                ";
        
                            ?>
                                <script>
                                $(document).ready(function() {
                                    $("a").click(function() {
                                    var id = $(this).attr("data-id");
                                    $.ajax({
                                        url: "Student_Dashboard.php",
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
                        $notification3 = mysqli_query($conn, "SELECT * FROM notification WHERE notif_id = '$notif_id' AND description LIKE  '%Mentoring%'");
                        if(mysqli_num_rows($notification3) > 0)
                        {
                            while($notif_fetch = mysqli_fetch_array($notification3))
                            {
                                $id = $notif_fetch['notif_id'];
                                $notif_desc = $notif_fetch['description'];
                                $notif_link = $notif_fetch['link'];
                                $notif_by = $notif_fetch['added_by'];
                                $date_added = $notif_fetch['date_added'];
                                $notif_added = date("M j Y, h:i:s A", strtotime($date_added));
                                $max_text = 35;
                                $displayed_desc = substr($notif_desc, 0, $max_text);
                                if (strlen($notif_desc) > $max_text) {
                                    $displayed_desc .= "...";
                                }
                                $return .= "
                                    <li ><a class='dropdown-item' href='$notif_link' data-id='$id'><i class = 'fa fa-clock-o'></i> $notif_by $displayed_desc<br><div class='dropdown-right' style='opacity:0.5;'> $notif_added</div></a></li>
                                ";
        
                            ?>
                                <script>
                                $(document).ready(function() {
                                    $("a").click(function() {
                                    var id = $(this).attr("data-id");
                                    $.ajax({
                                        url: "Student_Dashboard.php",
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

         <!-- Button trigger modal -->
         <button type="button" class="btn filebtn" style="float:right;" data-bs-toggle="modal" data-bs-target="#instructionsModal">
                <i class = "fa fa-file-text fa-1x"></i> &nbsp; General Instruction
         </button>
         <br>

               <!-- Modal -->
               <div class="modal fade" id="instructionsModal" tabindex="-1" aria-labelledby="instructionsModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="instructionsModalLabel">General Instruction</h2>
                        
                    
                    </div>
                    <div class="modal-body">
                        <h6>
                        <p>Please read the instructions below:</p>

                        <p> 1. Revise the forms before submitting to your College and the Legal Office.</p>
                        <p> 2. Use only the appropriate forms for your OJT Program.</p>
                        <p> 3. For students who will be using the MOA template of the University, you are required to submit the following documents:</p>
                        <p class = indent1> a. MOA template of the University </p>
                        <ul class="indent">
                                   <li>Determine the appropriate signatory of your MOA (Dr. Manuel M. Muhi or Prof. Pascualito B. Gatan). </li>
                                   <li>Inquire your College if there is a need for a copyright provision, if yes, print the MOA with copyright provision. </li>
                        </ul> 
                        <p class = indent1>b. Internship Agreement</p>
                        <p class = indent1>c. Internship Plan provided by the College</p>
                        <p>4. For students who will be using the MOA template of the Company, you are required to submit the following documents:</p>
                        <p class = indent1>a. MOA template of the Company (subject for review by the Legal Office)</p>
                        <p class = indent1>b. Internship Agreement</p>
                        <p class = indent1>c. Internship Plan provided by the College</p>
                        <p class = indent1>d. Consent Form</p>
                        <p>For Documents needed to be downloaded, Please see the Documents Tab.</p>
                        </h6>
                        <style type="text/css"> 
                            .indent { margin-left:80px; }
                            .indent1 { margin-left:40px; }
                        </style>
                    </div>
                    <form method='POST'>
                    <div class="modal-footer">
                        <label>
                            <input type="checkbox" name = "check" id="check" value="<?php echo $checkbox_value; ?>" <?php echo $checked; ?>> Do not show again
                        </label>
                        <button type="submit" name = "insert" class="btn btn-primary" data-bs-dismiss="modal">I understood</button>
                    </div>
                    <script type="text/javascript">
                            function autoshowMod(){
                                $('#instructionsModal').modal('show');
                            }

                            function disableautoMod(){
                                autoshowMod = null;
                            }

                        </script> 

                    <?php 
                        if (isset($_POST['insert'])) {
                            ?> <script type = "text/javascript">
                            disableautoMod();
                            </script>
                            <?php
                          }
                        if (isset($_SESSION['studnum'])) {
                            if($checkbox_value === "no")
                            {
                                $checked = "checked";
                                ?> <script type = "text/javascript">
                                autoshowMod();
                                </script>
                                <?php
                            }
                            else
                            {
                                $checked = "";
                                ?> <script type = "text/javascript">
                                disableautoMod();
                                </script>
                                <?php
                                
                            } 
                          } 
                            
                        ?>
                    </form>
                    
                    </div>
                </div>
                </div>  </h1>
        </div>

       

        <br><br>
        <style>
            .row {
                margin: 15px -5px;
            }
        </style>
        
        <div class="container-fluid" style = "margin-left: -15px"> 
                    <div class="card-body stylebox" style="background-color:white; border-width:2px;"> <br>
                   

                    
                    <div class="row">
                    <div class="col-sm-6" id="first_title">
                        <div class="style" style="background-color:#6b6b6b;">
                            <h5 style="color:white;"><i class = "fa fa-clock-o fa-1x" style="color:white;"></i> &nbsp; Number of Hours Rendered</h5>
                            <h2 class="font-weight-bold" style="color:white;"> <?php echo $get_hrs; ?> &nbsp;out of <?php echo $HRS_Rendered; ?> hours</h2>
                        </div>
                    </div>
                    <div class="col-sm-6" id="second_title">
                        <div class="style" style="background-color:#6b6b6b;">
                            <h5 style="color:white;"><i class = "fa fa-user-circle-o" style="color:white;"></i> &nbsp; Adviser</h5>
                            <h2 class="font-weight-bold" style="color:white;"> <?php echo $faculty_name; ?></h2>
                        </div>
                    </div>
                    </div>
                    <br>
                    <hr style="background-color:maroon;border-width:2px;">
                    <br>
                    
                     <center>
                        <h4 style="font-size:28px; color:maroon;">Classwork</h4>
                    </center>
                    
                    <div class="row content">
                        <div class="col-sm-12">
                            <div class="row">
                            
                            <!-- Display Finished Task -->
                            <div class="col-sm-3" id="classwork1" style="white-space: nowrap;">
                                <a style="text-decoration:none; color:black;" href="Student_DashClass.php?finished&classCode=<?php echo $code; ?>"> 
                                <div class="style" style="background-color: #329C08;">
                                    <h5 class = "text" style="text-align:center; color:white;"><i class = "fa fa-check-square-o fa-1x"></i> &nbsp; Finished Tasks</h5>           
                                    <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $stud_ass_row2; ?></h2>
                                </div>
                                </a>
                            </div>
        
        
                            <!-- Display Returned Task --> 
                            <div class="col-sm-3" id="classwork2" style="white-space: nowrap;">
                                <a style="text-decoration:none; color:black;" href="Student_DashClass.php?returned&classCode=<?php echo $code; ?>"> 
                                <div class="style" style="background-color: #0049B4;">
                                    <h5 class = "text" style="text-align:center;color:white;font-size:115%;"><i class = "fa fa-repeat fa-1x"></i> &nbsp; Need to Resubmit</h5>           
                                    <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $stud_ass_row3; ?> </h2>
                                </div>
                                </a>
                            </div>
        
                            <!-- Display Unfinished Task --> 
                            <div class="col-sm-3" id="classwork3" style="white-space: nowrap;">
                            <a style="text-decoration:none; color:black;" href="Student_DashClass.php?unfinished&classCode=<?php echo $code; ?>"> 
                                <div class="style" style="background-color: #EA8000;">
                                    <h5 class = "text" style="text-align:center;color:white;"><i class = "fa fa-meh-o fa-1x"></i> &nbsp; Unfinished Tasks</h5>           
                                    <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $unfinished; ?> </h2>
                                </div>
                                </a>
                            </div>
        
                            <!-- Display Missing Task --> 
                            <div class="col-sm-3" id="classwork4" style="white-space: nowrap;">
                                <a style="text-decoration:none; color:black;" href="Student_DashClass.php?missing&classCode=<?php echo $code; ?>"> 
                                <div class="style" style="background-color: #CC0000;">
                                    <h5 class = "text" style="text-align:center;color:white;"><i class = "fa fa-exclamation-triangle fa-1x"></i> &nbsp; Missing Tasks</h5>           
                                    <h2 class="font-weight-bold" style="text-align:center;color:white;"> <?php echo $missingincrement; ?> </h2>
                                </div>
                                </a>
                            </div>
                        
                        </div>

                    </div>
                
    
        
                </div> <!-- end of card-body-->

        <br>

        <script>
            
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize) {
            if (tabletSize.matches) 
            {
                document.getElementById('first_title').classList.remove('col-md-6');
                document.getElementById('first_title').classList.add('col-md-12');
                document.getElementById('second_title').classList.remove('col-md-6');
                document.getElementById('second_title').classList.add('col-md-12');
                document.getElementById('classwork1').classList.remove('col-md-3');
                document.getElementById('classwork1').classList.add('col-md-12');
                document.getElementById('classwork2').classList.remove('col-md-3');
                document.getElementById('classwork2').classList.add('col-md-12');
                document.getElementById('classwork3').classList.remove('col-md-3');
                document.getElementById('classwork3').classList.add('col-md-12');
                document.getElementById('classwork4').classList.remove('col-md-3');
                document.getElementById('classwork4').classList.add('col-md-12');
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
                document.getElementById('first_title').classList.remove('col-md-12');
                document.getElementById('first_title').classList.add('col-md-6');
                document.getElementById('second_title').classList.remove('col-md-12');
                document.getElementById('second_title').classList.add('col-md-6');
                document.getElementById('classwork1').classList.remove('col-md-12');
                document.getElementById('classwork1').classList.add('col-md-3');
                document.getElementById('classwork2').classList.remove('col-md-12');
                document.getElementById('classwork2').classList.add('col-md-3');
                document.getElementById('classwork3').classList.remove('col-md-12');
                document.getElementById('classwork3').classList.add('col-md-3');
                document.getElementById('classwork4').classList.remove('col-md-12');
                document.getElementById('classwork4').classList.add('col-md-3');
                document.getElementById('topnav_right').style.float = "right";
                document.getElementById('topnav_right').classList.add('topnavbar-right');
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
    


    </div> <!--end of main-->
    <script>
        $('.edit_profile').click(function(e) {
            e.preventDefault();
            
            $.ajax({
            type: "POST",
            url: "Student_Dashboard.php",
            data: {
                'checking_editbtn': true,
                'student_num': <?php echo $studentnum ?>,
            },
            success: function (response) {
                $.each(response,function (key, value){

                $('#student_num').val(value['studentnum']);
                $('#name').val(value['name']);
                $('#email').val(value['email']);
                $('#num').val(value['contact_number']);
                $('#month').val(value['month']);
                $('#day').val(value['day']);
                $('#year').val(value['year']);

                });
                
                $('#profile').modal('show');
                
            }
            });

            });
    </script>
</div> <!--end of container-fluid-->
    
</body>
</html>

