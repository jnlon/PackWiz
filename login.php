<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Login page for PackWiz, redirects to account Home if already logged in
 */

// redirect to user home if we are already logged-in
session_start();
if (isset($_SESSION['user_id'])) {
	header('Location: ' . 'account/index.php');
	die();
}
?>
<!doctype html>
<html lang="en">
	<head>
		<title>[PW] Login</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="main.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<h1> Login </h1>
		<nav>
			<a href="login.php">Login</a>
			<a href="create.html">Create Account</a>
			<a href="public.php">Public Packs</a>
			<a href="index.html">Home</a>
		</nav>

		<h2> Account Details </h2>
		<form action="account/login.php" method="POST">

			<label> 
				<div>Email</div>
				<input id="email" type="email" name="email" maxlength="255" placeholder="Email" required>
			</label>

			<label> 
				<div>Password</div>
				<input id="password" type="password" name="password" minlength="8" placeholder="Password" required>
			</label>

			<input type="submit" name="submit" value="Login">

			<h3>Demo Login Credentials</h3>
			<p>Email: <em>packwiz@null.domain</em> Password: <em>packwiz123</em></p>

		</form>
	</body>
</html>
