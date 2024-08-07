import React from 'react';
import type { Action } from '../../types';
export interface SelectAllActionsProps {
    /** Visually hidden text for screen readers */
    accessibilityLabel?: string;
    /** Label for the bulk actions */
    label?: string;
    /** State of the bulk actions checkbox */
    selected?: boolean | 'indeterminate';
    /** List is in a selectable state */
    selectMode?: boolean;
    /** Text to select all across pages */
    paginatedSelectAllText?: string;
    /** Action for selecting all across pages */
    paginatedSelectAllAction?: Action;
    /** Disables bulk actions */
    disabled?: boolean;
    /** Callback when the select all checkbox is clicked */
    onToggleAll?(): void;
}
export declare const SelectAllActions: React.ForwardRefExoticComponent<SelectAllActionsProps & React.RefAttributes<unknown>>;
//# sourceMappingURL=SelectAllActions.d.ts.map