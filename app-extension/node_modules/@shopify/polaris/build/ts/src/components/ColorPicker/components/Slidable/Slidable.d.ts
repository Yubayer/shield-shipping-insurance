import React, { PureComponent } from 'react';
interface Position {
    x: number;
    y: number;
}
interface State {
    dragging: boolean;
}
export interface SlidableProps {
    draggerX?: number;
    draggerY?: number;
    onChange(position: Position): void;
    onDraggerHeight?(height: number): void;
}
export declare class Slidable extends PureComponent<SlidableProps, State> {
    state: State;
    private node;
    private draggerNode;
    componentDidMount(): void;
    render(): React.JSX.Element;
    private setDraggerNode;
    private setNode;
    private startDrag;
    private handleDragEnd;
    private handleMove;
    private handleDraggerMove;
}
export {};
//# sourceMappingURL=Slidable.d.ts.map