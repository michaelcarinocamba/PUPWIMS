<?php 
    session_start();
    include '../db_conn.php';
    if(!isset($_SESSION['studnum']))
    {
        header("Location: Student_Login.php?LoginFirst");
    }
   

    $studnum = $_SESSION['studnum'];
    $name = $_SESSION['name'];
    $code = $_SESSION['code'];


    if(isset($_REQUEST['workID']) && isset($_REQUEST['classCode']))
    {
        $workID = $_REQUEST['workID'];
        $ifemptyquery = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id LIKE '$workID' AND studentnum LIKE '$studnum'");

        if(mysqli_num_rows($ifemptyquery) === 0){

            $classwork_query1 = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$workID' AND studentnum = '$studnum'");
            $classwork_row1 = mysqli_fetch_array($classwork_query1);

            $classwork_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$workID' AND class_code = '$code'");
            $classwork_row = mysqli_fetch_array($classwork_query);
            $id = $classwork_row['assignment_id'];
            $profname = $classwork_row['facultynum'];
            $classwork_date = $classwork_row['date_added'];
            $classinstruction = $classwork_row['instruction'];
            $classpoints = $classwork_row['points'];
            $path = $classwork_row['files_destination'];
            $classfile = $classwork_row['files'];

            $fileExt = explode('.', $classfile);
            $fileActualExt = end($fileExt);
            $allowed  = array('jpg','jpeg','png');
            $fileDiv = "";
            if (in_array($fileActualExt, $allowed)) {
            $fileDiv = "<div id='postedFile'>
            <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen' width='80%' height='auto' >
            </div>";
        }
                                
        //display the assignment details kapag wala pa nasasubmit
        if(substr($classwork_row['files_destination'], -4) === ".jpg" || substr($classwork_row['files'], -4) === ".jpg" || substr($classwork_row['files_destination'], -5) === ".jpeg" || substr($classwork_row['files'], -5) === ".jpeg"  ||  substr($classwork_row['files_destination'], -4) === ".png" || substr($classwork_row['files'], -4) === ".png")
        { 
           
            echo "<br>". 
                    "<div class = 'row text'>".
                    "<div class = 'col-4'> Prof. ". $profname . "</div>". 
                    "<div class = 'col-6'>". $classwork_date . "</div>".
                    "<div class = 'col-2'>Points: &emsp;" . "/" . $classpoints . "</div>".

                    "<br><br><div class = 'col-12'> Instructions: &emsp;" . $classinstruction .  "</div>".
                    "<br><br><div class = 'col-12 '>". $fileDiv . "</div>".
                 "</div> <br><br>";
        }
        
        else{
            echo "<br>". 
            "<div class = 'row text'>".
                "<div class = 'col-4'> Prof. ". $profname . "</div>". 
                "<div class = 'col-6'>". $classwork_date . "</div>".
                "<div class = 'col-2'>Points: &emsp;" . "/" . $classpoints . "</div>".

                "<br><br><div class = 'col-12'> Instructions: &emsp;" . $classinstruction .  "</div>".
                "<br><br><div class = 'col-12 '>".  "<br>" . "<a href = '../uploads/{$classwork_row['files']}'>{$classwork_row['files']}</a>" . "<br>". "</div>".
            "</div> <br><br>";
        }


        if(isset($_POST['upload']))
        {
            $assignment_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$workID'");
            $assignment_row = mysqli_fetch_array($assignment_query);
            $assignment_due = $assignment_row['due_date'];
            $assignmentid = $assignment_row['assignment_id'];
            $filetypename = $_POST['name'];
            $filetypename = strip_tags($filetypename);
            $filetypename = mysqli_real_escape_string($conn, $filetypename);
            $check_empty = preg_replace('/\s+/', '', $filetypename);
            $descript = $_POST['desc'];
            date_default_timezone_set('Asia/Singapore');
            $date_added = date("Y-m-d h:i:s");

            $file = $_FILES['file'];
            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileError = $_FILES['file']['error'];
            $fileType = $_FILES['file']['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'ppt', 'xls');

            if($check_empty != "" && $fileName == "")
            {
                $query = mysqli_query($conn, "INSERT INTO student_assignment VALUES('','$assignmentid', '$studnum', '$filetypename', '$descript', '$code', '$date_added', '$assignment_due', '', '','','')");
                header ("Location: Student_addclassworklog.php?workID=$id&classCode=$code");
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

                            $sql = mysqli_query($conn, "INSERT INTO student_assignment VALUES('','$assignmentid', '$studnum', '$filetypename', '$descript', '$code', '$date_added', '$assignment_due','$fileName', '', '$fileDestination', '')");
                            header ("Location: student_addclassworklog.php?workID=$id&classCode=$code");
                        }
                    
                        else{
                            echo "Your file is too big!";
                        }
                    }
                
                    else{
                        echo "There was an error uploading your file.";
                    }
                }
        
                else{
                    echo "You cannot upload files of this type!";
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

    <title>Documents</title> <link rel="shortcut icon" href= "images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <link rel = "stylesheet" href = "../css/student_dashclasswork.css">
    
</head>
<body style=" background-image: url(../images/GREY-BG.png); background-size: cover; background-position: center; background-repeat: repeat; height: 100vh; margin: 0;">

    <div id="createAssign" class="content">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                 Submit Assignment
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Submit an Assignment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body p-5">

                        <!--this is where the text areas are going to be placed-->
                        <div class="md-file">
                            <label for="formFile" class="form-label"></label>
                            <input class="form-control" type="file" name="file" id="formFile">
                        </div>
                    
                        <br>

                        <div class="md-title">
                            <label for="exampleFormControlInput1" name="title" class="form-label">File Name</label>
                            <input type="text" name="name" class="form-control" id="exampleFormControlInput1" placeholder="Title">
                        </div>
                    
                        <br>

                        <div class="md-textarea">
                            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                            <textarea name="desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Instructions" rows="3"></textarea>
                         </div>

                    
                    </div> <!--end of modal-body-->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="Upload" class="btn btn-primary">Submit</button>
                    </div>
                
                </div><!--end of modal-content-->

            </div> <!--end of modal-dialog-->
            </div> <!--end of modal fade-->

                
        </form>

        </body>
</html>

<?php }
    else if (mysqli_num_rows($ifemptyquery) === 1)
    {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

        <link rel = "stylesheet" href = "../css/student_dashclasswork.css">
    </head> 

<?php
    $classwork_query1 = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$workID' AND studentnum = '$studnum'");
    $classwork_row1 = mysqli_fetch_array($classwork_query1);
    $ass_id = $classwork_row1['assignment_id'];
    $classpoint = $classwork_row1['points'];
    $added_by = $classwork_row1['studentnum'];


    $classwork_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$workID' AND class_code = '$code'");
    $classwork_row = mysqli_fetch_array($classwork_query);
    $id = $classwork_row['assignment_id'];
    $profname = $classwork_row['facultynum'];
    $classwork_date = $classwork_row['date_added'];
    $classinstruction = $classwork_row['instruction'];
    $classpoints = $classwork_row['points'];
    $path = $classwork_row['files_destination'];
    $classfile = $classwork_row['files'];

    $fileExt = explode('.', $classfile);
    $fileActualExt = end($fileExt);
    $allowed  = array('jpg','jpeg','png');
    $fileDiv = "";
    
    if (in_array($fileActualExt, $allowed)) 
    {
        $fileDiv = "<div id='postedFile'>
        <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen' width='50%' height='auto' >
        </div>";
    }
                                

    //display the assignment details kapag may nasubmit na 
    if(substr($classwork_row['files_destination'], -4) === ".jpg" || substr($classwork_row['files'], -4) === ".jpg" || substr($classwork_row['files_destination'], -5) === ".jpeg" || substr($classwork_row['files'], -5) === ".jpeg"  ||  substr($classwork_row['files_destination'], -4) === ".png" || substr($classwork_row['files'], -4) === ".png")
    { 
        echo "<br>". 
        "<div class = 'row text'>".
           "<div class = 'col-4'> Prof. ". $profname. "</div>". 
           "<div class = 'col-3'>". $classwork_date. "</div>".
           "<div class = 'col-3'>Points: &emsp;". $classpoint. "/" . $classpoints. "</div>".
           
           "<br><br><div class = 'col-12'> Instructions: &emsp;". $classinstruction .  "</div>".
           "<br><br><div class = 'col-12'>". $fileDiv. "</div>".
          
        "</div>". "<br><br>";
    }
        
    else{
        echo "<br>". 
        "<div class = 'row text'>".
            "<div class = 'col-4'> Prof. ". $profname . "</div>". 
            "<div class = 'col-6'>". $classwork_date . "</div>".
            "<div class = 'col-2'>Points: &emsp;" . $classpoint. "/" . $classpoints. "</div>".

            "<br><br><div class = 'col-12'> Instructions: &emsp;" . $classinstruction .  "</div>".
            "<br><br><div class = 'col-12 '>".  "<br>" . "<a href = '../uploads/{$classwork_row['files']}'>{$classwork_row['files']}</a>" . "<br>". "</div>".
        "</div> <br><br>";
    }
    
    echo "<br>" . 
         "<div class = 'text'>You've already submitted!</div>" . "<br>" . "
         <form method='post'><button class='btn btn-primary' type='submit' name='unsubmit'>Unsubmit Classwork</button></form>";
}
if(isset($_POST['unsubmit']))
{
    $query = mysqli_query($conn, "DELETE FROM student_assignment WHERE assignment_id = '$workID' AND studentnum = '$studnum'");
    header("Location: Student_addclassworklog.php?workID=$id&classCode=$code");
}
}
$str = "";
if(isset($_POST['unsubmit']))
{
    $get_id = $_POST['id'];
    $classwork_query1 = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id = '$get_id' AND studentnum = '$studnum'");
    $classwork_row1 = mysqli_fetch_array($classwork_query1);
    $ass_id = $classwork_row1['assignment_id'];
    $ass_classpoint = $classwork_row1['points'];
    $ass_added_by = $classwork_row1['studentnum'];
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
        <img src='$ass_path' onclick='window.open(this.src)' title='Click Here To View Full Screen' width='100px' height='100px' >
        </div>";
    }


    $classwork_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$get_id' AND class_code = '$code'");
    $classwork_row = mysqli_fetch_array($classwork_query);
    $id = $classwork_row['assignment_id'];
    $profname = $classwork_row['facultynum'];
    $classwork_date = $classwork_row['date_added'];
    $classinstruction = $classwork_row['instruction'];
    $classpoints = $classwork_row['points'];
    $path = $classwork_row['files_destination'];
    $classfile = $classwork_row['files'];

    $fileExt = explode('.', $classfile);
    $fileActualExt = end($fileExt);
    $allowed  = array('jpg','jpeg','png');
    $fileDiv = "";
    
    if (in_array($fileActualExt, $allowed)) 
    {
        $fileDiv = "<div id='postedFile'>
        <img src='$path' onclick='window.open(this.src)' title='Click Here To View Full Screen' width='50%' height='auto' >
        </div>";
    }
    ?>
<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - Classwork</title> <link rel="shortcut icon" href= "images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


            <link rel = "stylesheet" href = "../css/adviser_dashclasswork.css">
    
</head>

<body style=" background-image: url(../images/GREY-BG.png); background-size: cover; background-position: center; background-repeat: repeat; height: 100vh; margin: 0;">
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->
    
        <!--SIDE BAR-->
        <div id="sidebar" class="sidebar">
            <center>
            <!--Background Side Bar-->
            <img src="../images/Background1.png" alt="background" class = "sidebar_bg">
                <br><br>
                <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>
                
                <h5 class="font-weight-bold" style = "color: white;" >OPTIONS</h5> <br>

                <a href="Student_DashHome.php">Dashboard </a>
                <a href="Student_DTR.php">Time In/ Time Out</a> 
                <a href="Student_Calendar.php?classCode=<?php echo $code ?>">Schedule Mentoring</a>
            </center>
        </div>

        
        <!--MAIN CONTENT -->
        <div class = "main">

            <!--TOP NAV-->
            <div class = "topnavbar">
                <a href="Student_DashHome.php"> Home </a>
                <a href="Student_DashClasswork.php?classCode=<?php echo $code ?>"> Classwork </a>
                <a href="Student_DashDocument.php"> Documents </a>
                <?php
                    $query = mysqli_query($conn, "SELECT * FROM student_evaluation WHERE studentnum = '$studnum'");
                    if(mysqli_num_rows($query) === 0){ ?>
                    <a href="Student_Evaluation.php?classCode=<?php echo $code ?>"> Evaluation </a>
                        <?php }  ?>

                <div class="topnavbar-right">
                    <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                        Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                    </a>
                </div>
            </div> <!--end of topnavbar-->

            <br><br><br><br>
            
            <h1 class="font-weight-bold">Classworks</h1><br>


<?php

$checkclasswork = false;
                $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
                $row = mysqli_fetch_array($sql);
                if(!empty($row)){
                    $faculty = $row['facultynum'];
                }
                $data_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE facultynum = '$faculty' AND class_code = '$code' ORDER BY assignment_id DESC");
                
                if(mysqli_num_rows($data_query)>0)
                {
                    $checkclasswork=true;$checksubmit = "";
                    
                    if ($checkclasswork==true)
                    {
                        $str = "";
                        $data_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE facultynum = '$faculty' AND class_code = '$code' ORDER BY assignment_id DESC");
                    
                        if(mysqli_num_rows($data_query)>0)
                        {

                            while($row=mysqli_fetch_array($data_query))
                            {
                                $id = $row['assignment_id'];
                                $title = $row['title'];
                                $name = $row['name'];
                                $instruction = $row['instruction'];
                                $date_added = $row['date_added'];
                                $due_date = $row['due_date'];
                                $points = $row['points'];
                                $image = $row['files_destination'];

                                $classwork_query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$id' AND class_code = '$code'");
                                $classwork_row = mysqli_fetch_array($classwork_query);
                                $class_id = $classwork_row['assignment_id'];
                                $class_profname = $classwork_row['facultynum'];
                                $class_classwork_date = $classwork_row['date_added'];
                                $class_classinstruction = $classwork_row['instruction'];
                                $class_classpoints = $classwork_row['points'];
                                $class_path = $classwork_row['files_destination'];
                                $class_classfile = $classwork_row['files'];

                                $fileExt = explode('.', $class_classfile);
                                $fileActualExt = end($fileExt);
                                $allowed  = array('jpg','jpeg','png');
                                $fileDiv = "";
                                
                                if (in_array($fileActualExt, $allowed)) 
                                {
                                    $fileDiv = "<div id='postedFile'>
                                    <img src='$class_path' onclick='window.open(this.src)' title='Click Here To View Full Screen' height='auto' width='60%' border-radius='10px' >
                                    </div>";
                                }

                                
                                $checkquery = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id LIKE '$id'");
                                

                                if(mysqli_num_rows($checkquery) === 1)
                                {
                                    $checksubmit = "Submitted";
                                }
                                else
                                {
                                    $checksubmit = "Not submitted";
                                }

			?> <!--end of first php -->
                    
                                <script>
                                    function toggle<?php echo $class_id; ?>() {
                                        var element = document.getElementById("toggleClass<?php echo $class_id; ?>");

                                        if (element.style.display == "block")
                                            element.style.display = "none";
                                        else
                                            element.style.display = "block";
                                        
                                    } //end of function
                                    
                                    
                                </script>

                                <?php


                                    $str .= "<br>
                                    <div class='card-body stylebox' onClick='javascript:toggle$class_id(); myFunction(this)' >
                                            <div class='card-body stylebox' style='background-color: maroon'>
                                            
                                                <div class='row'>
                                                    <div class='col-8'>
                                                        <b style = 'font-weight: 600;color:white;'>Assignment Title: $title </b>
                                                    </div>


                                                    <div class='col-4' style = 'color:white;'>
                                                        Due Date: $due_date &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; $checksubmit
                                                        </div>
                                                        
                                                        <br>
                                                    </div>

                                                    
                                                </div>";
                                    $ifemptyquery = mysqli_query($conn, "SELECT * FROM student_assignment WHERE assignment_id LIKE '$get_id' AND studentnum LIKE '$studnum'");
                                    if (mysqli_num_rows($ifemptyquery) === 0)
                                    {
                                        $str .="
                                            <div class='card-body display' id='toggleClass$class_id'>
                                            ";
                                                if(substr($classwork_row['files_destination'], -4) === ".jpg" || substr($classwork_row['files'], -4) === ".jpg" || substr($classwork_row['files_destination'], -5) === ".jpeg" || substr($classwork_row['files'], -5) === ".jpeg"  ||  substr($classwork_row['files_destination'], -4) === ".png" || substr($classwork_row['files'], -4) === ".png")
                                                { 
                                                    $str .="
                                                    <div class = 'row text'>
                                                    <div class = 'col-4'> Prof. $profname  </div>
                                                    <div class = 'col-6'> $classwork_date  </div>
                                                    <div class = 'col-2'>Points: $ass_classpoint /  $classpoints  </div>

                                                    <br><br><div class = 'col-12'> Instructions: &emsp; $classinstruction </div>
                                                    <br><br>
                                                    <div class = 'col-12' style='border: 1px solid black;'> $fileDiv</div></div>";
                                                    
                                                    if(substr($classwork_row1['files_destination'], -4) === ".jpg" || substr($classwork_row1['files'], -4) === ".jpg" || substr($classwork_row1['files_destination'], -5) === ".jpeg" || substr($classwork_row1['files'], -5) === ".jpeg"  ||  substr($classwork_row1['files_destination'], -4) === ".png" || substr($classwork_row1['files'], -4) === ".png")
                                                    {
                                                        $str .="
                                                        <br><br><hr class='dashed'><h4> Your work </h4><div class = 'row text'><div style='position: relative; width: 150px;border: 1px solid black;padding: 25px;margin-right:1%;margin-bottom:1%;'>$fileDiv1 <br><center><div style='position:asolute; margin-right:20%; width:60%; '><a class = 'filebtn hfilebtn' style='font-size: 10px;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>View Picture</a></div></center> </div>
                                                        <center><div style='width: 300px;border: 1px solid black;padding: 15px;margin: 20px;'>File name: $ass_file_name <br> Description: $ass_desc </div></center></div>";
                                                    }
                                                    else
                                                    {
                                                        $str .="
                                                        <br><br><hr class='dashed'><h4> Your work </h4>
                                                        <div class = 'row text'>
                                                        <center><a class = 'filebtn hfilebtn' style='width: auto; height:25%; margin-top:20%;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>{$classwork_row1['files']}</a></center>
                                                        <div style='width: 300px;border: 1px solid black;padding: 15px;margin: 20px;'>File name: $ass_file_name <br> Description: $ass_desc </div>
                                                        </div>
                                                        ";
                                                    }
                                                
                                                }
                                                else{
                                                    $str .="<div class = 'row text'>
                                                    <div class = 'col-4'> Prof. $profname  </div>
                                                    <div class = 'col-6'> $classwork_date  </div>  <div class = 'col-2'>Points: $ass_classpoint /  $classpoints  </div>
                                                    <br><br><div class = 'col-12'>Instructions: &emsp; $classinstruction  </div><br><br>
                                                    <div class = 'col-12' style=' margin-left:1%;' ><a class = 'filebtn hfilebtn' target = '_blank' href = '../uploads/{$classwork_row['files']}'>{$classwork_row['files']}</a> </div>";
                                                    
                                                    if(substr($classwork_row1['files_destination'], -4) === ".jpg" || substr($classwork_row1['files'], -4) === ".jpg" || substr($classwork_row1['files_destination'], -5) === ".jpeg" || substr($classwork_row1['files'], -5) === ".jpeg"  ||  substr($classwork_row1['files_destination'], -4) === ".png" || substr($classwork_row1['files'], -4) === ".png")
                                                    {
                                                        $str .="
                                                        <br><br><hr class='dashed'><h4> Your work </h4><div class = 'row text'><div style='position: relative; width: 250px;border: 1px solid black;padding: 15px;margin: 20px;'>$fileDiv1 <center><div style='position:asolute; margin-left:40%; width: 30px; '><a class = 'filebtn hfilebtn' style='font-size: 12px;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>View File</a></div></center> </div>
                                                    <center><div style='width: 300px;border: 1px solid black;padding: 15px;margin: 20px;'>File name: $ass_file_name <br> Description: $ass_desc </div></center></div>";
                                                    }
                                                    else
                                                    {
                                                        $str .="
                                                        </div> <br><br><hr class='dashed'><h4> Your work </h4>
                                                        <div class = 'row text'>
                                                            <center><a class = 'filebtn hfilebtn' style='width: auto; height:25%; margin-top:20%;' target = '_blank' href = '../uploads/{$classwork_row1['files']}'>{$classwork_row1['files']}</a></center>
                                                            <div style='width: 300px;border: 1px solid black;padding: 15px;margin: 20px;'>File name: $ass_file_name <br> Description: $ass_desc </div>
                                                        </div>"; 
                                                    }
                                                }
                                                $str .="
                                                <button type='button' class='filebtn hfilebtn' style='margin-left: 85%;' data-bs-toggle='modal' data-bs-target='#editModal$get_id'>
                                                    Edit
                                                </button>
                                           
                                            </div>
                                            <div class='modal fade' id='editModal$get_id' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
                                                <div class='modal-dialog modal-lg'>
                                                    <div class='modal-content'>
                                                

                                                    <div class='modal-header'>
                                                        <h5 class='modal-title' id='editModalLabel'>Submit an Assignment</h5>
                                                    </div>

                                                    <form method='POST' enctype='multipart/form-data'>
                                                    <div class='modal-body p-5'>
                                                        

                                                        <div class='md-file'>
                                                            <label for='formFile' class='form-label'></label>
                                                            <input class='form-control' type='file' name='file' id='formFile1'>
                                                        </div>

                                                        <br>

                                                        <div class='md-title'>
                                                            <label for='exampleFormControlInput1' name='title' class='form-label'>File Name</label>
                                                            <input type='text' name='name' class='form-control' id='exampleFormControlInput1' placeholder='$ass_file_name'>
                                                        </div>

                                                        <br>

                                                        <div class='md-textarea'>
                                                            <label for='exampleFormControlTextarea1' class='form-label'>Description</label>
                                                            <textarea name='desc' class='form-control' id='exampleFormControlTextarea1' placeholder='$ass_desc' rows='3'></textarea>
                                                        </div>

                                                    </div>

                                                    <div class='modal-footer'>
                                                        <input type='hidden' name='id' value='$get_id'>
                                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                        <button type='submit' name='save' class='btn btn-primary'>Submit</button>
                                                    </div></form>
                                                    

                                                
                                             
                                                    <form action = 'Student_DashClasswork.php' method='POST'><input type='hidden' name='id' value='$get_id'><button class='filebtn hfilebtn' type='submit' name='turnin' style='margin-left:85%;'>Turn-In</button></form>
                                                </div>
                                                </div>
                                                </div>
                                                ";
                                    }
                                }
                                echo $str;
                            }
                        }
                    }
    
    

    


    
}


    ?>