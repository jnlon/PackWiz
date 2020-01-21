<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Define the FileDB class which abstracts database operations using the 'file' table
 */
include_once (dirname(__FILE__) . '/DB.php');

/**
 * Define FileDB. Like PackDB and UserDB, methods in this class comprise a
 * high-level API for CRUD operations on the database.
 */
class FileDB extends DB {

	public function update_file($owner, $name, $path, $bytes) {
		// remove file with this name if it exists for this owner
		$sql = 'DELETE FROM files WHERE owner = ? AND name = ?';
		$this->sql_execute($sql, [$owner, $name]);
		// insert the uploaded file
		$sql = 'INSERT INTO files(owner, last_updated, name, bytes, content) VALUES(?, ?, ?, ?, ?)';
		date_default_timezone_set('America/Toronto');
		$last_updated = date('Y-m-d H:i:s');
		$content = file_get_contents($path);
		$this->sql_execute($sql, [$owner, $last_updated, $name, $bytes, $content]);
	}

	public function delete_files($owner, $file_id_array) {
		$sql = 'DELETE FROM files WHERE owner = ? AND id = ?';
		$stmt = $this->dbh->prepare($sql);
		foreach ($file_id_array as $file_id)
			$stmt->execute([$owner, $file_id]);

		$sql = 'DELETE FROM pack_files WHERE file_id = ?';
		$stmt = $this->dbh->prepare($sql);
		foreach ($file_id_array as $file_id)
			$stmt->execute([$file_id]);
	}

	public function for_each_user_file($user_id, $handle_row_func) {
		$sql = 'SELECT * FROM files WHERE owner = ?';
		$this->for_each_row($this->sql_execute($sql, [$user_id]), $handle_row_func);
	}

	public function get_file_content_as_string($file_id) {
		$sql = 'SELECT content from files where id = ? LIMIT 1';
		return $this->get_single_row($sql, [$file_id])['content'];
	}
}
?>
