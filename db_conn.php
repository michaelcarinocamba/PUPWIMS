<?php
    $sname = "localhost";
    $unmae = "pupwims_admin";
    $password = "Pupwims123";

    $db_name = "db_pupwims";

    $conn = mysqli_connect($sname, $unmae, $password, $db_name);

    if(!$conn)
    {
        echo "Connection Failed";
        die('Could not Connect MySql Server:' .mysql_error());
    }

?>
