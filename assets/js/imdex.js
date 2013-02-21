/**
 * Shows a Bootstrap Popover message on an element.
 *
 * @param <String> msg
 * @param <Element> target 
 */
function showError(msg, target) {
	$(target).popover({ 
		title: 'Error',
		content: msg, 
		html: true, 
		trigger: 'manual' 
	});

	$(target).popover('show');
	setTimeout(function() { 
		$(target).popover('destroy'); 
	}, 5000);
}

/**
 * Deletes the specified file and reloads the page on success.
 *
 * @param <String> file
 * @param <Element> sender
 */
function deleteFile(file, sender) {
	$.post('/do.php?action=deleteFile', { filename: file }, function(data) {
		if (data.error) {
			showError(data.message, sender);
		} else {
			window.location.reload();
		}
	}, 'json');
}