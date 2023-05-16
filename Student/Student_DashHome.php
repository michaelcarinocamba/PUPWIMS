<?php
    session_start();
    include "../db_conn.php";
    if(!isset($_SESSION['studnum']))
    {
        header("Location: Student_Login.php?LoginFirst");
    }

    $name = $_SESSION['name'];
    $studentnum = $_SESSION['studnum'];
    $section = $_SESSION['yrsection'];

    $sql11 = mysqli_query($conn, "SELECT * FROM student_record");   
    $row11 = mysqli_fetch_array($sql11);
    if(!empty($row11)){
        $code = $row11['class_code'];
    }

    $code = $_GET['classCode'];

    $stud_details_query = mysqli_query($conn, "SELECT * FROM student_info");
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

    
?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - Home</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <link rel = "stylesheet" href = "../css/student_DashHome.css">


<style>
    [data-tooltip] {
        font-size: 13px;
        font-style: italic;
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

            <div class="topnavbar-right" id="topnav_right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->

        <br><br><br>
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

  
        <div>
             <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;"> Welcome, <?php echo $name; ?> ! </h1>
            <hr style="background-color:maroon;border-width:2px;">
            <button type="button" class="btn filebtn" style="float:right;" data-bs-toggle="modal" data-bs-target="#reminderModal">
                <i class = "fa fa-file-text fa-1x"></i> &nbsp; Rules and Regulations 
         </button><br><br>
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
                title: 'Posted succesfully.'
            })
            </script>
            <?php
                }
            if(isset($_REQUEST['uploadnotsuccess5']))
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
                title: 'Your file is too big.'
            })
            </script>
            <?php
                }
                if(isset($_REQUEST['uploadnotsuccess6']))
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
                if(isset($_REQUEST['uploadnotsuccess7']))
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
                title: 'You cannot upload files of this type.'
            })
            </script>
            <?php
                }
                if(isset($_REQUEST['uploadnotsuccess8']))
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
                title: 'Try to input text or file first.'
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
                    title: 'Deleted Successfully.'
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
                    title: 'Edited Successfully.'
                })
                </script>
                <?php
                    }
                    if(isset($_REQUEST['editnotsuccess1']))
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
                        title: 'Your file is too big.'
                    })
                    </script>
                    <?php
                        }
                        if(isset($_REQUEST['editnotsuccess2']))
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
                        if(isset($_REQUEST['editnotsuccess3']))
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
                        title: 'You cannot upload files of this type!'
                    })
                    </script>
                    <?php
                        }
                        if(isset($_REQUEST['editnotsuccess4']))
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
                            title: 'Try to input text or file first'
                        })
                        </script>
                        <?php
                            }
                        
                
            ?>
            
        <!-- modal -->
        
        <div class="card-body stylebox" style="background-color:white; border-width:2px;">
            <h4> What's on your mind? </h4>
            
            <!--POST & UPLOAD-->
                <form action="Student_Post.php?classCode=<?php echo $code; ?>" method="POST" enctype="multipart/form-data">
                    <textarea  class = "postarea"name = "content" id = "post_text_area" placeholder='Share something...'></textarea>

                <!-- Modal Reminder Hate Speech -->
                <div class="modal fade" id="reminderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="exampleModalLabel">  Rules and Regulations</h2>
                        
                    </div>
                    <div class="modal-body">
                    <h7>
                        <p>  Please read the rules below:</p>

                        <p class = indent1> 1.	Don’t share your sensitive information.</p>
                        <p class = indent1> 2.	Protect your colleagues’ information. Do not share colleagues’ personal data, including their picture, without their permission.</p>
                        <p class = indent1> 3.	Know the rules. You are responsible for what you comment. Read, understand, and follow the university’s code of ethics.</p>
                        <p class = indent1> 4.	Be secure. Use a secure password, update it regularly, and never share your login information with anyone.</p>
                        <p class = indent1> 5.	Be respectful. Never publish material that is obscene, racist, sexist, pornographic, sexually exploitative, or in any other way discriminatory, threatening or harassing, personally offensive, defamatory, or illegal.</p>
                        <p class = indent1> 6.	Think before you post.</p>   
                    </h7>

                    <style type="text/css"> 
                            .indent { margin-left:80px; }
                            .indent1 { margin-left:40px; }
                        </style>

        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">I understand</button>
                    
                    </div>
                    </div>
                </div>
                </div>


                    <div class ="fileupload" data-tooltip="Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX. Maximum File Size accepted is 20 MB.">
                    <input class = "inputfile" type = "file" id="fileToUpload" name = "file" >
                    <label for="fileToUpload">
                        <i class = "fa fa-upload fa-1x">&emsp;</i>
                        Choose a file
                    </label>
                    <label id="file-name"></label>

                    <style>
                        
                        .fileupload:hover:after {
                        content:attr(data-tooltip);
                        }
    
                        </style>

                    </div>
    
                    
                    <!--TO DISPLAY THE FILENAME-->
                    <script>
                        document.querySelector("#fileToUpload").onchange = function(){
                            document.querySelector("#file-name").textContent = this.files[0].name;
                        }
                    </script>
                    
                    <br>
                    <input type='hidden' name='code' value='<?php $code ?>'>
                    <input class = "inputfile" type = "submit" name = "post" id="post_button" value = "post" >
                    <label for="post_button" style="float:right;">
                        Post
                    </label>

                    <BR>
                    <br>
                    <hr class="dashed">
                </form>

                
                
                <?php
                
                $str = "";
                $data_query = mysqli_query($conn, "SELECT * FROM post WHERE class_code = '$code' ORDER BY post_id DESC");
                
                if(mysqli_num_rows($data_query) > 0) 
                {
                    while ($row = mysqli_fetch_array($data_query)) 
                    {
                        $id = $row['post_id'];
                        $content = $row['content'];
                        $added_by = $row['added_by'];
                        $date_time = $row['date_added'];
                        $file = $row['files'];
                        $path = $row['files_destination'];
                        $edited = $row['edited'];
                        $name = $row['name'];

                        $post = mysqli_query($conn, "SELECT * FROM post WHERE post_id = '$id' AND class_code ='$code'");
                        $post_row = mysqli_fetch_array($post);
                        $post_id = $post_row['post_id'];
                        $post_content = $post_row['content'];
                        $post_added_by =$post_row['added_by'];
                        $post_file = $post_row['files'];
                        $post_path = $post_row['files_destination'];
                        $post_edited = $post_row['edited'];
                        $post_name = $post_row['name'];
                        ?>

                        <script>
                        function toggle<?php echo $id; ?>() {
                            var element = document.getElementById("toggleComment<?php echo $id; ?>");
                   
                            if (element.style.display == "block")
                                element.style.display = "none";
                            else
                                element.style.display = "block";
                        }
                    </script>

                    <?php

                    $post_query = mysqli_query($conn, "SELECT * FROM post WHERE post_id LIKE '$post_id'");
                    $post_row1 = mysqli_fetch_array($post_query);
                    $body = $post_row1['content'];

                    $comments_check = mysqli_query($conn, "SELECT * FROM comment WHERE post_id='$post_id'");
                    $comments_check_num = mysqli_num_rows($comments_check);

                    $check_edit = "";
                    if($post_edited == "yes")
                    {
                        $check_edit = "edited";
                    }
                    
                        
 
                        //Timeframe
                        date_default_timezone_set('Asia/Singapore');
                        $date_added = date("Y-m-d h:i:s");
                        $start_date = new DateTime($date_time); //Time of post
                        $end_date = new DateTime($date_added); //Current time
                        $interval = $start_date->diff($end_date); //Difference between dates 
                            
                            if ($interval->y >= 1) 
                            {
                                if ($interval == 1)
                                    $time_message = $interval->y . " year ago"; //1 year ago
                                else
                                    $time_message = $interval->y . " years ago"; //1+ year ago
                            } 
                            
                            else if ($interval->m >= 1) 
                            {
                                if ($interval->d == 0) {
                                    $days = " ago";
                                } 
                                else if ($interval->d == 1) { 
                                    $days = $interval->d . " day ago"; 
                                }
                                else {
                                    $days = $interval->d . " days ago";
                                }
            

                                if ($interval->m == 1) {
                                        $time_message = $interval->m . " month" . $days;
                                } 
                                else {
                                    $time_message = $interval->m . " months" . $days;
                                }
                            } 
                            
                            else if ($interval->d >= 1) 
                            {
                                if ($interval->d == 1) {
                                    $time_message = "Yesterday";
                                } 
                                else {
                                    $time_message = $interval->d . " days ago";
                                }
                            } 
                            
                            else if ($interval->h >= 1) 
                            {
                                if ($interval->h == 1) {
                                    $time_message = $interval->h . " hour ago";
                                } 
                                else {
                                    $time_message = $interval->h . " hours ago";
                                }
                            } 
                            
                            else if ($interval->i >= 1) 
                            {
                                if ($interval->i == 1) {
                                    $time_message = $interval->i . " minute ago";
                                } 
                                else {
                                    $time_message = $interval->i . " minutes ago";
                                }
                            } 
                            
                            else 
                            {
                                if ($interval->s < 30) {
                                    $time_message = "Just now";
                                } 
                                
                                else {
                                     $time_message = $interval->s . " seconds ago";
                                }
                            }
                            
                            /*IMAGE DISPLAY*/
                            $fileExt = explode('.', $post_file);
                            $fileActualExt = end($fileExt);
                            $allowed  = array('jpg','jpeg','png');
                            $fileDiv = "";
                                
                                if (in_array($fileActualExt, $allowed)) {
                                    $fileDiv = "<div id='postedFile'>
                                            <img src='$post_path' onclick='window.open(this.src)' title='Click Here To View Full Screen'  style='width:auto;min-width:100%;max-width:250px; height:auto;min-height:100%;max-height:250px;'>
                                        </div>";
                            }

                            /*DISPLAY NAME*/
                            $str .= "
                                <b> $post_name </b> &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                <span style='font-size: 14px'> $time_message &emsp;&emsp; $check_edit</span>";
                                
                              
                                if($post_added_by === $studentnum){
                                    $str .="
                                    <a type = 'button' class = 'deletebtn' data-bs-toggle='modal' data-bs-target='#deleteModal$post_id'>
                                            <i class = 'fa fa-trash'></i>
                                        </a>
                                        <a type = 'button' class = 'deletebtn' data-bs-toggle='modal' data-bs-target='#editModal$post_id'>
                                            <i class = 'fa fa-edit'></i>
                                        </a>
                                        
                                        
                                        <div class='modal fade' id='editModal$post_id' style='margin-left:5%;' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-bottom modal-dialog-centered modal-dialog' role='document'>
                                            <div class='modal-content'>

                                            <div class='modal-header'>
                                                <h5 class='modal-title'><b>Edit Post</b></h5>
                                                <button type='button' class='close' data-bs-dismiss='modal'>&times;</button>
                                            </div>

                                            <form action = 'Student_Post.php?classCode=$code' method='POST' enctype='multipart/form-data'>
                                            <div class='modal-body'>
                                                    <textarea  class = 'postarea' name = 'editedcontent' id = 'post_text_area' placeholder='Edit post'></textarea>

                                                    <input class = 'inputfile' type = 'file' id='fileToEdit' name = 'file' >
                                                    <label for='fileToEdit'>
                                                        <i class = 'fa fa-upload fa-1x'>&emsp;</i>
                                                        Choose a file
                                                    </label>
                                                    <label id='file-edit'></label>
                                                    
                                                    <!--TO DISPLAY THE FILENAME-->
                                                    <script>
                                                        document.querySelector('#fileToEdit').onchange = function(){
                                                            document.querySelector('#file-edit').textContent = this.files[0].name;
                                                        }
                                                    </script>
                                                    
                                                    <br>
                                                    <input type='hidden' name='get_id' value='$post_id'>
                                                    <input class = 'inputfile' type = 'submit' name = 'edit' id='edit_button'>
                                                    <label for='edit_button' style='float:right;'>
                                                        Post
                                                    </label>

                                                    <br>
                                                    <hr class='dashed'>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  

                                    <!-- Modal DELETE DATA-->
                                    <div class='modal fade' id='deleteModal$post_id' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog modal-lg' role='document'>
                                        <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id = 'deleteModalLabel'><b>CONFIRMATION</b></h5>
                                            
                                            
                                            
                                        </div>
                                        <form action = 'Student_Post.php?classCode=$code' method = 'POST'>
                                        <div class='modal-body'>
                                        <input type='hidden' name='get_id' value='$post_id'>
                                            <h4> Do you want to delete this information? </h4>
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                        <button type='submit' name = 'delete' class='btn btn-danger'>Confirm</button>
                                        </div>
                                        </form>
                                        </div>
                                    </div>
                                    </div>

                                    ";
                                                    

                                }
                                
                                

                                
                            
                            /*DISPLAY CONTENT*/
                            $str .="
                            <p style='white-space:pre-line;margin-left:2%;margin-top:2%;'>$post_content</p>";
                            
                            //Blank
                            if($post_row['files_destination'] === "" || $post_row['files'] ===  "")
                            {
                                $str.= "<br>";
                            }
                            
                            //Image
                            else if(substr($post_row['files_destination'], -4) === ".jpg" || substr($post_row['files'], -4) === ".jpg" || substr($post_row['files_destination'], -5) === ".jpeg" || substr($post_row['files'], -5) === ".jpeg"  ||  substr($post_row['files_destination'], -4) === ".png" || substr($post_row['files'], -4) === ".png")
                            { 
                                $str .= "$fileDiv";
                            }
                                                        
                            //Pdf,docs,excel
                            else if((substr($row['files_destination'], -5) === ".docx"  || substr($row['files'], -5) === ".docx" || substr($row['files_destination'], -5) === ".docs"  || substr($row['files'], -5) === ".docs" || substr($row['files_destination'], -4) === ".pdf"  || substr($row['files'], -4) === ".pdf" || substr($row['files_destination'], -5) === ".pptx"  || substr($row['files'], -5) === ".pptx")){
                                $str .= "
                                <center><a class = 'filebtn hfilebtn' style='width: 50%;max-width:50%; height:auto; margin-top:1%;position:sticky;' target = '_blank' href = '../uploads/{$post_row['filename']}'>{$post_row['filename']}</a></center><br>";
                            }
                            
                            
                            
                            $str .= "
                                <div id='viewcomment' class='commentOption' onClick='javascript:toggle$post_id()'> 
										   <br><a style='margin-left:1%;' href='#viewcomment'>View Comments...</a>
							</div>
                            
                            <hr class='dashed'>
                            <div class='post_comment' id='toggleComment$post_id' style='display:none;'>
							<iframe class='resp-iFrame' src='Student_Post.php?comment&post_id=$post_id&classCode=$code' id='comment_iframe' frameborder='0' style='position: relative;background-color:white; padding-left: 3%; width: 100% !important; height: 100% !important;  '></iframe>
								    
										</div>
                            <hr class='dashed'>";
                        


                        ?>
                        <?php
                         
                    }
                    
                    echo $str ;?>
                    <?php  

                }
                else{
                    ?> <center> <?php echo "There's no discussion yet!"; ?> </center> <?php
                }
                
                
                                
                                    // $post_id = $_REQUEST['editpost'];
                                    // $postquery = mysqli_query($conn, "SELECT * FROM post WHERE post_id LIKE '$post_id'");
                                    // $getpost = mysqli_fetch_array($postquery);
                                    // $body = $getpost['content'];

                                    ?>
                                    
                                <?php
                                  /*end of IF */
            ?> <!--end of PHP-->

        </div> <!-- end of card-body-->
        <br><br>

    </div> <!--end of main-->
    
</div> <!--end of container-fluid-->
    
</body>
</html>