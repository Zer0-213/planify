import {remixRoutesOptionAdapter} from "@react-router/remix-routes-option-adapter";
import {flatRoutes} from "remix-flat-routes";

const routes = remixRoutesOptionAdapter((defineRoutes) => {
    return flatRoutes("routes", defineRoutes, {
        ignoredRouteFiles: ['**/.*'], // Ignore dot files (like .DS_Store)
        //appDir: 'app',
        //routeDir: 'routes',
        //basePath: '/',
        //paramPrefixChar: '$',
        //nestedDirectoryChar: '+',
        //routeRegex: /((\${nestedDirectoryChar}[\/\\][^\/\\:?*]+)|[\/\\]((index|route|layout|page)|(_[^\/\\:?*]+)|([^\/\\:?*]+\.route)))\.(ts|tsx|js|jsx|md|mdx)$$/,
    });
});
export default routes;