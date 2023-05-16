<?php

session_start();
include '../db_conn.php';
if(!isset($_SESSION['faculty']))
    {
        header("Location: Adviser_Login.php?LoginFirst");
    }


$faculty = $_SESSION['faculty'];

$name = $_SESSION['name'];

if(isset($_POST["post"])){
    $code = $_REQUEST['classCode'];
    $content = $_POST['content'];
    $file = $_FILES['file'];
    date_default_timezone_set('Asia/Singapore');
    $date_added = date("Y-m-d h:i:s");
    

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'ppt', 'pptx', 'xls', '');
    
    if ($content != "" && $fileName == "") {

        $query = mysqli_query($conn, "INSERT INTO post VALUES('', '$content' , '$faculty' , '$name', '$code', '$date_added', 'no', '', '', '')");
        $notif_desc = "has posted something in discussion.";
        $date_time_now = date("Y-m-d H:i:s");
        $notif_link = "Student_DashHome.php?classCode=$code&#viewpost";
        $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
        if(mysqli_num_rows($getstudentname) > 0)
        {
            while($getstudentname_row = mysqli_fetch_array($getstudentname))
            {
                 $student_name = $getstudentname_row['name'];

                 $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
            }
        }
        header("Location: Adviser_DashHome.php?classCode=$code&uploadsuccess");
    }

    else if($fileName !=  ""){
    if(in_array($fileActualExt, $allowed)) {
        if($fileError === 0) {
            if($fileSize < 20000000) {
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = '../uploads/'.$fileName;
                move_uploaded_file($fileTmpName,$fileDestination);

                $sql = "INSERT INTO post VALUES ('', '$content', '$faculty', '$name', '$code', '$date_added', 'no', '$fileName', '$fileNameNew', '$fileDestination')";
                mysqli_query($conn, $sql);
                $added_id = mysqli_insert_id($conn);
                $notif_desc = "has posted something in discussion.";
                $date_time_now = date("Y-m-d H:i:s");
                $notif_link = "Student_DashHome.php?classCode=$code&#viewpost";
                $getstudentname = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
                if(mysqli_num_rows($getstudentname) > 0)
                {
                    while($getstudentname_row = mysqli_fetch_array($getstudentname))
                    {
                         $student_name = $getstudentname_row['name'];
        
                         $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$student_name', '$code', '$date_time_now','no')");
                    }
                }
                header("Location:  Adviser_DashHome.php?classCode=$code&uploadsuccess");
            } else{
                echo "Your file is too big!";
                header("Location:  Adviser_DashHome.php?classCode=$code&uploadnotsuccess5");
            }
        }else {
            echo "There was an error uploading your file.";
            header("Location:  Adviser_DashHome.php?classCode=$code&uploadnotsuccess6");
        }
    } else{
        echo "You cannot upload files of this type!";
        header("Location:  Adviser_DashHome.php?classCode=$code&uploadnotsuccess7");
    }
    
    }
     else
    {
        echo "Try to input  text or file first";
        header("Location: Adviser_DashHome.php?classCode=$code&uploadnotsuccess8");
    }
}

if(isset($_REQUEST['delete']))
{
    $code = $_REQUEST['classCode'];
    $id = $_REQUEST['get_id'];
    $query1 = mysqli_query($conn, "SELECT files_destination FROM post WHERE post_id = '$id'");
    $row = mysqli_fetch_array($query1);
    $files = $row['files_destination'];
    if(unlink($files)){
    $query = mysqli_query($conn, "DELETE FROM post WHERE post_id='$id'");
    header("Location: Adviser_DashHome.php?classCode=$code&deletesuccess");
    }
    else
    {
        $query = mysqli_query($conn, "DELETE FROM post WHERE post_id='$id'");
        header("Location: Adviser_DashHome.php?classCode=$code&deletesuccess");
    }
    
}



