<?php
session_start ();
include('login.php');
// including common mongo connection file

$connection = new Mongo();
$database = $connection->selectDB('project');

$collection = $database->selectCollection ( 'posts_collection' );


$_SESSION['user_id'] = new MongoId("577e1d46cb7851c7110041b4");

$user_collection = $database->users;
$user = $user_collection->find ( array ('_id' => $_SESSION['user_id'] ));
foreach ($user as $us){
	$array = $us['friends'];
}
echo $array;



/*
 $user_collection = $database->users;
$user = $user_collection->find ( array (
		'_id' => $_SESSION ['user_id'] 
) );

$i =0;
foreach ( $user as $us ) {
	
	$posts_cursor = $collection->find ( array (
			'_id' => array (
					'$in' => $us ['friends'] 
			) 
	) )->sort ( array (
			'_id' => - 1 
	) );
	echo $arr."\n";
	$i++;
	}
 */



//$friend= $user['friends'];
//var_dump($friends);
//echo "\n".$user;

//$user_object = $user_collection -> find(array('name'=>"omer"));


/*
$user_object-> fields(array("friends"=>true));
$array = iterator_to_array($user_object);
echo $array;
var_dump($user_object);
*/
?>