<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Account Home Page - Display a list of links to various portions
 * of the app
 */
require('../include/login_required.php'); ?>
<!doctype html>
<html lang="en">
	<head>
		<title>[PW] User Home</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../main.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<h1> User Home </h1>
		<nav><a href="../index.html">Back to Main Page</a></nav>
		<a href="packs.php"><h2>Edit Packs</h2></a>
		<a href="files.php"><h2>Upload Files</h2></a>
		<a href="info.php"><h2>Manage Account</h2></a>
		<a href="logout.php"><h2>Logout</h2></a>
	</body>
</html>
