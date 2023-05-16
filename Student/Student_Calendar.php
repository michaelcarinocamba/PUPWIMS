<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['studnum']))
    {
        header("Location: Student_Login.php?LoginFirst");
    }
$code = $_REQUEST['classCode'];
$studentnum = $_SESSION['studnum'];

?>

<!DOCTYPE html>
<html lang="en" style = "height: auto;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Student Scheduled Mentoring</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

    <!-- for logout icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


 
    
    <link rel = "stylesheet" href = "../css/student_dashdocu.css">
    
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
        #schedule
        {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #schedule td, #schedule th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #schedule tr:nth-child(even){background-color: #f2f2f2;}

        #schedule th 
        {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: maroon;
        color: white;
        text-align: center;
        white-space: nowrap;
        }

        #schedule td 
        {
            white-space: break-spaces;
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
<div class = "container-fluid" style = "margin-left: -15px"> 
 <!--SIDE BAR-->
 <div id="sidebar" class="sidebar" style="background-image: url('../images/Background1.png');background-repeat: round;"> <br><br>
 <center>
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
        <div class = "topnavbar" style="z-index:1;">
        <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">

            <div class="topnavbar-right" id="topnav_right">
                <a target = "_self" href="../Logout.php?Logout=<?php echo $studentnum ?>" > 
                Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                </a>
            </div>
        </div> <!--end of topnavbar-->

       

    <div class = "sched_viewing_data">
        <br><br><br><br>
        <script>
            const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
            function handleScreenSizeChange(tabletSize) {
                if (tabletSize.matches) 
                {
                    document.getElementById('topnav_right').classList.remove('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "12px";
                } 
                else
                {
                    document.getElementById('topnav_right').classList.add('topnavbar-right');
                    document.getElementById('topnav_right').style.fontSize = "13.5px";
                }
            }

            tabletSize.addListener(handleScreenSizeChange);
            handleScreenSizeChange(tabletSize);
        </script>
        <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;"> Scheduled Mentoring / Monitoring</h2>
        <hr style="background-color:maroon;border-width:2px;">
        <button type="button" class="btn btn-danger" style="background-color:maroon; float:right;" data-toggle="modal" data-target="#historyModal"><i class="fa fa-history"></i> History </button><br><br>
 <!-- History -->
        <div class="modal fade" id="historyModal">
                    <div class="modal-dialog modal-lg" style="min-width:80%;">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title"><b>MENTORING SCHEDULE HISTORY</b> </p> 
                                
                            </div>

                        
                                <div class="modal-body">
                                <div class="table table-responsive">
                                <table id ="schedule">
                                    <thead>
                                    <tr>
                    
                                        <th scope = "col">Title</th>
                                        <th scope = "col">Type</th>
                                        <th scope = "col">Adviser Notes</th>
                                        <th scope = "col">Scheduled Date</th>
                                        <th scope = "col">Start Time</th>
                                        <th scope = "col">End Time</th>
                                        <th scope = "col">Company</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <!--Changes -->
                                        <?php
                                           $query = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND class_code = '$code'");
                                           while($row1 = mysqli_fetch_array($query)){
                                               $comp = $row1['company'];
                                           }
                                           $data_query = mysqli_query($conn, "SELECT * FROM adviser_schedule WHERE company = '$comp' AND class_code = '$code' ORDER BY id DESC");
                                           if(mysqli_num_rows($data_query) > 0)
                                           {
                                               ?> 
                                            
                                               <?php
                                               while($row = mysqli_fetch_array($data_query))
                                               {
                                                    
                                                    $id = $row['id'];
                                                    $sched_end = $row['end_time'];
                                                    $duedate = $row['date'];
                                                    date_default_timezone_set('Asia/Manila');
                                                    $currentday = date('Y-m-d');
                                                    $currenttime = date('H:i:s A');
                                                    $comp = $row['company'];
                                                    $title = $row['title'];
                                                    $instruction = $row['instruction'];
                                                    $company = $row['company'];
                                                    $start_time = $row['start_time'];
                                                    $date = $row['date'];
                                                    $formatted_date = date("Y-m-d", strtotime($row['date']));
                                                    $formatted_start = date("H:i:s A", strtotime($row['start_time']));
                                                    $formatted_end = date("H:i:s A", strtotime($row['end_time']));
                                                    
                                                
                                                    if($currentday > $date)
                                                    {
                                                    $stud_query = mysqli_query($conn, "SELECT * FROM student_record WHERE company = '$comp' AND class_code = '$code'");
                                                    
                                                    ?>
                                                    <tr>
                                                    <tr>
                                                        <td class="sched_id" style ="display : none;"><?php echo $row['id']; ?></td>
                                                        <td style = "background-color:white;"><?php echo $row['title']; ?></td>
                                                        <td style = "background-color:white;"><?php echo $row['type']; ?></td>
                                                        <td style = "background-color:white;"><?php echo $row['instruction']; ?></td>
                                                        <td style = "background-color:white;"><?php echo date("M j Y", strtotime($row['date'])); ?></td>
                                                        <td style = "background-color:white;"><?php echo date("h:i A", strtotime($row['start_time'])); ?></td>
                                                        <td style = "background-color:white;"><?php echo date("h:i A", strtotime($row['end_time'])); ?></td>
                                                        <td style = "background-color:white;"><?php echo $company; ?></td>
                                                    </tr>
                                                    <?php
                                                }

                                            }
                                            }
                                            
                                            
                                            ?>
                                    </tbody>
                                </table>
                                </div>
                                </div> <!--end of modal-body-->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

        <div class="card" style = "border-radius:5px 5px 0 0;">
        <div class="card-body">
        <div class="table table-responsive">

        <table id ="schedule">
            <thead>
                <tr>
                    
                    <th scope = "col" style="border-radius:5px 0 0 0;">Title</th>
                    <th scope = "col">Type</th>
                    <th scope = "col">Adviser Notes</th>
                    <th scope = "col">Scheduled Date</th>
                    <th scope = "col">Start Time</th>
                    <th scope = "col">End Time</th>
                    <th scope = "col" style="border-radius:0 5px 0 0;">Company</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    $query = mysqli_query($conn, "SELECT * FROM student_record WHERE studentnum = '$studentnum' AND class_code = '$code'");
                    while($row1 = mysqli_fetch_array($query)){
                        $comp = $row1['company'];
                    }
                    $data_query = mysqli_query($conn, "SELECT * FROM adviser_schedule WHERE company = '$comp' AND class_code = '$code' ORDER BY id DESC");
                    if(mysqli_num_rows($data_query) > 0)
                    {
                        ?> 
                        
                        <?php
                        while($row = mysqli_fetch_array($data_query))
                        {
                            $id = $row['id'];
                            $company =$row['company'];
                            $sched_end = $row['end_time'];
                            $duedate = $row['date'];
                            date_default_timezone_set('Asia/Manila');
                            $currentday = date('Y-m-d');
                            $currenttime = date('H:i:s A');
                            $date = $row['date'];
                            $formatted_start = date("H:i:s A", strtotime($row['start_time']));
                            $formatted_end = date("H:i:s A", strtotime($row['end_time']));
                            $formatted_date = date("Y-m-d", strtotime($row['date']));
                            if($currentday <= $date )
                            {
                                ?>
                                    <tr>
                                        <td class="sched_id" style ="display : none;"><?php echo $row['id']; ?></td>
                                        <td style = "background-color:white;"><?php echo $row['title']; ?></td>
                                        <td style = "background-color:white;"><?php echo $row['type']; ?></td>
                                        <td style = "background-color:white;"><?php echo $row['instruction']; ?></td>
                                        <td style = "background-color:white;"><?php echo date("M j Y", strtotime($row['date'])); ?></td>
                                        <td style = "background-color:white;"><?php echo date("h:i A", strtotime($row['start_time'])); ?></td>
                                        <td style = "background-color:white;"><?php echo date("h:i A", strtotime($row['end_time'])); ?></td>
                                        <td style = "background-color:white;"><?php echo $company; ?></td>
                                    </tr>

                    <?php   }
                           
                        }
                        
                    }
                    
                    else { ?>
                        <div class="alert alert-success" role="alert">
                        You do not have any Scheduled Meeting yet
                        </div>
                        
                        <tr>
                                <td style ="display : none;"></td>
                                <td style = "background-color:white;"></td>
                                <td style = "background-color:white;"></td>
                                <td style = "background-color:white;"></td>
                                <td style = "background-color:white;"></td>
                                <td style = "background-color:white;"></td>
                                <td style = "background-color:white;"></td>
                                <td style = "background-color:white;"></td>
                            </tr><?php
                    }?>
            </tbody>
        </table>
</div>
        </div>
    </div>   
    </div>

    </div>
</div><!--end of main --> 


</body>
</html>
