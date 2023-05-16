 <?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['coordinator']))
    {
        header("Location: Coordinator_Login.php?LoginFirst");
    }

date_default_timezone_set('Asia/Manila');
$date_today = date('Y-m-d');

$coordnum = $_SESSION['coordinator'];
$name = $_SESSION['name'];

if(isset($_REQUEST['adddata']))
{
    if(isset($_POST["upload"]))
    {
        if($_FILES['product_file']['name'])
         {
            $filename = explode(".", $_FILES['product_file']['name']);
             if(end($filename) == "csv")
                {
                $handle = fopen($_FILES['product_file']['tmp_name'], "r");
                while($data = fgetcsv($handle))
                {
                    
                    $company_name = mysqli_real_escape_string($conn, $data[0]);
                    $nature = mysqli_real_escape_string($conn, $data[1]);
                   
                    $contactperson = mysqli_real_escape_string($conn, $data[2]);
                    $position = mysqli_real_escape_string($conn, $data[3]);
                    $contactnum =  mysqli_real_escape_string($conn, $data[4]);
                    $email = mysqli_real_escape_string($conn, $data[5]);
                    $address = mysqli_real_escape_string($conn, $data[6]);
                    $moastat = mysqli_real_escape_string($conn, $data[7]);  
                    
                    $course = mysqli_real_escape_string($conn, $data[8]);
                    $branches = mysqli_real_escape_string($conn, $data[9]);
                    $date_signed = mysqli_real_escape_string($conn, $data[10]);
                    $date_end = mysqli_real_escape_string($conn, $data[11]);
                    $moasign = mysqli_real_escape_string($conn, $data[12]);
                    $dtiper = mysqli_real_escape_string($conn, $data[13]);
                    $secper = mysqli_real_escape_string($conn, $data[14]);
                    $birper = mysqli_real_escape_string($conn, $data[15]);

                    $datesigned = date('Y-m-d', strtotime($date_signed));
                    $dateend = date('Y-m-d', strtotime($date_end));
                    
                    $query = "INSERT INTO company_list
                    SET 
                    company_name ='$company_name', 
                    nature_business ='$nature',
                    
                    contact_person ='$contactperson',
                    position ='$position',
                    contact_num = '$contactnum', 
                    email = '$email', 
                    address = '$address',
                    moa_status ='$moastat',
                    company_status = 'Active',
                    course = '$course',
                    branches = '$branches',
                    date_signed = '$datesigned',
                    date_end = '$dateend',
                    MOA_sign = '$moasign',
                    dti_permit = '$dtiper',
                    sec_permit = '$secper',
                    bir_permit = '$birper',
                    years_active = '$yearsactive'";

                    mysqli_query($conn, $query);
                    $added_id = mysqli_insert_id($conn);

                    $sqlyrs = mysqli_query($conn, "SELECT years_active FROM company_list WHERE company_id = '$added_id'");
                    $yrs = mysqli_fetch_array($sqlyrs);   
                }
            fclose($handle);
            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&Home&CSVsucess");
            }
            else
            {
            $_SESSION['message'] = '<label class="text-danger">Please Select CSV File only</label>';
            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&Home&CSVunsucess1");
            }
            }
            else
            {
            $_SESSION['message'] = '<label class="text-danger">Please Select File</label>';
            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&Home&CSVunsucess2");
            }

            if(isset($_GET["updation"]))
            {
            $_SESSION['message'] = '<label class="text-success">Company List Upload Done</label>';
            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&Home&CSVunsucess3");
            }

    }

    if(isset($_POST['save_company']))
    {
        $compname = $_POST['compname'];
        $nbusiness = $_POST['nbusiness'];
        $contactper = $_POST['contactper'];
        $position = $_POST['position'];
        $contactnum = $_POST['contactnum'];
        $emailadd = $_POST['emailadd'];
        $address = $_POST['address'];
        $moastatus = $_POST['moastatus'];
        $course_string = $_POST['course'];
        $course = implode("," , $course_string);
        $branches = $_POST['branches'];
        $datesigned = date('Y-m-d', strtotime($_POST['datesigned']));
        $date_end = date('Y-m-d', strtotime($_POST['date_end']));

        //computation to get years active
        date_default_timezone_set('Asia/Manila'); //date today
        $dt = date('Y'); //year today
        $dt1 = date('Y', strtotime($datesigned)); //year selected in add partner company
        $yrsactive =  $dt - $dt1; // to get years active 
        
        $univsign = $_POST['univsign'];
        $dtipermit = $_POST['dtipermit'];
        $secpermit = $_POST['secpermit'];
        $birpermit = $_POST['birpermit'];

        $date_today = date('Y-m-d');

        $start_date = new DateTime($date_today); //Time of post
        $end_date = new DateTime($date_end); //Current time
        $interval = $start_date->diff($end_date); //Difference between dates 

        
            

        
       

        $query = " INSERT INTO company_list (company_name, nature_business, contact_person, position, contact_num, email, address, moa_status, course, branches, date_signed, date_end, MOA_sign, dti_permit, sec_permit, bir_permit, years_active, company_status) 
                    VALUES ('$compname', '$nbusiness', '$contactper', '$position', '$contactnum', '$emailadd', '$address', '$moastatus',  '$course', '$branches', '$datesigned', '$date_end', '$univsign', '$dtipermit', '$secpermit', '$birpermit', '$yrsactive', 'Active') ";
        $query_run = mysqli_query($conn, $query);
        $added_id = mysqli_insert_id($conn);

        $sqlyrs = mysqli_query($conn, "SELECT years_active FROM company_list WHERE company_id = '$added_id'");
        $yrs = mysqli_fetch_array($sqlyrs);   
        
        if($interval->y == 0 && $interval->m <=5 && $interval->m >= 0)
        {
            $query1 = "UPDATE company_list SET company_status = 'Expiring'  WHERE company_id = '$added_id'";
            $query_run = mysqli_query($conn, $query1);
        }
        
   

        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];
            
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
            
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');
            
               if(in_array($fileActualExt, $allowed)){
                if ($fileError === 0){
                    if($fileSize < 1000000){
                        $fileNameNew = uniqid('', true).".".$fileActualExt;
                        $fileDestination = '../uploads/'.$fileName;
                        move_uploaded_file($fileTmpName,$fileDestination);
                        $sql = "INSERT INTO company_moa VALUES ('$added_id', '$compname', '$contactnum', '$fileName', '$fileNameNew', '$fileDestination')";
                        mysqli_query($conn, $sql);
                        echo "success";
                    }else {
                        echo "Your file is too big!!";
                    header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess2");
                    }
                }else {
                    echo "There was an error uploading your File!";
                    header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess3");
                }
    
               } else {
                echo "You cannot upload files of this type!";
                header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess4");
               }
    
               $file = $_FILES['fileDTI'];
               $fileName = $_FILES['fileDTI']['name'];
               $fileTmpName = $_FILES['fileDTI']['tmp_name'];
               $fileSize = $_FILES['fileDTI']['size'];
               $fileError = $_FILES['fileDTI']['error'];
               $fileType = $_FILES['fileDTI']['type'];
                   
               $fileExt = explode('.', $fileName);
               $fileActualExt = strtolower(end($fileExt));
                   
               $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                   
                      if(in_array($fileActualExt, $allowed)){
                       if ($fileError === 0){
                           if($fileSize < 1000000){
                               $fileNameNew = uniqid('', true).".".$fileActualExt;
                               $fileDestination = '../uploads/'.$fileName;
                               move_uploaded_file($fileTmpName,$fileDestination);
                               $sql = "INSERT INTO company_dti VALUES ('$added_id', '$compname', '$contactnum', '$fileName', '$fileNameNew', '$fileDestination')";
                               mysqli_query($conn, $sql);
                               echo "success";
                           }else {
                               echo "Your file is too big!!";
                           header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess2");
                           }
                       }else {
                           echo "There was an error uploading your File!";
                           header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess3");
                       }
       
                      } else {
                       echo "You cannot upload files of this type!";
                       header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess4");
                      }
    
                      $file = $_FILES['fileSEC'];
                      $fileName = $_FILES['fileSEC']['name'];
                      $fileTmpName = $_FILES['fileSEC']['tmp_name'];
                      $fileSize = $_FILES['fileSEC']['size'];
                      $fileError = $_FILES['fileSEC']['error'];
                      $fileType = $_FILES['fileSEC']['type'];
                          
                      $fileExt = explode('.', $fileName);
                      $fileActualExt = strtolower(end($fileExt));
                          
                      $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                          
                             if(in_array($fileActualExt, $allowed)){
                              if ($fileError === 0){
                                  if($fileSize < 1000000){
                                    $fileNameNew = uniqid('', true).".".$fileActualExt;
                                    $fileDestination = '../uploads/'.$fileName;
                                      move_uploaded_file($fileTmpName,$fileDestination);
                                      $sql = "INSERT INTO company_sec VALUES ('$added_id', '$compname', '$contactnum', '$fileName','$fileNameNew', '$fileDestination')";
                                      mysqli_query($conn, $sql);
                                      echo "success";
                                  }else {
                                      echo "Your file is too big!!";
                                  header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess2");
                                  }
                              }else {
                                  echo "There was an error uploading your File!";
                                  header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess3");
                              }
              
                             } else {
                              echo "You cannot upload files of this type!";
                              header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess4");
                             }
    
                             $file = $_FILES['fileBIR'];
                             $fileName = $_FILES['fileBIR']['name'];
                             $fileTmpName = $_FILES['fileBIR']['tmp_name'];
                             $fileSize = $_FILES['fileBIR']['size'];
                             $fileError = $_FILES['fileBIR']['error'];
                             $fileType = $_FILES['fileBIR']['type'];
                                 
                             $fileExt = explode('.', $fileName);
                             $fileActualExt = strtolower(end($fileExt));
                                 
                             $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                                 
                                    if(in_array($fileActualExt, $allowed)){
                                     if ($fileError === 0){
                                         if($fileSize < 1000000){
                                             $fileNameNew = uniqid('', true).".".$fileActualExt;
                                             $fileDestination = '../uploads/'.$fileName;
                                             move_uploaded_file($fileTmpName,$fileDestination);
                                             $sql = "INSERT INTO company_bir VALUES ('$added_id', '$compname', '$contactnum', '$fileName', '$fileNameNew', '$fileDestination')";
                                             mysqli_query($conn, $sql);
                                             echo "success";
                                         }else {
                                             echo "Your file is too big.";
                                         header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess2");
                                         }
                                     }else {
                                         echo "There was an error uploading your File!";
                                         header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess3");
                                     }
                     
                                    } else {
                                     echo "You cannot upload files of this type!";
                                     header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess4");
                                    }
    

        if ($query_run)
        {
            $_SESSION['status'] = "PARTNER COMPANY ENTRY HAS SUCESSFULLY SAVED!";
            $notif_desc = "has added new partner company in the list. [Coordinator]";
            $date_time_now = date("Y-m-d H:i:s");
            $notif_link = "#";
            $advquery = mysqli_query($conn, "SELECT * FROM adviser_info");
            if(mysqli_num_rows($advquery) > 0)
            {
                while($advrow = mysqli_fetch_array($advquery))
                {
                    $advname = $advrow['name'];
                    $getnotif = mysqli_query($conn, "INSERT INTO notification VALUES('', '$notif_desc', '$notif_link', '$coordname', '$advname', '', '$date_time_now','no')");
                }
            }
            header("Location: Coordinator_DashHome.php?Home&uploadsuccess");
            
        }

        else
        {
            $_SESSION['status'] = "PARTNER COMPANY ENTRY HAS NOT SUCESSFULLY SAVED!";
            header("Location: Coordinator_DashHome.php?Home");
            
        }
	
    }

    
    

    if (isset($_POST['checking_viewbtn']))
    {
        $c_id = $_POST['company_id'];

        $return = "";
        
        $query = "SELECT * FROM company_list WHERE company_id = '$c_id'";
        $query_run = mysqli_query($conn, $query);    
        
        if(mysqli_num_rows($query_run) > 0)
        {
            foreach($query_run as $row)
            {   
                $date_signed = date("M j, Y", strtotime($row['date_signed']));
                $date_end = date("M j, Y", strtotime($row['date_end']));
            
                $return .= "
                <div class = 'form-row'>
                    <div class='form-group col-md-6'>
                        &emsp;<label for = ''><b>Company Name: </b></label>
                            <input type='text' class = 'form-control' placeholder = '{$row['company_name']}' disabled>
                    </div>
                
                        <div class='form-group col-md-6'>
                        &emsp;<label for = ''><b>Nature of Business: </b></label>
                            <input type='text' class = 'form-control' placeholder = '{$row['nature_business']}' disabled>
                        </div>
                        
                    </div>
                    <div class = 'form-row'>
                        <div class='form-group col-md-6'>
                        &emsp;<label for = ''><b>Contact Person: </b></label>
                            <input type='text' class = 'form-control' placeholder = '{$row['contact_person']}' disabled>
                        </div>
                        
                    
                        <div class='form-group col-md-6'>
                        &emsp;<label for = ''><b>Position: </b></label>
                            <input type='text' class = 'form-control' placeholder = '{$row['position']}' disabled>
                        </div>
                        </div>
                        <div class = 'form-row'>
                        <div class='form-group col-md-6'>
                        &emsp;<label for = ''><b>Contact Number: </b></label>
                            <input type='text' class = 'form-control' placeholder = '{$row['contact_num']}' disabled>
                        </div>
                        
                    
                        <div class='form-group col-md-6'>
                    &emsp;<label for = ''><b>Email Address: </b></label>
                        <input type='text' class = 'form-control' placeholder = '{$row['email']}' disabled>
                </div>
                </div>
                <div class='form-group'>
                    &emsp;<label for = ''><b>Company Address: </b></label>
                        <input type='text' class = 'form-control' placeholder = '{$row['address']}' disabled>
                </div>

                <div class = 'form-row'>
                        <div class='form-group col-md-6'>
                        &emsp;<label for = ''><b>MOA Status: </b></label>
                            <input type='text' class = 'form-control' placeholder = '{$row['moa_status']}' disabled>
                        </div>
                        <div class='form-group col-md-6'>
                        &emsp;<label for = ''><b>Company Status: </b></label>
                            <input type='text' class = 'form-control' placeholder = '{$row['company_status']}' disabled>
                        </div>
                    </div>";
                        if($row['company_status'] === 'Inactive')
                        {
                            $return .= "
                            <div class = 'form-row'>
                                <div class='form-group col-md-6'>
                                &emsp;<label for = ''><b>Reason of being Inactive: </b></label>
                                    <input type='text' class = 'form-control' placeholder = '{$row['report']}' disabled>
                                </div>
                                <div class='form-group col-md-6'>
                                &emsp;<label for = ''><b>Further Explanation: </b></label>
                                    <input type='text' class = 'form-control' placeholder = '{$row['reason']}' disabled>
                                </div>
                            </div>
                            ";
                        }
                        $return .= "
                    
                    <div class = 'form-row'>
                    <div class='form-group col-md-6'>
                    &emsp;<label for = ''><b>Course: </b></label>
                        <input type='text' class = 'form-control' title='{$row['course']}' placeholder = '{$row['course']}' disabled>
                    </div>
                    <div class='form-group col-md-6'>
                    &emsp;<label for = ''><b>Branch/Campus/College: </b></label>
                        <input type='text' class = 'form-control' placeholder = '{$row['branches']}' disabled>
                    </div>
                </div>
                <div class='form-row'>
                <div class='form-group col-md-6'>
                    &emsp;<label for = ''><b>Date Signed: </b></label>
                        <input type='text' class = 'form-control' placeholder = '$date_signed' disabled>
                        </div>
                <div class='form-group col-md-6'>
                    &emsp;<label for = ''><b>MOA Expiration Date: </b></label>
                        <input type='text' class = 'form-control' placeholder = '$date_end' disabled>
                        </div>
                </div>
                ";
                
                $company_moa = mysqli_query($conn, "SELECT * FROM company_moa WHERE company_id = '$c_id'");
                if(mysqli_num_rows($company_moa) > 0)
                {
                    foreach($company_moa as $moarow)
                    {
                        $fileDestinationMOA = $moarow['filedestinationMOA'];
                        $fileNameMOA = $moarow['filenameMOA'];
                        
                        $MOAfile = "<a target='_blank' href = '../uploads/$fileNameMOA'>$fileNameMOA</a>";
                        $return .= "
                        <div class = 'form-row'>
                            <div class='form-group col-md-6'>
                                &emsp;<label for = ''><b>Partner Company has MOA Permit: </b></label>
                                <div class = 'form-control' style='background:#e9ecef;'><div style=' display: block;
                                width: 330px;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                white-space: nowrap;'>$MOAfile</div></div>
                            </div>
                        
                            
                        ";
                    }
                
                    
                }
                else
                {
                    $return .="
                    <div class = 'form-row'>
                            <div class='form-group col-md-6'>
                                &emsp;<label for = ''><b>Partner Company has MOA Permit: </b></label>
                                <div class = 'form-control' style='background:#e9ecef;'>None</div>
                            </div>
                    ";
                }


                $company_dti = mysqli_query($conn, "SELECT * FROM company_dti WHERE company_id = '$c_id'");
                if(mysqli_num_rows($company_dti)> 0)
                {
                    foreach($company_dti as $dtirow)
                    {
                        $fileDestinationDTI = $dtirow['filedestinationDTI'];
                        $fileNameDTI = $dtirow['filenameDTI'];
                        $DTIfile = "<a target='_blank' href = '../uploads/$fileNameDTI'>$fileNameDTI</a>";
                        $return .= "
                        
                            <div class='form-group col-md-6'>
                                &emsp;<label for = ''><b>Partner Company has DTI Permit: </b></label>
                                <div class = 'form-control' style='background:#e9ecef;'><div style=' display: block;
                                width: 330px;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                white-space: nowrap;'>$DTIfile</div></div>
                            </div>
                        </div>
                        ";
                    }
                
                }
                else
                {
                    $return .="
                    <div class='form-group col-md-6'>
                                &emsp;<label for = ''><b>Partner Company has DTI Permit: </b></label>
                                <div class = 'form-control' style='background:#e9ecef;'>None</div>
                            </div>
                        </div>
                    ";
                }



                $company_sec = mysqli_query($conn, "SELECT * FROM company_sec WHERE company_id = '$c_id'");
                if(mysqli_num_rows($company_sec) > 0)
                {
                    foreach($company_sec as $secrow)
                    { 
                        $fileDestinationSEC = $secrow['filedestinationSEC'];
                        $fileNameSEC = $secrow['filenameSEC'];
                        $SECfile = "<a target='_blank' href = '../uploads/$fileNameSEC'>$fileNameSEC</a>";
                        $return .="
                        <div class = 'form-row'>
                            <div class='form-group col-md-6'>
                                &emsp;<label for = ''><b>Partner Company has SEC Permit: </b></label>
                                <div class = 'form-control' style='background:#e9ecef;'><div style=' display: block;
                                width: 330px;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                white-space: nowrap;'>$SECfile</div></div>
                            </div>
                        ";
                    }
                   
                }
                else
                {
                    $return .="
                    <div class = 'form-row'>
                    <div class='form-group col-md-6'>
                        &emsp;<label for = ''><b>Partner Company has SEC Permit: </b></label>
                        <div class = 'form-control' style='background:#e9ecef;'>None</div>
                    </div>
                    ";
                }


                $company_bir = mysqli_query($conn, "SELECT * FROM company_bir WHERE company_id = '$c_id'");
                if(mysqli_num_rows($company_bir) > 0)
                {
                    foreach($company_bir as $birrow)
                    {
                        $fileDestinationBIR = $birrow['filedestinationBIR'];
                        $fileNameBIR = $birrow['filenameBIR'];
                        $BIRfile = "<a target='_blank' href = '../uploads/$fileNameBIR'>$fileNameBIR</a>";
                        $return .= "
                        <div class='form-group col-md-6'>
                            &emsp;<label for = ''><b>Partner Company has BIR Permit: </b></label>
                            <div class = 'form-control' style='background:#e9ecef;'><div style=' display: block;
                            width: 330px;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            white-space: nowrap;'>$BIRfile</div></div>
                        </div>
                    </div>
                        ";
                    }
                    
                }
                else
                {
                    $return .="
                    <div class='form-group col-md-6'>
                            &emsp;<label for = ''><b>Partner Company has BIR Permit: </b></label>
                            <div class = 'form-control' style='background:#e9ecef;'>None</div>
                        </div>
                    </div>
                    ";
                }
                
            }
            
        }

       
       echo $return;

        
    }


    if (isset($_POST['checking_editbtn']))
    {
        $c_id = $_POST['company_id'];
        $result_array = [];
        
        $query = "SELECT * FROM company_list WHERE company_id = '$c_id'";
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
        echo $return = "NO RECORD FOUND!";
        }
    }

    if(isset($_POST['update_company']))
    {
        $c_id = $_POST['edit_id'];
        $compname = $_POST['compname'];
        $nbusiness = $_POST['nbusiness'];
        $contactper = $_POST['contactper'];
        $position = $_POST['position'];
        $contactnum = $_POST['contactnum'];
        $emailadd = $_POST['emailadd'];
        $address = $_POST['address'];
        $moastatus = $_POST['moastatus'];
        $course_string = $_POST['course'];
        if(!empty($course_string)){
            $course = implode("," , $course_string);
        }
        else
        {
            $course = "Not Applicable";
        }
        
        $company_status = $_POST['comp_status'];
        
        $branches = $_POST['branches'];
        $datesigned = date('Y-m-d', strtotime($_POST['datesigned']));
        $dateend = date('Y-m-d', strtotime($_POST['date_end']));
        date_default_timezone_set('Asia/Manila'); //date today
        $dt = date('Y'); //year today
        $dt1 = date('Y', strtotime($datesigned));
        $yrsactive =  $dt - $dt1;
        $MOAsign = $_POST['moasign'];
        $dtipermit = $_POST['dtipermit'];
        $secpermit = $_POST['secpermit'];
        $birpermit = $_POST['birpermit'];
        $date_today = date('Y-m-d');
        if($_POST['report'] !== "Others")
        {
            $report = $_POST['report'];
        }
        else if($_POST['report'] === 'Others')
        {
            $report = $_POST['report_s'];
        }

        $reason = $_POST['reason'];
        
      
        
        

        $start_date = new DateTime($date_today); //Time of post
        $end_date = new DateTime($dateend); //Current time
        $interval = $start_date->diff($end_date); //Difference between dates 

        $query = " UPDATE company_list SET company_name = '$compname', nature_business = '$nbusiness', contact_person = '$contactper', position = '$position', contact_num = '$contactnum', email = '$emailadd', address = '$address', moa_status = '$moastatus', course = '$course', branches = '$branches', date_signed = '$datesigned', date_end = '$dateend', MOA_sign = '$MOAsign', dti_permit = '$dtipermit', sec_permit = '$secpermit', bir_permit = '$birpermit', years_active = '$yrsactive', company_status = '$company_status', report = '$report', reason = '$reason' WHERE company_id = '$c_id' ";
        $query_run = mysqli_query($conn, $query);

        $sqlyrs = mysqli_query($conn, "SELECT years_active FROM company_list WHERE company_id = '$c_id'");
        $yrs = mysqli_fetch_array($sqlyrs);   

        
        // if($interval->m > 2)
        // {
        //     $query = "UPDATE company_list SET company_status = 'Active' WHERE company_id = '$comp_id'";
        //     $query_run = mysqli_query($conn, $query);
        // }
        // else if($date_today == $expiration_date || $date_today > $expiration_date)
        // {
        //     $query2 = "UPDATE company_list SET company_status = 'Defunct'  WHERE company_id = '$comp_id'";
        //     $query_run = mysqli_query($conn, $query2);
        // }
        // else if($interval->y == 0 && $interval->m <=2 && $interval->m >= 0)
        // {
        //     $query1 = "UPDATE company_list SET company_status = 'Inactive'  WHERE company_id = '$comp_id'";
        //     $query_run = mysqli_query($conn, $query1);
        // }
        

    //    if($yrs['years_active'] <= '3'){
    //        $query = "UPDATE company_list SET company_status = 'Active' WHERE company_id = '$c_id'";
    //        $query_run = mysqli_query($conn, $query);
    //    }

       
    //    else if($yrs['years_active'] === '4'){
    //        $query1 = "UPDATE company_list SET company_status = 'Inactive'  WHERE company_id = '$c_id'";
    //        $query_run = mysqli_query($conn, $query1);
    //    }
       
       
    //    else if($yrs['years_active'] >= '5'){
    //        $query2 = "UPDATE company_list SET company_status = 'Defunct'  WHERE company_id = '$c_id'";
    //        $query_run = mysqli_query($conn, $query2);
    //    }
   
        if($MOAsign === "No")
        {
            $sql = mysqli_query($conn, "DELETE FROM company_moa WHERE company_id = '$c_id'");
        }
        else if($MOAsign === "Yes")
        {
            $query = mysqli_query($conn, "SELECT * FROM company_moa WHERE company_id = '$c_id'");
            if(mysqli_num_rows($query) === 1)
            {
                $fileMOA = $_FILES['file'];
                $fileNameMOA = $_FILES['file']['name'];
                $fileTmpNameMOA = $_FILES['file']['tmp_name'];
                $fileSizeMOA = $_FILES['file']['size'];
                $fileErrorMOA = $_FILES['file']['error'];
                $fileTypeMOA = $_FILES['file']['type'];
                $fileExt = explode('.', $fileNameMOA);
                $fileActualExt = strtolower(end($fileExt));
                
                
                    
                $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                    
                if(in_array($fileActualExt, $allowed)){
                    if ($fileErrorMOA === 0){
                        if($fileSizeMOA < 1000000){
                            $fileNameNewMOA = uniqid('', true).".".$fileActualExt;
                            $fileDestinationMOA = '../uploads/'.$fileNameMOA;
                            move_uploaded_file($fileTmpNameMOA,$fileDestinationMOA);
                           
                            $sql = "UPDATE company_moa SET company_name = '$compname', contact_num = '$contactnum', filenameMOA = '$fileNameMOA',filenamenewMOA = '$fileNameNewMOA', filedestinationMOA = '$fileDestinationMOA' WHERE company_id = '$c_id'";
                            mysqli_query($conn, $sql);
                            
                            
                        }else {
                            // File size
                            header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess2");
                        }
                    }else {
                        //Something went wrong
                        header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess3");
                    }
    
                    } else {
                    //Not Allowed
                    header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess4");
                    }
            }
            else if(mysqli_num_rows($query) === 0)
            {
                $file = $_FILES['file'];
                $fileName = $_FILES['file']['name'];
                $fileTmpName = $_FILES['file']['tmp_name'];
                $fileSize = $_FILES['file']['size'];
                $fileError = $_FILES['file']['error'];
                $fileType = $_FILES['file']['type'];
                    
                $fileExt = explode('.', $fileName);
                $fileActualExt = strtolower(end($fileExt));
                    
                $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                    
                       if(in_array($fileActualExt, $allowed)){
                        if ($fileError === 0){
                            if($fileSize < 1000000){
                                $fileNameNew = uniqid('', true).".".$fileActualExt;
                                $fileDestination = '../uploads/'.$fileName;
                                move_uploaded_file($fileTmpName,$fileDestination);
                                $sql = "INSERT INTO company_moa VALUES ('$c_id', '$compname', '$contactnum', '$fileName', '$fileNameNew', '$fileDestination')";
                                mysqli_query($conn, $sql);
                                echo "success";
                            }else {
                                echo "Your file is too big!!";
                            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess2");
                            }
                        }else {
                            echo "There was an error uploading your File!";
                            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess3");
                        }
            
                       } else {
                        echo "You cannot upload files of this type!";
                        header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess4");
                }
            }
           
        }
        
        if($dtipermit === "No")
        {
            $sql = mysqli_query($conn, "DELETE FROM company_dti WHERE company_id = '$c_id'");
        }
        else if($dtipermit === "Yes")
        {
            $query = mysqli_query($conn, "SELECT * FROM company_dti WHERE company_id = '$c_id'");
            if(mysqli_num_rows($query) === 1)
            {
                $fileDTI = $_FILES['fileDTI'];
                $fileNameDTI = $_FILES['fileDTI']['name'];
                $fileTmpNameDTI = $_FILES['fileDTI']['tmp_name'];
                $fileSizeDTI = $_FILES['fileDTI']['size'];
                $fileErrorDTI = $_FILES['fileDTI']['error'];
                $fileTypeDTI = $_FILES['fileDTI']['type'];
                    
                $fileExt = explode('.', $fileNameDTI);
                $fileActualExt = strtolower(end($fileExt));
                    
                $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                    
                    if(in_array($fileActualExt, $allowed)){
                        if ($fileErrorDTI === 0){
                            if($fileSizeDTI < 1000000){
                                $fileNameNewDTI = uniqid('', true).".".$fileActualExt;
                                $fileDestinationDTI = '../uploads/'.$fileNameDTI;
                                move_uploaded_file($fileTmpNameDTI,$fileDestinationDTI);
                                $sql = "UPDATE company_dti SET company_name = '$compname', contact_num = '$contactnum', filenameDTI = '$fileNameDTI', filenamenewDTI = '$fileNameNewDTI', filedestinationDTI = '$fileDestinationDTI' WHERE company_id = '$c_id'";
                                mysqli_query($conn, $sql);
                                echo "success";
                            }else {
                            
                                header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess2");
                            }
                        }else {
                        
                            header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess3");
                        }
        
                    } else {
                    
                        header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess4");
                    }
                }
                else if(mysqli_num_rows($query) === 0)
                {
                    $file = $_FILES['fileDTI'];
                    $fileName = $_FILES['fileDTI']['name'];
                    $fileTmpName = $_FILES['fileDTI']['tmp_name'];
                    $fileSize = $_FILES['fileDTI']['size'];
                    $fileError = $_FILES['fileDTI']['error'];
                    $fileType = $_FILES['fileDTI']['type'];
                        
                    $fileExt = explode('.', $fileName);
                    $fileActualExt = strtolower(end($fileExt));
                        
                    $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                        
                            if(in_array($fileActualExt, $allowed)){
                            if ($fileError === 0){
                                if($fileSize < 1000000){
                                    $fileNameNew = uniqid('', true).".".$fileActualExt;
                                    $fileDestination = '../uploads/'.$fileName;
                                    move_uploaded_file($fileTmpName,$fileDestination);
                                    $sql = "INSERT INTO company_dti VALUES ('$c_id', '$compname', '$contactnum', '$fileName', '$fileNameNew', '$fileDestination')";
                                    mysqli_query($conn, $sql);
                                    echo "success";
                                }else {
                                    echo "Your file is too big!!";
                                header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess2");
                                }
                            }else {
                                echo "There was an error uploading your File!";
                                header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess3");
                            }
            
                            } else {
                            echo "You cannot upload files of this type!";
                            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess4");
                            }
    
                }
        }

        if($secpermit === "No")
        {
            $sql = mysqli_query($conn, "DELETE FROM company_sec WHERE company_id = '$c_id'");
        }
        else if($secpermit === "Yes")
        {
            $query = mysqli_query($conn, "SELECT * FROM company_sec WHERE company_id = '$c_id'");
            if(mysqli_num_rows($query) === 1)
            {

                   $file = $_FILES['fileSEC'];
                   $fileName = $_FILES['fileSEC']['name'];
                   $fileTmpName = $_FILES['fileSEC']['tmp_name'];
                   $fileSize = $_FILES['fileSEC']['size'];
                   $fileError = $_FILES['fileSEC']['error'];
                   $fileType = $_FILES['fileSEC']['type'];
                       
                   $fileExt = explode('.', $fileName);
                   $fileActualExt = strtolower(end($fileExt));
                       
                   $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                       
                          if(in_array($fileActualExt, $allowed)){
                           if ($fileError === 0){
                               if($fileSize < 1000000){
                                 $fileNameNew = uniqid('', true).".".$fileActualExt;
                                 $fileDestination = '../uploads/'.$fileName;
                                   move_uploaded_file($fileTmpName,$fileDestination);
                                   $sql = "UPDATE company_sec SET company_name = '$compname', contact_num = '$contactnum', filenameSEC = '$fileName', filenamenewSEC = '$fileNameNew', filedestinationSEC = '$fileDestination' WHERE company_id = '$c_id'";
                                   mysqli_query($conn, $sql);
                                   echo "success";
                               }else {
                               
                                header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess2");
                               }
                           }else {
                           
                            header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess3");
                           }
           
                          } else {
                          
                            header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess4");
                          }
                }   
                else if(mysqli_num_rows($query) === 0)
            {
                $file = $_FILES['fileSEC'];
                $fileName = $_FILES['fileSEC']['name'];
                $fileTmpName = $_FILES['fileSEC']['tmp_name'];
                $fileSize = $_FILES['fileSEC']['size'];
                $fileError = $_FILES['fileSEC']['error'];
                $fileType = $_FILES['fileSEC']['type'];
                    
                $fileExt = explode('.', $fileName);
                $fileActualExt = strtolower(end($fileExt));
                    
                $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                    
                       if(in_array($fileActualExt, $allowed)){
                        if ($fileError === 0){
                            if($fileSize < 1000000){
                              $fileNameNew = uniqid('', true).".".$fileActualExt;
                              $fileDestination = '../uploads/'.$fileName;
                                move_uploaded_file($fileTmpName,$fileDestination);
                                $sql = "INSERT INTO company_sec VALUES ('$c_id', '$compname', '$contactnum', '$fileName','$fileNameNew', '$fileDestination')";
                                mysqli_query($conn, $sql);
                                echo "success";
                            }else {
                                echo "Your file is too big!!";
                            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess2");
                            }
                        }else {
                            echo "There was an error uploading your File!";
                            header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess3");
                        }
        
                       } else {
                        echo "You cannot upload files of this type!";
                        header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess4");
                       }
            }

        }

            if($bripermit === "No")
            {
                $sql = mysqli_query($conn, "DELETE FROM company_bir WHERE company_id = '$c_id'");
            }
            else if($birpermit === "Yes")
            {
                $query = mysqli_query($conn, "SELECT * FROM company_bir WHERE company_id = '$c_id'");
                if(mysqli_num_rows($query) === 1)
                {
                          $file = $_FILES['fileBIR'];
                             $fileName = $_FILES['fileBIR']['name'];
                             $fileTmpName = $_FILES['fileBIR']['tmp_name'];
                             $fileSize = $_FILES['fileBIR']['size'];
                             $fileError = $_FILES['fileBIR']['error'];
                             $fileType = $_FILES['fileBIR']['type'];
                                 
                             $fileExt = explode('.', $fileName);
                             $fileActualExt = strtolower(end($fileExt));
                                 
                             $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                                 
                                    if(in_array($fileActualExt, $allowed)){
                                     if ($fileError === 0){
                                         if($fileSize < 1000000){
                                             $fileNameNew = uniqid('', true).".".$fileActualExt;
                                             $fileDestination = '../uploads/'.$fileName;
                                             move_uploaded_file($fileTmpName,$fileDestination);
                                             $sql = "UPDATE company_bir SET company_name = '$compname', contact_num = '$contactnum', filenameBIR = '$fileName', filenamenewBIR = '$fileNameNew', filedestinationBIR = '$fileDestination' WHERE company_id = '$c_id'";
                                             mysqli_query($conn, $sql);
                                             echo "success";
                                         }else {
                                            
                                            header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess2");
                                         }
                                     }else {
                                        
                                        header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess3");
                                     }
                     
                                    } else {
                                        
                                        header("Location: Coordinator_DashHome.php?Home&uploadnotsuccess4");
                                    }
                    }
                    else if(mysqli_num_rows($query) === 0)
                    {
                        $file = $_FILES['fileBIR'];
                        $fileName = $_FILES['fileBIR']['name'];
                        $fileTmpName = $_FILES['fileBIR']['tmp_name'];
                        $fileSize = $_FILES['fileBIR']['size'];
                        $fileError = $_FILES['fileBIR']['error'];
                        $fileType = $_FILES['fileBIR']['type'];
                            
                        $fileExt = explode('.', $fileName);
                        $fileActualExt = strtolower(end($fileExt));
                            
                        $allowed = array('jpg', 'jpeg', 'png', 'pdf');
                            
                               if(in_array($fileActualExt, $allowed)){
                                if ($fileError === 0){
                                    if($fileSize < 1000000){
                                        $fileNameNew = uniqid('', true).".".$fileActualExt;
                                        $fileDestination = '../uploads/'.$fileName;
                                        move_uploaded_file($fileTmpName,$fileDestination);
                                        $sql = "INSERT INTO company_bir VALUES ('$c_id', '$compname', '$contactnum', '$fileName', '$fileNameNew', '$fileDestination')";
                                        mysqli_query($conn, $sql);
                                        echo "success";
                                    }else {
                                        echo "Your file is too big.";
                                    header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess2");
                                    }
                                }else {
                                    echo "There was an error uploading your File!";
                                    header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess3");
                                }
                
                               } else {
                                echo "You cannot upload files of this type!";
                                header("Location: Coordinator_DashHome.php?coordinator=$coordnum&uploadnotsuccess4");
                               }
                    }
                }
 
 

        if ($query_run)
        {
           
            header("Location: Coordinator_DashHome.php?Home&editsuccess");
        }

        else
        {
           
            header("Location: Coordinator_DashHome.php?Home&editunsuccess");
        }
    }

    if(isset($_POST['delete_company']))
    {

        $id = $_POST['company_id'];

        $query = " UPDATE company_list SET remove = 'yes' WHERE company_id = '$id' ";
        $query_run = mysqli_query($conn, $query);	


        if ($query_run)
        {
            $_SESSION['status'] = "PARTNER COMPANY INFORMATION HAS SUCCESSFULLY DELETED!";
            header("Location: Coordinator_DashHome.php?Home&deletesuccess");
        }

        else
        {
            $_SESSION['status'] = "OH NO! SOMETHING WENT WRONG!";
            header("Location: Coordinator_DashHome.php?Home&deleteunsuccess");
        }
    }
}


