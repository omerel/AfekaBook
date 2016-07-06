<!-- Code created By Omer Elgrably and Barr Inbar  -->
<!-- Contribution code:  Ashish Trivedi -->

<?php
session_start ();
// initilaize varible wrongName
$_SESSION['wrongName']=0;
// Set authentication to NULL
unset($_SESSION['authentication']);
?>
  <script type="text/javascript"> window.location = "login.php"</script> 