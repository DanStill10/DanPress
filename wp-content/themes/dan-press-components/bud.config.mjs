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
      editor: ['@src/styles/editor.scss'],
    })

    .use([BudSass, BudReact, BudSWC])
    .watch(['**/*.php']);

  // Emit all font files (including those self-hosted via @fontsource/inter
  // from node_modules) into public/fonts/ so the compiled app.css can
  // reference them locally instead of pointing into node_modules.
  app.build.setRule('font', (rule) =>
    rule
      .setType('asset/resource')
      .setTest(/\.woff2$/)
      .setGenerator({ filename: 'fonts/[name][ext]' }),
  );

  // if (app.isDevelopment) {
  //   app.dev
  //     .setProxyUrl('http://dan-still-wordpress-site.lndo.site')
  //     .setUrl('http://localhost:3000');
  // }

  return app;
};