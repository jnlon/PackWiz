/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Utility functions to help with table generation and fetch requests
 */

/**
 * This function generates HTML tables based on data retrieved from AJAX requests.
 *
 * The structure and content of tables are determined tablespec. Tablespec is
 * an array of objects containing 'th' and 'fn' keys. The 'th' value is a
 * string (or element) containing the table column header. The 'fn' value is a
 * function that takes some JSON object as a paramater (likely retrieved from
 * an AJAX endpoint) and returns a string (or element) that is inserted into
 * the appropriate cell.  Every object in jsonArray contains data for a single
 * table row. The 'fn' function returns the cell value for this row under the
 * column with heading 'th'.
 *
 * @param {Array} tableSpec an array of {'th':..., 'fn':...} objects described above
 * @param {Array} jsonArray an array of JSON objects (likely returned from an AJAX request)
 */
function tablify(tableSpec, jsonArray) {
	// make the first table row, ie the header
	const ths = tableSpec.map(ts => ts['th']);
	const header = $('<tr>')
		.append(ths.map(inner => $('<th>').append(inner)));

	// generate the array of rows by calling fn() on json the json object
	const rows = jsonArray.map((json) => {
		const toData = (ts) => $('<td>').append(ts.fn(json));
		return $('<tr>').append(tableSpec.map(toData));
	});

	// make and return the table with header and rows
	return $('<table>')
		.append(header)
		.append(rows);
}

/**
 * Make a POST request to endpoint using fetch(), using a query-paramater
 * formatted body string. Log the response message to an element called "#log"
 * @param {String} endpoint the url/server path to POST at
 * @param {String} body request body (query paramater formatted string)
 */
function postLog(endpoint, body) {
	const options = {
		method: 'POST',
		credentials: 'include',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: body
	};
	return fetch(endpoint, options)
		.then(r => r.json())
		.then(function(json) {
			// responses objects can either be {'success': (message)} or {'error': (message)}
			const result = ('success' in json) ? 'success' : 'error';
			// style the message based on the response type
			$('#log').empty().attr('class', result).text(json[result]);
			return json;
		});
}

/**
 * fetch() an endpoint serving JSON object(s) and pass the result to tablify()
 * using tableSpec. The result of tablify is then appended to the element
 * identified by elementID. See 'tablify' for more information about tableSpec
 */
function ajaxTablify(endpoint, tableSpec, elementID) {
	fetch(endpoint)
		.then(r => r.json())
		.then(function(jsonArray) {
			const table = tablify(tableSpec, jsonArray);
			$(elementID).html(table).hide().fadeIn(200); // Attach table to DOM
		});
}

// a global Util object. Makes these functions easier to spot when used in
// other files
const Util = {
	tablify: tablify,
	ajaxTablify: ajaxTablify,
	postLog: postLog
}

