const path = require('path');
const resolveRequire = require('./util/resolve-require');
const validateConfig = require('./util/validate-config');

module.exports = (env = {}) => {
  const userConfig = resolveRequire(`${__dirname}/../config`);
  const validConfig = env.skipConfigValidation ? userConfig : validateConfig(userConfig);

  if (validConfig === false) {
    return false;
  }

  const rootPath =
    'paths' in validConfig && validConfig.paths.root
      ? validConfig.paths.root
      : process.cwd();
  const distPath = path.join(rootPath, 'dist');
  const themeName =
    'themeName' in validConfig ? validConfig.themeName : 'fwp';

  const entry = {
    main: ['./scripts/main.js', './styles/main.scss'],
    blocks: ['./styles/blocks.scss'],
  };

  if (process.env.NODE_ENV === undefined) {
    process.env.NODE_ENV = env.isProduction ? 'production' : 'none';
  }

  return {
    devUrl: '',
    themeName,
    proxyUrl: 'http://localhost:3000',
    open: false,
    bsWatchFiles: [
      'inc/**/*.php',
      'parts/**/*.php',
      'templates/**/*.php',
      'assets/**/*.js',
    ],
    env,
    entry,
    copy: 'images/**/*',
    publicPath: `/wp-content/themes/${themeName}/${path.basename(distPath)}/`,
    paths: {
      root: rootPath,
      dist: distPath,
      assets: path.join(rootPath, 'assets'),
    },
    enabled: {
      sourceMaps: true,
      optimize: env.isProduction,
      cacheBusting: env.isProduction,
    },
    manifest: {},
    ...validConfig,
  }
}
