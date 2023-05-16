<?php
    session_start();
    include "../db_conn.php";
    if(!isset($_SESSION['coordinator']))
    {
        header("Location: Coordinator_Login.php?LoginFirst");
    }
    $coordnum = $_SESSION['coordinator'];

    if(isset($_REQUEST['adddata']))
    {
        if(isset($_POST['save']))
        {
            $docname = $_POST['docname'];
            $doclink = $_POST['doclink'];
    
            $insert_doc = mysqli_query($conn, "INSERT INTO document VALUES('','$docname','$doclink')");
            header("Location: Coordinator_Document.php?Home&success");
        }
    
        if (isset($_POST['checking_editbtn']))
        {
            $c_id = $_POST['doc_id'];
            $result_array = [];
            
            $query = "SELECT * FROM document WHERE doc_id = '$c_id'";
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
    
        if(isset($_POST['update_doc']))
        {
            $doc_id = $_POST['doc_id'];
            $doc_name = $_POST['docname'];
            $doc_link = $_POST['doclink'];
    
            $query = mysqli_query($conn, "UPDATE document SET name = '$doc_name', link = '$doc_link' WHERE doc_id = '$doc_id'");
            header("Location: Coordinator_Document.php?Home&editsuccess");
        }

        if(isset($_POST['delete_document']))
        {
            $doc_id = $_POST['document_id'];

            $query = mysqli_query($conn, "DELETE FROM document WHERE doc_id = '$doc_id'");
            header("Location: Coordinator_Document.php?Home&deletesuccess");
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

    <title>Coordinator Dashboard</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

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

       
        #document 
        {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        min-width: 100%;
        }

        #document td, #document th 
        {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #document tr:nth-child(even){background-color: #f2f2f2;}

        #document th 
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
        
        <div class = "topnavbar">
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
        
        if(isset($_REQUEST['success']))
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
                title: 'Document saved successfully'
            })
            </script>
      <?php
        }
        ?>
         <?php
        
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
                title: 'Document updated successfully'
            })
            </script>
      <?php
        }
        ?>
         <?php
        
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
                title: 'Document deleted successfully'
            })
            </script>
      <?php
        }
        ?>
            
            <h1 class="font-weight-bold" style = "font-size: 35px; color:maroon;">On-the-Job Training Requirements </h1><hr style="background-color:maroon;border-width:2px;"><button type="button" class="btn btn-primary" style="background-color:maroon;float:right;" data-toggle="modal" data-target="#DocumentModal"> <b>+ ADD DOCUMENT</b> </button><br><br>
            
                    

            <div class="modal fade" tabindex="-1" role="dialog" id="DocumentModal">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">

                    <div class="modal-header">
                    <h5 class="modal-title"><b>REQUIRED DOCUMENTS</b></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <form method="post" enctype='multipart/form-data' action = "Coordinator_Document.php?adddata">
                    <div class="modal-body">
                    <div class="form-group">
                        
                        <div class="form-group">
                            <label for = ""><b>Document Name</b></label>
                            <input type="text" name = "docname" class = "form-control" placeholder = "Enter Document Name" required>
                        </div>
                        <div class="form-group">
                            <label for = ""><b>Document Link</b></label>
                            <input type="text" name = "doclink" class = "form-control" placeholder = "Enter Document Link" required>
                        </div>
                        
                        </div>
                    </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name = "save" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

            
            <p style="font-size:14px;">Below are the required documents visible by the students. </p>
             <br><br>

             <!-- Modal EDIT DATA -->
        <div class="modal fade" id="editDocumentModal" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                    <h5 class="modal-title"><b>DOCUMENT REQUIREMENT</b></h5>
                
                    </div>
                    
                <form method = "POST" action = "Coordinator_Document.php?adddata">
                    <div class="modal-body">
                    
                    <input type = "hidden" name = "doc_id" id = "edit_id">

                    <div class="form-group">
                        <label for = ""><b>Document Name</b></label>
                        <input type="text" name = "docname" id = "edit_docname" class = "form-control" required>
                    </div>
                    <div class="form-group">
                        <label for = ""><b>Document Link</b></label>
                        <input type="text" name = "doclink" id = "edit_doclink" class = "form-control" required>
                    </div>
                    </div>
                                        
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name = "update_doc" class="btn btn-primary">Update</button>
                </div>
                </form>
            </div>
            </div>
        </div>


            <!-- Modal DELETE DATA-->
            <div class="modal fade" id="deleteDocumentModal" tabindex="-1" role="dialog" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id = "deleteDocumentModalLabel"><b>CONFIRMATION</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method = "POST" action = "Coordinator_Document.php?adddata">
                <div class="modal-body">
                <input type = "hidden" name = "document_id" id = "delete_id">
                    <h4> Do you want to delete this information? </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name = "delete_document" class="btn btn-danger">Confirm</button>
                </div>
                </form>
                </div>
            </div>
            </div>
            <div class="table table-responsive">
            <table id = "document">
            <thead>
            <tr>
                <th style='display: none;'></th>
                <th scope = "col">Document Name</th>
                <th scope = "col">Link</th>
                <th scope = "col">Action</th>
                
            </tr>
            </thead>

            <tbody>
                <!-- changes starts here -->
            <?php
            $get_doc = mysqli_query($conn, "SELECT * FROM document");

            if (mysqli_num_rows($get_doc) > 0)
            {
                while ($row = mysqli_fetch_array($get_doc))
                {
                    $id = $row['doc_id'];
                    $max_text = 50;
                    $displayed_link = substr($row['link'], 0, $max_text);
                    if (strlen($row['link']) > $max_text) {
                        $displayed_link .= "...";
                      }
                ?>
                <tr>
                    <td class="doc_id" style='display: none;'><?php echo $row['doc_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><a href="<?php echo $row['link']; ?>" target="_blank"><?php echo $displayed_link; ?></a></td>
                    <td style="text-align:center;">
                    <div class="btn-group" role="group" aria-label="Button Group">
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
            <script>
            $(document).ready(function() {

                $('.delete_btn').click(function(e) {
                e.preventDefault();

                var doc_id = $(this).closest('tr').find('.doc_id').text();
                $('#delete_id').val(doc_id);
                $('#deleteDocumentModal').modal('show');
                });

                $('.edit_btn').click(function(e) {
                e.preventDefault();

                var doc_id = $(this).closest('tr').find('.doc_id').text();
                
                $.ajax({
                type: "POST",
                url: "Coordinator_Document.php?adddata",
                data: {
                    'checking_editbtn': true,
                    'doc_id': doc_id,
                },
                success: function (response) {
                    $.each(response,function (key, value){
                    $('#edit_id').val(value['doc_id']);
                    $('#edit_docname').val(value['name']);
                    $('#edit_doclink').val(value['link']);
                    });
                    $('#editDocumentModal').modal('show');
                }
                });

                });

                
            });
            </script>
            <script>
            $(document).ready(function(){
            $('#document').DataTable({
                lengthMenu:[
                [10, 15, 20, 30, 50, -1],
                [10, 15, 20, 30, 50, 'All'],
                ],
            });
            });
            </script>

<br><br><br>
        </div> <!-- end of main -->
    
    
    
</div> <!--End of container-fluid -->

</body>
</html> 
<?php
}
?>