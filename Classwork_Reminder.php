<?php
include 'db_conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'db_conn.php';

date_default_timezone_set('Asia/Singapore');
$date_today = date("Y-m-d H:i:s");

//Email the user when due date is tomorrow
$adv_ass = mysqli_query($conn, "SELECT * FROM adviser_assignment");
if(mysqli_num_rows($adv_ass) > 0)
{
    
    while($row_ass = mysqli_fetch_array($adv_ass))
    {
        $ass_id = $row_ass['assignment_id'];
        $duedate = $row_ass['due_date'];
        $title = $row_ass['title'];
        $class_code = $row_ass['class_code'];
        $class = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$class_code'");
        $get_class = mysqli_fetch_array($class);
        $year_section = $get_class['year_section'];
        $subject_name = $get_class['subject_name'];
        $students = $get_class['student_list'];
        $students = str_replace(',', ' ', $students);
        $get_email = mysqli_query($conn, "SELECT * FROM student_record WHERE class_code = '$class_code'");
        $row_email = mysqli_fetch_array($get_email);
        $stud_email = $row_email['email'];
        $stud_ass = mysqli_query($conn, "SELECT * FROM student_assignment WHERE studentnum = '$students' AND assignment_id = '$ass_id'");
        if(mysqli_num_rows($stud_ass) === 0)
        {
            $start_date = new DateTime($date_today);
            $end_date = new DateTime($duedate);
            $interval = $start_date->diff($end_date);

            if ($interval->d == 1) 
            {
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                            //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'sg2plzcpnl493881.prod.sin2.secureserver.net';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'pupwims_mailer@pup-wims.site';
                    $mail->Password   = 'pupwims123!';
                    $mail->SMTPSecure = 'ssl';     
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
                    //Recipients
                    $mail->setFrom('pupwims_mailer@pup-wims.site', 'PUP WIMS Adviser');
                    $mail->addAddress($stud_email);     //Add a recipient           
        
        
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'PUPSJ Internship Classwork';

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
                                                <br><p>&emsp;Hello and good day <b>$name</b>!<br><br>
                                                &emsp;&emsp;  Your work $title in $year_section - $subject_name is due tomorrow. Kindly submit your output before the said deadline.<br></p>
                                                <hr style='background-color:maroon;border-width:2px;'>
                                                <i style='font-size:15px; font-weight: 0.5px;'>&emsp;Sincerely, <br>
                                                &emsp;Polytechnic University of the Philippines, Internship</i><br></td>
                                            </tr>
                                        </tbody>
                                    </table>
           
            
                                    ";
        
                    $mail->send();
                   
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } 
        
        }

    }

}
?>