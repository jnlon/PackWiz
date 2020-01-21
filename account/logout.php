<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Log-out a user by unsetting session variable(s)
 */
session_start();
unset($_SESSION['user_id']);
header('Location: ' . '../');
?>
