'use strict';

var React = require('react');

function Image({
  alt,
  sourceSet,
  source,
  crossOrigin,
  onLoad,
  className,
  ...rest
}) {
  const finalSourceSet = sourceSet ? sourceSet.map(({
    source: subSource,
    descriptor
  }) => `${subSource} ${descriptor}`).join(',') : null;
  const handleLoad = React.useCallback(() => {
    if (onLoad) onLoad();
  }, [onLoad]);
  return /*#__PURE__*/React.createElement("img", Object.assign({
    alt: alt,
    src: source,
    crossOrigin: crossOrigin,
    className: className,
    onLoad: handleLoad
  }, finalSourceSet ? {
    srcSet: finalSourceSet
  } : {}, rest));
}

exports.Image = Image;
