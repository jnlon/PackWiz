<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Connect to database and return DB handle
 */
// designed to be used like: $dbh = require('connect.php');
$dbh = new PDO("mysql:host=localhost;dbname=000799827", "000799827", "19980412");
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $dbh;
?>
