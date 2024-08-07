import React from 'react';
import styles from '../../List.scss.js';

function Item({
  children
}) {
  return /*#__PURE__*/React.createElement("li", {
    className: styles.Item
  }, children);
}

export { Item };
