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

  // Emit all font files (including those self-hosted via @fontsource/inter
  // from node_modules) into public/fonts/ so the compiled app.css can
  // reference them locally instead of pointing into node_modules.
  app.build.setRule('font', (rule) =>
    rule
      .setType('asset/resource')
      .setTest(/\.woff2$/)
      .setGenerator({ filename: 'fonts/[name][ext]' }),
  );

  // Content-hash emitted JS/CSS filenames in production (e.g. js/app.abc12345.js,
  // css/app.abc12345.css). Combined with the immutable browser cache in
  // .htaccess this ensures clients never reuse a stale asset after a deploy.
  if (app.isProduction) {
    app.hash('[contenthash:8]');
  }

  // if (app.isDevelopment) {
  //   app.dev
  //     .setProxyUrl('http://dan-still-wordpress-site.lndo.site')
  //     .setUrl('http://localhost:3000');
  // }

  return app;
};