<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Delete the packs identified by the POSTed list of pack-ids
 */

require('../../include/login_required.php');
require('../../include/input_helpers.php');

// parse the pack id array from a json string in param 'ids'
list($pack_id_array, $err) = parse_param_from_json(filter_input(INPUT_POST, 'ids'));
$pack_id_array !== null or die(json_encode(['error' => $err]));

// make sure at least one pack is selected
if (count($pack_id_array) == 0)
	die(json_encode(['error' => 'No Packs Selected']));

// delete the packs in the database
require('../../include/models/PackDB.php');
$PackDB = new PackDB();
$PackDB->delete_packs($_SESSION['user_id'], $pack_id_array);

// return a success json string
echo json_encode(['success' => 'Pack(s) successfully deleted']);
?>
