<?php
session_start();
/*Code written by Ashish Trivedi*/

//including common mongo connection file
include('../mongo_connection.php');

//getting post text from the ajax POST parameters
$post_id = new MongoId($_POST['post_id']);
$collection = $database-> selectCollection('posts_collection');

$check = $collection->findone ( array ('_id' => $post_id) );
	$check_private = $check['post_private'];
	
if ($check_private == 0)
	$collection->update(array("_id" => $post_id),array('$set'=>array('post_private' => 1)));
else
	$collection->update(array("_id" => $post_id),array('$set'=>array('post_private' => 0)));
	
?>