<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Create a user with the given POSTed credentials
 */

// get form inputs
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password');

// verify all inputs exist, are secure, and within database type ranges
$inputs = [$email, $username, $password];
if (in_array(false, $inputs, true) || in_array(null, $inputs, true))
	die('Error: Missing one or more form inputs');

if (! (strlen($email) <= 255))
	die('Error: Email must be <= 255 characters');

if (! (strlen($username) <= 255))
	die('Error: Username must be >= 8 characters');

if (! (strlen($password) >= 8))
	die('Error: Password must be >= 8 characters');

// connect to database with UserDB instance
require('../include/models/UserDB.php');
$UserDB = new UserDB();

// check that an account with these credentials does not already exist
if ($UserDB->name_exists($username))
	die('Username already taken');

if ($UserDB->email_exists($email))
	die('Email already exists');

// attempt to create the account
try {
	$UserDB->create_account($email, $username, $password);
} catch (PDOException $e) {
	die('Account could not be created. Error message: ' . $e->getMessage());
}

echo 'Account created successfully';
?>
