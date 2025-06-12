# Dan Press Components - WordPress Theme

This is a custom WordPress theme designed for modern front-end development. It uses Lando for a consistent local development environment and Bud.js for compiling assets like Sass and React.

## Prerequisites

Before you begin, ensure you have the following software installed on your local machine:

* **Lando:** For running the local WordPress environment. [Installation Guide](https://lando.dev/download/)
* **Node.js:** Version 20.x or higher. [Download](https://nodejs.org/)
* **Yarn:** Version 1.22.x or higher. [Installation Guide](https://classic.yarnpkg.com/en/docs/install)

---

## Local Development Setup

Follow these steps to get the project running on your local machine.

1.  **Clone the Repository:**
    ```bash
    git clone [your-repository-url]
    cd [your-project-directory]
    ```

2.  **Start Lando:**
    From the project root, start the Lando containers. This will provision your web server and database.
    ```bash
    lando start
    ```
    Once it's running, you can access the local site at the URL provided by Lando (e.g., `http://dan-still-wordpress-site.lndo.site/`).

3.  **Install Front-End Dependencies:**
    Navigate to the theme directory and install the necessary Node.js packages.
    ```bash
    cd wp-content/themes/dan-press-components
    yarn install
    ```

4.  **Run the Development Build:**
    To watch for file changes and automatically recompile assets, run the following command from the theme directory.
    ```bash
    npx bud build --watch
    ```
    *Note: You must manually refresh your browser to see changes. See the "Current Status" section for why we use this command instead of `npx bud dev`.*

---

## Build Commands

All commands should be run from within the theme directory (`wp-content/themes/dan-press-components`).

* **Development (Watch Mode):** Compiles assets and watches for changes. **This is the recommended command for development.**
    ```bash
    npx bud build --watch
    ```

* **Production Build:** Compiles and minifies assets for production.
    ```bash
    npx bud build
    ```

---

## Troubleshooting Journey & Project History

This project encountered a series of complex and interconnected issues during its initial setup. This log is provided to help future developers understand the state of the project and avoid repeating these debugging steps.

### Phase 1: Environment & Command Execution
* **Initial Error:** `lando yarn dev` failed with `executable file not found in $PATH`.
* **Resolution:** Confirmed that Node.js build tools must be run on the **host machine (the Mac)**, not inside the Lando container. The local Bud.js server runs on the host and proxies to the Lando site.

### Phase 2: The Stubborn `setProxyUrl` Error
* **Error:** Running `npx bud dev` repeatedly failed with `TypeError: Cannot read properties of undefined (reading 'setProxyUrl')`.
* **Diagnosis:** This was the most difficult issue, caused by a **severe version mismatch** between `@roots/bud` and its various extensions (e.g., `@roots/bud-sass`, `@roots/sage`). The project had a mix of `v6.7.3` and `v6.24.0` packages, which are incompatible.
* **Resolution:**
    1.  Manually edited `package.json` to align **every single `@roots/*` package** to the same version (`^6.24.0`).
    2.  Performed a complete "scorched earth" reinstall by deleting `node_modules`, `yarn.lock`, `.budfiles`, `.cache`, and clearing the Yarn cache (`yarn cache clean`) before running `yarn install`. This ensured a perfectly consistent dependency tree.

### Phase 3: Build & Configuration Errors
After resolving the versioning crisis, a series of smaller configuration errors appeared.

1.  **`Module not found` Error:**
    * **Problem:** The build failed because it couldn't find `src/index.js`.
    * **Resolution:** The theme's source files are in `/resources`, not `/src`. The `bud.config.js` was updated with `app.setPath('@src', 'resources')` and the correct entrypoints.

2.  **`package.json` Integrity Errors:**
    * **Problem:** `yarn install` failed due to `Unexpected end of JSON input` and later `Not Found` for a package.
    * **Resolution:** The `package.json` file was manually rebuilt in a code editor to fix syntax errors. A typo in a package name (`@roots/bud-preset-recommended`) was corrected to `@roots/bud-preset-recommend`.

3.  **ES Module `import` Error:**
    * **Problem:** After fixing dependencies, the build failed with `Cannot use import statement outside a module`.
    * **Resolution:** This was resolved by adding `"type": "module"` to `package.json`. However, this change unexpectedly caused the original `setProxyUrl` error to return, pointing to a likely bug in the tool itself. The final fix was to **remove** `"type": "module"` and **rename `bud.config.js` to `bud.config.mjs`**, which correctly isolates the module context to just the config file.

### Phase 4: Final Bug Confirmation
* **Problem:** Even with a perfect configuration, `npx bud dev` continued to fail with the `setProxyUrl` error, while `npx bud build` worked perfectly.
* **Conclusion:** This is a confirmed bug within the Bud.js (`v6.24.0`) framework, specific to how the `dev` service initializes in this environment.

---

## Current Status & Development Workflow

* **Primary Issue:** As of this writing, `npx bud dev` is **unusable** due to a persistent internal bug in Bud.js. A bug report has been filed with the Roots team.
* **Recommended Workflow:** Use the watch mode for development. It provides automatic recompilation on file changes.
    ```bash
    npx bud build --watch
    ```
* **Limitation:** This workflow **does not include Hot Module Replacement (HMR)**. You will need to **manually refresh your browser** to see updated styles and scripts. This is a temporary measure until the bug in `bud dev` is resolved by the framework authors.


