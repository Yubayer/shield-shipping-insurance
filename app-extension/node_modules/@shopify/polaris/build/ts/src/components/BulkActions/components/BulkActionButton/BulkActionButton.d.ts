import React from 'react';
import type { DisableableAction } from '../../../../types';
export type BulkActionButtonProps = {
    disclosure?: boolean;
    indicator?: boolean;
    handleMeasurement?(width: number): void;
    showContentInButton?: boolean;
} & DisableableAction;
export declare function BulkActionButton({ handleMeasurement, url, external, onAction, content, disclosure, accessibilityLabel, disabled, indicator, showContentInButton, }: BulkActionButtonProps): React.JSX.Element;
//# sourceMappingURL=BulkActionButton.d.ts.map