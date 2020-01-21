<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Define the UserDB class which abstracts database operations using the 'user' table
 */
include_once (dirname(__FILE__) . '/DB.php');

/**
 * Define UserDB. Like PackDB and FileDB, methods in this class comprise a
 * high-level API for CRUD operations on the database.
 */
class UserDB extends DB {

	/**
	 * Determine if an account with the given email already exists
	 * @param {String} $email email address
	 * @return {Boolean}
	 */
	public function email_exists($email) {
		$sql = 'SELECT * FROM users WHERE email = ?';
		return $this->row_exists($sql, [$email]);
	}

	/**
	 * Determine if an account with the given username already exists
	 * @param {String} $username username
	 * @return {Bool}
	 */
	public function name_exists($username) {
		$sql = 'SELECT * FROM users WHERE username = ?';
		return $this->row_exists($sql, [$username]);
	}

	/**
	 * Create an account with the given email, username, and password
	 * @param {String} $email email
	 * @param {String} $username username
	 * @param {String} $password password (will be hashed)
	 */
	public function create_account($email, $username, $password) {
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$sql = 'INSERT INTO users(email, username, password_hash) VALUES(?, ?, ?)';
		$this->sql_execute($sql, [$email, $username, $hash]);
	}

	/**
	 * Attempt to login a user with the given email and password
	 * @param {String} $email email
	 * @param {String} $password password (will be hashed)
	 * @return {Bool} whether login was successful
	 */
	public function verify_login($email, $password) {
		$sql = 'SELECT password_hash FROM users WHERE email = ?';
		$user_hash = $this->get_single_row($sql, [$email])['password_hash'];
		return password_verify($password, $user_hash);
	}

	/**
	 * Retrieve the user ID from an email address
	 * @param {String} $email email
	 * @return {Integer} user ID
	 */
	public function get_id_from_email($email) {
		$sql = 'SELECT id FROM users WHERE email = ?';
		return $this->get_single_row($sql, [$email])['id'];
	}

	/**
	 * Retrieve the user ID from a username
	 * @param {String} $username username
	 * @return {Integer} user ID
	 */
	public function get_id_from_username($username) {
		$sql = 'SELECT id FROM users WHERE username = ?';
		return $this->get_single_row($sql, [$username])['id'];
	}

	/**
	 * Retrieve a row containing various information about a user from an ID
	 * @param {Integer} $user_id user ID
	 * @return {Array} database row fetch result
	 */
	public function get_user_info($user_id) {
		$sql = 'SELECT u.id, u.username, u.email,
		(SELECT COUNT(*) FROM packs WHERE owner = u.id) as pack_count,
		(SELECT COUNT(*) FROM files WHERE owner = u.id) as file_count
		FROM users u WHERE id = ?';
		$row = $this->get_single_row($sql, [$user_id]);
		return $row;
	}

	/**
	 * Delete a user account including all owned files and packs
	 * @param {Integer} $user_id user ID
	 */
	public function delete_user($user_id) {
		$sql = 'DELETE FROM users WHERE id = ?';
		$this->sql_execute($sql, [$user_id]);
		$sql = 'DELETE FROM files WHERE owner = ?';
		$this->sql_execute($sql, [$user_id]);
		$sql = 'DELETE FROM packs WHERE owner = ?';
		$this->sql_execute($sql, [$user_id]);
	}

}
?>
