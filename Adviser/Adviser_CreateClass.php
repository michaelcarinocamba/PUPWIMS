
<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['faculty']))
    {
        header("Location: Adviser_Login.php?LoginFirst");
    }

    $course = "";
    $yr_section = "";
    $school_year = "";
    $class_code = "";
    $faculty = $_SESSION['faculty'];
    

    if(isset($_POST['create'])){
        $course = strip_tags($_POST['course']);

    
        $yr_section = strip_tags($_POST['yrsection']);
        $yr_section = str_replace(' ', '', $yr_section);

        $HRS = $_POST['HRS_Rendered'];

        $semester = $_POST['semester'];
    
        $school_year = $_POST['school_year'];
    
        $class_code = strtolower($course . "_" . $yr_section . "_" . $semester . "_" . "$school_year");
    
        $check_code_query = mysqli_query($conn, "SELECT class_code FROM create_class WHERE class_code = '$class_code'");
    
        $i = 0;
    
        while (mysqli_num_rows($check_code_query) != 0) {
                $i++;
                $class_code = $class_code . "_" . $i;
                $check_code_query = mysqli_query($conn, "SELECT class_code  FROM create_class WHERE class_code = '$class_code'");
        }
        $Adviser = $_SESSION['faculty'];
        $name = $_SESSION['name'];
    
        if(($course != "") && ($yr_section != "") && ($school_year != ""))
        {
            date_default_timezone_set('Asia/Singapore');
            $date_added = date("Y-m-d h:i:s");
            

            $query = mysqli_query($conn, "INSERT INTO create_class VALUES('', '$Adviser', '$course', '$yr_section','$semester','$school_year', '$HRS', '$class_code', '')");
            $task8 = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$Adviser', '$name', '$class_code', 'OJT Portfolio', 'Please attach your overall OJT Portfolio here.', '$date_added','', '', '', 'Not Graded', '1', '', 'no')");
            $task7 = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$Adviser', '$name', '$class_code', 'Certification of Completion', 'Please attach your file here.', '$date_added','', '', '', 'Not Graded', '1', '', 'no')");
            $task6 = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$Adviser', '$name', '$class_code', 'Acceptance Letter from Company', 'Please attach your file here.', '$date_added','', '', '', 'Not Graded', '1', '', 'no')");
            $task5 = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$Adviser', '$name', '$class_code', 'Endorsement Letter', 'Please attach your file here.', '$date_added','', '', '', 'Not Graded', '1', '', 'no')");
            $task4 = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$Adviser', '$name', '$class_code', 'Internship Agreement', 'Please attach your file here.', '$date_added','', '', 'PUP_Internship_Agreement.doc', 'Not Graded', '1', '../OJT_Files/PUP_Internship_Agreement.doc', 'no')");
            $task3 = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$Adviser', '$name', '$class_code', 'Consent Form', 'Please attach your file here.', '$date_added','', '', 'Consent_Form.docx', 'Not Graded', '1', '../OJT_Files/Consent_Form.docx', 'no')");
            $task2 = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$Adviser', '$name', '$class_code', 'Medical Exam', 'Please attach your medical exam result here.', '$date_added','', '', '', 'Not Graded', '1', '', 'no')");
            $task1 = mysqli_query($conn, "INSERT INTO adviser_assignment VALUES ('', '$Adviser', '$name', '$class_code', 'MOA Signed & Notarized', 'Please attach your file here.', '$date_added','', '', 'OJT-MOA_TEMPLATE.docx', 'Not Graded', '1', '../OJT_Files/OJT-MOA_TEMPLATE.docx', 'no')");

        } 
    
        $_SESSION['course'] = "$course";
        $_SESSION['yrsection'] = "$$yr_section";
        $_SESSION['schoolyear'] = "$school_year";
        $_SESSION['semester'] = "$semester";
        $_SESSION['code'] = "$class_code";
        header("Location: Adviser_ClassList.php");
        exit(); 
    }
    

    
?>



