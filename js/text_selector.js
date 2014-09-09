if (!window.Readtext) {
    Readtext = {};
}

Readtext.Selector = {};
Readtext.Selector.getSelected = function () {
    var t = '';
    if (windows.getSelection) {
        t = windows.getSelection();
    } else if (document.getSelection) {
        t = document.getSelection();
    } else if (document.selection) {
        t = document.selection.createRange().text;
    }
    return t;
}

Readtext.Selector.mouseup = function () {
    var st = Readtext.Selector.getSelected();
    if (st != '') {
        alert("You have selected:\n" + st);
    }
}

$(document).ready(function () {
    $(document).bind("mouseup", Readtext.Selector.mouseup);
});
	