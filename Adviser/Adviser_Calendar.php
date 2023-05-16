<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['faculty']))
{
    header("Location: Adviser_Login.php?LoginFirst");
}

$faculty = $_SESSION['faculty'];
$code = $_GET['classCode'];

// Fetching data from create_class table
$section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
if(mysqli_num_rows($section) > 0)
{
    $get_section = mysqli_fetch_array($section);

    $course = $get_section['course'];
    $year_section = $get_section['year_section'];
}

// Function for posting calendar
if(isset($_POST['post']))
{
    
    $instruction = $_POST['instruction'];
    $instruction = strip_tags($instruction);
    $instruction = mysqli_real_escape_string($conn, $instruction);
    $check_empty = preg_replace('/\s+/', '', $instruction);
    
    date_default_timezone_set('Asia/Singapore');
    $current_date = date("Y-m-d h:i:s");

    $type = $_POST['option'];
    $title = $_POST['title'];
    $date_added = $_POST['due_date'];
    $company = $_POST['company'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $query = mysqli_query($conn, "INSERT INTO adviser_schedule VALUES ('', '$faculty', '$type', '$title', '$instruction', '$code', '$start_time', '$end_time', '$date_added', '$company', 'no')");

    $query1 = mysqli_query($conn, "SELECT * FROM adviser_schedule WHERE facultynum = '$faculty' AND class_code = '$code'");
    while($row = mysqli_fetch_array($query1)){
        $id = $row['id'];
    }
    $getname = mysqli_query($conn, "SELECT * FROM adviser_info WHERE facultynum = '$faculty'");
    $fetchname = mysqli_fetch_array($getname);
    $advisername = $fetchname['name'];

    $query4 = mysqli_query($conn, "INSERT INTO student_schedule VALUES ('', '$id', '$type', '$faculty', '$start_time','$end_time', '$date_added', '$company', '$code')");
    $notif_desc = "has created Schedule Mentoring for your company. [$company]";
    $date_time_now = date("Y-m-d h:i:s");
    $notif_link = "Student_Calendar.php?classCode=$code";
    $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code' AND company = '$company'");
    if(mysqli_num_rows($getstudentname) > 0)
    {
        while($getstudentname_row = mysqli_fetch_array($getstudentname))
        {
             $student_name = $getstudentname_row['name'];

             $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$advisername', '$student_name', '$code', '$date_time_now','no')");
        }
    }


    if ($query)
    {
        header("Location: Adviser_Calendar.php?classCode=$code&uploadsuccess");
    }           

	else
	{
		header("Location: Adviser_Calendar.php?classCode=$code&uploadunsuccess");
	}	

}


if(isset($_POST['delete_student']))
{
    $code = $_POST['classCode'];
    $id = $_POST['sched_id'];

    $query3 = mysqli_query($conn, "UPDATE adviser_schedule SET remove = 'yes' WHERE id = '$id' AND class_code = '$code'");
    if ($query3)
    {
        header("Location: Adviser_Calendar.php?classCode=$code&deletesuccess");
    }
    else
    {
        header("Location: Adviser_Calendar.php?classCode=$code&deleteunsuccess");
    }
}

if(isset($_POST['edit_sched']))
    {
        $sched_id = $_POST['edit_id'];
        $instruction = $_POST['instruction'];
        $instruction = strip_tags($instruction);
        $instruction = mysqli_real_escape_string($conn, $instruction);
        $check_empty = preg_replace('/\s+/', '', $instruction);
        
        date_default_timezone_set('Asia/Singapore');
        $current_date = date("Y-m-d h:i:s");

        $title = $_POST['title'];
        $date_added = $_POST['due_date'];
        $company = $_POST['company'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $getname = mysqli_query($conn, "SELECT * FROM adviser_info WHERE facultynum = '$faculty'");
        $fetchname = mysqli_fetch_array($getname);
        $advisername = $fetchname['name'];

        $notif_desc = "has updated Schedule Mentoring for your company. [$company]";
        $notif_link = "Student_Calendar.php?classCode=$code";

        $query = mysqli_query($conn, "UPDATE adviser_schedule SET title = '$title', instruction = '$instruction',date = '$date_added', company = '$company', start_time = '$start_time', end_time = '$end_time' WHERE id = '$sched_id'");

        $getstudentsched = mysqli_query($conn, "SELECT * FROM student_schedule WHERE schedule_id = '$sched_id'");
        $fetchsched = mysqli_fetch_array($getstudentsched);
       
            $company_set = $fetchsched['company'];
        
        

        if(!empty($getstudentsched))
        {
            
            if($company_set === $company)
            {
                $studentquery = mysqli_query($conn, "UPDATE student_schedule SET start_time = '$start_time', end_time ='$end_time', date = '$date_added', company = '$company' WHERE schedule_id = '$sched_id'");
                $query = mysqli_query($conn, "SELECT * FROM student_record WHERE company = '$company'");
                if(mysqli_num_rows($query) > 0)
                {
                    while($row = mysqli_fetch_array($query))
                    {
                        $student_name = $row['name'];
                        $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$advisername', '$student_name', '$code', '$current_date','no')");
                    }
                }
            }
            else
            {
                $sched_delete = mysqli_query($conn, "DELETE FROM student_schedule WHERE schedule_id = '$sched_id'");
                $query4 = mysqli_query($conn, "INSERT INTO student_schedule VALUES ('', '$sched_id', '$faculty', '$start_time','$end_time', '$date_added', '$company', '$code')");

                $query = mysqli_query($conn, "SELECT * FROM student_record WHERE company = '$company'");
                if(mysqli_num_rows($query) > 0)
                {
                    while($row = mysqli_fetch_array($query))
                    {
                        $student_name = $row['name'];
                        $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$advisername', '$student_name', '$code', '$current_date','no')");
                    }
                }
            }
           
        }
        else
        {
            $query4 = mysqli_query($conn, "INSERT INTO student_schedule VALUES ('', '$sched_id', '$faculty', '$start_time','$end_time', '$date_added', '$company', '$code')");
            $query = mysqli_query($conn, "SELECT * FROM student_record WHERE company = '$company'");
            if(mysqli_num_rows($query) > 0)
            {
                while($row = mysqli_fetch_array($query))
                {
                    $student_name = $row['name'];
                    $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$advisername', '$student_name', '$code', '$current_date','no')");
                }
            }
        }
        if ($query)
        {
            header("Location: Adviser_Calendar.php?classCode=$code&editsuccess");
        }
        else
        {
             header("Location: Adviser_Calendar.php?classCode=$code&editunsuccess");
        }

       
    }

?>


<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Student Mentoring Schedule</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <link rel = "stylesheet" href = "../css/adviser_dashhome.css">
    
    <style>
        
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

       
        
        #schedule
        {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #schedule td, #schedule th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #schedule tr:nth-child(even){background-color: #f2f2f2;}

        #schedule th 
        {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: maroon;
        color: white;
        text-align: center;
        white-space: nowrap;
        }
        
        #schedule td
        {
            background-color: white;
            color: black;
            text-align: center;
            white-space: break-spaces;
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
<div class = "container-fluid" style = "margin-left: -15px"> 

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
        <div class = "topnavbar" style="z-index:1;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">

            <div class="topnavbar-right" id="topnav_right">
            <a target = "_self" href="../Logout.php?Logout=<?php echo $faculty ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->
        <br><br><br><br>
        

        
                <!-- Button trigger modal -->
        <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Scheduled Mentoring/Monitoring </h1>
        <hr style="background-color:maroon;border-width:2px;">
        <button type="button" class="btn btn-primary" style="background-color:maroon;float:right;" data-toggle="modal" data-target="#myModal"><b>+ CREATE SCHEDULE</b> </button>
        <button type="button" class="btn btn-danger" style="background-color:maroon;float:right;" data-toggle="modal" data-target="#historyModal"><i class="fa fa-history"></i> HISTORY </button>
        <br><br>
        
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
            title: 'Mentoring Schedule has successfully saved.'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['uploadunsuccess']))
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
            title: 'Mentoring Schedule has not successfully saved.'
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
            title: 'Mentoring Schedule has successfully edited.'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['editunsuccess']))
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
            title: 'Mentoring Schedule has not successfully edited.'
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
            title: 'Mentoring Schedule has successfully deleted.'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['deleteunsuccess']))
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
            title: 'Mentoring Schedule has not successfully deleted.'
        })
        </script>
        <?php
            }
            ?>

        <!-- Modal for adding schedule -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog modal-lg" id="mentoring">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><b>MENTORING SCHEDULE</b> </h5><p style="text-align:right;"><?php echo "$course" . " $year_section"; ?></p> 
                        
                    </div>

                    <form method="POST">
                        <div class="modal-body">

                        <style type="text/css">
                            .d-none{
                                display: none;
                                    }
                            </style>  

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <div class="md-title">
                                        <label for = ""><b>Type of Meeting</b></label>
                                        <select name = "option" class = "form-control" />
                                        <option value="Face-to-face">Face-to-face</option>
                                        <option value="Online">Online</option>
                                        <option value="Hybrid">Hybrid</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="" class="form-label"><b>Select Company: </b></label>
                                    <select name = "company" class = "form-control" />
                                        <?php
                                            $company_none = "";
                                            $data_query = mysqli_query($conn, "SELECT DISTINCT company FROM student_record WHERE class_code = '$code'");
                                            while($row=mysqli_fetch_array($data_query))
                                            {
                                                $company = $row['company'];
                                                if($company === "")
                                                {
                                                    $company_none = "No company";
                                                    echo"<option value='$company_none'>$company_none</option>";
                                                }
                                                else
                                                {
                                                    echo"<option value='$company'>$company</option>";
                                                }
                                                

                                                
                                            }
                                        ?>
                                    </select>
                                </div>
                                </div>
                            <div class="form-group">
                                <label for=""><b>Title</b></label>
                                <input type="text" name="title" class="form-control" placeholder="Title" required>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="" class="form-label"><b>Notes</b></label>
                                <textarea name="instruction" class="form-control" placeholder="Notes" rows="3" required></textarea>
                            </div>
                            <br>

                            
                           
                            <br>
                             
                             <div class="form-row">
                                
                             <div class="form-group col-md-4">
                                <label for="" class="form-label"><b>Date</b></label>
                                <input class="form-control form-control-sm" id="grd" type="date" name="due_date" aria-label=".form-control-sm example" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                             <div class="form-group col-md-4">
                                <label for="" class="form-label"><b>Start  Time</b></label>
                                <input class="form-control form-control-sm" id="start_time" type="time" name="start_time" aria-label=".form-control-sm example"  required>
                            </div>
                            <br>

                            <div class="form-group col-md-4">
                                <label for="grd" class="form-label"><b>End  Time</b></label>
                                <input class="form-control form-control-sm" id="end_time" type="time" name="end_time" aria-label=".form-control-sm example" min=""  disabled>
                            </div>
                            <br>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                            $(document).ready(function() {
                            $('#start_time').change(function() {
                                $('#end_time').prop('disabled', false);
                                $('#end_time').prop('min', $('#start_time').val());
                            });
                            });
                            </script>
                            </div>
                        </div> <!--end of modal-body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="post" class="btn btn-primary" style="background-color:maroon;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for History -->
        <div class="modal fade" id="historyModal">
            <div class="modal-dialog modal-lg" style="min-width:80%;">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><b>MENTORING SCHEDULE HISTORY</b> </h5><p style="text-align:right;"><?php echo "$course" . " $year_section"; ?></p>   
                    </div>

                        <div class="modal-body">
                        <div class="table table-responsive">
                        <table id ="schedule">
                            <thead>
                                <tr>
                                    <th style="display:none;"></th>
                                    <th scope = "col">Title</th>
                                    <th scope = "col">Type</th>
                                    <th scope = "col">Notes</th>
                                    <th scope = "col">Scheduled Date</th>
                                    <th scope = "col">Company</th>
                                    <th scope = "col">Start Time</th>
                                    <th scope = "col">End Time</th>
                                    <th scope = "col">Student</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <!--Changes -->
                                <?php
                                    $query = mysqli_query($conn, "SELECT * FROM adviser_schedule WHERE facultynum = '$faculty' AND class_code = '$code' AND remove = 'no'");

                                    if(mysqli_num_rows($query) > 0)
                                    {
                                        
                                        while($row = mysqli_fetch_array($query))
                                        {
                                            
                                            $id = $row['id'];
                                            $sched_end = $row['end_time'];
                                            $duedate = $row['date'];
                                            date_default_timezone_set('Asia/Manila');
                                            $currentday = date('Y-m-d');
                                            $currenttime = date('h:i:s A');
                                            $comp = $row['company'];
                                            $title = $row['title'];
                                            $instruction = $row['instruction'];
                                            $company = $row['company'];
                                            $start = $row['start_time'];
                                            $end = $row['end_time'];
                                            $date = $row['date'];
                                            $formatted_date = date("Y-m-d", strtotime($row['date']));
                                            $formatted_start = date("H:i:s A", strtotime($row['start_time']));
                                            $formatted_end = date("H:i:s A", strtotime($row['end_time']));
                                            
                                        
                                            if($currentday >= $date)
                                            {
                                            $stud_query = mysqli_query($conn, "SELECT * FROM student_record WHERE company = '$comp' AND class_code = '$code'");
                                            
                                            ?>
                                            <tr>
                                                <td class="sched_id" style='display: none;'><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['title']; ?></td>
                                                <td><?php echo $row['type']; ?></td>
                                                <td><?php echo $row['instruction']; ?></td>
                                                <td><?php echo date("M j Y", strtotime($row['date'])); ?></td>
                                                <td><?php echo $row['company']; ?></td>
                                                <td><?php echo date("h:i A", strtotime($row['start_time'])); ?></td>
                                                <td><?php echo date("h:i A", strtotime($row['end_time'])); ?></td>
                                                <td><?php  if(mysqli_num_rows($stud_query) > 0)
                                                {
                                                    while($stud_row = mysqli_fetch_array($stud_query)){
                                                        echo $stud_row['name']; echo ', <br> ';} ?><?php

                                                    }
                                                
                                                ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        

                                    }
                                    }
                                    
                                    
                                    ?>
                            </tbody>
                        </table>
                        </div>
                        </div> <!--end of modal-body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main table for mentoring schedule -->
        <div class="card">
        <div class="card-body">
        <div class="table table-responsive">
        <table id ="schedule">
            <thead>
                <tr>
                    <th style="display:none;"></th>
                    <th scope = "col">Title</th>
                    <th scope = "col">Type</th>
                    <th scope = "col">Notes</th>
                    <th scope = "col">Scheduled Date</th>
                    <th scope = "col">Company</th>
                    <th scope = "col">Start Time</th>
                    <th scope = "col">End Time</th>
                    <th scope = "col">Student</th>
                    <th scope = "col">Action</th>
                    
                    
                </tr>
            </thead>
            <tbody>
                <!--Changes -->
                <?php
                    $query = mysqli_query($conn, "SELECT * FROM adviser_schedule WHERE facultynum = '$faculty' AND class_code = '$code' AND remove = 'no'");

                    if(mysqli_num_rows($query) > 0)
                    {
                        
                        while($row = mysqli_fetch_array($query))
                        {
                            
                            $id = $row['id'];
                            $sched_end = $row['end_time'];
                            $duedate = $row['date'];
                            date_default_timezone_set('Asia/Manila');
                            $currentday = date('Y-m-d');
                            $currenttime = date('H:i:s');
                            $comp = $row['company'];
                            $title = $row['title'];
                            $instruction = $row['instruction'];
                            $company = $row['company'];
                            $start = $row['start_time'];
                            $end = $row['end_time'];
                            $date = $row['date'];
                            $formatted_date = date("Y-m-d", strtotime($row['date']));
                            $formatted_start = date("H:i:s A", strtotime($row['start_time']));
                            $formatted_end = date("H:i:s A", strtotime($row['end_time']));
                            
                            if($currentday <= $date)
                            {
                            $stud_query = mysqli_query($conn, "SELECT * FROM student_record WHERE company = '$comp' AND class_code = '$code' ORDER BY name ASC");
                            
                            ?>
                            <tr>
                                <td class="sched_id" style='display: none;'><?php echo $row['id']; ?></td>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['type']; ?></td>
                                <td><?php echo $row['instruction']; ?></td>
                                <td><?php echo date("M j Y", strtotime($row['date'])); ?></td>
                                <td><?php echo $row['company']; ?></td>
                                <td><?php echo date("H:i A", strtotime($row['start_time'])); ?></td>
                                <td><?php echo date("H:i A", strtotime($row['end_time'])); ?></td>
                                <td style="white-space:nowrap;"><?php  if(mysqli_num_rows($stud_query) > 0)
                                {
                                    while($stud_row = mysqli_fetch_array($stud_query)){
                                    echo $stud_row['name']; echo ', <br> ';} ?><?php

                                }
                                
                                ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button Group">                            
                                            <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn" data-bs-toggle='modal' data-bs-target='#editScheduleModal<?php echo $id; ?>'><i class='fa fa-edit' ></i></a>
                                           
                                            <a href="#" button type="button" class = "userinfo btn btn-danger delete_btn"><i class='fa fa-trash'></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        

                    }
                    }
                    
                    ?>
            </tbody>
        </table>
            <!-- Edit Modal -->
                                        <div class="modal fade" id="editScheduleModal<?php echo $id ?>" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" id="edit_mentoring">
                                                    <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><b>MENTORING SCHEDULE</b> </h5><p style="text-align:right;"><?php echo "$course" . " $year_section"; ?></p>   
                                                            </div>

                                                            <form method="POST">
                                                                <div class="modal-body">

                                                                    <input type = "hidden" name = "edit_id" value = "<?php echo $id; ?>">

                                                                    <style type="text/css">
                                                                    .d-none{
                                                                        display: none;
                                                                            }
                                                                    </style>  

                                                                    <div class="form-row">

                                                                        <div class="form-group col-md-6">
                                                                            <div class="md-title">
                                                                                <label for = ""><b>Type of Meeting</b></label>
                                                                                <select name = "option" class = "form-control" required/>
                                                                                <option value="Face-to-face">Face-to-face</option>
                                                                                <option value="Online">Online</option>
                                                                                <option value="Hybrid">Hybrid</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="" class="form-label"><b>Select Company: </b></label>
                                                                            <select name="company" value = "<?php echo $company; ?>" id = "edit_company"  class = "form-control" required>
                                                                                <?php
                                                                                    $data_query = mysqli_query($conn, "SELECT DISTINCT company FROM student_record WHERE class_code = '$code'");
                                                                                    while($row=mysqli_fetch_array($data_query))
                                                                                    {
                                                                                        $company = $row['company'];
                                                                                        if($company === "")
                                                                                        {
                                                                                            $company_none = "No company";
                                                                                            echo"<option value='$company_none'>$company_none</option>";
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            echo"<option value='$company'>$company</option>";
                                                                                        }


                                                                                        
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                            </div>
                                                                        </div>
                                                                    

                                                                    <div class="form-group">
                                                                        <label for=""><b>Title</b></label>
                                                                        <input type="text" name="title" id = "edit_title" class="form-control" value = "<?php echo $title ?>" placeholder="<?php echo $title ?>" required>
                                                                    </div>
                                                                    <br>

                                                                    <div class="form-group">
                                                                        <label for="" class="form-label"><b>Instructions</b></label>
                                                                        <textarea name="instruction" id = "edit_instruction" class="form-control" placeholder="<?php echo $instruction; ?>" rows="3" required><?php echo $instruction ?></textarea>
                                                                    </div>
                                                                    <br>

                                                                    <div class="form-group">
                                                                        <label for="" class="form-label"><b>Date</b></label>
                                                                        <input class="form-control form-control-sm" id="grd" type="date" name="due_date" id = "edit_date" value = "<?php echo $formatted_date ?>" aria-label=".form-control-sm example" required>
                                                                    </div>
                                                                    <br>
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="" class="form-label"><b>Start  Time</b></label>
                                                                        <input class="form-control form-control-sm" id="start_time" type="time" name="start_time" value = "<?php echo $formatted_start ?>" id = "edit_start" aria-label=".form-control-sm example" required>
                                                                    </div>
                                                                    <br>
                                                                    
                                                                    <div class="form-group col-md-6">
                                                                        <label for="grd" class="form-label">End  Time</label>
                                                                        <input class="form-control form-control-sm" id="end_time" type="time" name="end_time" value = "<?php echo $formatted_end ?>" min="<?php echo $formatted_start ?>" id = "edit_end" aria-label=".form-control-sm example" required>
                                                                    </div>
                                                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                                    <script>
                                                                    $(document).ready(function() {
                                                                    $('#start_time').change(function() {
                                                                        $('#end_time').prop('disabled', false);
                                                                        $('#end_time').prop('min', $('#start_time').val());
                                                                    });
                                                                    });
                                                                    </script>
                                                                </div>
                                                                    <br>

                                                                </div> <!--end of modal-body-->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" name="edit_sched" class="btn btn-primary">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                                    
                                                </div>
                
                <!-- Modal DELETE DATA-->
                <div class="modal fade" id="deleteCalendarModal" tabindex="-1" role="dialog" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" style='max-width: 80%;' role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id = "deleteStudentModalLabel"><b>CONFIRMATION</b></h5>
                                </button>
                            </div>
                            <form action = "Adviser_Calendar.php" method = "POST">
                                <div class="modal-body">
                                    <input type = "hidden" name = "sched_id" id = "delete_id">
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
                                                                        
                                                                            </div>
        </div>
        </div>
        </div>
    </div>       
    
         
</div>
<script>
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
            function handleScreenSizeChange(tabletSize) {
                if (tabletSize.matches) 
                {
                    document.getElementById('topnav_right').classList.remove('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "12px";
                    document.getElementById('mentoring').style.maxWidth = "80%";
                    document.getElementById('edit_mentoring').style.maxWidth = "80%";
                } 
                else
                {
                    document.getElementById('topnav_right').classList.add('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "13.5px";
                    document.getElementById('mentoring').style.maxWidth = "";
                    document.getElementById('edit_mentoring').style.maxWidth = "";
                }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>
        <script>
            $(document).ready(function() {

                $('.delete_btn').click(function(e) {
                e.preventDefault();

                var sched_id = $(this).closest('tr').find('.sched_id').text();
                $('#delete_id').val(sched_id);
                $('#deleteCalendarModal').modal('show');
                });

                $('.edit_btn').click(function(e) {
                e.preventDefault();

                var comp_id = $(this).closest('tr').find('.sched_id').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_Calendar.php",
                data: {
                    'checking_editbtn': true,
                    'id': id,
                },
                success: function (response) {
                    $.each(response,function (key, value){
                    $('#edit_id').val(value['id']);
                    $('#edit_title').val(value['title']);
                    $('#edit_instruction').val(value['instruction']);
                    $('#edit_date').val(value['date']);
                    
                    $('#edit_company').val(value['company']);
                    $('#edit_start').val(value['start_time']);
                    $('#edit_end').val(value['end_time']);
                    
                    });
                    $('#editScheduleModal').modal('show');
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

            $(document).ready(function()
            {
                $('#schedule').DataTable(
                    {
                        Menu:[
                        [10, 15, 20, 30, 50, -1],
                        [10, 15, 20, 30, 50, 'All'],
                        ],
                    });
            
            });

            
        </script>
</body>
</html>