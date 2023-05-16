<?php
    //DONEE
    session_start();
    include "../db_conn.php";
    if(!isset($_SESSION['studnum']))
    {
        header("Location: Student_Login.php?LoginFirst");
    }

    $student = $_SESSION["studnum"];
    $name = $_SESSION['name'];  
    date_default_timezone_set('Asia/Singapore');

    $stud_query = mysqli_query($conn, "SELECT * FROM student_info WHERE studentnum = '$student'");
    $fetch = mysqli_fetch_array($stud_query);
    $month = $fetch['month'];
    $day = $fetch['day'];
    $year = $fetch['year'];
    $email = $fetch['email'];
    $number = $fetch['contact_number'];



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

        // Update student profile
        if(isset($_POST['update_student']))
        {
            $contact_number = $_POST['contact'];
            $email_add = $_POST['emailadd'];
            $current_pass = $_POST['current_pass'];

            
    
            $query = mysqli_query($conn, "UPDATE student_info SET email = '$email_add', contact_number = '$contact_number' WHERE studentnum = '$student'");
            if($query)
            {
                if(!empty($current_pass))
                {
                    $query = mysqli_query($conn, "SELECT password FROM student_info WHERE studentnum = '$student'");
                    $fetch =  mysqli_fetch_array($query);
                    $password = $fetch['password'];

                    
                    if($password === $_POST['current_pass'])
                    {
                        $new_pass = $_POST['new_pass'];
                        $con_pass = $_POST['confirm_pass'];

                        if($new_pass === $con_pass)
                        {
                            $update = mysqli_query($conn, "UPDATE student_info SET password = '$new_pass' WHERE studentnum = '$student'");
                        }
                        else
                        {
                            header("Location: Student_ClassList.php?notmatch");
                            exit();
                        }
                    }
                    else
                    {
                        header("Location: Student_ClassList.php?incorrect");
                        exit();
                    }
                }
                    header("Location: Student_ClassList.php?updated");
                }
            else
            {
                header("Location: Student_ClassList.php?error");
            }
            
        }

?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Classroom</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel = "stylesheet" href = "../css/student_classlist.css">


