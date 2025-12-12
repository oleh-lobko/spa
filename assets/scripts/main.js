// Import everything from autoload folder
import './autoload/**/*'; // eslint-disable-line

// Import local dependencies
import './plugins/lazyload';
import './plugins/modernizr.min';
import 'slick-carousel';
import 'jquery-match-height';
import objectFitImages from 'object-fit-images';

/* global google */

/**
 * Init foundation
 */
$(document).foundation();

/**
 * Fit slide video background to video holder
 */
function resizeVideo() {
  let $holder = $('.videoHolder');
  $holder.each(function () {
    let $that = $(this);
    let ratio = $that.data('ratio') ? $that.data('ratio') : '16:9';
    let width = parseFloat(ratio.split(':')[0]);
    let height = parseFloat(ratio.split(':')[1]);
    $that.find('.video').each(function () {
      if ($that.width() / width > $that.height() / height) {
        $(this).css({
          width: '100%',
          height: 'auto',
        });
      } else {
        $(this).css({
          width: ($that.height() * width) / height,
          height: '100%',
        });
      }
    });
  });
}

/**
 * Scripts which runs after DOM load
 */
$(document).on('ready', function () {
  /**
   * Make elements equal height
   */
  $('.matchHeight').matchHeight();

  /**
   * IE Object-fit cover polyfill
   */
  if ($('.of-cover').length) {
    objectFitImages('.of-cover');
  }

  /**
   * Remove placeholder on click
   */
  const removeFieldPlaceholder = () => {
    $('input, textarea').each((i, el) => {
      $(el)
        .data('holder', $(el).attr('placeholder'))
        .on('focusin', () => {
          $(el).attr('placeholder', '');
        })
        .on('focusout', () => {
          $(el).attr('placeholder', $(el).data('holder'));
        });
    });
  };

  removeFieldPlaceholder();

  $(document).on('gform_post_render', () => {
    removeFieldPlaceholder();
  });

  /**
   * Scroll to Gravity Form confirmation message after form submit
   */
  $(document).on('gform_confirmation_loaded', function (event, formId) {
    let $target = $('#gform_confirmation_wrapper_' + formId);
    if ($target.length) {
      $('html, body').animate({ scrollTop: $target.offset().top - 50 }, 500);
      return false;
    }
  });

  /**
   * Hide gravity forms required field message on data input
   */
  $('body').on(
    'change keyup',
    '.gfield input, .gfield textarea, .gfield select',
    function () {
      let $field = $(this).closest('.gfield');
      if ($field.hasClass('gfield_error') && $(this).val().length) {
        $field.find('.validation_message').hide();
      } else if ($field.hasClass('gfield_error') && !$(this).val().length) {
        $field.find('.validation_message').show();
      }
    }
  );

  function toggleMobileMenu() {
    const $menuToggle = $('.mobile-menu-toggle');
    const $menu = $('#main-menu');

    const isActive = $menuToggle.hasClass('is-active');

    if (isActive) {
      $menuToggle.removeClass('is-active');
      $menu.removeClass('expanded').slideUp(300);
    } else {
      $menuToggle.addClass('is-active');
      $menu.addClass('expanded').slideDown(300);
    }
  }

  function closeMobileMenu() {
    $('.mobile-menu-toggle').removeClass('is-active');
    $('#main-menu').removeClass('expanded').slideUp(300);
  }

  $(document).on('click', '.mobile-menu-toggle', function (e) {
    e.preventDefault();
    e.stopPropagation();
    toggleMobileMenu();
  });

  $(document).on('click', '.header-menu a', function () {
    if (window.innerWidth < 640) {
      closeMobileMenu();
    }
  });

  $(document).on('click', function (e) {
    if (window.innerWidth < 640) {
      if (
        !$(e.target).closest('.header').length &&
        $('.mobile-menu-toggle').hasClass('is-active')
      ) {
        closeMobileMenu();
      }
    }
  });

  $(window).on('resize', function () {
    if (window.innerWidth >= 640) {
      $('.mobile-menu-toggle').removeClass('is-active');
      $('#main-menu').removeClass('expanded').show();
    } else {
      $('#main-menu').hide();
    }
  });

  $(window).on('orientationchange', function () {
    if (
      $('.mobile-menu-toggle').hasClass('is-active') &&
      window.innerWidth < 641
    ) {
      closeMobileMenu();
    }
  });

  resizeVideo();
});

/**
 * Scripts which runs after all elements load
 */
$(window).on('load', function () {
  let $preloader = $('.preloader');
  if ($preloader.length) {
    $preloader.addClass('preloader--hidden');
  }

  /*
   *  This function will render each map when the document is ready (page has loaded)
   */
  $('.event-map-container').each(function () {
    render_map($(this));
  });
  document.querySelectorAll('.ctf-tweet-date').forEach((el) => {
    el.textContent = formatTimeAgo(el.textContent);
  });
});

