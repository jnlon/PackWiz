<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: File Library - Manage and upload user files with AJAX requests
 */
require('../include/login_required.php');
?>
<!doctype html>
<html lang="en">
	<head>
		<title>[PW] Files </title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../main.css">
		<link rel="stylesheet" type="text/css" href="css/grid.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="js/jquery-3.4.1.min.js"></script>
		<script src="js/util.js"></script>
		<script src="js/files.js"></script>
	</head>
	<body>
		<h1> File Library </h1>
		<nav><a href="index.php">Back to User Home</a></nav>
		<h2> Account Files </h2>
		<div id="files"> </div>
		<p id="log"> </p>
		<p> <input id="delete-selected" type="submit" value="Delete Selected"> </p>
		<p> <em>Tip:</em> To download files add them to a <a href="packs.php">pack</a></p>
		<h2> Upload New File </h2>
		<form id="upload-form">
			<!--<label for="pack_name"> Pack Name: </label>-->
			<input type="file" id="file" name="file">
			<input type="submit" value="Upload File">
			<h3> Upload Progress </h3>
			<div id="upload-progress">
				<div id="upload-message">No Files have been uploaded</div>
				<div id="upload-progress-bar"></div>
			</div>
		</form>
	</body>
</html>
