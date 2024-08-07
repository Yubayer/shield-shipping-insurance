'use strict';

var React = require('react');
var focus = require('../../utilities/focus.js');
var shared = require('../shared.js');
var setActivatorAttributes = require('./set-activator-attributes.js');
var PopoverOverlay = require('./components/PopoverOverlay/PopoverOverlay.js');
var Pane = require('./components/Pane/Pane.js');
var Section = require('./components/Section/Section.js');
var Portal = require('../Portal/Portal.js');

// TypeScript can't generate types that correctly infer the typing of
// subcomponents so explicitly state the subcomponents in the type definition.
// Letting this be implicit works in this project but fails in projects that use
// generated *.d.ts files.
const PopoverComponent = /*#__PURE__*/React.forwardRef(function Popover({
  activatorWrapper = 'div',
  children,
  onClose,
  activator,
  preventFocusOnClose,
  active,
  fixed,
  ariaHaspopup,
  preferInputActivator = true,
  zIndexOverride,
  ...rest
}, ref) {
  const [activatorNode, setActivatorNode] = React.useState();
  const overlayRef = React.useRef(null);
  const activatorContainer = React.useRef(null);
  const WrapperComponent = activatorWrapper;
  const id = React.useId();
  function forceUpdatePosition() {
    overlayRef.current?.forceUpdatePosition();
  }
  React.useImperativeHandle(ref, () => {
    return {
      forceUpdatePosition
    };
  });
  const setAccessibilityAttributes = React.useCallback(() => {
    if (activatorContainer.current == null) {
      return;
    }
    const firstFocusable = focus.findFirstFocusableNodeIncludingDisabled(activatorContainer.current);
    const focusableActivator = firstFocusable || activatorContainer.current;
    const activatorDisabled = 'disabled' in focusableActivator && Boolean(focusableActivator.disabled);
    setActivatorAttributes.setActivatorAttributes(focusableActivator, {
      id,
      active,
      ariaHaspopup,
      activatorDisabled
    });
  }, [id, active, ariaHaspopup]);
  const handleClose = source => {
    onClose(source);
    if (activatorContainer.current == null || preventFocusOnClose) {
      return;
    }
    if (source === PopoverOverlay.PopoverCloseSource.FocusOut && activatorNode) {
      const focusableActivator = focus.findFirstFocusableNodeIncludingDisabled(activatorNode) || focus.findFirstFocusableNodeIncludingDisabled(activatorContainer.current) || activatorContainer.current;
      if (!focus.focusNextFocusableNode(focusableActivator, isInPortal)) {
        focusableActivator.focus();
      }
    } else if (source === PopoverOverlay.PopoverCloseSource.EscapeKeypress && activatorNode) {
      const focusableActivator = focus.findFirstFocusableNodeIncludingDisabled(activatorNode) || focus.findFirstFocusableNodeIncludingDisabled(activatorContainer.current) || activatorContainer.current;
      if (focusableActivator) {
        focusableActivator.focus();
      } else {
        focus.focusNextFocusableNode(focusableActivator, isInPortal);
      }
    }
  };
  React.useEffect(() => {
    if (!activatorNode && activatorContainer.current) {
      setActivatorNode(activatorContainer.current.firstElementChild);
    } else if (activatorNode && activatorContainer.current && !activatorContainer.current.contains(activatorNode)) {
      setActivatorNode(activatorContainer.current.firstElementChild);
    }
    setAccessibilityAttributes();
  }, [activatorNode, setAccessibilityAttributes]);
  React.useEffect(() => {
    if (activatorNode && activatorContainer.current) {
      setActivatorNode(activatorContainer.current.firstElementChild);
    }
    setAccessibilityAttributes();
  }, [activatorNode, setAccessibilityAttributes]);
  const portal = activatorNode ? /*#__PURE__*/React.createElement(Portal.Portal, {
    idPrefix: "popover"
  }, /*#__PURE__*/React.createElement(PopoverOverlay.PopoverOverlay, Object.assign({
    ref: overlayRef,
    id: id,
    activator: activatorNode,
    preferInputActivator: preferInputActivator,
    onClose: handleClose,
    active: active,
    fixed: fixed,
    zIndexOverride: zIndexOverride
  }, rest), children)) : null;
  return /*#__PURE__*/React.createElement(WrapperComponent, {
    ref: activatorContainer
  }, React.Children.only(activator), portal);
});
function isInPortal(element) {
  let parentElement = element.parentElement;
  while (parentElement) {
    if (parentElement.matches(shared.portal.selector)) return false;
    parentElement = parentElement.parentElement;
  }
  return true;
}
const Popover = Object.assign(PopoverComponent, {
  Pane: Pane.Pane,
  Section: Section.Section
});

Object.defineProperty(exports, 'PopoverCloseSource', {
  enumerable: true,
  get: function () { return PopoverOverlay.PopoverCloseSource; }
});
exports.Popover = Popover;
