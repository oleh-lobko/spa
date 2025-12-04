# Changelog
All notable changes to this project will be documented in this file.

## Release notes - 2024-07-01

### Added
- New php codesniffer fixer - `php-cs-fixer`
- `sass` package for styles compilation
- `sharp` package for image optimization

### Changed
- Used native autoloader instead of composer's one
  - No need to upload `vendor` folder to the server anymore
- Unified text domain usage within the theme - `fwp` is now used everywhere
- Replaced `fwp_` functions prefix with `wl_`
- Renamed `dist/vendor` folder into `dist/ext` to avoid conflicts with composer's vendors directory
- Node packages update:
  - Updated minimal supported node version to `18.17.0`
  - Updated required yarn version to `4.x`
  - Replaced deprecated `@squoosh/lib` with `sharp`
  - Used `sass` instead of `node-sass`

### Removed
- Composer autoloader
- `node-sass` and `@squoosh/lib` packages

-----------------------------------------------------------------

## Release notes - 2020-02-11

### Added
- Added webpack module bundler and created **build** directory which contains all its configuration files;
- New **config.json** file;
- New **autoload** folder for styles and scripts;
- Linters:
  - [stylelint](https://stylelint.io/) - for styles;
  - [eslint](https://eslint.org/) - for javascript;
- Prettier file watcher for PhpStorm;
- **.idea** folder to initially configure the PhpStorm according to linters rules and used technologies;

### Changed
- Used [**yarn**](https://classic.yarnpkg.com/en/) instead of **npm** for installing packages and running package scripts. For more info see [yarn cli docs](https://classic.yarnpkg.com/en/docs/cli/);
- New source files structure. **images**, **fonts**, **scss** (now **styles**) and **js** (now **scripts**) folders moved into the **assets** directory;
- **functions.php** splitted into several files:
  - **inc/google-maps.php** - google maps integration;
  - **inc/theme-customizations.php** - theme customizations;
  - **inc/gravity-form-customizations.php** - [Gravity Forms](https://www.gravityforms.com/) customizations;
  - **inc/tiny-mce-customizations.php** - [TinyMCE](https://www.tiny.cloud/) customizations;
- **custom.scss** renamed into **main.scss**;
- `.css-clip` replaced with `.show-for-sr`;
- **global.js** renamed into **main.js** and it has es5 support;
- All the plugins installed via npm and listed in **package.json**;
- Package scripts:
  - `bs` and `watch` combined into `start`;
  - `build:fn`, `build:css` and `prefix` combined into `build`;
  - new `build:production` script - for making optimized and minified build;
  - new `build:production:analyze` script - for checking production bundle size;
  - new `lint:scripts`, `lint:styles` and combined `lint` scripts - for detecting script's and style's linting errors;
  - new `fix:scripts` and `fix:styles` scripts - for fixing most common linting errors;

### Removed
- **bs-config.js** file;
