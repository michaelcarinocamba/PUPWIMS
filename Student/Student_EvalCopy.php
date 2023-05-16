<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['studnum']))
    {
        header("Location: Student_Login.php?LoginFirst");
    }

$studentnum = $_SESSION['studnum'];
$class_code = $_GET['classCode'];

$query = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND class_code = '$class_code'");
$row = mysqli_fetch_array($query);
$company = $row['company'];

$sqleval = mysqli_query($conn, "SELECT * FROM create_class WHERE student_list LIKE '%$studentnum%'");
$roweval = mysqli_fetch_array($sqleval);
if(!empty($roweval)){
    
    $facultyeval = $roweval['facultynum'];

}

$sqlcomp = mysqli_query($conn, "SELECT * FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");
if(mysqli_num_rows($sqlcomp) > 0)
{
    while($comp = mysqli_fetch_array($sqlcomp))
    {
        $comp_name = $comp['company_name'];
        $supervisor = $comp['supervisor'];
        $dept = $comp['department'];
    }
}

?>
<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

    <title>Internship Evaluation | PUPSJ-WIMS</title> <link rel="shortcut icon" href= "images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel = "stylesheet" href = "../css/student_DashHome.css">

</head>

<body>
    <!-- Heading for printing -->
    
    <div id="PrintButton" class="sidebar">
            <center>
            <br><br>
                <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>
                <img src="../images/Background1.png" alt="background" style="z-index: -1; object-fit: cover; position: absolute; top: 0; left: 0; width:100%; height: 100%;">
                <h6 style="color: white;font-size:20px;"><b> Student </b></h6>
                
            </center>
        </div>
    <br>
    <div class = "header">
        <img src="../images/pupmainlogo.png" style = "width: 130px; float:left; margin-right: 5px;">
        <p style = "font-family:'Times New Roman',serif; padding: 10px 0px 5px 0px; margin: 0; font-size: 16px;">Republic of the Philippines</p>
        <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 20px;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p></b>
        <p style = "font-family:'Times New Roman',serif; margin: 0;">OFFICE OF THE VICE PRESIDENT FOR BRANCHES AND SATELLITE CAMPUSES</p>
        <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 20px;">San Juan City Branch</p></b>
        <hr>
  
</div>

<div class = "main">
            
