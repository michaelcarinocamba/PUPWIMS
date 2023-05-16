<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['coordinator']))
        {
            header("Location: Coordinator_Login.php?LoginFirst");
        }

        $coordnum = $_SESSION['coordinator'];
        $name = $_SESSION['name'];

if(isset($_REQUEST['active']))
{
    




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

        body 
        {
        font-family: Arial, Helvetica, sans-serif;
        }

        hr.solid 
        {
        border-top: 3px solid #bbb;
        }
    </style>

</head>

<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

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
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Active Companies </h1>
            <hr class="solid">
            <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Coordinator_Dashboard.php?classCode=<?php echo $coordnum; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a> <br><br>
            <button type="button" class="userinfo btn btn-danger" style="background-color:maroon; margin-left: 1%; float:right;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;ADVANCED FILTER</b> </button>
            <br><br><br>
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
                [10, 15, 20, 30, 50, -1],
                [10, 15, 20, 30, 50, 'All'],
                ],
            });
            });
            </script>

                </div> <!-- end -->
        </div> 
</div> 

</body>
</html>   
<?php
}
}

if(isset($_REQUEST['inactive']))
{
    


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

        body 
        {
        font-family: Arial, Helvetica, sans-serif;
        }

        hr.solid 
        {
        border-top: 3px solid #bbb;
        }
    </style>

</head>

<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

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
                    
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Inactive Companies </h1>
            <hr class="solid">
            <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Coordinator_Dashboard.php?classCode=<?php echo $coordnum; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a><br><br>
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
                                            <select name = "course[]" id = "edit_course" class = "form-control" multiple required/>
                                            
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
                                            <select name = "moasign" id = "edit_moasign" class = "form-control" onchange = "editenableMOA(this)" required />
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
                                            <select name = "dtipermit" id = "edit_dtipermit" class = "form-control" onchange = "editenableDTI(this)" required/>
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
                                            <select name = "secpermit" id = "edit_secpermit" class = "form-control" onchange = "editenableSEC(this)" required />
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
                                            <select name = "birpermit" id = "edit_birpermit" class = "form-control" onchange="editenableBIR(this)" required/>
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
                [10, 15, 20, 30, 50, -1],
                [10, 15, 20, 30, 50, 'All'],
                ],
            });
            });
            </script>

                </div> <!-- end -->
        </div> 
</div> 

</body>
</html>   
<?php
}
}

if(isset($_REQUEST['defunct']))
{
    

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

        body 
        {
        font-family: Arial, Helvetica, sans-serif;
        }

        hr.solid 
        {
        border-top: 3px solid #bbb;
        }
    </style>

</head>

<body>
<div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

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
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Expiring Companies </h1>
            <hr class="solid">
            <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Coordinator_Dashboard.php?classCode=<?php echo $coordnum; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a><br><br>
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
            $query = "SELECT * FROM company_list WHERE company_status = 'Expiring'" ;
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
                    
                    <td style="text-align:center;"><?php echo $row['date_signed']; ?></td>
                    <td style="text-align:center;"><?php echo $row['date_end']; ?></td>
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

</body>
</html>   
<?php
}
}

