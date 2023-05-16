<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../ForgotPass/PHPMailer/src/Exception.php';
require '../ForgotPass/PHPMailer/src/PHPMailer.php';
require '../ForgotPass/PHPMailer/src/SMTP.php';
include "../db_conn.php";
if(!isset($_SESSION['faculty']))
{
    header("Location: Adviser_Login.php?LoginFirst");
}


if(isset($_REQUEST['graded']))
{
    $code = $_REQUEST['classCode'];
    $sql1 = "SELECT * FROM adviser_assignment ORDER BY assignment_id DESC";
    $result = mysqli_query($conn,$sql1);
    $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    if(mysqli_num_rows($section) > 0)
    {
        $get_section = mysqli_fetch_array($section);

        $course = $get_section['course'];
        $year_section = $get_section['year_section'];
    }

    $faculty = $_SESSION['faculty'];
    $name = $_SESSION['name'];
    $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row1 = mysqli_fetch_array($sql);
    date_default_timezone_set('Asia/Singapore');
    $date_today = date("Y-m-d\Th:i");

    if(isset($_REQUEST['save']))
    {
        $id = $_POST['id'];
        $getid = $_POST['get_id'];
        $point = $_POST['point']; 

        $assignmentqeury = mysqli_query($conn, "SELECT * FROM student_assignment WHERE class_code LIKE '$code' AND assignment_id LIKE '$getid'");
        $assignmentrow = mysqli_fetch_array($assignmentqeury);
        $studnum = $assignmentrow['studentnum'];

        $adviserquery = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE facultynum = '$faculty' AND class_code = '$code' AND assignment_id = '$getid' ORDER BY assignment_id DESC");
        $assval = mysqli_fetch_array($adviserquery);
        $maxpoint = $assval['points'];

        if($point > $maxpoint)
        {
            
            header("Location: Adviser_Classwork.php?classCode=$code&maxpoint");
        }
        else{
            $updategrade = mysqli_query($conn,"UPDATE student_assignment set points = '$point', grade = '1', status = 'Returned' where assignment_id = '$getid' AND class_code LIKE '$code' AND studentnum LIKE '$id'");
            $notif_desc = "has returned your classwork.";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Student_DashClasswork.php?classCode=$code";
            $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code' and studentnum LIKE '$id'");
            $getstudentname_row = mysqli_fetch_array($getstudentname);
            $student_name = $getstudentname_row['name'];
            if($getstudentname){
            $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
            header("Location: Adviser_Classwork.php?classCode=$code&gradedsuccess");
        }
        }
    }
    ?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Adviser Dashboard - Classwork</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
    <script src="../ckeditor/ckeditor.js"></script>
    <script src="../ckeditor/build-config.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
                        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel = "stylesheet" href = "../css/adviser_dashclasswork.css">
</head>

<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

          <!--SIDE BAR-->
    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
            <!--Background Side Bar-->

            <br><br>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>

            <h6 style="color: white;font-size:20px;"><b> Adviser </b></h6> <br><p style="text-align:center;font-size:12px;color:white;"><?php echo "$course" . " $year_section"; ?></p><hr style="background-color:white;width:200px;">
            <a href="Adviser_Dashboard.php?classCode=<?php echo $code ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp; Dashboard</a>
            <a href="Adviser_DashHome.php?classCode=<?php echo $code ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments" style="font-size: 1.3em;"></i>&emsp;Discussion</a>
            <a href="Adviser_Calendar.php?classCode=<?php echo $code ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-clock-o" style="font-size: 1.3em;"></i>&emsp; Schedule</a> 
            
            <a href="Adviser_ClassList.php" style="margin-top:80%;">
            <i class = "fa fa-arrow-circle-left fa-1x" style="font-size: 1em;"></i> &nbsp; Back to Class List
            </a> 
                         

        </center>
    </div>

    <!--MAIN CONTENT -->
    <div class = "main">

        <!--TOP NAV-->
        <div class = "topnavbar">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
            <div class="topnavbar-right" id="topnav_right">
            <a target = "_self" href="../Logout.php?Logout=<?php echo $faculty ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->
            <br><br><br><br>
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Graded Task <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Adviser_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
            <hr style="background-color:maroon;border-width:2px;">


            <div id="createAssign">

        <?php
       $query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE type = 'Graded' AND class_code = '$code' AND remove = 'no' ORDER BY assignment_id DESC");

       if(mysqli_num_rows($query) > 0)
       {
           $str = "";
           
           while($row = mysqli_fetch_array($query))
           {
               $id = $row['assignment_id'];
               $title = $row['title'];
               $name = $row['name'];
               $instruction = $row['instruction'];
               $date_added = $row['date_added'];
               $date_added_display = date("M j Y, h:i:s A", strtotime($row['date_added']));
               $start_date = $row['start_date'];
               $start_date_display =  date("M j Y, h:i:s A", strtotime($row['start_date']));
               $due_date = $row['due_date'];
               $due_date_display =  date("M j Y, h:i:s A", strtotime($row['due_date']));
               $type = $row['type'];
               $points = $row['points'];
               $file = $row['files'];
               $image = $row['files_destination'];
               $fileExt = explode('.', $file);
               $fileActualExt = end($fileExt);
               $allowed  = array('jpg','jpeg','png');
               $script = "<script>
                   CKEDITOR.replace('edit_instruction$id');
               </script>";

               $fileDiv = "";
               if (in_array($fileActualExt, $allowed)) 
               {
                   $fileDiv = "<div id='postedFile'>
                   <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;' >
                   </div>";
               }
               $formatted_date = date("Y-m-d\Th:i", strtotime($row['due_date']));
               $formatted_date_start = date("Y-m-d\Th:i", strtotime($row['start_date']));
               ?>
                <script>
                   function toggle<?php echo $id; ?>() {
                       var element = document.getElementById("toggleClass<?php echo $id; ?>");
           
                       if (element.style.display == "block")
                           element.style.display = "none";
                       else
                           element.style.display = "block";
                   }

                   
               </script>
               <?php
               $str .= "<br>
               <div class='card-body stylebox' style='background-color:white;'>
               <div onClick='javascript:toggle$id(); myFunction(this)'>
                   <div class='card-body stylebox' style='background-color:#329C08'>
                       <div class='row'>
                           <div class='col-8'>
                               <b style = 'font-weight: 600; color:white;'>Assignment Title: $title </b>
                           </div>
                           <div class='col-4'>
                               <b style = 'font-weight: 600; color:white;'>Type: $type </b>
                           </div>
                           <div class='col-8' style='color:white;'>
                           ";
                               if($start_date === '0000-00-00 00:00:00')
                               {
                                   $str .="
                               
                                   Start Date: (Edit Start Date)
                                   ";
                               }
                               else
                               {
                                   $str .="
                               
                                   Start Date: $start_date_display 
                                   ";

                               }
                               $str .="
                           </div>
                           <div class='col-4' style='color:white;'>
                               ";
                               if($due_date === '0000-00-00 00:00:00')
                               {
                                   $str .="
                               
                                   Due Date: (Edit Due Date)
                                   ";
                               }
                               else
                               {
                                   $str .="
                               
                                   Due Date: $due_date_display 
                                   ";

                               }
                               $str .="
                               
                               
                           </div>
                       </div>
                   </div>
               </div>
               

               <div class='card-body display' id='toggleClass$id' >
               ";

              
                   if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                   { $str .="
                       <div class = 'row text'>
                           <div class = 'col-4'> Instructor $name  </div>
                           <div class = 'col-6'> $date_added_display  </div>
                           <div class = 'col-2'>Possible points: $points  </div>
       
                           <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div>
                           <br><br><div class = 'col-12 '> $fileDiv  </div>
                       </div> <br><br>
                       ";
                   }
                   else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                   {
                       $str .="<div class = 'row text'>
                                   <div class = 'col-4'> Instructor $name  </div>
                                   <div class = 'col-6'> $date_added_display  </div>  <div class = 'col-2'>Possible points: $points  </div>
                                   <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div> <br>
                                   <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '{$row['files_destination']}'>{$row['files']}</a> </div>
                                   
                               </div> <br><br>"; 
                   }
                   else
                   {
                       $str .="<div class = 'row text'>
                                   <div class = 'col-4'> Instructor $name  </div>
                                   <div class = 'col-6'> $date_added_display  </div>  <div class = 'col-2'>Possible points: $points  </div>
                                   <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div>

                               </div> <br><br>"; 
                   }
        
               $str .="
                                                   
                       <!-- Button trigger modal -->
                       <button type='button' class='btn button hbutton' data-bs-toggle='modal' data-bs-target='#viewModal$id'>
                           View Assignment
                       </button>
                       <button type='button' class='btn button hbutton' style='float:right;' data-bs-toggle='modal' data-bs-target='#editModal$id'>
                           Edit
                       </button>
                       <button type='button' class='btn button hbutton' style='float:right;' data-bs-toggle='modal' data-bs-target='#deleteModal$id'>
                           Delete
                       </button>

                       <div class='modal fade' id='viewModal$id' tabindex='-1' aria-labelledby='viewModalLabel' aria-hidden='true'>
                           <div class='modal-dialog modal-lg' style='max-width:80%;'>
                               <div class='modal-content'>
                                   <div class='modal-header'>
                                       <h5 class='modal-title' id='viewModalLabel'>View Assignment</h5>
                                       
                                   </div>
                                   
                                   <div class='modal-body p-5'>
                                  <form method='POST' enctype='multipart/form-data'>
                                  <button type='button' class='btn button hbutton' style='float:right; font-size:80%;' data-bs-toggle='modal' data-bs-target='#updateModal$id'>
                                       Edit Grades
                                   </button>
                                  <center>
                                 
                                      <div class='form-group col-md-6'>
                                      <label for = ''><b>Input Grade: &emsp;</b></label>
                                      <input type='number' name='point' class = 'form-control' placeholder='0/$points' min='0' max='$points'>
                                      </div>
                                  </center>
                                  <br>
                                   <table cellpadding='0' cellspacing='200px' border='0' class='table' id=''>
                                           <thead>
                                               <tr>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Date Upload</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>File Name</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Description</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Submitted by:</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Assignment</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Status</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Grade</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Select</th>
                                               </tr>
                                           </thead>
                                           
                                           <tbody> ";
                                               $student_query = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$id' AND class_code = '$code' ORDER BY id DESC");
                                               if(mysqli_num_rows($student_query)>0) 
                                               {
                                                   while($tablerow = mysqli_fetch_array($student_query))
                                                   {
                                                       $get_id = $tablerow['id'];
                                                       $assignmentid = $tablerow['assignment_id'];
                                                       $added_by = $tablerow['studentnum'];
                                                       $get_name = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$added_by' AND class_code = '$code'");
                                                       $row = mysqli_fetch_array($get_name);
                                                       $name = $row['name'];
                                                       $notempty = $tablerow['points'];
                                                       $ass_id = $tablerow['id'];
                                                       $path = $tablerow['files_destination'];
                                                       $file = $tablerow['files'];
                                                       $date_display = date("M j Y, H:i:s A", strtotime($tablerow['date_added']));
                                                       $status = $tablerow['status'];
                                                       $fileExt = explode('.', $file);
                                                       $fileActualExt = end($fileExt);
                                                       $allowed  = array('jpg','jpeg','png');
                                                       $fileDiv = "";
                                                       if (in_array($fileActualExt, $allowed)) {
                                                           $fileDiv = "<div id='postedFile'>
                                                               <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen'  style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;' >
                                                                   </div>";
                                                       }
                                                       $file_title = htmlspecialchars($tablerow['file_name'], ENT_QUOTES);
                                                       $displayed_file = substr($tablerow['file_name'],0,50);
                                                       $desc_title = htmlspecialchars($tablerow['description'], ENT_QUOTES);
                                                       $displayed_desc = substr($tablerow['description'],0,50);
                                                      
                                                     
                                                       if($notempty === '0'){
                                                           $str .=
                                                           " 
                                                           <tr>
                                                           
                                                                   <td style='text-align:center;'> {$date_display} </td>
                                                                   <td style='text-align:center;'> <span title='$file_title'> {$displayed_file} </td>
                                                                   <td style='text-align:center;'><span title='$desc_title'> {$displayed_desc} </td>
                                                                   <td style='text-align:center;'> {$name} </td>";
                                                                   if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png"){ 
                                                                       $str.= "
                                                                       <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='$path'>View Assignment</a></td>"; 
                                                                   }
                                                                   else if(substr($tablerow['files_destination'], -4) === ".pdf" || substr($tablerow['files'], -4) === ".pdf" || substr($tablerow['files_destination'], -5) === ".docx" || substr($tablerow['files'], -5) === ".docx"  ||  substr($tablerow['files_destination'], -4) === ".doc" || substr($tablerow['files'], -4) === ".doc"  ||  substr($tablerow['files_destination'], -4) === ".ppt" || substr($tablerow['files'], -4) === ".ppt"  ||  substr($tablerow['files_destination'], -5) === ".pptx" || substr($tablerow['files'], -5) === ".pptx"  ||  substr($tablerow['files_destination'], -4) === ".xls" || substr($tablerow['files'], -4) === ".xls")
                                                                   {
                                                                       $str .= "<td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Assignment</a></td>";
                                                                   }
                                                                   else
                                                                   {
                                                                       $str .="<td></td>";
                                                                   }
                                                                   $str .="
                                                                   <td style='text-align:center;'> {$tablerow['status']} </td>
                                                                   <td style='text-align:center;'>
                                                                  
                                                                    <div class='btn-group' role='group' aria-label='Button Group'>
                                                                       
                                                                           <input type='hidden' name='id' value='$added_by'>
                                                                           <input type='hidden' name='ass_id' value='$assignmentid'>
                                                                           
                                                                           <input type='hidden' name='classCode' value='$code'>

                                                                   </td></div>
                                                                   <td><input type='checkbox' class='checkbox' name='get_id[]' value='$get_id'></td>

                                                           </tr>       
                                                               ";

                                                       }

                                                       else{
                                                           $str .= "
                                                           <tr>
                                                               <td style='text-align:center;'> {$date_display} </td>
                                                               <td style='text-align:center;'> <span title='$file_title'> {$displayed_file} </td>
                                                               <td style='text-align:center;'><span title='$desc_title'> {$displayed_desc} </td>
                                                               <td style='text-align:center;'> {$name} </td>";
                                                               if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png"){
                                                               
                                                                   $str ="
                                                                   <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='$path'>View Assignment</a></td>";" 
                                                                   ";                                                                                                   
                                                               }
                                                               else if(substr($tablerow['files_destination'], -4) === ".pdf" || substr($tablerow['files'], -4) === ".pdf" || substr($tablerow['files_destination'], -5) === ".docx" || substr($tablerow['files'], -5) === ".docx"  ||  substr($tablerow['files_destination'], -4) === ".doc" || substr($tablerow['files'], -4) === ".doc"  ||  substr($tablerow['files_destination'], -4) === ".ppt" || substr($tablerow['files'], -4) === ".ppt"  ||  substr($tablerow['files_destination'], -5) === ".pptx" || substr($tablerow['files'], -5) === ".pptx"  ||  substr($tablerow['files_destination'], -4) === ".xls" || substr($tablerow['files'], -4) === ".xls")
                                                               {
                                                                   $str .= "<td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Assignment</a></td>";
                                                               }
                                                               else
                                                               {
                                                                   $str .="<td></td>";
                                                               }
                                                               $str .= "
                                                                <td style='text-align:center;'> {$tablerow['status']}</td>
                                                                <td style='text-align:center;'> {$tablerow['points']}</td>
                                                               <td> </td>
                                                               </tr>";
                                                               
                                                       }
                                                   }                                    
                                               }
                                               else
                                               {
                                                   $str .="
                                                   <h6><center>There's no post yet.</center></h6> <br>
                                                   <tr>
                                                               <td style='text-align:center;width:15%;'> </td>
                                                               <td style='text-align:center;width:15%;'> </td>
                                                               <td style='text-align:center;width:15%;'> </td>
                                                               <td style='text-align:center;width:15%;'> </td>
                                                               <td style='text-align:center;width:15%;'> </td>
                                                               <td style='text-align:center;width:15%;'> </td>
                                                               <td style='text-align:center;width:15%;'> </td>
                                                               <td style='text-align:center;width:15%;'> </td>
                                                   </tr>
                                                   ";
                                               }

                                           $str .= "
                                           </tbody>
                                       </table>
                                   </div> <!--End of modal-body --> 
                                   
                                   <div class='modal-footer'>
                                           <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                           <button name='save' class='btn btn-primary' style='background-color:maroon;' id='btn_s'>Save</button>
                                       </div>
                                   
                               </div> <!--End of modal-content -->
                               </form>
                           </div> <!--End of modal-dialog --> 
                       </div> <!--End of view modal -->

                       <div class='modal fade' id='updateModal$id' tabindex='-1' aria-labelledby='updateModalLabel' aria-hidden='true'>
                           <div class='modal-dialog modal-lg' style='max-width:80%;'>
                               <div class='modal-content'>  
                               <div class='modal-header'>
                                   <h5 class='modal-title' id='viewModalLabel'>View Assignment</h5>
                                   <br>
                               </div>

                                   <form method='POST' enctype='multipart/form-data'>
                                       <div class='modal-body p-5'>
                                       <center>
                                       
                                           <label for = ''><b>Input Grade: &emsp;</b></label>
                                           <input type='number' name='point' class = 'form-control' placeholder='0/$points' min='0' max='$points'>
                                       </center>
                                       <br><br>
                                       <table cellpadding='0' cellspacing='200px' border='0' class='table' id=''>
                                           <thead>
                                               <tr>
                                                   <th style='padding:8px; width:15%; text-align:center;'>Date Upload</th>
                                                   <th style='padding:8px; width:15%; text-align:center;'>File Name</th>
                                                   <th style='padding:8px; width:15%; text-align:center;''>Description</th>
                                                   <th style='padding:8px; width:15%; text-align:center;''>Submitted by:</th>
                                                   <th style='padding:8px; width:15%; text-align:center;''>Assignment</th>
                                                   <th style='padding:8px; width:15%; text-align:center;''>Status</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Grade</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Select</th>
                                                   
                                               </tr>
                                           </thead>
                                               <tbody>";
                                                   $student_query = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$id' AND class_code = '$code' ORDER BY id DESC");
                                                   if(mysqli_num_rows($student_query)>0) 
                                                   {
                                                       while($tablerow = mysqli_fetch_array($student_query))
                                                       {
                                                           $get_id_update = $tablerow['id'];
                                                           $assignmentid = $tablerow['assignment_id'];
                                                           $added_by = $tablerow['studentnum'];
                                                           $get_name = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$added_by' AND class_code = '$code'");
                                                           $row = mysqli_fetch_array($get_name);
                                                           $name = $row['name'];
                                                           $notempty = $tablerow['points'];
                                                           $ass_id = $tablerow['id'];
                                                           $path = $tablerow['files_destination'];
                                                           $date_display = date("M j Y, H:i:s A", strtotime($tablerow['date_added']));
                                                           $file = $tablerow['files'];
                                                           $status = $tablerow['status'];
                                                           $fileExt = explode('.', $file);
                                                           $fileActualExt = end($fileExt);
                                                           $allowed  = array('jpg','jpeg','png');
                                                           $fileDiv = "";
                                                           
                                                           if (in_array($fileActualExt, $allowed)) {
                                                               $fileDiv = "<div id='postedFile'>
                                                                   <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen'  style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;'>
                                                                       </div>";
                                                           }
                                                           $str .="
                                                           <tr>
                                                               <td style='text-align:center;'> {$date_display} </td>
                                                               <td style='text-align:center;'> {$tablerow['file_name']} </td>
                                                               <td style='text-align:center;'> {$tablerow['description']} </td>
                                                               <td style='text-align:center;'> {$name} </td>";
                                                               if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png"){ $str.= "
                                                                   <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='$path'>View Assignment</i></a> </td>"; 
                                                               }
                                                               else if(substr($tablerow['files_destination'], -4) === ".pdf" || substr($tablerow['files'], -4) === ".pdf" || substr($tablerow['files_destination'], -5) === ".docx" || substr($tablerow['files'], -5) === ".docx"  ||  substr($tablerow['files_destination'], -4) === ".doc" || substr($tablerow['files'], -4) === ".doc"  ||  substr($tablerow['files_destination'], -4) === ".ppt" || substr($tablerow['files'], -4) === ".ppt"  ||  substr($tablerow['files_destination'], -5) === ".pptx" || substr($tablerow['files'], -5) === ".pptx"  ||  substr($tablerow['files_destination'], -4) === ".xls" || substr($tablerow['files'], -4) === ".xls")
                                                               {
                                                                   $str .= "<td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Assignment</i></a></td>";
                                                               }
                                                               else
                                                               {
                                                                   $str .="<td style='text-align:center;'></td>";
                                                               }
                                                               $str .="
                                                               <td style='text-align:center;'> {$tablerow['status']} </td>
                                                               <td style='text-align:center;'>
                                                                   
                                                                   <div class='btn-group' role='group' aria-label='Button Group'>
                                                                       <input type='hidden' name='id' value='$added_by'>
                                                                       <input type='hidden' name='ass_id' value='$assignmentid'>
                                                                       
                                                                       <input type='hidden' name='classCode' value='$code'>
                                                                       
                                                                       
                                                                          $notempty
                                                                       
                                                                        
                                                                       
                                                                   </div>
                                                               </td>
                                                               <td style='text-align:center;'>";
                                                               if($type === "Graded")
                                                                       {
                                                                           $str .="<input type='checkbox' class='checkbox' name='get_id[]' value='$get_id'>";
                                                                       }
                                                                       $str.="</td>
                                                           </tr>

                                                           ";
                                                   }
                                               }
                                               $str .="
                                               </tbody>
                                           </table>

                                       </div>
                                   
                                   <div class='modal-footer'>
                                   <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                   <button name='save' class='btn btn-primary' style='background-color:maroon;' id='btn_s'>Save</button>
                               </div>
                               </form>
                               </div>       <!--End of modal-content -->                 
                           </div><!--End of modal-dialog --> 
                       </div><!--End of update modal -->

                       <!-- Modal DELETE DATA-->
                       <div class='modal fade' id='deleteModal$id' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
                           <div class='modal-dialog modal-lg' role='document'>
                               <div class='modal-content'>
                                   <div class='modal-header'>
                                       <h5 class='modal-title' id = 'deleteModalLabel'><b>CONFIRMATION</b></h5>
                                       
                                   </div>
                                       <form action = 'Adviser_Assignment.php?class=$code' method = 'POST'>
                                       <div class='modal-body'>
                                       <input type='hidden' name='get_id' value='$id'>
                                           <h4> Do you want to remove this Activity? </h4>
                                       </div>
                                       <div class='modal-footer'>
                                           <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                       <button type='submit' name = 'delete' class='btn btn-danger'>Confirm</button>
                                       </div>
                                       </form>
                               </div>
                           </div>
                       </div>

                       <div class='modal fade' id='editModal$id' style='margin-left:5%;' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>
                           <div class='modal-dialog modal-lg' id='edit_ass'>
                               <div class='modal-content'>

                               <!--modal header-->
                               <div class='modal-header'>
                                   <h5 class='modal-title' id='exampleModalLabel'>Edit an Assignment</h5>
                                       
                               </div>
           
                               <!--modal body-->
                               <form action='Adviser_Assignment.php?class=$code' method='POST' enctype='multipart/form-data'>
                                   <div class='modal-body p-5'>
           
                                       <!--this is where the text areas are going to be placed-->
                                       <div class='md-title'>
                                           <label for='exampleFormControlInput1' name='title' class='form-label'>Title</label>
                                           <input type='text' name='title' class='form-control' id='exampleFormControlInput1' placeholder='$title' value='$title'>
                                       </div>
                                       <br>
           
                                       <div class='md-textarea'>
                                           <label for='exampleFormControlTextarea1' class='form-label'>Instructions</label>
                                           <textarea name='instruction' class='form-control' id='edit_instruction$id' rows='3' required>$instruction</textarea>
                                       </div>

                                       $script

                                       <div class='md-file' data-tooltip='Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX. Maximum File Size accepted is 50 MB.'>
                                           <label for='formFile' class='form-labe'></label>
                                           <input class='form-control' type='file' name='file' id='formFile'>

                                           <style>
                                   
                                               .md-file:hover:after {
                                               content:attr(data-tooltip);
                                               }
                           
                                               </style>
                                       </div>
                                       <br>

                                       <style type='text/css'>
                                       .d-none{
                                           display: none;
                                               }
                                       </style>
           
                                       <h8>Type of Assignment</h8>";
                                       $radiobutton = "
                                       <script type='text/javascript'>
                                           function enableGRDedit$id(answer) {console.log(answer.value);
                                               if(answer.value == 'Graded') {
                                                   document.getElementById('edit_option').classList.remove('d-none');
                                               } else if(answer.value == 'Not Graded') {
                                                   document.getElementById('edit_option').classList.remove('d-none');
                                               }
                                               else {
                                                   document.getElementById('edit_option').classList.add('d-none');
                                               }
                                           }
                                       </script> ";
                                           
                                   $str .=" 
                                   $radiobutton
                                       
                                       <div id ='graded' class='form-check'>
                                       <form>
                                       <label>
                                           <input type='radio' name='option' value='Graded'"; if($type == "Graded"){ $str .= "checked"; } $str .=" onchange = 'enableGRDedit$id(this)' required> Graded
                                       </label>
                                       
                                       </div>
                                       <div id='notgraded' class='form-check' onchange = 'enableGRDedit$id(this)'>
                                       <label>
                                           <input type='radio' name='option' value='Not Graded' "; if($type == "Not Graded"){ $str .= "checked"; } $str .=" > Not Graded
                                       </label>    
                                       </div>
           
                                       <div id='edit_option' class='md-grade'>
                                           <label for='grd' class='form-label'>Points</label>
                                           <input class='form-control form-control-sm' type='text' name='points' placeholder='$points' value='$points' aria-label='.form-control-sm example'>
                                       </div>
                                       <br>";

                                       if($start_date === '0000-00-00 00:00:00' || $due_date === '0000-00-00 00:00:00')
                                       {
                                           $str .="
                                           <div class = 'form-row'>
                                           <div class='form-group col-md-6'>
                                               <div class='md-grade'>
                                                   <label for='grd' class='form-label'>Start Date</label>
                                                   <input class='form-control form-control-sm' id='start_date' type='datetime-local' name='start_date' value='$start_date' aria-label='.form-control-sm example'>
                                               </div>
                                           </div>
                                           <div class='form-group col-md-6'>
                                               <div class='md-grade'>
                                                   <label for='grd' class='form-label'>Due Date</label>
                                                   <input class='form-control form-control-sm' id='end_date' type='datetime-local' name='due_date' value='$due_date' min='$start_date' aria-label='.form-control-sm example'>
                                               </div>
                                           </div>"; ?>
                                           <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                           <script>
                                               
                                           $(document).ready(function() {
                                           $('#start_date').change(function() {
                                               $('#end_date').prop('disabled', false);
                                               $('#end_date').prop('min', $('#start_date').val());
                                           });
                                           });
                                           </script>
                                           <?php
                                           $str .="
                                           </div>
                                           <br>
                                           ";
                                       }
                                       else
                                       {
                                           $str .="
                                           <div class = 'form-row'>
                                       <div class='form-group col-md-6'>
                                           <div class='md-grade'>
                                               <label for='grd' class='form-label'>Start Date</label>
                                               <input class='form-control form-control-sm' id='grd' type='datetime-local' name='start_date' value='$formatted_date_start' aria-label='.form-control-sm example'>
                                           </div>
                                       </div>
                                       <div class='form-group col-md-6'>
                                           <div class='md-grade'>
                                               <label for='grd' class='form-label'>Due Date</label>
                                               <input class='form-control form-control-sm' id='grd' type='datetime-local' name='due_date' value='$formatted_date' min='$formatted_date_start' aria-label='.form-control-sm example'>
                                           </div>
                                       </div>
                                       "; ?>
                                           <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                           <script>
                                           $(document).ready(function() {
                                           $('#start_date').change(function() {
                                               $('#end_date').prop('disabled', false);
                                               $('#end_date').prop('min', $('#start_date').val());
                                           });
                                           });
                                           </script>
                                           <?php
                                           $str .="
                                       </div>
                                       <br>
                                           ";
                                       }
           
                                       $str .="

                                       <input type='hidden' name='get_id' value='$id'>
           
                                   </div> <!--end of modal-body-->
           
                                   <!--modal footer-->
                                   <div class='modal-footer'>
                                       <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                       <button type='submit' name='edit' class='btn btn-primary'>Submit</button>
                                   </div>
                                   </form>
                           </div>
                       </div>
               </div>
               
                   </div> <!--End of toggle -->
               </div> <!--End of Card -->";
           }
           echo $str;
       }else{
           $str = "
           <br>
           <div class='card-deck'>
               <div class='card style'>
                   <div class='card-body text-center'>
                   <br>
                   <h5> There's no classworks yet!</h5>
                   </div>
               </div>
           </div>
           ";
           echo $str;
       }
       ?>
       </div>
