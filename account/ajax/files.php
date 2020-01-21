<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Output a JSON array with objects describing user files
 */

require('../../include/login_required.php');
require('../../include/models/FileDB.php');
$FileDB = new FileDB();

/**
 * Return a list of file names in this pack as strings
 * @param {Integer} $pack_id pack id
 * @param {PDO} $dbh a DB handle to re-use
 * @return {Array} an array of file name strings
 */
function get_all_file_names_in_pack($pack_id, $dbh) {
	require('../../include/models/PackDB.php');
	$PackDB = new PackDB($dbh); // re-use the given database handle
	$file_name_array = [];
	$PackDB->for_each_file_in_pack($pack_id, function($row) use (&$file_name_array) {
		array_push($file_name_array, $row['name']);
	});
	return $file_name_array;
}

// Optionally, this endpoint takes a 'pack_id' paramater.
// if provided, we retrieve a list a filenames in the pack ('in_pack_array')
// which is used to determine the value of the 'in_pack' value of each JSON
// object in the returned array.
//
// This is a quick and dirty way to indicate which files are assigned to this
// pack, which is used on edit_pack.php to assign check-boxes on load
$in_pack_array = [];
$pack_id = filter_input(INPUT_GET, 'pack_id');
if ($pack_id !== null || $pack_id !== false)
	$in_pack_array = get_all_file_names_in_pack($pack_id, $FileDB->getHandle());

// for each file owned by the user, generate an assoc-array of file data and
// push it to $files_json_array. this structure will be encoded as a JSON
// string when all user file rows have been processed
$files_json_array = [];
$FileDB->for_each_user_file($_SESSION['user_id'], function($row) use (&$files_json_array, $in_pack_array) {
	array_push($files_json_array, array(
		'id' => $row['id'],
		'name' => $row['name'],
		'bytes' => $row['bytes'],
		'last_updated' => $row['last_updated'],
		'in_pack' => in_array($row['name'], $in_pack_array)
	));
});

// echo the structure as an array of JSON objects
echo json_encode($files_json_array);
?>

