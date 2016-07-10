<!-- Code created By Omer Elgrably and Barr Inbar  -->
<!-- Contribution code:  Ashish Trivedi -->

<?php
// If this is a new session
if (session_status() != PHP_SESSION_ACTIVE)
{
	session_start ();
	$wrongflag=NULL;
	if (!isset($_SESSION['wrongName']))
		$_SESSION['wrongName'] = NULL;
	if (!isset($_SESSION['wrongRePass']))
		$_SESSION['wrongRePass'] = NULL;
}

// including common mongo connection file
include ('mongo_connection.php');

// Check if exists and has value other than NULL
if (isset ( $_SESSION ['authentication'] )) {
	?>
<script type="text/javascript">window.location = "index.php"</script>
You're already logged in, redirecting you.
	<?php
} else {
	if (isset ( $_POST ['login'] )) {
		
		$postedUsername = $_POST ['username']; // Gets the posted username, put's it into a variable.
		$postedPassword = $_POST ['password']; // Gets the posted password, put's it into a variable.
		$userDatabaseSelect = $database->users; // Selects the users collection
		$userDatabaseFind = $userDatabaseSelect->find ( array (
				'name' => $postedUsername 
		) ); // Does a search for Usernames with the posted Username Variable
		     
		$storedUsername=NULL;
		
		// Iterates through the found results
		foreach ( $userDatabaseFind as $userFind ) {
			$storedUsername = $userFind ['name'];
			$storedPassword = $userFind ['password'];
			$storedPic = $userFind ['profile_pic'];
		}
		
		// If user and password match, update the current user on this session and enter to index page
		if ($postedUsername == $storedUsername && password_verify($postedPassword, $storedPassword)) {
			$_SESSION ['authentication'] = 1;
			$_SESSION ['user_id'] = $userFind ['_id'];
			$_SESSION ['user_name'] = $storedUsername;
			$_SESSION ['user_profile_pic'] = $storedPic;
			?>
<script type="text/javascript"> window.location = "index.php"</script>
<?php
		} else {
			// If there is no match- retrieve error
			$wrongflag = 1;
		}
	}
}
?>
<link
	href='http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css'
	rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/style.css" />
<html>
<head>
</head>
<body class="login_body">
	<div class="wrapper">

		<form class="form-signin" action="login.php" method="post">
			<h2 class="form-signin-heading">Please login</h2>
			<?php if($wrongflag == 1){ echo "<font size='2px' color='red' face='Arial'> Wrong Username/Password </font><br/>";} ?>
			<input type="text" class="form-control" name="username"
				placeholder="User name" required="" autofocus="" /> <br>
				<input placeholder="Password" type ="password" required="" class="form-control" name="password" /><br>
				<label class="login_checkbox">
				<input type="checkbox" value="remember-me" id="rememberMe" name="rememberMe"> Remember me</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit"
				name="login" value="login">Login</button>
		</form>
		<h1 align="center">Or</h1>
		    
		<form class="form-signin" action="signUp.php" method="post" enctype="multipart/form-data">
			<h2 class="form-signin-heading">Sign Up</h2>
			<?php if($_SESSION['wrongName']==1){ echo "<font size='2px' color='red' face='Arial'> Username not available </font><br/>";} ?>
			<input type="text" class="form-control" name="username"
				placeholder="User name" required=" " autofocus="" /><br>
			<?php if($_SESSION['wrongRePass']==1){ echo "<font size='2px' color='red' face='Arial'> Passwords do not match</font><br/>";} ?>
			<input type="password" class="form-control" name="password"
				placeholder="Password" required="" /><br> 
			<input type="password" class="form-control" name="re_password"
				placeholder="Retype Password" required="" /><br> 
			<p class="help-block">Please Upload your image here</p>
			<input type="file" name="fileToUpload" /><br>
			<button class="btn btn-lg btn-primary btn-block" type="submit"
				name="signup" value="signup">SignUp</button>
		</form>

	</div>

</body>
</html>
