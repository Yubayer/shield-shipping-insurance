'use strict';

var React = require('react');
var polarisTokens = require('@shopify/polaris-tokens');

function getTheme(themeName) {
  return polarisTokens.themes[themeName];
}
const ThemeContext = /*#__PURE__*/React.createContext(null);
function useTheme() {
  const theme = React.useContext(ThemeContext);
  if (!theme) {
    throw new Error('No theme was provided. Your application must be wrapped in an <AppProvider> component. See https://polaris.shopify.com/components/app-provider for implementation instructions.');
  }
  return theme;
}
function UseTheme(props) {
  const theme = useTheme();
  return props.children(theme);
}

exports.ThemeContext = ThemeContext;
exports.UseTheme = UseTheme;
exports.getTheme = getTheme;
exports.useTheme = useTheme;