if(isset($_REQUEST['Home']))
{

?>


<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Coordinator Dashboard - Partner Company List</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
   

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <link rel = "stylesheet" href = "../css/coordinator_dashdocu.css">

    <style>

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
        #company 
        {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        min-width: 100%;
        }

        #company td, #company th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #company tr:nth-child(even){background-color: #f2f2f2;}

        #company th 
        {
        padding-top: 12px;
        padding-bottom: 12px;
        background-color: maroon;
        color: white;
        text-align: center;
        white-space: nowrap;
        }

        #defunct_company 
        {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        min-width: 100%;
        }

        #defunct_company  td, #defunct_company  th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #defunct_company  tr:nth-child(even){background-color: #f2f2f2;}

        #defunct_company  th 
        {
        padding-top: 12px;
        padding-bottom: 12px;
        background-color: maroon;
        color: white;
        text-align: center;
        white-space: nowrap;
        }

        
        #table 
        {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        border-collapse: collapse;
        /*width: 100%;*/
        margin-right: 20px;
        margin-left: 20px;
        margin-bottom: 20px;
        }

        #table td
        {
        text-align: center;
        border: 1px solid #ddd;
        /* padding: 8px; */
        }

        #table tr:nth-of-type(even)
        {
        text-align: center;
        border: 1px solid #ddd;
        background-color: #EEEEEE;
        /* padding: 8px; */
        }

        /* #company tr:nth-child(even){background-color: #f2f2f2;} */

        #table th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        color: black;
        text-align: center;
        }

        body 
        {
        font-family: Arial, Helvetica, sans-serif;
        }

        hr.solid 
        {
        border-top: 3px solid #bbb;
        }

        .display_screen 
        {
            display:block;
        }

        .print-only 
        {
            display:none;
        }

        @media print{
            #PrintButton{
                display: none;
            }
            .display_screen 
            {
                display: none;
            }
           

            @page{
                size: landscape;
            }
             .print-only 
            {
                display:block;
            }
            .code{
                color: red;
            }
        }
    </style>
    <script>
		function printPage() {
			// Show the elements with class "print-only"
			var printElements = document.getElementsByClassName('print-only');
			for (var i = 0; i < printElements.length; i++) {
				printElements[i].style.display = 'block';
			}

			// Print the page
			window.print();

			// Hide the elements with class "print-only" again
			for (var i = 0; i < printElements.length; i++) {
				printElements[i].style.display = 'none';
			}
		}
	</script>
