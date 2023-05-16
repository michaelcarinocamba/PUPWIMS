<?php
include "../db_conn.php";

$code = $_REQUEST['classCode'];
$query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE class_code = '$code'");
$sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
$row1 = mysqli_fetch_array($sql);

$course = $row1['course'];
$year_section = $row1['year_section'];
$fileName = "Classwork_from_" . $course . "_" . $year_section . ".csv";

?>
CLASSWORK FOR <?php echo $course ;?>_<?php echo $year_section; ?>. 
<?php

if(isset($_REQUEST['classCode']))
{
    
    $code = $_REQUEST['classCode'];
    $query = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE class_code = '$code'");
    $sql = mysqli_query($conn, "SELECT * FROM create_class WHERE class_code = '$code'");
    $row1 = mysqli_fetch_array($sql);
    
    $course = $row1['course'];
    $year_section = $row1['year_section'];
    $fileName = "Classwork_from_" . $course . "_" . $year_section . ".csv";


    // Set the response headers to download a CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'. $fileName .'"');
    header('Cache-Control: max-age=0');
    $output = fopen('php://output', 'w');
    // Output the table header row

    $get_title = mysqli_query($conn, "SELECT title FROM adviser_assignment WHERE class_code = '$code'");

    $data = array();
    $columns[] = 'Student Name';
    while($title = mysqli_fetch_array($get_title))
    {
       
        $columns[] = $title['title'];
    }

    fputcsv($output, $columns);
    
    $get_student = mysqli_query($conn, "SELECT studentnum FROM student_record WHERE class_code = '$code'");
    while ($student = mysqli_fetch_assoc($get_student)) {
        $studentnum = $student['studentnum'];
        $stud_query = mysqli_query($conn, "SELECT name FROM student_info WHERE studentnum ='$studentnum'");
        if(mysqli_num_rows($stud_query)>0) 
        {
            while($row= mysqli_fetch_assoc($stud_query))
            {
                $get_adv = mysqli_query($conn, "SELECT * FROM adviser_assignment WHERE class_code = '$code'");
                if(mysqli_num_rows($get_adv) > 0)
                {
                    while($get_id = mysqli_fetch_array($get_adv))
                    {
                        $id = $get_id['assignment_id']; 
                        $get_stud = mysqli_query($conn, "SELECT points FROM student_assignment WHERE assignment_id = '$id' AND studentnum = '$studentnum'");
                        if(mysqli_num_rows($get_stud) > 0)
                        {
                            while($row1 = mysqli_fetch_assoc($get_stud))
                            {
                                $points = $row1['points'];
                                if($points === '1')
                                {
                                    $row[] = '/';
                                }
                                else if($points > 1)
                                {
                                    $row[] = $row1['points'];
                                }
                                else
                                {
                                    $row[] = 'X';
                                }
                                
                            }
                        }
                        else
                        {
                            $row[] = 'X';
                        }
                    }
                
                }
                
                fputcsv($output, $row);   
            }
        }
       
    }
    // while ($row = mysqli_fetch_assoc($get_points)) {
    //     fputcsv($output, $row);   
    // }

    
    // 
    // if(mysqli_num_rows($get_student)>0)
    // {
       
    //     while($get_assignment = mysqli_fetch_assoc($get_student))
    //     {
    //         $stud_num = $get_assignment['studentnum'];
    //         $row[] = $stud_num;
    //     }
        
    // }
    // else
    // {
    //     $row[] = '';
    // }

    // foreach ($data as $row) {
    //     fputcsv($output, $row);
    // }

    // Write the data to the CSV file
    // Loop through the result set and add each row to the array

    // while ($row = mysqli_fetch_assoc($get_student)) {
    //     // Check if any columns are arrays and convert them to strings
        
        
    // }
    // fputcsv($output, $row);
    mysqli_close($conn);
    // Close the file pointer
    fclose($output);
}
exit;

?>