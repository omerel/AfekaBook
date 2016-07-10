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
	
	// upload picture
	$target_dir = "images/";
	$target_file = $target_dir . basename ( $_FILES ["fileToUpload"] ["name"] );
	$uploadOk = 1;
	$imageFileType = pathinfo ( $target_file, PATHINFO_EXTENSION );
	// Check if image file is a actual image or fake image
	if (isset ( $_POST ["submit"] )) {
		$check = getimagesize ( $_FILES ["fileToUpload"] ["tmp_name"] );
		if ($check !== false) {
			//echo "File is an image - " . $check ["mime"] . ".";
			$uploadOk = 1;
		} else {
			//echo "File is not an image.";
			$uploadOk = 0;
		}
	}
	// Check file size
	if ($_FILES ["fileToUpload"] ["size"] > 5000000) {
		//echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		//echo "Sorry, your file was not uploaded.";
		$picture = "profile_pic.jpg"; // regular pic
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file ( $_FILES ["fileToUpload"] ["tmp_name"], $target_file )) {
			//echo "The file " . basename ( $_FILES ["fileToUpload"] ["name"] ) . " has been uploaded.";
			$picture = basename ( $_FILES ["fileToUpload"] ["name"]);
		} else {
			//echo "Sorry, there was an error uploading your file.";
		}
	}
	// createThumb from pic
	createThumbs($target_dir,$target_dir,60,$picture);
	
	// create new document with the new posted details
	$document = array (
			"_id" => new MongoId (),
			"password" => $_POST ['password'],
			"name" => $_POST ['username'],
			"profile_pic" => $picture,
			"friends" => array()
	);
	// insert doucument into db
	$collection->insert ( $document );
	// add the user imself as a friend
	$user_id = $collection->findOne(array('name' => $_POST ['username']));
	$user_id = $user_id['_id'];
	$collection->update(array("_id" => $user_id),array('$addToSet' => array("friends"=>$user_id)));
	
}

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth,$picture )
{
	// open the directory
	$dir = opendir( $pathToImages );

	// loop through it, looking for any/all JPG files:
	while (false !== ($fname = readdir( $dir ))) {
		// parse path for the extension
		$info = pathinfo($pathToImages . $fname);
		// continue only if this is a JPEG image
		if ( (strtolower($info['extension']) == 'jpg') && ($picture == $fname ) )
		{
			// load image and get image size
			$img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
			$width = imagesx( $img );
			$height = imagesy( $img );

			// calculate thumbnail size
			$new_width = $thumbWidth;
			$new_height = floor( $height * ( $thumbWidth / $width ) );

			// create a new tempopary image
			$tmp_img = imagecreatetruecolor( $new_width, $new_height );

			// copy and resize old image into new image
			imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

			// save thumbnail into a file
			imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
		}
	}
	// close the directory
	closedir( $dir );
}

?>
<!-- return to login page  -->
<script type="text/javascript"> window.location = "login.php"</script> 
