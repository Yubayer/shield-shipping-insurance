import { GraphQLVariables } from './graphql.js';
/**
 * Executes a GraphQL query against the Business Platform API.
 *
 * @param query - GraphQL query to execute.
 * @param token - Business Platform token.
 * @param variables - GraphQL variables to pass to the query.
 * @returns The response of the query of generic type <T>.
 */
export declare function businessPlatformRequest<T>(query: string, token: string, variables?: GraphQLVariables): Promise<T>;
