<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Define the abstract base class DB
 */

/**
 * DB base class. Methods in this class are mostly helpers designed
 * to make implementation of higher-level features in dervived classes
 * easier. The only state tracked is the database handle.
 */
abstract class DB {

	// a database handle shared by subclasses
	protected $dbh = false;

	/**
	 * Construct the object re-using the database handle if provided,
	 * otherwise use a fresh DB connection
	 * @param {PDO} $dbh [optional] The database handle
	 */
	public function __construct($dbh = false) {
		// if we are not re-using an existing handle passed by caller,
		// start a new connection to the database automatically
		if (! $dbh) {
			$this->dbh = require(dirname(__FILE__) . '/../connect.php');
		} else {
			$this->dbh = $dbh;
		}
	}

	/**
	 * Return the database handle in-use by this object
	 * @param {PDO} The database handle
	 */
	public function getHandle() {
		return $this->dbh;
	}

	/**
	 * Execute the SQL string using the given paramaters and return the statement
	 * @param {String} $sql The SQL string
	 * @param {Array} $params An array of paramaters for the statement
	 * @return {PDOStatement} The statement
	 */
	protected function sql_execute($sql, $params) {
		$s = $this->dbh->prepare($sql);
		$s->execute($params);
		return $s;
	}

	/**
	 * Execute the SQL string with the given paramaters and return a single row
	 * @param {String} $sql The SQL string
	 * @param {Array} $params An array of paramaters for the statement
	 * @return {Array} The single row
	 */
	protected function get_single_row($sql, $params) {
		return $this->sql_execute($sql, $params)->fetch();
	}

	/**
	 * Execute the SQL string with the given paramaters and return a single row
	 * @param {String} $sql The SQL string
	 * @param {Bool} true if the row exists, otherwise false
	 */
	protected function row_exists($sql, $params) {
		return $this->get_single_row($sql, $params) !== false;
	}

	/**
	 * Execute the provided function for every row that can be fetched by statement
	 * @param {PDOStatement} $statement The PDO statement
	 * @param {Function} A callable function that accepts a fetched row as its only argument
	 */
	protected function for_each_row($statement, $func) {
		while ($row = $statement->fetch()) {
			$func($row);
		}
	}
}
?>
