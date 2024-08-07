import React from 'react';
import styles from './FooterHelp.scss.js';

function FooterHelp({
  children
}) {
  return /*#__PURE__*/React.createElement("div", {
    className: styles.FooterHelp
  }, /*#__PURE__*/React.createElement("div", {
    className: styles.Text
  }, children));
}

export { FooterHelp };
