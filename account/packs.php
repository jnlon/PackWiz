<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Pack Library - Manage and create packs with AJAX requests
 */
require('../include/login_required.php'); ?>
<!doctype html>
<html lang="en">
	<head>
		<title>[PW] Packs </title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../main.css">
		<link rel="stylesheet" type="text/css" href="css/grid.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="js/jquery-3.4.1.min.js"></script>
		<script src="js/util.js"></script>
		<script src="js/packs.js"></script>
	</head>
	<body>
		<h1> Pack Library </h1>
		<nav><a href="index.php">Back to User Home</a></nav>
		<h2> Account Packs </h2>
		<div id="packs"> </div>
		<p> <em>Tip:</em> Click on a Pack Name to edit associated files </p>
		<p id="log"> </p>
		<p> <input id='delete-selected' type='submit' value='Delete Selected'> </p>
		<h2> Create New Pack </h2>
		<form id="create-pack">
			<label> 
				<span> Pack Name </span>
				<input name="name" type="text" placeholder="New Pack">
			</label>

			<label> 
				<span>Public <span class="tooltip" title="Public packs are discoverable from the Public Packs page. Select this option to share this pack with the world.">?</span></span>
				<input id="public" name="public" type="checkbox">
			</label>

			<div>
				<input name="submit" type="submit" value="Create Pack">
			</div>
		</form>
	</body>
</html>