</head>

<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

<div class = "display_screen">
        <!--SIDE BAR-->
        <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
            <center>
                <!--Background Side Bar-->

                <br><br>
                <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>

                <h6 style="color: white;"> Coordinator </h6>
            <br><br><hr style="background-color:white;width:200px;">
            <a href="Coordinator_Dashboard.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp;Dashboard</a>
            <a href="Coordinator_DashHome.php?Home" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-building" style="font-size: 1.3em;"></i>&emsp;Partner Company List </a>
            <a href="Coordinator_Record_Adviser.php?coordnum=<?php echo $coordnum ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-users" style="font-size: 1.3em;"></i>&emsp;Record </a>
            <a href="Coordinator_Classwork.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments-o" style="font-size: 1.3em;"></i>&emsp;Announcement </a>
            <a href="Coordinator_Document.php?Home" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-file-text" style="font-size: 1.3em;"></i>&emsp;Document </a>
            <a href="Coordinator_Graph.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp;Company Evaluation</a>
            
            </center>
        </div>

        
      <?php
        
        if(isset($_REQUEST['CSVsucess']))
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
                title: 'CSV file successfully added'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['CSVunsucess1']))
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
        if(isset($_REQUEST['CSVunsucess2']))
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
        if(isset($_REQUEST['CSVunsucess3']))
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
                title: 'Company List Upload Done'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['uploadnotsuccess2']))
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
                title: 'Your file is too big.'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['uploadnotsuccess3']))
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
                title: 'There was an error uploading your File.'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['uploadnotsuccess4']))
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
                title: 'You cannot upload files of this type.'
            })
            </script>
      <?php
        }
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
                title: 'Partner Company List Added Successfully.'
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
                title: 'Partner Company List Updated Successfully.'
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
                title: 'Something went wrong.'
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
                title: 'Partner Company List Deleted Successfully.'
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
                title: 'Something went wrong.'
            })
            </script>
      <?php
        }
        if(isset($_REQUEST['CSVsucess']))
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
                title: 'CSV file successfully added'
            })
            </script>
      <?php
        }
        ?>

                    


        <div class = "main">
            <div class = "topnavbar" style="z-index: 1;">
              
            <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
                <div class="topnavbar-right" id="topnav_right">
                    <a target = "_self" href="../Logout.php?Logout=<?php echo $coordnum ?>" > 
                                Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                    </a>
                </div>
            </div> <!--end of topnavbar-->
        
            <br><br><br><br>

            <h1 class="font-weight-bold"  style = "font-size: 35px; color:maroon;">Partner Company List  </h1>
            <hr style="background-color:maroon;border-width:2px;">
            <button type="button" class="btn btn-primary" style="background-color:maroon;" onclick = "printPage()"><b><i class="fa fa-file-text-o"></i>&emsp;PRINT LIST</b> </button><br>
            <button type="button" class="btn btn-primary" style="background-color:maroon;" data-toggle="modal" data-target="#myModal"><b>+ ADD PARTNER COMPANY ENTRY</b> </button>
            <button type="button" class="btn btn-primary" style="background-color:maroon;" data-toggle="modal" data-target="#addComp"> <b>+ ADD PARTNER COMPANY LIST (CSV FILE)</b> </button>
            <button type="button" class="userinfo btn btn-danger" style="background-color:maroon;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;ADVANCED FILTER</b> </button>
            <br><br>
                 <script>
                        const tabletSize1 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
                        function handleScreenSizeChange(tabletSize1) {
                            if (tabletSize1.matches) 
                            {
                                document.getElementById('topnav_right').classList.remove('topnavbar-right');
                            } 
                            else
                            {
                                document.getElementById('topnav_right').classList.add('topnavbar-right');
                            }
                        }

                        tabletSize1.addListener(handleScreenSizeChange);
                        handleScreenSizeChange(tabletSize1);
                    </script>
            
            <!-- Modal Add partner company csv -->

        <div class="modal fade" id="addComp">
                            <div class="modal-dialog modal-lg" >
                                <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title"><b>UPLOAD CSV FILE</b></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <form action="Coordinator_DashHome.php?adddata" method="post" enctype='multipart/form-data'>
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4" style="text-align:center; margin-top: 10%;">
                                            
                                                
                                                <label for = ""><b>Place CSV Folder Here</b></label><br>
                                                <p>&emsp;&emsp;<input type="file" name="product_file" /></p>
                                            
                                            <br><br><br>
                                            <a class="btn btn-primary" style="background-color:maroon;" href="../csvfiles/sample_partner_companylist.csv" download><i class="fa fa-download" aria-hidden="true"></i> <b> &nbsp; Download Template </b></a>
                                        </div>
                                    
                                    <div class="form-group col-md-8" >
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
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name = "upload" class="btn btn-primary" style="background-color:maroon;">Upload</button>
                                    </div>
                                </form>
                </div>
            </div>
        </div>

       

         <!-- Modal FILTER DATA-->
         <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>ADVANCED FILTER</b></h5>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form action = "Coordinator_DashHome.php?Filter" method = "POST">
                        <div class = 'form-row'>
                            <div class='form-group col-md-6'>
                                <div class="md-title">
                                    <label for="exampleFormControlInput1" name="title" class="form-label"><b>Company Name</b> </label>
                                    <input type="text" name="compname" class="form-control" placeholder="Search Company Name">
                                </div>
                            </div>
                            <div class='form-group col-md-6'>
                                <div class="md-title">
                                    <label for="exampleFormControlInput1" name="title" class="form-label"><b> Contact Person</b></label>
                                    <input type="text" name="contact" class="form-control" placeholder="Search Contact Person">
                                </div>
                            </div>
                        </div>
                        <div class = 'form-row'>
                            <div class="form-group col-md-6">
                                <div class="md-title">
                                    <label for = ""><b>MOA Status</b></label>
                                    <select name = "MOAstatus" class = "form-control" />
                                    <option value = "">--Select MOA Status--</option>
                                    <option value = "MOA in Process">MOA in Process</option>
                                    <option value = "MOA Signed">MOA Signed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="md-title">
                                    <label for = ""><b>Company Status</b></label>
                                    <select name = "COMPstatus" class = "form-control" />
                                    <option value = "">--Select Company Status--</option>
                                    <option value = "Active">Active</option>
                                    <option value = "Expiring">Expiring(5 months before the expiry date)</option>
                                    <option value = "Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class = 'form-row'>
        
                            <div class='form-group col-md-6'>
                                <div class="md-title">
                                    <label for="exampleFormControlInput1" name="title" class="form-label"><b> Start Date</b></label>
                                    <input type="datetime-local" name="signed" class="form-control">
                                </div>
                            </div>
                            <div class='form-group col-md-6'>
                                <div class="md-title">
                                    <label for="exampleFormControlInput1" name="title" class="form-label"><b> Due Date</b></label>
                                    <input type="datetime-local" name="expired" class="form-control">
                                </div>
                            </div>
                        </div>
        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name = "filter" class="btn btn-danger">Filter</button>
                    </div>
                    </form>
                    </div>
                </div>
                </div>
                
                    <br>
            
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION SHEET</b></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
            <form action = "Coordinator_DashHome.php?adddata" method = "POST" enctype="multipart/form-data">
                <div class="modal-body">

                <div class="form-group">
                    <label for = ""><b>Company Name</b></label>
                    <input type="text" name = "compname" class = "form-control" placeholder = "Enter Company Name" required>
                </div>

                <div class = "form-row">
                <div class="form-group col-md-6">
                    <label for = ""><b>Nature of Business</b></label>
                    <select name = "nbusiness" class = "form-control" />
                    <option value = "">--Select Nature of Business--</option>
                    <option value = "Government Sector">Government Sector</option>
                    <option value = "International Sector">International Sector</option>
                    <option value = "Manufacturing Sector">Manufacturing Sector</option>
                    <option value = "Merchandising Sector">Merchandising Sector</option>
                    <option value = "Military Sector">Military Sector</option>
                    <option value = "Non Profit Sector">Non-Profit Sector</option>
                    <option value = "Not Applicable">Not Applicable</option>
                    <option value = "Others">Others</option>
                    <option value = "Private Sector">Private Sector</option>
                    <option value = "Public Sector">Public Sector</option>
                    <option value = "Service Sector">Service Sector</option>
                    <option value = "Technology Sector">Technology Sector</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for = ""><b>Contact Person</b></label>
                    <input type="text" name = "contactper" class = "form-control" placeholder = "Enter Contact Person" required>
                </div>

                <div class="form-group col-md-6">
                    <label for = ""><b>Position</b></label>
                    <input type="text" name = "position" class = "form-control" placeholder = "Enter Company Designation" required>
                </div>

                <div class="form-group col-md-6">
                    <label for = ""><b>Contact Number</b></label>
                    <input type="text" name = "contactnum" class = "form-control" placeholder = "Enter Contact Number" required>
                </div>
                </div>

                <div class="form-group">
                    <label for = ""><b>Email Address</b></label>
                    <input type="text" name = "emailadd" class = "form-control" placeholder = "Enter Email Address" required>
                </div>

                <div class="form-group">
                    <label for = ""><b>Company Address</b></label>
                            <textarea type="text" name = "address" class="form-control" placeholder = "Enter Company Address" required></textarea>
                </div>

                <div class = "form-row">
                <div class="form-group col-md-6">
                    <label for = ""><b>Memorandum of Agreement (MOA) Status</b></label>
                    <select name = "moastatus" class = "form-control" />
                    <option value = "">--Select MOA Status--</option>
                    <option value = "MOA in Process">MOA in Process</option>
                    <option value = "MOA Signed">MOA Signed</option>
                    
                    </select>
                </div>
                
                <div class="form-group col-md-6">
                    <label for = ""><b>Branch/Campus/College</b></label>
                    <select name = "branches" class = "form-control" />
                    <option value = "">--Select Branch/Campus/College--</option>
                    <option value = "San Juan City Branch">San Juan City Branch</option>
                    <option value = "Not Applicable">Not Applicable</option>
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label for = ""><b>Program</b> <i style="font-size:14px;">(to select multiple programs if applicable, hold ctrl then select.)</i></label>
                    <select name = "course[]" class = "form-control" multiple/>
                    
                    <option value = "Bachelor of Science in Accountancy">Bachelor of Science in Accountancy</option>
                    <option value = "Bachelor of Science in Business Administration major in Financial Management">Bachelor of Science in Business Administration major in Financial Management</option>
                    <option value = "Bachelor of Science in Entrepreneurship">Bachelor of Science in Entrepreneurship</option>
                    <option value = "Bachelor of Secondary Education major in English">Bachelor of Secondary Education major in English</option>
                    <option value = "Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                    <option value = "Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                    <option value = "Bachelor of Science in Psychology">Bachelor of Science in Psychology</option>
                    <option value = "Not Applicable">Not Applicable</option>
                    </select>
                </div>

                
                </div>
                <div class = "form-row">
                <div class="form-group col-md-6">
                    <label for = ""><b>Date Signed</b></label>
                    <input type="date" name = "datesigned" class = "form-control" placeholder = "Enter Date Signed">
                </div>
                <div class="form-group col-md-6">
                    <label for = ""><b>MOA Expiration Date</b></label>
                    <input type="date" name = "date_end" class = "form-control" placeholder = "Enter Date End">
                </div>
                </div>

                <style type="text/css">
                    .d-none{
                        display: none;
                    }
                </style>
               

                <div class = "form-row">
                <div class="form-group col-md-6">
                    <label for = ""><b>MOA is signed by the University</b></label>
                    <select name = "univsign" class = "form-control" onchange = "enableMOA(this)">
                    <option value = "">--Please Answer--</option>
                    <option value = "Yes">Yes</option>
                    <option value = "No">No</option>
                    </select>
                </div>
                
                
                <div id = "Upload1" class="form-group col-md-6 d-none">
                    <input class = "inputfile" type = "file" id="fileToUpload1" name = "file" >
                        <label for="fileToUpload1">
                            <i class = "fa fa-upload fa-1x">&emsp;</i>
                            Choose a file
                        </label>
                    <label id="file-name"></label>
                </div>
            
                    
            <script type="text/javascript">
                function enableMOA(answer) {
                    console.log(answer.value);
                        if(answer.value == "Yes") {
                            document.getElementById('Upload1').classList.remove('d-none');
                        } else {
                            document.getElementById('Upload1').classList.add('d-none');
                        }
                    }
                
            </script>

                <div class="form-group col-md-6">
                    <label for = ""><b>Partner Company has DTI Permit</b></label>
                    <select name = "dtipermit" class = "form-control" onchange = "enableDTI(this)"/>
                    <option value = "">--Please Answer--</option>
                    <option value = "Yes">Yes</option>
                    <option value = "No">No</option>
                    </select>
                </div>

                <div id = "Upload2" class="form-group col-md-6 d-none">
                    <input class = "inputfile" type = "file" id="fileToUpload2" name = "fileDTI" >
                        <label for="fileToUpload2">
                            <i class = "fa fa-upload fa-1x">&emsp;</i>
                            Choose a file
                        </label>
                    <label id="file-name"></label>
                </div>
            
                    
            <script type="text/javascript">
                function enableDTI(answer) {
                    console.log(answer.value);
                        if(answer.value == "Yes") {
                            document.getElementById('Upload2').classList.remove('d-none');
                        } else {
                            document.getElementById('Upload2').classList.add('d-none');
                        }
                    }
                
            </script>

                <div class="form-group col-md-6">
                    <label for = ""><b>Partner Company has SEC Permit</b></label>
                    <select name = "secpermit" class = "form-control" onchange = "enableSEC(this)" />
                    <option value = "">--Please Answer--</option>
                    <option value = "Yes">Yes</option>
                    <option value = "No">No</option>
                    </select>
                </div>

                <div id = "Upload3" class="form-group col-md-6 d-none">
                    <input class = "inputfile" type = "file" id="fileToUpload3" name = "fileSEC" >
                        <label for="fileToUpload3">
                            <i class = "fa fa-upload fa-1x">&emsp;</i>
                            Choose a file
                        </label>
                    <label id="file-name"></label>
                </div>
            
                    
            <script type="text/javascript">
                function enableSEC(answer) {
                    console.log(answer.value);
                        if(answer.value == "Yes") {
                            document.getElementById('Upload3').classList.remove('d-none');
                        } else {
                            document.getElementById('Upload3').classList.add('d-none');
                        }
                    }
                
            </script>

                <div class="form-group col-md-6">
                    <label for = ""><b>Partner Company has BIR Permit</b></label>
                    <select name = "birpermit" class = "form-control" onchange="enableBIR(this)" />
                    <option value = "">--Please Answer--</option>
                    <option value = "Yes">Yes</option>
                    <option value = "No">No</option>
                    </select>
                </div>

                <div id = "Upload4" class="form-group col-md-6 d-none">
                    <input class = "inputfile" type = "file" id="fileToUpload4" name = "fileBIR" >
                        <label for="fileToUpload4">
                            <i class = "fa fa-upload fa-1x">&emsp;</i>
                            Choose a file
                        </label>
                    <label id="file-name"></label>
                </div>
            
                    
            <script type="text/javascript">
                function enableBIR(answer) {
                    console.log(answer.value);
                        if(answer.value == "Yes") {
                            document.getElementById('Upload4').classList.remove('d-none');
                        } else {
                            document.getElementById('Upload4').classList.add('d-none');
                        }
                    }
                
            </script>
                </div>

                </div>
                
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name = "save_company" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
            </div>
        </div>

        <!-- Modal VIEW DATA-->
        <div class="modal fade" id="companyVIEWModal" tabindex="1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION</b></h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                
            </div>
            <div class="modal-body">
                <div class = "company_viewing_data"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="view_close" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
        
        <!-- Modal EDIT DATA -->
        <div class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
                                    <div class='modal-dialog modal-lg' style='max-width: 70%;'>
                                    <div class="modal-content">

                                        <div class="modal-header">
                                        <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION SHEET</b></h5>
                                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                        </div>
                                        
                                    <form action = "Coordinator_DashHome.php?adddata" method = "POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                        
                                        <input type = "hidden" name = "edit_id" id = "edit_id">
                                        <div class = "form-row">
                                        <div class="form-group col-md-4">
                                            <label for = ""><b>Company Name</b></label>
                                            <input type="text" name = "compname" id = "edit_compname" class = "form-control" required>
                                        </div>

                                       
                                        <div class="form-group col-md-4">
                                            <label for = ""><b>Nature of Business</b></label>
                                            <select name = "nbusiness" id = "edit_nbusiness" class = "form-control" />
                                            <option value = "">--Select Nature of Business--</option>
                                            <option value = "Government Sector">Government Sector</option>
                                            <option value = "International Sector">International Sector</option>
                                            <option value = "Manufacturing Sector">Manufacturing Sector</option>
                                            <option value = "Merchandising Sector">Merchandising Sector</option>
                                            <option value = "Military Sector">Military Sector</option>
                                            <option value = "Non Profit Sector">Non-Profit Sector</option>
                                            <option value = "Not Applicable">Not Applicable</option>
                                            <option value = "Others">Others</option>
                                            <option value = "Private Sector">Private Sector</option>
                                            <option value = "Public Sector">Public Sector</option>
                                            <option value = "Service Sector">Service Sector</option>
                                            <option value = "Technology Sector">Technology Sector</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for = ""><b>Contact Person</b></label>
                                            <input type="text" name = "contactper" id = "edit_contactper" class = "form-control" required>
                                        </div>


                                        <div class="form-group col-md-4">
                                            <label for = ""><b>Position</b></label>
                                            <input type="text" name = "position" id = "edit_position" class = "form-control" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for = ""><b>Contact Number</b></label>
                                            <input type="text" name = "contactnum" id = "edit_contactnum" class = "form-control" required>
                                        </div>
                                        

                                        <div class="form-group col-md-4">
                                            <label for = ""><b>Email Address</b></label>
                                            <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" required>
                                        </div>
                                        </div>

                                        <div class="form-group">
                                            <label for = ""><b>Company Address</b></label>
                                                    <textarea type="text" name = "address" id = "edit_address" class="form-control" required></textarea>
                                        </div>
                                        <hr style="border-top: 3px double #8c8b8b;">
                                        <div class = "form-row">
                                        
                                        
                                        <div class="form-group col-md-4">
                                            <label for = ""><b>Branch/Campus/College</b></label>
                                            <select name = "branches" id = "edit_branches" class = "form-control" />
                                            <option value = "">--Select Branch/Campus/College--</option>
                                            <option value = "San Juan City Branch">San Juan City Branch</option>
                                            <option value = "Not Applicable">Not Applicable</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for = ""><b>MOA Status</b></label>
                                            <select name = "moastatus" id = "edit_moastatus" class = "form-control" />
                                            <option value = "">--Select MOA Status--</option>
                                            <option value = "MOA in Process">MOA in Process</option>
                                            <option value = "MOA Signed">MOA Signed</option>
                                            
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for = ""><b>Company Status</b></label>
                                            <select name = "comp_status" id = "edit_status" class = "form-control" onchange="editEnableStatus(this)"/>
                                            <option value = "">--Select Company Status--</option>
                                            <option value = "Active">Active</option>
                                            <option value = "Expiring">Expiring (5 months before the expiry date)</option>
                                            <option value = "Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        </div>

                                       
                                       
                                        <div  class = "form-row">
                                            <div id = "defunct_id" class="form-group col-md-6 d-none">
                                                <label for = ""><b>Reason of being Inactive</b></label>
                                                <select name = "report" id = "edit_report" class = "form-control" onchange="enableOthers(this)"/>
                                                <option value = "">--Select Inactive Reason--</option>
                                                <option value = "MOA Expired(Company not renewing)">MOA Expired (Company not renewing)</option>
                                                <option value = "MOA Expired(University not renewing)">MOA Expired (University not renewing)</option>
                                                <option value = "MOA Expired(Company closed down)">MOA Expired (Company closed down)</option>
                                                <option value = "Others">Others:</option>
                                                </select>
                                            </div>
                                            <div id = "defunct_id_s" class="form-group col-md-4 d-none">
                                                <label for = ""><b>Specify reason of being Inactive</b></label>
                                                <input type="text" name = "report_s" id = "edit_report_s" class = "form-control">
                                            </div>

                                            <div id = "reason" class="form-group col-md-6 d-none">
                                                <label for = ""><b>Write down reason:</b></label>
                                                <textarea type="text" name = "reason" id = "reason" class = "form-control" style="max-height:40%;"></textarea>
                                            </div>
                                                                            
                                        </div>
                                        <script type="text/javascript">
                                           
                                            
                                        </script>

                                        <script type="text/javascript">
                                            function editEnableStatus(answer) {
                                                
                                                    if(answer.value == "Inactive") {
                                                        document.getElementById('defunct_id').classList.remove('d-none');
                                                        document.getElementById('reason').classList.remove('d-none');
                                                    } else {
                                                        document.getElementById('defunct_id').classList.add('d-none');
                                                        document.getElementById('reason').classList.add('d-none');
                                                    }
                                                }

                                            function enableOthers(answer){
                                                if(answer.value == "Others")
                                                {
                                                    document.getElementById('defunct_id_s').classList.remove('d-none');
                                                    document.getElementById('defunct_id').classList.remove('col-md-6');
                                                    document.getElementById('defunct_id').classList.add('col-md-4');
                                                    document.getElementById('reason').classList.remove('col-md-6');
                                                    document.getElementById('reason').classList.add('col-md-4');
                                                }
                                                else
                                                {
                                                    document.getElementById('defunct_id_s').classList.add('d-none');
                                                    document.getElementById('defunct_id').classList.remove('col-md-4');
                                                    document.getElementById('defunct_id').classList.add('col-md-6');
                                                    document.getElementById('reason').classList.remove('col-md-4');
                                                    document.getElementById('reason').classList.add('col-md-6');
                                                }
                                            }
                                            
                                        </script>
                                       

                                        <div class="form-group col-md-12">
                                            <label for = ""><b>Program</b> <i style="font-size:14px;">(to select multiple programs if applicable, hold ctrl then select.)</i></label>
                                            <select name = "course[]" id = "edit_course" class = "form-control" multiple/>
                                            
                                            <option value = "Bachelor of Science in Accountancy">Bachelor of Science in Accountancy</option>
                                            <option value = "Bachelor of Science in Business Administration major in Financial Management">Bachelor of Science in Business Administration major in Financial Management</option>
                                            <option value = "Bachelor of Science in Entrepreneurship">Bachelor of Science in Entrepreneurship</option>
                                            <option value = "Bachelor of Secondary Education major in English">Bachelor of Secondary Education major in English</option>
                                            <option value = "Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                                            <option value = "Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                            <option value = "Bachelor of Science in Psychology">Bachelor of Science in Psychology</option>
                                            <option value = "Not Applicable">Not Applicable</option>
                                            </select>
                                        </div>

                                        
                                        
                                                <hr style="border-top: 3px double #8c8b8b;">
                                        
                                            <div class = "form-row">
                                            <div class="form-group col-md-6">
                                                <label for = ""><b>Date Signed</b></label>
                                                <input type="date" name = "datesigned" id="edit_datesigned" class = "form-control" placeholder = "Enter Date Signed">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for = ""><b>MOA Expiration Date</b></label>
                                                <input type="date" name = "date_end" id="edit_dateend" class = "form-control" placeholder = "Enter Date End">
                                            </div>
                                            </div>

                                        <style type="text/css">
                                            .d-none{
                                                display: none;
                                            }
                                        </style>

                                        <div class = "form-row">
                                        <div class="form-group col-md-6">
                                            <label for = ""><b>Is Memorandum of Agreement Signed?</b></label>
                                            <select name = "moasign" id = "edit_moasign" class = "form-control" onchange = "editenableMOA(this)">
                                            <option value = "">--Please Answer--</option>
                                            <option value = "Yes">Yes</option>
                                            <option value = "No">No</option>
                                            </select>
                                        </div>

                                        <div id = "Upload5" class="form-group col-md-6 d-none">
                                            <input class = "inputfile" type = "file" id="fileToUpload1" name = "file" >
                                                <label for="fileToUpload1">
                                                    <i class = "fa fa-upload fa-1x">&emsp;</i>
                                                    Choose a file
                                                </label>
                                            <label id="file-name"></label>
                                        </div>
                                        
                                                
                                        <script type="text/javascript">
                                            function editenableMOA(answer) {
                                                console.log(answer.value);
                                                    if(answer.value == "Yes") {
                                                        document.getElementById('Upload5').classList.remove('d-none');
                                                    } else {
                                                        document.getElementById('Upload5').classList.add('d-none');
                                                    }
                                                }
                                            
                                        </script>

                                    

                                        <div class="form-group col-md-6">
                                            <label for = ""><b>Partner Company has DTI Permit</b></label>
                                            <select name = "dtipermit" id = "edit_dtipermit" class = "form-control" onchange = "editenableDTI(this)"/>
                                            <option value = "">--Please Answer--</option>
                                            <option value = "Yes">Yes</option>
                                            <option value = "No">No</option>
                                            </select>
                                        </div>

                                        <div id = "Upload6" class="form-group col-md-6 d-none">
                                            <input class = "inputfile" type = "file" id="fileToUpload6" name = "fileDTI" >
                                                <label for="fileToUpload6">
                                                    <i class = "fa fa-upload fa-1x">&emsp;</i>
                                                    Choose a file
                                                </label>
                                            <label id="file-name"></label>
                                        </div>
                                    
                                            
                                    <script type="text/javascript">
                                        function editenableDTI(answer) {
                                            console.log(answer.value);
                                                if(answer.value == "Yes") {
                                                    document.getElementById('Upload6').classList.remove('d-none');
                                                } else {
                                                    document.getElementById('Upload6').classList.add('d-none');
                                                }
                                            }
                                        
                                    </script>

                                        <div class="form-group col-md-6">
                                            <label for = ""><b>Partner Company has SEC Permit</b></label>
                                            <select name = "secpermit" id = "edit_secpermit" class = "form-control" onchange = "editenableSEC(this)" />
                                            <option value = "">--Please Answer--</option>
                                            <option value = "Yes">Yes</option>
                                            <option value = "No">No</option>
                                            </select>
                                        </div>

                                        <div id = "Upload7" class="form-group col-md-6 d-none">
                                            <input class = "inputfile" type = "file" id="fileToUpload7" name = "fileSEC" >
                                                <label for="fileToUpload7">
                                                    <i class = "fa fa-upload fa-1x">&emsp;</i>
                                                    Choose a file
                                                </label>
                                            <label id="file-name"></label>
                                        </div>
                                    
                                            
                                    <script type="text/javascript">
                                        function editenableSEC(answer) {
                                            console.log(answer.value);
                                                if(answer.value == "Yes") {
                                                    document.getElementById('Upload7').classList.remove('d-none');
                                                } else {
                                                    document.getElementById('Upload7').classList.add('d-none');
                                                }
                                            }
                                        
                                    </script>

                                        <div class="form-group col-md-6">
                                            <label for = ""><b>Partner Company has BIR Permit</b></label>
                                            <select name = "birpermit" id = "edit_birpermit" class = "form-control" onchange="editenableBIR(this)" />
                                            <option value = "">--Please Answer--</option>
                                            <option value = "Yes">Yes</option>
                                            <option value = "No">No</option>
                                            </select>
                                        </div>

                                        <div id = "Upload8" class="form-group col-md-6 d-none">
                                            <input class = "inputfile" type = "file" id="fileToUpload8" name = "fileBIR" >
                                                <label for="fileToUpload8">
                                                    <i class = "fa fa-upload fa-1x">&emsp;</i>
                                                    Choose a file
                                                </label>
                                            <label id="file-name"></label>
                                        </div>
                                    
                                            
                                    <script type="text/javascript">
                                        function editenableBIR(answer) {
                                            console.log(answer.value);
                                                if(answer.value == "Yes") {
                                                    document.getElementById('Upload8').classList.remove('d-none');
                                                } else {
                                                    document.getElementById('Upload8').classList.add('d-none');
                                                }
                                            }
                                        
                                    </script>
                                        </div>

                                        </div>
                                        
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name = "update_company" class="btn btn-primary">Update</button>
                                        </div>
                                        </form>
                                    </div>
                                    </div>
                                </div>


            <!-- Modal DELETE DATA-->
            <div class="modal fade" id="deleteCompanyModal" tabindex="-1" role="dialog" aria-labelledby="deleteCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id = "deleteCompanyModalLabel"><b>CONFIRMATION</b></h5>
                    
                </div>
                <form action = "Coordinator_DashHome.php?adddata" method = "POST">
                <div class="modal-body">
                <input type = "hidden" name = "company_id" id = "delete_id">
                    <h4> Do you want to remove this company? </h4>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name = "delete_company" class="btn btn-danger">Confirm</button>
                </div>
                </form>
                </div>
            </div>
            </div>

            <!-- defunct modal -->

            <div class="modal fade" id="defunctModal" tabindex="-1" role="dialog" aria-labelledby="deleteCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="min-width:80%;">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><b>INACTIVE COMPANY</b> </h5>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                    <div class="card">
                    <div class="card-body">
                    <div class="table table-responsive">
                    <table id = "defunct_company">
                    <thead>
                    <tr>
                        <th style='display: none;'></th>
                        <th scope = "col">Company Name</th>
                        <th scope = "col">Contact Person</th>
                        
                        <th scope = "col">Date Signed</th>
                        <th scope = "col">Expiration Date</th>
                        <th scope = "col">MOA Status</th>
                        <th scope = "col">Company Status</th>
                        <th scope = "col">Reason of being Inactive</th>
                        <th scope = "col">Action</th>
                        
                    </tr>
                    </thead>

                    <tbody>
                        <!-- changes starts here -->
                    <?php
                    $query = "SELECT * FROM company_list WHERE company_status = 'Inactive'";
                    $query_run = mysqli_query($conn, $query);

                    if (mysqli_num_rows($query_run) > 0)
                    {
                        while ($row = mysqli_fetch_array($query_run))
                        {
                            $id = $row['company_id'];
                        ?>
                        <tr>
                            <td class="comp_id" style='display: none;'><?php echo $row['company_id']; ?></td>
                            <td><?php echo $row['company_name']; ?></td>
                            <td style="text-align:center;"><?php echo $row['contact_person']; ?></td>
                            
                            <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_signed'])); ?></td>
                            <?php 
                            if($row['date_end'] === "0000-00-00")
                            {
                                ?>
                                    <td style="text-align:center;">No Expiration Date</td>
                                <?php
                            }
                            else
                            {
                                ?>
                                    <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_end'])); ?></td>
                                <?php
                            }
                            ?>
                            
                            <td style="text-align:center;"><?php echo $row['moa_status']; ?></td>
                            <td style="text-align:center;"><?php echo $row['company_status']; ?></td>
                            <td style="text-align:center;"><?php echo $row['report']; ?></td>
                            <td style="text-align:center;">
                            <div class="btn-group" role="group" aria-label="Button Group">
                            <a href="#" button type="button" class = "userinfo btn btn-primary view_btn"><i class='fa fa-eye'></i></a>
                            <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn"><i class='fa fa-edit'></i></a>
                            <a href="#" button type="button" class = "userinfo btn btn-danger delete_btn"><i class='fa fa-trash'></i></a>

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
            </div>

            </div>



            <div class="card">
                
            <div class="card-body">
                
            <div class="table table-responsive">
            
            <table id = "company">
            <thead>
            <tr>
                <th style='display: none;'></th>
                <th scope = "col">Company Name</th>
                <th scope = "col">Contact Person</th>
                
                <th scope = "col">Date Signed</th>
                <th scope = "col">Expiration Date</th>
                <th scope = "col">MOA Status</th>
                <th scope = "col">Company Status</th>
                <th scope = "col">Action</th>
                
            </tr>
            </thead>

            <tbody>
                <!-- changes starts here -->
            <?php
            $query = "SELECT * FROM company_list WHERE company_status != 'Inactive' AND remove = 'no'";
            $query_run = mysqli_query($conn, $query);

            if (mysqli_num_rows($query_run) > 0)
            {
                while ($row = mysqli_fetch_array($query_run))
                {
                    $id = $row['company_id'];
                ?>
                <tr>
                    <td class="comp_id" style='display: none;'><?php echo $row['company_id']; ?></td>
                    <td><?php echo $row['company_name']; ?></td>
                    <td style="text-align:center;"><?php echo $row['contact_person']; ?></td>
                    
                    <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_signed'])); ?></td>
                    <?php 
                    if($row['date_end'] === "0000-00-00")
                    {
                        ?>
                            <td style="text-align:center;">No Expiration Date</td>
                        <?php
                    }
                    else
                    {
                        ?>
                            <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_end'])); ?></td>
                        <?php
                    }
                    ?>
                    
                    <td style="text-align:center;"><?php echo $row['moa_status']; ?></td>
                    <td style="text-align:center;"><?php echo $row['company_status']; ?></td>
                    <td style="text-align:center;">
                    <div class="btn-group" role="group" aria-label="Button Group">
                    <a href="#" button type="button" class = "userinfo btn btn-primary view_btn"><i class='fa fa-eye'></i></a>
                    <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn"><i class='fa fa-edit'></i></a>
                    <a href="#" button type="button" class = "userinfo btn btn-danger delete_btn"><i class='fa fa-trash'></i></a>
                    </td>
                    </div>
                </tr>
                <?php
                }
            }

            else
            {
                echo "<h5>NO RECORD FOUND!</h5>";
            }
            ?>

            </tbody>
            </table>
            </div>
            </div></div></div>

             <script>
                 $('.defunct').click(function() {

                     $('#defunctModal').modal('show');

                })
            </script>

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

                var comp_id = $(this).closest('tr').find('.comp_id').text();
                $('#delete_id').val(comp_id);
                $('#deleteCompanyModal').modal('show');
                });

                $('.edit_btn').click(function(e) {
                e.preventDefault();

                

                // const id = $(this).getElementById(''
                // $('#test').click(() => { alert('jello')}));

                var comp_id = $(this).closest('tr').find('.comp_id').text();
                
                $.ajax({
                type: "POST",
                url: "Coordinator_DashHome.php?adddata",
                data: {
                    'checking_editbtn': true,
                    'company_id': comp_id,
                },
                success: function (response) {
                    $.each(response,function (key, value){
                    $('#edit_id').val(value['company_id']);
                    $('#edit_compname').val(value['company_name']);
                    $('#edit_nbusiness').val(value['nature_business']);
                    $('#edit_contactper').val(value['contact_person']);
                    $('#edit_position').val(value['position']);
                    $('#edit_contactnum').val(value['contact_num']);
                    $('#edit_emailadd').val(value['email']);
                    $('#edit_address').val(value['address']);
                    $('#edit_moastatus').val(value['moa_status']);
                    $('#edit_course').val(value['course']);
                    $('#edit_status').val(value['company_status']);
                    $('#edit_branches').val(value['branches']);
                    $('#edit_datesigned').val(value['date_signed']);
                    $('#edit_dateend').val(value['date_end']);
                    $('#edit_moasign').val(value['MOA_sign']);
                    $('#edit_dtipermit').val(value['dti_permit']);
                    $('#edit_secpermit').val(value['sec_permit']);
                    $('#edit_birpermit').val(value['bir_permit']);
                    $('#edit_report').val(value['report']);
                    $('#edit_report_s').val(value['report']);
                    });
                    $('#editCompanyModal').modal('show');               
                }
                });

                });

                $('.view_btn').click(function(e) {
                e.preventDefault();

                var comp_id = $(this).closest('tr').find('.comp_id').text();
                
                $.ajax({
                type: "POST",
                url: "Coordinator_DashHome.php?adddata",
                data: {
                    'checking_viewbtn': true,
                    'company_id': comp_id,
                },
                success: function (response) {
                    console.log(response);
                    $('.company_viewing_data').html(response);
                    $('#companyVIEWModal').modal('show');
                }
                });

                });
            });
            </script>

           




            <script>
            $(document).ready(function(){
            $('#company').DataTable({
                lengthMenu:[
                [-1, 10, 15, 20, 30, 50],
                ['All', 10, 15, 20, 30, 50],
                ],
            });
            $('#defunct_company').DataTable({
                lengthMenu:[
                [-1, 10, 15, 20, 30, 50],
                ['All', 10, 15, 20, 30, 50],
                ],
            });
            });
            </script>

                </div> <!-- end -->
        </div> 
    </div> 
