/* Name: Jasper Hanlon
 * Date: 2019-12-2
 * Purpose: Generate HTML table for File Library page and add callbacks
 * necessary to upload or remove existing files
 */

/**
 * Window load callback - Refresh files, setup file upload & delete callbacks
 */
window.addEventListener('load', function() {
	refreshFiles();
	setupFileUpload(document.forms['upload-form']);
	document.querySelector('#delete-selected').addEventListener('click', deleteSelected);
});

/**
 * Construct the File Library HTML table from JSON data returned by
 * ajax/files.php. See 'util.js' documentation for more information about
 * table spec
 */
function refreshFiles() {
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
		{ th: 'File Name', fn: (json) => json['name'] },
		{ th: 'File Size', fn: (json) => json['bytes'] },
		{ th: 'Last Updated', fn: (json) => json['last_updated'] },
	]

	// construct and insert the resulting table at '#files'
	Util.ajaxTablify('ajax/files.php', tableSpec, '#files');
}

/**
 * POST an array of file ID's to delete_file.php. File IDs are retrieved
 * from the 'id' attribute of checked <input> checkbox elements in the table.
 */
function deleteSelected() {
	const selectedIDs = $('table td :checked').toArray().map((e) => e.id);
	const body = 'ids=' + JSON.stringify(selectedIDs);
	Util.postLog('ajax/delete_file.php', body)
		.then((json) => refreshFiles());
}

/**
 * Setup callbacks related file uploads using the XMLHttpRequest API.
 * (See comment below for why this API is used over fetch)
 * @param {HTMLFormElement} form the upload form
 */
function setupFileUpload(form) {
	/**
	 * Update progress bar based on upload percent. Called periodically as files are uploaded.
	 */
	function updateProgress(e) {
		const percent = Math.round((e.loaded / e.total) * 100);
		$('#upload-progress-bar')
			.css('width', percent + '%')
			.text(percent + '%')
	}

	/**
	 * Update upload message and CSS. Called once the file upload has begun
	 */
	function transferStart(e) {
		$('#upload-message')
			.text('Uploading...');
		$('#upload-progress-bar')
			.css('display', 'block');
	}

	/**
	 * Update the upload message. Called once the file upload is complete.
	 */
	function transferComplete() {
		const json = this.response;
		const code = Object.keys(json)[0]; // either 'success' or 'error'
		message = $('<span>').addClass(code).text(json[code]);
		$('#upload-message').html(message);
		refreshFiles();
	}

	// 'Upload File' submit button callback
	form.addEventListener('submit', function(e) {
		e.preventDefault();

		/*
		 * NOTE: The old XMLHttpRequest() API is required to implement the
		 * upload progress meter because it is not currently possible with fetch()
		 * See: https://stackoverflow.com/questions/35711724/upload-progress-indicators-for-fetch
		 */
		const req = new XMLHttpRequest();
		req.responseType = 'json';
		req.upload.addEventListener('loadstart', transferStart);
		req.upload.addEventListener('progress', updateProgress);
		req.addEventListener('load', transferComplete);

		req.open('POST', 'ajax/upload.php', true);
		req.send(new FormData(form)); // FormData always serializes as multipart/form-data
	});
}
