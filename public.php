<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Listing of public packs links to download preview page
 */
?>
<!doctype html>
<html lang="en">
	<head>
		<title>[PW] Public Packs</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="main.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<h1>Public Packs</h1>
		<nav>
			<a href="login.php">Login</a>
			<a href="create.html">Create Account</a>
			<a href="public.php">Public Packs</a>
			<a href="index.html">Home</a>
		</nav>

		<ul class="bold">
		<?php
			require('include/models/PackDB.php');
			$PackDB = new PackDB();
			$PackDB->for_each_public_pack(function ($row) {
				$username = $row["username"];
				$pack = $row["name"];
				$preview_href = htmlspecialchars("preview.php?user=$username&pack=$pack");
				echo "<li><a href=\"$preview_href\">$username/$pack</a></li>";
			});
		?>
		</ul>
	</body>
</html>
