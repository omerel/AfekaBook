<!-- Code created By Omer Elgrably and Barr Inbar  -->
<!-- Contribution code:  Ashish Trivedi -->
<!-- Contribution code:  designshack -->



<?php
session_start ();
// including common mongo connection file
include ('mongo_connection.php');

?>

<!--Referencing common Javascript and CSS files-->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css" />
<link href="css/dropzone.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
<script src="js/dropzone.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
// Retrieve all users data for the search box
$userDataBaseSelect = $database->users;
$usersDataBase = $userDataBaseSelect->find ();
?>

<script type="text/javascript">

$(function(){
	var currencies=[];
	<?php
	foreach ( $usersDataBase as $document ) {
		?>
	 currencies.push({value:"<?php echo $document["name"]?>",data:"<?php echo $document["_id"] ?>"});<?php
	}
	?>	  
	// setup autocomplete function pulling from currencies[] array
	  $('#autocomplete').autocomplete({
	    lookup: currencies,
	    onSelect: function (suggestion) {
		    var new_friend_id = suggestion.data;
		    var user_id ="<?php echo $_SESSION['user_id']; ?>";
		    alert( suggestion.value+" has become your friend" );
		   	$.post('php_scripts/add_friend.php',{friend_id: new_friend_id,user_id:user_id},function(){});
		   	window.location = "index.php";
	    }
	  });
	});
</script>

<html>
<body>

	<!--  <div id="stage"></div>-->

	<ul class="ul_menu">
		<!-- ul_menu_bar -->
		<li class="li_menu"><a class="a_menu" href="#home">Afeka Face</a></li>
		<!-- Serach field control -->
		<li class="li_menu" style="float: right"><a class="active"
			href="logOut.php">LogOut</a></li>
		<li class="li_menu" style="float: right"><img
			class="profile_picture_top"
			src="images/<?php echo $_SESSION['user_profile_pic']; ?>" /></li>
		<li class="li_menu" style="float: right"><p class="a_menu">Hello <?php echo $_SESSION['user_name'];?> |</p></li>
		<li id="searchfield"><input type="text" name="currency"
			class="biginput" id="autocomplete"
			placeholder="Find users and add them as a friend"></li>
	</ul>
	<!-- ul_menu_bar ends -->

