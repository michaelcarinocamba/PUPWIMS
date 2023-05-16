<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['studnum']))
    {
        header("Location: Student_Login.php?LoginFirst");
    }

$studentnum = $_SESSION['studnum'];
$code = $_GET['classCode'];

$get_company = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code' AND studentnum = '$studentnum'");

    $row_comp = mysqli_fetch_array($get_company);
    $company = $row_comp['company'];




$sqleval = mysqli_query($conn, "SELECT * FROM create_class WHERE student_list LIKE '%$studentnum%' AND class_code LIKE '$code'");
$roweval = mysqli_fetch_array($sqleval);
if(!empty($roweval)){
$facultyeval = $roweval['facultynum'];
$class_code = $roweval['class_code'];
}

// Adviser Query
$sqladv = mysqli_query($conn, "SELECT * FROM adviser_info WHERE facultynum LIKE '$facultyeval'");

$rowadv = mysqli_fetch_array($sqladv);
if(!empty($rowadv)){
$department = $rowadv['department'];
}

// Student Evaluation Query
if(isset($_POST['submit_checkbox']))
{
   $compname = $_POST['compname'];
   $supervisor = $_POST['supervisor'];
   $department = $_POST['department']; 
   $comment = $_POST['comment'];
   $answer1 = $_POST['answer1']; $answer2 = $_POST['answer2']; $answer3 = $_POST['answer3']; $answer4 = $_POST['answer4']; $answer5 = $_POST['answer5'];
   $answer6 = $_POST['answer6']; $answer7 = $_POST['answer7']; $answer8 = $_POST['answer8']; $answer9 = $_POST['answer9']; $answer10 = $_POST['answer10'];
   $answer11 = $_POST['answer11']; $answer12 = $_POST['answer12']; $answer13 = $_POST['answer13']; $answer14 = $_POST['answer14']; $answer15 = $_POST['answer15'];

   $validation = mysqli_query($conn, "SELECT * FROM student_evaluation WHERE studentnum = '$studentnum' AND class_code = '$code' AND company_name = '$company'");
   if(mysqli_num_rows($validation) === 0)
   {
        $query = mysqli_query($conn,"INSERT INTO student_evaluation VALUES ('', '$studentnum', '$code', '$company','$supervisor','$department','$answer1','$answer2','$answer3','$answer4','$answer5','$answer6','$answer7','$answer8','$answer9','$answer10','$answer11','$answer12','$answer13','$answer14','$answer15','$comment')");
        if($query)
        {
            header("Location: Student_EvalCopy.php?classCode=$code");
        }
        else
        {
            header("Location: Student_Evaluation.php?unsuccess");
        }
   }
   else
   {
        header("Location: Student_Dashboard.php?classCode=$code");
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
    
    <title>Internship Evaluation | PUPSJ-WIMS</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel = "stylesheet" href = "../css/student_DashHome.css">
    <script src="../ckeditor/ckeditor.js"></script>
    
</head>

<body id="body">
    <section>
    

    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
            <center>
            <br><br>
                <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>
               
                <h6 style="color: white;font-size:20px;"><b> Student </b></h6>
                
            </center>
        </div>

        <div class = "main">
            <div class = "topnavbar">
                <div class="topnavbar-right">
                </div>
                    
            </div> 
            <br><br>
        <div class = "maincontent">
        <?php
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
                title: 'There seems problem with your input'
            })
            </script>
      <?php
        }  ?>

        

            <h1 class="font-weight-bold" id="title" style = "font-size: 35px; color:maroon;"> Internship Evaluation <a class="btn btn-primary" id="back_btn" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Student_Dashboard.php?classCode=<?php echo $code; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a>
                 </h1>
                 <br><br>
            <div class="alert alert-danger" id = "reminder" role="alert">
                <h4>REMINDER:</h4> 
                <h6 id="h6">This evaluation will be important in determining the value of your internship experience, both for you and for future student interns. Your
                evaluation should be honest and constructive and should include both challenges and successes. Please provide detailed remarks so that
                your Internship Coordinator can discuss them with the Partner Company to improve and maintain the intership program.</h6>
                <h6 id="h6">In submitting this form I agree to my details being used for the purposes of improving and maintaining the internship program. 
                The information will only be accessed by necessary Internship Coordinator. I understand my data will be held securely and will not be distributed to third parties. 
                The data will be kept confidential in adherence to the Data Privacy Act of 2013.</h6>
            </div>
                <form method = "POST">
                <script>
                    function setRequired(value) {
                        var radioButtons = document.getElementsByName("answer1, answer2, answer3, answer4, answer5, answer6, answer7, answer8, answer9, answer10, answer11, answer12, answer13, answer14, answer15");
                        for (var i = 0; i < radioButtons.length; i++) {
                            if (radioButtons[i].value === value) {
                            radioButtons[i].required = true;
                            } else {
                            radioButtons[i].required = false;
                            }
                        }
                        }
                </script>
                <div class = "form-row">
                <div class="form-group col-md-6">
                    <label for=""><b>Company Name</b></label>
                    <input type= "text" name = "compname" class="form-control" placeholder="<?php echo $company; ?>" value="<?php echo $company; ?>" disabled/>
                </div>
                <div class="form-group col-md-6">
                    <label for=""><b>Student Number</b></label>
                    <input type= "text" name = "studnum" class="form-control"  placeholder="<?php echo $studentnum; ?>" value="<?php echo $studentnum; ?>" disabled/>
                </div>
                </div>

                <div class = "form-row">
                <div class="form-group col-md-6">
                    <label for=""><b>Immediate Supervisor</b></label>
                    <input type="supervisor" name = "supervisor" class="form-control" placeholder="Enter Name of Immediate Supervisor" required/>
                </div>

                <div class="form-group col-md-6">
                    <label for=""><b>Department</b></label>
                    <input type="department" name = "department" class="form-control" placeholder = "Enter Company Department" required/>
                </div>
                </div>
                <style>
                    table, th, td {
                    border:.5px solid black;
                    padding: 5px;
                    width: 5px;;
                    height: auto;
                    }

                </style>
                <script>
                    const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
                    function handleScreenSizeChange(tabletSize) {
                        if (tabletSize.matches) 
                        {
                            document.getElementById('body').style.fontSize = "13px";
                            document.getElementById('title').style.fontSize = "30px";
                            document.getElementById('h6').style.fontSize = "15px";
                            document.getElementById('back_btn').style.fontSize = "13px";
                        
                        } 
                        else
                        {
                            document.getElementById('body').style.fontSize = "";
                            document.getElementById('title').style.fontSize = "";
                            document.getElementById('h6').style.fontSize = "";
                            document.getElementById('back_btn').style.fontSize = "";
                        }
                    }

                    tabletSize.addListener(handleScreenSizeChange);
                    handleScreenSizeChange(tabletSize);
                </script>

                <table style="width:100%;">
                    <tr>
                        <th><center>Questions<center><br></th>
                        <th><center>Strongly Agree</center><br></th>
                        <th><center>Agree</center><br></th>
                        <th><center>Disagree</center><br></th>
                        <th><center>Strongly Disagree</center><br></th>
                    </tr>
                    <tr>
                        <td><label for=""><b>1.</b> My field of interest was realistically introduced to me though this event.<b><code>*</code></b></label></td><br>
                        <td><center><input type="radio" name="answer1" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer1" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer1" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer1" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    
                    <tr>
                        <td><label for=""><b>2.</b> I now have a greater understanding of the ideas, theories, and abilities involved <br>in my field of study as a result of my internship.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer2" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer2" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer2" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer2" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>3.</b> I was given adequate orientation and training.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer3" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer3" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer3" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer3" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>4.</b> My supervisor and I met on a regular basis, and I continuosly receive critical feedback.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer4" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer4" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer4" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer4" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>5.</b> I was given levels of responsibility that were in the line with my abilities. And as my <br>experience grew, I was given more responsibility.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer5" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer5" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer5" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer5" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>6.</b> When I had concerns or questions, my supervisor was available.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer6" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer6" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer6" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer6" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>7.</b> The work I performed was challenging and stimulating.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer7" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer7" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer7" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer7" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>8.</b> I receive the same treatment as other employees.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer8" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer8" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer8" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer8" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>9.</b> My coworkers and I got along well at work.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer9" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer9" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer9" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer9" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>10.</b> There were ample opportunities for learning.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer10" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer10" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer10" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer10" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>11.</b> After this internship experience, I believe I am more equipped to enter the workforce.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer11" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer11" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer11" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer11" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>12.</b> My internship experiences introduced me to the discipline and working world.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer12" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer12" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer12" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer12" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>13.</b> The internship experience helped me choose the field I wanted to work in after graduation.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer13" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer13" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer13" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer13" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>14.</b> My expectations were met by the organization where I conducted my internship.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer14" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer14" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer14" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer14" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    <tr>
                        <td><label for=""><b>15.</b> I advise future student interns to complete their internship at this organization.<b><code>*</code></b></label></td>
                        <td><center><input type="radio" name="answer15" value="4" onclick="setRequired(this.value)"required></center></td>
                        <td><center><input type="radio" name="answer15" value="3" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer15" value="2" onclick="setRequired(this.value)"></center></td>
                        <td><center><input type="radio" name="answer15" value="1" onclick="setRequired(this.value)"></center></td>
                    </tr>
                    
                </table>


                <style>
                    .code{
                        color: red;
                    }
                </style>
                <br><br>

                
                <div class="md-textarea">
                    <label for="exampleFormControlTextarea1" class="form-label">&emsp;Notes/Comments/Suggestions:</label>
                    <textarea name="comment" id="comment" class="form-control" placeholder="Insert Notes/Comments/Suggestions..." rows="3"></textarea>
                </div>
                <script>
                    CKEDITOR.replace('comment');
                </script>

                <br>
                <div class = "form-group">
                    <center><button type="submit" name = "submit_checkbox" class="btn btn-primary" style="background-color: maroon;">Submit</button></center>
                </div>

                </form>
            </div>

        </div><!--end of main -->
    </section>
</body>
</html>