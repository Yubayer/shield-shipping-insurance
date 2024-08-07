import { useState } from 'react';
import { themeDefault, getMediaConditions } from '@shopify/polaris-tokens';
import { isServer } from './target.js';
import { useIsomorphicLayoutEffect } from './use-isomorphic-layout-effect.js';

const Breakpoints = {
  // TODO: Update to smDown
  navigationBarCollapsed: '767.95px',
  // TODO: Update to lgDown
  stackedContent: '1039.95px'
};
const noWindowMatches = {
  media: '',
  addListener: noop,
  removeListener: noop,
  matches: false,
  onchange: noop,
  addEventListener: noop,
  removeEventListener: noop,
  dispatchEvent: _ => true
};
function noop() {}
function navigationBarCollapsed() {
  return typeof window === 'undefined' ? noWindowMatches : window.matchMedia(`(max-width: ${Breakpoints.navigationBarCollapsed})`);
}
function stackedContent() {
  return typeof window === 'undefined' ? noWindowMatches : window.matchMedia(`(max-width: ${Breakpoints.stackedContent})`);
}

/**
 * Directional alias for each Polaris `breakpoints` token.
 *
 * @example 'smUp' | 'smDown' | 'smOnly' | 'mdUp' | etc.
 */

/**
 * Match results for each directional Polaris `breakpoints` alias.
 */

const breakpointsQueryEntries = getBreakpointsQueryEntries(themeDefault.breakpoints);
function getMatches(defaults,
/**
 * Used to force defaults on initial client side render so they match SSR
 * values and hence avoid a Hydration error.
 */
forceDefaults) {
  if (!isServer && !forceDefaults) {
    return Object.fromEntries(breakpointsQueryEntries.map(([directionAlias, query]) => [directionAlias, window.matchMedia(query).matches]));
  }
  if (typeof defaults === 'object' && defaults !== null) {
    return Object.fromEntries(breakpointsQueryEntries.map(([directionAlias]) => [directionAlias, defaults[directionAlias] ?? false]));
  }
  return Object.fromEntries(breakpointsQueryEntries.map(([directionAlias]) => [directionAlias, defaults ?? false]));
}
/**
 * Retrieves media query matches for each directional Polaris `breakpoints` alias.
 *
 * @example
 * const {smUp} = useBreakpoints();
 * return smUp && 'Hello world';
 *
 * @example
 * const {mdUp} = useBreakpoints({defaults: {mdUp: true}});
 * mdUp //=> `true` during SSR
 *
 * @example
 * const breakpoints = useBreakpoints({defaults: true});
 * breakpoints //=> All values will be `true` during SSR
 */
function useBreakpoints(options) {
  // On SSR, and initial CSR, we force usage of the defaults to avoid a
  // hydration mismatch error.
  // Later, in the effect, we will call this again on the client side without
  // any defaults to trigger a more accurate client side evaluation.
  const [breakpoints, setBreakpoints] = useState(getMatches(options?.defaults, true));
  useIsomorphicLayoutEffect(() => {
    const mediaQueryLists = breakpointsQueryEntries.map(([_, query]) => window.matchMedia(query));
    const handler = () => setBreakpoints(getMatches());
    mediaQueryLists.forEach(mql => {
      if (mql.addListener) {
        mql.addListener(handler);
      } else {
        mql.addEventListener('change', handler);
      }
    });

    // Trigger the breakpoint recalculation at least once client-side to ensure
    // we don't have stale default values from SSR.
    handler();
    return () => {
      mediaQueryLists.forEach(mql => {
        if (mql.removeListener) {
          mql.removeListener(handler);
        } else {
          mql.removeEventListener('change', handler);
        }
      });
    };
  }, []);
  return breakpoints;
}

/**
 * Converts `breakpoints` tokens into directional media query entries.
 *
 * @example
 * const breakpointsQueryEntries = getBreakpointsQueryEntries(breakpoints);
 * breakpointsQueryEntries === [
 *   ['xsUp', '(min-width: ...)'],
 *   ['xsDown', '(max-width: ...)'],
 *   ['xsOnly', '(min-width: ...) and (max-width: ...)'],
 *   ['smUp', '(min-width: ...) and (max-width: ...)'],
 *   ['mdUp', '(min-width: ...) and (max-width: ...)'],
 *   // etc.
 * ]
 */
function getBreakpointsQueryEntries(breakpoints) {
  const mediaConditionEntries = Object.entries(getMediaConditions(breakpoints));
  return mediaConditionEntries.map(([breakpointsToken, mediaConditions]) => Object.entries(mediaConditions).map(([direction, mediaCondition]) => {
    const breakpointsAlias = breakpointsToken.split('-')[1];

    // e.g. smUp, smDown, smOnly, etc.
    const directionAlias = `${breakpointsAlias}${capitalize(direction)}`;
    return [directionAlias, mediaCondition];
  })).flat();
}
function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

export { getBreakpointsQueryEntries, navigationBarCollapsed, stackedContent, useBreakpoints };
