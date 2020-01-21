<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Delete the files identified by the POSTed list of file-ids 
 */

require('../../include/login_required.php');
require('../../include/input_helpers.php');

// parse the file id array from a json string in param 'ids'
list($file_id_array, $err) = parse_param_from_json(filter_input(INPUT_POST, 'ids'));
$file_id_array or die(json_encode(['error' => $err]));

// make sure at least one file is selected
if (count($file_id_array) <= 0)
	die(json_encode(['error' => 'No Files Selected']));

// delete the files in the database
require('../../include/models/FileDB.php');
$FileDB = new FileDB();
$FileDB->delete_files($_SESSION['user_id'], $file_id_array);

// return a success json string
echo json_encode(['success' => 'File(s) successfully deleted']);
?>
