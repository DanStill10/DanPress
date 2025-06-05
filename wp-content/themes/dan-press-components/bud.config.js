// @ts-check

// Import the Bud extensions you are using
import BudSass from '@roots/bud-sass';
import BudReact from '@roots/bud-react';
import BudSWC from '@roots/bud-swc';

/**
 * Build configuration
 *
 * @see {@link https://bud.js.org/guides/configure}
 * @param {import('@roots/bud').Bud} app
 */
export default async (app) => {
  app
    .setPath('@src', 'src')
    .setPath('@dist', 'public')

    .entry({
      app: ['@src/index.js', '@src/index.scss'],
    })

    .use(BudSass)
    .use(BudReact)
    .use(BudSWC)

    .dev
      .setProxyUrl('http://dan-still-wordpress-site.lndo.site/')
      .setUrl('http://localhost:3000')
    .parent

    .watch(['**/*.php']);
};