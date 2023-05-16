<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['studnum']))
{
    header("Location: Student_Login.php?LoginFirst");
}

$name = $_SESSION['name'];
$studentnum = $_SESSION['studnum'];
date_default_timezone_set('Asia/Singapore');

if(isset($_REQUEST['finished']))
{
    $code = $_REQUEST['classCode'];
    $studentnum = $_SESSION['studnum'];
    
    if(isset($_POST['saveagain']))
    {
        $get_id = $_POST['id1'];
        $assignment_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$get_id' AND class_code = '$code'");
        $assignment_row = mysqli_fetch_array($assignment_query);
        $assignment_due = $assignment_row['due_date'];
        $assignmentid = $assignment_row['assignment_id'];
        $assignment_title = $assignment_row['title'];
        $filetypename = $_POST['name1'];
        $prof_name = $assignment_row['name'];
        $filetypename = strip_tags($filetypename);
        $filetypename = mysqli_real_escape_string($conn, $filetypename);
        $check_empty = preg_replace('/\s+/', '', $filetypename);
        $descript = $_POST['desc1'];
        date_default_timezone_set('Asia/Singapore');
        $date_added = date("Y-m-d H:i:s");
        $file = $_FILES['file1'];
        $fileName = $_FILES['file1']['name'];
        $fileTmpName = $_FILES['file1']['tmp_name'];
        $fileSize = $_FILES['file1']['size'];
        $fileError = $_FILES['file1']['error'];
        $fileType = $_FILES['file1']['type'];

        if($assignment_due < $date_added)
        {
            $status = "Turned in late";
        }
        else
        {
            
            $status = "Turned in";
        }

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx', 'xls');

        if($check_empty != "" && $fileName == "")
        {
            $query = mysqli_query($conn, "UPDATE student_assignment SET file_name = '$filetypename', description = '$descript', date_added = '$date_added', files = '$fileName', files_destionation = '$fileDestination' WHERE assignment_id = '$get_id' AND class_code = '$code'");
            $notif_desc = "has resubmitted his/her classwork. [$filetypename]";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Adviser_Classwork.php?classCode=$code";

            $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$prof_name', '$code', '$date_time_now','no')");
            
            
            header("Location: Student_DashClasswork.php?classCode=$code&updatesuccess");
        }

        else if($fileName != "")
        {
            if(in_array($fileActualExt, $allowed))
            {
                if($fileError === 0)
                {
                    if($fileSize < 50000000)
                    {
                        $fileDestination = '../uploads/'.$fileName;
                        move_uploaded_file($fileTmpName,$fileDestination);

                        $query = mysqli_query($conn, "UPDATE student_assignment SET file_name = '$filetypename', description = '$descript', date_added = '$date_added', files = '$fileName', files_destination = '$fileDestination' WHERE assignment_id = '$get_id' AND class_code = '$code'");
                        $notif_desc = "has resubmitted his/her classwork. [$filetypename]";
                        $date_time_now = date("Y-m-d H:i:s");
                        $notif_link = "Adviser_Classwork.php?classCode=$code";

                        $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$prof_name', '$code', '$date_time_now','no')");
                        
                        
                       
                        header("Location: Student_DashClasswork.php?classCode=$code&updatesuccess");
                    }
                
                    else{
                        header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
                    }
                }
            
                else{
                    header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
                }
            }

            else{
                header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
            }
        }
    }


    ?>
    <!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - Classwork</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel = "stylesheet" href = "../css/student_dashclass.css">
    
</head>

<body id="body">
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->
    
     <!--SIDE BAR-->
     <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <!--Background Side Bar-->
    
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
        <div class = "topnavbar" style ="z-index:1;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">


            <div class="topnavbar-right" id="topnav_right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->

            <br><br><br><br>
            <script>
            
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize) {
            if (tabletSize.matches) 
            {
                document.getElementById('topnav_right').style.float = "left";
                document.getElementById('topnav_right').style.fontSize = "12px";
                document.getElementById('body').style.fontSize = "12.5px";
                
            } 
            else
            {

                document.getElementById('topnav_right').classList.add('topnavbar-right');
                document.getElementById('topnav_right').style.fontSize = "13.5px";
                document.getElementById('body').style.fontSize = "";
            }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>
            
        <?php
        if(isset($_REQUEST['updatesuccess']))
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
                title: 'Classwork submission has been updated.'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['unsuccess']))
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
                icon: 'warning',
                title: 'There was an error uploading your file.'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['uploadsuccess']))
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
                title: 'Classwork submission has been uploaded.'
            })
            </script>
      <?php
        } ?>
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Finished Tasks<a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Student_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
            <hr style="background-color:maroon;border-width:2px;">
            &emsp;&emsp; 

    <?php

    $stud_ass_finished = mysqli_query($conn, "SELECT * FROM student_assignment WHERE class_code = '$code' AND studentnum = '$studentnum'");
    if(mysqli_num_rows($stud_ass_finished) > 0)
    {
        $str = "";

        while($row = mysqli_fetch_array($stud_ass_finished))
        {
            $id = $row['id'];
            $ass_id = $row['assignment_id'];
            $studentnum = $row['studentnum'];
            $file_name = $row['file_name'];
            $description = $row['description'];
            $class_code = $row['class_code'];
            $date_added = $row['date_added'];
            $date_added_display = date("M j Y, H:i:s A", strtotime($row['date_added']));
            $due_date = $row['due_date'];
            $due_date_display =  date("M j Y, H:i:s A", strtotime($row['due_date']));
            $files = $row['files'];
            $points = $row['points'];
            $files_destination = $row['files_destination'];
            $grade = $row['grade'];
            $status = $row['status'];

            $fileExt1 = explode('.', $files);
            $fileActualExt1 = end($fileExt1);
            $allowed1  = array('jpg','jpeg','png', 'JPG', 'JPEG' ,'PNG');
            $fileDiv1 = "";
            
            if (in_array($fileActualExt1, $allowed1)) 
            {
                $fileDiv1 = "<div id='postedFile'>
                <img src='$files_destination' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='width:auto;min-width:100%;max-width:250px; height:auto;min-height:100%;max-height:250px;'>
                </div>";
            }

            $adv_classwork = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$ass_id' AND class_code = '$code'");
            $classrow = mysqli_fetch_array($adv_classwork);
            if(!empty($classrow))
            {
                $work_id = $classrow['assignment_id'];
                $faculty = $classrow['facultynum'];
                $adv_name = $classrow['name'];
                $title = $classrow['title'];
                $instruction = $classrow['instruction'];
                $ass_added = $classrow['date_added'];
                $ass_added_display = date("M j Y, H:i:s A", strtotime($classrow['date_added']));
                $ass_duedate = $classrow['due_date'];
                $ass_duedate_display =  date("M j Y, H:i:s A", strtotime($classrow['due_date']));
                $start_date = $classrow['start_date'];
                $start_date_display =  date("M j Y, H:i:s A", strtotime($classrow['start_date']));
                $ass_file = $classrow['files'];
                $type = $classrow['type'];
                $possible_point = $classrow['points'];
                $ass_destination = $classrow['files_destination'];

                $fileExt = explode('.', $ass_file);
                $fileActualExt = end($fileExt);
                $allowed  = array('jpg','jpeg','png');
                $fileDiv = "";

                if (in_array($fileActualExt, $allowed)) 
                {
                    $fileDiv = "<div id='postedFile'>
                    <img src='$class_path' onclick='window.open(this.src)' title='Click Here To View Full Screen' height='auto' width='60%' border-radius='10px' >
                    </div>";
                }

                date_default_timezone_set('Asia/Singapore');
                $date_today = date("Y-m-d H:i:s");

                ?> <!--end of first php -->
                        
                <script>
                    function toggle<?php echo $work_id; ?>() {
                        var element = document.getElementById("toggleClass<?php echo $work_id; ?>");

                        if (element.style.display == "block")
                            element.style.display = "none";
                        else
                            element.style.display = "block";
                        
                    } //end of function   
                </script>

                <?php

                $str .= "<br>
                <div class='card-body stylebox' style='background-color:white;'>
                    <div onClick='javascript:toggle$work_id(); myFunction(  )'>
                        <div class='card-body stylebox' style='background-color: #329C08'>
                        
                        <div class='row'>
                                <div class='col-6'>
                                    <b style = 'font-weight: 600;color:white;'>Assignment Title: $title </b> 
                                    <br>
                                    </div>
                                <div class='col-6'>
                                    <b style = 'font-weight: 600;color:white;'>Type: $type </b>
                                </div>
                                
                        </div>


                        <div class='row'>
                        <div class='col-6' style='color:white;'>
                            ";
                            if($start_date === '0000-00-00 00:00:00')
                            {
                                $str .="
                            
                                Start Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                ";
                            }
                            else
                            {
                                $str .="
                            
                                Start Date: $start_date_display &emsp;
                                ";

                            }
                            $str .="
                        </div>
                        <div class='col-6' style='color:white;'>
                        ";
                        if($due_date === '0000-00-00 00:00:00')
                        {
                            $str .="
                        
                            Due Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; On going
                            ";
                        }
                        else
                        {
                            $str .="
                        
                            Due Date: $due_date_display &emsp; $status
                            ";

                        }
                        $str .="
                            
                            </div>
                    </div>

                                
                        </div>
                    </div>
                            
                    <div class='card-body display' id='toggleClass$work_id'>
                                                                    
                        ";
                        if($type === 'Graded') 
                        {
                            if(substr($classrow['files_destination'], -4) === ".jpg" || substr($classrow['files'], -4) === ".jpg" || substr($classrow['files_destination'], -5) === ".jpeg" || substr($classrow['files'], -5) === ".jpeg"  ||  substr($classrow['files_destination'], -4) === ".png" || substr($classrow['files'], -4) === ".png")
                            { $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    <div class = 'col-2'>Points: &emsp; /  $points  </div>
                
                                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                    <br><br>
                                    <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
                                
                                </div> <br><br>";
                                

                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                    <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                    
                                        <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>";
                                }
                                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                        <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                                else
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                        
                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                            
                            }
                            else if(substr($classrow['files_destination'], -4) === ".pdf" || substr($classrow['files'], -4) === ".pdf" || substr($classrow['files_destination'], -5) === ".docx" || substr($classrow['files'], -5) === ".docx"  ||  substr($classrow['files_destination'], -4) === ".doc" || substr($classrow['files'], -4) === ".doc"  ||  substr($classrow['files_destination'], -4) === ".ppt" || substr($classrow['files'], -4) === ".ppt"  ||  substr($classrow['files_destination'], -5) === ".pptx" || substr($classrow['files'], -5) === ".pptx"  ||  substr($classrow['files_destination'], -4) === ".xls" || substr($classrow['files'], -4) === ".xls")
                            {
                                $str .="<div class = 'row text'>
                                            <div class = 'col-4'> Instructor $name  </div>
                                            <div class = 'col-6'> $date_added_display  </div>
                                            <div class = 'col-2'>Points: &emsp; /  $points  </div>
                        
                                            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                            <br><br>
                                            <div class = 'col-12' style=' margin-left:1%;width:10%;'>
                                                <a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$classrow['files']}'>{$classrow['files']}</a> 
                                            </div>
                                        </div><br><br>";
                                
                                        if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                            <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                            
                                                <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>";
                                        }
                                        else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                                <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                                        else
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                                
                                            <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                            }
                            else
                            {
                                $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    <div class = 'col-2'>Points: &emsp; /  $points  </div>
                
                                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                    
                                </div><br><br>";
                        
                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                            <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                            
                                                <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>";
                                        }
                                        else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                                <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                                        else
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                                
                                            <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                            }
                        }
                        else
                        {
                            if(substr($classrow['files_destination'], -4) === ".jpg" || substr($classrow['files'], -4) === ".jpg" || substr($classrow['files_destination'], -5) === ".jpeg" || substr($classrow['files'], -5) === ".jpeg"  ||  substr($classrow['files_destination'], -4) === ".png" || substr($classrow['files'], -4) === ".png")
                            { $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    
                
                                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                    <br><br>
                                    <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
                                
                                </div> <br><br>";
                                

                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                    <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                    
                                        <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>";
                                }
                                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                        <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                                else
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                        
                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                            
                            }
                            else if(substr($classrow['files_destination'], -4) === ".pdf" || substr($classrow['files'], -4) === ".pdf" || substr($classrow['files_destination'], -5) === ".docx" || substr($classrow['files'], -5) === ".docx"  ||  substr($classrow['files_destination'], -4) === ".doc" || substr($classrow['files'], -4) === ".doc"  ||  substr($classrow['files_destination'], -4) === ".ppt" || substr($classrow['files'], -4) === ".ppt"  ||  substr($classrow['files_destination'], -5) === ".pptx" || substr($classrow['files'], -5) === ".pptx"  ||  substr($classrow['files_destination'], -4) === ".xls" || substr($classrow['files'], -4) === ".xls")
                            {
                                $str .="<div class = 'row text'>
                                            <div class = 'col-4'> Instructor $name  </div>
                                            <div class = 'col-6'> $date_added_display  </div>
                                            
                        
                                            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                            <br><br>
                                            <div class = 'col-12' style=' margin-left:1%;'>
                                                <a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> 
                                            </div>
                                        </div><br><br>";
                                
                                        if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                            <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                            
                                                <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>";
                                        }
                                        else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                                <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                                        else
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                                
                                            <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                            }
                            else
                            {
                                $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    
                
                                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                    
                                </div><br><br>";
                        
                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                    <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                    
                                        <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>";
                                }
                                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                        <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                                else
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                        
                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                            }

                        }
                        if($due_date > $date_today || $due_date === "0000-00-00 00:00:00")
                        {
                                $str .="
                            <hr class='dashed'>
                            <button class='filebtn hfilebtn' id='unsubmitBtn' type='button' style='float:right;' data-toggle='modal' data-target='#unsubmitModal$id'>Unsubmit</button>";
                            if(isset($_POST['unsubmit']))
                            {
                                ?>
                                    <script type='text/javascript'>
                                            $(document).ready(function(){
                                                document.getElementById("unsubmitBtn").style.display = "none";
                                            });
                                    </script>
                                <?php
                                
                        
                    
                    
                    $str .="
                        <button class='filebtn hfilebtn'";if(isset($_POST['unsubmit'])){ $str.="type='button' style='float:right;' data-toggle='modal' data-target='#submitagainModal$id'";}else{$str.="style='display:none;'";}$str.=">+ Add or Change</button>
                        ";}
                        }
                        else if($status === "Need to resubmit")
                        {
                            $str .="
                            <hr class='dashed'>
                            <button class='filebtn hfilebtn' id='unsubmitBtn' type='button' style='float:right;' data-toggle='modal' data-target='#unsubmitModal$id'>Unsubmit</button>";
                            if(isset($_POST['unsubmit']))
                            {
                                ?>
                                    <script type='text/javascript'>
                                            $(document).ready(function(){
                                                document.getElementById("unsubmitBtn").style.display = "none";
                                            });
                                    </script>
                                <?php
                                
                        
                    
                    
                    $str .="
                        <button class='filebtn hfilebtn'";if(isset($_POST['unsubmit'])){ $str.="type='button' style='float:right;' data-toggle='modal' data-target='#submitagainModal$id'";}else{$str.="style='display:none;'";}$str.=">+ Add or Change</button>
                        ";}
                        }
                        else if($due_date < $date_today)
                        {
                            $str .="<hr class='dashed'>
                            ";
                        }
                        $str .="
                        
                        <div class='modal fade' id='submitagainModal$id' tabindex='-1' aria-labelledby='submitagainModalLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content'>
                            

                                <div class='modal-header'>
                                    <h5 class='modal-title' id='submitagainModalLabel'>Submit an Assignment</h5>
                                </div>

                                <form method='POST' enctype='multipart/form-data'>
                                <div class='modal-body p-5'>
                                    

                                    <div class='md-file'>
                                        <label for='formFile' class='form-label'></label>
                                        <input class='form-control' type='file' name='file1' id='formFile'>
                                    </div>

                                    <br>

                                    <div class='md-title'>
                                        <label for='exampleFormControlInput' name='title' class='form-label'>File Name</label>
                                        <input type='text' name='name1' class='form-control' id='exampleFormControlInput' placeholder='$file_name'>
                                    </div>

                                    <br>

                                    <div class='md-textarea'>
                                        <label for='exampleFormControlTextarea1' class='form-label'>Description</label>
                                        <textarea name='desc1' class='form-control' id='exampleFormControlTextarea1' placeholder='$description' rows='3'></textarea>
                                    </div>

                                </div>

                                <div class='modal-footer'>
                                    <input type='hidden' name='id1' value='$id'>
                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                    <button type='submit' name='saveagain' class='btn btn-primary'>Resubmit</button>
                                </div></form>
                                

                            </div>
                        </div>
                    </div>
                    <div id='unsubmitModal$id' class='modal fade' tabindex='-1' role='dialog' aria-hidden='true'>
                    <div class='modal-dialog modal-lg'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h4 class='modal-title'>Unsubmit?</h4>
                            </div>
                            <div class='modal-body'>
                                Unsubmit to add or change attachments. Don't forget to resubmit once you're done.
                            </div>
                            <div class='modal-footer'>
                            <form method = 'POST'>
                                <input type='hidden' name='id' value='$id'>
                                <button class='btn btn-danger' type='submit' name='unsubmit'>Unsubmit</button> 
                                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                    </div>
                </div></form>
                
                </div>
                        
            </div>";
            }
            else
            {
                $str = "
                <br>
                <div class='card-deck-6'>
                    <div class='card style'>
                        <div class='card-body text-center'>
                        <br>
                        <img alt='Join Class' class = 'img-join' src='../images/class-img.png' style='width:25%;height:25%;'><br><br><br>
                        <h5> There's no record for Finished Task yet.</h5>
                        </div>
                    </div>
                </div>
                ";
                
            }
            
        }
        echo $str;

    }
    else
    {
        $str = "
        <br>
        <div class='card-deck-6'>
            <div class='card style'>
                <div class='card-body text-center'>
                <br>
                <img alt='Join Class' class = 'img-join' src='../images/class-img.png' style='width:25%;height:25%;'><br><br><br>
                <h5> There's no record for Finished Task yet.</h5>
                </div>
            </div>
        </div>
        ";
        echo $str;
    }
}//end of finished       


