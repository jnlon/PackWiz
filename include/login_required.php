<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Start a PHP session and show an error if not logged in
 */
session_start();
if(!isset($_SESSION['user_id']))
	die("You must be logged-in to access this resource");
?>
