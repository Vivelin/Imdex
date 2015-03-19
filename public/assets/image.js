/**
 * Returns the sum of the bottom margins of the element and all its parents.
 */
function getMarginBottom(elem) {
	var margin = parseInt(elem.css("margin-bottom"), 10);

	var $parent = elem.parent();
	while ($parent.parent().length) { // Fuck jQuery
		margin += parseInt($parent.css("margin-bottom"), 10);
		$parent = $parent.parent();
	}

	return margin;
}

/**
 * Sets the absolute height of the image container.
 */
function setContainerSize() {
	var $elem = $("#container");
	if ($elem.length) {
		var height = $(window).height();
		var top = $elem.offset().top;
		var margin = getMarginBottom($elem);

		$elem.height(Math.ceil(height - top - margin));
	}
}

/**
 * Attaches onClick handlers to all buttons that have an action specified in the
 * data-action attribute.
 */
function prepareButtons() {
	var $delete = $("a[data-action]");
	$delete.each(function(index, element) {
		var $element = $(element); // Again, fuck jQuery
		switch ($element.data("action")) {
			case "delete":
				$element.click(onDelete);
				break;
		}
	});
}

/**
 * Handles the `delete` button action.
 */
function onDelete(e) {
	$.post("/delete", { 
		"path": window.location.pathname,
		"file": view()
	})
	.done(function() {
		window.location.search = "";
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.error(jqXHR);

		var $button = $(e.target);
		$button.popup("remove");
		$button.popup({ "content": jqXHR.responseText || errorThrown });
		$button.popup("show");
	});
}

/**
 * Gets the value of the `view` query string parameter.
 */
function view() {
	var match = /[\?\&]view=?([^\&]*)/.exec(window.location.search);
	return match ? decodeURIComponent(match[1]) : null;
}

// Fuck CSS
setContainerSize();
prepareButtons();

$(window).resize(setContainerSize);
