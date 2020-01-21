<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Edit Pack - Add or remove files from a pack with AJAX requests
 */

require('../include/login_required.php');
require('../include/models/PackDB.php');
$PackDB = new PackDB();

// verify the logged-in user owns the pack with this id
$apparent_pack_id = filter_input(INPUT_GET, 'id');
if (! $PackDB->user_owns_pack($_SESSION['user_id'], $apparent_pack_id)) {
	http_response_code(403);
	die('You are not the owner of this pack');
}

?>
<!doctype html>
<html lang="en">
	<head>
		<title>[PW] Edit Pack</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../main.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="js/jquery-3.4.1.min.js"></script>
		<script src="js/util.js"></script>
		<script src="js/edit_pack.js"></script>
	</head>
	<body>
		<h1>Edit Pack</h1>
		<nav><a href="index.php">Back to User Home</a></nav>
		<h2>Pack Files</h2>
		<p>Use table check-boxes to add or remove files from this pack. Click Apply Changes to save. </p>
		<div id="files"></div>
		<p id="log"> </p>
		<p> <input type="submit" id="apply" name="apply" value="Apply Changes"> </p>
		<p><em>Tip:</em> Upload more files <a href="files.php">here</a></p>
	</body>
</html>
