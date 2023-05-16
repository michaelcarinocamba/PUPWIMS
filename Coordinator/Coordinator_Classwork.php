<?php
//DONE
    session_start();
    include "../db_conn.php";
    if(!isset($_SESSION['coordinator']))
    {
        header("Location: Coordinator_Login.php?LoginFirst");
    }



    $coordnum = $_SESSION['coordinator'];
    $name = $_SESSION['name'];
    date_default_timezone_set('Asia/Singapore');
    $date_today = date("Y-m-d\TH:i");

    if(isset($_POST["post"])){
        
        $instruction = $_POST['instruction'];
        
        $title = $_POST['title'];
        $file = $_FILES['file'];
        $points = $_POST['points'];
        $due_date = $_POST['due_date'];
        date_default_timezone_set('Asia/Singapore');
        $date_added = date("Y-m-d H:i:s");
        

        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'csv');


        if ($instruction != "" && $fileName == "") 
        {
            $query = mysqli_query($conn, "INSERT INTO coordinator_classwork VALUES ('', '$coordnum', '$name', '$title', '$instruction','$date_added', '$due_date', '', '','','','no')");
            $notif_desc = "has created activities in classwork. [OJT Coordinator]";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Adviser_CoordClasswork.php";
            $getadviser = mysqli_query($conn, "SELECT * FROM adviser_info");
            if(mysqli_num_rows($getadviser) > 0)
            {
                while($adviser = mysqli_fetch_array($getadviser))
                {
                    $adv_name = $adviser['name'];
                    $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$adv_name', '', '$date_time_now','no')");
                }
            }
            header("Location: Coordinator_Classwork.php?uploadsuccess");
        }

        else if($fileName !=  "")
        {
            if(in_array($fileActualExt, $allowed)) 
            {
                if($fileError === 0) 
                {
                    if($fileSize < 50000000) 
                    {
            
                        $fileDestination = '../uploads/'.$fileName;
                        move_uploaded_file($fileTmpName,$fileDestination);

                        $sql = "INSERT INTO coordinator_classwork VALUES ('', '$coordnum', '$name',  '$title', '$instruction','$date_added', '$due_date', '$fileName', '$fileDestination','','','no')";
                        $notif_desc = "has created activities in classwork. [OJT Coordinator]";
                        $date_time_now = date("Y-m-d H:i:s");
                        $notif_link = "Adviser_CoordClasswork.php";
                        $getadviser = mysqli_query($conn, "SELECT * FROM adviser_info");
                        if(mysqli_num_rows($getadviser) > 0)
                        {
                            while($adviser = mysqli_fetch_array($getadviser))
                            {
                                $adv_name = $adviser['name'];
                                $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$adv_name', '', '$date_time_now','no')");
                            }
                        }
                        mysqli_query($conn, $sql);
                        
                        
                        header("Location:  Coordinator_Classwork.php?uploadsuccess");
                    } 
                    
                    else {
                        header("Location:  Coordinator_Classwork.php?uploadunsuccess");
                    }

                }
                
                else {
                    header("Location:  Coordinator_Classwork.php?uploadunsuccess");
                }

            } 
            
            else {
                header("Location:  Coordinator_Classwork.php?uploadunsuccess");
            }

        }
    }

    if(isset($_POST["edit"])){
        $get_id = $_POST['get_id'];
        $instruction = $_POST['instruction'];
        $instruction = strip_tags($instruction);
        $instruction = mysqli_real_escape_string($conn, $instruction);
        $check_empty = preg_replace('/\s+/', '', $instruction);
        
        $title = $_POST['title'];
        $file = $_FILES['file'];
        $due_date = $_POST['due_date'];
        date_default_timezone_set('Asia/Singapore');
        $date_added = date("Y-m-d H:i:s");
        

        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'csv');


        if ($check_empty != "" && $fileName == "") 
        {
            $query = mysqli_query($conn, "UPDATE coordinator_classwork SET title = '$title', instruction = '$instruction', due_date = '$due_date' WHERE assignment_id = '$get_id'");
            $notif_desc = "has updated activities in classwork. [OJT Coordinator]";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Adviser_CoordClasswork.php";
            $getadviser = mysqli_query($conn, "SELECT * FROM adviser_info");
            if(mysqli_num_rows($getadviser) > 0)
            {
                while($adviser = mysqli_fetch_array($getadviser))
                {
                    $adv_name = $adviser['name'];
                    $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$adv_name', '', '$date_time_now','no')");
                }
            }
            header("Location: Coordinator_Classwork.php?updatesuccess");
        }

        else if($fileName !=  "")
        {
            if(in_array($fileActualExt, $allowed)) 
            {
                if($fileError === 0) 
                {
                    if($fileSize < 50000000) 
                    {
            
                        $fileDestination = '../uploads/'.$fileName;
                        move_uploaded_file($fileTmpName,$fileDestination);

                        $query = mysqli_query($conn, "UPDATE coordinator_classwork SET title = '$title', instruction = '$instruction', due_date = '$due_date', files = '$fileName', files_destination = '$fileDestination' WHERE assignment_id = '$get_id'");
                        $notif_desc = "has updated activities in classwork. [OJT Coordinator]";
                        $date_time_now = date("Y-m-d H:i:s");
                        $notif_link = "Adviser_CoordClasswork.php";
                        $getadviser = mysqli_query($conn, "SELECT * FROM adviser_info");
                        if(mysqli_num_rows($getadviser) > 0)
                        {
                            while($adviser = mysqli_fetch_array($getadviser))
                            {
                                $adv_name = $adviser['name'];
                                $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$adv_name', '', '$date_time_now','no')");
                            }
                        }
                        mysqli_query($conn, $sql);
                        
                        
                        header("Location:  Coordinator_Classwork.php?updatesuccess");
                    } 
                    
                    else {
                        header("Location:  Coordinator_Classwork.php?updateunsuccess");
                    }

                }
                
                else {
                    header("Location:  Coordinator_Classwork.php?updateunsuccess");
                }

            } 
            
            else {
                header("Location:  Coordinator_Classwork.php?updateunsuccess");
            }

        }
    }


    if(isset($_REQUEST['delete']))
    {
        $id = $_REQUEST['get_id'];
        
        $query = mysqli_query($conn, "SELECT * FROM coordinator_classwork WHERE assignment_id = '$id'");
        if($query)
        {
            $deletequery = mysqli_query($conn, "UPDATE coordinator_classwork SET remove = 'yes' WHERE assignment_id = '$id'");
            header("Location:  Coordinator_Classwork.php?deletesuccess");
        }
        else
        {
            header("Location:  Coordinator_Classwork.php?deleteunsuccess");
        }
            
        
        
    }

    if(isset($_REQUEST['permanently_delete']))
    {

        $id = $_REQUEST['get_id'];
        $query = mysqli_query($conn, "SELECT * FROM coordinator_classwork WHERE assignment_id = '$id'");
        if($query)
        {
            
            $deletequery = mysqli_query($conn,  "DELETE FROM coordinator_classwork WHERE assignment_id = '$id'");
            $deletequery = mysqli_query($conn, "DELETE FROM adviser_classwork WHERE assignment_id = '$id'");
            header("Location:  Coordinator_Classwork.php?deletesuccess");
        }
        else
        {
            header("Location:  Coordinator_Classwork.php?deleteunsuccess");
        }
        
    }

    if(isset($_REQUEST['retrieve_btn']))
        {

        $id = $_REQUEST['get_id'];
        $query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$id' AND class_code = '$code'");
        if($query)
        {
            
            $retirevequery = mysqli_query($conn, "UPDATE coordinator_classwork SET remove = 'no' WHERE assignment_id = '$id'");
            header("Location: Coordinator_Classwork.php?retrievesuccess");
        }
        else
        {
            header("Location: Coordinator_Classwork.php?retrieveunsuccess");
        }
    }


