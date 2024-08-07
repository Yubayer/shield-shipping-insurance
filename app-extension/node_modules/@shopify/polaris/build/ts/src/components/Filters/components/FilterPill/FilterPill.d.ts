import React from 'react';
import type { FilterInterface } from '../../../../types';
export interface FilterPillProps extends FilterInterface {
    /** A unique identifier for the filter */
    filterKey: string;
    /** Whether the filter is selected or not */
    selected?: boolean;
    /** Whether the Popover will be initially open or not */
    initialActive: boolean;
    /** Callback invoked when the filter is removed */
    onRemove?(key: string): void;
    /** Callback invoked when the filter is clicked */
    onClick?(key: string): void;
    /** Whether filtering is disabled */
    disabled?: boolean;
    /** Whether the filter should close when clicking inside another Popover. */
    closeOnChildOverlayClick?: boolean;
}
export declare function FilterPill({ filterKey, label, filter, disabled, hideClearButton, selected, initialActive, closeOnChildOverlayClick, onRemove, onClick, }: FilterPillProps): React.JSX.Element | null;
//# sourceMappingURL=FilterPill.d.ts.map