if(isset($_REQUEST['returned']))
{
    $code = $_REQUEST['classCode'];
    $studentnum = $_SESSION['studnum'];
    
    if(isset($_POST['saveagain']))
    {
        $get_id = $_POST['id1'];
        $assignment_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$get_id' AND class_code = '$code'");
        $assignment_row = mysqli_fetch_array($assignment_query);
        $assignment_due = $assignment_row['due_date'];
        $assignmentid = $assignment_row['assignment_id'];
        $assignment_title = $assignment_row['title'];
        $filetypename = $_POST['name1'];
        $prof_name = $assignment_row['name'];
        $filetypename = strip_tags($filetypename);
        $filetypename = mysqli_real_escape_string($conn, $filetypename);
        $check_empty = preg_replace('/\s+/', '', $filetypename);
        $descript = $_POST['desc1'];
        date_default_timezone_set('Asia/Singapore');
        $date_added = date("Y-m-d H:i:s");
        $file = $_FILES['file1'];
        $fileName = $_FILES['file1']['name'];
        $fileTmpName = $_FILES['file1']['tmp_name'];
        $fileSize = $_FILES['file1']['size'];
        $fileError = $_FILES['file1']['error'];
        $fileType = $_FILES['file1']['type'];

        if($assignment_due < $date_added)
        {
            $status = "Turned in late";
        }
        else
        {
            
            $status = "Turned in";
        }

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx', 'xls');

        if($check_empty != "" && $fileName == "")
        {
            $query = mysqli_query($conn, "UPDATE student_assignment SET file_name = '$filetypename', description = '$descript', date_added = '$date_added', files = '$fileName', files_destionation = '$fileDestination' WHERE assignment_id = '$get_id' AND class_code = '$code'");
            $notif_desc = "has resubmitted his/her classwork. [$filetypename]";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Adviser_Classwork.php?classCode=$code";

            $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$prof_name', '$code', '$date_time_now','no')");
            
            
            header("Location: Student_DashClasswork.php?classCode=$code&updatesuccess");
        }

        else if($fileName != "")
        {
            if(in_array($fileActualExt, $allowed))
            {
                if($fileError === 0)
                {
                    if($fileSize < 50000000)
                    {
                        $fileDestination = '../uploads/'.$fileName;
                        move_uploaded_file($fileTmpName,$fileDestination);

                        $query = mysqli_query($conn, "UPDATE student_assignment SET file_name = '$filetypename', description = '$descript', date_added = '$date_added', files = '$fileName', files_destination = '$fileDestination' WHERE assignment_id = '$get_id' AND class_code = '$code'");
                        $notif_desc = "has resubmitted his/her classwork. [$filetypename]";
                        $date_time_now = date("Y-m-d H:i:s");
                        $notif_link = "Adviser_Classwork.php?classCode=$code";

                        $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$prof_name', '$code', '$date_time_now','no')");
                        
                        
                       
                        header("Location: Student_DashClasswork.php?classCode=$code&updatesuccess");
                    }
                
                    else{
                        header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
                    }
                }
            
                else{
                    header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
                }
            }

            else{
                header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
            }
        }
    }


    ?>
    <!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - Classwork</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel = "stylesheet" href = "../css/student_dashclass.css">
    
</head>

<body id="body">
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->
    
     <!--SIDE BAR-->
     <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <!--Background Side Bar-->

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
        <div class = "topnavbar" style ="z-index:1;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">


            <div class="topnavbar-right" id="topnav_right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->

            <br><br><br><br>
            <script>
            
            const tabletSize1 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize1) {
            if (tabletSize1.matches) 
            {
                document.getElementById('topnav_right').style.float = "left";
                document.getElementById('topnav_right').style.fontSize = "12px";
                document.getElementById('body').style.fontSize = "12.5px";
                
            } 
            else
            {

                document.getElementById('topnav_right').classList.add('topnavbar-right');
                document.getElementById('topnav_right').style.fontSize = "13.5px";
                document.getElementById('body').style.fontSize = "";
            }
            }

            tabletSize1.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize1);
        </script>
        <?php
        if(isset($_REQUEST['updatesuccess']))
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
                title: 'Classwork submission has been updated.'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['unsuccess']))
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
                icon: 'warning',
                title: 'There was an error uploading your file.'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['uploadsuccess']))
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
                title: 'Classwork submission has been uploaded.'
            })
            </script>
      <?php
        } ?>
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Need to Resubmit <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Student_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
            <hr style="background-color:maroon;border-width:2px;">
            &emsp;&emsp; 

    <?php

    $stud_ass_returned = mysqli_query($conn, "SELECT * FROM student_assignment WHERE studentnum = '$studentnum' AND class_code = '$code' AND status = 'Need to resubmit'");
    if(mysqli_num_rows($stud_ass_returned) > 0)
    {
        $str = "";

        while($row = mysqli_fetch_array($stud_ass_returned))
        {
            $id = $row['id'];
            $ass_id = $row['assignment_id'];
            $studentnum = $row['studentnum'];
            $file_name = $row['file_name'];
            $description = $row['description'];
            $class_code = $row['class_code'];
            $date_added = $row['date_added'];
            $date_added_display = date("M j Y, H:i:s A", strtotime($row['date_added']));
            $due_date = $row['due_date'];
            $due_date_display =  date("M j Y, H:i:s A", strtotime($row['due_date']));
            $files = $row['files'];
            $points = $row['points'];
            $files_destination = $row['files_destination'];
            $grade = $row['grade'];
            $status = $row['status'];

            $fileExt1 = explode('.', $files);
            $fileActualExt1 = end($fileExt1);
            $allowed1  = array('jpg','jpeg','png', 'JPG', 'JPEG' ,'PNG');
            $fileDiv1 = "";
            
            if (in_array($fileActualExt1, $allowed1)) 
            {
                $fileDiv1 = "<div id='postedFile'>
                <img src='$files_destination' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='width:auto;min-width:100%;max-width:250px; height:auto;min-height:100%;max-height:250px;'>
                </div>";
            }

            $adv_classwork = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$ass_id' AND class_code = '$code'");
            $classrow = mysqli_fetch_array($adv_classwork);
            if(!empty($classrow))
            {
                $work_id = $classrow['assignment_id'];
                $faculty = $classrow['facultynum'];
                $adv_name = $classrow['name'];
                $title = $classrow['title'];
                $instruction = $classrow['instruction'];
                $ass_added = $classrow['date_added'];
                $ass_added_display = date("M j Y, H:i:s A", strtotime($classrow['date_added']));
                $ass_duedate = $classrow['due_date'];
                $ass_duedate_display =  date("M j Y, H:i:s A", strtotime($classrow['due_date']));
                $start_date = $classrow['start_date'];
                $start_date_display =  date("M j Y, H:i:s A", strtotime($classrow['start_date']));
                $ass_file = $classrow['files'];
                $type = $classrow['type'];
                $possible_point = $classrow['points'];
                $ass_destination = $classrow['files_destination'];

                $fileExt = explode('.', $ass_file);
                $fileActualExt = end($fileExt);
                $allowed  = array('jpg','jpeg','png');
                $fileDiv = "";

                if (in_array($fileActualExt, $allowed)) 
                {
                    $fileDiv = "<div id='postedFile'>
                    <img src='$ass_destination' onclick='window.open(this.src)' title='Click Here To View Full Screen'  style='width:auto;min-width:100%;max-width:250px; height:auto;min-height:100%;max-height:250px;' >
                    </div>";
                }

                date_default_timezone_set('Asia/Singapore');
                $date_today = date("Y-m-d H:i:s");

                ?> <!--end of first php -->
                        
                <script>
                    function toggle<?php echo $work_id; ?>() {
                        var element = document.getElementById("toggleClass<?php echo $work_id; ?>");

                        if (element.style.display == "block")
                            element.style.display = "none";
                        else
                            element.style.display = "block";
                        
                    } //end of function   
                </script>

                <?php

                $str .= "<br>
                <div class='card-body stylebox' style='background-color:white;'>
                <div onClick='javascript:toggle$work_id(); myFunction(  )'>
                    <div class='card-body stylebox' style='background-color: #0049B4'>
                    
                    <div class='row'>
                            <div class='col-6'>
                                <b style = 'font-weight: 600;color:white;'>Assignment Title: $title </b> 
                                <br>
                                </div>
                            <div class='col-6'>
                                <b style = 'font-weight: 600;color:white;'>Type: $type </b>
                            </div>
                            
                    </div>


                    <div class='row'>
                    <div class='col-6' style='color:white;'>
                        ";
                        if($start_date === '0000-00-00 00:00:00')
                        {
                            $str .="
                        
                            Start Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                            ";
                        }
                        else
                        {
                            $str .="
                        
                            Start Date: $start_date_display &emsp;
                            ";

                        }
                        $str .="
                    </div>
                    <div class='col-6' style='color:white;'>
                    ";
                    if($due_date === '0000-00-00 00:00:00')
                    {
                        $str .="
                    
                        Due Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; On going
                        ";
                    }
                    else
                    {
                        $str .="
                    
                        Due Date: $due_date_display &emsp; $status
                        ";

                    }
                    $str .="
                        
                        </div>
                </div>

                            
                    </div>
                </div>
                            
                <div class='card-body display' id='toggleClass$work_id'>
                                                                    
                        ";
                        if($type === 'Graded') 
                        {
                            if(substr($classrow['files_destination'], -4) === ".jpg" || substr($classrow['files'], -4) === ".jpg" || substr($classrow['files_destination'], -5) === ".jpeg" || substr($classrow['files'], -5) === ".jpeg"  ||  substr($classrow['files_destination'], -4) === ".png" || substr($classrow['files'], -4) === ".png")
                            { $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    <div class = 'col-2'>Points: &emsp; /  $points  </div>
                
                                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                    <br><br>
                                    <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
                                
                                </div> <br><br>";
                                

                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                    <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                    
                                        <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>";
                                }
                                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                        <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                                else
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                        
                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                            
                            }
                            else if(substr($classrow['files_destination'], -4) === ".pdf" || substr($classrow['files'], -4) === ".pdf" || substr($classrow['files_destination'], -5) === ".docx" || substr($classrow['files'], -5) === ".docx"  ||  substr($classrow['files_destination'], -4) === ".doc" || substr($classrow['files'], -4) === ".doc"  ||  substr($classrow['files_destination'], -4) === ".ppt" || substr($classrow['files'], -4) === ".ppt"  ||  substr($classrow['files_destination'], -5) === ".pptx" || substr($classrow['files'], -5) === ".pptx"  ||  substr($classrow['files_destination'], -4) === ".xls" || substr($classrow['files'], -4) === ".xls")
                            {
                                $str .="<div class = 'row text'>
                                            <div class = 'col-4'> Instructor $name  </div>
                                            <div class = 'col-6'> $date_added_display  </div>
                                            <div class = 'col-2'>Points: &emsp; /  $points  </div>
                        
                                            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div> <br>
                                            <br><br>
                                            <div class = 'col-12' style=' margin-left:1%;width:10%;'>
                                            <br> <a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$classrow['files']}'>{$classrow['files']}</a> 
                                            </div>
                                        </div><br><br>";
                                
                                        if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                            <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                            
                                                <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>";
                                        }
                                        else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                                <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                                        else
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                                
                                            <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                            }
                            else
                            {
                                $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    <div class = 'col-2'>Points: &emsp; /  $points  </div>
                
                                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                    
                                </div><br><br>";
                        
                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                            <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                            
                                                <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>";
                                        }
                                        else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                                <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                                        else
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                                
                                            <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                            }
                        }
                        else
                        {
                            if(substr($classrow['files_destination'], -4) === ".jpg" || substr($classrow['files'], -4) === ".jpg" || substr($classrow['files_destination'], -5) === ".jpeg" || substr($classrow['files'], -5) === ".jpeg"  ||  substr($classrow['files_destination'], -4) === ".png" || substr($classrow['files'], -4) === ".png")
                            { $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    
                
                                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                    <br><br>
                                    <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
                                
                                </div> <br><br>";
                                

                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                    <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                    
                                        <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>";
                                }
                                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                        <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                                else
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                        
                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                            
                            }
                            else if(substr($classrow['files_destination'], -4) === ".pdf" || substr($classrow['files'], -4) === ".pdf" || substr($classrow['files_destination'], -5) === ".docx" || substr($classrow['files'], -5) === ".docx"  ||  substr($classrow['files_destination'], -4) === ".doc" || substr($classrow['files'], -4) === ".doc"  ||  substr($classrow['files_destination'], -4) === ".ppt" || substr($classrow['files'], -4) === ".ppt"  ||  substr($classrow['files_destination'], -5) === ".pptx" || substr($classrow['files'], -5) === ".pptx"  ||  substr($classrow['files_destination'], -4) === ".xls" || substr($classrow['files'], -4) === ".xls")
                            {
                                $str .="<div class = 'row text'>
                                            <div class = 'col-4'> Instructor $name  </div>
                                            <div class = 'col-6'> $date_added_display  </div>
                                            
                        
                                            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  <br></div>
                                            <br><br>
                                            <div class = 'col-12' style=' margin-left:1%;'>
                                            <br>
                                                <a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> 
                                            </div>
                                        </div><br><br>";
                                
                                        if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                            <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                            
                                                <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>";
                                        }
                                        else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                            <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                                <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                                        else
                                        {
                                            $str .="
                                            <br><br><hr class='dashed'><h4> Your work </h4>
                                            <div class = 'row text'>
                                                
                                            <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                            </div>
                                            ";
                                        }
                            }
                            else
                            {
                                $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    
                
                                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                                    
                                </div><br><br>";
                        
                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                    <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$row['files']}'>Full View</a></div></center> </div>
                                    
                                        <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>";
                                }
                                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                    <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;margin-left:5%;position:sticky;' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a></center><br>
                                        <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                                else
                                {
                                    $str .="
                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                    <div class = 'row text'>
                                        
                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $files </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$description</h6> </div>
                                    </div>
                                    ";
                                }
                            }

                        }
                        if($due_date > $date_today || $due_date === "0000-00-00 00:00:00")
                        {
                                $str .="
                            <hr class='dashed'>
                            <button class='filebtn hfilebtn' id='unsubmitBtn' type='button' style='float:right;' data-toggle='modal' data-target='#unsubmitModal$id'>Unsubmit</button>";
                            if(isset($_POST['unsubmit']))
                            {
                                ?>
                                    <script type='text/javascript'>
                                            $(document).ready(function(){
                                                document.getElementById("unsubmitBtn").style.display = "none";
                                            });
                                    </script>
                                <?php
                                
                        
                    
                    
                    $str .="
                        <button class='filebtn hfilebtn'";if(isset($_POST['unsubmit'])){ $str.="type='button' style='float:right;' data-toggle='modal' data-target='#submitagainModal$id'";}else{$str.="style='display:none;'";}$str.=">+ Add or Change</button>
                        ";}
                        }
                        else if($status === "Need to resubmit")
                        {
                            $str .="
                            <hr class='dashed'>
                            <button class='filebtn hfilebtn' id='unsubmitBtn' type='button' style='float:right;' data-toggle='modal' data-target='#unsubmitModal$id'>Unsubmit</button>";
                            if(isset($_POST['unsubmit']))
                            {
                                ?>
                                    <script type='text/javascript'>
                                            $(document).ready(function(){
                                                document.getElementById("unsubmitBtn").style.display = "none";
                                            });
                                    </script>
                                <?php
                                
                        
                    
                    
                    $str .="
                        <button class='filebtn hfilebtn'";if(isset($_POST['unsubmit'])){ $str.="type='button' style='float:right;' data-toggle='modal' data-target='#submitagainModal$id'";}else{$str.="style='display:none;'";}$str.=">+ Add or Change</button>
                        ";}
                        }
                        else if($due_date < $date_today)
                        {
                            $str .="<hr class='dashed'>
                            ";
                        }
                        $str .="
                        <div class='modal fade' id='submitagainModal$id' tabindex='-1' aria-labelledby='submitagainModalLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content'>
                            

                                <div class='modal-header'>
                                    <h5 class='modal-title' id='submitagainModalLabel'>Submit an Assignment</h5>
                                </div>

                                <form method='POST' enctype='multipart/form-data'>
                                <div class='modal-body p-5'>
                                    

                                    <div class='md-file'>
                                        <label for='formFile' class='form-label'></label>
                                        <input class='form-control' type='file' name='file1' id='formFile'>
                                    </div>

                                    <br>

                                    <div class='md-title'>
                                        <label for='exampleFormControlInput' name='title' class='form-label'>File Name</label>
                                        <input type='text' name='name1' class='form-control' id='exampleFormControlInput' placeholder='$file_name'>
                                    </div>

                                    <br>

                                    <div class='md-textarea'>
                                        <label for='exampleFormControlTextarea1' class='form-label'>Description</label>
                                        <textarea name='desc1' class='form-control' id='exampleFormControlTextarea1' placeholder='$description' rows='3'></textarea>
                                    </div>

                                </div>

                                <div class='modal-footer'>
                                    <input type='hidden' name='id1' value='$id'>
                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                    <button type='submit' name='saveagain' class='btn btn-primary'>Resubmit</button>
                                </div></form>
                                

                            </div>
                        </div>
                    </div>
                    <div id='unsubmitModal$id' class='modal fade' tabindex='-1' role='dialog' aria-hidden='true'>
                    <div class='modal-dialog modal-lg'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h4 class='modal-title'>Unsubmit?</h4>
                            </div>
                            <div class='modal-body'>
                                Unsubmit to add or change attachments. Don't forget to resubmit once you're done.
                            </div>
                            <div class='modal-footer'>
                            <form method = 'POST'>
                                <input type='hidden' name='id' value='$id'>
                                <button class='btn btn-danger' type='submit' name='unsubmit'>Unsubmit</button> 
                                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                    </div>
                </div></form>
                
                </div>
                        
            </div>";
            }
            else
            {
                $str = "
                <br>
                <div class='card-deck-6'>
                    <div class='card style'>
                        <div class='card-body text-center'>
                        <br>
                        <img alt='Join Class' class = 'img-join' src='../images/class-img.png' style='width:25%;height:25%;'><br><br><br>
                        <h5> There's no record for Finished Task yet.</h5>
                        </div>
                    </div>
                </div>
                ";
                
            }
            
        }
        echo $str;

    }
    else
    {
        $str = "
        <br>
        <div class='card-deck-6'>
            <div class='card style'>
                <div class='card-body text-center'>
                <br>
                <img alt='Join Class' class = 'img-join' src='../images/class-img.png' style='width:25%;height:25%;'><br><br><br>
                <h5> There's no record for Returned Task yet.</h5>
                </div>
            </div>
        </div>
        ";
        echo $str;
    }
} //end of returned

