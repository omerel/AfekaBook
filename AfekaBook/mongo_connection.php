<!-- Code created By Omer Elgrably and Barr Inbar  -->
<!-- Contribution code:  Ashish Trivedi -->

<?php
/*Code written by Ashish Trivedi*/

//This file contains the common database connection code used accross files
//If you have modified username and password for connection, define it here     
$connection = new MongoClient();
$database = $connection->selectDB('project');

?>