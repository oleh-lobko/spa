const url = require('url');
const webpack = require('webpack');
const browserSync = require('browser-sync');
const webpackDevMiddleware = require('webpack-dev-middleware');
const webpackHotMiddleware = require('webpack-hot-middleware');

const config = require('./config')();

if (config === false) {
  return;
}

/**
 * We do this to enable injection over SSL.
 */
if (url.parse(config.devUrl).protocol === 'https:') {
  process.env.NODE_TLS_REJECT_UNAUTHORIZED = 0;
}

if (url.parse(config.devUrl).path !== '/') {
  config.proxyUrl += url.parse(config.devUrl).path;
}

const webpackConfig = require('./webpack.config')({ watch: true });

webpackConfig.plugins = [
  ...webpackConfig.plugins,
  new webpack.HotModuleReplacementPlugin(),
];
webpackConfig.entry = require('./util/addHotMiddleware')(webpackConfig.entry);

webpackConfig.output = {
  ...webpackConfig.output,
  pathinfo: true,
  publicPath: config.proxyUrl + config.publicPath,
};

const bundler = webpack(webpackConfig);

browserSync({
  host: url.parse(config.proxyUrl).hostname,
  port: url.parse(config.proxyUrl).port,
  delay: 200,
  open: config.open,
  ui: false,
  ghostMode: false,

  proxy: {
    target: config.devUrl,
    middleware: [
      webpackDevMiddleware(bundler, {
        publicPath: config.proxyUrl + config.publicPath,
        stats: false,
        // for other settings see
        // https://webpack.js.org/guides/development/#using-webpack-dev-middleware
        // https://webpack.js.org/configuration/dev-server/
      }),

      webpackHotMiddleware(bundler),
    ],
  },
  files: config.bsWatchFiles,
});
