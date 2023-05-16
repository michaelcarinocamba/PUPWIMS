<?php
    //DONE
    session_start();
    include '../db_conn.php';

    if(!isset($_SESSION['faculty']) && !isset($_GET['classCode']))
    {
        header("Location: Adviser_Login.php?LoginFirst");
    }
    

    $facnum = $_SESSION['faculty'];
    $name = $_SESSION['name'];

    if(isset($_GET['classCode']))
    {
        $code = $_GET['classCode'];
    }

    // Function for Adding classwork
    if(isset($_POST["post"])){
        $code = $_GET['class'];
        $instruction = $_POST['instruction'];
        $instruction = strip_tags($instruction);
        $instruction = mysqli_real_escape_string($conn, $instruction);
        $check_empty = preg_replace('/\s+/', '', $instruction);
        
        $title = $_POST['title'];
        $file = $_FILES['file'];
        
        $start_date = $_POST['start_date'];
        $due_date = $_POST['due_date'];
        $option = $_POST['option'];
        if($_POST['option'] === "Not Graded")
        {
            $points = "1";
        }
        else
        {
            $points = $_POST['points'];
        }
        date_default_timezone_set('Asia/Singapore');
        $date_added = date("Y-m-d H:i:s A");
        

        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx', 'xls');


        if ($check_empty != "" && $fileName == "") 
        {
            $query = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$facnum', '$name', '$code', '$title', '$instruction','$date_added', '$start_date', '$due_date', '', '$option', '$points', '', 'no')");
            $notif_desc = "has created activities in classwork.";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Student_DashClasswork.php?classCode=$code";
            $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
            if(mysqli_num_rows($getstudentname) > 0)
            {
                while($getstudentname_row = mysqli_fetch_array($getstudentname))
                {
                    $student_name = $getstudentname_row['name'];

                    $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
                }
            }
            header("Location: Adviser_Classwork.php?classCode=$code&uploadsuccess");
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

                        $sql = "INSERT INTO adviser_assignment VALUES ('', '$facnum', '$name', '$code', '$title', '$instruction','$date_added', '$start_date', '$due_date', '$fileName', '$option', '$points', '$fileDestination', 'no')";
                        mysqli_query($conn, $sql);
                        
                        $notif_desc = "has created activities in classwork.";
                        $date_time_now = date("Y-m-d H:i:s");
                        $notif_link = "Student_DashClasswork.php?classCode=$code";
                        $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
                        if(mysqli_num_rows($getstudentname) > 0)
                        {
                            while($getstudentname_row = mysqli_fetch_array($getstudentname))
                            {
                                $student_name = $getstudentname_row['name'];

                                $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
                            }
                        }
                        header("Location:  Adviser_Classwork.php?classCode=$code&uploadsuccess");
                    } 
                    
                    else {
                        header("Location:  Adviser_Classwork.php?classCode=$code&unsuccess");
                    }

                }
                
                else {
                    header("Location:  Adviser_Classwork.php?classCode=$code&unsuccess");
                }

            } 
            
            else {
                header("Location:  Adviser_Classwork.php?classCode=$code&unsuccess");
            }

        }
    }

    // Function for Editing classwork
    if(isset($_POST["edit"])){
        $code = $_GET['class'];
        $get_id = $_POST['get_id'];
        $instruction = $_POST['instruction'];
        $instruction = strip_tags($instruction);
        $instruction = mysqli_real_escape_string($conn, $instruction);
        $check_empty = preg_replace('/\s+/', '', $instruction);
        
        $title = $_POST['title'];
        $file = $_FILES['file'];
        $points = $_POST['points'];
        $start_date = $_POST['start_date'];
        $due_date = $_POST['due_date'];
        $option = $_POST['option'];
        date_default_timezone_set('Asia/Singapore');
        $date_added = date("Y-m-d h:i:s");
        

        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx', 'xls');


        if ($check_empty != "" && $fileName == "") 
        {
            $query = mysqli_query($conn, "UPDATE adviser_assignment SET title = '$title', instruction = '$instruction', start_date = '$start_date', due_date = '$due_date', points = '$points', type = '$option' WHERE assignment_id = '$get_id'");
            $notif_desc = "has updated activities in classwork. [$title]";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "Student_DashClasswork.php?classCode=$code&#toggleClass$get_id";
            $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
            if(mysqli_num_rows($getstudentname) > 0)
            {
                while($getstudentname_row = mysqli_fetch_array($getstudentname))
                {
                    $student_name = $getstudentname_row['name'];

                    $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
                }
            }
            header("Location: Adviser_Classwork.php?classCode=$code&editsuccess");
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

                        $query = mysqli_query($conn, "UPDATE adviser_assignment SET title = '$title', instruction = '$instruction', start_date = '$start_date', due_date = '$due_date', points = '$points', files = '$fileName', files_destination = '$fileDestination', type = '$option' WHERE assignment_id = '$get_id'");
                        $notif_desc = "has updated activities in classwork. [$title]";
                        $date_time_now = date("Y-m-d H:i:s");
                        $notif_link = "Student_DashClasswork.php?classCode=$code&#toggleClass$get_id";
                        $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
                        if(mysqli_num_rows($getstudentname) > 0)
                        {
                            while($getstudentname_row = mysqli_fetch_array($getstudentname))
                            {
                                $student_name = $getstudentname_row['name'];

                                $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
                            }
                        }
                        
                        
                        header("Location:  Adviser_Classwork.php?classCode=$code&editsuccess");
                    } 
                    
                    else {
                        header("Location:  Adviser_Classwork.php?classCode=$code&unsuccess");
                    }

                }
                
                else {
                   header("Location:  Adviser_Classwork.php?classCode=$code&unsuccess");
                }

            } 
            
            else {
                header("Location:  Adviser_Classwork.php?classCode=$code&unsuccess");
            }

        }
        else
        {
            header("Location:  Adviser_Classwork.php?classCode=$code&editunsuccess");
        }
    }

    // Function for removing classwork
    if(isset($_REQUEST['delete']))
    {
        $id = $_REQUEST['get_id'];
        $code = $_REQUEST['class'];
        $query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$id' AND class_code = '$code'");
        if($query)
        {
            
            $deletequery = mysqli_query($conn, "UPDATE adviser_assignment SET remove = 'yes' WHERE assignment_id = '$id' AND class_code = '$code'");
            header("Location: Adviser_Classwork.php?classCode=$code&deletesuccess");
        }
        else
        {
            header("Location: Adviser_Classwork.php?classCode=$code&deleteunsuccess");
        }
        
    }

    // Function for permanently deleting classwork
    if(isset($_REQUEST['permanently_delete']))
    {

        $id = $_REQUEST['get_id'];
        $code = $_REQUEST['class'];
        $query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$id' AND class_code = '$code'");
        if($query)
        {
            
            $deletequery = mysqli_query($conn,  "DELETE FROM adviser_assignment WHERE assignment_id = '$id' AND class_code = '$code'");
            $deletequery = mysqli_query($conn, "DELETE FROM student_assignment WHERE assignment_id = '$id' AND class_code = '$code'");
            header("Location: Adviser_Classwork.php?classCode=$code&deletesuccess");
        }
        else
        {
            header("Location: Adviser_Classwork.php?classCode=$code&deleteunsuccess");
        }
        
    }

    // Function for retrieving classwork
    if(isset($_REQUEST['retrieve_btn']))
        {

        $id = $_REQUEST['get_id'];
        $code = $_REQUEST['class'];
        $query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE assignment_id = '$id' AND class_code = '$code'");
        if($query)
        {
            
            $retirevequery = mysqli_query($conn, "UPDATE adviser_assignment SET remove = 'no' WHERE assignment_id = '$id' AND class_code = '$code'");
            header("Location: Adviser_Classwork.php?classCode=$code&retrievesuccess");
        }
        else
        {
            header("Location: Adviser_Classwork.php?classCode=$code&retrieveunsuccess");
        }
    }



