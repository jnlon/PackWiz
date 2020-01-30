<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Receive an uploaded file from a POST request, output a JSON object indicating error or success
 */

require('../../include/login_required.php');
require('../../include/models/UserDB.php');

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

$file = $_FILES['file'];

// check that we recieved the uploaded file
if ($file === null) {
	$max_upload = max(ini_get('post_max_size'), ini_get('upload_max_filesize'));
	die(json_encode(['error' => "No file received - maximum upload size is $max_upload"]));
}

// check for error on uploaded file
if ($file['error']) {
	$msg = $error_msg_map[$file['error']];
	die(json_encode(['error' => $msg]));
}

// demo restriction: ensure account file_count does not exceed 10
$file_limit = 10;
$UserDB = new UserDB();
$info_row = $UserDB->get_user_info($_SESSION['user_id']);
if ($info_row['file_count'] >= $file_limit) {
	die(json_encode(['error' => 'Too many files - Account file limit reached']));
}

require('../../include/models/FileDB.php');
$FileDB = new FileDB();
$FileDB->update_file($_SESSION['user_id'], $file['name'], $file['tmp_name'], $file['size']);

echo json_encode(['success' => 'File update succesful']);
?>