if(isset($_REQUEST['unfinished']))
{
    $code = $_REQUEST['classCode'];
    $studentnum = $_SESSION['studnum'];
    

    if(isset($_POST['save']))
    {   


        $get_id = $_POST['id'];
        $status = "";

      
        $assignment_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$get_id' AND class_code = '$code'");
        $assignment_row = mysqli_fetch_array($assignment_query);
        $assignment_due = $assignment_row['due_date'];
        $assignmentid = $assignment_row['assignment_id'];
        $assignment_title = $assignment_row['title'];
        $filetypename = $_POST['name'];
        $filetypename = strip_tags($filetypename);
        $filetypename = mysqli_real_escape_string($conn, $filetypename);
        $check_empty = preg_replace('/\s+/', '', $filetypename);
        $descript = $_POST['desc'];
        date_default_timezone_set('Asia/Singapore');
        $date_added = date("Y-m-d H:i:s");
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];
            
        
            if($assignment_due < $date_added)
            {
                $status = "Turned in late";
            }
            else
            {
               
                $status = "Turned in";
            }
            
            

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'ppt', 'xls');

        if($check_empty != "" && $fileName == "")
        {
            $query = mysqli_query($conn, "INSERT INTO student_assignment VALUES('','$assignmentid', '$studentnum', '$filetypename', '$descript', '$code', '$date_added', '$assignment_due', '', '','','', '$status') ");
            header("Location: Student_DashClasswork.php?classCode=$code&uploadsuccess");
        }
        
        else if($fileName != "")
        {
            if(in_array($fileActualExt, $allowed))
            {
                if($fileError === 0)
                {
                    if($fileSize < 1000000000)
                    {
                        $fileDestination = '../uploads/'.$fileName;
                        move_uploaded_file($fileTmpName,$fileDestination);

                        $sql = mysqli_query($conn, "INSERT INTO student_assignment VALUES('','$assignmentid', '$studentnum', '$filetypename', '$descript', '$code', '$date_added', '$assignment_due','$fileName', '', '$fileDestination', '', '$status')");
                        
                        $message = "Your classwork has been submitted!";
                        header("Location: Student_DashClasswork.php?classCode=$code&uploadsuccess");
                        
                    }
                
                    else{
                        header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
                    }
                }
            
                else{
                    header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
                }
            }
    
            else{
                header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
            }
    }
    }


    ?>
    <!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - Classwork</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel = "stylesheet" href = "../css/student_dashclass.css">
    
