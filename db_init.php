<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: (Re-)Initialize the database. This file can only be run from CLI using 'php -f db_init.ph'
 */

// die() if not running script from commandline
if (PHP_SAPI !== 'cli')
	die();

$sqls = [
	'DROP TABLE IF EXISTS pack_files',
	'DROP TABLE IF EXISTS packs',
	'DROP TABLE IF EXISTS files',
	'DROP TABLE IF EXISTS users',
	'CREATE TABLE users (
			id INT PRIMARY KEY AUTO_INCREMENT,
			email VARCHAR(255) UNIQUE NOT NULL,
			username VARCHAR(32) UNIQUE NOT NULL,
			password_hash TEXT NOT NULL
	)',
	'CREATE TABLE files (
			id INT PRIMARY KEY AUTO_INCREMENT,
			owner INT NOT NULL,
			last_updated DATETIME,
			name TEXT,
			bytes INT,
			content LONGBLOB,
			FOREIGN KEY (owner) REFERENCES users(id) ON DELETE CASCADE
	)',
	'CREATE TABLE packs (
			id INT PRIMARY KEY AUTO_INCREMENT,
			owner INT NOT NULL,
			name TEXT NOT NULL,
			secret_key TEXT,
			public BOOLEAN,
			FOREIGN KEY (owner) REFERENCES users(id) ON DELETE CASCADE
	)',
	'CREATE TABLE pack_files (
			pack_id INT NOT NULL,
			file_id INT NOT NULL,
			FOREIGN KEY (pack_id) REFERENCES packs(id) ON DELETE CASCADE,
			FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE
	)'
];

$dbh = require('include/connect.php');
foreach ($sqls as $sql) {
	echo $sql;
	echo "\n	=> [" . $dbh->exec($sql) . "]\n";
}

echo "DONE.";
?>
