<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Output a JSON array with objects describing user packs
 */

require('../../include/login_required.php');
require('../../include/models/PackDB.php');
require('../../include/models/UserDB.php');
$PackDB = new PackDB();
$UserDB = new UserDB($PackDB->getHandle());
$username = $UserDB->get_user_info($_SESSION['user_id'])['username'];

// use the callback function to fill pack_json_array with associatve arrays containing
// relavent row data. We must pass $pack_json_array as a reference (using '&')
// because we mutate the array state for use later in the current (non-callback) scope

$pack_json_array = [];

$PackDB->for_each_user_pack($_SESSION['user_id'], function($row) use (&$pack_json_array, $username) {
	$secret_key_param = ($row['secret_key']) ? ('&key=' . $row['secret_key']) : '';
	array_push($pack_json_array, array(
		'id' => $row['id'],
		'name' => $row['name'],
		'public' => $row['public'] ? 'yes' : 'no',
		'secret_key' => $row['secret_key'],
		'download_qs' => '?user=' . $username . '&pack=' . $row['name'] . $secret_key_param,
		'file_count' => $row['file_count'],
	));
});

// echo pack_json_array as a json string
echo json_encode($pack_json_array);
?>
