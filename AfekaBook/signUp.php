<!-- Code created By Omer Elgrably and Barr Inbar  -->
<!-- Contribution code:  Ashish Trivedi -->


<?php
session_start ();
// connect to mongodb
$m = new MongoClient ();
// select a database
$db = $m->project;
// select users collection
$collection = $db->createCollection ( "users" );

// initialize wrongName varible for used user ( if user exist wrongName = 1 else 0)
$_SESSION['wrongName']=0;

$postedUsername = $_POST ['username']; // Gets the posted username, put's it into a variable.
$userDatabaseSelect = $db->users; // Selects the users collection
$userDatabaseFind = $userDatabaseSelect->find ( array (
		'name' => $postedUsername 
) ); // Does a search for Usernames with the posted Username Variable
     
// Iterates through the found results
foreach ( $userDatabaseFind as $userFind ) {
	$storedUsername = $userFind ['name'];
}
if ($postedUsername == $storedUsername) {
	// if user exist wrongName = 1
	$_SESSION['wrongName']=1;
} else {
	// if user exist wrongName = 0
	$_SESSION['wrongName']=0; 
	// create new document with the new posted details
	$document = array (
			"_id" => new MongoId (),
			"password" => $_POST ['password'],
			"name" => $_POST ['username'],
			"profile_pic" => "profile_pic.jpg",
			"friends" => array()
	);
	// insert doucument into db
	$collection->insert ( $document );
	// add the user imself as a friend
	$user_id = $collection->findOne(array('name' => $_POST ['username']));
	$user_id = $user_id['_id'];
	$collection->update(array("_id" => $user_id),array('$addToSet' => array("friends"=>$user_id)));
	
}
?>
<!-- return to login page  -->
<script type="text/javascript"> window.location = "login.php"</script>
