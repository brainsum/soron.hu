"use strict";

(function (document, $) {
    if (! $) return;

    // Import important layout scoped modifications

    var item = $("#root,#load");
    var node = document.createElement('div');

    item[0].classList.add('js');
    node.innerHTML = item[1].childNodes[0].textContent;
    node = node.childNodes[0];

    item[1].parentNode.replaceChild(node, item[1]);

})(document, document.querySelectorAll ? function (query) {return document.querySelectorAll(query)} : false);