</head>

<body id="body">
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
      <div class = "topnavbar" style ="z-index:1;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">


            <div class="topnavbar-right" id="topnav_right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->

            <br><br><br><br>
            <script>
            
            const tabletSize2 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize2) {
            if (tabletSize2.matches) 
            {
                document.getElementById('topnav_right').style.float = "left";
                document.getElementById('topnav_right').style.fontSize = "12px";
                document.getElementById('body').style.fontSize = "12.5px";
                
            } 
            else
            {

                document.getElementById('topnav_right').classList.add('topnavbar-right');
                document.getElementById('topnav_right').style.fontSize = "13.5px";
                document.getElementById('body').style.fontSize = "";
            }
            }

            tabletSize2.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize2);
        </script>
            
        <?php
        if(isset($_REQUEST['updatesuccess']))
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
                title: 'Classwork submission has been updated.'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['unsuccess']))
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
                icon: 'warning',
                title: 'There was an error uploading your file.'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['uploadsuccess']))
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
                title: 'Classwork submission has been uploaded.'
            })
            </script>
      <?php
        } ?>
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Unfinished Tasks <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Student_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
            <hr style="background-color:maroon;border-width:2px;">
            &emsp;&emsp; 

    <?php
        $datenow = date("Y-m-d H:i:s");
        
    $get_assignment1 = mysqli_query($conn, "SELECT t1.* FROM adviser_assignment t1 WHERE NOT EXISTS (SELECT * FROM student_assignment t2 WHERE t2.assignment_id = t1.assignment_id AND t2.studentnum = '$studentnum' ) AND t1.class_code = '$code' AND t1.due_date > '$datenow'");
    $get_assignment2 = mysqli_query($conn, "SELECT t1.* FROM adviser_assignment t1 WHERE NOT EXISTS (SELECT * FROM student_assignment t2 WHERE t2.assignment_id = t1.assignment_id AND t2.studentnum = '$studentnum' ) AND t1.class_code = '$code' AND t1.due_date = '0000-00-00 00:00:00'");
    if(mysqli_num_rows($get_assignment1) > 0)
    {
        $str = "";
        while($row = mysqli_fetch_array($get_assignment1))
        {
            $id = $row['assignment_id'];
            $title = $row['title'];
            $name = $row['name'];
            $instruction = $row['instruction'];
            $date_added = $row['date_added'];
            $date_added_display = date("M j Y, H:i:s A", strtotime($row['date_added']));
            $start_date = $row['start_date'];
            $start_date_display =  date("M j Y, H:i:s A", strtotime($row['start_date']));
            $due_date = $row['due_date'];
            $due_date_display =  date("M j Y, H:i:s A", strtotime($row['due_date']));
            $points = $row['points'];
            $file = $row['files'];
            $image = $row['files_destination'];
            $type = $row['type'];
            $fileExt = explode('.', $file);
            $fileActualExt = end($fileExt);
            $allowed  = array('jpg','jpeg','png');
            $fileDiv = "";
            if (in_array($fileActualExt, $allowed)) 
            {
                $fileDiv = "<div id='postedFile'>
                <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;' >
                </div>";
            }
            $formatted_date = date("Y-m-d\Th:i", strtotime($row['due_date']));
            $formatted_date_start = date("Y-m-d\TH:i", strtotime($row['start_date']));

            $checkquery = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$id' AND studentnum = '$studentnum' AND class_code = '$code'");
            $row1 = mysqli_fetch_array($checkquery);
            if(!empty($row1)){
            $duedate = $row1['due_date'];
            $dateadded = $row1['date_added'];
            $status = $row1['status'];
            }
            else
            {   
                $duedate = "";
                $dateadded = "";
                $status = "";
            }
            

            date_default_timezone_set('Asia/Singapore');
            $date_today = date("Y-m-d H:i:s");
            if(mysqli_num_rows($checkquery) === 0)
            {

                if($due_date < $date_today)
                {
                   
                  $status = 'Missing';
                  
                }
                
                else if($due_date > $date_today)
                {
                    
                   $status = 'On going';
                 
                }
                else if($due_date === '0000-00-00 00:00:00')
                {
                    $status = 'On going';
                }
            }
                


            ?> <!--end of first php -->
                    
            <script>
                    function toggle<?php echo $id; ?>() {
                        var element = document.getElementById("toggleClass<?php echo $id; ?>");

                        if (element.style.display == "block")
                            element.style.display = "none";
                        else
                            element.style.display = "block";
                        
                    } //end of function   
                </script>
           <?php

        $str .= "<br>
        <div class='card-body stylebox' style='background-color:white;'>
        <div onClick='javascript:toggle$id(); myFunction(this)'>
            <div class='card-body stylebox' style='background-color: #EA8000;'>
        
                <div class='row'>
                    <div class='col-6'>
                        <b style = 'font-weight: 600;color:white;'>Assignment Title: $title </b> 
                        <br>
                        </div>
                    <div class='col-6'>
                        <b style = 'font-weight: 600;color:white;'>Type: $type </b>
                    </div>
                    
                </div>
                <div class='row'>
                    <div class='col-6' style='color:white;'>
                        ";
                        if($start_date === '0000-00-00 00:00:00')
                        {
                            $str .="
                        
                            Start Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                            ";
                        }
                        else
                        {
                            $str .="
                        
                            Start Date: $start_date_display &emsp;
                            ";

                        }
                        $str .="
                    </div>
                    <div class='col-6' style='color:white;'>
                    ";
                    if($due_date === '0000-00-00 00:00:00')
                    {
                        $str .="
                    
                        Due Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; On going
                        ";
                    }
                    else
                    {
                        $str .="
                    
                        Due Date: $due_date_display &emsp; $status
                        ";

                    }
                    $str .="
                        
                        </div>
                </div>
                
            </div>
        </div>
              
        <div class='card-body display' id='toggleClass$id'>
                                                    
        ";
        if($type === 'Graded') 
        {
            if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
            { $str .="
                <div class = 'row text'>
                    <div class = 'col-4'> Instructor $name  </div>
                    <div class = 'col-6'> $date_added_display  </div>
                    <div class = 'col-2'>Points: &emsp; /  $points  </div>

                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                    <br><br>
                    <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
                
                </div> <br><br>
                ";
            }
                
            else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
            {
                $str .="
                    <div class = 'row text'>
                        <div class = 'col-4'> Instructor $name  </div>
                        <div class = 'col-6'> $date_added_display  </div>
                        <div class = 'col-2'>Points: &emsp; /  $points  </div>
    
                        <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                        <br><br>
                        <div class = 'col-12'><br><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                        
                    </div> <br><br><hr class='dashed'>"; 
            }
            else
            {
                $str .="
                    <div class = 'row text'>
                        <div class = 'col-4'> Instructor $name  </div>
                        <div class = 'col-6'> $date_added_display  </div>
                        <div class = 'col-2'>Points: &emsp; /  $points  </div>
    
                        <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                        <br><br>
                    </div> <br><br><hr class='dashed'>"; 
            }
        } 
        else 
        {
            if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
            {
                $str .="
                <div class = 'row text'>
                    <div class = 'col-4'> Instructor $name  </div>
                    <div class = 'col-6'> $date_added_display  </div>
                    

                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                    <br><br>
                    <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
                
                </div> <br><br>
                ";
                }
                
                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                {
                    $str .="
                        <div class = 'row text'>
                            <div class = 'col-4'> Instructor $name  </div>
                            <div class = 'col-6'> $date_added_display  </div>

                            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div> <br>
                            <br><br>
                            <div class = 'col-12'><br><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                            
                        </div> <br><br><hr class='dashed'>"; 
                }
                else
                {
                    $str .="
                        <div class = 'row text'>
                            <div class = 'col-4'> Instructor $name  </div>
                            <div class = 'col-6'> $date_added_display  </div>

                            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                            <br><br>
                        </div> <br><br><hr class='dashed'>"; 
                }
        }
      

        if($formatted_date_start <= $date_today)
        {
            $str .= "
        
            <button type='button' class='filebtn hfilebtn' style='float:right;' data-toggle='modal' data-target='#submitModal$id'>
            + Add or Create
            </button> ";
        }
        else if($start_date === '0000-00-00 00:00:00')
        {
            $str .= "
        
            <button type='button' class='filebtn hfilebtn' style='float:right;' data-toggle='modal' data-target='#submitModal$id'>
            + Add or Create
            </button> ";
        }
        
        $str .= "

        <div class='modal fade' id='submitModal$id' tabindex='-1' aria-labelledby='submitModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-lg'>
            <div class='modal-content'>

                <div class='modal-header'>
                    <h5 class='modal-title' id='submitModalLabel'>Submit an Assignment</h5>
                </div>

                <form method='POST' enctype='multipart/form-data'>
                <div class='modal-body p-5'>
                    

                <div class='md-file' data-tooltip='Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX. Maximum File Size accepted is 50 MB.'>
                <label for='formFile' class='form-label'></label>
                <input class='form-control' type='file' name='file' id='formFile1'>
                <style>

                .md-file:hover:after {
                content:attr(data-tooltip);
                }

                </style>

                </div>

                    <br>

                    <div class='md-title'>
                        <label for='exampleFormControlInput1' name='title' class='form-label'>File Name</label>
                        <input type='text' name='name' class='form-control' id='exampleFormControlInput1' placeholder='Title' required>
                    </div>

                    <br>

                    <div class='md-textarea'>
                        <label for='exampleFormControlTextarea1' class='form-label'>Description</label>
                        <textarea name='desc' class='form-control' id='exampleFormControlTextarea1' placeholder='Instructions' rows='3'></textarea>
                    </div>

                </div>

                <div class='modal-footer'>
                    <input type='hidden' name='id' value='$id'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                    <button type='submit' name='save' class='btn btn-primary'>Submit</button>
                </div></form>
                
            </div>
        </div>
    </div>

</div>
</div>";

        }
        echo $str;

    }


    if(mysqli_num_rows($get_assignment2) > 0)
    {
        $str = "";
        while($row = mysqli_fetch_array($get_assignment2))
        {
            $id = $row['assignment_id'];
            $title = $row['title'];
            $name = $row['name'];
            $instruction = $row['instruction'];
            $date_added = $row['date_added'];
            $date_added_display = date("M j Y, H:i:s A", strtotime($row['date_added']));
            $start_date = $row['start_date'];
            $start_date_display =  date("M j Y, H:i:s A", strtotime($row['start_date']));
            $due_date = $row['due_date'];
            $due_date_display =  date("M j Y, H:i:s A", strtotime($row['due_date']));
            $points = $row['points'];
            $file = $row['files'];
            $image = $row['files_destination'];
            $type = $row['type'];
            $fileExt = explode('.', $file);
            $fileActualExt = end($fileExt);
            $allowed  = array('jpg','jpeg','png');
            $fileDiv = "";
            if (in_array($fileActualExt, $allowed)) 
            {
                $fileDiv = "<div id='postedFile'>
                <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;' >
                </div>";
            }
            $formatted_date = date("Y-m-d\Th:i", strtotime($row['due_date']));
            $formatted_date_start = date("Y-m-d\TH:i", strtotime($row['start_date']));

            $checkquery = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$id' AND studentnum = '$studentnum' AND class_code = '$code'");
            $row1 = mysqli_fetch_array($checkquery);
            if(!empty($row1)){
            $duedate = $row1['due_date'];
            $dateadded = $row1['date_added'];
            $status = $row1['status'];
            }
            else
            {   
                $duedate = "";
                $dateadded = "";
                $status = "";
            }
            

            date_default_timezone_set('Asia/Singapore');
            $date_today = date("Y-m-d H:i:s");
   
            if(mysqli_num_rows($checkquery) === 0)
            {

                if($due_date < $date_today)
                {
                   
                  $status = 'Missing';
                  
                }
                
                else if($due_date > $date_today)
                {
                    
                   $status = 'On going';
                 
                }
                else if($due_date === '0000-00-00 00:00:00')
                {
                    $status = 'On going';
                }
            }
                


            ?> <!--end of first php -->
                    
            <script>
                    function toggle<?php echo $id; ?>() {
                        var element = document.getElementById("toggleClass<?php echo $id; ?>");

                        if (element.style.display == "block")
                            element.style.display = "none";
                        else
                            element.style.display = "block";
                        
                    } //end of function   
                </script>
           <?php

        $str .= "<br>
        <div class='card-body stylebox' style='background-color:white;'>
        <div onClick='javascript:toggle$id(); myFunction(this)'>
            <div class='card-body stylebox' style='background-color: #EA8000;'>
        
                <div class='row'>
                    <div class='col-6'>
                        <b style = 'font-weight: 600;color:white;'>Assignment Title: $title </b> 
                        <br>
                        </div>
                    <div class='col-6'>
                        <b style = 'font-weight: 600;color:white;'>Type: $type </b>
                    </div>
                    
                </div>
                <div class='row'>
                    <div class='col-6' style='color:white;'>
                        ";
                        if($start_date === '0000-00-00 00:00:00')
                        {
                            $str .="
                        
                            Start Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                            ";
                        }
                        else
                        {
                            $str .="
                        
                            Start Date: $start_date_display &emsp;
                            ";

                        }
                        $str .="
                    </div>
                    <div class='col-6' style='color:white;'>
                    ";
                    if($due_date === '0000-00-00 00:00:00')
                    {
                        $str .="
                    
                        Due Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; On going
                        ";
                    }
                    else
                    {
                        $str .="
                    
                        Due Date: $due_date_display &emsp; $status
                        ";

                    }
                    $str .="
                        
                        </div>
                </div>
                
            </div>
        </div>
              
        <div class='card-body display' id='toggleClass$id'>
                                                    
        ";
        if($type === 'Graded') 
        {
            if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
            { $str .="
                <div class = 'row text'>
                    <div class = 'col-4'> Instructor $name  </div>
                    <div class = 'col-6'> $date_added_display  </div>
                    <div class = 'col-2'>Points: &emsp; /  $points  </div>

                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                    <br><br>
                    <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
                
                </div> <br><br>
                ";
            }
                
            else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
            {
                $str .="
                    <div class = 'row text'>
                        <div class = 'col-4'> Instructor $name  </div>
                        <div class = 'col-6'> $date_added_display  </div>
                        <div class = 'col-2'>Points: &emsp; /  $points  </div>
    
                        <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                        <br><br>
                        <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                        
                    </div> <br><br><hr class='dashed'>"; 
            }
            else
            {
                $str .="
                    <div class = 'row text'>
                        <div class = 'col-4'> Instructor $name  </div>
                        <div class = 'col-6'> $date_added_display  </div>
                        <div class = 'col-2'>Points: &emsp; /  $points  </div>
    
                        <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                        <br><br>
                    </div> <br><br><hr class='dashed'>"; 
            }
        } 
        else 
        {
            if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
            {
                $str .="
                <div class = 'row text'>
                    <div class = 'col-4'> Instructor $name  </div>
                    <div class = 'col-6'> $date_added_display  </div>
                    

                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                    <br><br>
                    <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
                
                </div> <br><br>
                ";
                }
                
                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                {
                    $str .="
                        <div class = 'row text'>
                            <div class = 'col-4'> Instructor $name  </div>
                            <div class = 'col-6'> $date_added_display  </div>

                            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                            <br><br>
                            <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                            
                        </div> <br><br><hr class='dashed'>"; 
                }
                else
                {
                    $str .="
                        <div class = 'row text'>
                            <div class = 'col-4'> Instructor $name  </div>
                            <div class = 'col-6'> $date_added_display  </div>

                            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                            <br><br>
                        </div> <br><br><hr class='dashed'>"; 
                }
        }
      

        if($formatted_date_start <= $date_today)
        {
            $str .= "
        
            <button type='button' class='filebtn hfilebtn' style='float:right;' data-toggle='modal' data-target='#submitModal$id'>
            + Add or Create
            </button> ";
        }
        else if($start_date === '0000-00-00 00:00:00')
        {
            $str .= "
        
            <button type='button' class='filebtn hfilebtn' style='float:right;' data-toggle='modal' data-target='#submitModal$id'>
            + Add or Create
            </button> ";
        }
        
        $str .= "

        <div class='modal fade' id='submitModal$id' tabindex='-1' aria-labelledby='submitModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-lg'>
            <div class='modal-content'>

                <div class='modal-header'>
                    <h5 class='modal-title' id='submitModalLabel'>Submit an Assignment</h5>
                </div>

                <form method='POST' enctype='multipart/form-data'>
                <div class='modal-body p-5'>
                    

                <div class='md-file' data-tooltip='Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX. Maximum File Size accepted is 50 MB.'>
                <label for='formFile' class='form-label'></label>
                <input class='form-control' type='file' name='file' id='formFile1'>
                <style>

                .md-file:hover:after {
                content:attr(data-tooltip);
                }

                </style>

                </div>

                    <br>

                    <div class='md-title'>
                        <label for='exampleFormControlInput1' name='title' class='form-label'>File Name</label>
                        <input type='text' name='name' class='form-control' id='exampleFormControlInput1' placeholder='Title' required>
                    </div>

                    <br>

                    <div class='md-textarea'>
                        <label for='exampleFormControlTextarea1' class='form-label'>Description</label>
                        <textarea name='desc' class='form-control' id='exampleFormControlTextarea1' placeholder='Instructions' rows='3'></textarea>
                    </div>

                </div>

                <div class='modal-footer'>
                    <input type='hidden' name='id' value='$id'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                    <button type='submit' name='save' class='btn btn-primary'>Submit</button>
                </div></form>
                
            </div>
        </div>
    </div>

</div>
</div>";


        }
        echo $str;

    }
    else if(mysqli_num_rows($get_assignment2) == 0 && mysqli_num_rows($get_assignment1) == 0)
    {
        $str = "
        <br>
        <div class='card-deck-6'>
            <div class='card style'>
                <div class='card-body text-center'>
                <br>
                <img alt='Join Class' class = 'img-join' src='../images/class-img.png' style='width:25%;height:25%;'><br><br><br>
                <h5> There's no record for Unfinished Task.</h5>
                </div>
            </div>
        </div>
        ";
        echo $str;
    }

}//end of unfinished