</div> 
<script>
            
            const tabletSize6 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize6) {
            if (tabletSize6.matches) 
            {
                document.getElementById('topnav_right').style.fontSize = "12px";
                document.getElementById('topnav_right').style.float = "left";
                document.getElementById('assignment').style.maxWidth = "80%";
                document.getElementById('edit_ass').style.maxWidth = "80%";
                document.getElementById('view_ass1').style.maxWidth = "98%";
                document.getElementById('view_ass2').style.maxWidth = "100%";
                document.getElementById('update_ass1').style.maxWidth = "98%";
                document.getElementById('update_ass2').style.maxWidth = "100%";
                document.getElementById('trash_ass').style.maxWidth = "80%";
                
            } 
            else
            {

                document.getElementById('topnav_right').style.fontSize = "13.5px";
                document.getElementById('topnav_right').style.float = "right";
                document.getElementById('assignment').style.maxWidth = "";
                document.getElementById('edit_ass').style.maxWidth = "";
                document.getElementById('view_ass1').style.maxWidth = "80%";
                document.getElementById('view_ass2').style.maxWidth = "80%";
                document.getElementById('update_ass1').style.maxWidth = "80%";
                document.getElementById('update_ass2').style.maxWidth = "80%";
                document.getElementById('trash_ass').style.maxWidth = "";
            }
            }

            tabletSize6.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize6);
        </script>
        </body>
