// @ts-check

import BudSass from '@roots/bud-sass';
import BudReact from '@roots/bud-react';
import BudSWC from '@roots/bud-swc';

/**
 * Build configuration
 * @param {import('@roots/bud').Bud} app
 */
export default async (app) => {
  app
    /**
     * 1. Set source and distribution paths.
     * This is the key fix for the "Can't resolve 'index.js'" error.
     */
    .setPath({
      '@src': 'src',
      '@dist': 'public',
    })

    /**
     * 2. Define entrypoints using the correct @src path.
     */
    .entry({
      app: ['@src/index.js', '@src/index.scss'],
    })

    /**
     * 3. Load the extensions we need.
     */
    .use([BudSass, BudReact, BudSWC])

    /**
     * 4. Watch for changes in PHP files to trigger browser reloads.
     */
    .watch(['**/*.php']);

  /**
   * 5. Configure the dev server. This is safe to add back now.
   */
  if (app.isDevelopment) {
    app.dev
      .setProxyUrl('http://dan-still-wordpress-site.lndo.site')
      .setUrl('http://localhost:3000');
  }

  return app;
};