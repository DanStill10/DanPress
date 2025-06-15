// @ts-check

import BudSass from '@roots/bud-sass';
import BudReact from '@roots/bud-react';
import BudSWC from '@roots/bud-swc';

export default async (app) => {
  app
    /** Set paths to match your theme's structure */
    .setPath({
      '@src': 'resources',
      '@dist': 'public',
    })
    /** Define entrypoints using the correct paths */
    .entry({
      app: ['@src/scripts/app.js', '@src/styles/app.scss'],
    })

    .use([BudSass, BudReact, BudSWC])
    .watch(['**/*.php']);

  // if (app.isDevelopment) {
  //   app.dev
  //     .setProxyUrl('http://dan-still-wordpress-site.lndo.site')
  //     .setUrl('http://localhost:3000');
  // }

  return app;
};