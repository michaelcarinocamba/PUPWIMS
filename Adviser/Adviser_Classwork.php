<?php
//DONE
    session_start();
    include "../db_conn.php";
    if(!isset($_SESSION['faculty']))
    {
        header("Location: Adviser_Login.php?LoginFirst");
    }

    $sql1 = "SELECT * FROM adviser_assignment ORDER BY assignment_id DESC";
    $result = mysqli_query($conn,$sql1);

    $faculty = $_SESSION['faculty'];
    $code = $_GET['classCode'];
    $name = $_SESSION['name'];
    $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row1 = mysqli_fetch_array($sql);
    $course = $row1['course'];
    $year_section = $row1['year_section'];
    date_default_timezone_set('Asia/Singapore');
    $date_today = date("Y-m-d\TH:i");


    $sql = "SELECT * FROM post WHERE class_code = '$code' ORDER BY post_id DESC";
    $result = mysqli_query($conn,$sql);

        if(isset($_REQUEST['save']))
        {
            $id = $_POST['id'];
            $getid = $_POST['get_id'];
            $code = $_REQUEST['classCode'];
            $ass_id = $_POST['ass_id'];
            $points = $_POST['point'];

            foreach ($getid as $a_id)
            {

                $assignmentqeury = mysqli_query($conn, "SELECT * FROM student_assignment WHERE class_code LIKE '$code' AND id LIKE '$a_id'");
                $assignmentrow = mysqli_fetch_array($assignmentqeury);
                $studnum = $assignmentrow['studentnum'];
    
                $adviserquery = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE facultynum = '$faculty' AND class_code = '$code' AND assignment_id = '$ass_id'");
                $assval = mysqli_fetch_array($adviserquery);
                $maxpoint = $assval['points'];
                $type = $assval['type'];
               
                
                $point = in_array($a_id, $getid) ? $points : '';
                 

                if($_POST['point'] !== "")
                {
                    if($type === "Graded")
                    {
                            $updategrade = mysqli_query($conn,"UPDATE student_assignment SET points = '$point', grade = '1', status = 'Returned' WHERE id LIKE '$a_id' AND class_code = '$code'");
                    }
                    else if($type === "Not Graded")
                    {
                        if($point === "0")
                        {
                            $updategrade = mysqli_query($conn,"UPDATE student_assignment SET points = '$point', grade = '1', status = 'Need to resubmit' WHERE id LIKE '$a_id' AND class_code = '$code'");
                        }
                        else if($point === "1")
                        {
                            $updategrade = mysqli_query($conn,"UPDATE student_assignment SET points = '$point', grade = '1', status = 'Accepted' WHERE id LIKE '$a_id' AND class_code = '$code'");
                        }
                    }
    
                    $notif_desc = "has returned your classwork.";
                    $date_time_now = date("Y-m-d H:i:s");
                    $notif_link = "Student_DashClasswork.php?classCode=$code";
                    $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code' and studentnum LIKE '$id'");
                    $getstudentname_row = mysqli_fetch_array($getstudentname);
                    $student_name = $getstudentname_row['name'];
                    if($getstudentname)
                    {
                        $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
                        
                    }
                    }
                    if($_POST['point'] === "")
                    {
    
                    }
                        
                
               
                
            }
            header("Location: Adviser_Classwork.php?classCode=$code&gradedsuccess"); 


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

<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

        <!--SIDE BAR-->
    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>

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
        <a href="Adviser_Dashboard.php?classCode=<?php echo $code ?>" style="font-size:13.5px;" id="topnav1"><i class="fa fa-home" style="width:15px;"></i>&emsp;Home </a>
            <a href="Adviser_Classwork.php?classCode=<?php echo $code ?>" style="font-size:13.5px;" id="topnav2"><i class="fa fa-book" style="width:15px;"> </i>&emsp;Classwork </a>
            <a href="Adviser_People.php?classCode=<?php echo $code ?>&Home" style="font-size:13.5px;" id="topnav3"><i class="fa fa-user" style="width:15px;"></i>&emsp;Students </a>
            <a href="Adviser_PartnerList.php?classCode=<?php echo $code ?>&Home" style="font-size:13.5px;" id="topnav4"><i class="fa fa-university" style="width:15px;"></i>&emsp;Partner Company</a>
            
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
                title: 'Classwork has been uploaded.'
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
                title: 'Classwork has been updated.'
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
                title: 'Classwork has been deleted.'
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
                title: 'Classwork has not been deleted.'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['maxpoint']))
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
                title: 'Inputted points exceed the maximum points available'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['gradedsuccess']))
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
                title: 'Successfully graded.'
            })
            </script>
      <?php
        } 
        if(isset($_REQUEST['retrievesuccess']))
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
                title: 'There was an error in updating classwork.'
            })
            </script>
      <?php
        }?>

        <script>
            function exportData() 
            {
                // Send an AJAX request to the PHP script
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'Adviser_Export.php?classCode=<?php echo $code ?>');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Create a link to download the CSV file
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(new Blob([xhr.responseText]));
                        link.download = 'Classwork_from_<?php echo $course ?>_<?php echo $year_section ?>.csv';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                };
                xhr.send();
            }

            
        </script>

            
                <!-- MODAL FOR TRASH  -->
                <div class="modal fade" id="classwork_delete" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl" id="trash_ass">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title fs-5" id="exampleModalLabel">Deleted Classworks</h3>
                                
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="table table-responsive">
                                        <table id="table">
                                        <thead>
                                            <tr>
                                            <th style='display: none;'></th>
                                            <th style='display: none;'></th>
                                            <th style="white-space: nowrap;">Classwork Title</th>
                                            <th>Type</th>
                                            <th>Start Date</th>
                                            <th>Due Date</th>
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $query1 = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE remove = 'yes' AND class_code = '$code'");

                                                if(mysqli_num_rows($query1)> 0){
                                                    while($row = mysqli_fetch_array($query1))
                                                    {
                                                        ?>
                                                        <tr> 
                                                        <td class="as_id" style='display: none;'><?php echo $row['assignment_id']; ?></td>
                                                        <td class="classCode" style='display: none;'><?php echo $row['class_code']; ?></td>
                                                        <td><?php echo $row['title']; ?></td>
                                                        <td><?php echo $row['type']; ?></td>
                                                        <td><?php echo $row['start_date']; ?></td>  
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


                        <!-- MODAL FOR RETRIEVE -->

                        <div class="modal fade" id="retrieve_studentModal" tabindex="-1" role="dialog" aria-labelledby="retrieve_studentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id = "retrieve_studentModalLabel"><b>CONFIRMATION</b></h5>
                                    </button>
                                </div>
                                <form action = "Adviser_Assignment.php" method = "POST">
                                <div class="modal-body">
                                <input type = "hidden" name = "get_id" id = "retrieve_id">
                                <input type = "hidden" name = "class" value = "<?php echo $code; ?>">
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


                            <!-- MODAL PERMANENTLY DELETE DATA-->
                        <div class="modal fade" id="permanent_deleteStudentModal" tabindex="-1"  role="dialog" aria-labelledby="permanent_deleteStudentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg"  role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id = "permanent_deleteStudentModalLabel"> <b>CONFIRMATION</b></h5>
                                    </div>

                                    <form action = "Adviser_Assignment.php" method = "POST">
                                        <div class="modal-body">
                                            <input type = "hidden" name = "get_id" id = "perm_delete_id">
                                            <input type = "hidden" name = "class" value = "<?php echo $code; ?>">
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
                                $('#classwork_delete').modal('hide');
                                var as_id = $(this).closest('tr').find('.as_id').text();
                                $('#perm_delete_id').val(as_id);
                                $('#permanent_deleteStudentModal').modal('show');
                                });

                                $('.retrieve_btn').click(function(e) {
                                e.preventDefault();
                                $('#classwork_delete').modal('hide');

                                var as_id = $(this).closest('tr').find('.as_id').text();
                                $('#retrieve_id').val(as_id);
                                $('#retrieve_studentModal').modal('show');
                                });
                            });
 
                        </script>
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Classworks </h1>
            <hr style="background-color:maroon;border-width:2px;"><br>
            <button type="button" class="btn btn-primary" style="float:right;background-color:maroon;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class = "fa fa-plus fa-1x"></i> &nbsp;
                   <b>Create Assignment</b> 
                </button>
            <button type="button" class="btn btn-danger" style="background-color:maroon;" onclick = "exportData()"><b><i class="fa fa-file-text-o"></i>&emsp;Export to CSV</b> </button>
            <button type="button" class="btn btn-danger" style="background-color:maroon;" data-bs-toggle="modal" data-bs-target="#classwork_delete"><b><i class="fa fa-trash"></i>&emsp;Deleted</b> </button>
            <br>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" id="assignment">
                    <div class="modal-content">
                    
                    <!--modal header-->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create an Assignment</h5>
                            
                    </div>

                    <!--modal body-->
                    <form action="Adviser_Assignment.php?class=<?php echo $code ?>" method="POST" enctype="multipart/form-data">
                        <div class="modal-body p-5">

                            <!--this is where the text areas are going to be placed-->
                            <div class="md-title">
                                <label for="exampleFormControlInput1" name="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" id="exampleFormControlInput1" placeholder="Title">
                            </div>
                            <br>

                            <div class="md-textarea">
                                <label for="exampleFormControlTextarea1" class="form-label">Notes</label>
                                <textarea name="instruction" id="instruction" class="form-control" placeholder="Notes" rows="3"></textarea>
                            </div>
                            <script>
                                CKEDITOR.replace('instruction');
                            </script>

                            <div class="md-file" data-tooltip='Documents acccepted are: JPG, JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLSX. Maximum File Size accepted is 50 MB.'>
                                <label for="formFile" class="form-label"></label>
                                <input class="form-control" type="file" name="file" id="formFile">

                                <style>
                        
                                .md-file:hover:after {
                                content:attr(data-tooltip);
                                }
            
                                </style>
                            </div>
                            <br>

                            <style type="text/css">
                            .d-none{
                                display: none;
                                    }
                            </style>

                            <h8>Type of Assignment</h8>
                            <div id ="graded" class="form-check">
                            <form>
                            <label>
                                <input type="radio" name="option" value="Graded" onchange = "enableGRD(this)" required> Graded
                            </label>
                            <script type="text/javascript">
                           function enableGRD(answer) {
                            console.log(answer.value);
                                if(answer.value == "Graded") {
                                    document.getElementById('point').classList.remove('d-none');
                                } else if(answer.value == "Not Graded") {
                                    document.getElementById('point').classList.remove('d-none');
                                }
                                 else {
                                    document.getElementById('point').classList.add('d-none');
                                }
                            }
                            </script>  
                            </div>
                            <div id="notgraded" class="form-check" onchange = "enableGRD(this)">
                            <label>
                                <input type="radio" name="option" value="Not Graded"> Not Graded
                            </label>    
                            </div>
                            
                            <br>

                            <div id="point" class="md-grade d-none">
                                <label for="grd" class="form-label">Points</label>
                                <input class="form-control form-control-sm" id="grd" type="text" name="points" placeholder="Possible points" aria-label=".form-control-sm example">
                            </div>
                            <br>
                            <div class = 'form-row'>
                            <div class='form-group col-md-6'>
                                <div class="md-grade">
                                    <label for="grd" class="form-label">Start Date</label>
                                    <input class="form-control form-control-sm" id="start_date" type="datetime-local" name="start_date" min="<?php echo $date_today; ?>" aria-label=".form-control-sm example">
                                </div>
                            </div>
                            <div class='form-group col-md-6'>
                                <div class="md-grade">
                                    <label for="grd" class="form-label">Due Date</label>
                                    <input class="form-control form-control-sm" id="end_date" type="datetime-local" name="due_date" min= "" aria-label=".form-control-sm example" disabled>
                                </div>
                            </div>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                            $(document).ready(function() {
                            $('#start_date').change(function() {
                                $('#end_date').prop('disabled', false);
                                $('#end_date').prop('min', $('#start_date').val());
                            });
                            });
                            </script>
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
                </div> <!--end of Modal-->

                <br>
            <?php
                $query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE facultynum = '$faculty' AND class_code = '$code' AND remove = 'no' ORDER BY assignment_id DESC");

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
                            $fileDiv = "
                            <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;' >
                            ";
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
                            if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png" || substr($row['files_destination'], -4) === ".JPG" || substr($row['files'], -4) === ".JPG" || substr($row['files_destination'], -5) === ".JPEG" || substr($row['files'], -5) === ".JPEG"  ||  substr($row['files_destination'], -4) === ".PNG" || substr($row['files'], -4) === ".PNG")
                            { $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    <div class = 'col-2'>Possible points: $points  </div>
                
                                    <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div>
                                    <br><br><div class = 'col-12 '> <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;'>  </div>
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
                            if(substr($row['files_destination'], -4) === ".jpg" || substr($row['files'], -4) === ".jpg" || substr($row['files_destination'], -5) === ".jpeg" || substr($row['files'], -5) === ".jpeg"  ||  substr($row['files_destination'], -4) === ".png" || substr($row['files'], -4) === ".png" || substr($row['files_destination'], -4) === ".JPG" || substr($row['files'], -4) === ".JPG" || substr($row['files_destination'], -5) === ".JPEG" || substr($row['files'], -5) === ".JPEG"  ||  substr($row['files_destination'], -4) === ".PNG" || substr($row['files'], -4) === ".PNG")
                            { $str .="
                                <div class = 'row text'>
                                    <div class = 'col-4'> Instructor $name  </div>
                                    <div class = 'col-6'> $date_added_display  </div>
                                    <br><br><div class = 'col-12' style='white-space:normal;'>Notes: <br>&emsp; $instruction  </div>
                                    <br><br><div class = 'col-12 '> <img src='$image' onclick='window.open(this.src)' title='Click Here To View Full Screen' style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;'>  </div>
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
                                    <div class='modal-dialog modal-lg'"; if($type === "Not Graded"){$str.="id='view_ass1'";}else{$str.="id='view_ass2'";} $str .=" >
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
                                                                $allowed  = array('jpg','jpeg','png', 'JPG', 'JPEG', 'PNG');
                                                                $fileDiv = "";
                                                                if (in_array($fileActualExt, $allowed)) {
                                                                    $fileDiv = "
                                                                        <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen'  style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;'>
                                                                            ";
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
                                                                            <td style='text-align:center;'> <span title='$file_title'> {$displayed_file}</span> </td>
                                                                            <td style='text-align:center;'><span title='$desc_title'> {$displayed_desc}</span> </td>
                                                                            <td style='text-align:center;'> {$name} </td>";
                                                                            if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png" || substr($tablerow['files_destination'], -4) === ".JPG" || substr($tablerow['files'], -4) === ".JPG" || substr($tablerow['files_destination'], -5) === ".JPEG" || substr($tablerow['files'], -5) === ".JPEG"  ||  substr($tablerow['files_destination'], -4) === ".PNG" || substr($tablerow['files'], -4) === ".PNG"){ 
                                                                                $str.= "
                                                                                <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'> View Assignment</a></td>"; 
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
                                                                                </div>
                                                                            </td>
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
                                                                        <td style='text-align:center;'> <span title='$file_title'> {$displayed_file} </span> </td>
                                                                        <td style='text-align:center;'><span title='$desc_title'> {$displayed_desc} </span></td>
                                                                        <td style='text-align:center;'> {$name} </td>";
                                                                        if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png" || substr($tablerow['files_destination'], -4) === ".JPG" || substr($tablerow['files'], -4) === ".JPG" || substr($tablerow['files_destination'], -5) === ".JPEG" || substr($tablerow['files'], -5) === ".JPEG"  ||  substr($tablerow['files_destination'], -4) === ".PNG" || substr($tablerow['files'], -4) === ".PNG"){
                                                                        
                                                                            $str .="
                                                                            <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Assignment</a></td>";" 
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
                                    <div class='modal-dialog modal-lg'"; if($type === "Not Graded"){$str.="id='update_ass1'";}else{$str.="id='update_ass2'";} $str .=">
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
                                                                    $allowed  = array('jpg','jpeg','png','JPG','JPEG','PNG');
                                                                    $fileDiv = "";
                                                                    
                                                                    if (in_array($fileActualExt, $allowed)) {
                                                                        $fileDiv = "
                                                                            <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen'  style='display:block;margin:0 auto;width:auto;min-width:50%;max-width:175px; height:auto;min-height:50%;max-height:250px;'>
                                                                                ";
                                                                    }
                                                                    $str .="
                                                                    <tr>
                                                                        <td style='text-align:center;'> {$date_display} </td>
                                                                        <td style='text-align:center;'> {$tablerow['file_name']} </td>
                                                                        <td style='text-align:center;'> {$tablerow['description']} </td>
                                                                        <td style='text-align:center;'> {$name} </td>";
                                                                        if(substr($tablerow['files_destination'], -4) === ".jpg" || substr($tablerow['files'], -4) === ".jpg" || substr($tablerow['files_destination'], -5) === ".jpeg" || substr($tablerow['files'], -5) === ".jpeg"  ||  substr($tablerow['files_destination'], -4) === ".png" || substr($tablerow['files'], -4) === ".png"){ $str.= "
                                                                            <td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='$path'>View Assignment</a> </td>"; 
                                                                        }
                                                                        else if(substr($tablerow['files_destination'], -4) === ".pdf" || substr($tablerow['files'], -4) === ".pdf" || substr($tablerow['files_destination'], -5) === ".docx" || substr($tablerow['files'], -5) === ".docx"  ||  substr($tablerow['files_destination'], -4) === ".doc" || substr($tablerow['files'], -4) === ".doc"  ||  substr($tablerow['files_destination'], -4) === ".ppt" || substr($tablerow['files'], -4) === ".ppt"  ||  substr($tablerow['files_destination'], -5) === ".pptx" || substr($tablerow['files'], -5) === ".pptx"  ||  substr($tablerow['files_destination'], -4) === ".xls" || substr($tablerow['files'], -4) === ".xls")
                                                                        {
                                                                            $str .= "<td style='text-align:center;'><a class = 'filebtn hfilebtn' target = '_blank' href='../uploads/{$tablerow['files']}'>View Assignment</a></td>";
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
?>
        </div>
</div> 
<script>
            
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize) {
            if (tabletSize.matches) 
            {
                document.getElementById('topnav_right').style.fontSize = "12px";
                document.getElementById('topnav_right').style.float = "left";
                document.getElementById('topnav1').style.fontSize = "12px";
                document.getElementById('topnav2').style.fontSize = "12px";
                document.getElementById('topnav3').style.fontSize = "12px";
                document.getElementById('topnav4').style.fontSize = "12px";
                document.getElementById('topnav1').style.padding = "14px 35px";
                document.getElementById('topnav2').style.padding = "14px 35px";
                document.getElementById('topnav3').style.padding = "14px 35px";
                document.getElementById('topnav4').style.padding = "14px 35px";
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
                document.getElementById('topnav1').style.fontSize = "13.5px";
                document.getElementById('topnav2').style.fontSize = "13.5px";
                document.getElementById('topnav3').style.fontSize = "13.5px";
                document.getElementById('topnav4').style.fontSize = "13.5px";
                document.getElementById('topnav1').style.padding = "14px 50px";
                document.getElementById('topnav2').style.padding = "14px 50px";
                document.getElementById('topnav3').style.padding = "14px 50px";
                document.getElementById('topnav4').style.padding = "14px 50px";
                document.getElementById('assignment').style.maxWidth = "";
                document.getElementById('edit_ass').style.maxWidth = "";
                document.getElementById('view_ass1').style.maxWidth = "80%";
                document.getElementById('view_ass2').style.maxWidth = "80%";
                document.getElementById('update_ass1').style.maxWidth = "80%";
                document.getElementById('update_ass2').style.maxWidth = "80%";
                document.getElementById('trash_ass').style.maxWidth = "";
            }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>
</body>
</html>