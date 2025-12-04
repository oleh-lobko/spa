import LazyLoad from 'vanilla-lazyload';

// Init LazyLoad
let lazyLoadInstance = new LazyLoad({
  elements_selector: 'img[data-lazy-src],.pre-lazyload',
  data_src: 'lazy-src',
  data_srcset: 'lazy-srcset',
  data_sizes: 'lazy-sizes',
  skip_invisible: false,
  class_loading: 'lazyloading',
  class_loaded: 'lazyloaded',
});
// Add tracking on adding any new nodes to body to update lazyload for the new images (AJAX for example)
window.addEventListener(
  'LazyLoad::Initialized',
  function () {
    // Get the instance and puts it in the lazyLoadInstance variable
    if (window.MutationObserver) {
      let observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
          mutation.addedNodes.forEach(function (node) {
            if (typeof node.getElementsByTagName !== 'function') {
              return;
            }
            let imgs = node.getElementsByTagName('img');
            if (0 === imgs.length) {
              return;
            }
            lazyLoadInstance.update();
          });
        });
      });
      let b = document.getElementsByTagName('body')[0];
      let config = { childList: true, subtree: true };
      observer.observe(b, config);
    }
  },
  false
);

// Update LazyLoad images before Slide change
// $('.slick-slider').on('beforeChange', function() {
//   lazyLoadInstance.update()
// })