if(isset($_REQUEST['edit']))
{
    $edit_id = $_REQUEST['get_id'];
    $content = $_POST['editedcontent'];
    $classcode = $_REQUEST['classCode'];
    $content = strip_tags($content);
    $content = mysqli_real_escape_string($conn, $content);


    $file = $_FILES['file'];
    
    date_default_timezone_set('Asia/Singapore');
    $date_added = date("Y-m-d h:i:s");
    

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'ppt', 'pptx' , 'xlsx', '$content', '');

   

    if ($content != "" && $fileName == "") {
        if($student != $user_to){
            $added_to = 'none';
        }
        $query = mysqli_query($conn, "UPDATE post SET content = '$content', edited ='yes', date_added = '$date_added' WHERE post_id LIKE '$edit_id'");
        header("Location: Adviser_DashHome.php?classCode=$classcode&editsuccess");
    }
    else if($fileName !=  ""){
    if(in_array($fileActualExt, $allowed)) {
        if($fileError === 0) {
            if($fileSize < 20000000) {
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = '../uploads/'.$fileNameNew;
                move_uploaded_file($fileTmpName,$fileDestination);

                if($student != $user_to){
                    $added_to = 'none';
                }

                $sql = "UPDATE post SET content = '$content', edited ='yes', date_added = '$date_added', filename = '$fileName', files = '$fileNameNew', files_destination = '$fileDestination' WHERE post_id LIKE '$edit_id'";
                mysqli_query($conn, $sql);
                
                header("Location: Adviser_DashHome.php?classCode=$classcode&editsuccess");
            } else{
                echo "Your file is too big!";
                header("Location: Adviser_DashHome.php?classCode=$classcode&editnotsuccess1");
            }
        }else {
            echo "There was an error uploading your file.";
            header("Location: Adviser_DashHome.php?classCode=$classcode&editnotsuccess2");
        }
    } else{
        echo "You cannot upload files of this type!";
        header("Location: Adviser_DashHome.php?classCode=$classcode&editnotsuccess3");
    }
    
    }
    else
    {
        echo "Try to input  text or file first";
        header("Location: Adviser_DashHome.php?classCode=$classcode&editnotsuccess4");
    }
}


