<?php
session_start();
include "../db_conn.php";

$faculty = $_SESSION['faculty'];
if(!isset($_SESSION['faculty']))
{
    header("Location: Adviser_Login.php?LoginFirst");
}



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../db_conn.php';


if(isset($_REQUEST['adddata']))
{

    if(isset($_POST['send_email']))
    {
        $email = $_POST['comp_email'];
        $student = $_POST['get_student'];
        $code  = $_POST['get_code'];
        $name = $_POST['get_name'];
        $company = $_POST['get_company'];
        $faculty = $_SESSION['faculty']; 
        $query = mysqli_query($conn, "SELECT * FROM adviser_info WHERE facultynum = '$faculty'");
        if(mysqli_num_rows($query) === 1)
        {
            $fetch = mysqli_fetch_assoc($query);
            $faculty_email = $fetch['email'];
        }
        $mail = new PHPMailer(true);

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
            $mail->setFrom('pupwims_mailer@pup-wims.site', 'PUP WIMS Adviser');
            $mail->addAddress($email);     //Add a recipient           

            

            //Content
            
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'PUPSJ Internship Evaluation';
            
            $mail->Body    = "
            <table style='border-collapse: collapse; min-width: 100%; font-family: Arial, Helvetica, sans-serif;'>
                <thead>
                    <tr style='background-color:#AA5656;'>
                        <th style='color:white; font-size:25px; padding: 14px 30px;'> PUPSJ Internship Evaluation </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style='background-color: #FFFBF5;border: 1px solid #ddd; font-family: Helvetica; font-size: 18px;margin-left:3%;'>  
                        <br><p>&emsp;Hello and good day <b>$company</b>!<br><br>
                        &emsp;&emsp; We would like to inform you that we need your evaluation for the student name:<b> $name. </b><br><br>
                        &emsp;Kindly send it to the email address provided:<b> $faculty_email</b><br><br>
                        &emsp;Thank you for your cooperation!</p>
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
        $query = mysqli_query($conn, "UPDATE student_record SET status = 'Evaluated' WHERE studentnum = '$student' AND class_code = '$code'");
        header("Location: Adviser_People.php?classCode=$code&Home&evalsuccess");
        
    }

    if(isset($_POST['delete_student']))
        {

            $id = $_POST['student_id'];
            $code  = $_POST['classCode'];        
            
            $query = "UPDATE student_record SET remove = 'yes' Where student_id = '$id' AND class_code = '$code' ORDER by studentnum ASC";
            $query_run = mysqli_query($conn, $query);	


            if ($query_run)
            {
                $_SESSION['status'] = "PARTNER COMPANY INFORMATION HAS SUCCESSFULLY DELETED!";
                header("Location: Adviser_People.php?classCode=$code&Home&deletesuccess");
            }

            else
            {
                $_SESSION['status'] = "OH NO! SOMETHING WENT WRONG!";
                header("Location: Adviser_People.php?classCode=$code&Home");
            }
        }

        if(isset($_POST['permanently_delete']))
        {

            $id = $_POST['student_id'];
            $code  = $_POST['classCode'];

            $query = " DELETE FROM student_record WHERE student_id = '$id' AND class_code = '$code'";
            $query_run = mysqli_query($conn, $query);	


            if ($query_run)
            {
                $_SESSION['status'] = "PARTNER COMPANY INFORMATION HAS SUCCESSFULLY DELETED!";
                header("Location: Adviser_People.php?classCode=$code&Home&deletesuccess");
            }

            else
            {
                $_SESSION['status'] = "OH NO! SOMETHING WENT WRONG!";
                header("Location: Adviser_People.php?classCode=$code&Home");
            }
        }

     if(isset($_POST['retrieve_btn']))
        {

            $id = $_POST['student_id'];
            $code  = $_POST['classCode'];
           
            
            $query = "UPDATE student_record SET remove = 'no' WHERE student_id = '$id' AND class_code = '$code' ORDER by studentnum ASC";
            $query_run = mysqli_query($conn, $query);	


            if ($query_run)
            {
                $_SESSION['status'] = "STUDENT SUCCESSFULLY RETRIEVED!";
                header("Location: Adviser_People.php?classCode=$code&Home&retrievesuccess");
            }

            else
            {
                $_SESSION['status'] = "OH NO! SOMETHING WENT WRONG!";
                header("Location: Adviser_People.php?classCode=$code&Home&retrieveunsuccess");
            }
    }


    if(isset($_POST["upload"]))
    {
        $code = $_POST['getCode'];

        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('csv');

        if($_FILES['file']['name'])
         {
            $filename = explode(".", $_FILES['file']['name']);
             if(end($filename) == "csv")
                {
                $handle = fopen($_FILES['file']['tmp_name'], "r");
                while($data = fgetcsv($handle))
                {
                    $studentnum = mysqli_real_escape_string($conn, $data[0]);
                    $email = mysqli_real_escape_string($conn, $data[1]);

                    $get_student = mysqli_query($conn, "SELECT * FROM student_info WHERE studentnum = '$studentnum'");
                    if(mysqli_num_rows($get_student) > 0)
                    {
                        while($row_student = mysqli_fetch_array($get_student))
                        {
                            $student_id = $row_student['student_id'];
                            $student_name = $row_student['name']; 
                             $url = "http://pup-wims.site/Student/Student_Login.php";
                             $mail = new PHPMailer(true);
                             
                            $faculty = $_SESSION['faculty']; 
                            $query = mysqli_query($conn, "SELECT * FROM adviser_info WHERE facultynum = '$faculty'");
                            if(mysqli_num_rows($query) === 1)
                            {
                                $fetch = mysqli_fetch_assoc($query);
                                $faculty_name = $fetch['name'];
                            }

                            try {
                                $mail->isSMTP();                                            
                                $mail->Host       = 'sg2plzcpnl493881.prod.sin2.secureserver.net';
                                $mail->SMTPAuth   = true;
                                $mail->Username   = 'pupwims_mailer@pup-wims.site';
                                $mail->Password   = 'pupwims123!';
                                $mail->SMTPSecure = 'ssl';     
                                $mail->Port       = 465;                                 
                    
                                //Recipients
                                $mail->setFrom('pupwims_mailer@pup-wims.site', 'PUP WIMS Adviser');
                                $mail->addAddress($email);     //Add a recipient           
                    
                                
                    
                                //Content
                                
                                $mail->isHTML(true);                                  //Set email format to HTML
                                $mail->Subject = 'PUPSJ Internship Class';
                                
                                $mail->Body    = "
                                <table style='border-collapse: collapse; min-width: 100%; font-family: Arial, Helvetica, sans-serif;'>
                                    <thead>
                                        <tr style='background-color:#AA5656;'>
                                            <th style='color:white; font-size:25px; padding: 14px 30px;'> PUPSJ Internship Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style='background-color: #FFFBF5;border: 1px solid #ddd; font-family: Helvetica; font-size: 18px;margin-left:3%;'>  
                                            <br><p>&emsp;Hi, <b>$student_name</b>!<br><br>
                                            &emsp;&emsp; I'd like to inform you that you are already enrolled in our class and can access it.<br><br>
                                            &emsp;To login into your account, you can click <a href='$url' style='text-decoration:none;'>this link</a><br><br>
                                            &emsp;-- $faculty_name</p>
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
                            
                            $validate_student = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND class_code = '$code'");
                            
                        
                            if(mysqli_num_rows($validate_student) > 0)
                            {
                                $data_query1 = mysqli_query($conn, "UPDATE create_class SET student_list = CONCAT(student_list,'$studentnum ,') WHERE class_code = '$code'");
                                $data_query = mysqli_query($conn, "UPDATE student_record SET student_id = '$student_id', studentnum = '$studentnum', name = '$student_name', email = '$email', confirm = 'no' WHERE studentnum ='$studentnum' AND class_code = '$code'");
                            }
                            else
                            { 
                                $data_query = mysqli_query($conn, "UPDATE create_class SET student_list = CONCAT(student_list,'$studentnum ,') WHERE class_code = '$code'");
                                $get_record = mysqli_query($conn, "INSERT INTO student_record VALUES('$student_id', '$studentnum', '$student_name', '$email', '$code', '', '', '', '', '', '', '', 'no', 'no')");

                            }
                                
                            }
                            
                            

                           
                        }
                    }
                    fclose($handle);
                     header("Location: Adviser_People.php?classCode=$code&Home&uploadsuccess");              
                   
                }
                else
                {
                    header("Location: Adviser_People.php?classCode=$code&Home&uploadunsuccess1");
                }
           
            }
            else
            {
                header("Location: Adviser_People.php?classCode=$code&Home&uploadunsuccess2");
            }

        }
           

    if (isset($_POST['checking_viewbtn']))
        {
            $s_id = $_POST['student_id'];
            $code = $_POST['classCode'];
            
            $query = "SELECT * FROM student_record WHERE student_id = '$s_id' AND class_code = '$code'";
            $query_run = mysqli_query($conn, $query);
            $get_status = mysqli_fetch_array($query_run);
            $status = $get_status['status'];

            $student = $get_status['studentnum'];
            $query1 = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$s_id' AND studentnum = '$student' AND class_code = '$code' AND HRS_rendered = '500' AND status = 'Completed'");
            $query2 = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$s_id' AND studentnum = '$student' AND class_code = '$code' AND HRS_rendered = '500' AND status = 'Evaluated'");
            

            if(mysqli_num_rows($query_run) > 0)
            {
                foreach($query_run as $row)
                {
                    echo $return = "
                    <div class = 'form-row'>
                        <div class='form-group col-md-6'>
                        <center><label for = ''><b>Student Number: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['studentnum']}' style='text-align: center;' disabled>
                        </div>
                        <div class='form-group col-md-6'>
                            <center><label for = ''><b>Name: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['name']}' style='text-align: center;' disabled>
                        </div>
                    </div>
                    <div class = 'form-row'>
                        <div class='form-group col-md-6'>
                            <center><label for = ''><b>Email: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['email']}' style='text-align: center;' disabled>
                        </div>
                        <div class='form-group col-md-6'>
                            <center><label for = ''><b>Insurance: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['insurance']}' style='text-align: center;' disabled>
                        </div>
                    </div>
                    
                    

                    <div class = 'form-row'>
                        <div class='form-group col-md-6'>
                            <center><label for = ''><b>Psychological: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['psychological']}' style='text-align: center;' disabled>
                        </div>
                        <div class='form-group col-md-6'>
                            <center><label for = ''><b>Medical: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['medical']}' style='text-align: center;' disabled>
                        </div> 
                    </div>
                    
                    <div class = 'form-row'>
                        <div class='form-group col-md-6'>
                            <center><label for = ''><b>Status: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['status']}' style='text-align: center;' disabled>
                        </div>                      
                        <div class='form-group col-md-6'>
                            <center><label for = ''><b>Hours Rendered: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['HRS_rendered']} Hours' style='text-align: center;' disabled>
                        </div>
                    </div> 
                    <div class = 'form-row'>
                        <div class='form-group col-md-6'>
                            <center><label for = ''><b>Company: </b></label></center>
                            <input type='text' class = 'form-control' placeholder = '{$row['company']}' style='text-align: center;' disabled>
                        </div>
                        ";
                         if(mysqli_num_rows($query1) === 1)
                         {
                            echo $return = "<div class='form-group col-md-6'>
                            <center><label for = ''><b>Send Email to Company </b></label></center>
                            <div  style='text-align:center;'><a href='#' button type='button'  class = 'userinfo btn btn-secondary btn-sm' data-toggle='modal' data-target='#evalStudentModal$s_id'><i class='fa fa-upload'></i></a></div>
                            </div>
                            <!-- Modal EVALUATION -->
                            <div class='modal fade' id='evalStudentModal$s_id' tabindex='-1' role='dialog' aria-labelledby='evalStudentModalLabel' aria-hidden='true'>
                                <div class='modal-dialog modal-lg'  role='document'>
                                    <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title'><b>COMPANY EVALUATION TO STUDENT</b></h5>
                                            
                                            </div>";
                                            
                                            $get_student = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$s_id'");
                                            $get_record = mysqli_fetch_array($get_student);
                                            $student = $get_record['studentnum'];
                                            $code = $get_record['class_code'];
                                            $name = $get_record['name'];
                                            $company = $get_record['company'];

                                            echo $return = "
                                                <div class='modal-body'>
                                                    
                                                    <form action = 'Adviser_People.php?adddata' method = 'POST'>
                                                    <center>
                                                    <p style='color:red;padding:2px;'>Notice: This will email an Evaluation Form to $name's Company <br> and the process of transaction will be held on the outlook. <br> Any replies and comments will only be transact to the email thread.</p>
                                                    
                                                    <br>
                                                    <div class='form-row'>
                                                        <div class='form-group col-md-6'>
                                                            
                                                            <label for = ''><b>Company: </b></label>
                                                            <input type='text' name = 'comp' class = 'form-control' style='text-align: center;pointer-events: none;' value = '$company'>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            
                                                            <label for = ''><b>Company Email Address: </b></label>
                                                            <input type='text' name = 'comp_email' id = 'edit_comp_email' class = 'form-control' style='text-align: center;' placeholder = 'Enter Company Email Address'>
                                                        </div></center>
                                                    <input type = 'hidden' name = 'get_student' value = '$student'>
                                                    <input type = 'hidden' name = 'get_code' value = '$code'>
                                                    <input type = 'hidden' name = 'get_name' value = '$name'>
                                                    <input type = 'hidden' name = 'get_company' value = '$company'>
                                                </div>
                                            
                                           
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                            <button type='submit' name = 'send_email' class='btn btn-primary'>Send</button>
                                        </div>
                                        
                                    </div></form>
                                </div>
                            </div>
                            
                            ";  
                                  
                         }
                         else if (mysqli_num_rows($query2) === 1)
                         {
                            echo $return = "<div class='form-group col-md-6'>
                            <center><label for = ''><b>Send Email to Company again</b></label></center>
                            <div  style='text-align:center;'><a href='#' button type='button'  class = 'userinfo btn btn-secondary btn-sm' data-toggle='modal' data-target='#evalStudentModal$s_id'><i class='fa fa-upload'></i></a></div>
                            </div>
                            <!-- Modal EVALUATION -->
                            <div class='modal fade' id='evalStudentModal$s_id' tabindex='-1' role='dialog' aria-labelledby='evalStudentModalLabel' aria-hidden='true'>
                                <div class='modal-dialog modal-lg'  role='document'>
                                    <div class='modal-content'>
                                            <div class='modal-header'>
                                            
                                                <h5 class='modal-title'><b>COMPANY EVALUATION TO STUDENT</b></h5>
                                            
                                            </div>";
                                            
                                            $get_student = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$s_id'");
                                            $get_record = mysqli_fetch_array($get_student);
                                            $student = $get_record['studentnum'];
                                            $code = $get_record['class_code'];
                                            $name = $get_record['name'];
                                            $company = $get_record['company'];

                                            echo $return = "
                                                <div class='modal-body'>
                                                    
                                                    <form action = 'Adviser_People.php?adddata' method = 'POST'>
                                                    <center>
                                                    <p style='color:red;padding:2px;'>Notice: This will email an Evaluation Form to $name's Company <br> and the process of transaction will be held on the outlook. <br> Any replies and comments will only be transact to the email thread.</p>
                                                    
                                                    <br>
                                                    <div class='form-row'>
                                                        <div class='form-group col-md-6'>
                                                            
                                                            <label for = ''><b>Company: </b></label>
                                                            <input type='text' name = 'comp' class = 'form-control' style='text-align: center;pointer-events: none;' value = '$company'>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            
                                                            <label for = ''><b>Company Email Address: </b></label>
                                                            <input type='text' name = 'comp_email' id = 'edit_comp_email' class = 'form-control' style='text-align: center;' placeholder = 'Enter Company Email Address'>
                                                        </div></center>
                                                    <input type = 'hidden' name = 'get_student' value = '$student'>
                                                    <input type = 'hidden' name = 'get_code' value = '$code'>
                                                    <input type = 'hidden' name = 'get_name' value = '$name'>
                                                    <input type = 'hidden' name = 'get_company' value = '$company'>
                                                </div>
                                            
                                           
                                        <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                            <button type='submit' name = 'send_email' class='btn btn-primary'>Send</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            ";  
                         }
                         else
                         {
                            echo $return = "<div class='form-group col-md-6'>
                            <center><label for = ''><b>Send Email to Company</b></label></center>
                           <p style='font-size:12px;text-align:center;'>Will only appear when student's done with OJT Process</p>
                            </div>";
                         }
                         echo $return = "
                        
                    </div>             
                    
                ";
                

                }
            }

            else
            {
            echo $return = "NO RECORD!";
            }
        }

        if (isset($_POST['checking_editbtn']))
        { 
            $s_id = $_POST['student_id'];
            $code  = $_POST['classCode'];
            $result_array = [];
            $result_array_info = [];
            
            $query = "SELECT t1.*, t2.contact_number FROM student_record t1 JOIN student_info t2 ON t1.student_id = t2.student_id WHERE t1.student_id = '$s_id' AND t1.class_code = '$code'";
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
                $data = null;
                header('Content-Type: application/json');
                echo json_encode($data);
            }
        }

        if(isset($_POST['update_student']))
        {
            $code  = $_POST['classCode'];
            $s_id = $_POST['edit_id'];
            $student_num = $_POST['studentnum'];
            $student_name = $_POST['name'];
            $student_email = $_POST['emailadd'];
            $student_company = $_POST['company'];
            $student_psychological = $_POST['psychological'];   
            $student_medical = $_POST['medical'];
            $student_status = $_POST['status'];
            $student_DTR = $_POST['DTR'];
            if($_POST['insurance'] === "Select")
            {
                $student_insurance = $_POST['Sinsurance'];
            }
            else
            {
                $student_insurance = $_POST['insurance'];
            }
            
            $time = $_POST['HRS_rendered'];
            

            $query = mysqli_query($conn, "UPDATE student_record SET company = '$student_company', psychological = '$student_psychological', medical = '$student_medical' WHERE student_id = '$s_id' AND class_code = '$code'");


            if ($query)
            {
                            
            if($student_psychological === 'Accepted' && $student_medical === 'Accepted')
            {
                $query = mysqli_query($conn, "UPDATE student_record SET status = '$student_status', dtr_option = '$student_DTR', HRS_rendered = '$time', insurance = '$student_insurance' WHERE student_id = '$s_id' AND class_code = '$code'");
                header("Location: Adviser_People.php?classCode=$code&Home&editsuccess");
            }
            else
            {
                $query = mysqli_query($conn, "UPDATE student_record SET status = 'Incomplete' WHERE student_id = '$s_id' AND class_code = '$code'");
                header("Location: Adviser_People.php?classCode=$code&Home&editsuccess&incomplete");
            }
                
            }
    
            else
            {
                $_SESSION['status'] = "OH NO! SOMETHING WENT WRONG!";
                header("Location: Adviser_People.php?classCode=$code&Home&unsuccess");
            }
        }

        if(isset($_POST['endorse']))
        {
            $code = $_POST['classCode'];
            $students = $_POST['student'];
            
     
            //Company
            if($_POST['S_company'] === '')
            {
                $company_name = $_POST['company'];
                
            }
            else if ($_POST['company'] === '')
            {
                $company_name = $_POST['S_company'];
                
            }
            else
            {
                $company_name = "";
                
            } 
            //Insurance
            if($_POST['S_insurance'] === '')
            {
                
                $Insurance = $_POST['insurance'];
            }
            else if ($_POST['insurance'] === '')
            {
                
                $Insurance = $_POST['S_insurance'];
            }
            else
            {
                
                $Insurance = "";
            } 

            //Status
            if($_POST['status'] !== "")
            {
                $Status = $_POST['status'];
            }
            else
            {
                $Status = "";
            }

            //DTR
            if($_POST['DTR'] !== "")
            {
                $DTR = $_POST['DTR'];
            }
            else
            {
                $DTR = "";
            }

            //Psychological
            if($_POST['psychological'] !== "")
            {
                $Psychological = $_POST['psychological'];
            }
            else
            {
                $Psychological = "";
            }

            //Medical
            if($_POST['medical'] !== "")
            {
                $Medical = $_POST['medical'];
            }
            else
            {
                $Medical = "";
            }

            //Hours Rendered
            if($_POST['HRS_rendered'] !== "")
            {
                $HRS = $_POST['HRS_rendered'];
                $HRS_Rendered = intval($HRS);

            }
            else
            {
                $HRS_Rendered = "";
            }
            
            

            foreach ($students as $s_id) {

                $hrs_query = mysqli_query($conn, "SELECT HRS_rendered FROM student_record WHERE student_id = '$s_id' AND class_code = '$code'");
                $fetch = mysqli_fetch_array($hrs_query);
                $HRS_record = $fetch['HRS_rendered'];

                $get_max_hours = mysqli_query($conn, "SELECT HRS_Rendered FROM create_class WHERE class_code = '$code'");
                $fetch_hour = mysqli_fetch_array($get_max_hours);
                $Max_HRS = $fetch_hour['HRS_Rendered'];

                $add_HRS = $HRS_record + $HRS_Rendered;
                
                if($add_HRS <= $Max_HRS)
                {
                    $HRS_added = $add_HRS;
                }
                else
                {
                    $HRS_added = $HRS_record;
                }
                                
                $company = in_array($s_id, $students) ? $company_name : '';
                $insurance = in_array($s_id, $students) ? $Insurance : '';
                $status = in_array($s_id, $students) ? $Status : '';
                $DTR_Opt = in_array($s_id, $students) ? $DTR : '';
                $psychological = in_array($s_id, $students) ? $Psychological : '';
                $medical = in_array($s_id, $students) ? $Medical : '';
                $hrs_rendered = in_array($s_id, $students) ? $HRS_added : '';

                if($company_name !== "" || $Insurance !== "")
                {
                    $sql = mysqli_query($conn,"UPDATE student_record SET company = '$company' WHERE student_id = '$s_id' AND class_code = '$code'");    
                }

                if($Insurance !== "")
                {
                    $sql = mysqli_query($conn,"UPDATE student_record SET insurance = '$insurance' WHERE student_id = '$s_id' AND class_code = '$code'");
                }
                
                if($Status !== "")
                {
                    $sql = mysqli_query($conn,"UPDATE student_record SET status = '$status' WHERE student_id = '$s_id' AND class_code = '$code'");
                }

                if($DTR !== "")
                {
                    $sql = mysqli_query($conn,"UPDATE student_record SET dtr_option = '$DTR_Opt' WHERE student_id = '$s_id' AND class_code = '$code'");
                }

                if($Psychological !== "")
                {
                    $sql = mysqli_query($conn,"UPDATE student_record SET psychological = '$psychological' WHERE student_id = '$s_id' AND class_code = '$code'");
                }

                if($Medical !== "")
                {
                    $sql = mysqli_query($conn,"UPDATE student_record SET medical = '$medical' WHERE student_id = '$s_id' AND class_code = '$code'");
                }

                if($HRS_Rendered !== "")
                {
                    $sql = mysqli_query($conn,"UPDATE student_record SET HRS_rendered = '$hrs_rendered' WHERE student_id = '$s_id' AND class_code = '$code'");
                }

                
                if($company_name === "" && $Insurance === "" && $Status === "" && $DTR === "" && $Psychological === "" && $Medical === "")
                {
                    header("Location: Adviser_People.php?classCode=$code&Home&unsuccess");
                }
                
                
            }
            header("Location: Adviser_People.php?classCode=$code&Home&editSuccess");
        }
    }

        




