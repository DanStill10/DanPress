Dan Press Components Theme

This is a modern, block-based WordPress theme built with Bud.js and the native WordPress block development toolkit (@wordpress/scripts).
Development Workflow

This theme uses a dual-build process to handle theme-wide assets and individual block assets separately. You will need two terminal windows open during development.
Prerequisites

Ensure you have run composer install and yarn install in this directory.
Terminal 1: Block Development

This process, managed by @wordpress/scripts, compiles the JavaScript and CSS for all custom blocks located in the /blocks directory.

    Navigate to the theme directory:

    cd /path/to/wp-content/themes/dan-press-components

    Start the watch process:

    yarn start

This will watch for changes in the /blocks and /src directories and recompile them on the fly. The output is sent to the /build directory.
Terminal 2: Theme Asset Development

This process, managed by Bud.js, compiles theme-wide assets like the main app.scss stylesheet.

    Navigate to the theme directory:

    cd /path/to/wp-content/themes/dan-press-components

    Start the watch process:

    npx bud build --watch

This will watch for changes in the /resources directory and recompile them into the /public directory.

Known Issue: The npx bud dev command is currently unusable due to an internal bug in Bud.js v6.24.0. The build --watch command is the official workaround and provides automatic recompilation, but requires a manual browser refresh to see changes.
Creating New Blocks

This theme is designed for rapid block development. The functions.php file automatically registers any block placed in the /blocks directory.

To create a new block:

    Create a new folder inside /blocks (e.g., /blocks/skills-grid).

    Inside the new folder, create your block.json, edit.js, render.php, etc.

    Add an import for your new block's folder in /src/index.js:

    // src/index.js
    import '../blocks/project-showcase';
    import '../blocks/skills-grid'; // Add your new block here

The yarn start process will automatically pick it up and compile it.
Production Builds

To create production-ready, minified assets, run the following commands:

# For theme assets (styles, etc.)
npx bud build

# For block assets
yarn build

A CI/CD pipeline would run both of these commands before deploying the theme.