</div>

       
<div class="print-only">
    
    <img src="../images/pupmainlogo.png" style = "width: 130px; float:left; margin-right: 5px;">
    <p style = "font-family:'Times New Roman',serif; padding: 10px 0px 5px 0px; margin: 0; font-size: 14px;">Republic of the Philippines</p>
    <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p></b>
    <p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 14px;">OFFICE OF THE VICE PRESIDENT FOR BRANCHES AND SATELLITE CAMPUSES</p>
    <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">San Juan City Branch  <button type="button" id = "PrintButton" class="userinfo btn btn-primary" style="float:right;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;Advanced Filter</b> </button></h1></p></b>
    <br><hr>
    <table id = "table">
<thead style = "background-color: #EEEEEE;">
<tr>
    <th style='display: none;'></th>
    <th scope = "col">Company Name</th>
    <th scope = "col">Contact Person</th>
    <th scope = "col">Company Address</th>
    <th scope = "col">Contact Number</th>
    <th scope = "col">MOA Status</th>
    <th scope = "col">Date Signed</th>
    <th scope = "col">Expiration Date</th>
    <th scope = "col">Company Status</th>
    
</tr>
</thead>

<tbody>
    <!-- changes starts here -->
<?php
$query = "SELECT * FROM company_list WHERE company_status != 'Inactive' AND remove = 'no'";
$query_run = mysqli_query($conn, $query);

