import React from 'react';
import type { ChoiceListProps } from '../../../ChoiceList';
import type { SortButtonChoice } from '../../types';
export declare enum SortButtonDirection {
    Asc = "asc",
    Desc = "desc"
}
export interface SortButtonProps {
    choices: SortButtonChoice[];
    selected: ChoiceListProps['selected'];
    onChange: (selected: string[]) => void;
    disabled?: boolean;
    onChangeKey?: (key: string) => void;
    onChangeDirection?: (direction: string) => void;
}
export declare function SortButton({ choices, selected, onChange, disabled, onChangeKey, onChangeDirection, }: SortButtonProps): React.JSX.Element;
//# sourceMappingURL=SortButton.d.ts.map