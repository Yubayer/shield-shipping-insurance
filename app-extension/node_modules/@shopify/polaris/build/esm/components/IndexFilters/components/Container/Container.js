import React from 'react';
import styles from './Container.scss.js';

const Container = ({
  children
}) => {
  return /*#__PURE__*/React.createElement("div", {
    className: styles.Container
  }, children);
};

export { Container };