if (mysqli_num_rows($query_run) > 0)
{
    while ($row = mysqli_fetch_array($query_run))
    {
        $id = $row['company_id'];
    ?>
    <tr>
        <td class="comp_id" style='display: none;'><?php echo $row['company_id']; ?></td>
        <td><?php echo $row['company_name']; ?></td>
        <td><?php echo $row['contact_person']; ?></td>
        <td><?php echo $row['address']; ?></td>
        <td><?php echo $row['contact_num']; ?></td>
        <td><?php echo $row['moa_status']; ?></td>
        <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_signed'])); ?></td>
        <?php 
        if($row['date_end'] === "0000-00-00")
        {
            ?>
                <td style="text-align:center;">No Expiration Date</td>
            <?php
        }
        else
        {
            ?>
                <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_end'])); ?></td>
            <?php
        }
        ?>
        <td><?php echo $row['company_status']; ?></td>
    </tr>
    <?php
    }
}

else
{
    echo "<h5>NO RECORD FOUND!</h5>";
}
?>

</tbody>
</table>
<br><br><br>
<footer style = "margin-left: 20px;">
<p style = "margin: 5px;">Prepared By:</p><br><hr style="background-color:black;border-width:2px; width: 30%;">
<p style = "margin: 0; margin-left: 2%; font-size: 4;">Signature over Printed Name</p>
</footer>


