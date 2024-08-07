export declare const FindOrganizationQuery: string;
export interface FindOrganizationQuerySchema {
    organizations: {
        nodes: {
            id: string;
            businessName: string;
            website: string;
            apps: {
                pageInfo: {
                    hasNextPage: boolean;
                };
                nodes: {
                    id: string;
                    title: string;
                    apiKey: string;
                }[];
            };
        }[];
    };
}
