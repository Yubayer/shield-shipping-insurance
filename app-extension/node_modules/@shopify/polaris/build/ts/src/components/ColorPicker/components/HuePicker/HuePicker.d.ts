import React, { PureComponent } from 'react';
interface State {
    sliderHeight: number;
    draggerHeight: number;
}
export interface HuePickerProps {
    hue: number;
    onChange(hue: number): void;
}
export declare class HuePicker extends PureComponent<HuePickerProps, State> {
    state: State;
    render(): React.JSX.Element;
    private setSliderHeight;
    private setDraggerHeight;
    private handleChange;
}
export {};
//# sourceMappingURL=HuePicker.d.ts.map