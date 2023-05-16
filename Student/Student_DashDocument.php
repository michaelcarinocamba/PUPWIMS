<?php
    session_start();
    include "../db_conn.php";
    if(!isset($_SESSION['studnum']))
    {
        header("Location: Student_Login.php?LoginFirst");
    }
    
    $code = $_GET['classCode'];
    $query = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row = mysqli_fetch_array($query);
    $HRS_Rendered = $row['HRS_Rendered'];
    $studentnum = $_SESSION['studnum'];
?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Home - Documents</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel = "stylesheet" href = "../css/student_dashdocu.css">

</head>

<body >
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
       
        <a href="Student_Dashboard.php?classCode=<?php echo $code; ?>"  style="font-size:13.5px;" id="topnav1"><i class="fa fa-home" style="width:15px;"></i> &emsp;Home </a>
        <a href="Student_DashClasswork.php?classCode=<?php echo $code; ?>"  style="font-size:13.5px;" id="topnav2"><i class="fa fa-book" style="width:15px;"> </i> &emsp;Classwork </a>
        <a href="Student_DashDocument.php?classCode=<?php echo $code; ?>"  style="font-size:13.5px;" id="topnav3"><i class="fa fa-file-text" style="width:15px;"></i>  &emsp;Documents </a>
            
            <?php
            $stud_query = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND status = 'Completed' AND HRS_rendered = '$HRS_Rendered' AND class_code = '$code'");

            if(mysqli_num_rows($stud_query)>0)
            {
                while($fetch = mysqli_fetch_array($stud_query))
                {
                    $company = $fetch['company']; 
                    
                    $query = mysqli_query($conn, "SELECT * FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company' AND class_code = '$code'");
                    if(mysqli_num_rows($query) === 0)
                    { 
                       
                            ?>
                            <a href="Student_Evaluation.php?classCode=<?php echo $code ?>" style="font-size:13.5px;" id="topnav4"><i class="fa fa-line-chart" style="font-size: 15px;"></i>  &emsp;Evaluation </a>
                            <?php
                        
                         } 
                }
            }
            else
            {
                $company = "";
            }

            
               
                
            
?>
    
            <div class="topnavbar-right" id="topnav_right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                 <center>Logout  <i class = "fa fa-sign-out fa-1x"></i></center>
                </a>
            </div>
        </div> <!--end of topnavbar-->
        
            <br><br><br><br>
            
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">On-the-Job Training Requirements</h1>
            <hr style="background-color:maroon;border-width:2px;"> <br>
            
            <p style="font-size: 15px;">Below are the required documents. By clicking the button, you may view or download the file. </p>

            
           
            
            <div class="row row-cols-1 row-cols-md-3 g-4" style = "display: fixed; position: inherit;" id="card_row">

            <?php
            $get_doc = mysqli_query($conn, "SELECT * FROM document");

            if(mysqli_num_rows($get_doc) > 0)
            {
                $str = "";
                while($row = mysqli_fetch_array($get_doc))
                {
                    $doc_id = $row['doc_id'];
                    $docname = $row['name'];
                    $doclink = $row['link'];
                    $max_text = 18;
                    $displayed_name = substr($docname, 0, $max_text);
                    if (strlen($docname) > $max_text) {
                        $displayed_name .= "...";
                      }

                    $str .= "
                    <div class = 'col'>
                        <div class = 'p-2'>
                            <div class='card style h-100'>
                                <div class='card-body text-center'>
                                    <a class='text' target = '_blank' href='$doclink'>
                                        <i class = 'fa fa-file-text fa-3x icon'></i>
                                        <p title='$docname'>$displayed_name</p> <br>
                                        <button class='button hbutton'>View / Download</button>
                                    </a>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";
                }
                echo $str;
            }
            ?>

</div> <!--end of card deck-->
<br><br><br>
<script>
            
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize) {
            if (tabletSize.matches) 
            {
                document.getElementById('topnav_right').style.float = "left";
                document.getElementById('topnav_right').style.fontSize = "12px";
                document.getElementById('topnav1').style.fontSize = "12px";
                document.getElementById('topnav2').style.fontSize = "12px";
                document.getElementById('topnav3').style.fontSize = "12px";
                document.getElementById('topnav4').style.fontSize = "12px";
                document.getElementById('topnav1').style.padding = "14px 35px";
                document.getElementById('topnav2').style.padding = "14px 35px";
                document.getElementById('topnav3').style.padding = "14px 35px";
                document.getElementById('topnav4').style.padding = "14px 35px";
                document.getElementById('card_row').classList.remove('row-cols-md-3');
                document.getElementById('card_row').classList.add('row-cols-md-2');
                
                
            } 
            else
            {

                document.getElementById('topnav_right').classList.add('topnavbar-right');
                document.getElementById('topnav_right').style.fontSize = "13.5px";
                document.getElementById('topnav1').style.fontSize = "13.5px";
                document.getElementById('topnav2').style.fontSize = "13.5px";
                document.getElementById('topnav3').style.fontSize = "13.5px";
                document.getElementById('topnav4').style.fontSize = "13.5px";
                document.getElementById('topnav1').style.padding = "14px 50px";
                document.getElementById('topnav2').style.padding = "14px 50px";
                document.getElementById('topnav3').style.padding = "14px 50px";
                document.getElementById('topnav4').style.padding = "14px 50px";
                document.getElementById('card_row').classList.remove('row-cols-md-2');
                document.getElementById('card_row').classList.add('row-cols-md-3');
                
            }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>
        </div> <!-- end of main -->
    
    
    
</div> <!--End of container-fluid -->

</body>
</html>