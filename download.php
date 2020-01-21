<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Download a file or pack .zip archive based on query paramaters
 */

/* Valid Query Paramaters

--- download a zip file of pack (key required if pack is private)
download.php?user=...&pack=...
download.php?user=...&pack=...&key=...

--- download a single file from a pack (key required if pack is private)
download.php?user=...&pack=...&file=...
download.php?user=...&pack=...&file=...&key=...
*/

/** 
 * Terminate request with HTTP error 'Forbidden'
 */
function forbidden() {
	http_response_code(403);
	die('403 - Forbidden');
}

// Initialize PackDB
require('include/models/PackDB.php');
$PackDB = new PackDB();

// get query paramater inputs
$user_name = filter_input(INPUT_GET, 'user'); // mandatory
$pack_name = filter_input(INPUT_GET, 'pack'); // mandatory
$file_name = filter_input(INPUT_GET, 'file'); // optional, if downloading individual file
$key = filter_input(INPUT_GET, 'key'); // optional, mandatory if pack is not public

// get the pack id if secret_key is correct and given username owns the pack
$pack_id = $PackDB->get_pack_id_verify_secret($user_name, $pack_name, $key);

// if pack does not exist, or key is required and incorrect, block access
if ($pack_id === false || $pack_id === null)
	forbidden();

if ($file_name) {
	 // this request wants a specific file from the pack
	$content = $PackDB->get_pack_file_as_string($pack_id, $file_name);
	// the given filename has no content (does not exist in this pack)
	if ($content === null)
		forbidden();
	header('Content-Type: application/octet-stream');
	header("Content-Disposition: attachment; filename=\"$file_name\"");
	header('Content-Length: ' . strlen($content));
	die($content);
} else {
	// this request wants the whole pack as a zip
	// create a temporary zip file at $zip_file_path
	$zip_file_path = tempnam(sys_get_temp_dir(), 'PackWiz');
	$zip = new ZipArchive();
	$zip->open($zip_file_path);

	// for every file in this pack, add it to the temporary zip file
	$PackDB->for_each_file_in_pack($pack_id, function($row) use (&$zip) {
		$zip->addFromString($row['name'], $row['content']);
	});

	// close the zip file
	$zip->close();

	// set appropriate HTTP headers
	$zipname = 'PackWiz-' . $user_name . '-' . $pack_name . '.zip';
	header('Content-Type: application/zip');
	header("Content-Disposition: attachment; filename=\"$zipname\"");
	header('Content-Length: ' . filesize($zip_file_path));

	// echo the zip content and delete the temporary file
	readfile($zip_file_path);
	unlink($zip_file_path);
	die();
}

?>
