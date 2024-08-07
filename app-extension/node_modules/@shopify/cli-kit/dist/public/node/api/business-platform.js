import { graphqlRequest } from './graphql.js';
import { handleDeprecations } from './partners.js';
import { businessPlatformFqdn } from '../context/fqdn.js';
/**
 * Executes a GraphQL query against the Business Platform API.
 *
 * @param query - GraphQL query to execute.
 * @param token - Business Platform token.
 * @param variables - GraphQL variables to pass to the query.
 * @returns The response of the query of generic type <T>.
 */
export async function businessPlatformRequest(query, token, variables) {
    const api = 'BusinessPlatform';
    const fqdn = await businessPlatformFqdn();
    const url = `https://${fqdn}/destinations/api/2020-07/graphql`;
    return graphqlRequest({
        query,
        api,
        url,
        token,
        variables,
        responseOptions: { onResponse: handleDeprecations },
    });
}
//# sourceMappingURL=business-platform.js.map