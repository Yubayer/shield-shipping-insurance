import React, { forwardRef, useRef, useImperativeHandle } from 'react';
import { classNames } from '../../utilities/css.js';
import styles from './CheckableButton.scss.js';
import { Checkbox } from '../Checkbox/Checkbox.js';

const CheckableButton = /*#__PURE__*/forwardRef(function CheckableButton({
  accessibilityLabel,
  label = '',
  onToggleAll,
  selected,
  disabled,
  ariaLive
}, ref) {
  const checkBoxRef = useRef(null);
  function focus() {
    checkBoxRef?.current?.focus();
  }
  useImperativeHandle(ref, () => {
    return {
      focus
    };
  });
  const className = classNames(styles.CheckableButton);
  return /*#__PURE__*/React.createElement("div", {
    className: className,
    onClick: onToggleAll
  }, /*#__PURE__*/React.createElement("div", {
    className: styles.Checkbox
  }, /*#__PURE__*/React.createElement(Checkbox, {
    label: accessibilityLabel,
    labelHidden: true,
    checked: selected,
    disabled: disabled,
    onChange: onToggleAll,
    ref: checkBoxRef
  })), /*#__PURE__*/React.createElement("span", {
    className: styles.Label,
    "aria-live": ariaLive
  }, label));
});

export { CheckableButton };
