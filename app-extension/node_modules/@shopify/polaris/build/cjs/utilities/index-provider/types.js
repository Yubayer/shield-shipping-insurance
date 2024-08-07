'use strict';

const SELECT_ALL_ITEMS = 'All';
exports.SelectionType = void 0;
(function (SelectionType) {
  SelectionType["All"] = "all";
  SelectionType["Page"] = "page";
  SelectionType["Multi"] = "multi";
  SelectionType["Single"] = "single";
  SelectionType["Range"] = "range";
})(exports.SelectionType || (exports.SelectionType = {}));

exports.SELECT_ALL_ITEMS = SELECT_ALL_ITEMS;
