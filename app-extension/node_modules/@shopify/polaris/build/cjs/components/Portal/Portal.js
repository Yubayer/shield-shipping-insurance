'use strict';

var React = require('react');
var reactDom = require('react-dom');
var hooks = require('../../utilities/portals/hooks.js');

function Portal({
  children,
  idPrefix = '',
  onPortalCreated = noop
}) {
  const {
    container
  } = hooks.usePortalsManager();
  const uniqueId = React.useId();
  const portalId = idPrefix !== '' ? `${idPrefix}-${uniqueId}` : uniqueId;
  React.useEffect(() => {
    onPortalCreated();
  }, [onPortalCreated]);
  return container ? /*#__PURE__*/reactDom.createPortal( /*#__PURE__*/React.createElement("div", {
    "data-portal-id": portalId
  }, children), container) : null;
}
function noop() {}

exports.Portal = Portal;
