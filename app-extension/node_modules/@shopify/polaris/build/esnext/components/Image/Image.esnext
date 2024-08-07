import React, { useCallback } from 'react';

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
  const handleLoad = useCallback(() => {
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

export { Image };
