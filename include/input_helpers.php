<?php
/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Miscellanous helpers for processing input paramaters
 */

/**
 * Parse a paramter string as a JSON object and return the PHP value
 * @param {String} $param The paramater string in JSON format
 * @returns {Array} A 2-item array containing the result at index 0 if successful or an error message at index 1
 */
function parse_param_from_json($param) {
	if ($param === null || $param === false)
		return [null, 'No Values Received'];

	$json = json_decode($param);

	if ($json === null)
		return [null, 'JSON Decode Error'];

	return [$json, null];
}
?>
