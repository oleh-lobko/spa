const url = require('url');

module.exports = (config) => {
  const target = config.devUrl;

  if (!target || target.length === 0 || target === '%SITE_URL%') {
    console.error(
      '\x1b[30m\x1b[41m %s \x1b[0m \x1b[31m%s\x1b[0m',
      'devUrl is not specified',
      'Specify the correct devUrl in config.json!'
    );
    return false;
  }

  if (!('proxyUrl' in config) || config.proxyUrl.length === 0) {
    console.error(
      '\x1b[30m\x1b[41m %s \x1b[0m \x1b[31m%s\x1b[0m',
      'proxyUrl is not specified',
      'Specify the correct proxyUrl in config.json!'
    );
    return false;
  }

  if (target.substr(-1) === '/') {
    config.devUrl = target.slice(0, -1);
    console.warn(
      '\x1b[32m%s\x1b[0m',
      'devUrl contains trailing slash',
      'Trailing slash was removed.'
    );
  }

  if (url.parse(target).protocol !== url.parse(config.proxyUrl).protocol) {
    console.warn(
      '\x1b[35m\x1b[43m %s \x1b[0m \x1b[33m%s\x1b[0m',
      'Unmatched protocols',
      'proxyUrl should have the same protocol as the devURL.'
    );
    config.proxyUrl = config.proxyUrl.replace(
      url.parse(config.proxyUrl).protocol,
      url.parse(target).protocol
    );
    console.warn(
      '\x1b[32m%s\x1b[0m',
      `Protocol was automatically switched to "${url
        .parse(target)
        .protocol.replace(':', '')}"`
    );
  }

  return config;
};
