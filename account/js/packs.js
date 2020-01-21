/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Generate HTML table for Pack Library page and add callbacks
 * necessary to add or remove packs
 */

/**
 * Window load callback - Refresh packs, setup create pack form and deletion controls
 */
window.addEventListener('load', function() {
	refreshPacks();
	setupCreatePack();
	document.querySelector('#delete-selected').addEventListener('click', deleteSelected);
});

/**
 * Setup the "Create Pack" form submit callback.
 */
function setupCreatePack() {
	const form = document.forms['create-pack'];
	form.addEventListener('submit', function(e) {
		e.preventDefault();
		// convert form data into query param string
		const form = document.forms['create-pack'];
		const body = new URLSearchParams(new FormData(form));
		// send body to create_pack.php
		Util.postLog('ajax/create_pack.php', body)
			.then((json) => refreshPacks());
	});
}

/**
 * POST an array of pack ID's to delete_pack.php. Pack IDs are retrieved from
 * the 'id' attribute of checked <input> checkbox elements in the table.
 */
function deleteSelected() {
	const selectedIDS = $('table td :checked').toArray().map((e) => e.id);
	const body = 'ids=' + JSON.stringify(selectedIDS);
	Util.postLog('ajax/delete_pack.php', body)
		.then((json) => refreshPacks());
}

/**
 * Construct the Pack Library HTML table from JSON data returned by
 * ajax/packs.php. See 'util.js' documentation for more information about
 * table spec
 */
function refreshPacks() {
	const packNameColumnFunc = (json) =>
		$('<a>')
			.attr('href', 'edit_pack.php?id=' + json['id'])
			.text(json['name']);

	const checkBoxColumnFunc = (json) =>
		$('<input>')
			.addClass('table-checkbox')
			.attr('type', 'checkbox')
			.attr('id', json['id']);

	const headerCheckBox =
		$('<input>')
			.attr('type', 'checkbox')
			.on('click', () => $('.table-checkbox').click());

	const tableSpec = [
		{ th: headerCheckBox, fn: checkBoxColumnFunc },
		{ th: 'Pack Name', fn: packNameColumnFunc },
		{ th: 'Public', fn: (json) => json['public'] },
		{ th: 'Files', fn: (json) => json['file_count'] },
		//{ th: 'Secret Key', fn: (json) => json['secret_key'] },
		{ th: 'Download', fn: (json) => $('<a>').text('Download Zip').attr('href', '../download.php' + json['download_qs']) },
		{ th: 'Preview', fn: (json) => $('<a>').text('Preview').attr('href', '../preview.php' + json['download_qs']) },
	]

	Util.ajaxTablify('ajax/packs.php', tableSpec, '#packs');
}
