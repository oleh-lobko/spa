# Foundation + Webpack

## Required software

1. Web Browser
2. Node.js `^18.17.0` [[download link]](https://nodejs.org/dist/v18.17.0/)
3. Yarn `^4.3.1` [[installation guide]](https://classic.yarnpkg.com/en/docs/install#windows-stable)
4. PHP `8.1` [[download link]](https://www.php.net/downloads.php)
5. Composer [[download link]](https://getcomposer.org/download/)
6. PhpStorm

## Getting started

### Development
To start using our build you’ll need:

1. Download it by ftp as you always did.
2. Open the theme folder via **PhpStorm**.
3. Go to **Terminal** tool window [[screenshot]](https://www.screencast.com/t/2Qq1EUvqJJ).
4. Run `yarn install` command. Be careful and **don’t use npm** for this step. Using **npm** might cause an unexpected errors.
5. Wait for **Done in ##.##s.** Message.
6. Run `composer install` and wait till it finishes.
8. Go to **npm** tool window and run **start** script. Here is how you can enable it [[screenshot]](https://www.screencast.com/t/BxAH4sxyKGd) and how it will look like [[screenshot]](https://www.screencast.com/t/YXb7UCer2Y5). If you hate using UI solutions you can do it via terminal as well, using `yarn run start` command.
9. Wait until webpack and browsersync are finishing their initialization [[screenshot]](https://www.screencast.com/t/lB2tKdyqd), open the **Local:** url in your favorite browser and you’re ready to build the project!

### Build
To finilize your work and get everything wrapped up you should run **build** task, which will update the **dist** folder with the new files. After this you should upload the new dist content to the server.

**Important!** If you're going to upload the build to the live server, you should run **build:production** task. This one will create a bundle with the hashed file names, which will improve caching for the site.

**Note!** Use `yarn run build` or `yarn run build:production` if you are using terminal instead of UI.

## Theme structure notes

As you may already notice, we have two new directories - **build** and **dist**.
**build** is used to store all the build configurations and you won’t be editing it in any way.
**dist** folder is our final build folder. It contains the files which will be included into our wordpress theme. This folder will be replaced on every **build** task launch, so you also won’t edit it.

All editable resources are located under the **assets** folder [[screenshot]](https://www.screencast.com/t/AG0h1wX5HV48).
You might also notice some changes in the **assets** folder as well.
We’ve added an **autoload** directory for each, **styles** and **scripts** folders [[screenshot]](https://www.screencast.com/t/vpQ5VDIlGlf).
And the name is speaking for itself, all the files in these folders will be automatically imported into the main file. And this is the way the foundation-sites is imported now [[screenshot]](https://www.screencast.com/t/3N9seR3o).

## How to...

#### Enable a specific foundation module
1. Go to `assets/styles/autoload/_foundation.scss`;
2. Find the line with the module name and uncomment it;
3. Open [foundation-sites docs](https://get.foundation/sites/docs/index.html) and check if module [requires a javascript library](https://www.screencast.com/t/a8j2UOhfjLr);
4. If yes, go to `assets/scripts/autoload/foundation.js`;
5. Find the line with the module name and uncomment it;

#### Add an npm package
1. Find the package on [npm](https://www.npmjs.com/) and copy it's name;
2. Open **Terminal** tool window and run `yarn add [package_name] --dev`. For more info see [yarn cli docs](https://classic.yarnpkg.com/en/docs/cli/);
3. Import and use the installed package inside your **~.js** or **~.scss** files. For more info check [js import docs](https://developer.mozilla.org/ru/docs/Web/JavaScript/Reference/Statements/import) and [css import docs](https://developer.mozilla.org/en-US/docs/Web/CSS/@import);

#### How to use built-in theme images
- in **~.scss** files use relative path, e.g. `background-image: url('../images/image.jpg');`
- in **~.php** files use `asset_path()` helper, e.g. `asset_path('images/image.jpg')`;

#### How to start browsersync on different port
1. Go to **./config.json**;
2. Change port in **proxyUrl** parameter;

**Note!** Keep in mind that browsersync will take the next port as well for it's UI.

#### Disable Lazyload
1. Find and comment `import './plugins/lazyload';` line in `/assets/scripts/main.js`
2. Find and comment `require_once 'inc/class-lazyload.php';` line in `functions.php`

#### Use phpstorm external libraris to get the correct functions/methods highlighting and description
Theme config already includes the relative link to the library, but you need to clone in to your local machine.
We have a [repository](https://github.com/wlallc/phpstorm-external-libraries.git) that contains the wordpress core and most frequently used plugins.
For config to work correct you should place the library repo two foldes above then the current project. So the config is searching for the following path - ```../../phpstorm-external-libraries```.

If you want to place it somewhere else, you should change the path by yourself.
To do so, go to Settings->PHP, find the `Include Path` tab and add the required source location.
