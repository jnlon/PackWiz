<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Delete a user account if the POSTed password is correct
 */
require('../include/login_required.php');
require('../include/models/UserDB.php');

$UserDB = new UserDB();
$user_id = $_SESSION['user_id'];
$email = $UserDB->get_user_info($user_id)['email'];
$password = filter_input(INPUT_POST, 'password');

// if the provided password is correct, delete the account, otherwise dont
// do anything
if ($UserDB->verify_login($email, $password)) {
	$UserDB->delete_user($user_id);
	session_destroy();
	header('Location: ' . '../login.php');
} else {
	die('Password incorrect, account not deleted');
}
?>