</head>
<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

    <!--SIDE BAR-->
    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>
        <br><br>
            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png">
            <br><br>
         
            <h6 style="color: white;font-size:20px;"><b> Student </b></h6> <br><br><hr style="background-color:white;width:200px;">
            <a href="#" style="text-align:left;font-size:13.5px;" class="profile">&emsp;<i class= "fa fa-user-o" style="font-size: 1.3em;"></i>&emsp; Profile </a>
        </center>
    </div>

    <!--MAIN CONTENT -->
    <div class = "main">

        <!--TOP NAV-->
        <div class = "topnavbar" style="z-index:1;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
        <div class="topnavbar-right" id="topnav_right">
        <a target = "_self" href="../Logout.php?Logout=<?php echo $student ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>

        </div> <!--end of topnavbar-->

        <br><br><br>
        <?php
        if(isset($_REQUEST['updated']))
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
                title: 'Profile successfully updated!'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['error']))
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
                title: 'Something went wrong.'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['incorrect']))
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
                title: 'Password is incorrect.'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['notmatch']))
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
                title: 'New Password and Confirm Password didn\'t\' match.'
            })
            </script>
      <?php
        } if(isset($_REQUEST['loginsuccess']))
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
                title: 'Signed in successfully!'
            })
            </script>
      <?php
        } ?>

        <script>
            $('.profile').click(function(e){
                e.preventDefault();

                $('#profileModal').modal('show');
            })
        </script>

    <!-- MOBILE MODAL -->
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

        <!-- MODAL FOR STUDENT PROFILE -->
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="instructionsModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-lg" id="profile">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="instructionsModalLabel">Student Profile</h2>
                        </div>
                        <div class="modal-body">
                          <form method="POST">
                          <div class = "form-row">
                            <div class="form-group col-md-4">
                                <label for = ""><b>Student Number: </b></label>
                                <input type="text" class = "form-control" placeholder = "<?php echo $student; ?>" style="pointer-events: none;" disabled>
                            </div>

                            <div class="form-group col-md-4">
                                <label for = ""><b>Name: </b></label>
                                <input type="text" class = "form-control" placeholder = "<?php echo $name; ?>" style="pointer-events: none;" disabled>
                            </div>

                            <div class="form-group col-md-4">
                                <label for = ""><b>Contact Number: </b></label>
                                <input type="text" name = "contact" value = "<?php echo $number; ?>" class = "form-control" placeholder = "<?php echo $number; ?>" minlength="11" maxlength="11" pattern="[0-9]*" title="The contact Number requires 11 digits.">
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Email Address: </b></label>
                                <input type="email" name = "emailadd" value = "<?php echo $email; ?>" class = "form-control" placeholder = "<?php echo $email; ?>">
                            </div>
                            <div class="form-group col-md-2">
                                <label for = ""><b>Month: </b></label>
                                <input type="text" class = "form-control" placeholder = "<?php echo $month; ?>" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for = ""><b>Day: </b></label>
                                <input type="text" class = "form-control" placeholder = "<?php echo $day; ?>" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for = ""><b>Year: </b></label>
                                <input type="text" class = "form-control" placeholder = "<?php echo $year; ?>" disabled>
                            </div>
                            
                            <div class="form-group col-md-12" id="a_tag" style="text-align:center;margin-top:1%;">
                                <a href="#" type="button" id="change_pass" style="text-decoration:none;">Change Password</a>
                            </div>

                            <div class="form-group col-md-4 d-none" id = "current_pass">
                                <label for = ""><b>Current Password: </b></label>
                                <input type="password" name = "current_pass" placeholder="Enter your current Password" class = "form-control">
                            </div>

                            <div class="form-group col-md-4 d-none" id="new_pass">
                                <label for = ""><b>New Password: </b></label>
                                <input type="password" name = "new_pass" id="password" placeholder="Enter New Password" class = "form-control" title="Password must contain atleast 8 characters, 1 capital Letter, and 1 number." pattern="(?=.*\d)(?=.*[A-Z]).{8,}">
                            </div>

                            <div class="form-group col-md-4 d-none" id="confirm_pass">
                                
                                <label for = ""><b>Confirm Password: </b></label>
                                <input type="password" name = "confirm_pass" id="confirm-password" placeholder="Re-enter new Password" class = "form-control"  pattern="(?=.*\d)(?=.*[A-Z]).{8,}" oninput="checkPasswordMatch()">
                                <p id="password-match" style="text-align:center;color:red;"></p>
                            </div>
                            <script type="text/javascript">
                                $('#change_pass').click(() => {
                                    document.getElementById('a_tag').classList.add('d-none');
                                    document.getElementById('current_pass').classList.remove('d-none');
                                    document.getElementById('new_pass').classList.remove('d-none');
                                    document.getElementById('confirm_pass').classList.remove('d-none');
                                    
                                })
                            </script>
                            <script>
                            function checkPasswordMatch() {
                                var password = document.getElementById("password");
                                var confirmPassword = document.getElementById("confirm-password");

                                if (password.value != confirmPassword.value) {
                                    confirmPassword.setCustomValidity("Passwords do not match.");
                                    document.getElementById("password-match").textContent = "Passwords do not match.";
                                } else {
                                    confirmPassword.setCustomValidity("");
                                    document.getElementById("password-match").textContent = "";
                                }
                            }
                            </script>
                            
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
                        </div>
                        </form>
                    </div>
                </div>
        </div>
        
        
        
        
            
        <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Student Class List</h1><br>
        <div class="row row-cols-1 row-cols-md-2 g-4" id="col_row" style = "display: fixed; position: inherit;">
        <?php 
        
       $getClass = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum LIKE '%$student%' AND remove = 'no'");
            
            if(mysqli_num_rows($getClass) > 0)
            {
                    $str = "";

                    
                        while ($row = mysqli_fetch_array($getClass)) 
                        { 
                            $classCode = $row['class_code'];

                            $getClassInfo = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$classCode'");
                            if(mysqli_num_rows($getClassInfo) > 0)
                            {
                                while($row1 = mysqli_fetch_array($getClassInfo))
                                {
                                    $id = $row1['class_id'];
                                    $faculty = $row1['facultynum'];
                                    $course = $row1['course'];
                                    $yrsection = $row1['year_section'];
                                    $schoolyear =$row1['school_year'];
                                    $sem = $row1['semester'];
                                    $code = $row1['class_code'];

                                    $getAdviser = mysqli_query($conn, "SELECT * FROM adviser_info WHERE facultynum = '$faculty'");
                                    $fetchAdviser = mysqli_fetch_array($getAdviser);
                                    $AdviserName = $fetchAdviser['name'];
        
                                }
                            }

                        $str .= "<br>";
                        $str .= "
                        <div class = 'col'  style='margin-bottom:3%;'>
                        <div class = 'p-2'>
                            <div class='card style h-100' >
                                    <div class='card-body text-center' style='white-space:nowrap;'>
                                        <a class = 'text' href = 'Student_Dashboard.php?loginsuccess&classCode=$code'> 
                                             <h3>$course </h3>
                                        <div style='text-align:left;'>
                                        Year & Section: <b>$yrsection</b>
                                        <br>
                                            Semester: <b>$sem sem</b>
                                        <br>
                                            School Year: <b>$schoolyear</b>
                                            <br>
                                            Adviser: <b>$AdviserName</b>
                                        </div>
                                        
                                        <br>
                                        <button class = 'button hbutton' > 
                                           Visit Class &nbsp;
                                           <i class = 'fa fa-arrow-circle-right'></i>
                                        </button> 
                                        </a>  
                                    </div>
                                </div>
                            </div>
                            </div> ";
                            
                        } echo $str;
                    
                   
                       
                        
                       
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
                            <h5> You haven't joined any class yet. <br><br> Try to contact your Adviser.</h5>
                            </div>
                        </div>
                    </div>
                    ";
                    echo $str;
                } 
                            
        ?>

        <br>

                    
    </div> <!--End of main -->
    <script>
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
            function handleScreenSizeChange(tabletSize) {
                if (tabletSize.matches) 
                {
                    document.getElementById('topnav_right').classList.remove('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "12px";
                    document.getElementById('profile').style.maxWidth = "80%";
                    document.getElementById('col_row').classList.remove('row-cols-md-2');
                    document.getElementById('col_row').classList.add('row-cols-md-1');
                } 
                else
                {
                    document.getElementById('topnav_right').classList.add('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "13.5px";
                    document.getElementById('profile').style.maxWidth = "";
                    document.getElementById('col_row').classList.remove('row-cols-md-1');
                    document.getElementById('col_row').classList.add('row-cols-md-2');
                }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>

</div> <!--End of container-fluid -->
</body>
</html>