if (isset($_GET['post_id'])) {


    $post_id = $_GET['post_id'];
    $code = $_REQUEST['classCode'];

    if(isset($_POST['postComment' . $post_id]))
    {
        $post_body = $_POST['comment_body'];
        $post_body = mysqli_escape_string($conn, $post_body);
		$date_time_now = date("Y-m-d H:i:s");
        $getpost = mysqli_query($conn, "SELECT * FROM post WHERE post_id = '$post_id'");
        $fetchpost = mysqli_fetch_array($getpost);
        $post_name = $fetchpost['name'];

        $insert_comment = mysqli_query($conn, "INSERT INTO comment VALUES('', '$post_id', '$post_body','$faculty','$post_name', '$name', '$code', '$date_time_now', 'no')");

        $notif_desc = "has commented in your discussion.";
        $notif_link = "#viewcomment" . $post_id;


        if($post_name === $name){
            
        }
        else
        {
            $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$name', '$post_name', '$code', '$date_time_now','no')");
        }
    }
    
    $get_comments = mysqli_query($conn, "SELECT * FROM comment WHERE post_id='$post_id' ORDER BY comment_id ASC");
    $count = mysqli_num_rows($get_comments);
    
    if($count != 0)
    {
        while($comment = mysqli_fetch_array($get_comments))
        {
            $id = $comment['comment_id'];
            $post_id = $comment['post_id'];
            $comment_body = $comment['content'];
            $class_code = $comment['class_code'];
            $posted_by = $comment['added_by'];
            $comment_name = $comment['name'];
            $date_posted = $comment['date_added'];
            $edited = $comment['edited'];

            $check_edit = "";
            if($edited === "yes")
            {
                $check_edit = "edited";
            }
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

            //Timeframe
				$date_time_now = date("Y-m-d H:i:s");
				$start_date = new DateTime($date_posted); //Time of post
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
						$time_message = $interval->m . " month" . $days;
					} else {
						$time_message = $interval->m . " months" . $days;
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

                if(isset($_REQUEST['comment'])){
                    ?>
                    
                        <html>
                
                        <head>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                        <link rel = "stylesheet" href = "../css/student_DashHome.css">
                        
                        <a class = 'deletebtn' href='Adviser_Post.php?deletecomment=<?php echo $id ?>&post_id=<?php echo $post_id ?>&classCode=<?php echo $code ?>'>
                                        <i class = 'fa fa-trash'></i>
                                    </a>
                        
                        </head><a class = "name hname" href="<?php echo $posted_by ?>" style="font-size: 15px;"> <?php echo $comment_name ?></a>
                        &nbsp;&nbsp;<?php echo "<span style='font-size: 11px;'>$time_message &emsp;&emsp;$check_edit</span>" . "<br>" . "<p >$comment_body<p>" . "<br>" . "<hr class='dashed'>";
            
            ?>
                            <br>
                         
                    </html>
                
                <?php
                    }

                ?>
                
                    
                        
                        
                        <?php
                        if(isset($_REQUEST['editcomment']) && isset($_REQUEST['post_id']))
                        {
                            $editid = $_GET['editcomment'];
                            $post_id = $_GET['post_id'];

                            $getcomment = mysqli_query($conn, "SELECT * FROM comment WHERE comment_id LIKE '$editid' AND post_id LIKE '$post_id'");
                            $commentarray = mysqli_fetch_array($getcomment);
                            $content = $commentarray['content'];

                            if(isset($_POST['update' . $editid]))
                            {
                                $post_body = $_POST['editedPost_text'];
                                $post_body = mysqli_escape_string($conn, $post_body);
                                $date_time_now = date("Y-m-d H:i:s");
                                $editcomment = mysqli_query($conn, "UPDATE comment SET content = '$post_body',edited = 'yes',date_added = '$date_time_now' WHERE comment_id LIKE '$editid' AND post_id LIKE '$post_id'");


                                header("Location: Adviser_Post.php?comment&post_id=$post_id&classCode=$code");
                            }
                            if(isset($_POST['cancel']))
                            {
                                header("Location: Adviser_Post.php?comment&post_id=$post_id&classCode=$code");
                            }

                            ?>
                            

                            <html>
                            <head>
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


                                <link rel = "stylesheet" href = "../css/student_DashHome.css">
                            </head>

                                
                                    
                                  
    

                        </head><a class = "name hname" href="<?php echo $posted_by ?>" style="font-size: 15px;"> <?php echo $comment_name ?></a>
                        &nbsp;&nbsp;<?php echo "<span style='font-size: 11px;'>$time_message </span>" . "<br>" . "<p >$comment_body<p>" . "<br>" . "<hr class='dashed'>"; ?>
                                <br> 
                            
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="Adviser_Post.php?post_id=<?php echo $post_id; ?>&editcomment=<?php echo $editid ?>&classCode=<?php echo $code ?>" name="editComment<?php echo $editid ?>" method="POST" autocomplete="off">
                                                <textarea name="editedPost_text" id="edit_textarea" style="resize: none;width: 70%;height: 30%;padding: 10px 20px;box-sizing: border-box;border: 1px solid #C5C5C5;border-radius: 3px;font-size: 15px;resize: none;background-color: white;box-shadow: 1px 2px 2px #C5C5C5;" placeholder="Edit comment"></textarea>
                                                <a href="#"><input type="submit" name="cancel" value="Cancel" class="edit_box_btn" id="update_cancel_btn" style ="background-color: #6B6B6B;color: white;font-size: 12px;border: none;color: white;padding: 4px 15px;text-align: center;text-decoration: none;display: inline-block;border-radius: 10px;font-size: 16px;top-margin: 0;margin: 3px 2px;cursor: pointer;"></a>
                                                <input type="submit" name="update<?php echo $editid; ?>" value="Update" class="edit_box_btn" id="update_btn" style ="background-color: #6B6B6B;color: white;font-size: 12px;border: none;color: white;padding: 4px 15px;text-align: center;text-decoration: none;display: center;border-radius: 10px;font-size: 16px;margin: 5px 2px;cursor: pointer;">
                                            </form>
                                    </div>
                                </div>
                            </div>
                        </html>
                                <?php

                        }
                        
                   
                    
                
                
            ?>
           
            <?php
        }
    }
    else {
        echo "<p style='text-align: center; margin-bottom:1rem;'>No Comments to Show!</p><hr class='dashed'>";
    }

if(isset($_REQUEST['comment'])){
   ?>
   
                        <form action="Adviser_Post.php?comment&post_id=<?php echo $post_id ?>&classCode=<?php echo $code ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST" autocomplete="off">
                        <input type="text" name="comment_body" placeholder="Add a comment" style="resize: none;width: 90%;height: auto;padding: 10px 20px;box-sizing: border-box;border: 1px solid #C5C5C5;border-radius: 5px;font-size: 16px;resize: none;background-color: white;box-shadow: 1px 2px 3px #C5C5C5;">
                        <input type="submit" name="postComment<?php echo $post_id; ?>" value="Post" style ="background-color: #6B6B6B;color: white;font-size: 16px;border: none;color: white;padding: 4px 25px;text-align: center;text-decoration: none;display: inline-block;border-radius: 10px;font-size: 16px;margin: 4px 2px;cursor: pointer;">
                        
                    </form>  
                    <?php
}
}

if(isset($_REQUEST['deletecomment']))
{
    $comment_id = $_REQUEST['deletecomment'];
    $post_id = $_REQUEST['post_id'];
    
    $query = mysqli_query($conn, "DELETE FROM comment WHERE comment_id='$comment_id'");
    header("Location: Adviser_Post.php?comment&post_id=$post_id&classCode=$code");

}

?>

    