</div>


</body>
</html>
<?php }

if(isset($_REQUEST['Filter']))
{
    if(isset($_POST['filter']))
    {
        ?>
            

            <!DOCTYPE html>
            <html lang="en" style = "height: auto;">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title>Coordinator Dashboard - Partner Company List</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
            

                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
                <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
                <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
                <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
                <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


                <link rel = "stylesheet" href = "../css/coordinator_dashdocu.css">

                <style>
                    .table-responsive
                    {
                        
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
                    #company 
                    {
                    font-family: Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    min-width: 100%;
                    }

                    #company td, #company th 
                    {
                    border: 1px solid #ddd;
                    padding: 8px;
                    }

                    #company tr:nth-child(even){background-color: #f2f2f2;}

                    #company th 
                    {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    background-color: maroon;
                    color: white;
                    text-align: center;
                    white-space: nowrap;
                    }

                     
                    #table 
                    {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                    border-collapse: collapse;
                    /*width: 100%;*/
                    margin-right: 20px;
                    margin-left: 20px;
                    margin-bottom: 20px;
                    }

                    #table td
                    {
                    text-align: center;
                    border: 1px solid #ddd;
                    /* padding: 8px; */
                    }

                    #table tr:nth-of-type(even)
                    {
                    text-align: center;
                    border: 1px solid #ddd;
                    background-color: #EEEEEE;
                    /* padding: 8px; */
                    }

                    /* #company tr:nth-child(even){background-color: #f2f2f2;} */

                    #table th 
                    {
                    border: 1px solid #ddd;
                    padding: 8px;
                    padding-top: 12px;
                    padding-bottom: 12px;
                    text-align: left;
                    color: black;
                    text-align: center;
                    }


                    body 
                    {
                    font-family: Arial, Helvetica, sans-serif;
                    }

                    hr.solid 
                    {
                    border-top: 3px solid #bbb;
                    }
                    
                    .display_screen 
                    {
                        display:block;
                    }

                    .print-only 
                    {
                        display:none;
                    }

                    @media print{
                        #PrintButton{
                            display: none;
                        }
                        .display_screen 
                        {
                            display: none;
                        }
                    

                        @page{
                            size: landscape;
                        }
                        .print-only 
                        {
                            display:block;
                        }
                        .code{
                            color: red;
                        }
                    }
                </style>
                <script>
                    function printPage() {
                        // Show the elements with class "print-only"
                        var printElements = document.getElementsByClassName('print-only');
                        for (var i = 0; i < printElements.length; i++) {
                            printElements[i].style.display = 'block';
                        }

                        // Print the page
                        window.print();

                        // Hide the elements with class "print-only" again
                        for (var i = 0; i < printElements.length; i++) {
                            printElements[i].style.display = 'none';
                        }
                    }
                </script>
            </head>

            <body>
            <div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

            <div class = "display_screen">
                    <!--SIDE BAR-->
                    <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;">
                        <center>
                            <!--Background Side Bar-->

                            <br><br>
                            <img alt="PUP" class="img-circle" src="../images/pupsjlogo.png"><br><br>

                            <h6 style="color: white;"> Coordinator </h6>
                        <br><br><hr style="background-color:white;width:200px;">
                        <a href="Coordinator_Dashboard.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp;Dashboard</a>
                        <a href="Coordinator_DashHome.php?Home" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-building" style="font-size: 1.3em;"></i>&emsp;Partner Company List </a>
                        <a href="Coordinator_Record_Adviser.php?coordnum=<?php echo $coordnum ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-users" style="font-size: 1.3em;"></i>&emsp;Record </a>
                        <a href="Coordinator_Classwork.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments-o" style="font-size: 1.3em;"></i>&emsp;Announcement </a>
                        <a href="Coordinator_Document.php?Home" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-file-text" style="font-size: 1.3em;"></i>&emsp;Document </a>
                        <a href="Coordinator_Graph.php" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp;Company Evaluation</a>
                        
                        </center>
                    </div>

                    
                <?php
                    
                    if(isset($_REQUEST['CSVsucess']))
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
                            title: 'CSV file successfully added'
                        })
                        </script>
                <?php
                    }
                    if(isset($_REQUEST['CSVunsucess1']))
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
                    if(isset($_REQUEST['CSVunsucess2']))
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
                    if(isset($_REQUEST['CSVunsucess3']))
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
                            title: 'Company List Upload Done'
                        })
                        </script>
                <?php
                    }
                    if(isset($_REQUEST['uploadnotsuccess2']))
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
                            title: 'Your file is too big.'
                        })
                        </script>
                <?php
                    }
                    if(isset($_REQUEST['uploadnotsuccess3']))
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
                            title: 'There was an error uploading your File.'
                        })
                        </script>
                <?php
                    }
                    if(isset($_REQUEST['uploadnotsuccess4']))
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
                            title: 'You cannot upload files of this type.'
                        })
                        </script>
                <?php
                    }
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
                            title: 'Partner Company List Added Successfully.'
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
                            title: 'Partner Company List Updated Successfully.'
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
                            title: 'Something went wrong.'
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
                            title: 'Partner Company List Deleted Successfully.'
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
                            title: 'Something went wrong.'
                        })
                        </script>
                <?php
                    }
                    ?>

                   

                    <div class = "main">
                        <div class = "topnavbar" style="z-index: 1;">
                        
                        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
                            <div class="topnavbar-right" id="topnav_right">
                                <a target = "_self" href="../Logout.php?Logout=<?php echo $coordnum ?>" > 
                                            Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                                </a>
                            </div>
                        </div> <!--end of topnavbar-->
                    
                        <br><br><br><br>

                        <h1 class="font-weight-bold"  style = "font-size: 35px; color:maroon;">Partner Company List </h1>
                        <hr style="background-color:maroon;border-width:2px;">
                        <button type="button" class="btn btn-primary" style="background-color:maroon;" onclick = "printPage()"><b><i class="fa fa-file-text-o"></i>&emsp;PRINT LIST</b> </button> <br>
                        <button type="button" class="btn btn-primary" style="background-color:maroon;" data-toggle="modal" data-target="#myModal"><b>+ ADD PARTNER COMPANY ENTRY</b> </button>
                        <button type="button" class="btn btn-primary" style="background-color:maroon;" data-toggle="modal" data-target="#addComp"> <b>+ ADD PARTNER COMPANY LIST (CSV FILE)</b> </button>
                        <button type="button" class="userinfo btn btn-danger" style="background-color:maroon;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;ADVANCED FILTER</b> </button>
                    
                        <br><br>
                        <script>
                        const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
                        function handleScreenSizeChange(tabletSize) {
                            if (tabletSize.matches) 
                            {
                                document.getElementById('topnav_right').classList.remove('topnavbar-right');
                            } 
                            else
                            {
                                document.getElementById('topnav_right').classList.add('topnavbar-right');
                            }
                        }

                        tabletSize.addListener(handleScreenSizeChange);
                        handleScreenSizeChange(tabletSize);
                    </script>
                        
                        <!-- Modal Add partner company csv -->

                    <div class="modal fade" id="addComp">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                            <h5 class="modal-title"><b>UPLOAD CSV FILE</b></h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <form action="Coordinator_DashHome.php?adddata" method="post" enctype='multipart/form-data'>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                <center>
                                                    <label for = ""><b>Place Folder Here</b></label><br>
                                                    <p>&emsp;&emsp;<input type="file" name="product_file" /></p>
                                                </center>
                                                </div>
                                            </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name = "upload" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal FILTER DATA-->
                    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><b>ADVANCED FILTER</b></h5>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <form action = "Coordinator_DashHome.php?Filter" method = "POST">
                                    <div class = 'form-row'>
                                        <div class='form-group col-md-6'>
                                            <div class="md-title">
                                                <label for="exampleFormControlInput1" name="title" class="form-label"><b>Company Name</b> </label>
                                                <input type="text" name="compname" class="form-control" placeholder="Search Company Name">
                                            </div>
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <div class="md-title">
                                                <label for="exampleFormControlInput1" name="title" class="form-label"><b> Contact Person</b></label>
                                                <input type="text" name="contact" class="form-control" placeholder="Search Contact Person">
                                            </div>
                                        </div>
                                    </div>
                                    <div class = 'form-row'>
                                        <div class="form-group col-md-6">
                                            <div class="md-title">
                                                <label for = ""><b>MOA Status</b></label>
                                                <select name = "MOAstatus" class = "form-control" />
                                                <option value = "">--Select MOA Status--</option>
                                                <option value = "MOA in Process">MOA in Process</option>
                                                <option value = "MOA Signed">MOA Signed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="md-title">
                                                <label for = ""><b>Company Status</b></label>
                                                <select name = "COMPstatus" class = "form-control" />
                                                <option value = "">--Select Company Status--</option>
                                                <option value = "Active">Active</option>
                                                <option value = "Expiring">Expiring (5 months before the expiry date)</option>
                                                <option value = "Inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class = 'form-row'>
                    
                                        <div class='form-group col-md-6'>
                                            <div class="md-title">
                                                <label for="exampleFormControlInput1" name="title" class="form-label"><b> Start Date</b></label>
                                                <input type="datetime-local" name="signed" class="form-control">
                                            </div>
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <div class="md-title">
                                                <label for="exampleFormControlInput1" name="title" class="form-label"><b> Due Date</b></label>
                                                <input type="datetime-local" name="expired" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name = "filter" class="btn btn-danger">Filter</button>
                                </div>
                                </form>
                                </div>
                            </div>
                            </div>
                            
                                <br>
                        
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        
                            <div class="modal-header">
                            <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION SHEET</b></h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        
                        <form action = "Coordinator_DashHome.php?adddata" method = "POST" enctype="multipart/form-data">
                            <div class="modal-body">

                            <div class="form-group">
                                <label for = ""><b>Company Name</b></label>
                                <input type="text" name = "compname" class = "form-control" placeholder = "Enter Company Name" required>
                            </div>

                            <div class = "form-row">
                            <div class="form-group col-md-6">
                                <label for = ""><b>Nature of Business</b></label>
                                <select name = "nbusiness" class = "form-control" />
                                <option value = "">--Select Nature of Business--</option>
                                <option value = "Government Sector">Government Sector</option>
                                <option value = "International Sector">International Sector</option>
                                <option value = "Manufacturing Sector">Manufacturing Sector</option>
                                <option value = "Merchandising Sector">Merchandising Sector</option>
                                <option value = "Military Sector">Military Sector</option>
                                <option value = "Non Profit Sector">Non-Profit Sector</option>
                                <option value = "Not Applicable">Not Applicable</option>
                                <option value = "Others">Others</option>
                                <option value = "Private Sector">Private Sector</option>
                                <option value = "Public Sector">Public Sector</option>
                                <option value = "Service Sector">Service Sector</option>
                                <option value = "Technology Sector">Technology Sector</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Contact Person</b></label>
                                <input type="text" name = "contactper" class = "form-control" placeholder = "Enter Contact Person" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Position</b></label>
                                <input type="text" name = "position" class = "form-control" placeholder = "Enter Company Designation" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Contact Number</b></label>
                                <input type="text" name = "contactnum" class = "form-control" placeholder = "Enter Contact Number" required>
                            </div>
                            </div>

                            <div class="form-group">
                                <label for = ""><b>Email Address</b></label>
                                <input type="text" name = "emailadd" class = "form-control" placeholder = "Enter Email Address" required>
                            </div>

                            <div class="form-group">
                                <label for = ""><b>Company Address</b></label>
                                        <textarea type="text" name = "address" class="form-control" placeholder = "Enter Company Address" required></textarea>
                            </div>

                            <div class = "form-row">
                            <div class="form-group col-md-6">
                                <label for = ""><b>Memorandum of Agreement (MOA) Status</b></label>
                                <select name = "moastatus" class = "form-control" />
                                <option value = "">--Select MOA Status--</option>
                                <option value = "MOA in Process">MOA in Process</option>
                                <option value = "MOA Signed">MOA Signed</option>
                                
                                </select>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for = ""><b>Branch/Campus/College</b></label>
                                <select name = "branches" class = "form-control" />
                                <option value = "">--Select Branch/Campus/College--</option>
                                <option value = "San Juan City Branch">San Juan City Branch</option>
                                <option value = "Not Applicable">Not Applicable</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for = ""><b>Program</b> <i style="font-size:14px;">(to select multiple programs if applicable, hold ctrl then select.)</i></label>
                                <select name = "course[]" class = "form-control" multiple/>
                                
                                <option value = "Bachelor of Science in Accountancy">Bachelor of Science in Accountancy</option>
                                <option value = "Bachelor of Science in Business Administration major in Financial Management">Bachelor of Science in Business Administration major in Financial Management</option>
                                <option value = "Bachelor of Science in Entrepreneurship">Bachelor of Science in Entrepreneurship</option>
                                <option value = "Bachelor of Secondary Education major in English">Bachelor of Secondary Education major in English</option>
                                <option value = "Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                                <option value = "Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                <option value = "Bachelor of Science in Psychology">Bachelor of Science in Psychology</option>
                                <option value = "Not Applicable">Not Applicable</option>
                                </select>
                            </div>

                            
                            </div>
                            <div class = "form-row">
                            <div class="form-group col-md-6">
                                <label for = ""><b>Date Signed</b></label>
                                <input type="date" name = "datesigned" class = "form-control" placeholder = "Enter Date Signed">
                            </div>
                            <div class="form-group col-md-6">
                                <label for = ""><b>MOA Expiration Date</b></label>
                                <input type="date" name = "date_end" class = "form-control" placeholder = "Enter Date End">
                            </div>
                            </div>

                            <style type="text/css">
                                .d-none{
                                    display: none;
                                }
                            </style>
                        

                            <div class = "form-row">
                            <div class="form-group col-md-6">
                                <label for = ""><b>MOA is signed by the University</b></label>
                                <select name = "univsign" class = "form-control" onchange = "enableMOA(this)">
                                <option value = "">--Please Answer--</option>
                                <option value = "Yes">Yes</option>
                                <option value = "No">No</option>
                                </select>
                            </div>
                            
                            
                            <div id = "Upload1" class="form-group col-md-6 d-none">
                                <input class = "inputfile" type = "file" id="fileToUpload1" name = "file" >
                                    <label for="fileToUpload1">
                                        <i class = "fa fa-upload fa-1x">&emsp;</i>
                                        Choose a file
                                    </label>
                                <label id="file-name"></label>
                            </div>
                        
                                
                        <script type="text/javascript">
                            function enableMOA(answer) {
                                console.log(answer.value);
                                    if(answer.value == "Yes") {
                                        document.getElementById('Upload1').classList.remove('d-none');
                                    } else {
                                        document.getElementById('Upload1').classList.add('d-none');
                                    }
                                }
                            
                        </script>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Partner Company has DTI Permit</b></label>
                                <select name = "dtipermit" class = "form-control" onchange = "enableDTI(this)"/>
                                <option value = "">--Please Answer--</option>
                                <option value = "Yes">Yes</option>
                                <option value = "No">No</option>
                                </select>
                            </div>

                            <div id = "Upload2" class="form-group col-md-6 d-none">
                                <input class = "inputfile" type = "file" id="fileToUpload2" name = "fileDTI" >
                                    <label for="fileToUpload2">
                                        <i class = "fa fa-upload fa-1x">&emsp;</i>
                                        Choose a file
                                    </label>
                                <label id="file-name"></label>
                            </div>
                        
                                
                        <script type="text/javascript">
                            function enableDTI(answer) {
                                console.log(answer.value);
                                    if(answer.value == "Yes") {
                                        document.getElementById('Upload2').classList.remove('d-none');
                                    } else {
                                        document.getElementById('Upload2').classList.add('d-none');
                                    }
                                }
                            
                        </script>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Partner Company has SEC Permit</b></label>
                                <select name = "secpermit" class = "form-control" onchange = "enableSEC(this)" />
                                <option value = "">--Please Answer--</option>
                                <option value = "Yes">Yes</option>
                                <option value = "No">No</option>
                                </select>
                            </div>

                            <div id = "Upload3" class="form-group col-md-6 d-none">
                                <input class = "inputfile" type = "file" id="fileToUpload3" name = "fileSEC" >
                                    <label for="fileToUpload3">
                                        <i class = "fa fa-upload fa-1x">&emsp;</i>
                                        Choose a file
                                    </label>
                                <label id="file-name"></label>
                            </div>
                        
                                
                        <script type="text/javascript">
                            function enableSEC(answer) {
                                console.log(answer.value);
                                    if(answer.value == "Yes") {
                                        document.getElementById('Upload3').classList.remove('d-none');
                                    } else {
                                        document.getElementById('Upload3').classList.add('d-none');
                                    }
                                }
                            
                        </script>

                            <div class="form-group col-md-6">
                                <label for = ""><b>Partner Company has BIR Permit</b></label>
                                <select name = "birpermit" class = "form-control" onchange="enableBIR(this)" />
                                <option value = "">--Please Answer--</option>
                                <option value = "Yes">Yes</option>
                                <option value = "No">No</option>
                                </select>
                            </div>

                            <div id = "Upload4" class="form-group col-md-6 d-none">
                                <input class = "inputfile" type = "file" id="fileToUpload4" name = "fileBIR" >
                                    <label for="fileToUpload4">
                                        <i class = "fa fa-upload fa-1x">&emsp;</i>
                                        Choose a file
                                    </label>
                                <label id="file-name"></label>
                            </div>
                        
                                
                        <script type="text/javascript">
                            function enableBIR(answer) {
                                console.log(answer.value);
                                    if(answer.value == "Yes") {
                                        document.getElementById('Upload4').classList.remove('d-none');
                                    } else {
                                        document.getElementById('Upload4').classList.add('d-none');
                                    }
                                }
                            
                        </script>
                            </div>

                            </div>
                            
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name = "save_company" class="btn btn-primary">Save</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>

                    <!-- Modal VIEW DATA-->
                    <div class="modal fade" id="companyVIEWModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION</b></h5>

                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class = "company_viewing_data"> </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                    </div>
                    
                    <!-- Modal EDIT DATA -->
                    <div class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                    <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION SHEET</b></h5>
                                                
                                                    </div>
                                                    
                                                <form action = "Coordinator_DashHome.php?adddata" method = "POST">
                                                    <div class="modal-body">
                                                    
                                                    <input type = "hidden" name = "edit_id" id = "edit_id">

                                                    <div class="form-group">
                                                        <label for = ""><b>Company Name</b></label>
                                                        <input type="text" name = "compname" id = "edit_compname" class = "form-control" required>
                                                    </div>

                                                    <div class = "form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Nature of Business</b></label>
                                                        <select name = "nbusiness" id = "edit_nbusiness" class = "form-control" />
                                                        <option value = "">--Select Nature of Business--</option>
                                                        <option value = "Government Sector">Government Sector</option>
                                                        <option value = "International Sector">International Sector</option>
                                                        <option value = "Manufacturing Sector">Manufacturing Sector</option>
                                                        <option value = "Merchandising Sector">Merchandising Sector</option>
                                                        <option value = "Military Sector">Military Sector</option>
                                                        <option value = "Non Profit Sector">Non-Profit Sector</option>
                                                        <option value = "Not Applicable">Not Applicable</option>
                                                        <option value = "Others">Others</option>
                                                        <option value = "Private Sector">Private Sector</option>
                                                        <option value = "Public Sector">Public Sector</option>
                                                        <option value = "Service Sector">Service Sector</option>
                                                        <option value = "Technology Sector">Technology Sector</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Contact Person</b></label>
                                                        <input type="text" name = "contactper" id = "edit_contactper" class = "form-control" required>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Position</b></label>
                                                        <input type="text" name = "position" id = "edit_position" class = "form-control" required>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Contact Number</b></label>
                                                        <input type="text" name = "contactnum" id = "edit_contactnum" class = "form-control" required>
                                                    </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for = ""><b>Email Address</b></label>
                                                        <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for = ""><b>Company Address</b></label>
                                                                <textarea type="text" name = "address" id = "edit_address" class="form-control" required></textarea>
                                                    </div>

                                                    <div class = "form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Memorandum of Agreement (MOA) Status</b></label>
                                                        <select name = "moastatus" id = "edit_moastatus" class = "form-control" />
                                                        <option value = "">--Select MOA Status--</option>
                                                        <option value = "MOA in Process">MOA in Process</option>
                                                        <option value = "MOA Signed">MOA Signed</option>
                                                        
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Branch/Campus/College</b></label>
                                                        <select name = "branches" id = "edit_branches" class = "form-control" />
                                                        <option value = "">--Select Branch/Campus/College--</option>
                                                        <option value = "San Juan City Branch">San Juan City Branch</option>
                                                        <option value = "Not Applicable">Not Applicable</option>
                                                        </select>
                                                    </div>



                                                    <div class="form-group col-md-12">
                                                        <label for = ""><b>Program</b> <i style="font-size:14px;">(to select multiple programs if applicable, hold ctrl then select.)</i></label>
                                                        <select name = "course[]" id = "edit_course" class = "form-control" multiple/>
                                                        
                                                        <option value = "Bachelor of Science in Accountancy">Bachelor of Science in Accountancy</option>
                                                        <option value = "Bachelor of Science in Business Administration major in Financial Management">Bachelor of Science in Business Administration major in Financial Management</option>
                                                        <option value = "Bachelor of Science in Entrepreneurship">Bachelor of Science in Entrepreneurship</option>
                                                        <option value = "Bachelor of Secondary Education major in English">Bachelor of Secondary Education major in English</option>
                                                        <option value = "Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                                                        <option value = "Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                                        <option value = "Bachelor of Science in Psychology">Bachelor of Science in Psychology</option>
                                                        <option value = "Not Applicable">Not Applicable</option>
                                                        </select>
                                                    </div>

                                                    
                                                    </div>

                                                    
                                                        <div class = "form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for = ""><b>Date Signed</b></label>
                                                            <input type="date" name = "datesigned" id="edit_datesigned" class = "form-control" placeholder = "Enter Date Signed">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for = ""><b>MOA Expiration Date</b></label>
                                                            <input type="date" name = "date_end" id="edit_dateend" class = "form-control" placeholder = "Enter Date End">
                                                        </div>
                                                        </div>

                                                    <style type="text/css">
                                                        .d-none{
                                                            display: none;
                                                        }
                                                    </style>

                                                    <div class = "form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Is Memorandum of Agreement Signed?</b></label>
                                                        <select name = "moasign" id = "edit_moasign" class = "form-control" onchange = "editenableMOA(this)">
                                                        <option value = "">--Please Answer--</option>
                                                        <option value = "Yes">Yes</option>
                                                        <option value = "No">No</option>
                                                        </select>
                                                    </div>

                                                    <div id = "Upload5" class="form-group col-md-6 d-none">
                                                        <input class = "inputfile" type = "file" id="fileToUpload5" name = "file" >
                                                            <label for="fileToUpload5">
                                                                <i class = "fa fa-upload fa-1x">&emsp;</i>
                                                                Choose a file
                                                            </label>
                                                        <label id="file-name"></label>
                                                    </div>
                                                    
                                                            
                                                    <script type="text/javascript">
                                                        function editenableMOA(answer) {
                                                            console.log(answer.value);
                                                                if(answer.value == "Yes") {
                                                                    document.getElementById('Upload5').classList.remove('d-none');
                                                                } else {
                                                                    document.getElementById('Upload5').classList.add('d-none');
                                                                }
                                                            }
                                                        
                                                    </script>

                                                

                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Partner Company has DTI Permit</b></label>
                                                        <select name = "dtipermit" id = "edit_dtipermit" class = "form-control" onchange = "editenableDTI(this)"/>
                                                        <option value = "">--Please Answer--</option>
                                                        <option value = "Yes">Yes</option>
                                                        <option value = "No">No</option>
                                                        </select>
                                                    </div>

                                                    <div id = "Upload6" class="form-group col-md-6 d-none">
                                                        <input class = "inputfile" type = "file" id="fileToUpload6" name = "fileDTI" >
                                                            <label for="fileToUpload6">
                                                                <i class = "fa fa-upload fa-1x">&emsp;</i>
                                                                Choose a file
                                                            </label>
                                                        <label id="file-name"></label>
                                                    </div>
                                                
                                                        
                                                <script type="text/javascript">
                                                    function editenableDTI(answer) {
                                                        console.log(answer.value);
                                                            if(answer.value == "Yes") {
                                                                document.getElementById('Upload6').classList.remove('d-none');
                                                            } else {
                                                                document.getElementById('Upload6').classList.add('d-none');
                                                            }
                                                        }
                                                    
                                                </script>

                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Partner Company has SEC Permit</b></label>
                                                        <select name = "secpermit" id = "edit_secpermit" class = "form-control" onchange = "editenableSEC(this)" />
                                                        <option value = "">--Please Answer--</option>
                                                        <option value = "Yes">Yes</option>
                                                        <option value = "No">No</option>
                                                        </select>
                                                    </div>

                                                    <div id = "Upload7" class="form-group col-md-6 d-none">
                                                        <input class = "inputfile" type = "file" id="fileToUpload7" name = "fileSEC" >
                                                            <label for="fileToUpload7">
                                                                <i class = "fa fa-upload fa-1x">&emsp;</i>
                                                                Choose a file
                                                            </label>
                                                        <label id="file-name"></label>
                                                    </div>
                                                
                                                        
                                                <script type="text/javascript">
                                                    function editenableSEC(answer) {
                                                        console.log(answer.value);
                                                            if(answer.value == "Yes") {
                                                                document.getElementById('Upload7').classList.remove('d-none');
                                                            } else {
                                                                document.getElementById('Upload7').classList.add('d-none');
                                                            }
                                                        }
                                                    
                                                </script>

                                                    <div class="form-group col-md-6">
                                                        <label for = ""><b>Partner Company has BIR Permit</b></label>
                                                        <select name = "birpermit" id = "edit_birpermit" class = "form-control" onchange="editenableBIR(this)" />
                                                        <option value = "">--Please Answer--</option>
                                                        <option value = "Yes">Yes</option>
                                                        <option value = "No">No</option>
                                                        </select>
                                                    </div>

                                                    <div id = "Upload8" class="form-group col-md-6 d-none">
                                                        <input class = "inputfile" type = "file" id="fileToUpload8" name = "fileBIR" >
                                                            <label for="fileToUpload8">
                                                                <i class = "fa fa-upload fa-1x">&emsp;</i>
                                                                Choose a file
                                                            </label>
                                                        <label id="file-name"></label>
                                                    </div>
                                                
                                                        
                                                <script type="text/javascript">
                                                    function editenableBIR(answer) {
                                                        console.log(answer.value);
                                                            if(answer.value == "Yes") {
                                                                document.getElementById('Upload8').classList.remove('d-none');
                                                            } else {
                                                                document.getElementById('Upload8').classList.add('d-none');
                                                            }
                                                        }
                                                    
                                                </script>
                                                    </div>

                                                    </div>
                                                    
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name = "update_company" class="btn btn-primary">Update</button>
                                                    </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>


                        <!-- Modal DELETE DATA-->
                        <div class="modal fade" id="deleteCompanyModal" tabindex="-1" role="dialog" aria-labelledby="deleteCompanyModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id = "deleteCompanyModalLabel"><b>CONFIRMATION</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action = "Coordinator_DashHome.php?adddata" method = "POST">
                            <div class="modal-body">
                            <input type = "hidden" name = "company_id" id = "delete_id">
                                <h4> Do you want to delete this information? </h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name = "delete_company" class="btn btn-danger">Confirm</button>
                            </div>
                            </form>
                            </div>
                        </div>
                        </div>
                        <div class="card">
                        <div class="card-body">
                        <div class="table table-responsive">
                        <table id = "company">
                        <thead>
                        <tr>
                            <th style='display: none;'></th>
                            <th scope = "col">Company Name</th>
                            <th scope = "col">Contact Person</th>
                            
                            <th scope = "col">Date Signed</th>
                            <th scope = "col">Expiration Date</th>
                            <th scope = "col">MOA Status</th>
                            <th scope = "col">Company Status</th>
                            <th scope = "col">Action</th>
                            
                        </tr>
                        </thead>

                        <tbody>
                            <!-- changes starts here -->
                        <?php
                        $query = "SELECT DISTINCT * FROM company_list";

                        if(!empty($_POST['compname']))
                        {
                            $compname = $_POST['compname'];
                            $query .=" WHERE LOWER(company_name) LIKE LOWER('%$compname%') ";
                        }
                        
                        if(!empty($_POST['contact']))
                        {
                            $contact = $_POST['contact'];
                            if(strpos($query, "WHERE") === false)
                            {
                                $query .= " WHERE LOWER(contact_person) LIKE LOWER('%$contact%')";
                            }
                            else
                            {
                                $query .=" AND contact_person LIKE '%$contact%' ";
                            }
                            
                        }
                        if(!empty($_POST['MOAstatus']))
                        {
                            $MOAstatus = $_POST['MOAstatus'];
                            if(strpos($query, "WHERE") === false)
                            {
                                $query .= " WHERE moa_status LIKE '$MOAstatus'";
                            }
                            else
                            {
                                $query .=" AND moa_status LIKE '$MOAstatus' ";
                            }
                            
                        }
                        if(!empty($_POST['COMPstatus']))
                        {
                            $COMPstatus = $_POST['COMPstatus'];
                            if(strpos($query, "WHERE") === false)
                            {
                                $query .= " WHERE company_status LIKE '$COMPstatus'";
                            }
                            else
                            {
                                $query .= "AND company_status LIKE '$COMPstatus'";
                            }
                            
                        }
                        if($_POST['signed'] === '0000-00-00 00:00:00' || !empty($_POST['signed']) || $_POST['expired'] === '0000-00-00 00:00:00' || !empty($_POST['expired']))
                        {
                            $signeddate = date('Y-m-d', strtotime($_POST['signed']));
                            $expireddate = date('Y-m-d', strtotime($_POST['expired']));

                            if(!empty(($_POST['expired'])) && !empty(($_POST['signed'])) && strpos($query, "WHERE") === false)
                            {
                                $query .=" WHERE date_signed BETWEEN '$signeddate' AND '$expireddate' AND date_end BETWEEN '$signeddate' AND '$expireddate'";
                            }
                            else if(!empty(($_POST['expired'])) && !empty(($_POST['signed'])) && strpos($query, "WHERE") === true)
                            {
                                $query .=" AND date_signed BETWEEN '$signeddate' AND '$expireddate' AND date_end BETWEEN '$signeddate' AND '$expireddate'";
                            }
                            else if(!empty(($_POST['signed'])) && strpos($query, "WHERE") === false)
                            {
                                $query .= " WHERE date_signed LIKE '$signeddate'";
                            }
                            else if(!empty(($_POST['expired'])) && strpos($query, "WHERE") === false)
                            {
                                $query .= " WHERE date_end LIKE '$expireddate'";
                            }
                            
                            else if(!empty(($_POST['signed'])) && strpos($query, "WHERE") === true)
                            {
                                $query .=" AND date_signed LIKE '$signeddate' ";
                            }
                            else if(!empty(($_POST['expired'])) && strpos($query, "WHERE") === true)
                            {
                                $query .=" AND date_end LIKE '$expireddate'";
                            }
                            
                        }

                        $query_run = mysqli_query($conn, $query);

                        if (mysqli_num_rows($query_run) > 0)
                        {
                            while ($row = mysqli_fetch_array($query_run))
                            {
                                $id = $row['company_id'];
                            ?>
                            <tr>
                                <td class="comp_id" style='display: none;'><?php echo $row['company_id']; ?></td>
                                <td><?php echo $row['company_name']; ?></td>
                                <td style="text-align:center;"><?php echo $row['contact_person']; ?></td>
                                
                                <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_signed'])); ?></td>
                                <?php 
                                if($row['date_end'] === "0000-00-00")
                                {
                                    ?>
                                        <td style="text-align:center;">No Expiration Date</td>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_end'])); ?></td>
                                    <?php
                                }
                                ?>
                                <td style="text-align:center;"><?php echo $row['moa_status']; ?></td>
                                <td style="text-align:center;"><?php echo $row['company_status']; ?></td>
                                <td style="text-align:center;">
                                <div class="btn-group" role="group" aria-label="Button Group">
                                <a href="#" button type="button" class = "userinfo btn btn-primary view_btn"><i class='fa fa-eye'></i></a>
                                <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn"><i class='fa fa-edit'></i></a>
                                <a href="#" button type="button" class = "userinfo btn btn-danger delete_btn"><i class='fa fa-trash'></i></a>
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
                        </div></div></div>

                        <script>
                        $(document).ready(function() {

                            $('.delete_btn').click(function(e) {
                            e.preventDefault();

                            var comp_id = $(this).closest('tr').find('.comp_id').text();
                            $('#delete_id').val(comp_id);
                            $('#deleteCompanyModal').modal('show');
                            });

                            $('.edit_btn').click(function(e) {
                            e.preventDefault();

                            var comp_id = $(this).closest('tr').find('.comp_id').text();
                            
                            $.ajax({
                            type: "POST",
                            url: "Coordinator_DashHome.php?adddata",
                            data: {
                                'checking_editbtn': true,
                                'company_id': comp_id,
                            },
                            success: function (response) {
                                $.each(response,function (key, value){
                                $('#edit_id').val(value['company_id']);
                                $('#edit_compname').val(value['company_name']);
                                $('#edit_nbusiness').val(value['nature_business']);
                                $('#edit_contactper').val(value['contact_person']);
                                $('#edit_position').val(value['position']);
                                $('#edit_contactnum').val(value['contact_num']);
                                $('#edit_emailadd').val(value['email']);
                                $('#edit_address').val(value['address']);
                                $('#edit_moastatus').val(value['moa_status']);
                                $('#edit_course').val(value['course']);
                                $('#edit_branches').val(value['branches']);
                                $('#edit_datesigned').val(value['date_signed']);
                                $('#edit_dateend').val(value['date_end']);
                                $('#edit_moasign').val(value['MOA_sign']);
                                $('#edit_dtipermit').val(value['dti_permit']);
                                $('#edit_secpermit').val(value['sec_permit']);
                                $('#edit_birpermit').val(value['bir_permit']);
                                });
                                $('#editCompanyModal').modal('show');
                            }
                            });

                            });

                            $('.view_btn').click(function(e) {
                            e.preventDefault();

                            var comp_id = $(this).closest('tr').find('.comp_id').text();
                            
                            $.ajax({
                            type: "POST",
                            url: "Coordinator_DashHome.php?adddata",
                            data: {
                                'checking_viewbtn': true,
                                'company_id': comp_id,
                            },
                            success: function (response) {
                                console.log(response);
                                $('.company_viewing_data').html(response);
                                $('#companyVIEWModal').modal('show');
                            }
                            });

                            });
                        });
                        </script>






                        <script>
                        $(document).ready(function(){
                        $('#company').DataTable({
                            lengthMenu:[
                            [10, 15, 20, 30, 50, -1],
                            [10, 15, 20, 30, 50, 'All'],
                            ],
                        });
                        });
                        </script>

                            </div> <!-- end -->
                    </div> 
            </div> 
        </div>
              
        <div class="print-only">
    
    <img src="../images/pupmainlogo.png" style = "width: 130px; float:left; margin-right: 5px;">
    <p style = "font-family:'Times New Roman',serif; padding: 10px 0px 5px 0px; margin: 0; font-size: 14px;">Republic of the Philippines</p>
    <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p></b>
    <p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 14px;">OFFICE OF THE VICE PRESIDENT FOR BRANCHES AND SATELLITE CAMPUSES</p>
    <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">San Juan City Branch </h1></p></b>
    <br><hr>
    <table id = "table">
<thead style = "background-color: #EEEEEE;">
<tr>
    <th style='display: none;'></th>
    <th scope = "col">Company Name</th>
    <th scope = "col">Contact Person</th>
    <th scope = "col">Company Address</th>
    <th scope = "col">Contact Number</th>
    <th scope = "col">MOA Status</th>
    <th scope = "col">Date Signed</th>
    <th scope = "col">Expiration Date</th>
    <th scope = "col">Company Status</th>
    
</tr>
</thead>

<tbody>
    <!-- changes starts here -->
<?php
$query = "SELECT * FROM company_list";

if(!empty($_POST['compname']))
{
    $compname = $_POST['compname'];
    $query .=" WHERE LOWER(company_name) LIKE LOWER('%$compname%') ";
}

if(!empty($_POST['contact']))
{
    $contact = $_POST['contact'];
    if(strpos($query, "WHERE") === false)
    {
        $query .= " WHERE LOWER(contact_person) LIKE LOWER('%$contact%')";
    }
    else
    {
        $query .=" AND contact_person LIKE '%$contact%' ";
    }
    
}
if(!empty($_POST['MOAstatus']))
{
    $MOAstatus = $_POST['MOAstatus'];
    if(strpos($query, "WHERE") === false)
    {
        $query .= " WHERE moa_status LIKE '$MOAstatus'";
    }
    else
    {
        $query .=" AND moa_status LIKE '$MOAstatus' ";
    }
    
}
if(!empty($_POST['COMPstatus']))
{
    $COMPstatus = $_POST['COMPstatus'];
    if(strpos($query, "WHERE") === false)
    {
        $query .= " WHERE company_status LIKE '$COMPstatus'";
    }
    else
    {
        $query .= "AND company_status LIKE '$COMPstatus'";
    }
    
}
if($_POST['signed'] === '0000-00-00 00:00:00' || !empty($_POST['signed']) || $_POST['expired'] === '0000-00-00 00:00:00' || !empty($_POST['expired']))
{
    $signeddate = date('Y-m-d', strtotime($_POST['signed']));
    $expireddate = date('Y-m-d', strtotime($_POST['expired']));

    if(!empty(($_POST['expired'])) && !empty(($_POST['signed'])) && strpos($query, "WHERE") === false)
    {
        $query .="WHERE date_signed <= '$expireddate' AND date_end >= '$signeddate'";
        
    }
    else if(!empty(($_POST['expired'])) && !empty(($_POST['signed'])) && strpos($query, "WHERE") === true)
    {
        $query .=" AND date_signed <= '$expireddate' AND date_end >= '$signeddate'";
    }
    else if(!empty(($_POST['signed'])) && strpos($query, "WHERE") === false)
    {
        $query .= " WHERE date_signed LIKE '$signeddate'";
    }
    else if(!empty(($_POST['expired'])) && strpos($query, "WHERE") === false)
    {
        $query .= " WHERE date_end LIKE '$expireddate'";
    }
    
    else if(!empty(($_POST['signed'])) && strpos($query, "WHERE") === true)
    {
        $query .=" AND date_signed LIKE '$signeddate' ";
    }
    else if(!empty(($_POST['expired'])) && strpos($query, "WHERE") === true)
    {
        $query .=" AND date_end LIKE '$expireddate'";
    }
    
}

$query_run1 = mysqli_query($conn, $query);

if (mysqli_num_rows($query_run1) > 0)
{
    while ($row = mysqli_fetch_array($query_run1))
    {
        $id = $row['company_id'];
    ?>
    <tr>
        <td class="comp_id" style='display: none;'><?php echo $row['company_id']; ?></td>
        <td><?php echo $row['company_name']; ?></td>
        <td><?php echo $row['contact_person']; ?></td>
        <td><?php echo $row['address']; ?></td>
        <td><?php echo $row['contact_num']; ?></td>
        <td><?php echo $row['moa_status']; ?></td>
        <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_signed'])); ?></td>
        <?php 
        if($row['date_end'] === "0000-00-00")
        {
            ?>
                <td style="text-align:center;">No Expiration Date</td>
            <?php
        }
        else
        {
            ?>
                <td style="text-align:center;"><?php echo date("M j Y", strtotime($row['date_end'])); ?></td>
            <?php
        }
        ?>
        <td><?php echo $row['company_status']; ?></td>
    </tr>
    <?php
    }
}

else
{
    echo "<h5>NO RECORD FOUND!</h5>";
}
?>

</tbody>
</table>
<br><br><br>
<footer style = "margin-left: 20px;">
<p style = "margin: 5px;">Prepared By:</p><br><hr style="background-color:black;border-width:2px; width: 30%;">
<p style = "margin: 0; margin-left: 2%; font-size: 4;">Signature over Printed Name</p>
</footer>


</div>


            </body>
            </html>
        <?php
    }
}

?>