if(isset($_REQUEST['Home']))
{
    
    $code = $_REQUEST['classCode'];
    $str="";
    $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row1 = mysqli_fetch_array($sql);
    $course = $row1['course'];
    $students = $row1['student_list'];
    $students = str_replace(',', ' ', $students);
    $classID = $row1['class_id'];
    $sql2 = mysqli_query($conn, "SELECT * FROM adviser_info, create_class WHERE adviser_info.facultynum = create_class.facultynum AND class_code='$code'");
    $row2 = mysqli_fetch_array($sql2);
    $name = $row2['name'];
    $id = $row2['facultynum'];
    $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    if(mysqli_num_rows($section) > 0)
    {
        $get_section = mysqli_fetch_array($section);

        $course = $get_section['course'];
        $year_section = $get_section['year_section'];
    }

    $query = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code' AND status = ''");
    if(mysqli_num_rows($query) > 0)
    {
        while($row = mysqli_fetch_array($query))
        {
            
            $student_id = $row['student_id'];



            $update = mysqli_query($conn, "UPDATE student_record SET status= 'Incomplete' WHERE student_id = '$student_id' AND status = '' AND class_code = '$code'");
            
        }
    }


    $message ="";

    $query = "SELECT * FROM student_record WHERE remove = 'no' AND class_code = '$code' ORDER by studentnum ASC";
    $result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>People</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "../css/adviser_dashhome.css">
    
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
            white-space: nowrap;
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

        .columns {
            display: flex;
            flex-wrap: wrap;
        }
        .column {
            flex: 50%;
            padding: 5px;
        }

        input[type="checkbox"] {
        transform: scale(1.5);
        }
    </style>
</head>

<body>


    <!--SIDE BAR-->
    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
        <center>

            <!--Background Side Bar-->

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
        <div class = "topnavbar" style="z-index:2;">
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
            }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>

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
            title: 'Added successfully'
        })
        </script>
        <?php
        }
         if(isset($_REQUEST['evalsuccess']))
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
             title: 'Email sent successfully'
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
            title: 'Deleted successfully'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['incomplete']))
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
                    title: 'Psychological & Medical Results are not accepted.'
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
            title: 'Edited successfully'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['Norecord']))
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
            title: 'No Student Record detected!'
        })
        </script>
        <?php
            }
            if(isset($_REQUEST['uploadunsuccess1']))
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
            if(isset($_REQUEST['uploadunsuccess2']))
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
                if(isset($_REQUEST['editSuccess']))
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
                title: 'Student(s) Information successfully updated'
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
                ?>
            

                

            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Student 
             
            
            <br></h1> 
            <hr style="background-color:maroon;border-width:2px; "> 
            <button type="button" class="btn btn-primary" style="float:right;background-color:maroon;" data-bs-toggle="modal" data-bs-target="#addStudent"> <b>+ ENROLL STUDENT</b> </button> <br><br>
            <button type="button" class="btn btn-primary" style="background-color:maroon; float:right;" data-bs-toggle="modal" data-bs-target="#endorse"> <i class="fa fa-users"></i> <b>EDIT STUDENTS INFORMATION</b> </button>  <button type="button" class="btn btn-primary" style="float:left;background-color:maroon;float:right;" data-bs-toggle="modal" data-bs-target="#trash"> <i class="fa fa-trash" aria-hidden="true"></i></button> <br><br>
           
            
                
                        <div class="modal fade" id="addStudent">
                            <div class="modal-dialog modal-lg" style='max-width: 80%;'>
                                <div class="modal-content">

                                <div class="modal-header">
                                <h5 class="modal-title"><b>UPLOAD CSV FILE</b></h5>
                                
                                </div>

                                <form action="Adviser_People.php?adddata&classCode='<?php echo $code; ?>'" method="post" enctype='multipart/form-data'>
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4" style="text-align:center; margin-top: 10%;">
                                            
                                                <label for = ""><b>Place CSV Folder Here</b></label><br>
                                                <p>&emsp;&emsp;<input type="file" name="file" /></p>
                                            

                                            <br><br><br>
                                            <a class="btn btn-primary" style="background-color:maroon;" href="../csvfiles/sample_student_enrollment.csv" download><i class="fa fa-download" aria-hidden="true"></i> <b> &nbsp; Download Template </b></a>
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
                                <input type="hidden" name="getCode" value="<?php echo $code ?>">
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name = "upload" class="btn btn-primary" style="background-color:maroon;">Save</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL FOR TRASH  -->
                        <div class="modal fade" id="trash" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl" style='max-width: 80%;'>
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title fs-5" id="exampleModalLabel">Deleted Students</h3>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="table table-responsive">
                                        <table id="table">
                                        <thead>
                                            <tr>
                                            <th style='display: none;'></th>
                                            <th style='display: none;'></th>
                                            <th style="white-space: nowrap;">Student Number</th>
                                            <th>Name</th>
                                            <th>Email Address</th>
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $query1 = mysqli_query($conn, "SELECT * FROM student_record WHERE remove = 'yes' AND class_code = '$code'");

                                                if(mysqli_num_rows($query1)> 0){
                                                    while($row = mysqli_fetch_array($query1))
                                                    {
                                                        ?>
                                                        <tr> 
                                                        <td class="student_id" style='display: none;'><?php echo $row['student_id']; ?></td>
                                                        <td class="classCode" style='display: none;'><?php echo $row['class_code']; ?></td>
                                                        <td><?php echo $row['studentnum']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>  
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

                        <script>
                               $('.permanently_delete').click(function(e) {
                                e.preventDefault();

                                var stud_id = $(this).closest('tr').find('.student_id').text();
                                $('#perm_delete_id').val(stud_id);
                                $('#permanent_deleteStudentModal').modal('show');
                                });

                                $('.retrieve_btn').click(function(e) {
                                e.preventDefault();

                                var stud_id = $(this).closest('tr').find('.student_id').text();
                                $('#retrieve_id').val(stud_id);
                                $('#retrieve_studentModal').modal('show');
                                });
                                
                        </script>


                        <!-- modal for retrieve -->

                        <div class="modal fade" id="retrieve_studentModal" tabindex="-1" role="dialog" aria-labelledby="retrieve_studentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id = "retrieve_studentModalLabel"><b>CONFIRMATION</b></h5>
                                    </button>
                                </div>
                                <form action = "Adviser_People.php?adddata" method = "POST">
                                <div class="modal-body">
                                <input type = "hidden" name = "student_id" id = "retrieve_id">
                                <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">
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


                            <!-- Modal PERMANENTLY DELETE DATA-->
                        <div class="modal fade" id="permanent_deleteStudentModal" tabindex="-1"  role="dialog" aria-labelledby="permanent_deleteStudentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id = "permanent_deleteStudentModalLabel"> <b>CONFIRMATION</b></h5>
                                    </button>
                                </div>
                                <form action = "Adviser_People.php?adddata" method = "POST">
                                <div class="modal-body">
                                <input type = "hidden" name = "student_id" id = "perm_delete_id">
                                <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">
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

            <!-- modal for endorse student -->
            <div class="modal fade" id="endorse" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg" style='max-width: 80%;'>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>EDIT STUDENTS INFORMATION</b></h5>
                        </div>
                        <div class="modal-body">
                            <form action="Adviser_People.php?adddata&classCode='<?php echo $code; ?>'" method="POST" id='myForm' onsubmit='return validateForm()'> 
                            <div class = "form-row">

                            <?php 
                                 $get_HRS = mysqli_query($conn, "SELECT HRS_Rendered FROM create_class WHERE class_code = '$code'");
                                 $get = mysqli_fetch_array($get_HRS);
                                 $HRS = $get['HRS_Rendered'];
                            ?>

                            
                            <div class="form-group col-md-6">
                                <input type="hidden" name="classCode" value="<?php echo $code; ?>">
                                <label for = ""><b>Company</b> <i style="font-size:13px;">(if others, please use Specify Company instead)</i></label>
                                <select name = "company" class = "form-control" />
                                <option value = "">--Select Company--</option>
                                <?php
                                $company_query = mysqli_query($conn, "SELECT * FROM company_list");
                                if(mysqli_num_rows($company_query) > 0)
                                {
                                    while($comp_row = mysqli_fetch_array($company_query))
                                    {
                                        
                                        echo "<option value = '{$comp_row['company_name']}'>{$comp_row['company_name']} </option>";
                                    }
                                }
                                ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Specify Company: </b> </label>
                                <input type="text" name = "S_company"  class = "form-control" placeholder = "Enter Company Name"  oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Psychological Exam Status</b></label>
                                <select name = "psychological"  class = "form-control" />
                                <option value = "">--Select Psychological Exam Status--</option>
                                <option value = "Accepted">Accepted</option>
                                <option value = "Declined">Declined</option>
                                <option value = "Processing">Processing</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Medical Exam Status</b></label>
                                <select name = "medical" class = "form-control" />
                                <option value = "">--Select Medical Exam Status--</option>
                                <option value = "Accepted">Accepted</option>
                                <option value = "Declined">Declined</option>
                                <option value = "Processing">Processing</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Health Insurance</b><i style="font-size:13px;">(if others, please use Specify Insurance instead)</i></label>
                                <select name = "insurance"  class = "form-control" />
                                <option value = "">--Select Insurance--</option>
                                <option value = "Gcash">Gcash</option>
                                <option value = "Cebuana">Cebuana</option>
                                <option value = "MLhullier">MLhullier</option>
                                <option value = "Manulife">Manulife</option>
                                <option value = "Sun Life">Sun Life</option>
                                <option value = "Phil Health">Phil Health</option>
                                <option value = "MariaHealth">MariaHealth</option>
                                <option value = "Pru Life">Pru Life</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Specify Insurance: </b></label>
                                <input type="text" name = "S_insurance" class = "form-control" placeholder = "Enter Insurance Name">
                            </div>

                            

                            <div class="form-group col-md-6">
                                <label for = ""><b>Status</b></label>
                                <select name = "status"  class = "form-control" />
                                <option value = "">--Select Student Status--</option>
                                <option value = "Incomplete">Incomplete</option>
                                <option value = "On Going">On Going</option>
                                <option value = "Withdrawn">Withdrawn</option>
                                <option value = "Completed">Completed</option>
                                <option value = "Evaluated">Evaluated</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>DTR Option</b></label>
                                <select name = "DTR" class = "form-control" />
                                <option value = "">--Select DTR Option--</option>
                                <option value = "Company">Company</option>
                                <option value = "Classwork">Classwork</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for = ""><b>HOURS Rendered</b></label>
                                
                                <input type="number" name = "HRS_rendered" placeholder = "Add hour(s) to each students' hours rendered. (Max-Hours: <?php echo $HRS; ?>)" class = "form-control" max="<?php echo $HRS; ?>" >
                            </div>


                          
                            </div>
                            <hr>
                                <h6 style='text-align:center;'>Student(s) list</h6><br>         
                                <?php
                                    echo "<input type='checkbox' id='select-all-checkbox'> Select All <br><br>";
                                    $query = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$code'");
                                    $column = 0;
                                    echo "<div class='columns'>";
                                    
                                    if(mysqli_num_rows($query) > 0)
                                    {
                                        $i = 0; 
                                        while($row = mysqli_fetch_array($query))
                                        {
                                            
                                            echo "<div class='column' style='font-size:20px;'>";
                                            echo "<input type='checkbox' class='checkbox' name='student[]' value='{$row['student_id']}'> {$row['name']}<br>";
                                            echo "</div>";
                                            $column++;

                                            if ($column % 2 == 0) {
                                                echo "</div><div class='columns'>";
                                            }
                                        }
                                    }
                                    
                                    echo "<input type='hidden' name='input_text' id='input_text'><br>";
                                    echo "</div>";
                                    echo "<script>
                                        document.getElementById('myForm').addEventListener('submit', function(event) 
                                        {
                                            var checkboxes = document.getElementsByName('student[]');
                                            var checked = false;
                                            for (var i = 0; i < checkboxes.length; i++)
                                            {
                                                if (checkboxes[i].checked)
                                                {
                                                    checked = true;
                                                    break;
                                                }
                                            }
                                            if (!checked) 
                                            {
                                                document.getElementById('input_text').setAttribute('required', true);
                                                event.preventDefault();
                                            }
                                        });

                                        
                                    </script>
                                    <script>
                                    const selectAllCheckbox = document.getElementById('select-all-checkbox');
                                    const checkboxes = document.querySelectorAll('.checkbox');

                                    selectAllCheckbox.addEventListener('change', () => {
                                        checkboxes.forEach(checkbox => {
                                            checkbox.checked = selectAllCheckbox.checked;
                                        });
                                    });
                                    </script>";
                                ?>
                            
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name = "endorse" class="btn btn-primary" style="background-color:maroon;">Endorse</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>

            <!-- end of modal -->

            
                    

                    <?php echo $message; ?>    
                
            <div class="people">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <h3> Adviser </h3>
                        <div class="card">
                            <div class="card-body">
                                <h5><?php 
                                    echo $name;
                                ?></h5>
                                
                            </div>
                        </div>
                    
                        <br>
                        <h3> Students </h3>
                        
                            <div class="card">
                                <div class="card-body">
                                <div class="table table-responsive">
                                <table id="table">
                                    <thead>
                                        <tr>
                                        <th style='display: none;'></th>
                                        <th style='display: none;'></th>
                                        <th style="white-space: nowrap;">Student Number</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Contact Number</th>
                                        <th>Company</th>
                                        <th>Hours Rendered</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            if(mysqli_num_rows($result) > 0)    
                                            {
                                                while($row = mysqli_fetch_array($result))
                                                {
                                                    $student = $row['studentnum'];
                                                    $id = $row['student_id'];
                                                    $status = $row['status'];
                                                    $company = $row['company'];
                                                    $code = $row['class_code'];
                                                    $HRS_Rendered = $row['HRS_rendered'];

                                                    $get_HRS = mysqli_query($conn, "SELECT HRS_Rendered FROM create_class WHERE class_code = '$code'");
                                                    $get = mysqli_fetch_array($get_HRS);
                                                    $HRS = $get['HRS_Rendered'];

                                                    
                                                    $query_info = mysqli_query($conn, "SELECT * FROM student_info WHERE student_id = '$id'");
                                                    $get_number = mysqli_fetch_array($query_info);
                                                    $number = $get_number['contact_number'];
                                                    
                                                    $query1 = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$id' AND studentnum = '$student' AND class_code = '$code' AND HRS_rendered = '500' AND status = 'Completed'");
                                                    
                                                    ?>

                                                
                                                    <tr>
                                                        <td class="stud_id" style='display: none;'><?php echo $row['student_id']; ?></td>
                                                        <td class="classCode" style='display: none;'><?php echo $row['class_code']; ?></td>
                                                        <td><?php echo $row['studentnum']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td> 
                                                        <td><?php echo $number; ?> </td>         
                                                        <td class="company"><?php echo $row['company']; ?></td> 
                                                        <td><?php echo $row['HRS_rendered']; ?></td>
                                                        <?php
                                                         $query = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$id' AND studentnum = '$student' AND class_code = '$code' AND status = 'Completed' AND HRS_rendered = '500'");
                                        
                                                         if(mysqli_num_rows($query) === 1) 
                                                         {
                                                            $row1 = mysqli_fetch_array($query);
                                                            $id = $row1['student_id'];
                                                            
                                                            
                                                                ?>
                                                                    <td><a href="#" button type="button" class = "userinfo btn btn-secondary btn-sm" data-bs-toggle='modal' data-bs-target='#evalStudentModal<?php echo $id; ?>'><i class='fa fa-upload'></i></a></td>
                                                                             
                                                                    <!-- Modal EVALUATION -->
                                                                    <div class="modal fade" id="evalStudentModal<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="evalStudentModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg" id="eval_modal" role="document">
                                                                            <div class="modal-content">

                                                                            

                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"><b>COMPANY EVALUATION TO STUDENT</b></h5>
                                                                                    
                                                                                    </div>
                                                                                    <?php
                                                                                    $get_student = mysqli_query($conn, "SELECT * FROM student_record WHERE student_id = '$id'");
                                                                                    $get_record = mysqli_fetch_array($get_student);
                                                                                    $student = $get_record['studentnum'];
                                                                                    $code = $get_record['class_code'];
                                                                                    $name = $get_record['name'];
                                                                                    $company = $get_record['company'];
                                                                                    
                                                                                    ?>
                                                                                    
                                                                                        <div class="modal-body">
                                                                                            
                                                                                            <form action = "Adviser_People.php?adddata" method = "POST">
                                                                                            <center>
                                                                                            <p style="color:red;padding:2px;">Notice: This will email an Evaluation Form to <?php $name ?>'s Company <br> and the process of transaction will be held on the outlook. <br> Any replies and comments will only be transact to the email thread.</p>
                                                                                            
                                                                                            <br>
                                                                                            <div class="form-row">
                                                                                                <div class="form-group col-md-6">
                                                                                                    
                                                                                                    <label for = ""><b>Company: </b></label>
                                                                                                    <input type="text" name = "comp=" class = "form-control" style="text-align: center;pointer-events: none;" value = "<?php echo $company; ?>" required>
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    
                                                                                                    <label for = ""><b>Company Email Address: </b></label>
                                                                                                    <input type="text" name = "comp_email" id = "edit_comp_email" class = "form-control" style="text-align: center;" placeholder = "Enter Company Email Address" required>
                                                                                                </div></center>
                                                                                            <input type = "hidden" name = "get_student" value = "<?php echo $student; ?>">
                                                                                            <input type = "hidden" name = "get_code" value = "<?php echo $code; ?>">
                                                                                            <input type = "hidden" name = "get_name" value = "<?php echo $name; ?>">
                                                                                            <input type = "hidden" name = "get_company" value = "<?php echo $company; ?>">
                                                                                        </div>
                                                                                    
                                                                                   
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                    <button type="submit" name = "send_email" class="btn btn-primary">Send</button>
                                                                                </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                            
                                                         }
                                                         else if($status == "Evaluated")
                                                         {
                                                            ?>
                                                            <td>Evaluated</td>
                                                            <?php
                                                         }
                                                         else
                                                         {
                                                            ?>
                                                            <td><?php echo $row['status']; ?></td>
                                                            <?php
                                                         }
                                                        ?>
                                                        
                                                        <td>
                                                        <div class="btn-group" role="group" aria-label="Button Group">
                                                            <a href="#" button type="button" class = "userinfo btn btn-primary view_btn btn-sm"><i class='fa fa-eye'></i></a>                        
                                                            <!-- Modal VIEW DATA-->
                                                        <div class="modal fade" id="viewStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" style='max-width: 80%;' role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                                                                    </div>
                                                            <div class="modal-body">
                                                                <div class = "student_view"> </div>
                                                            </div>
                                                            <div class="modal-footer">

                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>

                                                            <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn btn-sm" data-bs-toggle='modal' data-bs-target='#editStudentModal'><i class='fa fa-edit'></i></a>

                                                            <!-- Modal EDIT DATA -->          
                                                                <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" style='max-width: 80%;' role="document">
                                                                        <div class="modal-content">

                                                                        

                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                                                                                
                                                                                </div>
                                                                                
                                                                                
                                                                                    <div class="modal-body">
                                                                                        <form action = "Adviser_People.php?adddata" method = "POST">
                                                                                        <input type = "hidden" name = "edit_id" id = "edit_id">
                                                                                        <div class = "form-row">
                                                                                        <div class="form-group col-md-6">
                                                                                            <label for = ""><b>Student Number: </b></label>
                                                                                            <input type="text" name = "studentnum" id = "edit_studentnum" class = "form-control" placeholder = "Enter Student Number" style="pointer-events: none;" disabled>
                                                                                        </div>

                                                                                        <div class="form-group col-md-6">
                                                                                            <label for = ""><b>Name: </b></label>
                                                                                            <input type="text" name = "name" id = "edit_name" class = "form-control" placeholder = "Enter Name" style="pointer-events: none;" disabled>
                                                                                        </div>

                                                                                        <div class="form-group col-md-6">
                                                                                            <label for = ""><b>Contact Number: </b></label>
                                                                                            <input type="text" name = "contact" id = "edit_contact" class = "form-control" placeholder = "Enter Contact Number" disabled>
                                                                                        </div>

                                                                                        <div class="form-group col-md-6">
                                                                                            <label for = ""><b>Email Address: </b></label>
                                                                                            <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" placeholder = "Enter Email Address" disabled>
                                                                                        </div>

                                                                                            <div class="form-group col-md-6">
                                                                                                <label for = ""><b>Psychological Exam Status</b></label>
                                                                                                <select name = "psychological" id = "edit_psychological" class = "form-control" />
                                                                                                <option value = "">--Select Psychological Exam Status--</option>
                                                                                                <option value = "Accepted">Accepted</option>
                                                                                                <option value = "Declined">Declined</option>
                                                                                                <option value = "Processing">Processing</option>
                                                                                                </select>
                                                                                            </div>

                                                                                            <div class="form-group col-md-6">
                                                                                                <label for = ""><b>Medical Exam Status</b></label>
                                                                                                <select name = "medical" id = "edit_medical" class = "form-control" />
                                                                                                <option value = "">--Select Medical Exam Status--</option>
                                                                                                <option value = "Accepted">Accepted</option>
                                                                                                <option value = "Declined">Declined</option>
                                                                                                <option value = "Processing">Processing</option>
                                                                                                </select>
                                                                                            </div>

                                                                                            <div class="form-group col-md-6">
                                                                                                <label for = ""><b>Status</b></label>
                                                                                                <select name = "status" id = "edit_status" class = "form-control" />
                                                                                                <option value = "">--Select Student Status--</option>
                                                                                                <option value = "Incomplete">Incomplete</option>
                                                                                                <option value = "On Going">On Going</option>
                                                                                                <option value = "Withdrawn">Withdrawn</option>
                                                                                                <option value = "Completed">Completed</option>
                                                                                                <option value = "Evaluated">Evaluated</option>
                                                                                                </select>
                                                                                            </div>

                                                                                            <div class="form-group col-md-6">
                                                                                                <label for = ""><b>DTR Option</b></label>
                                                                                                <select name = "DTR" id = "edit_DTR" class = "form-control" />
                                                                                                <option value = "">--Select DTR Option--</option>
                                                                                                <option value = "Company">Company</option>
                                                                                                <option value = "Classwork">Classwork</option>
                                                                                                </select>
                                                                                            </div>

                                                                                            <div class="form-group col-md-6">
                                                                                                <label for = ""><b>Health Insurance</b></label>
                                                                                                <select name = "insurance" id = "edit_insurance" class = "form-control" />
                                                                                                <option value = "">--Select Insurance--</option>
                                                                                                <option value = "Gcash">Gcash</option>
                                                                                                <option value = "Cebuana">Cebuana</option>
                                                                                                <option value = "MLhullier">MLhullier</option>
                                                                                                <option value = "Manulife">Manulife</option>
                                                                                                <option value = "Sun Life">Sun Life</option>
                                                                                                <option value = "Phil Health">Phil Health</option>
                                                                                                <option value = "MariaHealth">MariaHealth</option>
                                                                                                <option value = "Pru Life">Pru Life</option>
                                                                                                </select>
                                                                                            </div>

                                                                                            <div class="form-group col-md-6">
                                                                                                <label for = ""><b>Specify Insurance: </b></label>
                                                                                                <input type="text" name = "Sinsurance" id = "edit_Sinsurance" class = "form-control" placeholder = "Enter Insurance Name">
                                                                                            </div>
                                                                                            
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for = ""><b>Company: </b></label>
                                                                                                <input type="text" name = "company" id = "edit_company" class = "form-control" placeholder = "Enter Company">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for = ""><b>HOURS Rendered</b></label>
                                                                                                
                                                                                                <input type="number" name = "HRS_rendered" id = "edit_HRS_Rendered" class = "form-control" max="<?php echo $HRS; ?>" >
                                                                                            </div>
                                                                                            
                                                                                        </div>
                                                                                        <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">

                                                                                    </div><!--end of modal body -->

                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
                                                                            </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <a href="#" button type="button" class = "userinfo btn btn-danger delete_btn btn-sm"><i class='fa fa-trash'></i></a>

                                                                     <!-- Modal DELETE DATA-->
                                                                    <div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg" style='max-width: 80%;' role="document">
                                                                            <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id = "deleteStudentModalLabel"><b>CONFIRMATION</b></h5>
                                                                                </button>
                                                                            </div>
                                                                            <form action = "Adviser_People.php?adddata" method = "POST">
                                                                            <div class="modal-body">
                                                                            <input type = "hidden" name = "student_id" id = "delete_id">
                                                                            <input type = "hidden" name = "classCode" value = "<?php echo $code; ?>">
                                                                                <h4> Do you want to delete this information? </h4>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit" name = "delete_student" class="btn btn-danger">Confirm</button>
                                                                            </div>
                                                                            </form>
                                                                            </div>
                                                                        </div>
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
                    
                    </div>
                </div>
            
      


        



        <script>
            $(document).ready(function() {

                const modals = document.querySelectorAll('.modal');

                // Add event listener to each modal
                modals.forEach((modal) => {
                modal.addEventListener('show.bs.modal', () => {
                    // Close all other modals before showing the current modal
                    modals.forEach((otherModal) => {
                    if (otherModal !== modal && $(otherModal).hasClass('show')) {
                        $(otherModal).modal('hide');
                    }
                    });
                });
                });

                $('.delete_btn').click(function(e) {
                e.preventDefault();
                

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                $('#delete_id').val(stud_id);
                $('#deleteStudentModal').modal('show');
                });
                
                $('.edit_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                var classCode = $(this).closest('tr').find('.classCode').text();
                var company = $(this).closest('tr').find('.company').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_People.php?adddata",
                data: {
                    'checking_editbtn': true,
                    'student_id': stud_id,
                    'classCode': classCode,
                     Ccompany: company,
                },
                success: function (response) {
                    $.each(response,function (key, value){

                    if(company === "")
                    {
                        $('#edit_insurance, #edit_Sinsurance, #edit_psychological, #edit_medical, #edit_status, #edit_DTR, #edit_HRS_Rendered').val(value['insurance']).prop('disabled', true);
                    }
                    else
                    {
                        $('#edit_insurance, #edit_Sinsurance, #edit_psychological, #edit_medical, #edit_status, #edit_DTR, #edit_HRS_Rendered').val(value['insurance']).prop('disabled', false);
                    }
                    
                    $('#edit_id').val(value['student_id']);
                    $('#edit_studentnum').val(value['studentnum']);
                    $('#edit_name').val(value['name']);
                    $('#edit_emailadd').val(value['email']);
                    $('#edit_contact').val(value['contact_number']);
                    $('#edit_company').val(value['company']);
                    $('#edit_insurance').val(value['insurance']);
                    $('#edit_Sinsurance').val(value['insurance']);
                    $('#edit_psychological').val(value['psychological']);
                    $('#edit_medical').val(value['medical']);
                    $('#edit_status').val(value['status']);
                    $('#edit_DTR').val(value['dtr_option']);
                    $('#edit_HRS_Rendered').val(value['HRS_rendered']);
                    
                    });
                    
                    $('#editStudentModal').modal('show');
                   
                }
                });

                });

                $('.view_btn').click(function(e) {
                e.preventDefault();

                var stud_id = $(this).closest('tr').find('.stud_id').text();
                var classCode = $(this).closest('tr').find('.classCode').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_People.php?adddata",
                data: {
                    'checking_viewbtn': true,
                    'student_id': stud_id,
                    'classCode': classCode,
                },
                success: function (response) {
                    console.log(response);
                    $('.student_view').html(response);
                    $('#viewStudentModal').modal('show');
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
                ['All', 10, 15, 20, 30, 50],
                ],
            });
            });
    </script>
        

        </div><!--end of main -->

    


    
</body>
</html>
<?php
}
?>