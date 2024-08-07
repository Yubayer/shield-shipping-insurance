'use strict';

var React = require('react');

/** @deprecated Use the useEventListener hook instead. */
class EventListener extends React.PureComponent {
  componentDidMount() {
    this.attachListener();
  }
  componentDidUpdate({
    passive,
    ...detachProps
  }) {
    this.detachListener(detachProps);
    this.attachListener();
  }
  componentWillUnmount() {
    this.detachListener();
  }
  render() {
    return null;
  }
  attachListener() {
    const {
      event,
      handler,
      capture,
      passive
    } = this.props;
    window.addEventListener(event, handler, {
      capture,
      passive
    });
  }
  detachListener(prevProps) {
    const {
      event,
      handler,
      capture
    } = prevProps || this.props;
    window.removeEventListener(event, handler, capture);
  }
}

exports.EventListener = EventListener;
