<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Create a pack based on the POSTed data. Generate a secret key is
 * pack is private.
 */

require('../../include/login_required.php');
require('../../include/models/PackDB.php');
$PackDB = new PackDB();

// generate a 32-character hex string from 16 pseudo-random bytes
function gen_secret_key() {
	return implode(array_map(
			function() {return sprintf("%02x", rand(0, 255));},
			range(1, 16)
		)
	);
}

// form inputs
$user_id = $_SESSION['user_id'];
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$public = filter_input(INPUT_POST, 'public') ? true : false;
$secret_key = (! $public) ? gen_secret_key() : null;

// check that a pack name is provided and not empty
if ($name === null || $name === false || $name === '')
	die(json_encode(['error' => 'No pack name given']));

// check that a pack with this name does not already exist
if ($PackDB->pack_name_exists($user_id, $name))
	die(json_encode(['error' => 'A pack with this name already exists']));

// create the pack
try {
	$PackDB->create_pack($name, $user_id, $secret_key, $public);
} catch (PDOException $e) {
	die(json_encode(['error' => 'Database Exception: ', $e->getMessage()]));
}

echo json_encode(['success' => 'Pack successfully created']);
?>
