<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Preview a pack before download, show a table of files in the pack
 */

$user = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING);
$pack = filter_input(INPUT_GET, 'pack', FILTER_SANITIZE_STRING);
$key = filter_input(INPUT_GET, 'key');

require('include/models/PackDB.php');
require('include/models/UserDB.php');
$PackDB = new PackDB();

$pack_id = $PackDB->get_pack_id_verify_secret($user, $pack, $key);
$err = $pack_id === false || $pack_id === null;

$download_params = "user=$user&pack=$pack";
if ($key !== null && $key !== false)
	$download_params .= "&key=$key";

$download_params = htmlspecialchars($download_params);

?>
<!doctype html>
<html lang="en">
	<head>
		<title>[PW] Download Preview</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="main.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<h1>Download Preview</h1>
		<?php if ($err): ?>
		<p class="error center"> Invalid Parameters </p>
		<?php else: ?>
		<h2><?=$user?>/<?=$pack?></h2>
		<p class="bold"> <a href="download.php?<?= $download_params ?>">Download As Zip</a></p>
		<h3>Pack Contents</h3>
		<table>
		<tr>
			<th>Name</th>
			<th>Size</th>
			<th>Last Updated</th>
		</tr>
		<?php
			$PackDB->for_each_file_in_pack($pack_id, function($row) use ($download_params) {
				$file_href = "download.php?" . $download_params . '&amp;file=' . $row['name'];
				$tds = "<td> <a href=\"$file_href\">$row[name]</a></td>";
				$tds .= "<td>$row[bytes]</td>";
				$tds .= "<td>$row[last_updated]</td>";
				echo "<tr>$tds</tr>";
			});
		?>
		</table>
		<?php endif ?>
	</body>
</html>
