<?php
include "../db_conn.php";


if(isset($_REQUEST['Home']))
{
    ?>
<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Print Partner Company List</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">



    <link rel = "stylesheet" href = "../css/coordinator_dashdocu.css">

    <!-- TABLE STYLES -->
    <style>
        #company 
        {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        border-collapse: collapse;
        /*width: 100%;*/
        margin-right: 20px;
        margin-left: 20px;
        margin-bottom: 20px;
        }

        #company td
        {
        text-align: center;
        border: 1px solid #ddd;
        /* padding: 8px; */
        }

        #company tr:nth-of-type(even)
        {
        text-align: center;
        border: 1px solid #ddd;
        background-color: #EEEEEE;
        /* padding: 8px; */
        }

        /* #company tr:nth-child(even){background-color: #f2f2f2;} */

        #company th 
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
    </style>

</head>

<body>
        <!-- Heading for printing -->
        <img src="../images/pupmainlogo.png" style = "width: 130px; float:left; margin-right: 5px;">
        <p style = "font-family:'Times New Roman',serif; padding: 10px 0px 5px 0px; margin: 0; font-size: 14px;">Republic of the Philippines</p>
        <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p></b>
        <p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 14px;">OFFICE OF THE VICE PRESIDENT FOR BRANCHES AND SATELLITE CAMPUSES</p>
        <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">San Juan City Branch</p></b>
        
        <br><hr><br>


            <h1 class="font-weight-bold" style = "margin-right: 20px; margin-left: 20px;">Partner Company List
            <button type="button" id = "PrintButton" class="userinfo btn btn-primary" style="float:right;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;Advanced Filter</b> </button></h1>
            <hr class="solid">
            <!-- <button type="button" class="btn btn-primary"><b>Print List</b> </button> -->
            <br>
            

        <!-- Modal VIEW DATA-->
        <div class="modal fade" id="companyVIEWModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
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
                    <form action = "Coordinator_PrintList.php?Filter" method = "POST">
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
                                    <option value = "Inactive">Inactive</option>
                                    <option value = "Defunct">Defunct</option>
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
        
        <center>
            <button  type="button" class="btn btn-primary" id = "PrintButton" name = "print_checkbox" onclick="PrintPage()">PRINT</button>
            <a id = "PrintButton"  type="button" class="btn btn-primary" target = "_self" onclick = "window.close('Coordinator_PrintList.php');">BACK</a>

        </center>
        <br>

        <table id = "company">
            <thead style = "background-color: #EEEEEE;">
            <tr>
                <th style='display: none;'></th>
                <th scope = "col">Company Name</th>
                <th scope = "col">Contact Person</th>
                <th scope = "col">Company Address</th>
                <th scope = "col">Contact Number</th>
                <th scope = "col">MOA Status</th>
                <th scope = "col">Date Signed</th>
                <th scope = "col">Years Active</th>
                <th scope = "col">Company Status</th>
                
            </tr>
            </thead>

            <tbody>
                <!-- changes starts here -->
            <?php
            $query = "SELECT * FROM company_list";
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
                    <td><?php echo $row['date_signed']; ?></td>
                    <td><?php echo $row['years_active']; ?></td>
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

            <style>
                    @media print{
                        #PrintButton{
                            display: none;
                        }

                        @page{
                            size: A4;
                        }
                    }

                    .code{
                        color: red;
                    }
            </style>


            <!-- <script>
            $(document).ready(function(){
            $('#company').DataTable({
                lengthMenu:[
                [10, 15, 20, 30, 50, -1],
                [10, 15, 20, 30, 50, 'All'],
                ],
            });
            });
            </script> -->

                </div> <!-- end -->

</body>
    <script type="text/javascript">
        function PrintPage() {
            window.print();
        }
    </script>
</html>
<?php
}

