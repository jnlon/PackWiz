<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Show user account information and statistics, and provide a form
 * that deletes the account on submission
 */
require('../include/login_required.php');
require('../include/models/UserDB.php');
$UserDB = new UserDB();
$user_id = $_SESSION['user_id'];
$info_row = $UserDB->get_user_info($user_id);

?>
<!doctype html>
<html lang="en">
	<head>
		<title>[PW] Account Information</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../main.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<h1>Account Information</h1>
		<nav><a href="index.php">Back to User Home</a></nav>
		<h2>User Info</h2>
		<p> Email: <?= $info_row['email'] ?> </p>
		<p> Username: <?= $info_row['username'] ?> </p>
		<p> Account ID: <?= $user_id ?> </p>
		<h2>User Statistics</h2>
		<p> Number of Packs: <?= $info_row['pack_count'] ?></p>
		<p> Number of Files: <?= $info_row['file_count'] ?></p>
		<h2>Delete Account</h2>
		<p> Enter your password below and select "Delete Account" to remove your account and all associated data.</p>
		<p> <em>This cannot be undone. Your packs and files will be permanently deleted.</em></p>
		<form action="delete.php" method="POST">
			<input name="password" type="password">
			<input name="submit" type="submit" value="Delete Account">
		</form>
	</body>
</html>