if(isset($_REQUEST['missing']))
{
    $code = $_REQUEST['classCode'];
    $studentnum = $_SESSION['studnum'];
    

    if(isset($_POST['save']))
    {   


        $get_id = $_POST['id'];
        $status = "";

      
        $assignment_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$get_id' AND class_code = '$code'");
        $assignment_row = mysqli_fetch_array($assignment_query);
        $assignment_due = $assignment_row['due_date'];
        $assignmentid = $assignment_row['assignment_id'];
        $assignment_title = $assignment_row['title'];
        $filetypename = $_POST['name'];
        $filetypename = strip_tags($filetypename);
        $filetypename = mysqli_real_escape_string($conn, $filetypename);
        $check_empty = preg_replace('/\s+/', '', $filetypename);
        $descript = $_POST['desc'];
        date_default_timezone_set('Asia/Singapore');
        $date_added = date("Y-m-d H:i:s");
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];
            
        
            if($assignment_due < $date_added)
            {
                $status = "Turned in late";
            }
            else
            {
               
                $status = "Turned in";
            }
            
            

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'ppt', 'xls');

        if($check_empty != "" && $fileName == "")
        {
            $query = mysqli_query($conn, "INSERT INTO student_assignment VALUES('','$assignmentid', '$studentnum', '$filetypename', '$descript', '$code', '$date_added', '$assignment_due', '', '','','', '$status') ");
            header("Location: Student_DashClasswork.php?classCode=$code&uploadsuccess");
        }
        
        else if($fileName != "")
        {
            if(in_array($fileActualExt, $allowed))
            {
                if($fileError === 0)
                {
                    if($fileSize < 1000000000)
                    {
                        $fileDestination = '../uploads/'.$fileName;
                        move_uploaded_file($fileTmpName,$fileDestination);

                        $sql = mysqli_query($conn, "INSERT INTO student_assignment VALUES('','$assignmentid', '$studentnum', '$filetypename', '$descript', '$code', '$date_added', '$assignment_due','$fileName', '', '$fileDestination', '', '$status')");
                        
                        $message = "Your classwork has been submitted!";
                        header("Location: Student_DashClasswork.php?classCode=$code&uploadsuccess");
                        
                    }
                
                    else{
                        header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
                    }
                }
            
                else{
                    header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
                }
            }
    
            else{
                header("Location: Student_DashClasswork.php?classCode=$code&unsuccess");
            }
    }
    }


    ?>
    <!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - Classwork</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <link rel = "stylesheet" href = "../css/student_dashclass.css">
    
