const hotMiddlewareScript = require('webpack-hot-middleware/client?noInfo=true&timeout=20000&reload=false');

hotMiddlewareScript.subscribe((event) => {
  if (event.action === 'reload') {
    window.location.reload();
  }
});
