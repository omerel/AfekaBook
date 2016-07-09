<?php
session_start ();
$ds = DIRECTORY_SEPARATOR;  // Store directory separator (DIRECTORY_SEPARATOR) to a simple variable
 
$storeFolder = '/posts_imgs';   // Declare a variable for destination folder.
 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          // If file is sent to the page, store the file object to a temporary variable.    
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  // Create the absolute path of the destination folder.
     
    $targetFile =  $targetPath. $_FILES['file']['name'];  // Create the absolute path of the uploaded file destination.
 
    move_uploaded_file($tempFile,$targetFile); // Move uploaded file to destination.
    
    $target_file=basename ( $_FILES ["file"] ["name"] );
    $_SESSION ['upload_pic']= $target_file;
    
    createThumbs( "./posts_imgs/", "./posts_imgs_thumb/",100,$target_file);
}

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth,$picture )
{
	// open the directory
	$dir = opendir( $pathToImages );

			// load image and get image size
			$img = imagecreatefromjpeg( "{$pathToImages}{$picture}" );
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
			imagejpeg( $tmp_img, "{$pathToThumbs}{$picture}" );

	// close the directory
	closedir( $dir );
}
?>  