/**
 * Scripts which runs at window resize
 */
$(window).on('resize', function () {
  resizeVideo();
});

/**
 * Scripts which runs on scrolling
 */
$(window).on('scroll', function () {
  // jQuery code goes here
});

document.addEventListener('DOMContentLoaded', function () {
  // Remove date restrictions to allow past dates
  const dateInputs = document.querySelectorAll('input[type="date"]');
  dateInputs.forEach(function (input) {
    input.removeAttribute('min');
    input.removeAttribute('max');
  });
});

// ACF Google Map JS code

/*
 *  This function will render a Google Map onto the selected jQuery element
 */
function render_map($el) {
  // var
  var $markers = $el.find('.marker');
  // var styles = []; // Uncomment for map styling

  // vars
  var args = {
    zoom: 16,
    center: new google.maps.LatLng(0, 0),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: false,
    styles: [
      {
        featureType: 'all',
        elementType: 'labels.text',
        stylers: [
          {
            color: '#878787',
          },
        ],
      },
      {
        featureType: 'all',
        elementType: 'labels.text.stroke',
        stylers: [
          {
            visibility: 'off',
          },
        ],
      },
      {
        featureType: 'landscape',
        elementType: 'all',
        stylers: [
          {
            color: '#f9f5ed',
          },
        ],
      },
      {
        featureType: 'road.highway',
        elementType: 'all',
        stylers: [
          {
            color: '#f5f5f5',
          },
        ],
      },
      {
        featureType: 'road.highway',
        elementType: 'geometry.stroke',
        stylers: [
          {
            color: '#c9c9c9',
          },
        ],
      },
      {
        featureType: 'water',
        elementType: 'all',
        stylers: [
          {
            color: '#aee0f4',
          },
        ],
      },
    ],
  };

  // create map
  var map = new google.maps.Map($el[0], args);

  // add a markers reference
  map.markers = [];

  // add markers
  $markers.each(function () {
    add_marker($(this), map);
  });

  // center map
  center_map(map);
}

/*
 *  This function will add a marker to the selected Google Map
 */
var infowindow;

function add_marker($marker, map) {
  // var
  var latlng = new google.maps.LatLng(
    $marker.attr('data-lat'),
    $marker.attr('data-lng')
  );

  // Custom marker icon
  var customIcon = {
    url:
      $marker.data('marker-icon') ||
      '/wp-content/themes/your-theme/assets/images/event.png',
    scaledSize: new google.maps.Size(150, 70),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(40, 90),
  };
  // create marker
  var marker = new google.maps.Marker({
    position: latlng,
    map: map,
    icon: customIcon,
  });

  // add to array
  map.markers.push(marker);

  // if marker contains HTML, add it to an infoWindow
  if ($.trim($marker.html())) {
    // create info window
    infowindow = new google.maps.InfoWindow();

    // show info window when marker is clicked
    google.maps.event.addListener(marker, 'click', function () {
      // Close previously opened infowindow, fill with new content and open it
      infowindow.close();
      infowindow.setContent($marker.html());
      infowindow.open(map, marker);
    });
  }
}

/*
 *  This function will center the map, showing all markers attached to this map
 */
function center_map(map) {
  // vars
  var bounds = new google.maps.LatLngBounds();

  // loop through all markers and create bounds
  $.each(map.markers, function (i, marker) {
    var latlng = new google.maps.LatLng(
      marker.position.lat(),
      marker.position.lng()
    );
    bounds.extend(latlng);
  });

  // only 1 marker?
  if (map.markers.length == 1) {
    // set center of map
    map.setCenter(bounds.getCenter());
  } else {
    // fit to bounds
    map.fitBounds(bounds);
  }
}
function formatTimeAgo(compact) {
  const match = String(compact)
    .trim()
    .match(/^(\d+)([hdwmy])$/i);
  if (!match) return compact; // fallback if format is unknown

  let value = parseInt(match[1], 10);
  let unit = match[2].toLowerCase();

  // Normalize units: 24h -> 1d, 7d -> 1w, 4w -> 1m, 12m -> 1y
  if (unit === 'h' && value >= 24) {
    value = Math.round(value / 24);
    unit = 'd';
  }
  if (unit === 'd' && value >= 7) {
    value = Math.round(value / 7);
    unit = 'w';
  }
  if (unit === 'w' && value >= 4) {
    value = Math.round(value / 4);
    unit = 'm';
  }
  if (unit === 'm' && value >= 12) {
    value = Math.round(value / 12);
    unit = 'y';
  }

  const labels = {
    h: ['hour', 'hours'],
    d: ['day', 'days'],
    w: ['week', 'weeks'],
    m: ['month', 'months'],
    y: ['year', 'years'],
  };

  const [singular, plural] = labels[unit] || ['', ''];
  const word = value === 1 ? singular : plural;

  return `About ${value} ${word} ago`;
}
