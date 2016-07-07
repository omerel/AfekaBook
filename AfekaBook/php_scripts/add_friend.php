<?php
session_start();
/*Code written by Ashish Trivedi*/

//including common mongo connection file
include('../mongo_connection.php');

//getting post text from the ajax POST parameters
$friend_id = new MongoId($_POST['friend_id']);
$user_id = new MongoId($_POST['user_id']);
$collection = $database-> selectCollection('users');
$collection->update(array("_id" => $user_id),array('$addToSet' => array("friends"=>$friend_id)));
$collection->update(array("_id" => $friend_id),array('$addToSet' => array("friends"=>$user_id)));
?> 