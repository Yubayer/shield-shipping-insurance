export interface AllOrganizationsQuerySchemaOrganization {
    id: string;
    businessName: string;
    website?: string;
}
export interface AllOrganizationsQuerySchema {
    organizations: {
        nodes: AllOrganizationsQuerySchemaOrganization[];
    };
}
export declare const AllOrganizationsQuery: string;
