/// <reference types="react" />
export declare function useIsBulkActionsSticky(selectMode: boolean): {
    bulkActionsIntersectionRef: import("react").RefObject<HTMLDivElement>;
    tableMeasurerRef: import("react").RefObject<HTMLDivElement>;
    isBulkActionsSticky: boolean;
    bulkActionsAbsoluteOffset: number;
    bulkActionsMaxWidth: number;
    bulkActionsOffsetLeft: number;
    computeTableDimensions: () => {
        maxWidth: number;
        offsetHeight: number;
        offsetLeft: number;
    } | undefined;
};
//# sourceMappingURL=use-is-bulk-actions-sticky.d.ts.map