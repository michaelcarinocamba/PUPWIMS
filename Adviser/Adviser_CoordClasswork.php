<?php
    session_start();
    include "../db_conn.php";
    if(!isset($_SESSION['faculty']))
    {
        header("Location: Adviser_Login.php?LoginFirst");
    }

    $coordquery = mysqli_query($conn,"SELECT * FROM coordinator_info");
    $getcoord = mysqli_fetch_array($coordquery);
    $coordinator = $getcoord['coordinatornum'];
    $name = $_SESSION['name'];
    date_default_timezone_set('Asia/Singapore');
    $date_today = date("Y-m-d H:i:s");

    $faculty = $_SESSION['faculty'];
    $message = "";

    if(isset($_POST['saveagain']))
    {
    $get_id = $_POST['id1'];
    $assignment_query = mysqli_query($conn, "SELECT * FROM coordinator_classwork WHERE assignment_id = '$get_id'");
    $assignment_row = mysqli_fetch_array($assignment_query);
    $assignment_due = $assignment_row['due_date'];
    $assignmentid = $assignment_row['assignment_id'];
    $assignment_title = $assignment_row['title'];
    $filetypename = $_POST['name1'];
    $coordname = $assignment_row['name'];
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

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'csv');

    if($check_empty != "" && $fileName == "")
    {
        $query = mysqli_query($conn, "UPDATE adviser_classwork SET file_name = '$filetypename', description = '$descript', date_added = '$date_added' WHERE assignment_id = '$get_id' ");
        $notif_desc = "has resubmitted post. [$assignment_title]";
        $date_time_now = date("Y-m-d H:i:s");
        $notif_link = "Coordinator_Classwork.php";
        $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$coordname', '', '$date_time_now','no')");
            
        header("Location: Adviser_CoordClasswork.php");
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

                    $query = mysqli_query($conn, "UPDATE adviser_classwork SET file_name = '$filetypename', description = '$descript', date_added = '$date_added', files = '$fileName', files_destination = '$fileDestination' WHERE assignment_id = '$get_id' ");
                    $notif_desc = "has resubmitted post. [$assignment_title]";
                    $date_time_now = date("Y-m-d H:i:s");
                    $notif_link = "Coordinator_Classwork.php";
                    $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$coordname', '', '$date_time_now','no')");
            
                    $message = "Your classwork has been updated!";
                    header("Location: Adviser_CoordClasswork.php?sucess1");
                }
            
                else{
                    $message = "Your file is too big!";
                }
            }
        
            else{
                $message = "There was an error uploading your file.";
            }
        }

        else{
            $message = "You cannot upload files of this type!";
        }
    }
    }


    if(isset($_POST['save']))
    {   


        $get_id = $_POST['id'];
      
        $assignment_query = mysqli_query($conn, "SELECT * FROM coordinator_classwork WHERE assignment_id = '$get_id'");
        $assignment_row = mysqli_fetch_array($assignment_query);
        $assignment_due = $assignment_row['due_date'];
        $assignmentid = $assignment_row['assignment_id'];
        $assignment_title = $assignment_row['title'];
        $filetypename = $_POST['name'];
        $coordname = $assignment_row['name'];
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

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'csv');

        if($check_empty != "" && $fileName == "")
        {
            $query = mysqli_query($conn, "INSERT INTO adviser_classwork VALUES('','$assignmentid', '$faculty', '$filetypename', '$descript', '$date_added', '$assignment_due', '', '','') ");
            $notif_desc = "has submitted post. [$assignment_title]";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Coordinator_Classwork.php";
            $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$coordname', '', '$date_time_now','no')");
            
            header("Location: Adviser_CoordClasswork.php");
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

                        $sql = mysqli_query($conn, "INSERT INTO adviser_classwork VALUES('','$assignmentid', '$faculty', '$filetypename', '$descript', '$date_added', '$assignment_due','$fileName',  '$fileDestination', '')");
                        $notif_desc = "has submitted post. [$assignment_title]";
                        $date_time_now = date("Y-m-d H:i:s");
                        $notif_link = "Coordinator_Classwork.php";
                        $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$coordname', '', '$date_time_now','no')");
            
                        $message = "Your classwork has been uploaded!";
                        header("Location: Adviser_CoordClasswork.php?success2");
                    }
                
                    else{
                        $message = "Your file is too big!";
                    }
                }
            
                else{
                    $message = "There was an error uploading your file.";
                }
            }
    
            else{
                $message = "You cannot upload files of this type!";
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

    <title>Adviser Dashboard - Classwork</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <link rel = "stylesheet" href = "../css/adviser_dashclasswork.css">

    <style>
        [data-tooltip] {
        font-size: 13px;
        font-style: italic;
        }
    </style>

    
</head>

<body id="body">
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->
    
        <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <br><br>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>
            
            <h6 style="color: white;font-size:20px;"><b> Adviser </b></h6> <br><br><hr style="background-color:white;width:200px;">
            <a href="Adviser_ClassList.php">Class List</a>
            <a href="Adviser_CoordClasswork.php">Adviser Work</a> 
            
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
                                document.getElementById('body').style.fontSize = "13px";
                            } 
                            else
                            {
                                document.getElementById('topnav_right').classList.add('topnavbar-right');
                            }
                        }

                        tabletSize.addListener(handleScreenSizeChange);
                        handleScreenSizeChange(tabletSize);
                    </script>
            
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Coordinator Announcement</h1>
            <hr style="background-color:maroon;border-width:2px;">
            
            <?php

                $data_query = mysqli_query($conn, "SELECT * FROM coordinator_classwork WHERE remove = 'no' ORDER BY assignment_id DESC");

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
                        $due_date_display = date("M j Y, H:i:s A", strtotime($row['due_date']));
                        $file = $row['files'];
                        $image = $row['files_destination'];
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
                        $formatted_date = date("Y-m-d\TH:i", strtotime($row['due_date']));

                        $checkquery = mysqli_query($conn, "SELECT * FROM adviser_classwork WHERE assignment_id = '$id' AND facultynum = '$faculty'");
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
                        

                        if(mysqli_num_rows($checkquery) === 1)
                        {
                            
                        
                            if($due_date < $date_today)
                            {
                                $checksubmit = mysqli_query($conn, "UPDATE adviser_classwork SET status = 'Turned in late' WHERE assignment_id LIKE '$id'");
                                $status = "Turned in late";
                            }
                            else if($due_date > $date_today)
                            {
                                $checksubmit = mysqli_query($conn, "UPDATE adviser_classwork SET status = 'Turned in' WHERE assignment_id LIKE '$id'");
                                $status = "Turned in";
                            }
                            
                        }
                        else if(mysqli_num_rows($checkquery) === 0)
                        {
                            
                            
                            
                            if($due_date < $date_today)
                            {
                                $checksubmit = mysqli_query($conn, "UPDATE adviser_classwork SET status = 'Missing' WHERE assignment_id LIKE '$id'");
                                $status = "Missing";
                            }
                            else if($due_date > $date_today)
                            {
                                $checksubmit = mysqli_query($conn, "UPDATE adviser_classwork SET status = 'On going' WHERE assignment_id LIKE '$id'");
                                $status = "On going";
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

                            $check_submission = mysqli_query($conn, "SELECT * FROM adviser_classwork WHERE assignment_id LIKE '$id' AND facultynum LIKE '$faculty'");
                            if(mysqli_num_rows($check_submission) === 0){
                            $str .= "<br>
                            <div class='card-body stylebox' style='background-color:white;'>
                                <div onClick='javascript:toggle$id(); myFunction(this)'>
                                    <div class='card-body stylebox' style='background-color: maroon'>
                                    
                                        <div class='row'>
                                            <div class='col-8'>
                                                <b style = 'font-weight: 600;color:white;'>Assignment Title: $title </b>
                                            </div>


                                            <div class='col-4' style = 'color:white;'>
                                                Date added: $date_added_display
                                            </div>
                                                
                                                <br>
                                        </div>

                                            
                                    </div>
                                </div>

                                <div class='card-body display' id='toggleClass$id'>";
                                if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                { $str .="
                                    <div class = 'row text'>
                                        <div class = 'col-4'> OJT Coordinator, $name  </div>
                                        
                                        <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction  </div>
                                        <br><br>
                                        <div class = 'col-12' style='border: 1px solid black;'> $fileDiv</div>
                                
                                    </div> <br><br>
                                ";
                                }
                                
                                else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                {
                                    $str .="
                                    <div class = 'row text'>
                                        <div class = 'col-4'> OJT Coordinator, $name  </div>
                                        
                                        <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction  </div>
                                        <br><br>
                                        <div class = 'col-12'><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                                        
                                    </div> <br><br><hr class='dashed'>"; 
                                }
                                else
                                {
                                    $str .="
                                    <div class = 'row text'>
                                        <div class = 'col-4'> OJT Coordinator, $name  </div>
                                        
                                        <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction  </div>
                                        <br><br>
                                        
                                    </div> <br><br><hr class='dashed'>"; 
                                }
                                
                                $str .= "
                                
                                    <button type='button' class='filebtn hfilebtn' style='margin-left: 85%;' data-bs-toggle='modal' data-bs-target='#submitModal$id'>
                                    + Add or Create
                                    </button>
                                    ";
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
                                            <hr class='dashed'><h5><b>Notice: </b></h5>  <br> <a class = 'name hname' style='font-size: 15px;color: blue;'> $name < Coordinator ></a>
                                            &nbsp;&nbsp;<span style='font-size: 11px;'>$time_message </span><br><p style='margin-left: 1%;'>$post_content<p>";
                                        }
                                            
                                    }
                                    else
                                    {
                                        $str .="<hr class='dashed'><p style='text-align: center; margin-bottom:1rem;'>No Notice to Show!</p>";
                                    }
                                    $str .="
                                    
    
                                    
                                    <div class='modal fade' id='submitModal$id' tabindex='-1' aria-labelledby='submitModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog modal-lg'>
                                            <div class='modal-content'>
                                            

                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='submitModalLabel'>Attach response</h5>
                                                </div>

                                                <form method='POST' enctype='multipart/form-data'>
                                                <div class='modal-body p-5'>
                                                    

                                                <div class='md-file' data-tooltip='Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX, CSV. Maximum File Size accepted is 50 MB.'>
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
                                                        <textarea name='desc' class='form-control' id='exampleFormControlTextarea1' placeholder='Description' rows='3'></textarea>
                                                    </div>

                                                </div>

                                                <div class='modal-footer'>
                                                    <input type='hidden' name='id' value='$id'>
                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                    <button type='submit' name='save' class='btn btn-primary'>Submit</button>
                                                </div></form>
                                                

                                            </div>
                                        </div>
                                    </div>

                                    </div></div>

                                                        
                                                
                                                        

                                                    ";

                                                
                                            
                                }
                                else if(mysqli_num_rows($check_submission) === 1){
                                    $classwork_row1 = mysqli_fetch_array($check_submission);
                                    $ass_id = $classwork_row1['assignment_id'];
                                
                                    $ass_added_by = $classwork_row1['facultynum'];
                                    $ass_file_name = $classwork_row1['file_name'];
                                    $ass_desc = $classwork_row1['description'];
                                    $ass_file = $classwork_row1['files'];
                                    $ass_path = $classwork_row1['files_destination'];

                                    $fileExt1 = explode('.', $ass_file);
                                    $fileActualExt1 = end($fileExt1);
                                    $allowed1  = array('jpg','jpeg','png', 'JPG', 'JPEG' ,'PNG');
                                    $fileDiv1 = "";
                                    if (in_array($fileActualExt1, $allowed1)) 
                                    {
                                        $fileDiv1 = "<div id='postedFile'>
                                        <img src='$ass_path' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='width:auto;min-width:100%;max-width:250px; height:auto;min-height:100%;max-height:250px;' >
                                        </div>";
                                    }
                                    $str .= "<br>
                                    <div class='card-body stylebox' style='background-color:white;'>
                                        <div onClick='javascript:toggle$id(); myFunction(this)'>
                                            <div class='card-body stylebox' style='background-color: #329C08'>
                                                <div class='row'>
                                                    <div class='col-8'>
                                                        <b style = 'font-weight: 600;color:white;'>Assignment Title: $title </b>
                                                    </div>


                                                    <div class='col-4' style = 'color:white;'>
                                                    Date added: $date_added_display
                                                    </div>
                                                        
                                                        <br>
                                                </div>
                                            </div>
                                        </div>";

                                                        
                                                        
                                            $str .="
                                            <div class='card-body display' id='toggleClass$id'>
                                                                ";
                                                                    
                                                                    
                                                                    

                                            if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png")
                                            { $str .="
                                                <div class = 'row text'>
                                                    <div class = 'col-4'> OJT Coordinator, $name  </div>
                                                    
                                                    <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction  </div>
                                                    <br><br>
                                                    <div class = 'col-12' style='border: 1px solid black;'> $fileDiv</div>
                                                </div>";
                                                
                                                if(substr($classwork_row1['files_destination'], -4) === ".jpg" || substr($classwork_row1['files'], -4) === ".jpg" || substr($classwork_row1['files_destination'], -5) === ".jpeg" || substr($classwork_row1['files'], -5) === ".jpeg"  ||  substr($classwork_row1['files_destination'], -4) === ".png" || substr($classwork_row1['files'], -4) === ".png")
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                    <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                                    <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>Full View</a></div></center> </div>
                                                        
                                                     <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>";
                                                }
                                                else if(substr($classwork_row1['files_destination'], -4) === ".pdf" || substr($classwork_row1['files'], -4) === ".pdf" || substr($classwork_row1['files_destination'], -5) === ".docx" || substr($classwork_row1['files'], -5) === ".docx"  ||  substr($classwork_row1['files_destination'], -4) === ".doc" || substr($classwork_row1['files'], -4) === ".doc"  ||  substr($classwork_row1['files_destination'], -4) === ".ppt" || substr($classwork_row1['files'], -4) === ".ppt"  ||  substr($classwork_row1['files_destination'], -5) === ".pptx" || substr($classwork_row1['files'], -5) === ".pptx"  ||  substr($classwork_row1['files_destination'], -4) === ".xls" || substr($classwork_row1['files'], -4) === ".xls")
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                    <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;position:sticky;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>{$classwork_row1['files']}</a></center><br>
                                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>
                                                    ";
                                                }
                                                else
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                        
                                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>
                                                    ";
                                                }
                                            
                                            }
                                            else if(substr($row['files_destination'], -4) === ".pdf" || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".docx" || substr($row['files'], -5) === ".docx"  ||  substr($row['files_destination'], -4) === ".doc" || substr($row['files'], -4) === ".doc"  ||  substr($row['files_destination'], -4) === ".ppt" || substr($row['files'], -4) === ".ppt"  ||  substr($row['files_destination'], -5) === ".pptx" || substr($row['files'], -5) === ".pptx"  ||  substr($row['files_destination'], -4) === ".xls" || substr($row['files'], -4) === ".xls")
                                            {
                                                $str .="
                                                <div class = 'row text'>
                                                    <div class = 'col-4'> OJT Coordinator, $name  </div>
                                                    
                                                
                                
                                                    <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction  </div>
                                                    <br><br>
                                                <div class = 'col-12' style=' margin-left:1%; width:10%;'><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$row['files']}'>{$row['files']}</a> </div>
                                                </div>";
                                                
                                                if(substr($classwork_row1['files_destination'], -4) === ".jpg" || substr($classwork_row1['files'], -4) === ".jpg" || substr($classwork_row1['files_destination'], -5) === ".jpeg" || substr($classwork_row1['files'], -5) === ".jpeg"  ||  substr($classwork_row1['files_destination'], -4) === ".png" || substr($classwork_row1['files'], -4) === ".png")
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                        <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                                        <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>Full View</a></div></center> </div>
                                                            
                                                        <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>";
                                                }
                                                else if(substr($classwork_row1['files_destination'], -4) === ".pdf" || substr($classwork_row1['files'], -4) === ".pdf" || substr($classwork_row1['files_destination'], -5) === ".docx" || substr($classwork_row1['files'], -5) === ".docx"  ||  substr($classwork_row1['files_destination'], -4) === ".doc" || substr($classwork_row1['files'], -4) === ".doc"  ||  substr($classwork_row1['files_destination'], -4) === ".ppt" || substr($classwork_row1['files'], -4) === ".ppt"  ||  substr($classwork_row1['files_destination'], -5) === ".pptx" || substr($classwork_row1['files'], -5) === ".pptx"  ||  substr($classwork_row1['files_destination'], -4) === ".xls" || substr($classwork_row1['files'], -4) === ".xls")
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                        <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;position:sticky;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>{$classwork_row1['files']}</a></center><br>
                                                        <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>
                                                    ";
                                                }
                                                else
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                        
                                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>
                                                    ";
                                                }
                                            }
                                            else
                                            {
                                                $str .="
                                                <div class = 'row text'>
                                                    <div class = 'col-4'> OJT Coordinator, $name  </div>
                                                    
                                                
                                
                                                    <br><br><div class = 'col-12' style='white-space:normal;'>Instructions: <br>&emsp; $instruction  </div>
                                                    <br><br>
                                            
                                                </div>";
                                                
                                                if(substr($classwork_row1['files_destination'], -4) === ".jpg" || substr($classwork_row1['files'], -4) === ".jpg" || substr($classwork_row1['files_destination'], -5) === ".jpeg" || substr($classwork_row1['files'], -5) === ".jpeg"  ||  substr($classwork_row1['files_destination'], -4) === ".png" || substr($classwork_row1['files'], -4) === ".png")
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                    <div style='position: relative; width: auto;max-width: 40%;min-width:40%;height:50%; min-height:50%; max-height:100%; border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;margin-top:2%'>$fileDiv1 <br>
                                                    <center><div style='position:asolute; margin-right:20%; width: auto;max-width: 50%;min-width:50%;'><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-left:13%;position:relative;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>Full View</a></div></center> </div>
                                                        
                                                    <div style='width: auto;max-width: 50%;min-width:50%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>";
                                                }
                                                else if(substr($classwork_row1['files_destination'], -4) === ".pdf" || substr($classwork_row1['files'], -4) === ".pdf" || substr($classwork_row1['files_destination'], -5) === ".docx" || substr($classwork_row1['files'], -5) === ".docx"  ||  substr($classwork_row1['files_destination'], -4) === ".doc" || substr($classwork_row1['files'], -4) === ".doc"  ||  substr($classwork_row1['files_destination'], -4) === ".ppt" || substr($classwork_row1['files'], -4) === ".ppt"  ||  substr($classwork_row1['files_destination'], -5) === ".pptx" || substr($classwork_row1['files'], -5) === ".pptx"  ||  substr($classwork_row1['files_destination'], -4) === ".xls" || substr($classwork_row1['files'], -4) === ".xls")
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                        <center><a class = 'filebtn hfilebtn' style='width: 100%;max-width:100%; height:auto; margin-top:10%;position:sticky;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>{$classwork_row1['files']}</a></center><br>
                                                        <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>
                                                    ";
                                                }
                                                else
                                                {
                                                    $str .="
                                                    <br><br><hr class='dashed'><h4> Your work </h4>
                                                    <div class = 'row text'>
                                                        
                                                    <div style='width: auto;max-width: 100%;min-width:95%;height: auto;border: 1px dashed black;padding: 15px;margin: 20px;'><strong>File name:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp; $ass_file_name </h6><br> <strong>Description:</strong><h6 style='white-space:pre-line;'>&emsp;&emsp;$ass_desc</h6> </div>
                                                    </div>
                                                    ";
                                                }
                                            }
                                            

                                           
                                                    $str .="
                                                <hr class='dashed'>
                                                <button class='filebtn hfilebtn' id='unsubmitBtn' type='button' style='margin-left:85%;' data-toggle='modal' data-target='#unsubmitModal$id' onclick='hideButton()'>Unsubmit</button>";
                                            
                                                if(isset($_POST['unsubmit']))
                                                {
                                                    if($_POST['unsubmit_id'] === $id)
                                                    {
                                                        ?>
                                                            <script type='text/javascript'>
                                                                    $(document).ready(function(){
                                                                        document.getElementById("unsubmitBtn").style.display = "none";
                                                                    });

                                                                    function hideButton() {
                                                                        document.getElementById("unsubmitBtn").style.display = "none";
                                                                    }
                                                            </script>
                                                        <?php
                                                    
                                                    $str .="
                                                    <button class='filebtn hfilebtn' ";if(isset($_POST['unsubmit'])){ $str.="type='button' style='margin-left:85%;' data-toggle='modal' data-target='#submitagainModal$ass_id'";}else{$str.=" style='display:none;'";}$str.=" >+ Add or Change</button>
                                                ";}
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
                                                                $time_message = $interval->y . " year ago "; //1 year ago
                                                            else
                                                                $time_message = $interval->y . " years ago "; //1+ year ago
                                                        } else if ($interval->m >= 1) {
                                                            if ($interval->d == 0) {
                                                                $days = " ago";
                                                            } else if ($interval->d == 1) {
                                                                $days = $interval->d . " day ago ";
                                                            } else {
                                                                $days = $interval->d . " days ago ";
                                                            }


                                                            if ($interval->m == 1) {
                                                                $time_message = $interval->m . " month, " . $days;
                                                            } else {
                                                                $time_message = $interval->m . " months, " . $days;
                                                            }
                                                        } else if ($interval->d >= 1) {
                                                            if ($interval->d == 1) {
                                                                $time_message = "Yesterday ";
                                                            } else {
                                                                $time_message = $interval->d . " days ago ";
                                                            }
                                                        } else if ($interval->h >= 1) {
                                                            if ($interval->h == 1) {
                                                                $time_message = $interval->h . " hour ago ";
                                                            } else {
                                                                $time_message = $interval->h . " hours ago ";
                                                            }
                                                        } else if ($interval->i >= 1) {
                                                            if ($interval->i == 1) {
                                                                $time_message = $interval->i . " minute ago ";
                                                            } else {
                                                                $time_message = $interval->i . " minutes ago ";
                                                            }
                                                        } else {
                                                            if ($interval->s < 30) {
                                                                $time_message = "Just now";
                                                            } else {
                                                                $time_message = $interval->s . " seconds ago ";
                                                            }
                                                        }
                                                        $str .="
                                                        <hr class='dashed'><h5><b>Notice: </b></h5>  <br> <a class = 'name hname' style='font-size: 15px;color: blue;'> $name < Coordinator ></a>
                                                        &nbsp;&nbsp;<span style='font-size: 11px;'>$time_message </span><br><p style='margin-left: 1%;'>$post_content<p>";
                                                    }
                                        
                                            }
                                            else
                                            {
                                                $str .="<hr class='dashed'><p style='text-align: center; margin-bottom:1rem;'>No Notice to Show!</p>";
                                            }
                                            $str .="
                                            

                                            <div class='modal fade' id='submitagainModal$ass_id' tabindex='-1' aria-labelledby='submitagainModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog modal-lg'>
                                                <div class='modal-content'>
                                                

                                                    <div class='modal-header'>
                                                        <h5 class='modal-title' id='submitagainModalLabel'>Submit an Assignment</h5>
                                                    </div>

                                                    <form method='POST' enctype='multipart/form-data'>
                                                    <div class='modal-body p-5'>
                                                        

                                                        <div class='md-file'>
                                                            <label for='formFile' class='form-label'></label>
                                                            <input class='form-control' type='file' name='file1' id='formFile' required>
                                                        </div>

                                                        <br>

                                                        <div class='md-title'>
                                                            <label for='exampleFormControlInput' name='title' class='form-label'>File Name</label>
                                                            <input type='text' name='name1' class='form-control' id='exampleFormControlInput' placeholder='$ass_file_name' value='$ass_file_name'>
                                                        </div>

                                                        <br>

                                                        <div class='md-textarea'>
                                                            <label for='exampleFormControlTextarea1' class='form-label'>Description</label>
                                                            <textarea name='desc1' class='form-control' id='exampleFormControlTextarea1' placeholder='$ass_desc' rows='3'>$ass_desc</textarea>
                                                        </div>

                                                    </div>

                                                    <div class='modal-footer'>
                                                        <input type='hidden' name='id1' value='$ass_id'>
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
                                                            <input type='hidden' name='unsubmit_id' value='$id'>
                                                            <button class='btn btn-danger' type='submit' name='unsubmit'>Unsubmit</button> 
                                                            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div></form>

                                            </div>
                                            </div>

                                            
                                            
                                            ";
                                            }
                                        }
 //end of while
                            
                            echo $str;

						}
                        else{
                            $str = "
                            <br>
                            <div class='card-deck'>
                                <div class='card style'>
                                    <div class='card-body text-center'>
                                    <br>
                                    <h5> There's no classwork yet!</h5>
                                    </div>
                                </div>
                            </div>
                            ";
                            echo $str;
                        }//end of third if(mysqli_num_rows($data_query)>0)

				
            
                 //end of first if (mysqli_num_rows($data_query)>0)

                                ?><!--end of 2ND PHP -->
        <br><br>
        </div> <!-- end of main -->
</div> <!--End of container-fluid -->

</body>
</html>