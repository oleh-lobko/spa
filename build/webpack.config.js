const webpack = require('webpack');
const path = require('path');
const { argv } = require('yargs');

// Webpack plugins
const CopyPlugin = require('copy-webpack-plugin');
const CircularDependencyPlugin = require('circular-dependency-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const EslintPlugin = require('eslint-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const MinifyPlugin = require('babel-minify-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');

module.exports = (env = {}) => {
  // config
  const config = require('./config')({
    isProduction: 'production' in env,
    skipConfigValidation: 'skip-config-validation' in env,
    skipLint: 'skip-lint' in env,
    watch: 'watch' in env,
  });

  const assetsFilenames = `[name]${
    config.enabled.cacheBusting ? '.[contenthash]' : ''
  }`;

  let webpackConfig = {
    mode: config.env.isProduction ? 'production' : 'development',
    context: config.paths.assets,
    entry: config.entry,
    devtool: config.enabled.sourceMaps ? 'source-map' : false,
    output: {
      path: config.paths.dist,
      publicPath: '.' + config.publicPath,
      filename: `scripts/${assetsFilenames}.js`,
    },
    stats: {
      hash: false,
      version: false,
      timings: false,
      children: false,
      errors: true,
      errorDetails: true,
      warnings: false,
      chunks: false,
      modules: false,
      reasons: false,
      source: false,
      publicPath: false,
    },
    module: {
      rules: [
        {
          enforce: 'pre',
          test: /\.(js|s?[ca]ss)$/,
          include: config.paths.assets,
          loader: 'import-glob',
        },
        {
          test: /\.js$/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env', '@babel/preset-react'],
            },
          },
        },
        {
          test: /\.(sa|sc|c)ss$/,
          include: config.paths.assets,
          use: [
            {
              loader: MiniCssExtractPlugin.loader,
              options: {
                publicPath: '../',
              },
            },
            {
              loader: 'css-loader',
              options: { sourceMap: config.enabled.sourceMaps },
            },
            {
              loader: 'postcss-loader',
              options: {
                sourceMap: config.enabled.sourceMaps,
                postcssOptions: {
                  parser: 'postcss-safe-parser',
                  plugins: {
                    cssnano: {
                      preset: [
                        'default',
                        { discardComments: { removeAll: true } },
                      ],
                    },
                    autoprefixer: true,
                  },
                },
              },
            },
            {
              loader: 'resolve-url-loader',
              options: { sourceMap: config.enabled.sourceMaps },
            },
            {
              loader: 'sass-loader',
              options: {
                sassOptions: {
                  sourceMap: config.enabled.sourceMaps,
                  sourceMapContents: false,
                  outputStyle: 'compressed',
                },
              },
            },
          ],
        },
        {
          test: /\.(ttf|otf|eot|woff2?|png|jpe?g|gif|svg|ico)$/,
          include: config.paths.assets,
          type: 'asset/resource',
          generator: {
            filename: `[path]${assetsFilenames}[ext]`,
          },
        },
        {
          test: /\.(ttf|otf|eot|woff2?|png|jpe?g|gif|svg|ico)$/,
          include: /node_modules/,
          type: 'asset/resource',
          generator: {
            filename: (module) =>
              module.filename.replace('../node_modules', 'ext'),
          },
        },
      ],
    },
    resolve: {
      modules: [config.paths.assets, 'node_modules'],
      enforceExtension: false,
    },
    externals: {
      jquery: 'jQuery',
    },
    optimization: {
      minimize: config.env.isProduction,
      concatenateModules: config.env.isProduction,
      sideEffects: config.env.isProduction,
      runtimeChunk: 'single',
      moduleIds: 'deterministic',
      splitChunks: {
        chunks: 'async',
        name: config.env.isProduction ? false : 'main',

        cacheGroups: {
          ext: {
            test: /[\\/]node_modules[\\/]/,
            priority: -10,
            reuseExistingChunk: true,
            name: 'ext',
            chunks: 'all',
          },
        },
      },
    },
    plugins: [
      new CopyPlugin({
        patterns: [
          {
            from: config.copy,
            to: `[path]${assetsFilenames}[ext]`,
          },
        ],
      }),
      new MiniCssExtractPlugin({
        filename: `./styles/${assetsFilenames}.css`,
        ignoreOrder: true,
      }),
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
      }),
      new CleanWebpackPlugin({
        verbose: false,
      }),
      new CircularDependencyPlugin({
        exclude: /node_modules/,
        failOnError: false,
      }),
    ],
  };

  if (!config.env.skipLint) {
    webpackConfig.plugins.push(new StylelintPlugin({
      lintDirtyModulesOnly: config.env.watch,
    }));
    webpackConfig.plugins.push(new EslintPlugin({
      lintDirtyModulesOnly: config.env.watch,
    }));
  }

  if (config.env.isProduction) {
    webpackConfig.plugins.push(new MinifyPlugin());
  }

  if (config.enabled.optimize) {
    const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');

    webpackConfig.optimization = {
      ...webpackConfig.optimization,
      minimizer: [
        new ImageMinimizerPlugin({
          minimizer: [
            {
              // `sharp` will handle all bitmap formats (JPG, PNG, GIF, ...)
              implementation: ImageMinimizerPlugin.sharpMinify,

              // exclude SVG if implementation support it. Not required for `sharp`.
              // filter: (source, sourcePath) => !(/\.(svg)$/i.test(sourcePath)),
              options: {
                encodeOptions: {
                  // Your options for `sharp`
                  // https://sharp.pixelplumbing.com/api-output
                  jpeg: {
                    // https://sharp.pixelplumbing.com/api-output#jpeg
                    quality: 80,
                  },
                  webp: {
                    // https://sharp.pixelplumbing.com/api-output#webp
                    lossless: true,
                  },
                  avif: {
                    // https://sharp.pixelplumbing.com/api-output#avif
                    lossless: true,
                  },

                  // png by default sets the quality to 100%, which is same as lossless
                  // https://sharp.pixelplumbing.com/api-output#png
                  png: {
                    quality: 80,
                  },

                  // gif does not support lossless compression at all
                  // https://sharp.pixelplumbing.com/api-output#gif
                  gif: {},
                },
              },
            },
            {
              // `svgo` will handle vector images (SVG)
              implementation: ImageMinimizerPlugin.svgoMinify,
              options: {
                encodeOptions: {
                  // Pass over SVGs multiple times to ensure all optimizations are applied. False by default
                  multipass: true,
                  plugins: [
                    // set of built-in plugins enabled by default
                    // see: https://github.com/svg/svgo#default-preset
                    'preset-default',
                  ],
                },
              },
            },
          ],
        }),
      ],
    };
  }

  if (config.enabled.cacheBusting) {
    const WebpackAssetsManifest = require('webpack-assets-manifest');

    webpackConfig.plugins = [
      ...webpackConfig.plugins,
      new WebpackAssetsManifest({
        output: 'assets.json',
        space: 2,
        writeToDisk: false,
        assets: config.manifest,
        customize: (entry) => {
          const { key, value } = entry;
          const sourcePath = path.basename(path.dirname(key));
          const targetPath = path.basename(path.dirname(value));
          if (sourcePath === targetPath) {
            return entry;
          }

          return {
            key: `${targetPath}/${key}`,
            value,
          };
        },
      }),
    ];
  }

  return webpackConfig;
};
