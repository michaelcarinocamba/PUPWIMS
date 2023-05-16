<?php

    session_start();
    include "../db_conn.php";

        
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    require '../db_conn.php';

    if(!isset($_SESSION['coordinator']))
    {
        header("Location: Coordinator_Login.php?LoginFirst");
    }

$coordnum = $_SESSION['coordinator'];
$message ="";
$query = "SELECT * FROM adviser_info ORDER by facultynum ASC";
    $result = mysqli_query($conn, $query);

    if(isset($_REQUEST['adddata']))
    {

         if(isset($_POST["upload"]))
        {
            if($_FILES['product_file']['name'])
                {
                $filename = explode(".", $_FILES['product_file']['name']);
                    if(end($filename) == "csv")
                    {
                    $handle = fopen($_FILES['product_file']['tmp_name'], "r");
                    while($data = fgetcsv($handle))
                    {
                        
                       
                        $studentnum = mysqli_real_escape_string($conn, $data[0]);
                        $month = mysqli_real_escape_string($conn, $data[1]);
                        $day = mysqli_real_escape_string($conn, $data[2]);
                        $year = mysqli_real_escape_string($conn, $data[3]);
                        $name = mysqli_real_escape_string($conn, $data[4]);
                        $email = mysqli_real_escape_string($conn, $data[5]);
                        $number = mysqli_real_escape_string($conn, $data[6]);
                        $password = uniqid(true);

                        if($number === null)
                        {
                            $number = "";
                        }
                        
                        $query = "INSERT INTO student_info VALUES ('', '$studentnum', '$password', 'no', '$month', '$day', '$year', '$name', '$email', '$number')";
                        mysqli_query($conn, $query);
                        
                        $mail = new PHPMailer(true);
                        $url = "http://pup-wims.site/Student/Student_Login.php";
                        try {
                            //Server settings
                                    //Enable verbose debug output
                            $mail->isSMTP();                                            
                            $mail->Host       = 'sg2plzcpnl493881.prod.sin2.secureserver.net';
                            $mail->SMTPAuth   = true;
                            $mail->Username   = 'pupwims_mailer@pup-wims.site';
                            $mail->Password   = 'pupwims123!';
                            $mail->SMTPSecure = 'ssl';     
                            $mail->Port       = 465;                                 
                
                            //Recipients
                            $mail->setFrom('pupwims_mailer@pup-wims.site', 'PUP WIMS Coordinator');
                            $mail->addAddress($email);     //Add a recipient           
                
                            
                
                            //Content
                            
                            $mail->isHTML(true);                                
                            $mail->Subject = 'PUPSJ Internship WebMail Account';
                            
                            $mail->Body    = "
                            <table style='border-collapse: collapse; min-width: 100%; font-family: Arial, Helvetica, sans-serif;'>
                                <thead>
                                    <tr style='background-color:#AA5656;'>
                                        <th style='color:white; font-size:25px; padding: 14px 30px;'> PUPSJ Internship Student Account</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style='background-color: #FFFBF5;border: 1px solid #ddd; font-family: Helvetica; font-size: 18px;margin-left:3%;'>  
                                        <br><p>&emsp;Hello and good day <b>$name</b>!<br><br>
                                        &emsp;We are happy to inform you that we have created PUP Internship WebMail account for you.<br><br>
                                        &emsp;You can now use the information below:<br><br>
                                        &emsp;&emsp;Username: <b> $studentnum </b><br>
                                        &emsp;&emsp;Password: <b> $password </b><br><br>
                                        &emsp;To access, go to <a href='$url'>this link</a> for you to proceed!<br><br>
                                        &emsp;<b>Notice:</b> Upon first sign in, you will be required to change password.<br><br>
                                        &emsp;If the password is not working, try using <i>Forgot Password?</i> Feature.<br>
                                        &emsp;Ignore this message if you can already access your account.
                                        </p>
                                        
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
                    }
                fclose($handle);
                header("location: Coordinator_Record_Student.php?coordnum=$coordnum&uploadsuccess");
                }
        else
        {
        $message = '<label class="text-danger">Please Select CSV File only</label>';
        header("location: Coordinator_Record_Student.php?coordnum=$coordnum&CSVunsuccess1");
        }
        }
        else
        {
        $message = '<label class="text-danger">Please Select File</label>';
        header("location: Coordinator_Record_Student.php?coordnum=$coordnum&CSVunsuccess2");
        }

        if(isset($_GET["updation"]))
        {
        $message = '<label class="text-success">Adviser Update Done</label>';
        header("location: Coordinator_Record_Student.php?coordnum=$coordnum&CSVunsuccess3");
        }

        }
                    
        if (isset($_POST['checking_editbtn']))
        {
            $s_id = $_POST['student_id'];
            $result_array = [];
            
            $query = "SELECT * FROM student_info WHERE student_id = '$s_id'";
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
            $s_id = $_POST['edit_id'];
            $student_num = $_POST['studentnum'];
            $student_name = $_POST['name'];
            $student_email = $_POST['emailadd'];


            $query = mysqli_query($conn, "UPDATE student_info SET studentnum = '$student_num', name = '$student_name', email = '$student_email' WHERE student_id = '$s_id'");
            $update_record = mysqli_query($conn, "UPDATE student_record SET studentnum = '$student_num', name = '$student_name', email = '$student_email' WHERE student_id = '$s_id'");
            

            if ($query)
            {
                header("Location: Coordinator_Record_Student.php?coordnum=$coordnum&updatesuccess");
            }
    
            else
            {
                header("Location: Coordinator_Record_Student.php?coordnum=$coordnum&updateunsuccess");
            }
        }
    }


    if(isset($_REQUEST['coordnum']))
    {
        ?>
            <!DOCTYPE html>
            <html lang="en" style = "height: auto;">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title>Student</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
                <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
                <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <link rel = "stylesheet" href = "../css/coordinator_recordlist.css">

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
                    width: 100%;
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
                    <div class = "topnavbar" style="z-index:1;">
                    <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
                        <a href="Coordinator_Record_Adviser.php?coordnum=<?php echo $coordnum ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-user-plus" style="font-size: 1.3em;"></i>&emsp;Adviser </a>
                        <a href="Coordinator_Record_Student.php?coordnum=<?php echo $coordnum ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-user-plus" style="font-size: 1.3em;"></i>&emsp;Student </a>
                        <div class="topnavbar-right" id="topnav_right">
                            <a target = "_self" href="../Logout.php?Logout=<?php echo $coordnum ?>" > 
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
                                document.getElementById('topnav_right').classList.remove('topnavbar-right');
                            } 
                            else
                            {
                                document.getElementById('topnav_right').classList.add('topnavbar-right');
                            }
                        }

                        tabletSize1.addListener(handleScreenSizeChange);
                        handleScreenSizeChange(tabletSize1);
                    </script>
                    <?php
                   
                ?>

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
                                title: 'Student Account Successfully Added.'
                            })
                            </script>
                    <?php
                        } 
                        if(isset($_REQUEST['CSVunsuccess1']))
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
                                title: 'Please Select CSV File only'
                            })
                            </script>
                            <?php
                                }
                                if(isset($_REQUEST['CSVunsuccess2']))
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
                                        title: 'Please Select File'
                                    })
                                    </script>
                            <?php
                                }
                                if(isset($_REQUEST['CSVunsuccess3']))
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
                                        title: 'Company List Upload Done'
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
                                    title: 'Update successfully'
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
                                        title: 'Update Unsuccess'
                                    })
                                    </script>
                                    <?php } ?>
                            
                    
                    <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Internship Record (Student) 
                    
                    
                       </h1>
                    
                    <hr style="background-color:maroon; border-width:2px;">
                    <button type="button" class="btn btn-primary" style="background-color:maroon; float:right;" data-toggle="modal" data-target="#myModal"> <b>+ ADD STUDENT ACCOUNT</b> </button>
                     <br>

                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                <div class="modal-header">
                                <h5 class="modal-title"><b>UPLOAD CSV FILE</b></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <form action="Coordinator_Record_Student.php?adddata" method="post" enctype='multipart/form-data'>
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4" style="text-align:center; margin-top: 10%;">
                                            
                                                
                                                <label for = ""><b>Place CSV Folder Here</b></label><br>
                                                <p>&emsp;&emsp;<input type="file" name="product_file" /></p>
                                            
                                            <br><br><br>
                                            <a class="btn btn-primary" style="background-color:maroon;" href="../csvfiles/sample_student_registration.csv" download><i class="fa fa-download" aria-hidden="true"></i> <b> &nbsp; Download Template </b></a>
                                        </div>
                                    
                                    <div class="form-group col-md-8" >
                                        <p class = indent1><b>Notes:</b> Uploading CSV File may take some time to upload due to sending email to every entry/entries.</p> 
                                        <p class = indent1>1. Use the "Download Template" button to export the CORRECT CSV format.</p>
                                        <p class = indent1>2. Open the downloaded format in a spreadsheet application such as Microsoft Excel and alike.</p>
                                        <p class = indent1>3. Upon opening the downloaded format, you would see sample data within the first column of the spreadsheet. Use the sample columns as a guide and replace the data provided with the data that you want to upload.</p>
                                        <p class = indent1>4. Enter your data in the appropriate columns, making sure that each cell is correctly formatted.</p>
                                        <p class = indent1>5. Save the document as a CSV (Comma Delimited) (*.csv) file by selecting "Save As" or "Export" from the file menu and selecting "CSV (Comma Delimited) (*.csv)" as the file format.</p>
                                        <p class = indent1>6. Once you have saved your CSV file, you can upload it to the systems uploading bin for csv files. When uploading the file, make sure to check that the delimiter is correctly set.</p>
                                        <p class = indent1>7. Remember to double-check the formatting and content of your CSV file before uploading to avoid errors or data loss.</p>
                                        
                                            <style type="text/css">
                                                .indent1 { margin-left:40px; }
                                            </style>
                                    </div>
                                </div>
                                </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name = "upload" class="btn btn-primary" style="background-color:maroon;">Upload</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>

                      

                        <!-- Modal EDIT DATA -->          
        <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"  role="document">
                <div class="modal-content">

                

                        <div class="modal-header">
                            <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                           
                        </div>

                        
                            <div class="modal-body">
                                <form action = "Coordinator_Record_Student.php?adddata" method = "POST">
                                <input type = "hidden" name = "edit_id" id = "edit_id">
                                <div class = "form-row">
                                <div class="form-group col-md-3">
                                    <label for = ""><b>Student Number: </b></label>
                                    <input type="text" name = "studentnum" id = "edit_studentnum" class = "form-control" placeholder = "Enter Student Number" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for = ""><b>Name: </b></label>
                                    <input type="text" name = "name" id = "edit_name" class = "form-control" placeholder = "Enter Name" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Email Address: </b></label>
                                    <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" placeholder = "Enter Email Address" required>
                                </div>

                                    
                                </div>
                                

                            </div><!--end of modal body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

                        <!-- Modal EDIT DATA -->          
        <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"  role="document">
                <div class="modal-content">

                

                        <div class="modal-header">
                            <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                           
                        </div>

                        
                            <div class="modal-body">
                                <form action = "Coordinator_Record_Student.php?adddata" method = "POST">
                                <input type = "hidden" name = "edit_id" id = "edit_id">
                                <div class = "form-row">
                                <div class="form-group col-md-3">
                                    <label for = ""><b>Student Number: </b></label>
                                    <input type="text" name = "studentnum" id = "edit_studentnum" class = "form-control" placeholder = "Enter Student Number" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for = ""><b>Name: </b></label>
                                    <input type="text" name = "name" id = "edit_name" class = "form-control" placeholder = "Enter Name" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for = ""><b>Email Address: </b></label>
                                    <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" placeholder = "Enter Email Address" required>
                                </div>

                                    
                                </div>
                                

                            </div><!--end of modal body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
                        
                    <br>
                        
                   
                    <!-- changes -->
                    <?php 
                        $checkTeaching = false;
                        $data_query = mysqli_query($conn, "SELECT * FROM adviser_info ORDER BY faculty_id ASC");
                        
                        if(mysqli_num_rows($data_query) > 0)
                        {
                            $checkTeaching = true;

                            if($checkTeaching == true)
                            {
                                $query = "SELECT * FROM student_info ORDER by studentnum ASC";
                                $result = mysqli_query($conn, $query);  
                                ?>
                                <div class="card">
                                    <div class="card-body">
                                    <div class="table table-responsive">
                                    <table id="table">
                                            <thead>
                                                <tr>
                                                    <th style='display: none;'></th>
                                                <th>Student Number</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    <?php
                                                    if(mysqli_num_rows($result) > 0)
                                                    {
                                                        while($row = mysqli_fetch_array($result))
                                                        {
                                                            
                                                            
                                                            
                                                            ?>
                                                            <tr>
                                                                <td class="stud_id" style='display: none;'><?php echo $row['student_id']; ?></td>
                                                                <td><?php echo $row['studentnum']; ?></td>
                                                                <td><?php echo $row['name']; ?></td>
                                                                <td><?php echo $row['email']; ?></td> 
                                                                <td>
                                                                <div class="btn-group" role="group" aria-label="Button Group">
                                                                
                                                                    <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn btn-sm" data-bs-toggle='modal' data-bs-target='#editStudentModal'><i class='fa fa-edit'></i></a>
                                                                    
                                                                </td>
                                                                
                                                            </tr>
                                                            <?php }
                                                            ?>

                                                            
                                                            <?php
                                                        }
                                                    
                                                    else{
                                                        echo "<h5> NO RECORD FOUND!</h5>";
                                                    }
                                                    ?>
                                            </tbody>
                                        </table>

                                    </div>
                                    </div>
                                </div>
                                <?php
                            }

                        }
                        else
                        {
                            $str = "
                        <br>
                        <div class='card-deck'>
                            <div class='card style'>
                                <div class='card-body text-center'>
                                <br>
                                <h4> There's no record of adviser/s yet!</h4>
                                </div>
                            </div>
                        </div>
                        ";
                        echo $str;
                        } //end of if 
                                        
                    ?>
                    
                    <br><br>
                                
                </div> <!--End of main -->

            </div> <!--End of container-fluid -->
            <script>
            $(document).ready(function() {
                

                $('.edit_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                
                $.ajax({
                type: "POST",
                url: "Coordinator_Record_Student.php?adddata",
                data: {
                    'checking_editbtn': true,
                    'student_id': stud_id,
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
                        [-1, 10, 15, 20, 30, 50],
                        ['All', 10, 15, 20, 30, 50 ],
                        ],
                    });
                    });
            </script>

                </div><!--end of main -->

                
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
            </body>
            </html>
        <?php
    }

?>