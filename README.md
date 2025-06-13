# DanPress Portfolio Site

This repository contains the complete WordPress project for the Dan Still portfolio website. It is managed with Lando for local development and structured for modern theme and block development.

## Project Architecture

* **Local Environment:** Managed by [Lando](https://lando.dev/). The configuration is in `.lando.yml`.
* **CMS:** WordPress. Core files are managed as part of the Lando setup and are ignored by Git.
* **Theme:** A custom theme located at `wp-content/themes/dan-press-components`. This theme is where all front-end development takes place.
* **Content Blocks:** All custom content components (e.g., "Project Showcase") are built as native WordPress blocks within the theme.

---

## Local Environment Setup

### Prerequisites

* [Lando](https://lando.dev/download/)
* [Node.js](https://nodejs.org/) (v20.x or higher)
* [Yarn](https://classic.yarnpkg.com/en/docs/install) (v1.22.x or higher)
* [Composer](https://getcomposer.org/)

### Quick Start

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/DanStill10/DanPress.git](https://github.com/DanStill10/DanPress.git)
    cd DanPress
    ```

2.  **Start Lando:**
    This command will build the containers, install WordPress, and provide you with local access URLs.
    ```bash
    lando start
    ```

3.  **Install Dependencies:**
    The project uses both PHP and Node.js dependencies. Install them from the theme directory.
    ```bash
    cd wp-content/themes/dan-press-components
    composer install
    yarn install
    ```

4.  **Start Development:**
    Front-end development is handled entirely within the theme. See the theme's README for detailed instructions on compiling assets and building blocks.
    ```bash
    # Navigate to the theme directory to begin development
    cd wp-content/themes/dan-press-components
    
    # See this theme's README.md for the next steps.
    ```

---

## Deployment & CI/CD

This project is structured to be deployed via a CI/CD pipeline.

* **`.gitignore`:** The repository is configured to ignore WordPress core files, build artifacts (`/public`, `/build`), and dependency directories (`/vendor`, `/node_modules`).
* **Build Steps:** A future pipeline will be responsible for:
    1.  Running `composer install --no-dev` to get production PHP dependencies.
    2.  Running `yarn install` to get Node.js dependencies.
    3.  Running `npx bud build` to compile production theme assets.
    4.  Running `yarn build` (from `wp-scripts`) to compile production block assets.
    5.  Deploying the theme files to the production server.

This ensures a lean repository and a consistent, automated build process.

