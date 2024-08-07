import { gql } from 'graphql-request';
export const FindProductVariantQuery = gql `
  query {
    products(first: 1, query: "published_status:published") {
      edges {
        node {
          id
          variants(first: 1) {
            edges {
              node {
                id
              }
            }
          }
        }
      }
    }
  }
`;
//# sourceMappingURL=get_variant_id.js.map