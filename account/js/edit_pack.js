/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Generate and attach a table for the Edit Pack page, with
 * callbacks necessary to update the list of files in a pack
 */

/**
 * Window load callback - refresh the list of files and set the 'Apply' button callback
 */
window.addEventListener('load', function() {
	// pack ID is retrieved from the 'pack_id' query paramater in the page URL
	const packID = (new URLSearchParams(window.location.search.substring(1))).get("id");
	document.querySelector('#apply').addEventListener('click', (e) => updatePackFiles(packID));
	refreshFiles(packID);
});

/**
 * POST an array of pack ID's to ajax/edit_pack_files.php to set the list of files in this pack.
 * File IDs are retrieved from the 'id' attribute of checked <input>
 * checkbox elements in the table.
 * @param {String} packID the ID of the pack we are currently editing
 */
function updatePackFiles(packID) {
	const selected = $('table :checked').toArray().map((e) => e.id);
	const body = 'ids=' + JSON.stringify(selected) + '&pack_id=' + packID;
	Util.postLog('ajax/edit_pack_files.php', body)
		.then((json) => refreshPacks(packID));
}

/**
 * Construct the HTML table with the given spec by querying ajax/files.php.
 * See 'util.js' for more information about table spec
 * @param {String} packID the ID of the pack we are currently editing.
 */
function refreshFiles(packID) {

	// the file checkbox will be checked if 'in_pack' is true, indicating this
	// file is already in the pack
	const checkBoxColumnFunc = (json) =>
		$('<input>')
			.addClass('table-checkbox')
			.attr('type', 'checkbox')
			.attr('id', json['id'])
			.attr('what', json['in_pack'])
			.prop('checked', json['in_pack']);

	const headerCheckBox = 
		$('<input>')
			.attr('type', 'checkbox')
			.on('click', () => $('.table-checkbox').click());

	const tableSpec = [
		{ th: 'In Pack', fn: checkBoxColumnFunc},
		{ th: 'File Name', fn: (json) => json['name'] },
		{ th: 'File Size', fn: (json) => json['bytes'] },
		{ th: 'Last Updated', fn: (json) => json['last_updated'] },
	]

	// construct and insert the resulting table at '#files'
	Util.ajaxTablify('ajax/files.php?pack_id=' + packID, tableSpec, '#files');
}
