<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Receive an uploaded file from a POST request, output a JSON object indicating error or success
 */

require('../../include/login_required.php');

// Map an upload error code to a human readable string
// Taken from PHP documentation at: https://www.php.net/manual/en/features.file-upload.errors.php
$error_msg_map = array(
	0 => 'Success',
	1 => 'Uploaded file exceeds max upload size',
	2 => 'Uploaded file exceeds the max file size',
	3 => 'Uploaded file was only partially uploaded',
	4 => 'No file was uploaded',
	5 => 'Unknown Error',
	6 => 'Missing a temporary folder',
	7 => 'Failed to write file to disk',
	8 => 'A PHP Extension stopped the file upload'
);

// check for error on uploaded file
$file = $_FILES['file'];

if ($file === null) {
	$max_upload = max(ini_get('post_max_size'), ini_get('upload_max_filesize'));
	die(json_encode(['error' => "No file received - maximum upload size is $max_upload"]));
}

if ($file['error']) {
	$msg = $error_msg_map[$file['error']];
	die(json_encode(['error' => $msg]));
}

require('../../include/models/FileDB.php');
$FileDB = new FileDB();
$FileDB->update_file($_SESSION['user_id'], $file['name'], $file['tmp_name'], $file['size']);

echo json_encode(['success' => 'File update succesful']);
?>
