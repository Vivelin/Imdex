(function () {
    'use strict';

    /**
     * Returns the sum of the bottom margins of the element and all its parents.
     */
    function getMarginBottom(elem) {
        var margin = parseInt(elem.css("margin-bottom"), 10),
            $parent = elem.parent();

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
        var $elem = $("#container"),
            height = $(window).height(),
            top,
            margin;

        if ($elem.length) {
            top = $elem.offset().top;
            margin = getMarginBottom($elem);

            $elem.height(Math.ceil(height - top - margin));
        }
    }

    /**
     * Gets the value of the `view` query string parameter.
     */
    function view() {
        var match = /[\?\&]view=?([^\&]*)/.exec(window.location.search);
        return match ? decodeURIComponent(match[1]) : null;
    }

    /**
     * Handles the `delete` button action.
     */
    function onDelete(e) {
        $.post("/delete", {
            "path": window.location.pathname,
            "file": view()
        }).done(function () {
            window.location.search = "";
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error(jqXHR);

            var $button = $(e.target);
            $button.popup("remove");
            $button.popup({ "content": jqXHR.responseText || errorThrown });
            $button.popup("show");
        });
    }

    /**
     * Handles the `zoom` button action.
     */
    function onZoom(e) {
        var $container = $("#container");

        if ($container.length) {
            $container.toggleClass("constrained");
        }
    }

    /**
     * Attaches onClick handlers to all buttons that have an action specified in the
     * data-action attribute.
     */
    function prepareButtons() {
        var $delete = $("a[data-action]");
        $delete.each(function (index, element) {
            var $element = $(element); // Again, fuck jQuery
            switch ($element.data("action")) {
            case "delete":
                $element.click(onDelete);
                break;
            case "zoom":
                $element.click(onZoom);
                break;
            default:
                break;
            }
        });
    }

    /**
     * Converts <time> elements to a string representation for the user's
     * current language.
     */
    function prepareTime() {
        $("time").each(function (index, element) {
            var datetime = $(element).attr("datetime"),
                date = new Date(datetime);
            $(element).text(date.toLocaleString());
        });
    }

    // Fuck CSS
    setContainerSize();
    prepareButtons();
    prepareTime();

    $(window).resize(setContainerSize);
}());