</html>
       <?php
           
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////end of graded

if(isset($_REQUEST['ungraded']))
{
    $code = $_REQUEST['classCode'];
    $sql1 = "SELECT * FROM adviser_assignment ORDER BY assignment_id DESC";
    $result = mysqli_query($conn,$sql1);
    $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
if(mysqli_num_rows($section) > 0)
{
    $get_section = mysqli_fetch_array($section);

    $course = $get_section['course'];
    $year_section = $get_section['year_section'];
}

    $faculty = $_SESSION['faculty'];
    $name = $_SESSION['name'];
    $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row1 = mysqli_fetch_array($sql);
    date_default_timezone_set('Asia/Singapore');
    $date_today = date("Y-m-d\Th:i");

    if(isset($_REQUEST['save']))
    {
        $id = $_POST['id'];
        $getid = $_POST['get_id'];
        $point = $_POST['point']; 

        $assignmentqeury = mysqli_query($conn, "SELECT * FROM student_assignment WHERE class_code LIKE '$code' AND assignment_id LIKE '$getid'");
        $assignmentrow = mysqli_fetch_array($assignmentqeury);
        $studnum = $assignmentrow['studentnum'];

        $adviserquery = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE facultynum = '$faculty' AND class_code = '$code' AND assignment_id = '$getid' ORDER BY assignment_id DESC");
        $assval = mysqli_fetch_array($adviserquery);
        $maxpoint = $assval['points'];

        if($point > $maxpoint)
        {
            
            header("Location: Adviser_Classwork.php?classCode=$code&maxpoint");
        }
        else{
            $updategrade = mysqli_query($conn,"UPDATE student_assignment set points = '$point', grade = '1', status = 'Returned' where assignment_id = '$getid' AND class_code LIKE '$code' AND studentnum LIKE '$id'");
            $notif_desc = "has returned your classwork.";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Student_DashClasswork.php?classCode=$code";
            $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code' and studentnum LIKE '$id'");
            $getstudentname_row = mysqli_fetch_array($getstudentname);
            $student_name = $getstudentname_row['name'];
            if($getstudentname){
            $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
            header("Location: Adviser_Classwork.php?classCode=$code&gradedsuccess");
        }
        }
    }
    ?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Adviser Dashboard - Classwork</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
    <script src="../ckeditor/ckeditor.js"></script>
    <script src="../ckeditor/build-config.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
                        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel = "stylesheet" href = "../css/adviser_dashclasswork.css">
</head>

<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

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
        <div class = "topnavbar">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
            <div class="topnavbar-right" id="topnav_right">
            <a target = "_self" href="../Logout.php?Logout=<?php echo $faculty ?>" > 
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
                    document.getElementById('topnav_right').classList.remove('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "12px";
                } 
                else
                {
                    document.getElementById('topnav_right').classList.add('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "13.5px";
                }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Ungraded Task <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Adviser_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
            <hr style="background-color:maroon;border-width:2px;">


            <div id="createAssign">

        <?php
        $query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE type = 'Not Graded' AND class_code = '$code' AND remove = 'no' ORDER BY assignment_id DESC");

        if(mysqli_num_rows($query) > 0)
        {
            $str = "";
            
            while($row = mysqli_fetch_array($query))
            {
                $id = $row['assignment_id'];
                $title = $row['title'];
                $name = $row['name'];
                $instruction = $row['instruction'];
                $date_added = $row['date_added'];
                $date_added_display = date("M j Y, h:i:s A", strtotime($row['date_added']));
                $start_date = $row['start_date'];
                $start_date_display =  date("M j Y, h:i:s A", strtotime($row['start_date']));
                $due_date = $row['due_date'];
                $due_date_display =  date("M j Y, h:i:s A", strtotime($row['due_date']));
                $type = $row['type'];
                $points = $row['points'];
                $file = $row['files'];
                $image = $row['files_destination'];
                $fileExt = explode('.', $file);
                $fileActualExt = end($fileExt);
                $allowed  = array('jpg','jpeg','png');
                $script = "<script>
                    CKEDITOR.replace('edit_instruction$id');
                </script>";

                $fileDiv = "";
                if (in_array($fileActualExt, $allowed)) 
                {
                    $fileDiv = "<div id='postedFile'>
                    <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;' >
                    </div>";
                }
                $formatted_date = date("Y-m-d\Th:i", strtotime($row['due_date']));
                $formatted_date_start = date("Y-m-d\Th:i", strtotime($row['start_date']));
                ?>
                 <script>
                    function toggle<?php echo $id; ?>() {
                        var element = document.getElementById("toggleClass<?php echo $id; ?>");
            
                        if (element.style.display == "block")
                            element.style.display = "none";
                        else
                            element.style.display = "block";
                    }

                    
                </script>
                <?php
                $str .= "<br>
                <div class='card-body stylebox' style='background-color:white;'>
                <div onClick='javascript:toggle$id(); myFunction(this)'>
                    <div class='card-body stylebox' style='background-color: maroon'>
                        <div class='row'>
                            <div class='col-8'>
                                <b style = 'font-weight: 600; color:white;'>Assignment Title: $title </b>
                            </div>
                            <div class='col-4'>
                                <b style = 'font-weight: 600; color:white;'>Type: $type </b>
                            </div>
                            <div class='col-8' style='color:white;'>
                            ";
                                if($start_date === '0000-00-00 00:00:00')
                                {
                                    $str .="
                                
                                    Start Date: (Edit Start Date)
                                    ";
                                }
                                else
                                {
                                    $str .="
                                
                                    Start Date: $start_date_display 
                                    ";

                                }
                                $str .="
                            </div>
                            <div class='col-4' style='color:white;'>
                                ";
                                if($due_date === '0000-00-00 00:00:00')
                                {
                                    $str .="
                                
                                    Due Date: (Edit Due Date)
                                    ";
                                }
                                else
                                {
                                    $str .="
                                
                                    Due Date: $due_date_display 
                                    ";

                                }
                                $str .="
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class='card-body display' id='toggleClass$id' >
                ";

                if($type === 'Graded')
                {
                    if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                    { $str .="
                        <div class = 'row text'>
                            <div class = 'col-4'> Instructor $name  </div>
                            <div class = 'col-6'> $date_added_display  </div>
                            <div class = 'col-2'>Possible points: $points  </div>
        
                            <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div>
                            <br><br><div class = 'col-12 '> $fileDiv  </div>
                        </div> <br><br>
                        ";
                    }
                    else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                    {
                        $str .="<div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>  <div class = 'col-2'>Possible points: $points  </div>
                                    <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div> <br>
                                    <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '{$row['files_destination']}'>{$row['files']}</a> </div>
                                    
                                </div> <br><br>"; 
                    }
                    else
                    {
                        $str .="<div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>  <div class = 'col-2'>Possible points: $points  </div>
                                    <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div>

                                </div> <br><br>"; 
                    }
                }
                else if($type === 'Not Graded')
                {
                    if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                    { $str .="
                        <div class = 'row text'>
                            <div class = 'col-4'> Instructor $name  </div>
                            <div class = 'col-6'> $date_added_display  </div>
                            <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div>
                            <br><br><div class = 'col-12 '> $fileDiv  </div>
                        </div> <br><br>
                        ";
                    }
                    else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                    {
                        $str .="<div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>  
                                    <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div> <br>
                                    <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '{$row['files_destination']}'>{$row['files']}</a> </div>
                                    
                                </div> <br><br>"; 
                    }
                    else
                    {
                        $str .="<div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div> 
                                    <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div>

                                </div> <br><br>"; 
                    }
                }
                $notes = "Type 0 when students need to resubmit;<br> Type 1 when student's classwork is accepted";

                $str .="
                                                    
                        <!-- Button trigger modal -->
                        <button type='button' class='btn button hbutton' data-bs-toggle='modal' data-bs-target='#viewModal$id'>
                            View Assignment
                        </button>
                        <button type='button' class='btn button hbutton' style='float:right;' data-bs-toggle='modal' data-bs-target='#editModal$id'>
                            Edit
                        </button>
                        <button type='button' class='btn button hbutton' style='float:right;' data-bs-toggle='modal' data-bs-target='#deleteModal$id'>
                            Delete
                        </button>

                        <div class='modal fade' id='viewModal$id' tabindex='-1' aria-labelledby='viewModalLabel' aria-hidden='true'>
                            <div class='modal-dialog modal-lg' style='max-width:80%;' >
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='viewModalLabel'>View Assignment</h5>
                                        
                                    </div>
                                    
                                    <div class='modal-body p-5'>
                                   <form method='POST' enctype='multipart/form-data'>
                                   <button type='button' class='btn button hbutton' style='float:right; font-size:80%;' data-bs-toggle='modal' data-bs-target='#updateModal$id'>
                                        Edit Grades
                                    </button>
                                   <center>
                                   ";
                                   if($type === "Not Graded")
                                   {
                                       $str .="
                                       <div class='form-group col-md-6'>
                                       <label for = ''><b>Select Input: &emsp;</b></label>
                                       <select name='point' class = 'form-control' />
                                       <option value = ''>--Select--</option> 
                                       <option value = '0'>Need to Resubmit</option> 
                                       <option value = '1'>Accepted</option> 
                                       </select>
                                       </div>
                                       ";
                                   }
                                   else
                                   {
                                       $str .="
                                       <div class='form-group col-md-6'>
                                       <label for = ''><b>Input Grade: &emsp;</b></label>
                                       <input type='number' name='point' class = 'form-control' placeholder='0/$points' min='0' max='$points'>
                                       </div>";
                                        
                                    }
                                   $str .="
                                   </center>
                                   <br>
                                    <table cellpadding='0' cellspacing='200px' border='0' class='table' id=''>
                                            <thead>
                                                <tr>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Date Upload</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>File Name</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Description</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Submitted by:</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Assignment</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Status</th>";
                                                    if($type === "Not Graded")
                                                    {
                                                        $str .= "
                                                        
                                                        <th style='padding:8px; width:15%; text-align:center;'>Select</th>";
                                                    }
                                                    else
                                                    {
                                                        $str .= "
                                                        <th style='padding:8px; width:15%; text-align:center;'>Grade</th>
                                                        <th style='padding:8px; width:15%; text-align:center;'>Select</th>";
                                                    }
                                                    $str .="
                                                </tr>
                                            </thead>
                                            
                                            <tbody> ";
                                                $student_query = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$id' AND class_code = '$code' ORDER BY id DESC");
                                                if(mysqli_num_rows($student_query)>0) 
                                                {
                                                    while($tablerow = mysqli_fetch_array($student_query))
                                                    {
                                                        $get_id = $tablerow['id'];
                                                        $assignmentid = $tablerow['assignment_id'];
                                                        $added_by = $tablerow['studentnum'];
                                                        $get_name = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$added_by' AND class_code = '$code'");
                                                        $row = mysqli_fetch_array($get_name);
                                                        $name = $row['name'];
                                                        $notempty = $tablerow['points'];
                                                        $ass_id = $tablerow['id'];
                                                        $path = $tablerow['files_destination'];
                                                        $file = $tablerow['files'];
                                                        $date_display = date("M j Y, H:i:s A", strtotime($tablerow['date_added']));
                                                        $status = $tablerow['status'];
                                                        $fileExt = explode('.', $file);
                                                        $fileActualExt = end($fileExt);
                                                        $allowed  = array('jpg','jpeg','png');
                                                        $fileDiv = "";
                                                        if (in_array($fileActualExt, $allowed)) {
                                                            $fileDiv = "<div id='postedFile'>
                                                                <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen'  style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;' >
                                                                    </div>";
                                                        }
                                                        $file_title = htmlspecialchars($tablerow['file_name'], ENT_QUOTES);
                                                        $displayed_file = substr($tablerow['file_name'],0,50);
                                                        $desc_title = htmlspecialchars($tablerow['description'], ENT_QUOTES);
                                                        $displayed_desc = substr($tablerow['description'],0,50);
                                                       
                                                      
                                                        if($notempty === '0'){
                                                            $str .=
                                                            " 
                                                            <tr>
                                                            
                                                                    <td style='text-align:center;'> {$date_display} </td>
                                                                    <td style='text-align:center;'> <span title='$file_title'> {$displayed_file} </td>
                                                                    <td style='text-align:center;'><span title='$desc_title'> {$displayed_desc} </td>
                                                                    <td style='text-align:center;'> {$name} </td>";
                                                                    if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png"){ 
                                                                        $str.= "
                                                                        <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='$path'>View Assignment</a></td>"; 
                                                                    }
                                                                    else if(substr($tablerow['files_destination'], -4) === ".pdf" || substr($tablerow['files'], -4) === ".pdf" || substr($tablerow['files_destination'], -5) === ".docx" || substr($tablerow['files'], -5) === ".docx"  ||  substr($tablerow['files_destination'], -4) === ".doc" || substr($tablerow['files'], -4) === ".doc"  ||  substr($tablerow['files_destination'], -4) === ".ppt" || substr($tablerow['files'], -4) === ".ppt"  ||  substr($tablerow['files_destination'], -5) === ".pptx" || substr($tablerow['files'], -5) === ".pptx"  ||  substr($tablerow['files_destination'], -4) === ".xls" || substr($tablerow['files'], -4) === ".xls")
                                                                    {
                                                                        $str .= "<td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Assignment</a></td>";
                                                                    }
                                                                    else
                                                                    {
                                                                        $str .="<td></td>";
                                                                    }
                                                                    $str .="
                                                                    <td style='text-align:center;'> {$tablerow['status']} </td>
                                                                    <td style='text-align:center;'>
                                                                   
                                                                     <div class='btn-group' role='group' aria-label='Button Group'>
                                                                        
                                                                            <input type='hidden' name='id' value='$added_by'>
                                                                            <input type='hidden' name='ass_id' value='$assignmentid'>
                                                                            
                                                                            <input type='hidden' name='classCode' value='$code'>";

                                                                            if($type === "Not Graded")
                                                                            {
                                                                                $str .="<input type='checkbox' class='checkbox' name='get_id[]' value='$get_id'>";
                                                                            }
                                                                            else
                                                                            {
                                                                                $str .="";
                                                                            }

                                                                            $str .="

                                                                    </td></div>
                                                                    <td>";
                                                                    if($type === "Graded")
                                                                            {
                                                                                $str .="<input type='checkbox' class='checkbox' name='get_id[]' value='$get_id'>";
                                                                            }
                                                                            $str.="</td>

                                                            </tr>       
                                                                ";

                                                        }

                                                        else{
                                                            $str .= "
                                                            <tr>
                                                                <td style='text-align:center;'> {$date_display} </td>
                                                                <td style='text-align:center;'> <span title='$file_title'> {$displayed_file} </td>
                                                                <td style='text-align:center;'><span title='$desc_title'> {$displayed_desc} </td>
                                                                <td style='text-align:center;'> {$name} </td>";
                                                                if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png"){
                                                                
                                                                    $str ="
                                                                    <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='$path'>View Assignment</a></td>";" 
                                                                    ";                                                                                                   
                                                                }
                                                                else if(substr($tablerow['files_destination'], -4) === ".pdf" || substr($tablerow['files'], -4) === ".pdf" || substr($tablerow['files_destination'], -5) === ".docx" || substr($tablerow['files'], -5) === ".docx"  ||  substr($tablerow['files_destination'], -4) === ".doc" || substr($tablerow['files'], -4) === ".doc"  ||  substr($tablerow['files_destination'], -4) === ".ppt" || substr($tablerow['files'], -4) === ".ppt"  ||  substr($tablerow['files_destination'], -5) === ".pptx" || substr($tablerow['files'], -5) === ".pptx"  ||  substr($tablerow['files_destination'], -4) === ".xls" || substr($tablerow['files'], -4) === ".xls")
                                                                {
                                                                    $str .= "<td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Assignment</a></td>";
                                                                }
                                                                else
                                                                {
                                                                    $str .="<td></td>";
                                                                }
                                                                $str .= "
                                                                <td style='text-align:center;'> {$tablerow['status']}</td>";
                                                                if($type === "Not Graded")
                                                                {
                                                                    $str .="<td></td>";
                                                                }
                                                                else
                                                                {
                                                                    $str .=" <td style='text-align:center;'> {$tablerow['points']}</td>";
                                                                }
                                                                $str .= "
                                                                <td> </td>
                                                                </tr>";
                                                                
                                                        }
                                                    }                                    
                                                }
                                                else
                                                {
                                                    $str .="
                                                    <h6><center>There's no post yet.</center></h6> <br>
                                                    <tr>
                                                                <td style='text-align:center;width:15%;'> </td>
                                                                <td style='text-align:center;width:30%;'> </td>
                                                                <td style='text-align:center;width:15%;'> </td>
                                                                <td style='text-align:center;width:30%;'> </td>
                                                                <td style='text-align:center;width:15%;'> </td>
                                                    </tr>
                                                    ";
                                                }

                                            $str .= "
                                            </tbody>
                                        </table>
                                    </div> <!--End of modal-body --> 
                                    
                                    <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                            <button name='save' class='btn btn-primary' style='background-color:maroon;' id='btn_s'>Save</button>
                                        </div>
                                    
                                </div> <!--End of modal-content -->
                                </form>
                            </div> <!--End of modal-dialog --> 
                        </div> <!--End of view modal -->

                        <div class='modal fade' id='updateModal$id' tabindex='-1' aria-labelledby='updateModalLabel' aria-hidden='true'>
                            <div class='modal-dialog modal-lg' style='max-width:80%;'>
                                <div class='modal-content'>  
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='viewModalLabel'>View Assignment</h5>
                                    <br>
                                </div>

                                    <form method='POST' enctype='multipart/form-data'>
                                        <div class='modal-body p-5'>
                                        <center>
                                        ";
                                        if($type === "Not Graded")
                                        {
                                            $str .="
                                            <label for = ''><b>Select Input: &emsp;</b></label>
                                            <select name='point' class = 'form-control' />
                                            <option value = ''>--Select--</option> 
                                            <option value = '0'>Need to Resubmit</option> 
                                            <option value = '1'>Accepted</option> 
                                            </select>
                                            ";
                                        }
                                        else
                                        {
                                            $str .="
                                            <label for = ''><b>Input Grade: &emsp;</b></label>
                                            <input type='number' name='point' class = 'form-control' placeholder='0/$points' min='0' max='$points'>";
                                        }
                                        $str .="
                                        </center>
                                        <br><br>
                                        <table cellpadding='0' cellspacing='200px' border='0' class='table' id=''>
                                            <thead>
                                                <tr>
                                                    <th style='padding:8px; width:15%; text-align:center;'>Date Upload</th>
                                                    <th style='padding:8px; width:15%; text-align:center;'>File Name</th>
                                                    <th style='padding:8px; width:15%; text-align:center;''>Description</th>
                                                    <th style='padding:8px; width:15%; text-align:center;''>Submitted by:</th>
                                                    <th style='padding:8px; width:15%; text-align:center;''>Assignment</th>
                                                    <th style='padding:8px; width:15%; text-align:center;''>Status</th>";
                                                    if($type === "Not Graded")
                                                    {
                                                        $str .= "
                                                        
                                                        <th style='padding:8px; width:15%; text-align:center;'>Select</th>";
                                                    }
                                                    else
                                                    {
                                                        $str .= "
                                                        <th style='padding:8px; width:15%; text-align:center;'>Grade</th>
                                                        <th style='padding:8px; width:15%; text-align:center;'>Select</th>";
                                                    }
                                                    $str .="
                                                    
                                                </tr>
                                            </thead>
                                                <tbody>";
                                                    $student_query = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$id' AND class_code = '$code' ORDER BY id DESC");
                                                    if(mysqli_num_rows($student_query)>0) 
                                                    {
                                                        while($tablerow = mysqli_fetch_array($student_query))
                                                        {
                                                            $get_id_update = $tablerow['id'];
                                                            $assignmentid = $tablerow['assignment_id'];
                                                            $added_by = $tablerow['studentnum'];
                                                            $get_name = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$added_by' AND class_code = '$code'");
                                                            $row = mysqli_fetch_array($get_name);
                                                            $name = $row['name'];
                                                            $notempty = $tablerow['points'];
                                                            $ass_id = $tablerow['id'];
                                                            $path = $tablerow['files_destination'];
                                                            $date_display = date("M j Y, H:i:s A", strtotime($tablerow['date_added']));
                                                            $file = $tablerow['files'];
                                                            $status = $tablerow['status'];
                                                            $fileExt = explode('.', $file);
                                                            $fileActualExt = end($fileExt);
                                                            $allowed  = array('jpg','jpeg','png');
                                                            $fileDiv = "";
                                                            
                                                            if (in_array($fileActualExt, $allowed)) {
                                                                $fileDiv = "<div id='postedFile'>
                                                                    <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen'  style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;'>
                                                                        </div>";
                                                            }
                                                            $str .="
                                                            <tr>
                                                                <td style='text-align:center;'> {$date_display} </td>
                                                                <td style='text-align:center;'> {$tablerow['file_name']} </td>
                                                                <td style='text-align:center;'> {$tablerow['description']} </td>
                                                                <td style='text-align:center;'> {$name} </td>";
                                                                if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png"){ $str.= "
                                                                    <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='$path'>View Assignment</i></a> </td>"; 
                                                                }
                                                                else if(substr($tablerow['files_destination'], -4) === ".pdf" || substr($tablerow['files'], -4) === ".pdf" || substr($tablerow['files_destination'], -5) === ".docx" || substr($tablerow['files'], -5) === ".docx"  ||  substr($tablerow['files_destination'], -4) === ".doc" || substr($tablerow['files'], -4) === ".doc"  ||  substr($tablerow['files_destination'], -4) === ".ppt" || substr($tablerow['files'], -4) === ".ppt"  ||  substr($tablerow['files_destination'], -5) === ".pptx" || substr($tablerow['files'], -5) === ".pptx"  ||  substr($tablerow['files_destination'], -4) === ".xls" || substr($tablerow['files'], -4) === ".xls")
                                                                {
                                                                    $str .= "<td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Assignment</i></a></td>";
                                                                }
                                                                else
                                                                {
                                                                    $str .="<td style='text-align:center;'></td>";
                                                                }
                                                                $str .="
                                                                <td style='text-align:center;'> {$tablerow['status']} </td>
                                                                <td style='text-align:center;'>
                                                                    
                                                                    <div class='btn-group' role='group' aria-label='Button Group'>
                                                                        <input type='hidden' name='id' value='$added_by'>
                                                                        <input type='hidden' name='ass_id' value='$assignmentid'>
                                                                        
                                                                        <input type='hidden' name='classCode' value='$code'>
                                                                        
                                                                        ";

                                                                        if($type === "Not Graded")
                                                                        {
                                                                            $str .="<input type='checkbox' class='checkbox' name='get_id[]' value='$get_id_update'>";
                                                                        }
                                                                        else
                                                                        {
                                                                            $str .="$notempty";
                                                                        }
                                                                        
                                                                        $str .="
                                                                         
                                                                        
                                                                    </div>
                                                                </td>
                                                                <td style='text-align:center;'>";
                                                                if($type === "Graded")
                                                                        {
                                                                            $str .="<input type='checkbox' class='checkbox' name='get_id[]' value='$get_id'>";
                                                                        }
                                                                        $str.="</td>
                                                            </tr>

                                                            ";
                                                    }
                                                }
                                                $str .="
                                                </tbody>
                                            </table>

                                        </div>
                                    
                                    <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <button name='save' class='btn btn-primary' style='background-color:maroon;' id='btn_s'>Save</button>
                                </div>
                                </form>
                                </div>       <!--End of modal-content -->                 
                            </div><!--End of modal-dialog --> 
                        </div><!--End of update modal -->

                        <!-- Modal DELETE DATA-->
                        <div class='modal fade' id='deleteModal$id' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
                            <div class='modal-dialog modal-lg' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id = 'deleteModalLabel'><b>CONFIRMATION</b></h5>
                                        
                                    </div>
                                        <form action = 'Adviser_Assignment.php?class=$code' method = 'POST'>
                                        <div class='modal-body'>
                                        <input type='hidden' name='get_id' value='$id'>
                                            <h4> Do you want to remove this Activity? </h4>
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                        <button type='submit' name = 'delete' class='btn btn-danger'>Confirm</button>
                                        </div>
                                        </form>
                                </div>
                            </div>
                        </div>

                        <div class='modal fade' id='editModal$id' style='margin-left:5%;' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>
                            <div class='modal-dialog modal-lg' id='edit_ass'>
                                <div class='modal-content'>

                                <!--modal header-->
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLabel'>Edit an Assignment</h5>
                                        
                                </div>
            
                                <!--modal body-->
                                <form action='Adviser_Assignment.php?class=$code' method='POST' enctype='multipart/form-data'>
                                    <div class='modal-body p-5'>
            
                                        <!--this is where the text areas are going to be placed-->
                                        <div class='md-title'>
                                            <label for='exampleFormControlInput1' name='title' class='form-label'>Title</label>
                                            <input type='text' name='title' class='form-control' id='exampleFormControlInput1' placeholder='$title' value='$title'>
                                        </div>
                                        <br>
            
                                        <div class='md-textarea'>
                                            <label for='exampleFormControlTextarea1' class='form-label'>Instructions</label>
                                            <textarea name='instruction' class='form-control' id='edit_instruction$id' rows='3' required>$instruction</textarea>
                                        </div>

                                        $script

                                        <div class='md-file' data-tooltip='Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX. Maximum File Size accepted is 50 MB.'>
                                            <label for='formFile' class='form-labe'></label>
                                            <input class='form-control' type='file' name='file' id='formFile'>

                                            <style>
                                    
                                                .md-file:hover:after {
                                                content:attr(data-tooltip);
                                                }
                            
                                                </style>
                                        </div>
                                        <br>

                                        <style type='text/css'>
                                        .d-none{
                                            display: none;
                                                }
                                        </style>
            
                                        <h8>Type of Assignment</h8>";
                                        $radiobutton = "
                                        <script type='text/javascript'>
                                            function enableGRDedit$id(answer) {console.log(answer.value);
                                                if(answer.value == 'Graded') {
                                                    document.getElementById('edit_option').classList.remove('d-none');
                                                } else if(answer.value == 'Not Graded') {
                                                    document.getElementById('edit_option').classList.remove('d-none');
                                                }
                                                else {
                                                    document.getElementById('edit_option').classList.add('d-none');
                                                }
                                            }
                                        </script> ";
                                            
                                    $str .=" 
                                    $radiobutton
                                        
                                        <div id ='graded' class='form-check'>
                                        <form>
                                        <label>
                                            <input type='radio' name='option' value='Graded'"; if($type == "Graded"){ $str .= "checked"; } $str .=" onchange = 'enableGRDedit$id(this)' required> Graded
                                        </label>
                                        
                                        </div>
                                        <div id='notgraded' class='form-check' onchange = 'enableGRDedit$id(this)'>
                                        <label>
                                            <input type='radio' name='option' value='Not Graded' "; if($type == "Not Graded"){ $str .= "checked"; } $str .=" > Not Graded
                                        </label>    
                                        </div>
            
                                        <div id='edit_option' class='md-grade'>
                                            <label for='grd' class='form-label'>Points</label>
                                            <input class='form-control form-control-sm' type='text' name='points' placeholder='$points' value='$points' aria-label='.form-control-sm example'>
                                        </div>
                                        <br>";

                                        if($start_date === '0000-00-00 00:00:00' || $due_date === '0000-00-00 00:00:00')
                                        {
                                            $str .="
                                            <div class = 'form-row'>
                                            <div class='form-group col-md-6'>
                                                <div class='md-grade'>
                                                    <label for='grd' class='form-label'>Start Date</label>
                                                    <input class='form-control form-control-sm' id='start_date' type='datetime-local' name='start_date' value='$start_date' aria-label='.form-control-sm example'>
                                                </div>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <div class='md-grade'>
                                                    <label for='grd' class='form-label'>Due Date</label>
                                                    <input class='form-control form-control-sm' id='end_date' type='datetime-local' name='due_date' value='$due_date' min='$start_date' aria-label='.form-control-sm example'>
                                                </div>
                                            </div>"; ?>
                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                            <script>
                                                
                                            $(document).ready(function() {
                                            $('#start_date').change(function() {
                                                $('#end_date').prop('disabled', false);
                                                $('#end_date').prop('min', $('#start_date').val());
                                            });
                                            });
                                            </script>
                                            <?php
                                            $str .="
                                            </div>
                                            <br>
                                            ";
                                        }
                                        else
                                        {
                                            $str .="
                                            <div class = 'form-row'>
                                        <div class='form-group col-md-6'>
                                            <div class='md-grade'>
                                                <label for='grd' class='form-label'>Start Date</label>
                                                <input class='form-control form-control-sm' id='grd' type='datetime-local' name='start_date' value='$formatted_date_start' aria-label='.form-control-sm example'>
                                            </div>
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <div class='md-grade'>
                                                <label for='grd' class='form-label'>Due Date</label>
                                                <input class='form-control form-control-sm' id='grd' type='datetime-local' name='due_date' value='$formatted_date' min='$formatted_date_start' aria-label='.form-control-sm example'>
                                            </div>
                                        </div>
                                        "; ?>
                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                            <script>
                                            $(document).ready(function() {
                                            $('#start_date').change(function() {
                                                $('#end_date').prop('disabled', false);
                                                $('#end_date').prop('min', $('#start_date').val());
                                            });
                                            });
                                            </script>
                                            <?php
                                            $str .="
                                        </div>
                                        <br>
                                            ";
                                        }
            
                                        $str .="

                                        <input type='hidden' name='get_id' value='$id'>
            
                                    </div> <!--end of modal-body-->
            
                                    <!--modal footer-->
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                        <button type='submit' name='edit' class='btn btn-primary'>Submit</button>
                                    </div>
                                    </form>
                            </div>
                        </div>
                </div>
                
                    </div> <!--End of toggle -->
                </div> <!--End of Card -->";
            }
            echo $str;
        }else{
            $str = "
            <br>
            <div class='card-deck'>
                <div class='card style'>
                    <div class='card-body text-center'>
                    <br>
                    <h5> There's no classworks yet!</h5>
                    </div>
                </div>
            </div>
            ";
            echo $str;
        }
}