?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Coordinator Dashboard - Classwork</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel = "stylesheet" href = "../css/adviser_dashclasswork.css">
    <script src="../ckeditor/ckeditor.js"></script>
    <style>
        .modal {
            
            left: 0;
            top: -5%;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            }

            /* Modal Content */
        .modal-content {
            margin: 10% auto 0; /* 10% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 100%; /* Could be more or less, depending on screen size */
            height: auto; /* Set height to auto to make it responsive */
        }
        [data-tooltip] {
        font-size: 13px;
        font-style: italic;
        }

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

       
        .card-body
        {
            overflow: hidden;
            text-overflow: ellipsis;
            
        }
        .card-body:hover {
        overflow: visible;
        white-space: normal;
        text-overflow: clip;
        }
        
        #table
        {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            overflow-x: auto;
            padding: 0;
            min-width: 100%;
        }

        #table td, #table th 
        {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            max-width: auto;
            
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table th 
        {
            padding-top: 15px;
            padding-bottom: 15px;
            text-align: left;
            background-color: maroon;
            color: white;
            text-align: center;
            /* white-space: nowrap; */
        }

        .card-grid 
        {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card 
        {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
            
        }
        .card-body {
        font-size: 14px;
        line-height: 1.5;
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
<body>
    
<div class='modal fade' id='mobileModal' tabindex='-1' role='dialog' aria-labelledby='mobileModalLabel' aria-hidden='true'>
        <div class='modal-dialog  modal-dialog' role='document'>
            <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id = 'mobileModalLabel'><b>MOBILE USER</b></h5>
            </div>
            
            <div class='modal-body'>
            
                <h5>  To have better experience, please read the instruction below: </h5>
                <h6> 1. Go to your chrome settings and Turn on/Activate "Desktop site".</h6>
            </div>
            <div class='modal-footer'>
                
            
            </div>
            
            </div>
        </div>
        </div>
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


        <div class = "main">
            <div class = "topnavbar" style="z-index: 1;">
                
            <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">

                <div class="topnavbar-right" id="topnav_right">
                    <a target = "_self" href="../Logout.php?Logout=<?php echo $coordnum ?>" > 
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
                title: 'Successfully Added'
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
                title: 'There was an error uploading your file.'
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
                title: 'Successfully Deleted'
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
                title: 'Unsuccessfully Deleted'
            })
            </script>
      <?php
        }
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
                title: 'Successfully Updated'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['updateunsuccess']))
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
                title: 'Unsuccessfully Updated'
            })
            </script>
      <?php
        } if(isset($_REQUEST['retrievesuccess']))
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
            title: 'Retrieved successfully'
        })
        </script>


        <?php
            }
            if(isset($_REQUEST['retrieveunsuccess']))
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
            title: 'There was an error in retrieving the data'
        })
        </script>

      <?php
        } 
        
        ?>


            <div id="createAssign">
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Announcement for Adviser</h1>
            
            <hr style="background-color:maroon;border-width:2px;">
            <button type="button" class="btn btn-primary" style="float:right;background-color:maroon;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class = "fa fa-plus fa-1x"></i> &nbsp;
                   <b>CREATE ASSIGNMENT/ANNOUNCEMENT</b> 
                </button>
            <button type="button" class="btn btn-danger" style="background-color:maroon;float:left;" data-bs-toggle="modal" data-bs-target="#classwork_delete"><b><i class="fa fa-trash"></i>&emsp;DELETED</b> </button>
                <!-- Button trigger modal -->
                 <br>

                    <!-- MODAL FOR TRASH  -->
                    <div class="modal fade" id="classwork_delete" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl" id ="trash">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title fs-5" id="exampleModalLabel">Deleted Assignment/Announcement</h3>
                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="table table-responsive">
                                        <table id="table">
                                        <thead>
                                            <tr>
                                            <th style='display: none;'></th>
                                            <th style='display: none;'></th>
                                            <th style="white-space: nowrap;">Assignment Title</th>
                                            <th>Due Date</th>
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $query1 = mysqli_query($conn, "SELECT * FROM coordinator_classwork WHERE remove = 'yes'");

                                                if(mysqli_num_rows($query1)> 0){
                                                    while($row = mysqli_fetch_array($query1))
                                                    {
                                                        ?>
                                                        <tr> 
                                                        <td class="as_id" style='display: none;'><?php echo $row['assignment_id']; ?></td>
                                                        <td class="classCode" style='display: none;'><?php echo $row['coordinator_num']; ?></td>
                                                        <td><?php echo $row['title']; ?></td>
                                                        <td><?php echo $row['due_date']; ?></td>  
                                                        <td>
                                                        <div class="btn-group" role="group" aria-label="Button Group">
                                                            <a href="#" button type="button" class = "userinfo btn btn-success retrieve_btn btn-sm"><i class='fa fa-undo'></i></button>
                                                            <a href="#" button type="button" class = "userinfo btn btn-danger permanently_delete btn-sm"><i class='fa fa-trash'></i></a>
                                                        </div>
                                                        </td>
                                                        </div>  
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
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                        </div>


                        <!-- modal for retrieve -->

                        <div class="modal fade" id="retrieve_studentModal" tabindex="-1" role="dialog" aria-labelledby="retrieve_studentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id = "retrieve_studentModalLabel"><b>CONFIRMATION</b></h5>
                                    </button>
                                </div>
                                <form method = "POST">
                                <div class="modal-body">
                                <input type = "hidden" name = "get_id" id = "retrieve_id">
                                    <h4> Do you want to <b> retrieve </b> this information? </h4>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name = "retrieve_btn" class="btn btn-success">Confirm</button>
                                </div>
                                </form>
                                </div>
                            </div>
                            </div>


                            <!-- Modal PERMANENTLY DELETE DATA-->
                        <div class="modal fade" id="permanent_deleteStudentModal" tabindex="-1"  role="dialog" aria-labelledby="permanent_deleteStudentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id = "permanent_deleteStudentModalLabel"> <b>CONFIRMATION</b></h5>
                                    </button>
                                </div>
                                <form method = "POST">
                                <div class="modal-body">
                                <input type = "hidden" name = "get_id" id = "perm_delete_id">
                                    <h4> Do you want to <b>permanently delete</b> this information? </h4>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name = "permanently_delete" class="btn btn-danger">Confirm</button>
                                </div>
                                </form>
                                </div>
                            </div>
                            </div>


                <script>
                 $(document).ready(function() {
                
                $('.permanently_delete').click(function(e) {
                e.preventDefault();

                var as_id = $(this).closest('tr').find('.as_id').text();
                $('#perm_delete_id').val(as_id);
                $('#permanent_deleteStudentModal').modal('show');
                });

                $('.retrieve_btn').click(function(e) {
                e.preventDefault();

                var as_id = $(this).closest('tr').find('.as_id').text();
                $('#retrieve_id').val(as_id);
                $('#retrieve_studentModal').modal('show');
                });
            });

            </script>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" id="create_ass">
                    <div class="modal-content">
                    
                    <!--modal header-->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create an Assignment/Announcement</h5>
                            
                    </div>

                    <!--modal body-->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body p-5">

                            <!--this is where the text areas are going to be placed-->
                            <div class="md-title">
                                <label for="exampleFormControlInput1" name="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" id="exampleFormControlInput1" placeholder="Title">
                            </div>
                            <br>

                            <div class="md-textarea">
                                <label for="exampleFormControlTextarea1" class="form-label">Instructions</label>
                                <textarea name="instruction" class="form-control" id="instruction" placeholder="Instructions" rows="3"></textarea>
                            </div>

                            <script>
                                CKEDITOR.replace('instruction');
                            </script>


                            <div class="md-file" data-tooltip='Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX, CSV. Maximum File Size accepted is 50 MB.'>
                                <label for='formFile' class='form-label'></label>
                                <input class="form-control" type="file" name="file" id="formFile">
                                <style>
                        
                                .md-file:hover:after {
                                content:attr(data-tooltip);
                                }
            
                                </style>
                            </div>
                            <br>


                            <div class="md-grade">
                                <label for="grd" class="form-label">Due Date</label>
                                <input class="form-control form-control-sm" id="grd" type="datetime-local" name="due_date" min="<?php echo $date_today ?>" aria-label=".form-control-sm example">
                            </div>
                            <br>

                        </div> <!--end of modal-body-->

                        <!--modal footer-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="post" class="btn btn-primary">Submit</button>
                        </div>

                        </div> <!--end of modal dialog-->
                    </div>


                    </form> <!--end of form-->

            
        </div> <!-- end of main -->
        
        <?php

            $data_query = mysqli_query($conn, "SELECT * FROM coordinator_classwork WHERE coordinatornum = '$coordnum' AND remove = 'no' ORDER BY assignment_id DESC");
            
            if(mysqli_num_rows($data_query)>0)
            {
                $str = "";
                    while($row=mysqli_fetch_array($data_query))
                    {
                        $id = $row['assignment_id'];
                        $title = $row['title'];
                        $name = $row['name'];
                        $instruction = $row['instruction'];
                        $date_added = $row['date_added'];
                        $date_added_display = date("M j Y, H:i:s A", strtotime($row['date_added']));
                        $due_date = $row['due_date'];
                        $due_date_display =  date("M j Y, H:i:s A", strtotime($row['due_date']));
                        $file = $row['files'];
                        $image = $row['files_destination'];
                        $script = "<script>
                        CKEDITOR.replace('edit_instruction$id');
                    </script>";
                
                        $fileExt = explode('.', $file);
                        $fileActualExt = end($fileExt);
                        $allowed  = array('jpg','jpeg','png');
                        $fileDiv = "";

                        if (in_array($fileActualExt, $allowed)) 
                        {
                            $fileDiv = "<div id='postedFile'>
                            <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:150px; height:auto;min-height:50%;max-height:250px;'>
                            </div>";
                        }

                            ?><!--end of first php -->

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
                                    <div class='card-body stylebox'>
                                        <div onClick='javascript:toggle$id(); myFunction(this)' >
                                            <div class='card-body stylebox' style='background-color: maroon'>
                                                <div class='row'>
                                                    <div class='col-8'>
                                                        <b style = 'font-weight: 600; color:white;'>Assignment Title: $title </b>
                                                    </div>


                                                    <div class='col-4' style='color:white;'>
                                                        Due Date: $due_date_display    
                                                    </div>
                                                    <br>
                                            
                
                                                </div>

                                            </div>
                                        </div>
                                    

                                    <div class='card-body display' id='toggleClass$id'>";
                                        if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                        { $str .="
                                                <div class = 'row text'>
                                                    <div class = 'col-4'> Prof. $name  </div>
                                                    <div class = 'col-6'> $date_added_display  </div><br>
                                                    
                                
                                                    <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction <br> </div>
                                                    <br><br><div class = 'col-12 '> $fileDiv  </div>
                                                </div> <br>
                                            ";
                                        }
                                        else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                        {
                                            $str .="<div class = 'row text'>
                                                        <div class = 'col-4'> Prof. $name  </div>
                                                        <div class = 'col-6'> $date_added_display  </div>  <br>
                                                        <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction <br> </div> 
                                                        <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                                                        
                                                    </div> <br>"; 
                                        }
                                        else
                                        {
                                            $str .="<div class = 'row text'>
                                                        <div class = 'col-4'> Prof. $name  </div>
                                                        <div class = 'col-6'> $date_added_display   </div>  <br>
                                                        <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction  </div>

                                                    </div> <br>"; 
                                        }
                                        $str .="
                                                
                                                    <!-- Button trigger modal -->
                                                    <button type='button' class='btn button hbutton' data-bs-toggle='modal' data-bs-target='#viewModal$id'>
                                                        View Adviser Submission
                                                    </button>
                                                    <button type='button' class='btn button hbutton' style='float:right;' data-bs-toggle='modal' data-bs-target='#editModal$id'>
                                                        Edit
                                                    </button>
                                                    <button type='button' class='btn button hbutton' style='float:right;' data-bs-toggle='modal' data-bs-target='#deleteModal$id'>
                                                        Delete
                                                    </button>
                                                    <br><br>
                                                    
                                                    <hr class='dashed'>";
                                                    
                                                    ?>
                                                    <script type='text/javascript'>
                                                        function toggleComment<?php echo $id; ?>() {
                                                            var element = document.getElementById("toggle<?php echo $id; ?>");
                                                            
                                                
                                                            if (element.style.display == "block")
                                                                element.style.display = "none";
                                                                
                                                            else
                                                                element.style.display = "block";
                                                                $(document).ready(function(){
                                                                            document.getElementById("toggleText").style.display = "none";
                                                                        });
                                                                
                                                        }
                                                    </script>

                                                    <?php

                                                    if(isset($_POST['postComment' . $id]))
                                                    {
                                                        $post_body = $_POST['comment_body'];
                                                        $post_body = mysqli_escape_string($conn, $post_body);
                                                        $date_time_now = date("Y-m-d H:i:s");
                                                        $insert_comment = mysqli_query($conn, "UPDATE coordinator_classwork SET post_content = CONCAT(post_content,'$post_body <br>'), post_date_added = '$date_time_now' WHERE assignment_id = '$id'");
                                                    }
                                                    $get_comments = mysqli_query($conn, "SELECT * FROM coordinator_classwork WHERE assignment_id='$id' AND post_content != '' ORDER BY assignment_id ASC");
                                                    $count = mysqli_num_rows($get_comments);
                                                    if($count != 0)
                                                    {
                                                        while($comment = mysqli_fetch_array($get_comments))
                                                        {
                                                            $name = $comment['name'];
                                                            $post_content = $comment['post_content'];
                                                            $post_date = $comment['post_date_added'];

                                                                //Timeframe
                                                            $date_time_now = date("Y-m-d H:i:s");
                                                            $start_date = new DateTime($post_date); //Time of post
                                                            $end_date = new DateTime($date_time_now); //Current time
                                                            $interval = $start_date->diff($end_date); //Difference between dates 
                                                            if ($interval->y >= 1) {
                                                                if ($interval == 1)
                                                                    $time_message = $interval->y . " year ago"; //1 year ago
                                                                else
                                                                    $time_message = $interval->y . " years ago"; //1+ year ago
                                                            } else if ($interval->m >= 1) {
                                                                if ($interval->d == 0) {
                                                                    $days = " ago";
                                                                } else if ($interval->d == 1) {
                                                                    $days = $interval->d . " day ago";
                                                                } else {
                                                                    $days = $interval->d . " days ago";
                                                                }


                                                                if ($interval->m == 1) {
                                                                    $time_message = $interval->m . " month, " . $days;
                                                                } else {
                                                                    $time_message = $interval->m . " months, " . $days;
                                                                }
                                                            } else if ($interval->d >= 1) {
                                                                if ($interval->d == 1) {
                                                                    $time_message = "Yesterday";
                                                                } else {
                                                                    $time_message = $interval->d . " days ago";
                                                                }
                                                            } else if ($interval->h >= 1) {
                                                                if ($interval->h == 1) {
                                                                    $time_message = $interval->h . " hour ago";
                                                                } else {
                                                                    $time_message = $interval->h . " hours ago";
                                                                }
                                                            } else if ($interval->i >= 1) {
                                                                if ($interval->i == 1) {
                                                                    $time_message = $interval->i . " minute ago";
                                                                } else {
                                                                    $time_message = $interval->i . " minutes ago";
                                                                }
                                                            } else {
                                                                if ($interval->s < 30) {
                                                                    $time_message = "Just now";
                                                                } else {
                                                                    $time_message = $interval->s . " seconds ago";
                                                                }
                                                            }
                                                            $str .="
                                                            <a class = 'name hname' style='font-size: 15px;'> $name < Coordinator ></a>
                                                            &nbsp;&nbsp;<span style='font-size: 11px;'>$time_message </span><br><p style='margin-left: 1%; white-space:pre-wrap;'>$post_content</p><br><hr class='dashed'>";
                                                        }
                                                            
                                                    }
                                                    else
                                                    {
                                                        $str .="<p style='text-align: center; margin-bottom:1rem;'>No Notice to Show!</p><hr class='dashed'>";
                                                    }
                                                    
                                                    $str .="
                                                    <div class='commentOption' onClick='javascript:toggleComment$id()'> 
                                                        <br><p id='toggleText' style='color: blue; padding-left: 2%;'>Add a notice</p>
                                                    </div>
                                                    
                                                    <div class='post_comment' id='toggle$id' style='display:none;'>
                                                        <form id='comment_form' id='toggle$id' method='POST' autocomplete='off'>
                                                            <input type='text' name='comment_body' placeholder='Add a notice' style='resize: none;width: 90%;height: auto;padding: 10px 20px;box-sizing: border-box;border: 1px solid #C5C5C5;border-radius: 5px;font-size: 16px;resize: none;background-color: white;box-shadow: 1px 2px 3px #C5C5C5;'>
                                                            <input type='submit' name='postComment$id' value='Post' style ='background-color: #6B6B6B;color: white;font-size: 16px;border: none;color: white;padding: 4px 25px;text-align: center;text-decoration: none;display: inline-block;border-radius: 10px;font-size: 16px;margin: 4px 2px;cursor: pointer;'>
                                                            
                                                        </form>
                                                    </div>
                                                    
                                                    

                                                    <div class='modal fade' id='viewModal$id' tabindex='-1' aria-labelledby='viewModalLabel' aria-hidden='true'>
                                                        <div class='modal-dialog modal-lg' style='max-width: 90%;'>
                                                            <div class='modal-content'>
                                                                <div class='modal-header'>
                                                                    <h5 class='modal-title' id='viewModalLabel'>View Adviser Submission</h5>
                                                                </div>

                                                                
                                                                    <div class='modal-body p-5'>
                                                                    <table cellpadding='0' cellspacing='200px' border='0' class='table' id=''>
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style='padding:8px; width:15%; text-align:center;'>Date Upload</th>
                                                                                    <th style='padding:8px; width:15%; text-align:center;'>File Name</th>
                                                                                    <th style='padding:8px; width:15%; text-align:center;'>Description</th>
                                                                                    <th style='padding:8px; width:15%; text-align:center;'>Submitted by:</th>
                                                                                    <th style='padding:8px; width:15%; text-align:center;'>File Submitted</th>
                                                                                    
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody> ";
                                                                                    $table_query = mysqli_query($conn, "SELECT * FROM adviser_classwork WHERE assignment_id = '$id' ORDER BY facultynum DESC");
                                                                                    if(mysqli_num_rows($table_query)> 0)
                                                                                    {
                                                                                        
                                                                                        while($tablerow = mysqli_fetch_array($table_query)){
                                                                                        
                                                                                            $assignmentid = $tablerow['assignment_id'];
                                                                                            $added_by = $tablerow['facultynum'];

                                                                                            $adviser = mysqli_query($conn, "SELECT name FROM adviser_info WHERE facultynum = '$added_by'");
                                                                                            $get = mysqli_fetch_assoc($adviser);
                                                                                            $faculty_name = $get['name'];
                                                                                            
                                                                                            $adv_id = $tablerow['id'];
                                                                                            $adv_path = $tablerow['files_destination'];
                                                                                            $adv_file = $tablerow['files'];
                                                                                            $adv_status = $tablerow['status'];
                                                                                            $adv_fileExt = explode('.', $adv_file);
                                                                                            $adv_fileActualExt = end($adv_fileExt);
                                                                                            $adv_allowed  = array('jpg','jpeg','png');
                                                                                            $adv_fileDiv = "";
                                                                                                if (in_array($adv_fileActualExt, $adv_allowed)) {
                                                                                                    $adv_fileDiv = "<div id='postedFile'>
                                                                                                        <img src='$adv_path' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:150px; height:auto;min-height:50%;max-height:250px;'>
                                                                                                            </div>";
                                                                                                }
                                                                                            $desc_title = htmlspecialchars($tablerow['description'], ENT_QUOTES);
                                                                                            $displayed_desc = substr($tablerow['description'],0,50);
                                                                                            $file_title = htmlspecialchars($tablerow['file_name'], ENT_QUOTES);
                                                                                            $displayed_file = substr($tablerow['file_name'],0,30);

                                                                                            $displayed_date = date("M j Y, H:i:s A", strtotime($tablerow['date_added']));
                            
                                                                                            //display the assignment details na sinubmit ni student
                                                                                            
                                                                                                $str .= "
                                                                                                <tr>
                                                                                                    <td style='text-align:center;'> {$displayed_date} </td>
                                                                                                    <td style='text-align:center;'> <span title='$file_title' style='white-space:pre-line;'> {$displayed_file} </td>
                                                                                                    <td style='text-align:center;'> <span title='$desc_title' style='white-space:pre-line;'> {$displayed_desc}</td>
                                                                                                    <td style='text-align:center;'> $faculty_name </td>";
                                                                                                    if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png"){
                                                                                                    
                                                                                                        $str.= "
                                                                                                            <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='$adv_path'>View Submission</a></td>";                                                                                                  
                                                                                                    }
                                                                                                    else if(substr($tablerow['files_destination'], -4) === ".pdf" || substr($tablerow['files'], -4) === ".pdf" || substr($tablerow['files_destination'], -5) === ".docx" || substr($tablerow['files'], -5) === ".docx"  ||  substr($tablerow['files_destination'], -4) === ".doc" || substr($tablerow['files'], -4) === ".doc"  ||  substr($tablerow['files_destination'], -4) === ".ppt" || substr($tablerow['files'], -4) === ".ppt"  ||  substr($tablerow['files_destination'], -5) === ".pptx" || substr($tablerow['files'], -5) === ".pptx"  ||  substr($tablerow['files_destination'], -4) === ".xls" || substr($tablerow['files'], -4) === ".xls")
                                                                                                    {
                                                                                                        $str .= "<td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Submission</a></td>";
                                                                                                    }
                                                                                                    else
                                                                                                    {
                                                                                                        $str .="<td></td>";
                                                                                                    }
                                                                                                    $str .= "
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    </tr>";
                                                                                                    
                                                                                            
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
                                                                    </div>

                                                                    <div class='modal-footer'>
                                                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Modal DELETE DATA-->
                                                        <div class='modal fade' id='deleteModal$id' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
                                                            <div class='modal-dialog modal-xl' role='document'>
                                                                <div class='modal-content'>
                                                                    <div class='modal-header'>
                                                                        <h5 class='modal-title' id = 'deleteModalLabel'><b>CONFIRMATION</b></h5>
                                                                        
                                                                    </div>
                                                                        <form method = 'POST'>
                                                                        <div class='modal-body'>
                                                                        <input type='hidden' name='get_id' value='$id'>
                                                                            <h4> Do you want to delete this Activity? </h4>
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
                                                            <div class='modal-dialog modal-lg' id='edit'>
                                                                <div class='modal-content'>

                                                                <!--modal header-->
                                                                <div class='modal-header'>
                                                                    <h5 class='modal-title' id='exampleModalLabel'>EDIT ASSIGNMENT/ANNOUNCEMENT</h5>
                                                                        
                                                                </div>
                                            
                                                                <!--modal body-->
                                                                <form method='POST' enctype='multipart/form-data'>
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
                                                                            <input class='form-control' type='file' name='file' id='formFile' value='$file'>

                                                                            <style>
                                                                    
                                                                                .md-file:hover:after {
                                                                                content:attr(data-tooltip);
                                                                                }
                                                            
                                                                                </style>
                                                                        </div>
                                                                        <br>
                                                                            
                                                                                <div class='md-grade'>
                                                                                    <label for='grd' class='form-label'>Due Date</label>
                                                                                    <input class='form-control form-control-sm' id='end_date' type='datetime-local' name='due_date' value='$due_date' aria-label='.form-control-sm example'>
                                                                                
                                                                            </div><br>
                                                                            
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



                                                    </div>
                                                    </div>
                                                
                                    ";

                            } //end of while
                            
                            echo $str;
                        }
                        else{
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
                 //end of first if (mysqli_num_rows($data_query)>0)

                                ?><!--end of 2ND PHP -->
        <script>
                const tabletSize1 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
                function handleScreenSizeChange(tabletSize1) {
                    if (tabletSize1.matches) 
                    {
                        document.getElementById('topnav_right').classList.remove('topnavbar-right');
                        document.getElementById('create_ass').style.maxWidth = "80%";
                        document.getElementById('trash').style.maxWidth = "80%";
                        document.getElementById('edit').style.maxWidth = "80%";
                    } 
                    else
                    {
                        document.getElementById('topnav_right').classList.add('topnavbar-right');
                        document.getElementById('create_ass').style.maxWidth = "";
                        document.getElementById('trash').style.maxWidth = "";
                        document.getElementById('edit').style.maxWidth = "";
                    }
                }

                tabletSize1.addListener(handleScreenSizeChange);
                handleScreenSizeChange(tabletSize1);
            </script>
</div> <!--End of container-fluid -->
</body>
</html>