<div id="home"><br><br></div>
	<div id="div_main">
		<!--div_new_post for section to create new post -->
		<div id="div_new_post" class="div_new_post">
			<div id="div_post_content"></div>
			<textarea id="post_textarea" placeholder="Whats on your mind?"></textarea>
			<hr class="soften special">
			<form id="post_picture" action="upload.php" class="dropzone"></form>
			<input type="button" value="Post" id="btn_new_post"
				onClick="new_post('<?php echo $_SESSION['user_id']; ?>')"
				class="button_style" />
		</div>

		<!--div_new_post ends-->

		<!--post_stream for displaying the post stream -->
		<div id="post_stream">
    <?php
				// Selecting the posts_collection
				$collection = $database->selectCollection ( 'posts_collection' );
				$user_collection = $database->users;
				
				// Retreiving all the posts in the collection
				// If you want to retreive specific posts based on useer, relations, etc. put filter condition in find
				$posts_cursor = $collection->find ()->sort ( array (
						'_id' => - 1 
				) );
				
				// Iterating over all the retreived posts
				foreach ( $posts_cursor as $post ) {
					// User ID of the post author
					$post_author_id = $post ['post_author_id'];
					// Check box post private
					$check_box_post_private = $post ['post_private'];
					// print only my friends posts
					$user = $user_collection->find ( array (
							'_id' => $_SESSION ['user_id'] 
					) );
					foreach ( $user as $us ) {
						$array = $us ['friends'];
					}
					$size = sizeof ( $array );
					$z = 0;
					for($z; $z < $size; $z ++)
						// if the author of the post is my friend
						if ($post_author_id == $array [$z]) {
							// if the post is public or its my post show it
							if (($check_box_post_private == 0) || ($_SESSION ['user_id'] == $post_author_id)) {
								// Post ID
								$post_id = $post ['_id'];
								// Post text
								$post_text = $post ['post_text'];
								// Number of likes
								$post_like_count = $post ['total_likes'];
								// Number of comments
								$post_comment_count = $post ['total_comments'];
								// Post timestamp
								$post_timestamp = $post ['timestamp'];
								// User ID of the post author
								$post_author_id = $post ['post_author_id'];
								$post_pic = "posts_imgs_thumb/" . $post ['post_pic'];
								$post_pic_big = "posts_imgs/" . $post ['post_pic'];
								
								// Retreiving name of the author from the users collection based on the $post_author_id
								$collection = $database->selectCollection ( 'users' );
								$post_author_details = $collection->findOne ( array (
										'_id' => $post_author_id 
								) );
								// Name of post author
								$post_author = $post_author_details ['name'];
								// Profile picture of post author
								$post_author_profile_pic = $post_author_details ['profile_pic'];
								
								// ID of span displaying Like/Unlike option
								$post_like_unlike_id = $post_id . '_like_unlike';
								// ID of span displaying number of likes
								$post_like_count_id = $post_id . '_like_count';
								// ID of span displaying number of comments
								$post_comment_count_id = $post_id . '_comment_count';
								// In the comments box list, the last comment box is empty so that the user can comment there
								// ID for that last self comment box
								$post_self_comment_id = $post_id . '_self_comment';
								// ID of textbox in the last comment box
								$post_comment_text_box_id = $post_id . '_comment_text_box';
								
								// If the user has previously liked the post the option of 'Unlike' should be shown.
								// For this we check if the user's user id is present in likes_user_ids array which stores user ids of all those who have liked
								// Else the default Like option should be shown
								if (in_array ( $_SESSION ['user_id'], $post ['likes_user_ids'] )) {
									// User had already liked the post
									$like_or_unlike = 'Unlike';
								} else {
									// User has not liked the post
									$like_or_unlike = 'Like';
								}
								?>

      <!-- div to display all the post content - to be repeated for each post -->
			<div class="post_wrap" id="<?php echo $post['_id'];?>">
				<!-- div to display post author's profile picture -->
				<div class="post_wrap_author_profile_picture">
					<img src="images/<?php echo $post_author_profile_pic; ?>" />
				</div>
				<div class="post_details">
					<!-- div to display post author's name -->
					<div class="post_author">
					 <?php echo $post_author ?> 
				    </div>
					<!-- span to display post timestamp -->
					<div class="post_timestamp">
      								<?php echo $post_timestamp; ?>
      						</div>
					<!-- div to display post's text-->
					<br> <br> <br>
					<div class="post_text">
						<?php echo $post_text; ?>
				    </div>

					<div>
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-12">
									<img id="modal-952309"
										href="#modal-container-<?php echo $post_id?>" role="button"
										class="post_img_1" data-toggle="modal"
										src="<?php echo $post_pic; ?>"
										<?php
								if ($post_pic == "posts_imgs_thumb/")
									echo "style='visibility:hidden'";
								?> />
									<div class="modal fade"
										id="modal-container-<?php echo $post_id?>" role="dialog"
										aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog">

											<button type="button" class="close" data-dismiss="modal"
												aria-hidden="true">×</button>

											<img class="post_img" src="<?php echo $post_pic_big ?>">
										</div>

									</div>

								</div>
							</div>
						</div>

					</div>
					<br>
				</div>
				<!-- div to display all the comments related to post -->
				<div class="comments_wrap">
					<span> <span><img src="images/like.png" /></span> <!-- span to display Like/Unlike option -->
						<span class="post_feedback_like_unlike"
						id="<?php echo $post_like_unlike_id;?>"
						onclick="post_like_unlike(this,'<?php echo $_SESSION['user_id']; ?>')"><?php echo $like_or_unlike; ?></span>
						<!-- span to display number of likes --> <span
						class="post_feedback_count"
						id="<?php echo $post_like_count_id; ?>"><?php echo $post_like_count;?></span>
					</span> <span> <span class="post_feedback_comment"> <img
							src="images/comment.png" /> Comment
					</span> <!-- span to display number of comments --> <span
						class="post_feedback_count"
						id="<?php echo $post_comment_count_id; ?>"><?php echo $post_comment_count;?></span>
					</span>
					<!-- private post check box -->
					<span class='post_feedback_comment'
						<?php
								
								if ($_SESSION ['user_id'] != $post_author_id)
									echo "style ='visibility: hidden'";
								?>> <input type='checkbox' value='privat_post' id='privat_post'
						name='privat_post'
						<?php
								if ($check_box_post_private == 1)
									echo "checked";
								?>
						onClick="post_make_private('<?php echo $post_id ?>')">
						<?php echo "private post" ?></span>
					                           
                           <?php
								// iterating over all the comments
								for($i = 0; $i < $post_comment_count; $i ++) {
									// comment id
									$comment_id = $post ['comments'] [$i] ['comment_id'];
									// comment text
									$comment_text = $post ['comments'] [$i] ['comment_text'];
									// comment author user id
									$comment_author_id = $post ['comments'] [$i] ['comment_user_id'];
									// retreiving comment author's details fromm the users collection
									$collection = $database->selectCollection ( 'users' );
									$comment_author_details = $collection->findOne ( array (
											'_id' => new MongoId ( $comment_author_id ) 
									) );
									// comment author name
									$comment_author = $comment_author_details ['name'];
									// comment author profile picture name
									$comment_author_profile_pic = $comment_author_details ['profile_pic'];
									?>                
                           <!-- div for displaying each comment - to be repeated for each comment -->
					<div class="comment" id="<?php echo $comment_id; ?>">
						<!-- div to display comment author profile picture -->
						<div class="comment_author_profile_picture">
							<img src="images/<?php echo $comment_author_profile_pic; ?>" />
						</div>
						<div class="comment_details">
							<!-- div to display comment author's name -->
							<div class="comment_author">
			                    		<?php echo $comment_author; ?>
			                    	</div>
							<!-- div to display comment text -->
							<div class="comment_text">
			                    		<?php echo $comment_text; ?>
			                    	</div>
						</div>
					</div>
                            <?php } ?>   
                          <!-- div to display a default empty comment box at the end for the current user to comment-->
					<div class="comment" id="<?php echo $post_self_comment_id; ?>">
						<div class="comment_author_profile_picture">
							<img src="images/<?php echo $_SESSION['user_profile_pic']; ?>" />
						</div>
						<div class="comment_text">
							<textarea placeholder="Write a comment..."
								id="<?php echo $post_comment_text_box_id; ?>"
								onKeyPress="return new_comment(this,event,'<?php echo $_SESSION['user_id']; ?>')"></textarea>
						</div>
					</div>
				</div>
			</div>
			<hr class="soften special">
    <?php
							}
						}
				}
				
				?>
				
  
    </div>
	</div>
</body>
</html>