</head>

<body id="body">
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->
    
     <!--SIDE BAR-->
     <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <!--Background Side Bar-->

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
        <div class = "topnavbar" style ="z-index:1;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">


            <div class="topnavbar-right" id="topnav_right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->
            <br><br><br><br>
            <script>
            
            const tabletSize3 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize3) {
            if (tabletSize3.matches) 
            {
                document.getElementById('topnav_right').style.float = "left";
                document.getElementById('topnav_right').style.fontSize = "12px";
                document.getElementById('body').style.fontSize = "12.5px";
                
            } 
            else
            {

                document.getElementById('topnav_right').classList.add('topnavbar-right');
                document.getElementById('topnav_right').style.fontSize = "13.5px";
                document.getElementById('body').style.fontSize = "";
            }
            }

            tabletSize3.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize3);
        </script>

        <?php
        if(isset($_REQUEST['updatesuccess']))
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
                title: 'Classwork submission has been updated.'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['unsuccess']))
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
                icon: 'warning',
                title: 'There was an error uploading your file.'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['uploadsuccess']))
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
                title: 'Classwork submission has been uploaded.'
            })
            </script>
      <?php
        } ?>
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Missing Tasks <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Student_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
            <hr style="background-color:maroon;border-width:2px;">
            &emsp;&emsp; 

    <?php
        $datenow = date("Y-m-d H:i:s");
    $get_assignment = mysqli_query($conn, "SELECT t1.* FROM adviser_assignment t1 WHERE NOT EXISTS (SELECT * FROM student_assignment t2 WHERE t2.assignment_id = t1.assignment_id AND t2.studentnum = '$studentnum') AND t1.class_code = '$code' AND t1.due_date < '$datenow' AND t1.due_date != '0000-00-00 00:00:00'");
    if(mysqli_num_rows($get_assignment) > 0)
    {
        $str = "";
        while($row = mysqli_fetch_array($get_assignment))
        {
            
            $id = $row['assignment_id'];
            $title = $row['title'];
            $name = $row['name'];
            $instruction = $row['instruction'];
            $date_added = $row['date_added'];
            $date_added_display = date("M j Y, H:i:s A", strtotime($row['date_added']));
            $start_date = $row['start_date'];
            $start_date_display =  date("M j Y, H:i:s A", strtotime($row['start_date']));
            $due_date = $row['due_date'];
            $due_date_display =  date("M j Y, H:i:s A", strtotime($row['due_date']));
            $points = $row['points'];
            $file = $row['files'];
            $image = $row['files_destination'];
            $type = $row['type'];
            $fileExt = explode('.', $file);
            $fileActualExt = end($fileExt);
            $allowed  = array('jpg','jpeg','png');
            $fileDiv = "";
            if (in_array($fileActualExt, $allowed)) 
            {
                $fileDiv = "<div id='postedFile'>
                <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;' >
                </div>";
            }
            $formatted_date = date("Y-m-d\Th:i", strtotime($row['due_date']));
            $formatted_date_start = date("Y-m-d\TH:i", strtotime($row['start_date']));

            $checkquery = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$id' AND studentnum = '$studentnum' AND class_code = '$code'");
            $row1 = mysqli_fetch_array($checkquery);
            if(!empty($row1)){
            $duedate = $row1['due_date'];
            $dateadded = $row1['date_added'];
            $status = $row1['status'];
            }
            else
            {   
                $duedate = "";
                $dateadded = "";
                $status = "";
            }
            

            date_default_timezone_set('Asia/Singapore');
            $date_today = date("Y-m-d H:i:s");
   
            if(mysqli_num_rows($checkquery) === 0)
            {

                if($due_date < $date_today)
                {
                   
                  $status = 'Missing';
                  
                }
                
                else if($due_date > $date_today)
                {
                    
                   $status = 'On going';
                 
                }
                else if($due_date === '0000-00-00 00:00:00')
                {
                    $status = 'On going';
                }
            }
                


            ?> <!--end of first php -->
                    
            <script>
                    function toggle<?php echo $id; ?>() {
                        var element = document.getElementById("toggleClass<?php echo $id; ?>");

                        if (element.style.display == "block")
                            element.style.display = "none";
                        else
                            element.style.display = "block";
                        
                    } //end of function   
                </script>
           <?php

$str .= "<br>
<div class='card-body stylebox' style='background-color:white;'>
<div onClick='javascript:toggle$id(); myFunction(this)'>
    <div class='card-body stylebox' style='background-color: maroon;'>

        <div class='row'>
            <div class='col-6'>
                <b style = 'font-weight: 600;color:white;'>Assignment Title: $title </b> 
                <br>
                </div>
            <div class='col-6'>
                <b style = 'font-weight: 600;color:white;'>Type: $type </b>
            </div>
            
        </div>
        <div class='row'>
            <div class='col-6' style='color:white;'>
                ";
                if($start_date === '0000-00-00 00:00:00')
                {
                    $str .="
                
                    Start Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                    ";
                }
                else
                {
                    $str .="
                
                    Start Date: $start_date_display &emsp;
                    ";

                }
                $str .="
            </div>
            <div class='col-6' style='color:white;'>
            ";
            if($due_date === '0000-00-00 00:00:00')
            {
                $str .="
            
                Due Date: None &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; On going
                ";
            }
            else
            {
                $str .="
            
                Due Date: $due_date_display &emsp; $status
                ";

            }
            $str .="
                
                </div>
        </div>
        
    </div>
</div>
      
<div class='card-body display' id='toggleClass$id'>
                                            
";
if($type === 'Graded') 
{
    if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
    { $str .="
        <div class = 'row text'>
            <div class = 'col-4'> Instructor $name  </div>
            <div class = 'col-6'> $date_added_display  </div>
            <div class = 'col-2'>Points: &emsp; /  $points  </div>

            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
            <br><br>
            <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
        
        </div> <br><br>
        ";
    }
        
    else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
    {
        $str .="
            <div class = 'row text'>
                <div class = 'col-4'> Instructor $name  </div>
                <div class = 'col-6'> $date_added_display  </div>
                <div class = 'col-2'>Points: &emsp; /  $points  </div>

                <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                <br><br>
                <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                
            </div> <br><br><hr class='dashed'>"; 
    }
    else
    {
        $str .="
            <div class = 'row text'>
                <div class = 'col-4'> Instructor $name  </div>
                <div class = 'col-6'> $date_added_display  </div>
                <div class = 'col-2'>Points: &emsp; /  $points  </div>

                <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                <br><br>
            </div> <br><br><hr class='dashed'>"; 
    }
} 
else 
{
    if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
    {
        $str .="
        <div class = 'row text'>
            <div class = 'col-4'> Instructor $name  </div>
            <div class = 'col-6'> $date_added_display  </div>
            

            <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
            <br><br>
            <div class = 'col-12' style='border: 1px solid black;'>$fileDiv </div>
        
        </div> <br><br>
        ";
        }
        
        else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
        {
            $str .="
                <div class = 'row text'>
                    <div class = 'col-4'> Instructor $name  </div>
                    <div class = 'col-6'> $date_added_display  </div>

                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                    <br><br>
                    <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                    
                </div> <br><br><hr class='dashed'>"; 
        }
        else
        {
            $str .="
                <div class = 'row text'>
                    <div class = 'col-4'> Instructor $name  </div>
                    <div class = 'col-6'> $date_added_display  </div>

                    <br><br><div class = 'col-12' style='white-space:break-spaces;'>Instructions: <br>&emsp; $instruction  </div>
                    <br><br>
                </div> <br><br><hr class='dashed'>"; 
        }
}


if($formatted_date_start <= $date_today)
{
    $str .= "

    <button type='button' class='filebtn hfilebtn' style='float:right;' data-toggle='modal' data-target='#submitModal$id'>
    + Add or Create
    </button> ";
}
else if($start_date === '0000-00-00 00:00:00')
{
    $str .= "

    <button type='button' class='filebtn hfilebtn' style='float:right;' data-toggle='modal' data-target='#submitModal$id'>
    + Add or Create
    </button> ";
}

$str .= "

<div class='modal fade' id='submitModal$id' tabindex='-1' aria-labelledby='submitModalLabel' aria-hidden='true'>
<div class='modal-dialog modal-lg'>
    <div class='modal-content'>

        <div class='modal-header'>
            <h5 class='modal-title' id='submitModalLabel'>Submit an Assignment</h5>
        </div>

        <form method='POST' enctype='multipart/form-data'>
        <div class='modal-body p-5'>
            

        <div class='md-file' data-tooltip='Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX. Maximum File Size accepted is 50 MB.'>
        <label for='formFile' class='form-label'></label>
        <input class='form-control' type='file' name='file' id='formFile1'>
        <style>

        .md-file:hover:after {
        content:attr(data-tooltip);
        }

        </style>

        </div>

            <br>

            <div class='md-title'>
                <label for='exampleFormControlInput1' name='title' class='form-label'>File Name</label>
                <input type='text' name='name' class='form-control' id='exampleFormControlInput1' placeholder='Title' required>
            </div>

            <br>

            <div class='md-textarea'>
                <label for='exampleFormControlTextarea1' class='form-label'>Description</label>
                <textarea name='desc' class='form-control' id='exampleFormControlTextarea1' placeholder='Instructions' rows='3'></textarea>
            </div>

        </div>

        <div class='modal-footer'>
            <input type='hidden' name='id' value='$id'>
            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
            <button type='submit' name='save' class='btn btn-primary'>Submit</button>
        </div></form>
        
    </div>
</div>
</div>

</div>
</div>";

        }
        echo $str;

    }
    else
    {
        $str = "
        <br>
        <div class='card-deck-6'>
            <div class='card style'>
                <div class='card-body text-center'>
                <br>
                <img alt='Join Class' class = 'img-join' src='../images/class-img.png' style='width:25%;height:25%;'><br><br><br>
                <h5> There's no record for Missing Task.</h5>
                </div>
            </div>
        </div>
        ";
        echo $str;
    }
}//end of missing