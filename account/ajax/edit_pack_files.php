<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Update the list of files contained in a pack
 */

require('../../include/login_required.php');
require('../../include/input_helpers.php');

// parse the file id array from a json string in param 'ids'
list($file_id_array, $err) = parse_param_from_json(filter_input(INPUT_POST, 'ids'));
$file_id_array !== null or die(json_encode(['error' => $err]));

// get the ID of the pack being modified
$pack_id = filter_input(INPUT_POST, 'pack_id');
$pack_id or die(json_encode(['error' => 'Pack ID not provided']));

require('../../include/models/PackDB.php');
$PackDB = new PackDB();

// make sure the user owns this pack
if (! $PackDB->user_owns_pack($_SESSION['user_id'], $pack_id))
	die(json_encode(['error' => 'You are not the owner of this pack']));

// apply the updates
$PackDB->update_files_in_pack($pack_id, $file_id_array);

// return a success json string
echo json_encode(['success' => 'Pack updated sucessfully']);
?>
