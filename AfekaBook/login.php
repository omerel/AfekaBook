<!-- Code created By Omer Elgrably and Barr Inbar  -->
<!-- Contribution code:  Ashish Trivedi -->

<?php
session_start ();
// connect to mongodb
$m = new MongoClient ();
// select a database
$db = $m->project;
?>
<?php
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
		$userDatabaseSelect = $db->users; // Selects the users collection
		$userDatabaseFind = $userDatabaseSelect->find ( array (
				'name' => $postedUsername 
		) ); // Does a search for Usernames with the posted Username Variable
		     
		// Iterates through the found results
		foreach ( $userDatabaseFind as $userFind ) {	
		$storedUsername = $userFind ['name'];
			$storedPassword = $userFind ['password'];
		}
		
		// If user and password match, update the current user on this session and enter to index page
		if ($postedUsername == $storedUsername && $postedPassword == $storedPassword) {
			$_SESSION ['authentication'] = 1;
			$_SESSION ['user_id'] = $userFind [_id];
			$_SESSION ['user_name'] = $storedUsername;
			$_SESSION ['user_profile_pic'] = "profile_pic.jpg";
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
<link rel="stylesheet" href="style.css" />
<html>
<head>
</head>
<body class="login_body">
	<div class="wrapper">

		<form class="form-signin" action="login.php" method="post">
			<h2 class="form-signin-heading">Please login</h2>
			<?php if($wrongflag == 1){ echo "<font size='2px' color='red' face='Arial'> Wrong Username/Password </font><br/>";} ?>
			<input type="text" class="form-control" name="username"
				placeholder="User name" required=" " autofocus="" /> <input
				type="password" class="form-control" name="password"
				placeholder="Password" required="" /> <label class="login_checkbox">
				<input type="checkbox" value="remember-me" id="rememberMe"
				name="rememberMe"> Remember me
			</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit"
				name="login" value="login">Login</button>
		</form>
		<h1 align="center">Or</h1>
		<form class="form-signin" action="signUp.php" method="post">
			<h2 class="form-signin-heading">Sign Up</h2>
			<?php if($_SESSION['wrongName']==1){ echo "<font size='2px' color='red' face='Arial'> Username not availble </font><br/>";} ?>
			<input type="text" class="form-control" name="username"
				placeholder="User name" required=" " autofocus="" /> <input
				type="password" class="form-control" name="password"
				placeholder="Password" required="" />
			<button class="btn btn-lg btn-primary btn-block" type="submit"
				name="signup" value="signup">SignUp</button>
		</form>

	</div>

</body>
</html>
