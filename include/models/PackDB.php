<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Define the PackDB class which abstracts database operations using the 'pack' and 'pack_files' table
 */

include_once (dirname(__FILE__) . '/DB.php');

/**
 * Define PackDB. Like FileDB and UserDB, methods in this class comprise a
 * high-level API for CRUD operations on the database.
 */
class PackDB extends DB {

	/**
	 * Register a pack with the given user and settings.
	 * @param {String} $pack_name pack name
	 * @param {Integer} $owner user ID
	 * @param {String} $secret_key secret key
	 * @param {Boolean} $public whether this pack is public
	 */
	public function create_pack($pack_name, $owner, $secret_key, $public) {
		$sql = 'INSERT INTO packs(name, owner, secret_key, public) VALUES(?,?,?,?)';
		$this->sql_execute($sql, [$pack_name, $owner, $secret_key, ($public ? 1 : 0)]);
	}

	/**
	 * Determine if a user owns a pack
	 * @param {Integer} $owner_id user ID
	 * @param {Integer} $pack_id pack ID
	 * @return {Boolean} true if the user owns this pack, otherwise false
	 */
	public function user_owns_pack($owner_id, $pack_id) {
		$sql = 'SELECT * FROM packs WHERE owner = ? and id = ?';
		return $this->row_exists($sql, [$owner_id, $pack_id]);
	}

	/**
	 * Determine if the user owns a pack with the given name
	 * @param {Integer} $owner_id user ID
	 * @param {String} $pack_name pack name
	 * @return {Boolean} true if the user owns a pack with the given name
	 */
	public function pack_name_exists($owner_id, $pack_name) {
		$sql = 'SELECT * FROM packs WHERE owner = ? AND name = ?';
		return $this->row_exists($sql, [$owner_id, $pack_name]);
	}

	/**
	 * Execute a function for every pack owned by user
	 * @param {Integer} $user_id user ID
	 * @param {Function} $handle_row_func the function
	 */
	public function for_each_user_pack($user_id, $handle_row_func) {
		$sql = 'SELECT p.*, (SELECT count(*) FROM pack_files WHERE pack_id = p.id) AS file_count
		FROM packs p WHERE p.owner = ?';
		$s = $this->sql_execute($sql, [$user_id]);
		$this->for_each_row($s, $handle_row_func);
	}

	/**
	 * Execute a function for every file in the given pack
	 * @param {Integer} $pack_id user ID
	 * @param {Function} $handle_row_func the function
	 */
	public function for_each_file_in_pack($pack_id, $handle_row_func) {
		$sql = 'SELECT id,name,last_updated,content,bytes FROM files
		WHERE id IN (SELECT file_id FROM pack_files WHERE pack_id = ?)';
		$s = $this->sql_execute($sql, [$pack_id]);
		$this->for_each_row($s, $handle_row_func);
	}

	/**
	 * Execute a function for every public pack owned by any user
	 * @param {Function} $handle_row_func the function
	 */
	public function for_each_public_pack($handle_row_func) {
		$sql = 'SELECT p.*, u.username FROM packs p
		INNER JOIN users u ON p.owner = u.id
		WHERE p.public = TRUE';
		$s = $this->sql_execute($sql, []);
		$this->for_each_row($s, $handle_row_func);
	}

	/**
	 * Return the pack id for a pack owned by a user with the given pack/username.
	 * If the pack is key protected verify the given key matches. Otherwise return false.
	 * @param {String} $owner_name owning account username
	 * @param {String} $pack_name name given to the pack
	 * @param {String} $secret_key pack secret key
	 * @return {Integer|Boolean} the pack id, or false if key or pack or username was incorrect
	 */
	public function get_pack_id_verify_secret($owner_name, $pack_name, $secret_key) {
		$sql = 'SELECT * FROM packs
		WHERE name = ?
		AND owner = (SELECT id FROM users WHERE username = ?)';
		$row = $this->get_single_row($sql, [$pack_name, $owner_name]);
		if ($row['secret_key'] !== null && $row['secret_key'] !== $secret_key)
			return false;
		return $row['id'];
	}

	/**
	 * Return a row containing file content and length in bytes
	 * @param {Integer} $pack_id pack id
	 * @param {String} $file_name file name in pack
	 * @return {Array} result row containing file content and byte length
	 */
	public function get_pack_file_as_string($pack_id, $file_name) {
		$sql = 'SELECT f.content,f.bytes FROM files f
		INNER JOIN pack_files pf ON pf.file_id = f.id
		INNER JOIN packs p ON p.id = pf.pack_id
		WHERE
			p.id = ? AND
			f.name = ?
		LIMIT 1';
		return $this->get_single_row($sql, [$pack_id, $file_name])['content'];
	}

	/**
	 * Delete all packs with the given IDs if they are owned by owner
	 * @param {Integer} $owner user ID
	 * @param {Array} $pack_id_array array of pack IDs
	 */
	public function delete_packs($owner, $pack_id_array) {
		$sql = 'DELETE FROM packs WHERE owner = ? AND id = ?';
		$stmt = $this->dbh->prepare($sql);
		foreach ($pack_id_array as $pack_id)
			$stmt->execute([$owner, $pack_id]);

		// remove all pack<->file associations too
		$sql = 'DELETE FROM pack_files WHERE pack_id = ?';
		$stmt = $this->dbh->prepare($sql);
		foreach ($pack_id_array as $pack_id)
			$stmt->execute([$pack_id]);
	}
	
	/**
	 * Set the list of files in with this pack
	 * @param {Integer} $pack_id ID of pack we are setting
	 * @param {Array} $file_id_array a list of file IDs
	 */
	public function update_files_in_pack($pack_id, $file_id_array) {
		// remove all files currently associated with this pack
		$sql = 'DELETE FROM pack_files WHERE pack_id = ?';
		$this->sql_execute($sql, [$pack_id]);

		// set the new files associated wth this pack
		$sql = 'INSERT INTO pack_files(pack_id, file_id) VALUES(?, ?)';
		$stmt = $this->dbh->prepare($sql);
		foreach ($file_id_array as $file_id)
			$stmt->execute([$pack_id, $file_id]);
	}
}
?>
