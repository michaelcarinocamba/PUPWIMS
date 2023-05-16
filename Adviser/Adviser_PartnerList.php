<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['faculty']))
{
    header("Location: Adviser_Login.php?LoginFirst");
}
    $name = $_SESSION['name'];
    $faculty = $_SESSION['faculty'];


if(isset($_REQUEST['adddata']))
{
    if (isset($_POST['checking_viewbtn']))
    {
        $c_id = $_POST['company_id'];

        $sqlyrs = mysqli_query($conn, "SELECT years_active FROM company_list WHERE company_id = '$c_id'");
        $yrs = mysqli_fetch_array($sqlyrs);   

        $return = "";
        
        $query = "SELECT * FROM company_list WHERE company_id = '$c_id'";
        $query_run = mysqli_query($conn, $query);    
        
        if(mysqli_num_rows($query_run) > 0)
        {
            foreach($query_run as $row)
            {   
            
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
                        <input type='text' class = 'form-control' placeholder = 'Y/M/D: {$row['date_signed']}' disabled>
                        </div>
                <div class='form-group col-md-6'>
                    &emsp;<label for = ''><b>MOA Expiration Date: </b></label>
                        <input type='text' class = 'form-control' placeholder = 'Y/M/D: {$row['date_end']}' disabled>
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

    if (isset($_POST['filter_viewbtn']))
    {
        $c_id = $_POST['company_id'];

        $sqlyrs = mysqli_query($conn, "SELECT years_active FROM company_list WHERE company_id = '$c_id'");
        $yrs = mysqli_fetch_array($sqlyrs);   

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
                        
                    </div>
                    ";
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

}


if(isset($_REQUEST['Home']))
{
    $code = $_REQUEST['classCode'];
    $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    if(mysqli_num_rows($section) > 0)
    {
        $get_section = mysqli_fetch_array($section);

        $course = $get_section['course'];
        $year_section = $get_section['year_section'];
    }
?>


<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Adviser Dashboard - Partner Company List</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




    <link rel = "stylesheet" href = "../css/coordinator_dashdocu.css">

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
        #company 
        {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            overflow-x: auto;
            padding: 0;
            min-width: 100%;
        }

        #company td, #company th 
        {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            max-width: auto;
        }

        #company tr:nth-child(even){background-color: #f2f2f2;}

        #company th 
        {
            padding-top: 15px;
            padding-bottom: 15px;
            text-align: left;
            background-color: maroon;
            color: white;
            text-align: center;
            white-space: nowrap;
        }

        #company td 
        {
            background-color: white;
            color: black;
            text-align: center;
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
        min-width: 100%;
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
         <div class = "topnavbar" style="z-index:5;">
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

            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Partner Company List </h1>
            <hr style="background-color:maroon;border-width:2px; ">
            <button type="button" class="btn btn-danger" style="background-color:maroon;float:right;" onclick = "printPage()"><b><i class="fa fa-file-text-o"></i>&emsp;PRINT LIST</b> </button><br><br>
            <button type="button" class="userinfo btn btn-danger" style="background-color:maroon;margin-left: 1%; float:right;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;ADVANCED FILTER</b> </button>
            <button type="button" class="userinfo btn btn-danger defunct" style="background-color:maroon; float:right;"><b><i class="fa fa-minus"></i>&emsp;INACTIVE COMPANY</b> </button>

            
            <br>

            <br>

            <div class="modal fade" id="defunctModal" tabindex="-1" role="dialog" aria-labelledby="deleteCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="min-width:80%;">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><b>INACTIVE COMPANY</b> </h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
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


        <!-- Modal VIEW DATA-->
        <div class="modal fade" id="companyVIEWModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="view_modal" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION</b></h5>
                </button>
            </div>
            <div class="modal-body">
                <div class = "company_viewing_data"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
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
            <form action = "Adviser_PartnerList.php?classCode=<?php echo $code; ?>&Filter" method = "POST">
            <input type = "hidden" name = "classCode" value="<?php echo $code; ?>">
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
                            <option value = "Expiring">Expiring</option>
                            <option value = "Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div class = 'form-row'>

                    <div class='form-group col-md-6'>
                        <div class="md-title">
                            <label for="exampleFormControlInput1" name="title" class="form-label"><b> Date Signed</b></label>
                            <input type="date" name="signed" class="form-control">
                        </div>
                    </div>
                    <div class='form-group col-md-6'>
                        <div class="md-title">
                            <label for="exampleFormControlInput1" name="title" class="form-label"><b>MOA Expiration Date</b></label>
                            <input type="date" name="expired" class="form-control">
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
                ?>
                <tr>
                    <td class="comp_id" style='display: none;'><?php echo $row['company_id']; ?></td>
                    <td><?php echo $row['company_name']; ?></td>
                    <td><?php echo $row['contact_person']; ?></td>
                    <td><?php echo date("M j Y", strtotime($row['date_signed'])); ?></td>
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
                    <td><?php echo $row['moa_status']; ?></td>
                    <td><?php echo $row['company_status']; ?></td>
                    <td>
                    <div class="btn-group" role="group" aria-label="Button Group">
                    <a href="#" button type="button" class = "userinfo btn btn-primary view_btn"><i class='fa fa-eye'></i></a>
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
            </div>
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

                $('.view_btn').click(function(e) {
                e.preventDefault();

                var comp_id = $(this).closest('tr').find('.comp_id').text();
                
                $.ajax({
                type: "POST",
                url: "Adviser_PartnerList.php?adddata&classCode=<?php echo $code ?>",
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

                $('.filter_btn').click(function(e) {
                e.preventDefault();
                
                $.ajax({
                type: "POST",
                url: "Adviser_PartnerList.php?adddata&classCode=<?php echo $code ?>",
                data: {
                    'filter_viewbtn': true,
                },
                success: function (response) {
                    console.log(response);
                    $('.company_filtering_data').html(response);
                    $('#companyFILTERModal').modal('show');
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
    
                    <style>
                        #Ptable 
                        {
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 14px;
                        border-collapse: collapse;
                        width: 100%;
                        min-width: 90%;
                        max-width: 95%;
                        margin-right: 20px;
                        margin-left: 20px;
                        margin-bottom: 20px;
                        }

                        #Ptable td
                        {
                        text-align: center;
                        border: 1px solid #ddd;
                       
                        /* padding: 8px; */
                        }

                        #Ptable tr:nth-of-type(even)
                        {
                        text-align: center;
                        border: 1px solid #ddd;
                        background-color: #EEEEEE;
                        /* padding: 8px; */
                        }

                        /* #company tr:nth-child(even){background-color: #f2f2f2;} */

                        #Ptable th 
                        {
                        border: 1px solid #ddd;
                        padding: 8px;
                        padding-top: 12px;
                        padding-bottom: 12px;
                        text-align: left;
                        color: black;
                        text-align: center;
                        white-space: nowrap;
                        }

                    </style>
    

    <img src="../images/pupmainlogo.png" style = "width: 130px; float:left; margin-right: 5px;">
    <p style = "font-family:'Times New Roman',serif; padding: 10px 0px 5px 0px; margin: 0; font-size: 14px;">Republic of the Philippines</p>
    <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p></b>
    <p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 14px;">OFFICE OF THE VICE PRESIDENT FOR BRANCHES AND SATELLITE CAMPUSES</p>
    <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">San Juan City Branch  <button type="button" id = "PrintButton" class="userinfo btn btn-primary" style="float:right;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;Advanced Filter</b> </button></h1></p></b>
    <br><hr>
    <table id = "Ptable">
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
    
    <p style = "margin: 5px;">Prepared By:</p><br>
    <hr style="background-color:black;border-width:2px; width: 30%;">
    <p style = "margin: 0; margin-left: 2%; font-size: 4;">Signature over Printed Name</p>
    
</footer>


</div>
<script>
            
            const tabletSize1 = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');

            function handleScreenSizeChange(tabletSize1) {
            if (tabletSize1.matches) 
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
                document.getElementById('view_modal').style.maxWidth = "80%";

                
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
                document.getElementById('view_modal').style.maxWidth = "";
    
            }
            }

            tabletSize1.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize1);
        </script>
        

</body>
</html>
<?php }

if(isset($_REQUEST['Filter']))
{
    $code = $_REQUEST['classCode'];

    if(isset($_POST['filter']))
    {
        $code = $_REQUEST['classCode'];
        $section = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
        if(mysqli_num_rows($section) > 0)
        {
            $get_section = mysqli_fetch_array($section);

            $course = $get_section['course'];
            $year_section = $get_section['year_section'];
        }
    
        ?>
        
        
        <!DOCTYPE html>
        <html lang="en" style = "height: auto;">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
            <title>Adviser Dashboard - Partner Company List</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
        
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
            <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
            <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
            <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
            <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        
        
        
            <link rel = "stylesheet" href = "../css/coordinator_dashdocu.css">
        
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
                #company 
                {
                    font-family: Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    overflow-x: auto;
                    padding: 0;
                    min-width: 100%;
                }
        
                #company td, #company th 
                {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: center;
                    max-width: auto;
                }
        
                #company tr:nth-child(even){background-color: #f2f2f2;}
        
                #company th 
                {
                    padding-top: 15px;
                    padding-bottom: 15px;
                    text-align: left;
                    background-color: maroon;
                    color: white;
                    text-align: center;
                }
        
                #company td 
                {
                    background-color: white;
                    color: black;
                    text-align: center;
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

        
                body 
                {
                font-family: Arial, Helvetica, sans-serif;
                }
        
                hr.solid 
                {
                border-top: 3px solid #bbb;
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
        
                    <h6 style="color: white;font-size:20px;"><b> Adviser </b></h6> <br><p style="text-align:center;font-size:12px;color:white;"><?php echo "$course" . " $year_section"; ?></p><hr style="background-color:white;width:200px;">
                    <a href="Adviser_Dashboard.php?classCode=<?php echo $code ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-bar-chart" style="font-size: 1.3em;"></i>&emsp; Dashboard</a>
                    <a href="Adviser_DashHome.php?classCode=<?php echo $code ?>" style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-comments" style="font-size: 1.3em;"></i>&emsp;Discussion</a>
                    <a href="Adviser_Calendar.php?classCode=<?php echo $code ?>"  style="text-align:left;font-size:13.5px;">&emsp;<i class= "fa fa-clock-o" style="font-size: 1.3em;"></i>&emsp;Mentoring Schedule</a> 
                    
                    <a href="Adviser_ClassList.php" style="margin-top:80%;">
                    <i class = "fa fa-arrow-circle-left fa-1x" style="font-size: 1em;"></i> &nbsp; Back to Class List
                    </a> 
                    
                </center>
            </div>
        
            
            <!--MAIN CONTENT -->
            <div class = "main">
               <!--TOP NAV-->
        <div class = "topnavbar" style="z-index:5;">
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
        
                    <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Partner Company List  </h1>
                    <hr style="background-color:maroon;border-width:2px; ">
                    <button type="button" class="btn btn-danger" style="background-color:maroon;float:right;" onclick = "printPage()"><b><i class="fa fa-file-text-o"></i>&emsp;PRINT LIST</b> </button><br><br>
                    <button type="button" class="userinfo btn btn-danger" style="background-color:maroon;margin-left: 1%;float:right;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;ADVANCED FILTER</b> </button>
                    <button type="button" class="userinfo btn btn-danger defunct" style="background-color:maroon; float:right;"><b><i class="fa fa-minus"></i>&emsp;INACTIVE COMPANY</b> </button>
                    <br>
                    
                    <br>
                    
                    <div class="modal fade" id="defunctModal" tabindex="-1" role="dialog" aria-labelledby="deleteCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="min-width:80%;">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><b>INACTIVE COMPANY</b> </h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                    $query = "SELECT * FROM company_list WHERE company_status = 'Inactive' AND remove = 'no'";
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
        
        
                <!-- Modal VIEW DATA-->
                <div class="modal fade" id="companyVIEWModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" id="filter_view" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION</b></h5>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class = "company_viewing_data"> </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
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
                    <form action = "Adviser_PartnerList.php?classCode=<?php echo $code ?>&Filter" method = "POST">
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
                                    <option value = "Expiring">Expiring</option>
                                    <option value = "Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class = 'form-row'>
        
                            <div class='form-group col-md-6'>
                                <div class="md-title">
                                    <label for="exampleFormControlInput1" name="title" class="form-label"><b> Date Signed</b></label>
                                    <input type="date" name="signed" class="form-control">
                                </div>
                            </div>
                            <div class='form-group col-md-6'>
                                <div class="md-title">
                                    <label for="exampleFormControlInput1" name="title" class="form-label"><b> MOA Expiration Date</b></label>
                                    <input type="date" name="expired" class="form-control">
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
                       
                    <?php

                    $query = "SELECT * FROM company_list ";

                    if(!empty($_POST['compname']))
                    {
                        $compname = $_POST['compname'];
                        $query .=" WHERE LOWER(company_name) LIKE LOWER('%$compname%') AND remove = 'no'";
                    }
                    
                    if(!empty($_POST['contact']))
                    {
                        $contact = $_POST['contact'];
                        if(strpos($query, "WHERE") === false)
                        {
                            $query .= " WHERE LOWER(contact_person) LIKE LOWER('%$contact%') AND remove = 'no'";
                        }
                        else
                        {
                            $query .=" AND contact_person LIKE '%$contact%'";
                        }
                        
                    }
                    if(!empty($_POST['MOAstatus']))
                    {
                        $MOAstatus = $_POST['MOAstatus'];
                        if(strpos($query, "WHERE") === false)
                        {
                            $query .= " WHERE moa_status LIKE '$MOAstatus' AND remove = 'no'";
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
                            $query .= " WHERE company_status LIKE '$COMPstatus' AND remove = 'no'";
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
                            $query .=" WHERE date_signed BETWEEN '$signeddate' AND '$expireddate' AND date_end BETWEEN '$signeddate' AND '$expireddate' AND remove = 'no'";
                        }
                        else if(!empty(($_POST['expired'])) && !empty(($_POST['signed'])) && strpos($query, "WHERE") === true)
                        {
                            $query .=" AND date_signed BETWEEN '$signeddate' AND '$expireddate' AND date_end BETWEEN '$signeddate' AND '$expireddate'";
                        }
                        else if(!empty(($_POST['signed'])) && strpos($query, "WHERE") === false)
                        {
                            $query .= " WHERE date_signed LIKE '$signeddate' AND remove = 'no'";
                        }
                        else if(!empty(($_POST['expired'])) && strpos($query, "WHERE") === false)
                        {
                            $query .= " WHERE date_end LIKE '$expireddate' AND remove = 'no'";
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

                    if(strpos($query, "WHERE") === false)
                    {
                        $query .= " WHERE company_status != 'Inactive' AND remove = 'no'";
                    }
                    else
                    {
                        $query .="AND company_status != 'Inactive' AND remove = 'no'";
                    }
                   
                  

                    $query_run = mysqli_query($conn, $query);
                    ?>


                    <?php
        
                    if (mysqli_num_rows($query_run) > 0)
                    {
                        while ($row = mysqli_fetch_array($query_run))
                        {
                        ?>
                        <tr>
                            <td class="comp_id" style='display: none;'><?php echo $row['company_id']; ?></td>
                            <td><?php echo $row['company_name']; ?></td>
                            <td><?php echo $row['contact_person']; ?></td>
                            <td><?php echo date("M j Y", strtotime($row['date_signed'])); ?></td>
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
                            <td><?php echo $row['moa_status']; ?></td>
                            <td><?php echo $row['company_status']; ?></td>
                            <td>
                            <div class="btn-group" role="group" aria-label="Button Group">
                            <a href="#" button type="button" class = "userinfo btn btn-primary view_btn"><i class='fa fa-eye'></i></a>

                            </td>
                            </div>
                        </tr>
                        <?php
                        }
                    }
        
                    else
                    {
                        
                    }
                    ?>
        
                    </tbody>
                    </table>
                    </div>
                    </div>
                    <script>
                         $('.defunct').click(function() {
        
                             $('#defunctModal').modal('show');
        
                        })
                    </script>
                    <script>
                    $(document).ready(function() {
                        $('.view_btn').click(function(e) {
                        e.preventDefault();
        
                        var comp_id = $(this).closest('tr').find('.comp_id').text();
                        
                        $.ajax({
                        type: "POST",
                        url: "Adviser_PartnerList.php?adddata&classCode=<?php echo $code ?>",
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
        
                        $('.filter_btn').click(function(e) {
                        e.preventDefault();
                        
                        $.ajax({
                        type: "POST",
                        url: "Adviser_PartnerList.php?adddata&classCode=<?php echo $code ?>",
                        data: {
                            'filter_viewbtn': true,
                        },
                        success: function (response) {
                            console.log(response);
                            $('.company_filtering_data').html(response);
                            $('#companyFILTERModal').modal('show');
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

                    <style>
                       #Ptable 
                        {
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 14px;
                        border-collapse: collapse;
                        width: 100%;
                        min-width: 90%;
                        max-width: 90%;
                        margin-right: 20px;
                        margin-left: 20px;
                        margin-bottom: 20px;
                        }

                        #Ptable td
                        {
                        text-align: center;
                        border: 1px solid #ddd;
                       
                        /* padding: 8px; */
                        }

                        #Ptable tr:nth-of-type(even)
                        {
                        text-align: center;
                        border: 1px solid #ddd;
                        background-color: #EEEEEE;
                        /* padding: 8px; */
                        }

                        /* #company tr:nth-child(even){background-color: #f2f2f2;} */

                        #Ptable th 
                        {
                        border: 1px solid #ddd;
                        padding: 8px;
                        padding-top: 12px;
                        padding-bottom: 12px;
                        text-align: left;
                        color: black;
                        text-align: center;
                        white-space: nowrap;
                        }

                    </style>
    
            <img src="../images/pupmainlogo.png" style = "width: 130px; float:left; margin-right: 5px;">
            <p style = "font-family:'Times New Roman',serif; padding: 10px 0px 5px 0px; margin: 0; font-size: 14px;">Republic of the Philippines</p>
            <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p></b>
            <p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 14px;">OFFICE OF THE VICE PRESIDENT FOR BRANCHES AND SATELLITE CAMPUSES</p>
            <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">San Juan City Branch</h1></p></b>
            <br><hr>
            
            <table id = "Ptable">
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
        $query = "SELECT * FROM company_list ";

        if(!empty($_POST['compname']))
        {
            $compname = $_POST['compname'];
            $query .=" WHERE LOWER(company_name) LIKE LOWER('%$compname%') AND remove = 'no'";
        }
        
        if(!empty($_POST['contact']))
        {
            $contact = $_POST['contact'];
            if(strpos($query, "WHERE") === false)
            {
                $query .= " WHERE LOWER(contact_person) LIKE LOWER('%$contact%') AND remove = 'no'";
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
                $query .= " WHERE moa_status LIKE '$MOAstatus' AND remove = 'no'";
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
                $query .= " WHERE company_status LIKE '$COMPstatus' AND remove = 'no'";
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
                $query .=" WHERE date_signed BETWEEN '$signeddate' AND '$expireddate' AND date_end BETWEEN '$signeddate' AND '$expireddate' AND remove = 'no'";               
            }
            else if(!empty(($_POST['expired'])) && !empty(($_POST['signed'])) && strpos($query, "WHERE") === true)
            {
                $query .=" AND date_signed BETWEEN '$signeddate' AND '$expireddate' AND date_end BETWEEN '$signeddate' AND '$expireddate'";
            }
            else if(!empty(($_POST['signed'])) && strpos($query, "WHERE") === false)
            {
                $query .= " WHERE date_signed LIKE '$signeddate' AND remove = 'no'";
            }
            else if(!empty(($_POST['expired'])) && strpos($query, "WHERE") === false)
            {
                $query .= " WHERE date_end LIKE '$expireddate' AND remove = 'no'";
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
        if(strpos($query, "WHERE") === false)
        {
            $query .= " WHERE company_status != 'Inactive' AND remove = 'no'";
        }
        else
        {
            $query .="AND company_status != 'Inactive'";
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
                document.getElementById('filter_view').style.maxWidth = "80%";

                
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
                document.getElementById('filter_view').style.maxWidth = "";
    
            }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>
        
        </body>
        </html> 
        <?php
    }
  
   
}
