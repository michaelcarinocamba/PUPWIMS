<?php
session_start();
include "../db_conn.php";
if(!isset($_SESSION['coordinator']))
{
    header("Location: Coordinator_Login.php?LoginFirst");
}
$coordnum = $_SESSION['coordinator'];
$query=mysqli_query($conn, "SELECT * FROM student_evaluation GROUP BY company_name DESC");



?>

<!DOCTYPE html>
      <html lang="en" style = "height: auto;">
      <head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
          <title>Company Evaluation</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">
         
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
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
      
              #company 
              {
              font-family: Arial, Helvetica, sans-serif;
              border-collapse: collapse;
              width: 100%;
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
              background-color: maroon;
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
          
          <div class = "main">
              <!--TOP NAV-->
              <div class = "topnavbar">
              <img src="../images/maroon-bg.png" alt="background" class = "sidebar_bg">
                  <div class="topnavbar-right" id="topnav_right">
                  <a target = "_self" href="../Logout.php?Logout=<?php echo $coordnum ?>" > 
                          Log out &nbsp;<i class = "fa fa-sign-out fa-1x"></i>
                      </a>
                  </div>
              </div> <!--end of topnavbar-->
      
              <br><br><br>
             

              <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">Company Evaluation </h1>

              <hr class="solid">
              <button type="button" class="btn btn-primary" style="float:right;background-color:maroon;" data-toggle="modal" data-target="#StudentCommentModal"> <b><i class="fa fa-eye"></i> View Student Comment</b> </button>
            <br><br>
              <!-- BODY -->
              <div>
                <form method = "POST">
                    <center>
                <select name = "company"  style="width:300px;">
                <option value = "--Select Company--">--Select Company--</option>
                    <?php
                    while($row = mysqli_fetch_array($query))
                    {
                        $company = $row['company_name'];
                        
                        echo "<option value='$company'>$company</option>";
                        
                    }
                    ?>
                 </select>
                 <button type="submit" class="button" name="view" style="Background-color:maroon;" class="btn btn-primary">View Graph </button>
                </center>
                </form>
                
            </div>

            <?php
            if(isset($_POST['view']))
            {
                $company = $_POST['company'];
                $evaluation1_1 = mysqli_query($conn, "SELECT answer1 FROM student_evaluation WHERE company_name = '$company' AND answer1 = '4'");
                $get_answer1_1 = mysqli_num_rows($evaluation1_1);
    
                $evaluation1_2 = mysqli_query($conn, "SELECT answer1 FROM student_evaluation WHERE company_name = '$company' AND answer1 = '3'");
                $get_answer1_2 = mysqli_num_rows($evaluation1_2);
    
                $evaluation1_3 = mysqli_query($conn, "SELECT answer1 FROM student_evaluation WHERE company_name = '$company' AND answer1 = '2'");
                $get_answer1_3 = mysqli_num_rows($evaluation1_3);
    
                $evaluation1_4 = mysqli_query($conn, "SELECT answer1 FROM student_evaluation WHERE company_name = '$company' AND answer1 = '1'");
                $get_answer1_4 = mysqli_num_rows($evaluation1_4);
    
                //Question Number 2
                $evaluation2_1 = mysqli_query($conn, "SELECT answer2 FROM student_evaluation WHERE company_name = '$company' AND answer2 = '4'");
                $get_answer2_1 = mysqli_num_rows($evaluation2_1);
    
                $evaluation2_2 = mysqli_query($conn, "SELECT answer2 FROM student_evaluation WHERE company_name = '$company' AND answer2 = '3'");
                $get_answer2_2 = mysqli_num_rows($evaluation2_2);
    
                $evaluation2_3 = mysqli_query($conn, "SELECT answer2 FROM student_evaluation WHERE company_name = '$company' AND answer2 = '2'");
                $get_answer2_3 = mysqli_num_rows($evaluation2_3);
    
                $evaluation2_4 = mysqli_query($conn, "SELECT answer2 FROM student_evaluation WHERE company_name = '$company' AND answer2 = '1'");
                $get_answer2_4 = mysqli_num_rows($evaluation2_4);
    
                //Question Number 3
                $evaluation3_1 = mysqli_query($conn, "SELECT answer3 FROM student_evaluation WHERE company_name = '$company' AND answer3 = '4'");
                $get_answer3_1 = mysqli_num_rows($evaluation3_1);
    
                $evaluation3_2 = mysqli_query($conn, "SELECT answer3 FROM student_evaluation WHERE company_name = '$company' AND answer3 = '3'");
                $get_answer3_2 = mysqli_num_rows($evaluation3_2);
    
                $evaluation3_3 = mysqli_query($conn, "SELECT answer3 FROM student_evaluation WHERE company_name = '$company' AND answer3 = '2'");
                $get_answer3_3 = mysqli_num_rows($evaluation3_3);
    
                $evaluation3_4 = mysqli_query($conn, "SELECT answer3 FROM student_evaluation WHERE company_name = '$company' AND answer3 = '1'");
                $get_answer3_4 = mysqli_num_rows($evaluation3_4);
    
                //Question Number 4
                $evaluation4_1 = mysqli_query($conn, "SELECT answer4 FROM student_evaluation WHERE company_name = '$company' AND answer4 = '4'");
                $get_answer4_1 = mysqli_num_rows($evaluation4_1);
    
                $evaluation4_2 = mysqli_query($conn, "SELECT answer4 FROM student_evaluation WHERE company_name = '$company' AND answer4 = '3'");
                $get_answer4_2 = mysqli_num_rows($evaluation4_2);
    
                $evaluation4_3 = mysqli_query($conn, "SELECT answer4 FROM student_evaluation WHERE company_name = '$company' AND answer4 = '2'");
                $get_answer4_3 = mysqli_num_rows($evaluation4_3);
    
                $evaluation4_4 = mysqli_query($conn, "SELECT answer4 FROM student_evaluation WHERE company_name = '$company' AND answer4 = '1'");
                $get_answer4_4 = mysqli_num_rows($evaluation4_4);
    
                //Question Number 5
                $evaluation5_1 = mysqli_query($conn, "SELECT answer5 FROM student_evaluation WHERE company_name = '$company' AND answer5 = '4'");
                $get_answer5_1 = mysqli_num_rows($evaluation5_1);
    
                $evaluation5_2 = mysqli_query($conn, "SELECT answer5 FROM student_evaluation WHERE company_name = '$company' AND answer5 = '3'");
                $get_answer5_2 = mysqli_num_rows($evaluation5_2);
    
                $evaluation5_3 = mysqli_query($conn, "SELECT answer5 FROM student_evaluation WHERE company_name = '$company' AND answer5 = '2'");
                $get_answer5_3 = mysqli_num_rows($evaluation5_3);
    
                $evaluation5_4 = mysqli_query($conn, "SELECT answer5 FROM student_evaluation WHERE company_name = '$company' AND answer5 = '1'");
                $get_answer5_4 = mysqli_num_rows($evaluation5_4);
    
                //Question Number 6
                $evaluation6_1 = mysqli_query($conn, "SELECT answer6 FROM student_evaluation WHERE company_name = '$company' AND answer6 = '4'");
                $get_answer6_1 = mysqli_num_rows($evaluation6_1);
    
                $evaluation6_2 = mysqli_query($conn, "SELECT answer6 FROM student_evaluation WHERE company_name = '$company' AND answer6 = '3'");
                $get_answer6_2 = mysqli_num_rows($evaluation6_2);
    
                $evaluation6_3 = mysqli_query($conn, "SELECT answer6 FROM student_evaluation WHERE company_name = '$company' AND answer6 = '2'");
                $get_answer6_3 = mysqli_num_rows($evaluation6_3);
    
                $evaluation6_4 = mysqli_query($conn, "SELECT answer6 FROM student_evaluation WHERE company_name = '$company' AND answer6 = '1'");
                $get_answer6_4 = mysqli_num_rows($evaluation6_4);
    
                //Question Number 7
                $evaluation7_1 = mysqli_query($conn, "SELECT answer7 FROM student_evaluation WHERE company_name = '$company' AND answer7 = '4'");
                $get_answer7_1 = mysqli_num_rows($evaluation7_1);
    
                $evaluation7_2 = mysqli_query($conn, "SELECT answer7 FROM student_evaluation WHERE company_name = '$company' AND answer7 = '3'");
                $get_answer7_2 = mysqli_num_rows($evaluation7_2);
    
                $evaluation7_3 = mysqli_query($conn, "SELECT answer7 FROM student_evaluation WHERE company_name = '$company' AND answer7 = '2'");
                $get_answer7_3 = mysqli_num_rows($evaluation7_3);
    
                $evaluation7_4 = mysqli_query($conn, "SELECT answer7 FROM student_evaluation WHERE company_name = '$company' AND answer7 = '1'");
                $get_answer7_4 = mysqli_num_rows($evaluation7_4);
    
                //Question Number 8
                $evaluation8_1 = mysqli_query($conn, "SELECT answer8 FROM student_evaluation WHERE company_name = '$company' AND answer8 = '4'");
                $get_answer8_1 = mysqli_num_rows($evaluation8_1);
    
                $evaluation8_2 = mysqli_query($conn, "SELECT answer8 FROM student_evaluation WHERE company_name = '$company' AND answer8 = '3'");
                $get_answer8_2 = mysqli_num_rows($evaluation8_2);
    
                $evaluation8_3 = mysqli_query($conn, "SELECT answer8 FROM student_evaluation WHERE company_name = '$company' AND answer8 = '2'");
                $get_answer8_3 = mysqli_num_rows($evaluation8_3);
    
                $evaluation8_4 = mysqli_query($conn, "SELECT answer8 FROM student_evaluation WHERE company_name = '$company' AND answer8 = '1'");
                $get_answer8_4 = mysqli_num_rows($evaluation8_4);
    
                //Question Number 9
                $evaluation9_1 = mysqli_query($conn, "SELECT answer9 FROM student_evaluation WHERE company_name = '$company' AND answer9 = '4'");
                $get_answer9_1 = mysqli_num_rows($evaluation9_1);
    
                $evaluation9_2 = mysqli_query($conn, "SELECT answer9 FROM student_evaluation WHERE company_name = '$company' AND answer9 = '3'");
                $get_answer9_2 = mysqli_num_rows($evaluation9_2);
    
                $evaluation9_3 = mysqli_query($conn, "SELECT answer9 FROM student_evaluation WHERE company_name = '$company' AND answer9 = '2'");
                $get_answer9_3 = mysqli_num_rows($evaluation9_3);
    
                $evaluation9_4 = mysqli_query($conn, "SELECT answer9 FROM student_evaluation WHERE company_name = '$company' AND answer9 = '1'");
                $get_answer9_4 = mysqli_num_rows($evaluation9_4);
    
                //Question Number 10
                $evaluation10_1 = mysqli_query($conn, "SELECT answer10 FROM student_evaluation WHERE company_name = '$company' AND answer10 = '4'");
                $get_answer10_1 = mysqli_num_rows($evaluation10_1);
    
                $evaluation10_2 = mysqli_query($conn, "SELECT answer10 FROM student_evaluation WHERE company_name = '$company' AND answer10 = '3'");
                $get_answer10_2 = mysqli_num_rows($evaluation10_2);
    
                $evaluation10_3 = mysqli_query($conn, "SELECT answer10 FROM student_evaluation WHERE company_name = '$company' AND answer10 = '2'");
                $get_answer10_3 = mysqli_num_rows($evaluation10_3);
    
                $evaluation10_4 = mysqli_query($conn, "SELECT answer10 FROM student_evaluation WHERE company_name = '$company' AND answer10 = '1'");
                $get_answer10_4 = mysqli_num_rows($evaluation10_4);
    
                //Question Number 11
                $evaluation11_1 = mysqli_query($conn, "SELECT answer11 FROM student_evaluation WHERE company_name = '$company' AND answer11 = '4'");
                $get_answer11_1 = mysqli_num_rows($evaluation11_1);
    
                $evaluation11_2 = mysqli_query($conn, "SELECT answer11 FROM student_evaluation WHERE company_name = '$company' AND answer11 = '3'");
                $get_answer11_2 = mysqli_num_rows($evaluation11_2);
    
                $evaluation11_3 = mysqli_query($conn, "SELECT answer11 FROM student_evaluation WHERE company_name = '$company' AND answer11 = '2'");
                $get_answer11_3 = mysqli_num_rows($evaluation11_3);
    
                $evaluation11_4 = mysqli_query($conn, "SELECT answer11 FROM student_evaluation WHERE company_name = '$company' AND answer11 = '1'");
                $get_answer11_4 = mysqli_num_rows($evaluation11_4);
    
                //Question Number 12
                $evaluation12_1 = mysqli_query($conn, "SELECT answer12 FROM student_evaluation WHERE company_name = '$company' AND answer12 = '4'");
                $get_answer12_1 = mysqli_num_rows($evaluation12_1);
    
                $evaluation12_2 = mysqli_query($conn, "SELECT answer12 FROM student_evaluation WHERE company_name = '$company' AND answer12 = '3'");
                $get_answer12_2 = mysqli_num_rows($evaluation12_2);
    
                $evaluation12_3 = mysqli_query($conn, "SELECT answer12 FROM student_evaluation WHERE company_name = '$company' AND answer12 = '2'");
                $get_answer12_3 = mysqli_num_rows($evaluation12_3);
    
                $evaluation12_4 = mysqli_query($conn, "SELECT answer12 FROM student_evaluation WHERE company_name = '$company' AND answer12 = '1'");
                $get_answer12_4 = mysqli_num_rows($evaluation12_4);
    
                //Question Number 13
                $evaluation13_1 = mysqli_query($conn, "SELECT answer13 FROM student_evaluation WHERE company_name = '$company' AND answer13 = '4'");
                $get_answer13_1 = mysqli_num_rows($evaluation13_1);
    
                $evaluation13_2 = mysqli_query($conn, "SELECT answer13 FROM student_evaluation WHERE company_name = '$company' AND answer13 = '3'");
                $get_answer13_2 = mysqli_num_rows($evaluation13_2);
    
                $evaluation13_3 = mysqli_query($conn, "SELECT answer13 FROM student_evaluation WHERE company_name = '$company' AND answer13 = '2'");
                $get_answer13_3 = mysqli_num_rows($evaluation13_3);
    
                $evaluation13_4 = mysqli_query($conn, "SELECT answer13 FROM student_evaluation WHERE company_name = '$company' AND answer13 = '1'");
                $get_answer13_4 = mysqli_num_rows($evaluation13_4);
    
                //Question Number 14
                $evaluation14_1 = mysqli_query($conn, "SELECT answer14 FROM student_evaluation WHERE company_name = '$company' AND answer14 = '4'");
                $get_answer14_1 = mysqli_num_rows($evaluation14_1);
    
                $evaluation14_2 = mysqli_query($conn, "SELECT answer14 FROM student_evaluation WHERE company_name = '$company' AND answer14 = '3'");
                $get_answer14_2 = mysqli_num_rows($evaluation14_2);
    
                $evaluation14_3 = mysqli_query($conn, "SELECT answer14 FROM student_evaluation WHERE company_name = '$company' AND answer14 = '2'");
                $get_answer14_3 = mysqli_num_rows($evaluation14_3);
    
                $evaluation14_4 = mysqli_query($conn, "SELECT answer14 FROM student_evaluation WHERE company_name = '$company' AND answer14 = '1'");
                $get_answer14_4 = mysqli_num_rows($evaluation14_4);
    
                //Question Number 15
                $evaluation15_1 = mysqli_query($conn, "SELECT answer15 FROM student_evaluation WHERE company_name = '$company' AND answer15 = '4'");
                $get_answer15_1 = mysqli_num_rows($evaluation15_1);
    
                $evaluation15_2 = mysqli_query($conn, "SELECT answer15 FROM student_evaluation WHERE company_name = '$company' AND answer15 = '3'");
                $get_answer15_2 = mysqli_num_rows($evaluation15_2);
    
                $evaluation15_3 = mysqli_query($conn, "SELECT answer15 FROM student_evaluation WHERE company_name = '$company' AND answer15 = '2'");
                $get_answer15_3 = mysqli_num_rows($evaluation15_3);
    
                $evaluation15_4 = mysqli_query($conn, "SELECT answer15 FROM student_evaluation WHERE company_name = '$company' AND answer15 = '1'");
                $get_answer15_4 = mysqli_num_rows($evaluation15_4);

                $query1 = mysqli_query($conn, "SELECT * FROM student_evaluation WHERE company_name = '$company'");
                $row1 = mysqli_num_rows($query1);
    
                ?>

                        <div class="modal fade" tabindex="-1" role="dialog" id="StudentCommentModal">
                            <div class='modal-dialog modal-lg' id="comment">
                                <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title"><b>Student Comments</b></h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">

                                    <div style="white-space:normal;margin-left:2%;">
                                    <?php
                                        $query = mysqli_query($conn, "SELECT * FROM student_evaluation WHERE company_name = '$company' AND comment != ''");
                                        if(mysqli_num_rows($query) > 0)
                                        {
                                            while($row = mysqli_fetch_array($query))
                                            {
                                                $comment = $row['comment'];
                                                ?>
                                                
                                                        <?php echo $comment; ?>
                                                        <br><hr>
                                                    
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                                <h5 style="text-align:center;"><br>No Student Comments<br><hr></h5>
                                            <?php
                                        }
                                        ?>
                                                                            
                                    </div>
                                            

                                </div>

                                </div>
                            </div>
                        </div>
                        <script>
                        const tabletSize = window.matchMedia('(min-width: 300px) and (max-width: 1279.98px)');
                        function handleScreenSizeChange(tabletSize) {
                            if (tabletSize.matches) 
                            {
                                document.getElementById('topnav_right').classList.remove('topnavbar-right');
                                document.getElementById('comment').style.maxWidth = "80%";
                            } 
                            else
                            {
                                document.getElementById('topnav_right').classList.add('topnavbar-right');
                                document.getElementById('comment').style.maxWidth = "60%";
                            }
                        }

                        tabletSize.addListener(handleScreenSizeChange);
                        handleScreenSizeChange(tabletSize);
                    </script>
                      
                        <center><h2 style="color:maroon;"><?php echo $company; ?></h2></center>
                        <center><h5 style="color:maroon;">Total Students Answered: <b><?php echo $row1; ?></b></h5></center>
                        
                         <canvas id="myChart" width="250px" height="100px"></canvas>
                         

                      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                  <script>
    
               
          var ctx = document.getElementById('myChart').getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: ['Q1','Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9', 'Q10', 'Q11', 'Q12', 'Q13', 'Q14', 'Q15'],
                  datasets: [{
                      label: 'Strongly Agree',
                      data: [<?php echo $get_answer1_1; ?>,<?php echo $get_answer2_1; ?>,<?php echo $get_answer3_1; ?>,<?php echo $get_answer4_1; ?>,<?php echo $get_answer5_1; ?>,<?php echo $get_answer6_1; ?>,<?php echo $get_answer7_1; ?>,<?php echo $get_answer8_1; ?>,<?php echo $get_answer9_1; ?>,<?php echo $get_answer10_1; ?>,<?php echo $get_answer11_1; ?>,<?php echo $get_answer12_1; ?>,<?php echo $get_answer13_1; ?>,<?php echo $get_answer14_1; ?>,<?php echo $get_answer15_1; ?>],
                      backgroundColor: [
                          'rgba(75, 192, 192, 0.2)'
                      ],
                      borderColor: [
                          'rgba(75, 192, 192, 1)'
                      ],
                      borderWidth: 1
                  },
                  {
                      label: 'Agree',
                      data: [<?php echo $get_answer1_2; ?>,<?php echo $get_answer2_2; ?>,<?php echo $get_answer3_2; ?>,<?php echo $get_answer4_2; ?>,<?php echo $get_answer5_2; ?>,<?php echo $get_answer6_2; ?>,<?php echo $get_answer7_2; ?>,<?php echo $get_answer8_2; ?>,<?php echo $get_answer9_2; ?>,<?php echo $get_answer10_2; ?>,<?php echo $get_answer11_2; ?>,<?php echo $get_answer12_2; ?>,<?php echo $get_answer13_2; ?>,<?php echo $get_answer14_2; ?>,<?php echo $get_answer15_2; ?>],
                      backgroundColor: [
                          'rgba(54, 162, 235, 0.2)',
                      ],
                      borderColor: [
                          'rgba(54, 162, 235, 1)',
                      ],
                      borderWidth: 1
                  },
                  {
                      label: 'Disagree',
                      data: [<?php echo $get_answer1_3; ?>,<?php echo $get_answer2_3; ?>,<?php echo $get_answer3_3; ?>,<?php echo $get_answer4_3; ?>,<?php echo $get_answer5_3; ?>,<?php echo $get_answer6_3; ?>,<?php echo $get_answer7_3; ?>,<?php echo $get_answer8_3; ?>,<?php echo $get_answer9_3; ?>,<?php echo $get_answer10_3; ?>,<?php echo $get_answer11_3; ?>,<?php echo $get_answer12_3; ?>,<?php echo $get_answer13_3; ?>,<?php echo $get_answer14_3; ?>,<?php echo $get_answer15_3; ?>],
                      backgroundColor: [
                          'rgba(255, 206, 86, 0.2)',
                      ],
                      borderColor: [
                          'rgba(255, 206, 86, 1)',
                      ],
                      borderWidth: 1
                  },
                  {
                      label: 'Strongly Disagree',
                      data: [<?php echo $get_answer1_4; ?>, <?php echo $get_answer2_4; ?>, <?php echo $get_answer3_4; ?>, <?php echo $get_answer4_4; ?>, <?php echo $get_answer5_4; ?>, <?php echo $get_answer6_4; ?>, <?php echo $get_answer7_4; ?>, <?php echo $get_answer8_4; ?>, <?php echo $get_answer9_4; ?>, <?php echo $get_answer10_4; ?>, <?php echo $get_answer11_4; ?>, <?php echo $get_answer12_4; ?>, <?php echo $get_answer13_4; ?>, <?php echo $get_answer14_4; ?>, <?php echo $get_answer15_4; ?>, ],
                      
                      backgroundColor: [
                          'rgba(255, 99, 132, 0.2)',
                      ],
                      borderColor: [
                          'rgba(255, 99, 132, 1)',
                      ],
                      borderWidth: 1
                  }]
              },
              options: {
                responsive: true,
                    plugins: {
                    title: {
                        display: true,
                        text: 'Student to Company Evaluation',
                    }
                    },
                  scales: {
                        x: {
                            display: true,
                            title: {
                            display: true,
                            text: 'Questions',
                            color: 'maroon',

                            padding: {top: 20, left: 0, right: 0, bottom: 0}
                            }
                        },
                        y: {
                            display: true,
                            title: {
                            display: true,
                            text: 'Number of Students Answered',
                            color: 'maroon',
                            padding: {top: 0, left: 0, right: 0, bottom: 20}
                            },
                            ticks: {
                            // forces step size to be 50 units
                            stepSize: 1
                            }
                        }
                  }
              }
          });
    
    
        </script>
    
                  <!-- END OF BODY -->
                </div> 
            </body>
        </html> 
                      <?php

            }
            else if(!isset($POST['view']))
            {
                ?>
                <canvas id="myChart" width="250px" height="100px"></canvas>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>

         
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Q1','Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9', 'Q10', 'Q11', 'Q12', 'Q13', 'Q14', 'Q15'],
                        datasets: [{
                            label: 'Strongly Agree',
                            data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                            ],
                            borderWidth: 1
                        },
                        {
                            label: 'Agree',
                            data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                            ],
                            borderWidth: 1
                        },
                        {
                            label: 'Disagree',
                            data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                            backgroundColor: [
                                'rgba(255, 206, 86, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255, 206, 86, 1)',
                            ],
                            borderWidth: 1
                        },
                        {
                            label: 'Strongly Disagree',
                            data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                responsive: true,
                    plugins: {
                    title: {
                        display: true,
                        text: 'Student Evaluation to Company'
                    }
                    },
                  scales: {
                        x: {
                            display: true,
                            title: {
                            display: true,
                            text: 'Questions',
                            color: 'maroon',

                            padding: {top: 20, left: 0, right: 0, bottom: 0}
                            }
                        },
                        y: {
                            display: true,
                            title: {
                            display: true,
                            text: 'Number of Students Answered',
                            color: 'maroon',
                            padding: {top: 0, left: 0, right: 0, bottom: 20}
                            },
                            ticks: {
                            
                            stepSize: 1
                            }
                        }
                  }
              }
                });

            </script>

<?php
            }
            ?>
           
                