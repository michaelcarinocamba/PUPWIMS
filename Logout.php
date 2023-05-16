<?php
// Initialize the session
session_start();
 

if(isset($_GET['Logout']))
{
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: Homepage/Homepage.php");
exit;
}
?>