if(isset($_REQUEST['enrolled']))
{
    ?>

    <!DOCTYPE html>
    <html lang="en" style = "height: auto;">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Student</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
        <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel = "stylesheet" href = "../css/coordinator_recordlist.css">

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
            width: 100%;
            }

            #table td, #table th 
            {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            }

            #table tr:nth-child(even){background-color: #f2f2f2;}

            #table th 
            {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: white;
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
        </style>
        </head>

    <body>
    <div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

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

        <!--MAIN CONTENT -->
        <div class = "main">

            <!--TOP NAV-->
            <div class = "topnavbar" style="z-index:1;">
            <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
                
                <div class="topnavbar-right" id="topnav_right">
                    <a target = "_self" href="../Logout.php?Logout=<?php echo $coordnum ?>" > 
                        Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                    </a>
                </div>
            </div> <!--end of topnavbar-->

            <br><br><br><br>
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
            <?php
           
        ?>

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
                        title: 'Student Account Successfully Added.'
                    })
                    </script>
            <?php
                } 
                if(isset($_REQUEST['CSVunsuccess1']))
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
                        if(isset($_REQUEST['CSVunsuccess2']))
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
                        if(isset($_REQUEST['CSVunsuccess3']))
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
                        if(isset($_REQUEST['updatesuccess']))
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
                            title: 'Update successfully'
                        })
                        </script>
                        <?php
                            }
                            if(isset($_REQUEST['updateunsuccess']))
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
                                title: 'Update Unsuccess'
                            })
                            </script>
                            <?php } ?>
                    
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Internship Record (Enrolled)  
               </h1>
            
            <hr style="background-color:maroon; border-width:2px;">
            <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Coordinator_Dashboard.php?classCode=<?php echo $coordnum; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a>
             <br>


                <!-- Modal EDIT DATA -->          
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"  role="document">
        <div class="modal-content">

        

                <div class="modal-header">
                    <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                   
                </div>

                
                    <div class="modal-body">
                        <form action = "Coordinator_Record_Student.php?adddata" method = "POST">
                        <input type = "hidden" name = "edit_id" id = "edit_id">
                        <div class = "form-row">
                        <div class="form-group col-md-3">
                            <label for = ""><b>Student Number: </b></label>
                            <input type="text" name = "studentnum" id = "edit_studentnum" class = "form-control" placeholder = "Enter Student Number" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label for = ""><b>Name: </b></label>
                            <input type="text" name = "name" id = "edit_name" class = "form-control" placeholder = "Enter Name" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for = ""><b>Email Address: </b></label>
                            <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" placeholder = "Enter Email Address" required>
                        </div>

                            
                        </div>
                        

                    </div><!--end of modal body -->

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
                
            <br>
                
           
            <!-- changes -->
            <?php 
                $checkTeaching = false;
                $data_query = mysqli_query($conn, "SELECT * FROM adviser_info ORDER BY faculty_id ASC");
                
                if(mysqli_num_rows($data_query) > 0)
                {
                    $checkTeaching = true;

                    if($checkTeaching == true)
                    {
                        $query = "SELECT t1.* FROM student_info t1 WHERE EXISTS(SELECT * FROM student_record t2 WHERE t2.studentnum = t1.studentnum)";
                        $result = mysqli_query($conn, $query);  
                        ?>
                        <div class="card">
                            <div class="card-body">
                            <div class="table table-responsive">
                            <table id="table">
                                    <thead>
                                        <tr>
                                            <th style='display: none;'></th>
                                        <th>Student Number</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            if(mysqli_num_rows($result) > 0)
                                            {
                                                while($row = mysqli_fetch_array($result))
                                                {
                                                    
                                                    
                                                    
                                                    ?>
                                                    <tr>
                                                        <td class="stud_id" style='display: none;'><?php echo $row['student_id']; ?></td>
                                                        <td><?php echo $row['studentnum']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td> 
                                                        <td>
                                                        <div class="btn-group" role="group" aria-label="Button Group">
                                                        
                                                            <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn btn-sm" data-bs-toggle='modal' data-bs-target='#editStudentModal'><i class='fa fa-edit'></i></a>
                                                            
                                                        </td>
                                                        
                                                    </tr>
                                                    <?php }
                                                    ?>

                                                    
                                                    <?php
                                                }
                                            
                                            else{
                                                echo "<h5> NO RECORD FOUND!</h5>";
                                            }
                                            ?>
                                    </tbody>
                                </table>

                            </div>
                            </div>
                        </div>
                        <?php
                    }

                }
                else
                {
                    $str = "
                <br>
                <div class='card-deck'>
                    <div class='card style'>
                        <div class='card-body text-center'>
                        <br>
                        <h4> There's no record of adviser/s yet!</h4>
                        </div>
                    </div>
                </div>
                ";
                echo $str;
                } //end of if 
                                
            ?>
            
            <br><br>
                        
        </div> <!--End of main -->

    </div> <!--End of container-fluid -->
    <script>
    $(document).ready(function() {
        

        $('.edit_btn').click(function(e) {
        e.preventDefault();

        var stud_id = $(this).closest('tr').find('.stud_id').text();
        
        $.ajax({
        type: "POST",
        url: "Coordinator_Record_Student.php?adddata",
        data: {
            'checking_editbtn': true,
            'student_id': stud_id,
        },
        success: function (response) {
            $.each(response,function (key, value){
            $('#edit_id').val(value['student_id']);
            $('#edit_studentnum').val(value['studentnum']);
            $('#edit_name').val(value['name']);
            $('#edit_emailadd').val(value['email']);
            $('#edit_company').val(value['company']);
            $('#edit_psychological').val(value['psychological']);
            $('#edit_medical').val(value['medical']);
            $('#edit_status').val(value['status']);
            $('#edit_DTR').val(value['dtr_option']);
            
            });
            $('#editStudentModal').modal('show');
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
                ['All', 10, 15, 20, 30, 50 ],
                ],
            });
            });
    </script>

        </div><!--end of main -->

        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    </body>
    </html>
<?php
}



if(isset($_REQUEST['unenrolled']))
{
    ?>

    <!DOCTYPE html>
    <html lang="en" style = "height: auto;">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Student</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
        <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel = "stylesheet" href = "../css/coordinator_recordlist.css">

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
            width: 100%;
            }

            #table td, #table th 
            {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            }

            #table tr:nth-child(even){background-color: #f2f2f2;}

            #table th 
            {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: white;
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
        </style>
        </head>

    <body>
    <div class = "container-fluid" style = "margin-left: -15px"> <!--CSS IS FIX IN BOOTSTRAP-->

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

        <!--MAIN CONTENT -->
        <div class = "main">

            <!--TOP NAV-->
            <div class = "topnavbar" style="z-index:1;">
            <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
            
                <div class="topnavbar-right" id="topnav_right">
                    <a target = "_self" href="../Logout.php?Logout=<?php echo $coordnum ?>" > 
                        Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                    </a>
                </div>
            </div> <!--end of topnavbar-->

            <br><br><br><br>
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
            <?php
           
        ?>

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
                        title: 'Student Account Successfully Added.'
                    })
                    </script>
            <?php
                } 
                if(isset($_REQUEST['CSVunsuccess1']))
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
                        if(isset($_REQUEST['CSVunsuccess2']))
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
                        if(isset($_REQUEST['CSVunsuccess3']))
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
                        if(isset($_REQUEST['updatesuccess']))
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
                            title: 'Update successfully'
                        })
                        </script>
                        <?php
                            }
                            if(isset($_REQUEST['updateunsuccess']))
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
                                title: 'Update Unsuccess'
                            })
                            </script>
                            <?php } ?>
                    
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Internship Record (Unenrolled)  
               </h1>
            
            <hr style="background-color:maroon; border-width:2px;">
            <a class="btn btn-primary" style = "font-size: 15px; color:maroon; background-color:maroon; color:white; float: right; border-radius: 20px;" href="Coordinator_Dashboard.php?classCode=<?php echo $coordnum; ?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp; Back</a>
             <br>


                <!-- Modal EDIT DATA -->          
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"  role="document">
        <div class="modal-content">

        

                <div class="modal-header">
                    <h5 class="modal-title"><b>STUDENT INFORMATION</b></h5>
                   
                </div>

                
                    <div class="modal-body">
                        <form action = "Coordinator_Record_Student.php?adddata" method = "POST">
                        <input type = "hidden" name = "edit_id" id = "edit_id">
                        <div class = "form-row">
                        <div class="form-group col-md-3">
                            <label for = ""><b>Student Number: </b></label>
                            <input type="text" name = "studentnum" id = "edit_studentnum" class = "form-control" placeholder = "Enter Student Number" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label for = ""><b>Name: </b></label>
                            <input type="text" name = "name" id = "edit_name" class = "form-control" placeholder = "Enter Name" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for = ""><b>Email Address: </b></label>
                            <input type="text" name = "emailadd" id = "edit_emailadd" class = "form-control" placeholder = "Enter Email Address" required>
                        </div>

                            
                        </div>
                        

                    </div><!--end of modal body -->

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name = "update_student" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
                
            <br>
                
           
            <!-- changes -->
            <?php 
                $checkTeaching = false;
                $data_query = mysqli_query($conn, "SELECT * FROM adviser_info ORDER BY faculty_id ASC");
                
                if(mysqli_num_rows($data_query) > 0)
                {
                    $checkTeaching = true;

                    if($checkTeaching == true)
                    {
                        $query = "SELECT t1.* FROM student_info t1 WHERE NOT EXISTS(SELECT * FROM student_record t2 WHERE t2.studentnum = t1.studentnum)";
                        $result = mysqli_query($conn, $query);  
                        ?>
                        <div class="card">
                            <div class="card-body">
                            <div class="table table-responsive">
                            <table id="table">
                                    <thead>
                                        <tr>
                                            <th style='display: none;'></th>
                                        <th>Student Number</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            if(mysqli_num_rows($result) > 0)
                                            {
                                                while($row = mysqli_fetch_array($result))
                                                {
                                                    
                                                    
                                                    
                                                    ?>
                                                    <tr>
                                                        <td class="stud_id" style='display: none;'><?php echo $row['student_id']; ?></td>
                                                        <td><?php echo $row['studentnum']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td> 
                                                        <td>
                                                        <div class="btn-group" role="group" aria-label="Button Group">
                                                        
                                                            <a href="#" button type="button" class = "userinfo btn btn-secondary edit_btn btn-sm" data-bs-toggle='modal' data-bs-target='#editStudentModal'><i class='fa fa-edit'></i></a>
                                                            
                                                        </td>
                                                        
                                                    </tr>
                                                    <?php }
                                                    ?>

                                                    
                                                    <?php
                                                }
                                            
                                            else{
                                                echo "<h5> NO RECORD FOUND!</h5>";
                                            }
                                            ?>
                                    </tbody>
                                </table>

                            </div>
                            </div>
                        </div>
                        <?php
                    }

                }
                else
                {
                    $str = "
                <br>
                <div class='card-deck'>
                    <div class='card style'>
                        <div class='card-body text-center'>
                        <br>
                        <h4> There's no record of adviser/s yet!</h4>
                        </div>
                    </div>
                </div>
                ";
                echo $str;
                } //end of if 
                                
            ?>
            
            <br><br>
                        
        </div> <!--End of main -->

    </div> <!--End of container-fluid -->
    <script>
    $(document).ready(function() {
        

        $('.edit_btn').click(function(e) {
        e.preventDefault();

        var stud_id = $(this).closest('tr').find('.stud_id').text();
        
        $.ajax({
        type: "POST",
        url: "Coordinator_Record_Student.php?adddata",
        data: {
            'checking_editbtn': true,
            'student_id': stud_id,
        },
        success: function (response) {
            $.each(response,function (key, value){
            $('#edit_id').val(value['student_id']);
            $('#edit_studentnum').val(value['studentnum']);
            $('#edit_name').val(value['name']);
            $('#edit_emailadd').val(value['email']);
            $('#edit_company').val(value['company']);
            $('#edit_psychological').val(value['psychological']);
            $('#edit_medical').val(value['medical']);
            $('#edit_status').val(value['status']);
            $('#edit_DTR').val(value['dtr_option']);
            
            });
            $('#editStudentModal').modal('show');
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
                ['All', 10, 15, 20, 30, 50 ],
                ],
            });
            });
    </script>

        </div><!--end of main -->

        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    </body>
    </html>
<?php
}