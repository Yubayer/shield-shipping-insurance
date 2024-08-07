'use strict';

var React = require('react');
var debounce = require('../../../utilities/debounce.js');

const DEBOUNCE_PERIOD = 250;
const PADDING_IN_SELECT_MODE = 92;
function useIsBulkActionsSticky(selectMode) {
  const hasIOSupport = typeof window !== 'undefined' && Boolean(window.IntersectionObserver);
  const [isBulkActionsSticky, setIsSticky] = React.useState(false);
  const [bulkActionsAbsoluteOffset, setBulkActionsAbsoluteOffset] = React.useState(0);
  const [bulkActionsMaxWidth, setBulkActionsMaxWidth] = React.useState(0);
  const [bulkActionsOffsetLeft, setBulkActionsOffsetLeft] = React.useState(0);
  const bulkActionsIntersectionRef = React.useRef(null);
  const tableMeasurerRef = React.useRef(null);
  const handleIntersect = entries => {
    entries.forEach(entry => {
      setIsSticky(!entry.isIntersecting);
    });
  };
  const options = {
    root: null,
    rootMargin: '-12px',
    threshold: 0
  };
  const observerRef = React.useRef(hasIOSupport ? new IntersectionObserver(handleIntersect, options) : null);
  const computeTableDimensions = React.useCallback(() => {
    const node = tableMeasurerRef.current;
    if (!node) {
      return {
        maxWidth: 0,
        offsetHeight: 0,
        offsetLeft: 0
      };
    }
    const box = node.getBoundingClientRect();
    const paddingHeight = selectMode ? PADDING_IN_SELECT_MODE : 0;
    const offsetHeight = box.height - paddingHeight;
    const maxWidth = box.width;
    const offsetLeft = box.left;
    setBulkActionsAbsoluteOffset(offsetHeight);
    setBulkActionsMaxWidth(maxWidth);
    setBulkActionsOffsetLeft(offsetLeft);
  }, [selectMode]);
  React.useEffect(() => {
    computeTableDimensions();
    const debouncedComputeTableHeight = debounce.debounce(computeTableDimensions, DEBOUNCE_PERIOD, {
      trailing: true
    });
    window.addEventListener('resize', debouncedComputeTableHeight);
    return () => window.removeEventListener('resize', debouncedComputeTableHeight);
  }, [computeTableDimensions]);
  React.useEffect(() => {
    const observer = observerRef.current;
    if (!observer) {
      return;
    }
    const node = bulkActionsIntersectionRef.current;
    if (node) {
      observer.observe(node);
    }
    return () => {
      observer?.disconnect();
    };
  }, [bulkActionsIntersectionRef]);
  return {
    bulkActionsIntersectionRef,
    tableMeasurerRef,
    isBulkActionsSticky,
    bulkActionsAbsoluteOffset,
    bulkActionsMaxWidth,
    bulkActionsOffsetLeft,
    computeTableDimensions
  };
}

exports.useIsBulkActionsSticky = useIsBulkActionsSticky;