<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Class</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel = "stylesheet" href = "../css/adviser_createclass_css.css">

    <style>


      .fixed 
      {
        position: absolute;
        top: 55%;
        left: 50%;
        transform: translate(-50%, -50%);
      }
      .codestyle 
      {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: auto;
        padding: 5px 10px 5px 10px;
        border-radius: 10px;
        box-shadow: 3px 3px 10px #C5C5C5;
        text-align: left;
        }
        .button 
        {
            border: none;
            color: white;
            width: 50%;
            padding: 6px 5px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 10px;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
        .sidebar_bg {
            z-index: -1; 
            object-fit: cover; 
            position: absolute; 
            top: 0; left: 0; 
            width:100%; 
            height: 100%;
        }

        #yrsection {

            width: 100%;
            padding: 5px;
            margin: 0;
            border: 1px solid #ccc;
            background: #fff url('arrow.png') no-repeat right center;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            }
            #yrsection:focus {
            outline: none;
            }

            #school_year {
            width: 100%;
            padding: 5px;
            margin: 0;
            border: 1px solid #ccc;
            background: #fff url('arrow.png') no-repeat right center;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            }
            #school_year:focus {
            outline: none;
            }

            #HRS_Rendered 
            {
                width: 100%;
                padding: 5px;
                margin: 0;
                border: 1px solid #ccc;
                background: #fff url('arrow.png') no-repeat right center;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }
            #HRS_Rendered:focus 
            {
                outline: none;
            }

    </style>

</head>
<body style="background-image: url(../images/GREY-BG.png); background-size: cover; background-position: center; background-repeat: repeat; height: 100vh; margin: 0;">
<div class = "container-fluid" id = "grad1"> <!--CSS IS FIX IN BOOTSTRAP-->
    <!--TOP NAV-->
    <div class = "topnavbar" style="z-index:1;">
    <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
        <div class="topnavbar-right">
        <a target = "_self" href="../Logout.php?Logout=<?php echo $faculty ?>" > 
                    Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
        </div>
    </div> <!--end of topnavbar-->

    <br><br><br><br><br>

 

    <div class = "container">

           <div class="col-6 fixed" id="column_cont">
                <div class="card codestyle">
                    <br>
                    <div class="card-body text-center text">
                        <i class = 'fa fa-group fa-4x'></i>
                        <i class = 'fa fa-plus  fa-2x'></i> 
                        <h2 class="font-weight-bold">CREATE CLASS</h2>
                        
                        <hr class="dashed">  
                        <p> Kindly fill out the form below.</p>
                        
                        <form method="post">

                            <!--Course Name-->
                            <!-- <input type="text" name="course" id="course" placeholder=" Course" oninput="this.value = this.value.toUpperCase()"> -->
                            <label for=""><b>Course</b></label>
                            <select name = "course" class = "form-control" required/>
                            
                            <option value = "BSA">Bachelor of Science in Accountancy</option>
                            <option value = "BSBAFM">Bachelor of Science in Business Administration major in Financial Management</option>
                            <option value = "BSENT">Bachelor of Science in Entrepreneurship</option>
                            <option value = "BSED-ENG">Bachelor of Secondary Education major in English</option>
                            <option value = "BSHM">Bachelor of Science in Hospitality Management</option>
                            <option value = "BSIT">Bachelor of Science in Information Technology</option>
                            <option value = "BSPSY">Bachelor of Science in Psychology</option>
                            </select>
                            <br>
                                <?php if(isset($_GET['error1'])){ ?>
                            
                            <p class="error1"> <?php echo$_GET['error1']; ?> </p>
                                <?php } ?>

                            
                            <br>
                            
                            <div class = "form-row">
                            <div class="form-group col-md-6">
                            <!--Year & Section-->
                                <label for=""><b>Year and Section </b></label><br>
                                <input type="text" name="yrsection" pattern="[0-9!@#$%^&*()_+={}[\]|\\:;'<>,.?/~`-]+" id="yrsection" placeholder="e.g., 4-1" oninput="this.value = this.value.toUpperCase()" required>
                                <br>

                                    </div>


                            
                                    <div class="form-group col-md-6">
                            <!--Subject Name-->
                            <label for=""><b>School Year </b></label><br>
                            <input type="text" name="school_year" id="school_year" pattern="[0-9!@#$%^&*()_+={}[\]|\\:;'<>,.?/~`-]+" placeholder="e.g., 2022-2023" oninput="this.value = this.value.toUpperCase()" required>
                            <br>

                            </div>
                            </div>

                            <div class = "form-row">
                                <div class="form-group col-md-6">
                                    <label for=""><b>Semester</b></label><br>
                                    <select name = "semester" class = "form-control" required/>
                                    <option value = "1st">1st Semester</option>
                                    <option value = "2nd">2nd Semester</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <!--Subject Name-->
                                    <label for=""><b>Required Hours</b></label><br>
                                    <input type="number" name="HRS_Rendered" id="HRS_Rendered" min="0" pattern="[0-9]+" placeholder="Maximum Required Hours" required>
                                    <br>

                                </div>
                            </div>
                            <br>
                            
                            <center>
                                <button type="submit" class="button hbutton" name="create" >Create</button> 
                                <a button href="Adviser_ClassList.php" class="button hbutton" style="text-decoration: none;">Cancel</a> 
                            </center>
                            
                        </form>
                    
                    </div>               
                
                </div>
            </div>
                               

        
        </div> <!--end of row-->
        
    </div> <!--end of container-->
    <script>
        const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
        function handleScreenSizeChange(tabletSize) {
            if (tabletSize.matches) 
            {
                document.getElementById('column_cont').classList.remove('col-6');
                document.getElementById('column_cont').classList.add('col-12');
            } 
            else
            {
                document.getElementById('column_cont').classList.remove('col-12');
                document.getElementById('column_cont').classList.add('col-6');
            }
        }

        tabletSize.addListener(handleScreenSizeChange);
        handleScreenSizeChange(tabletSize);
    </script>

</div> <!--end of container-fluid-->

</body>
</html>