import { useRef, useCallback, useEffect } from 'react';
import { useIsomorphicLayoutEffect } from '../../utilities/use-isomorphic-layout-effect.js';

function KeypressListener({
  keyCode,
  handler,
  keyEvent = 'keyup',
  options,
  useCapture
}) {
  const tracked = useRef({
    handler,
    keyCode
  });
  useIsomorphicLayoutEffect(() => {
    tracked.current = {
      handler,
      keyCode
    };
  }, [handler, keyCode]);
  const handleKeyEvent = useCallback(event => {
    const {
      handler,
      keyCode
    } = tracked.current;
    if (event.keyCode === keyCode) {
      handler(event);
    }
  }, []);
  useEffect(() => {
    document.addEventListener(keyEvent, handleKeyEvent, useCapture || options);
    return () => {
      document.removeEventListener(keyEvent, handleKeyEvent, useCapture || options);
    };
  }, [keyEvent, handleKeyEvent, useCapture, options]);
  return null;
}

export { KeypressListener };