<div class = "maincontent">
    <h2> Internship Evaluation</h2>
        <form method = "POST">
            <div class = "display_form">
            <div class = "form-row">
                <div class="col-md-4">
                    <label for=""><b>Company Name</b></label>
                    <input type= "text" name = "compname" class="form-control" placeholder="<?php echo $comp_name; ?>" disabled/>
                </div>

                
                <div class="col-md-4">
                    <label for=""><b>Immediate Supervisor</b></label>

                    <input type="supervisor" name = "supervisor" class="form-control" placeholder="<?php echo $supervisor; ?>" disabled/>
                </div>
                
                <div class="col-md-4">
                    <label for=""><b>Department</b></label>
                    <input type="department" name = "department" class="form-control" placeholder="<?php echo $dept; ?>" disabled/>
                </div>
                </div>
            </div>
                
                <table style="width:100%;">
                    <tr style="background-color: #EEEEEE;">
                        <th ><center>Questions<center><br></th>
                        <th style="font-size:12px; padding:3px 4px;"><center>Strongly Agree</center><br></th>
                        <th style="font-size:12px; padding:3px 4px;"><center>Agree</center><br></th>
                        <th style="font-size:12px; padding:3px 4px;"><center>Disagree</center><br></th>
                        <th style="font-size:12px; padding:3px 4px;"><center>Strongly Disagree</center><br></th>
                    </tr>

                <!-- Number 1 -->
                <?php
                    $sqlans1 = mysqli_query($conn, "SELECT answer1 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                        if($sqlans1){
                            $ans1 = mysqli_fetch_array($sqlans1);
                            if($ans1['answer1'] === '4'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>1.</b> My field of interest was realistically introduced to me though this event.<b><code>*</code></b></label></td><br>
                                <td><center><input type = "radio" name = "answer1" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                         }

                            else if($ans1['answer1'] === '3'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>1.</b> My field of interest was realistically introduced to me though this event.<b><code>*</code></b></label></td><br>
                                <td><center><input type = "radio" name = "answer1" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                         }

                            else if($ans1['answer1'] === '2'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>1.</b> My field of interest was realistically introduced to me though this event.<b><code>*</code></b></label></td><br>
                                <td><center><input type = "radio" name = "answer1" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                         }

                            else if($ans1['answer1'] === '1'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>1.</b> My field of interest was realistically introduced to me though this event.<b><code>*</code></b></label></td><br>
                                <td><center><input type = "radio" name = "answer1" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer1" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                            }
                        }
                    ?>

                <!-- Number 2 -->
                <?php
                $sqlans2 = mysqli_query($conn, "SELECT answer2 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                    if($sqlans2){
                        $ans2 = mysqli_fetch_array($sqlans2);
                        if($ans2['answer2'] === '4'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>2.</b> I now have a greater understanding of the ideas, theories, and abilities involved <br>in my field of study as a result of my internship.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer2" value = "4" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer2" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer2" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer2" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                        }

                        else if($ans2['answer2'] === '3'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>2.</b> I now have a greater understanding of the ideas, theories, and abilities involved <br>in my field of study as a result of my internship.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer2" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        else if($ans2['answer2'] === '2'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>2.</b> I now have a greater understanding of the ideas, theories, and abilities involved <br>in my field of study as a result of my internship.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer2" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        else if($ans2['answer2'] === '1'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>2.</b> I now have a greater understanding of the ideas, theories, and abilities involved <br>in my field of study as a result of my internship.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer2" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer2" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>

                 <!-- Number 3 -->
                 <?php
                    $sqlans3 = mysqli_query($conn, "SELECT answer3 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                    if($sqlans3){
                        $ans3 = mysqli_fetch_array($sqlans3);
                        if($ans3['answer3'] === '4'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>3.</b> I was given adequate orientation and training.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer3" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        else if($ans3['answer3'] === '3'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>3.</b> I was given adequate orientation and training.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer3" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        else if($ans3['answer3'] === '2'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>3.</b> I was given adequate orientation and training.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer3" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        else if($ans3['answer3'] === '1'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>3.</b> I was given adequate orientation and training.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer3" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer3" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>

                <!-- Number 4 -->
                <?php
                    $sqlans4 = mysqli_query($conn, "SELECT answer4 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                    if($sqlans4){
                        $ans4 = mysqli_fetch_array($sqlans4);
                        if($ans4['answer4'] === '4'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>4.</b> My supervisor and I met on a regular basis, and I continuosly receive critical feedback.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer4" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        else if($ans4['answer4'] === '3'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>4.</b> My supervisor and I met on a regular basis, and I continuosly receive critical feedback.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer4" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        else if($ans4['answer4'] === '2'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>4.</b> My supervisor and I met on a regular basis, and I continuosly receive critical feedback.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer4" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        else if($ans4['answer4'] === '1'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>4.</b> My supervisor and I met on a regular basis, and I continuosly receive critical feedback.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer4" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer4" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>

                <!-- Number 5 -->
                <?php
                    $sqlans5 = mysqli_query($conn, "SELECT answer5 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");
                    if($sqlans5){
                        $ans5 = mysqli_fetch_array($sqlans5);
                            if($ans5['answer5'] === '4'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>5.</b> I was given levels of responsibility that were in the line with my abilities. And as my <br>experience grew, I was given more responsibility.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer5" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans5['answer5'] === '3'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>5.</b> I was given levels of responsibility that were in the line with my abilities. And as my <br>experience grew, I was given more responsibility.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer5" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans5['answer5'] === '2'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>5.</b> I was given levels of responsibility that were in the line with my abilities. And as my <br>experience grew, I was given more responsibility.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer5" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans5['answer5'] === '1'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>5.</b> I was given levels of responsibility that were in the line with my abilities. And as my <br>experience grew, I was given more responsibility.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer5" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer5" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>
                <!-- Number 6 -->
                <?php
                $sqlans6 = mysqli_query($conn, "SELECT answer6 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                if($sqlans6){
                    $ans6 = mysqli_fetch_array($sqlans6);
                    if($ans6['answer6'] === '4'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>6.</b> When I had concerns or questions, my supervisor was available.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer6" value = "4" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "2"disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans6['answer6'] === '3'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>6.</b> When I had concerns or questions, my supervisor was available.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer6" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "3" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "2"disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans6['answer6'] === '2'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>6.</b> When I had concerns or questions, my supervisor was available.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer6" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "2"checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans6['answer6'] === '1'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>6.</b> When I had concerns or questions, my supervisor was available.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer6" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer6" value = "1" checked/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }
                }
                ?>

                 <!-- Number 7 -->
                 <?php
                 $sqlans7 = mysqli_query($conn, "SELECT answer7 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");
                 if($sqlans7){
                    $ans7 = mysqli_fetch_array($sqlans7);
                    if($ans7['answer7'] === '4'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>7.</b> The work I performed was challenging and stimulating.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer7" value = "4" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans7['answer7'] === '3'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>7.</b> The work I performed was challenging and stimulating.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer7" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "3" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans7['answer7'] === '2'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>7.</b> The work I performed was challenging and stimulating.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer7" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "2" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans7['answer7'] === '1'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>7.</b> The work I performed was challenging and stimulating.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer7" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer7" value = "1" checked/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }
                }
                ?>

                <!-- Number 8 -->
                <?php
                $sqlans8 = mysqli_query($conn, "SELECT answer8 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                    if($sqlans8){
                        $ans8 = mysqli_fetch_array($sqlans8);
                        if($ans8['answer8'] === '4'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>8.</b> I receive the same treatment as other employees.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer8" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans8['answer8'] === '3'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>8.</b> I receive the same treatment as other employees.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer8" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans8['answer8'] === '2'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>8.</b> I receive the same treatment as other employees.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer8" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans8['answer8'] === '1'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>8.</b> I receive the same treatment as other employees.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer8" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer8" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>

                <!-- Number 9 -->
                <?php
                $sqlans9 = mysqli_query($conn, "SELECT answer9 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                if($sqlans9){
                    $ans9 = mysqli_fetch_array($sqlans9);
                    if($ans9['answer9'] === '4'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>9.</b> My coworkers and I got along well at work.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer9" value = "4" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans9['answer9'] === '3'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>9.</b> My coworkers and I got along well at work.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer9" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "3" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans9['answer9'] === '2'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>9.</b> My coworkers and I got along well at work.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer9" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "2" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans9['answer9'] === '1'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>9.</b> My coworkers and I got along well at work.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer9" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer9" value = "1" checked/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }
                }
                ?>

                <!-- Number 10 -->
                <?php
                $sqlans10 = mysqli_query($conn, "SELECT answer10 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                if($sqlans10){
                    $ans10 = mysqli_fetch_array($sqlans10);
                    if($ans10['answer10'] === '4'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>10.</b> There were ample opportunities for learning.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer10" value = "4" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans10['answer10'] === '3'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>10.</b> There were ample opportunities for learning.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer10" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "3" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans10['answer10'] === '2'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>10.</b> There were ample opportunities for learning.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer10" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "2" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }


                    if($ans10['answer10'] === '1'){
                        $str = '<div class="form-group">
                        <tr style = "background-color: #EEEEEE;">
                            <td><label for=""><b>10.</b> There were ample opportunities for learning.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer10" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer10" value = "1" checked/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }
                }
                ?>

                <!-- Number 11 -->
                <?php
                $sqlans11 = mysqli_query($conn, "SELECT answer11 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                if($sqlans11){
                    $ans11 = mysqli_fetch_array($sqlans11);
                    if($ans11['answer11'] === '4'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>11.</b> After this internship experience, I believe I am more equipped to enter the workforce.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer11" value = "4" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans11['answer11'] === '3'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>11.</b> After this internship experience, I believe I am more equipped to enter the workforce.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer11" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "3" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans11['answer11'] === '2'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>11.</b> After this internship experience, I believe I am more equipped to enter the workforce.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer11" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "2" checked/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "1" disabled/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }

                    if($ans11['answer11'] === '1'){
                        $str = '<div class="form-group">
                        <tr>
                            <td><label for=""><b>11.</b> After this internship experience, I believe I am more equipped to enter the workforce.<b><code>*</code></b></label></td>
                            <td><center><input type = "radio" name = "answer11" value = "4" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "3" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "2" disabled/></center><br></td>
                            <td><center><input type = "radio" name = "answer11" value = "1" checked/></center><br></td>
                        </tr>
                        </div>';

                        echo $str;
                    }
                }
                ?>

                <!-- Number 12 -->
                <?php
                    $sqlans12 = mysqli_query($conn, "SELECT answer12 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                    if($sqlans12){
                        $ans12 = mysqli_fetch_array($sqlans12);
                        if($ans12['answer12'] === '4'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>12.</b> My internship experiences introduced me to the discipline and working world.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer12" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans12['answer12'] === '3'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>12.</b> My internship experiences introduced me to the discipline and working world.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer12" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }


                        if($ans12['answer12'] === '2'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>12.</b> My internship experiences introduced me to the discipline and working world.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer12" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans12['answer12'] === '1'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>12.</b> My internship experiences introduced me to the discipline and working world.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer12" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer12" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>

                <!-- Number 13 -->
                <?php
                    $sqlans13 = mysqli_query($conn, "SELECT answer13 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                    if($sqlans13){
                        $ans13 = mysqli_fetch_array($sqlans13);
                        if($ans13['answer13'] === '4'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>13.</b> The internship experience helped me choose the field I wanted to work in after graduation.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer13" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans13['answer13'] === '3'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>13.</b> The internship experience helped me choose the field I wanted to work in after graduation.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer13" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans13['answer13'] === '2'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>13.</b> The internship experience helped me choose the field I wanted to work in after graduation.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer13" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans13['answer13'] === '1'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>13.</b> The internship experience helped me choose the field I wanted to work in after graduation.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer13" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer13" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>
                <!-- Number 14 -->
                <?php
                    $sqlans14 = mysqli_query($conn, "SELECT answer14 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                    if($sqlans14){
                        $ans14 = mysqli_fetch_array($sqlans14);
                        if($ans14['answer14'] === '4'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>14.</b> My expectations were met by the organization where I conducted my internship.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer14" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans14['answer14'] === '3'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>14.</b> My expectations were met by the organization where I conducted my internship.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer14" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans14['answer14'] === '2'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>14.</b> My expectations were met by the organization where I conducted my internship.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer14" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans14['answer14'] === '1'){
                            $str = '<div class="form-group">
                            <tr style = "background-color: #EEEEEE;">
                                <td><label for=""><b>14.</b> My expectations were met by the organization where I conducted my internship.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer14" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer14" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>

                <!-- Number 15 -->
                <?php
                    $sqlans15 = mysqli_query($conn, "SELECT answer15 FROM student_evaluation WHERE studentnum = '$studentnum' AND company_name = '$company'");

                    if($sqlans15){
                        $ans15 = mysqli_fetch_array($sqlans15);
                        if($ans15['answer15'] === '4'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>15.</b> I advise future student interns to complete their internship at this organization.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer15" value = "4" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans15['answer15'] === '3'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>15.</b> I advise future student interns to complete their internship at this organization.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer15" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "3" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans15['answer15'] === '2'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>15.</b> I advise future student interns to complete their internship at this organization.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer15" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "2" checked/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "1" disabled/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }

                        if($ans15['answer15'] === '1'){
                            $str = '<div class="form-group">
                            <tr>
                                <td><label for=""><b>15.</b> I advise future student interns to complete their internship at this organization.<b><code>*</code></b></label></td>
                                <td><center><input type = "radio" name = "answer15" value = "4" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "3" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "2" disabled/></center><br></td>
                                <td><center><input type = "radio" name = "answer15" value = "1" checked/></center><br></td>
                            </tr>
                            </div>';

                            echo $str;
                        }
                    }
                ?>
                </table>

                <style>
                    .header 
                    {
                        margin-left: 20%;
                    }
                    @media print{
                        #PrintButton{
                            display: none;
                        }

                        @page{
                            size: A4;
                        }
                        .sidebar 
                        {
                            display:none;
                        }
                        .maincontent 
                        {
                            float:left;
                        }
                        .main 
                        {
                            display:block;
                            margin-left: 0%;
                        }
                        .header 
                        { 
                            margin-left: 0%;
                            margin-top: 0%;
                            
                        }
                        .display_form
                        {
                            display: flex;
                            flex-direction: row;
                            flex-wrap: nowrap;
                        }
                        .display_form .form-row 
                        {
                            flex-basis: 100%;
                        }
                    }
                    
                    .code{
                        color: red;
                    }
                </style>
                <br>
                <hr id = "PrintButton" class='dashed'>
                <div class = "form-group">
                    <center>
                    <button type="submit" id = "PrintButton" name = "print_checkbox" class="filebtn hfilebtn" onclick="PrintPage()">PRINT</button>
                    <a id = "PrintButton" class="filebtn hfilebtn" target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>">DONE</a>
                </center>
                </div>
                <hr id = "PrintButton" class='dashed'>

                </form>
            </div>

        </div><!--end of main -->
    </section>
</body>

    <script type="text/javascript">
        function PrintPage() {
            window.print();
        }
    </script>
</html>