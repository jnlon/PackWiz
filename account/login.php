<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Log-in user if a valid email and password was POSTed
 */

// get form inputs
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

// init UserDB
require('../include/models/UserDB.php');
$UserDB = new UserDB();

// verify login was successful
if (!$UserDB->verify_login($email, $password))
	die('Login unsuccessful');

// create the session
session_start();
$_SESSION['user_id'] = $UserDB->get_id_from_email($email);
header('Location: ' . 'index.php');
?>