if(isset($_REQUEST['inprocess'])){

    $faculty = $_SESSION['faculty'];

    $code = $_REQUEST['classCode'];
    $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
if(mysqli_num_rows($section) > 0)
{
    $get_section = mysqli_fetch_array($section);

    $course = $get_section['course'];
    $year_section = $get_section['year_section'];
}
    $str="";
    $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row1 = mysqli_fetch_array($sql);
    $course = $row1['course'];
    $yrsection = $row1['year_section'];
    $students = $row1['student_list'];
    $students = str_replace(',', ' ', $students);
    $classID = $row1['class_id'];
    $sql2 = mysqli_query($conn, "SELECT * FROM adviser_info, create_class WHERE adviser_info.facultynum = create_class.facultynum AND class_code='$code'");
    $row2 = mysqli_fetch_array($sql2);
    $name = $row2['name'];
    $id = $row2['facultynum'];

    $message ="";

    $query = "SELECT * FROM student_record WHERE class_code = '$code' AND status = 'Incomplete' ORDER by studentnum ASC";
    $result = mysqli_query($conn, $query);
    ?>
<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>People</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "../css/adviser_dashhome.css">
    
    <style>
                .table-responsive
        {
            overflow-x:scroll;-webkit-overflow-scrolling:touch
        }
        @media (max-width:575.98px)
        {
            .table-responsive-sm{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:767.98px)
        {
            .table-responsive-md{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:991.98px)
        {
            .table-responsive-lg{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:1199.98px)
        {
            .table-responsive-xl{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:1399.98px)
        {
            .table-responsive-xxl{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        #table
        {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        min-width: 100%;
        }

        #table td, #table th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table th 
        {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: white;
        color: black;
        text-align: center;
        }

        body 
        {
        font-family: Arial, Helvetica, sans-serif;
        }

        hr.solid 
        {
        border-top: 3px solid #bbb;
        }
    </style>
</head>

<body>

    <!--SIDE BAR-->
    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <!--Background Side Bar-->

            <br><br>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>

            <h6 style="color: white;font-size:20px;"><b> Adviser </b></h6> <br><p style="text-align:center;font-size:12px;color:white;"><?php echo "$course" . " $year_section"; ?></p><hr style="background-color:white;width:200px;">
            <a href="Adviser_Dashboard.php?classCode=<?php echo $code ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp; Dashboard</a>
            <a href="Adviser_DashHome.php?classCode=<?php echo $code ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments" style="font-size: 1.3em;"></i>&emsp;Discussion</a>
            <a href="Adviser_Calendar.php?classCode=<?php echo $code ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-clock-o" style="font-size: 1.3em;"></i>&emsp;Schedule </a> 
            
            <a href="Adviser_ClassList.php" style="margin-top:80%;">
            <i class = "fa fa-arrow-circle-left fa-1x" style="font-size: 1em;"></i> &nbsp; Back to Class List
            </a> 
            
        </center>
    </div>

    <!--MAIN CONTENT -->
    <div class = "main">

        <!--TOP NAV-->
        <div class = "topnavbar" style="z-index:3;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
  
            <div class="topnavbar-right" id="topnav_right">
            <a target = "_self" href="../Logout.php?Logout=<?php echo $faculty ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->
            <br><br><br><br>
            

    <?php
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
            title: 'Added successfully'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['deletesuccess']))
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
            title: 'Deleted successfully'
        })
        </script>
        <?php
            } 
            if(isset($_REQUEST['editsuccess']))
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
            title: 'Edited successfully'
        })
        </script>
        <?php
            }?>
            

              <div class="newtable">    

              <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">In Process Students 
              <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Adviser_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
            <hr style="background-color:maroon;border-width:2px;">
                
            <div class="people">
                    
                        <br>
                        <h4> Students </h4>
                        <div class="card">
                            <div class="card-body" style="width: auto;">
                            <div class="table table-responsive">
                                <table id="table">
                                    <thead>
                                        <tr>
                                            <th style='display: none;'></th>
                                            <th style='display: none;'></th>
                                        <th>Student Number</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Company</th>
                                        <th>Psychological</th>
                                        <th>Medical</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            if(mysqli_num_rows($result) > 0)    
                                            {
                                                while($row = mysqli_fetch_array($result))
                                                {
                                                    $student = $row['studentnum'];
                                                    
                                                    ?>

                                                
                                                    <tr>
                                                        <td class="stud_id" style='display: none;'><?php echo $row['student_id']; ?></td>
                                                        <td class="classCode" style='display: none;'><?php echo $row['class_code']; ?></td>
                                                        <td><?php echo $row['studentnum']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>          
                                                        <td><?php echo $row['company']; ?></td> 
                                                        <td><?php echo $row['psychological']; ?></td>
                                                        <td><?php echo $row['medical']; ?></td>
                                                        <td><?php echo $row['status']; ?></td>
                                                        <td>
                                                        <div class="btn-group" role="group" aria-label="Button Group">
                                                            <a href="#" button type="button" class = "userinfo btn btn-primary view_btn btn-sm"><i class='fa fa-eye'></i></a>
                                                            <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn btn-sm" data-bs-toggle='modal' data-bs-target='#editStudentModal'><i class='fa fa-edit'></i></a>
                                                            <a href="#" button type="button" class = "userinfo btn btn-danger delete_btn btn-sm"><i class=' fa fa-trash'></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                           
                                            ?>
                                    </tbody>
                                </table>

                                
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
            

            <!-- Modal VIEW DATA-->
        <div class="modal fade" id="viewStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="view_modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                    </div>
            <div class="modal-body">
                <div class = "student_view"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>

         <!-- Modal EDIT DATA -->          
         <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="edit_modal" role="document">
                <div class="modal-content">

                

                        <div class="modal-header">
                            <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                           
                        </div>
                        <?php
                        $get_student = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
                        
                        ?>
                        
                            <div class="modal-body">
                                <form action = "Adviser_People.php?adddata" method = "POST">
                                <input type = "hidden" name = "edit_id" id = "edit_id">
                                <div class = "form-row">
                                <div class="form-group col-md-6">
                                    <label for = ""><b>Student Number: </b></label>
                                    <input type="text" name = "studentnum" id = "edit_studentnum" class = "form-control" placeholder = "Enter Student Number" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Name: </b></label>
                                    <input type="text" name = "name" id = "edit_name" class = "form-control" placeholder = "Enter Name" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Email Address: </b></label>
                                    <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" placeholder = "Enter Email Address" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Company: </b></label>
                                    <input type="text" name = "company" id = "edit_company" class = "form-control" placeholder = "Enter Company">
                                </div>

                                
                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Psychological Exam Status</b></label>
                                        <select name = "psychological" id = "edit_psychological" class = "form-control" />
                                        <option value = "">--Select Psychological Exam Status--</option>
                                        <option value = "Accepted">Accepted</option>
                                        <option value = "Declined">Declined</option>
                                        <option value = "Processing">Processing</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Medical Exam Status</b></label>
                                        <select name = "medical" id = "edit_medical" class = "form-control" />
                                        <option value = "">--Select Medical Exam Status--</option>
                                        <option value = "Accepted">Accepted</option>
                                        <option value = "Declined">Declined</option>
                                        <option value = "Processing">Processing</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Status</b></label>
                                        <select name = "status" id = "edit_status" class = "form-control" />
                                        <option value = "">--Select Student Status--</option>
                                        <option value = "On Going">On Going</option>
                                        <option value = "Withdrawn">Withdrawn</option>
                                        <option value = "Completed">Completed</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>DTR Option</b></label>
                                        <select name = "DTR" id = "edit_DTR" class = "form-control" />
                                        <option value = "">--Select DTR Option--</option>
                                        <option value = "Company">Company</option>
                                        <option value = "Classwork">Classwork</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Insurance</b></label>
                                        <select name = "insurance" id = "edit_insurance" class = "form-control" />
                                        <option value = "Select">--Select Insurance--</option>
                                        <option value = "Manulife">Manulife</option>
                                        <option value = "Sun Life">Sun Life</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Specify Insurance: </b></label>
                                        <input type="text" name = "Sinsurance" id = "edit_Sinsurance" class = "form-control" placeholder = "Enter Insurance Name">
                                    </div>
                                    

                                    <div class="form-group col-md-12">
                                        <label for = ""><b>HOURS Rendered</b></label>
                                        
                                        <input type="number" name = "HRS_rendered" id = "edit_HRS_Rendered" class = "form-control"  placeholder="Enter Hours Number">
                                    </div>
                                    
                                </div>
                                <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">

                            </div><!--end of modal body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

         <!-- Modal DELETE DATA-->
         <div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id = "deleteStudentModalLabel"><b>CONFIRMATION</b></h5>
                    </button>
                </div>
                <form action = "Adviser_DashClass.php?adddata" method = "POST">
                <div class="modal-body">
                <input type = "hidden" name = "student_id" id = "delete_id">
                <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">
                    <h4> Do you want to delete this information? </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name = "delete_student" class="btn btn-danger">Confirm</button>
                </div>
                </form>
                </div>
            </div>
            </div>

        <script>
            $(document).ready(function() {

                $('.delete_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                $('#delete_id').val(stud_id);
                $('#deleteStudentModal').modal('show');
                });
                
                $('.edit_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                var classCode = $(this).closest('tr').find('.classCode').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_DashClass.php?adddata",
                data: {
                    'checking_editbtn': true,
                    'student_id': stud_id,
                    'classCode': classCode,
                },
                success: function (response) {
                    $.each(response,function (key, value){
                    $('#edit_id').val(value['student_id']);
                    $('#edit_studentnum').val(value['studentnum']);
                    $('#edit_name').val(value['name']);
                    $('#edit_emailadd').val(value['email']);
                    $('#edit_company').val(value['company']);
                    $('#edit_insurance').val(value['insurance']);
                    $('#edit_psychological').val(value['psychological']);
                    $('#edit_medical').val(value['medical']);
                    $('#edit_status').val(value['status']);
                    $('#edit_DTR').val(value['dtr_option']);
                    
                    });
                    $('#editStudentModal').modal('show');
                }
                });

                });

                $('.view_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                var classCode = $(this).closest('tr').find('.classCode').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_DashClass.php?adddata",
                data: {
                    'checking_viewbtn': true,
                    'student_id': stud_id,
                    'classCode': classCode,
                },
                success: function (response) {
                    console.log(response);
                    $('.student_view').html(response);
                    $('#viewStudentModal').modal('show');
                }
                });

                

                });
            });
            </script>
            <script>
        $(document).ready( function () 
        {
            $('#myTable').DataTable();
        } );

            $(document).ready(function(){
            $('#table').DataTable({
                lengthMenu:[
                [10, 15, 20, 30, 50, -1],
                [10, 15, 20, 30, 50, 'All'],
                ],
            });
            });
    </script>
    <script>
            const tabletSize5 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
            function handleScreenSizeChange(tabletSize5) {
                if (tabletSize5.matches) 
                {
                    document.getElementById('topnav_right').classList.remove('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "12px";
                    document.getElementById('view_modal').style.maxWidth = "80%";
                    document.getElementById('edit_modal').style.maxWidth = "80%";
                } 
                else
                {
                    document.getElementById('topnav_right').classList.add('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "13.5px";
                    document.getElementById('view_modal').style.maxWidth = "";
                    document.getElementById('edit_modal').style.maxWidth = "";
                }
            }

            tabletSize5.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize5);
        </script>

         </div> <!--end of newtable1 -->

        </div><!--end of main -->



    
</body>
</html>
<?php

}////////////////////////////////////////////////////////////////////////////////////////////////////end of inprocess




