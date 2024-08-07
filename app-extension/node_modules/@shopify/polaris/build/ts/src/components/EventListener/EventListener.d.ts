import { PureComponent } from 'react';
interface BaseEventProps {
    event: string;
    capture?: boolean;
    handler(event: Event): void;
}
export interface EventListenerProps extends BaseEventProps {
    passive?: boolean;
}
/** @deprecated Use the useEventListener hook instead. */
export declare class EventListener extends PureComponent<EventListenerProps, never> {
    componentDidMount(): void;
    componentDidUpdate({ passive, ...detachProps }: EventListenerProps): void;
    componentWillUnmount(): void;
    render(): null;
    private attachListener;
    private detachListener;
}
export {};
//# sourceMappingURL=EventListener.d.ts.map