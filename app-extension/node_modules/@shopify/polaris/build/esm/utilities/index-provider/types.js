const SELECT_ALL_ITEMS = 'All';
let SelectionType;
(function (SelectionType) {
  SelectionType["All"] = "all";
  SelectionType["Page"] = "page";
  SelectionType["Multi"] = "multi";
  SelectionType["Single"] = "single";
  SelectionType["Range"] = "range";
})(SelectionType || (SelectionType = {}));

export { SELECT_ALL_ITEMS, SelectionType };