if(isset($_REQUEST['ongoing'])){

    $faculty = $_SESSION['faculty'];

    $code = $_REQUEST['classCode'];
    $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
if(mysqli_num_rows($section) > 0)
{
    $get_section = mysqli_fetch_array($section);

    $course = $get_section['course'];
    $year_section = $get_section['year_section'];
}
    $str="";
    $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row1 = mysqli_fetch_array($sql);
    $course = $row1['course'];
    $yrsection = $row1['year_section'];

    $students = $row1['student_list'];
    $students = str_replace(',', ' ', $students);
    $classID = $row1['class_id'];
    $sql2 = mysqli_query($conn, "SELECT * FROM adviser_info, create_class WHERE adviser_info.facultynum = create_class.facultynum AND class_code='$code'");
    $row2 = mysqli_fetch_array($sql2);
    $name = $row2['name'];
    $id = $row2['facultynum'];

    $message ="";

    $query = "SELECT * FROM student_record WHERE class_code = '$code' AND status = 'On Going' ORDER by studentnum ASC";
    $result = mysqli_query($conn, $query);
    ?>
<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>People</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "../css/adviser_dashhome.css">
    
    <style>
                .table-responsive
        {
            overflow-x:scroll;-webkit-overflow-scrolling:touch
        }
        @media (max-width:575.98px)
        {
            .table-responsive-sm{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:767.98px)
        {
            .table-responsive-md{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:991.98px)
        {
            .table-responsive-lg{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:1199.98px)
        {
            .table-responsive-xl{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:1399.98px)
        {
            .table-responsive-xxl{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        #table
        {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        min-width: 100%;
        }

        #table td, #table th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table th 
        {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: white;
        color: black;
        text-align: center;
        }

        body 
        {
        font-family: Arial, Helvetica, sans-serif;
        }

        hr.solid 
        {
        border-top: 3px solid #bbb;
        }
    </style>
</head>

<body>

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
        <div class = "topnavbar"  style="z-index:3;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">

            <div class="topnavbar-right" id="topnav_right">
            <a target = "_self" href="../Logout.php?Logout=<?php echo $faculty ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->
            <br><br><br><br>

    <?php
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
            title: 'Added successfully'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['deletesuccess']))
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
            title: 'Deleted successfully'
        })
        </script>
        <?php
            } 
            if(isset($_REQUEST['editsuccess']))
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
            title: 'Edited successfully'
        })
        </script>
        <?php
            }?>
            

              <div class="newtable">    

              <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">On Going Students
              <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Adviser_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
              <hr style="background-color:maroon;border-width:2px;">
              <div class="people">
                    
                        <br>
                        <h4> Students </h4>
                        <div class="card">
                            <div class="card-body" style="width: auto;">
                            <div class="table table-responsive">
                                <table id="table">
                                    <thead>
                                        <tr>
                                            <th style='display: none;'></th>
                                            <th style='display: none;'></th>
                                        <th>Student Number</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Company</th>
                                        <th>Psychological</th>
                                        <th>Medical</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            if(mysqli_num_rows($result) > 0)    
                                            {
                                                while($row = mysqli_fetch_array($result))
                                                {
                                                    $student = $row['studentnum'];
                                                    
                                                    ?>

                                                
                                                    <tr>
                                                        <td class="stud_id" style='display: none;'><?php echo $row['student_id']; ?></td>
                                                        <td class="classCode" style='display: none;'><?php echo $row['class_code']; ?></td>
                                                        <td><?php echo $row['studentnum']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>          
                                                        <td><?php echo $row['company']; ?></td> 
                                                        <td><?php echo $row['psychological']; ?></td>
                                                        <td><?php echo $row['medical']; ?></td>
                                                        <td><?php echo $row['status']; ?></td>
                                                        <td>
                                                        <div class="btn-group" role="group" aria-label="Button Group">
                                                            <a href="#" button type="button" class = "userinfo btn btn-primary view_btn btn-sm"><i class='fa fa-eye'></i></a>
                                                            <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn btn-sm" data-bs-toggle='modal' data-bs-target='#editStudentModal'><i class='fa fa-edit'></i></a>
                                                            <a href="#" button type="button" class = "userinfo btn btn-danger delete_btn btn-sm"><i class='fa fa-trash'></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                           
                                            ?>
                                    </tbody>
                                </table>

                                
                            </div>
                        </div>
                        </div>
                    
                    </div>
                </div>
            </div>
            

            <!-- Modal VIEW DATA-->
        <div class="modal fade" id="viewStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" id = "view_modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                    </div>
            <div class="modal-body">
                <div class = "student_view"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>

       <!-- Modal EDIT DATA -->          
       <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="edit_modal" role="document">
                <div class="modal-content">

                

                        <div class="modal-header">
                            <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                           
                        </div>
                        <?php
                        $get_student = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
                        
                        ?>
                        
                            <div class="modal-body">
                                <form action = "Adviser_People.php?adddata" method = "POST">
                                <input type = "hidden" name = "edit_id" id = "edit_id">
                                <div class = "form-row">
                                <div class="form-group col-md-6">
                                    <label for = ""><b>Student Number: </b></label>
                                    <input type="text" name = "studentnum" id = "edit_studentnum" class = "form-control" placeholder = "Enter Student Number" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Name: </b></label>
                                    <input type="text" name = "name" id = "edit_name" class = "form-control" placeholder = "Enter Name" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Email Address: </b></label>
                                    <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" placeholder = "Enter Email Address" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Company: </b></label>
                                    <input type="text" name = "company" id = "edit_company" class = "form-control" placeholder = "Enter Company">
                                </div>

                                
                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Psychological Exam Status</b></label>
                                        <select name = "psychological" id = "edit_psychological" class = "form-control" />
                                        <option value = "">--Select Psychological Exam Status--</option>
                                        <option value = "Accepted">Accepted</option>
                                        <option value = "Declined">Declined</option>
                                        <option value = "Processing">Processing</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Medical Exam Status</b></label>
                                        <select name = "medical" id = "edit_medical" class = "form-control" />
                                        <option value = "">--Select Medical Exam Status--</option>
                                        <option value = "Accepted">Accepted</option>
                                        <option value = "Declined">Declined</option>
                                        <option value = "Processing">Processing</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Status</b></label>
                                        <select name = "status" id = "edit_status" class = "form-control" />
                                        <option value = "">--Select Student Status--</option>
                                        <option value = "On Going">On Going</option>
                                        <option value = "Withdrawn">Withdrawn</option>
                                        <option value = "Completed">Completed</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>DTR Option</b></label>
                                        <select name = "DTR" id = "edit_DTR" class = "form-control" />
                                        <option value = "">--Select DTR Option--</option>
                                        <option value = "Company">Company</option>
                                        <option value = "Classwork">Classwork</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Insurance</b></label>
                                        <select name = "insurance" id = "edit_insurance" class = "form-control" />
                                        <option value = "Select">--Select Insurance--</option>
                                        <option value = "Manulife">Manulife</option>
                                        <option value = "Sun Life">Sun Life</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Specify Insurance: </b></label>
                                        <input type="text" name = "Sinsurance" id = "edit_Sinsurance" class = "form-control" placeholder = "Enter Insurance Name">
                                    </div>
                                    

                                    <div class="form-group col-md-12">
                                        <label for = ""><b>HOURS Rendered</b></label>
                                        
                                        <input type="number" name = "HRS_rendered" id = "edit_HRS_Rendered" class = "form-control"  placeholder="Enter Hours Number">
                                    </div>
                                    
                                </div>
                                <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">

                            </div><!--end of modal body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

         <!-- Modal DELETE DATA-->
         <div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id = "deleteStudentModalLabel"><b>CONFIRMATION</b></h5>
                    </button>
                </div>
                <form action = "Adviser_DashClass.php?adddata" method = "POST">
                <div class="modal-body">
                <input type = "hidden" name = "student_id" id = "delete_id">
                <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">
                    <h4> Do you want to delete this information? </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name = "delete_student" class="btn btn-danger">Confirm</button>
                </div>
                </form>
                </div>
            </div>
            </div>

        <script>
            $(document).ready(function() {

                $('.delete_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                $('#delete_id').val(stud_id);
                $('#deleteStudentModal').modal('show');
                });
                
                $('.edit_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                var classCode = $(this).closest('tr').find('.classCode').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_DashClass.php?adddata",
                data: {
                    'checking_editbtn': true,
                    'student_id': stud_id,
                    'classCode': classCode,
                },
                success: function (response) {
                    $.each(response,function (key, value){
                    $('#edit_id').val(value['student_id']);
                    $('#edit_studentnum').val(value['studentnum']);
                    $('#edit_name').val(value['name']);
                    $('#edit_emailadd').val(value['email']);
                    $('#edit_company').val(value['company']);
                    $('#edit_insurance').val(value['insurance']);
                    $('#edit_psychological').val(value['psychological']);
                    $('#edit_medical').val(value['medical']);
                    $('#edit_status').val(value['status']);
                    $('#edit_DTR').val(value['dtr_option']);
                    
                    });
                    $('#editStudentModal').modal('show');
                }
                });

                });

                $('.view_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                var classCode = $(this).closest('tr').find('.classCode').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_DashClass.php?adddata",
                data: {
                    'checking_viewbtn': true,
                    'student_id': stud_id,
                    'classCode': classCode,
                },
                success: function (response) {
                    console.log(response);
                    $('.student_view').html(response);
                    $('#viewStudentModal').modal('show');
                }
                });

                

                });
            });
            </script>
            <script>
        $(document).ready( function () 
        {
            $('#myTable').DataTable();
        } );

            $(document).ready(function(){
            $('#table').DataTable({
                lengthMenu:[
                [10, 15, 20, 30, 50, -1],
                [10, 15, 20, 30, 50, 'All'],
                ],
            });
            });
    </script>
        <script>
            const tabletSize1 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
            function handleScreenSizeChange(tabletSize1) {
                if (tabletSize1.matches) 
                {
                    document.getElementById('topnav_right').classList.remove('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "12px";
                    document.getElementById('view_modal').style.maxWidth = "80%";
                    document.getElementById('edit_modal').style.maxWidth = "80%";
                } 
                else
                {
                    document.getElementById('topnav_right').classList.add('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "13.5px";
                    document.getElementById('view_modal').style.maxWidth = "";
                    document.getElementById('edit_modal').style.maxWidth = "";
                }
            }

            tabletSize1.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize1);
        </script>

         </div> <!--end of newtable1 -->

        </div><!--end of main -->



    
</body>
</html>
<?php
}/////////////////////////////////////////end of on going