if(isset($_REQUEST['Filter']))
{
    ?>
    <!DOCTYPE html>
    <html lang="en" style = "height: auto;">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <title>Print Partner Company List</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
    
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
        <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
    
    
        <link rel = "stylesheet" href = "../css/coordinator_dashdocu.css">
    
        <!-- TABLE STYLES -->
        <style>
            #company 
            {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            border-collapse: collapse;
            width: 97%;
            min-width: 98%;
            margin-right: 20px;
            margin-left: 20px;
            margin-bottom: 20px;
            }
    
            #company td
            {
            text-align: center;
            border: 1px solid #ddd;
            /* padding: 8px; */
            }
    
            #company tr:nth-of-type(even)
            {
            text-align: center;
            border: 1px solid #ddd;
            background-color: #EEEEEE;
            /* padding: 8px; */
            }
    
            /* #company tr:nth-child(even){background-color: #f2f2f2;} */
    
            #company th 
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
        </style>
    
    </head>
    
    <body>
            <!-- Heading for printing -->
            <img src="../images/pupmainlogo.png" style = "width: 130px; float:left; margin-right: 5px;">
            <p style = "font-family:'Times New Roman',serif; padding: 10px 0px 5px 0px; margin: 0; font-size: 14px;">Republic of the Philippines</p>
            <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p></b>
            <p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 14px;">OFFICE OF THE VICE PRESIDENT FOR BRANCHES AND SATELLITE CAMPUSES</p>
            <b><p style = "font-family:'Times New Roman',serif; margin: 0; font-size: 18px;">San Juan City Branch</p></b>
            
            <br><hr><br>
    
    
                <h1 class="font-weight-bold" style = "margin-right: 20px; margin-left: 20px;">Partner Company List
                <button type="button" id = "PrintButton" class="userinfo btn btn-primary" style="float:right;" data-toggle="modal" data-target="#filterModal"><b><i class="fa fa-filter"></i>&emsp;Advanced Filter</b> </button></h1>
                <hr class="solid">
                <!-- <button type="button" class="btn btn-primary"><b>Print List</b> </button> -->
                <br>
                
    
            <!-- Modal VIEW DATA-->
            <div class="modal fade" id="companyVIEWModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>PARTNER COMPANY INFORMATION</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                        <form action = "Coordinator_PrintList.php?Filter" method = "POST">
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
                                        <option value = "Inactive">Inactive</option>
                                        <option value = "Defunct">Defunct</option>
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
            
            <center>
                <button  type="button" class="btn btn-primary" id = "PrintButton" name = "print_checkbox" onclick="PrintPage()">PRINT</button>
                <a id = "PrintButton"  type="button" class="btn btn-primary" target = "_self" onclick = "window.close('Coordinator_PrintList.php');">BACK</a>
    
            </center>
            <br>
    
            <table id = "company">
                <thead style = "background-color: #EEEEEE;">
                <tr>
                    <th style='display: none;'></th>
                    <th scope = "col">Company Name</th>
                    <th scope = "col">Contact Person</th>
                    <th scope = "col">Company Address</th>
                    <th scope = "col">Contact Number</th>
                    <th scope = "col">MOA Status</th>
                    <th scope = "col">Date Signed</th>
                    <th scope = "col">Years Active</th>
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
                 if($_POST['signed'] === '0000-00-00 00:00:00' || !empty($_POST['signed']))
                 {
                     $signeddate = date('Y-m-d', strtotime($_POST['signed']));
                     if(strpos($query, "WHERE") === false)
                     {
                         $query .= " WHERE date_signed LIKE '$signeddate'";
                     }
                     else
                     {
                         $query .=" AND date_signed LIKE '$signeddate' ";
                     }
                     
                 }
                 if($_POST['expired'] === '0000-00-00 00:00:00' || !empty($_POST['expired']))
                 {
                     $expireddate = date('Y-m-d', strtotime($_POST['expired']));
                     if(strpos($query, "WHERE") === false)
                     {
                         $query .= " WHERE date_end LIKE '$expireddate'";
                     }
                     else
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
                        <td><?php echo $row['contact_person']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['contact_num']; ?></td>
                        <td><?php echo $row['moa_status']; ?></td>
                        <td><?php echo $row['date_signed']; ?></td>
                        <td><?php echo $row['years_active']; ?></td>
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
    
                <style>
                        @media print{
                            #PrintButton{
                                display: none;
                            }
    
                            @page{
                                size: A4;
                            }
                        }
    
                        .code{
                            color: red;
                        }
                </style>
    
    
                <!-- <script>
                $(document).ready(function(){
                $('#company').DataTable({
                    lengthMenu:[
                    [10, 15, 20, 30, 50, -1],
                    [10, 15, 20, 30, 50, 'All'],
                    ],
                });
                });
                </script> -->
    
                    </div> <!-- end -->
    
    </body>
        <script type="text/javascript">
            function PrintPage() {
                window.print();
            }
        </script>
    </html>
    <?php
}
