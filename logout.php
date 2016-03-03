<?php


 session_start();   
 unset($_SESSION['user']);   
 unset($_SESSION['uploaded_file']);   
 

 session_unset();   
 session_destroy(); 

 header("Location: index.php");

?>