if(isset($_REQUEST['completed'])){

    $faculty = $_SESSION['faculty'];

    $code = $_REQUEST['classCode'];
    $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
if(mysqli_num_rows($section) > 0)
{
    $get_section = mysqli_fetch_array($section);

    $course = $get_section['course'];
    $year_section = $get_section['year_section'];
}
    $str="";
    $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row1 = mysqli_fetch_array($sql);
    $course = $row1['course'];
    $yrsection = $row1['year_section'];
    $students = $row1['student_list'];
    $students = str_replace(',', ' ', $students);
    $classID = $row1['class_id'];
    $sql2 = mysqli_query($conn, "SELECT * FROM adviser_info, create_class WHERE adviser_info.facultynum = create_class.facultynum AND class_code='$code'");
    $row2 = mysqli_fetch_array($sql2);
    $name = $row2['name'];
    $id = $row2['facultynum'];

    $message ="";

    $query = "SELECT * FROM student_record WHERE class_code = '$code' AND status = 'Completed' ORDER BY studentnum ASC";
    $result = mysqli_query($conn, $query);
    ?>
<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>People</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "../css/adviser_dashhome.css">
    
    <style>
               .table-responsive
        {
            overflow-x:scroll;-webkit-overflow-scrolling:touch
        }
        @media (max-width:575.98px)
        {
            .table-responsive-sm{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:767.98px)
        {
            .table-responsive-md{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:991.98px)
        {
            .table-responsive-lg{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:1199.98px)
        {
            .table-responsive-xl{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        @media (max-width:1399.98px)
        {
            .table-responsive-xxl{overflow-x:auto;-webkit-overflow-scrolling:touch
            }}
        #table
        {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        min-width: 100%;
        }

        #table td, #table th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table th 
        {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: white;
        color: black;
        text-align: center;
        }

        body 
        {
        font-family: Arial, Helvetica, sans-serif;
        }

        hr.solid 
        {
        border-top: 3px solid #bbb;
        }
    </style>
</head>

<body>

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
        <div class = "topnavbar"  style="z-index:3;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
  
            <div class="topnavbar-right" id="topnav_right">
            <a target = "_self" href="../Logout.php?Logout=<?php echo $faculty ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->
            <br><br><br><br>

    <?php
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
            title: 'Added successfully'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['deletesuccess']))
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
            title: 'Deleted successfully'
        })
        </script>
        <?php
            } 
            if(isset($_REQUEST['editsuccess']))
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
            title: 'Edited successfully'
        })
        </script>
        <?php
            }?>
            

              <div class="newtable">    

              <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Completed Students
              <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Adviser_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a></h1>
            <hr style="background-color:maroon;border-width:2px;">
                 
            <div class="people">
                    
                        <br>
                        <h4> Students </h4>
                        <div class="card">
                            <div class="card-body" style="width: auto;">
                            <div class="table table-responsive">
                                <table id="table">
                                    <thead>
                                        <tr>
                                            <th style='display: none;'></th>
                                            <th style='display: none;'></th>
                                        <th>Student Number</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Company</th>
                                        <th>Psychological</th>
                                        <th>Medical</th>
                                        
                                        <th>Evaluation</th>
                                        <th>Action</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            if(mysqli_num_rows($result) > 0)    
                                            {
                                                while($row = mysqli_fetch_array($result))
                                                {
                                                    $student = $row['studentnum'];
                                                    $id = $row['student_id'];
                                                    $status = $row['status'];
                                                    
                                                    ?>

                                                
                                                    <tr>
                                                        <td class="stud_id" style='display: none;'><?php echo $row['student_id']; ?></td>
                                                        <td class="classCode" style='display: none;'><?php echo $row['class_code']; ?></td>
                                                        <td><?php echo $row['studentnum']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>          
                                                        <td><?php echo $row['company']; ?></td> 
                                                        <td><?php echo $row['psychological']; ?></td>
                                                        <td><?php echo $row['medical']; ?></td>
                                                        
                                                        <?php
                                                         $query = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$id' AND studentnum = '$student' AND class_code = '$code' AND status = 'Completed' AND HRS_rendered = '500'");
                                        
                                                         if(mysqli_num_rows($query) === 1) 
                                                         {
                                                            $row1 = mysqli_fetch_array($query);
                                                            $id = $row1['student_id'];
                                                            
                                                            
                                                                ?>
                                                                    <td><a href="#" button type="button" class = "userinfo btn btn-secondary btn-sm" data-bs-toggle='modal' data-bs-target='#evalStudentModal<?php echo $id; ?>'><i class='fa fa-upload'></i></a></td>
                                                                             
                                                                    <!-- Modal EVALUATION -->
                                                                    <div class="modal fade" id="evalStudentModal<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="evalStudentModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg" id="eval_modal" role="document">
                                                                            <div class="modal-content">

                                                                            

                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"><b>COMPANY EVALUATION TO STUDENT</b></h5>
                                                                                    
                                                                                    </div>
                                                                                    <?php
                                                                                    $get_student = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$id'");
                                                                                    $get_record = mysqli_fetch_array($get_student);
                                                                                    $student = $get_record['studentnum'];
                                                                                    $code = $get_record['class_code'];
                                                                                    $name = $get_record['name'];
                                                                                    $company = $get_record['company'];
                                                                                    
                                                                                    ?>
                                                                                    
                                                                                        <div class="modal-body">
                                                                                            
                                                                                            <form action = "Adviser_DashClass.php?adddata" method = "POST">
                                                                                            <center>
                                                                                            <p style="color:red;padding:2px;">Notice: This will email an Evaluation Form to <?php $name ?>'s Company <br> and the process of transaction will be held on the outlook. <br> Any replies and comments will only be transact to the email thread.</p>
                                                                                            
                                                                                            <br>
                                                                                            <div class="form-row">
                                                                                                <div class="form-group col-md-6">
                                                                                                    
                                                                                                    <label for = ""><b>Company: </b></label>
                                                                                                    <input type="text" name = "comp=" class = "form-control" style="text-align: center;pointer-events: none;" value = "<?php echo $company; ?>" required>
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    
                                                                                                    <label for = ""><b>Company Email Address: </b></label>
                                                                                                    <input type="text" name = "comp_email" id = "edit_comp_email" class = "form-control" style="text-align: center;" placeholder = "Enter Company Email Address" required>
                                                                                                </div></center>
                                                                                            <input type = "hidden" name = "get_student" value = "<?php echo $student; ?>">
                                                                                            <input type = "hidden" name = "get_code" value = "<?php echo $code; ?>">
                                                                                            <input type = "hidden" name = "get_name" value = "<?php echo $name; ?>">
                                                                                            <input type = "hidden" name = "get_company" value = "<?php echo $company; ?>">
                                                                                        </div>
                                                                                    
                                                                                   
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                    <button type="submit" name = "send_email" class="btn btn-primary">Send</button>
                                                                                </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                            
                                                         }
                                                         else if($status == "Evaluated")
                                                         {
                                                            ?>
                                                            <td>Done</td>
                                                            <?php
                                                         }
                                                         else
                                                         {
                                                            ?>
                                                            <td>Pending</td>
                                                            <?php
                                                         }
                                                        ?>
                                                        <td>
                                                        <div class="btn-group" role="group" aria-label="Button Group">
                                                            <a href="#" button type="button" class = "userinfo btn btn-primary view_btn btn-sm"><i class='fa fa-eye'></i></a>
                                                            <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn btn-sm" data-bs-toggle='modal' data-bs-target='#editStudentModal'><i class='fa fa-edit'></i></a>
                                                            <a href="#" button type="button" class = "userinfo btn btn-danger delete_btn btn-sm"><i class='fa fa-trash'></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                           
                                            ?>
                                    </tbody>
                                </table>

                            </div>
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
            

            <!-- Modal VIEW DATA-->
        <div class="modal fade" id="viewStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="view_modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                    </div>
            <div class="modal-body">
                <div class = "student_view"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>

         <!-- Modal EDIT DATA -->          
         <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="edit_modal" role="document">
                <div class="modal-content">

                

                        <div class="modal-header">
                            <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                           
                        </div>
                        <?php
                        $get_student = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
                        
                        ?>
                        
                            <div class="modal-body">
                                <form action = "Adviser_People.php?adddata" method = "POST">
                                <input type = "hidden" name = "edit_id" id = "edit_id">
                                <div class = "form-row">
                                <div class="form-group col-md-6">
                                    <label for = ""><b>Student Number: </b></label>
                                    <input type="text" name = "studentnum" id = "edit_studentnum" class = "form-control" placeholder = "Enter Student Number" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Name: </b></label>
                                    <input type="text" name = "name" id = "edit_name" class = "form-control" placeholder = "Enter Name" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Email Address: </b></label>
                                    <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" placeholder = "Enter Email Address" style="pointer-events: none;" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Company: </b></label>
                                    <input type="text" name = "company" id = "edit_company" class = "form-control" placeholder = "Enter Company">
                                </div>

                                
                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Psychological Exam Status</b></label>
                                        <select name = "psychological" id = "edit_psychological" class = "form-control" />
                                        <option value = "">--Select Psychological Exam Status--</option>
                                        <option value = "Accepted">Accepted</option>
                                        <option value = "Declined">Declined</option>
                                        <option value = "Processing">Processing</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Medical Exam Status</b></label>
                                        <select name = "medical" id = "edit_medical" class = "form-control" />
                                        <option value = "">--Select Medical Exam Status--</option>
                                        <option value = "Accepted">Accepted</option>
                                        <option value = "Declined">Declined</option>
                                        <option value = "Processing">Processing</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Status</b></label>
                                        <select name = "status" id = "edit_status" class = "form-control" />
                                        <option value = "">--Select Student Status--</option>
                                        <option value = "On Going">On Going</option>
                                        <option value = "Withdrawn">Withdrawn</option>
                                        <option value = "Completed">Completed</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>DTR Option</b></label>
                                        <select name = "DTR" id = "edit_DTR" class = "form-control" />
                                        <option value = "">--Select DTR Option--</option>
                                        <option value = "Company">Company</option>
                                        <option value = "Classwork">Classwork</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Insurance</b></label>
                                        <select name = "insurance" id = "edit_insurance" class = "form-control" />
                                        <option value = "Select">--Select Insurance--</option>
                                        <option value = "Manulife">Manulife</option>
                                        <option value = "Sun Life">Sun Life</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for = ""><b>Specify Insurance: </b></label>
                                        <input type="text" name = "Sinsurance" id = "edit_Sinsurance" class = "form-control" placeholder = "Enter Insurance Name">
                                    </div>
                                    

                                    <div class="form-group col-md-12">
                                        <label for = ""><b>HOURS Rendered</b></label>
                                        
                                        <input type="number" name = "HRS_rendered" id = "edit_HRS_Rendered" class = "form-control"  placeholder="Enter Hours Number">
                                    </div>
                                    
                                </div>
                                <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">

                            </div><!--end of modal body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


         <!-- Modal DELETE DATA-->
         <div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id = "deleteStudentModalLabel"><b>CONFIRMATION</b></h5>
                    </button>
                </div>
                <form action = "Adviser_DashClass.php?adddata" method = "POST">
                <div class="modal-body">
                <input type = "hidden" name = "student_id" id = "delete_id">
                <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">
                    <h4> Do you want to delete this information? </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name = "delete_student" class="btn btn-danger">Confirm</button>
                </div>
                </form>
                </div>
            </div>
            </div>

        <script>
            $(document).ready(function() {

                $('.delete_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                $('#delete_id').val(stud_id);
                $('#deleteStudentModal').modal('show');
                });
                
                $('.edit_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                var classCode = $(this).closest('tr').find('.classCode').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_DashClass.php?adddata",
                data: {
                    'checking_editbtn': true,
                    'student_id': stud_id,
                    'classCode': classCode,
                },
                success: function (response) {
                    $.each(response,function (key, value){
                    $('#edit_id').val(value['student_id']);
                    $('#edit_studentnum').val(value['studentnum']);
                    $('#edit_name').val(value['name']);
                    $('#edit_emailadd').val(value['email']);
                    $('#edit_company').val(value['company']);
                    $('#edit_psychological').val(value['psychological']);
                    $('#edit_medical').val(value['medical']);
                    $('#edit_status').val(value['status']);
                    $('#edit_DTR').val(value['dtr_option']);
                    
                    });
                    $('#editStudentModal').modal('show');
                }
                });

                });

                $('.view_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                var classCode = $(this).closest('tr').find('.classCode').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_DashClass.php?adddata",
                data: {
                    'checking_viewbtn': true,
                    'student_id': stud_id,
                    'classCode': classCode,
                },
                success: function (response) {
                    console.log(response);
                    $('.student_view').html(response);
                    $('#viewStudentModal').modal('show');
                }
                });

                

                });
            });
            </script>
            <script>
        $(document).ready( function () 
        {
            $('#myTable').DataTable();
        } );

            $(document).ready(function(){
            $('#table').DataTable({
                lengthMenu:[
                [10, 15, 20, 30, 50, -1],
                [10, 15, 20, 30, 50, 'All'],
                ],
            });
            });
    </script>
        <script>
            const tabletSize3 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
            function handleScreenSizeChange(tabletSize3) {
                if (tabletSize3.matches) 
                {
                    document.getElementById('topnav_right').classList.remove('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "12px";
                    document.getElementById('view_modal').style.maxWidth = "80%";
                    document.getElementById('edit_modal').style.maxWidth = "80%";
                    document.getElementById('eval_modal').style.maxWidth = "80%";
                } 
                else
                {
                    document.getElementById('topnav_right').classList.add('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "13.5px";
                    document.getElementById('view_modal').style.maxWidth = "";
                    document.getElementById('edit_modal').style.maxWidth = "";
                    document.getElementById('eval_modal').style.maxWidth = "";
                }
            }

            tabletSize3.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize3);
        </script>

         </div> <!--end of newtable1 -->

        </div><!--end of main -->



    
</body>
</html>
<?php
}
if(isset($_REQUEST['adddata']))
{

    if(isset($_POST['send_email']))
    {
        $email = $_POST['comp_email'];
        $student = $_POST['get_student'];
        $code  = $_POST['get_code'];
        $name = $_POST['get_name'];
        $company = $_POST['get_company'];
        $mail = new PHPMailer(true);
        $faculty = $_SESSION['faculty']; 
        $query = mysqli_query($conn, "SELECT * FROM adviser_info WHERE facultynum = '$faculty'");
        if(mysqli_num_rows($query) === 1)
        {
            $fetch = mysqli_fetch_assoc($query);
            $faculty_email = $fetch['email'];
        }

        try {
            
            $mail->isSMTP();                                            
            $mail->Host       = 'sg2plzcpnl493881.prod.sin2.secureserver.net';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'pupwims_mailer@pup-wims.site';
            $mail->Password   = 'pupwims123!';
            $mail->SMTPSecure = 'ssl';     
            $mail->Port       = 465;                                 

            //Recipients
            $mail->setFrom('pupwims_mailer@pup-wims.site', 'PUP WIMS Adviser');
            $mail->addAddress($email);     //Add a recipient           

            

            //Content
            
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'PUPSJ Internship Evaluation';
           $mail->Body    = "
            <table style='border-collapse: collapse; min-width: 100%; font-family: Arial, Helvetica, sans-serif;'>
                <thead>
                    <tr style='background-color:#AA5656;'>
                        <th style='color:white; font-size:25px; padding: 14px 30px;'> PUPSJ Internship Evaluation </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style='background-color: #FFFBF5;border: 1px solid #ddd; font-family: Helvetica; font-size: 18px;margin-left:3%;'>  
                        <br><p>&emsp;Hello and good day $company!<br><br>
                        &emsp;&emsp; We would like to inform you that we need your evaluation for the student name:<b> $name. </b><br><br>
                        &emsp;Kindly send it to the email address provided:<b> $faculty_email</b><br>
                        &emsp;Thank you for your cooperation!</p>
                        <hr style='background-color:maroon;border-width:2px;'>
                        <i style='font-size:15px; font-weight: 0.5px;'>&emsp;Sincerely, <br>
                        &emsp;Polytechnic University of the Philippines, Internship</i><br></td>
                    </tr>
                </tbody>
            </table>
           
            
            ";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
    

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        $query = mysqli_query($conn, "UPDATE student_record SET status = 'Evaluated' WHERE studentnum = '$student' AND class_code = '$code'");
        header("Location: Adviser_DashClass.php?completed&classCode=$code");
        
    }

    if(isset($_POST['delete_student']))
        {

            $id = $_POST['student_id'];
            $code  = $_POST['classCode'];

            $query = " DELETE FROM student_record WHERE student_id = '$id' AND class_code = '$code'";
            $query_run = mysqli_query($conn, $query);	


            if ($query_run)
            {
                $_SESSION['status'] = "PARTNER COMPANY INFORMATION HAS SUCCESSFULLY DELETED!";
                header("Location: Adviser_People.php?classCode=$code&Home&deletesuccess");
            }

            else
            {
                $_SESSION['status'] = "OH NO! SOMETHING WENT WRONG!";
                header("Location: Adviser_People.php?classCode=$code&Home");
            }
        }

    if(isset($_POST["upload"]))
    {
        $code = $_POST['getCode'];
        if($_FILES['product_file']['name'])
         {
            $filename = explode(".", $_FILES['product_file']['name']);
             if(end($filename) == "csv")
                {
                $handle = fopen($_FILES['product_file']['tmp_name'], "r");
                while($data = fgetcsv($handle))
                {
                    $studentnum = mysqli_real_escape_string($conn, $data[0]);
                    $email = mysqli_real_escape_string($conn, $data[1]);

                    $get_student = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND class_code = '$code'");
                    if(mysqli_num_rows($get_student) > 0)
                    {
                        while($row_student = mysqli_fetch_array($get_student))
                        {
                            $student_id = $row_student['student_id'];
                            $student_name = $row_student['name']; 
                            
                            $validate_student = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND class_code = '$code'");
                            
                        
                            if(mysqli_num_rows($validate_student) > 0)
                            {

                                
                            }
                            else
                            { 
                                $data_query = mysqli_query($conn, "UPDATE create_class SET student_list=CONCAT(student_list,'$studentnum ,') WHERE class_code='$code'");
                                $get_record = mysqli_query($conn, "INSERT INTO student_record VALUES('$student_id', '$studentnum', '$student_name', '$email', '$code', '', '', '', '', '', '', '', '')");

                            }
                                
                            }
                            
                            

                           
                        }
                    }                 
                   
                }
            fclose($handle);
            header("Location: Adviser_People.php?classCode=$code&Home&uploadsuccess");
            }
            else
            {
            $message = '<label class="text-danger">Please Select CSV File only</label>';
            }
            }
            else
            {
            $message = '<label class="text-danger">Please Select File</label>';
            }

            if(isset($_GET["updation"]))
            {
            $message = '<label class="text-success">Adviser Update Done</label>';
            }

    }

    if (isset($_POST['checking_viewbtn']))
        {
            $s_id = $_POST['student_id'];
            $code = $_POST['classCode'];
            
            $query = "SELECT * FROM student_record WHERE student_id = '$s_id' AND class_code = '$code'";
            $query_run = mysqli_query($conn, $query);

            if(mysqli_num_rows($query_run) > 0)
            {
                foreach($query_run as $row)
                {
                    echo $return = "
                    <div class = 'form-row'>
                    <div class='form-group col-md-12'>
                    <center><label for = ''><b>Student Number: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['studentnum']}' style='text-align: center;' disabled>
                    </div>
                </div>
                <div class = 'form-row'>
                    <div class='form-group col-md-6'>
                        <center><label for = ''><b>Name: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['name']}' style='text-align: center;' disabled>
                    </div>
                    <div class='form-group col-md-6'>
                        <center><label for = ''><b>Email: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['email']}' style='text-align: center;' disabled>
                    </div>
                </div>
                
                <div class = 'form-row'>
                    <div class='form-group col-md-6'>
                        <center><label for = ''><b>Company: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['company']}' style='text-align: center;' disabled>
                    </div> 
                    <div class='form-group col-md-6'>
                        <center><label for = ''><b>Insurance: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['insurance']}' style='text-align: center;' disabled>
                    </div>
                </div>

                <div class = 'form-row'>
                    <div class='form-group col-md-6'>
                        <center><label for = ''><b>Psychological: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['psychological']}' style='text-align: center;' disabled>
                    </div>
                    <div class='form-group col-md-6'>
                        <center><label for = ''><b>Medical: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['medical']}' style='text-align: center;' disabled>
                    </div> 
                </div>
                
                <div class = 'form-row'>
                    <div class='form-group col-md-6'>
                        <center><label for = ''><b>Status: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['status']}' style='text-align: center;' disabled>
                    </div>                      
                    <div class='form-group col-md-6'>
                        <center><label for = ''><b>Hours Rendered: </b></label></center>
                        <input type='text' class = 'form-control' placeholder = '{$row['HRS_rendered']} Hours' style='text-align: center;' disabled>
                    </div>
                </div>   
                   
                        
                    
                    
                ";

                }
            }

            else
            {
            echo $return = "NO RECORD!";
            }
        }

        if (isset($_POST['checking_editbtn']))
        {
            $s_id = $_POST['student_id'];
            $code  = $_POST['classCode'];
            $result_array = [];
            
            $query = "SELECT * FROM student_record WHERE student_id = '$s_id' AND class_code = '$code'";
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
            echo $return = "NO RECORD FOUND!";
            }
        }

        if(isset($_POST['update_student']))
        {
            $code  = $_POST['classCode'];
            $s_id = $_POST['edit_id'];
            $student_num = $_POST['studentnum'];
            $student_name = $_POST['name'];
            $student_email = $_POST['emailadd'];
            $student_company = $_POST['company'];
            $student_psychological = $_POST['psychological'];   
            $student_medical = $_POST['medical'];
            $student_status = $_POST['status'];
            $student_DTR = $_POST['DTR'];
            if($_POST['insurance'] === "Select")
            {
                $student_insurance = $_POST['Sinsurance'];
            }
            else
            {
                $student_insurance = $_POST['insurance'];
            }
           
            
            $time = $_POST['HRS_rendered'];

            $get_student = mysqli_query($conn, "SELECT HRS_rendered FROM student_record WHERE student_id = '$s_id' AND class_code = '$code'");

            $get_time = mysqli_fetch_array($get_student);
                
            $rendered = $get_time['HRS_rendered'];
            

            $added_time =$time + $rendered;

            $query = mysqli_query($conn, "UPDATE student_record SET studentnum = '$student_num', name = '$student_name', email = '$student_email', company = '$student_company', psychological = '$student_psychological', medical = '$student_medical', dtr_option = '$student_DTR', HRS_rendered = '$added_time', insurance = '$student_insurance' WHERE student_id = '$s_id' AND class_code = '$code'");
            
            if($student_psychological === 'Accepted' && $student_medical === 'Accepted')
            {
                $query = mysqli_query($conn, "UPDATE student_record SET status = '$student_status' WHERE student_id = '$s_id' AND class_code = '$code'");
            }
            else
            {
                $query = mysqli_query($conn, "UPDATE student_record SET status = 'Incomplete' WHERE student_id = '$s_id' AND class_code = '$code'");
            }

            if ($query)
            {
                $_SESSION['status'] = "STUDENT INFORMATION HAS SUCCESSFULLY UPDATED!";
                header("Location: Adviser_People.php?classCode=$code&Home&editsuccess");
            }
    
            else
            {
                $_SESSION['status'] = "OH NO! SOMETHING WENT WRONG!";
                header("Location: Adviser_People.php?classCode=$code&Home&unsuccess");
            }
        }

/////////////////////////////////////////////////////////////////end of completed