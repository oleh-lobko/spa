<?php

use theme\CreateLazyImg;
use theme\DynamicAdmin;
use theme\WlAcfGfField;

/**
 * Functions.
 */

// Declaring the assets manifest
$manifest_json = get_theme_file_path() . '/dist/assets.json';
$assets = [
    'manifest' => file_exists($manifest_json) ? json_decode(file_get_contents($manifest_json), true) : [],
    'dist' => get_theme_file_uri() . '/dist',
    'dist_path' => get_theme_file_path() . '/dist',
];
unset($manifest_json);

/**
 * Retrieve the path to the asset, use hashed version if exists.
 *
 * @param mixed $asset
 * @param bool|string $path (optional) Defines if returned result is a path or a url (without leading slash if using path)
 *
 * @return string
 */
function asset_path($asset, $path = false)
{
    global $assets;
    $asset = isset($assets['manifest'][$asset]) ? $assets['manifest'][$asset] : $asset;

    return "{$assets[$path ? 'dist_path' : 'dist']}/{$asset}";
}

/** ========================================================================
 * Constants.
 */
define('IMAGE_PLACEHOLDER', asset_path('images/placeholder.jpg'));

/** ========================================================================
 * Included Functions.
 */
spl_autoload_register(function ($class_name) {
    if (0 === strpos($class_name, 'theme\\')) {
        $class_name = str_replace('theme\\', '', $class_name);
        $file = get_stylesheet_directory() . "/inc/classes/{$class_name}.php";

        if (!file_exists($file)) {
            echo sprintf(__('Error locating <code>%s</code> for inclusion.', 'fwp'), $file);
        } else {
            require_once $file;
        }
    }
});

array_map(function ($filename) {
    $file = get_stylesheet_directory() . "/inc/{$filename}.php";
    if (!file_exists($file)) {
        echo sprintf(__('Error locating <code>%s</code> for inclusion.', 'fwp'), $file);
    } else {
        include_once $file;
    }
}, [
    'helpers',
    'recommended-plugins',
    'theme-customizations',
    'home-slider',
    'svg-support',
    'gravity-form-customizations',
    'custom-fields-search',
    'google-maps',
    'tiny-mce-customizations',
    'posttypes',
    'rest',
    //    'gutenberg-support', // !!!IMPORTANT Comment the "Disable gutenberg" filter to enable Gutenberg
    //    'woo-customizations',
    //    'divi-support',
    //    'elementor-support',
    //    'shortcodes',
    'acf-placeholder',
    'pressable',
]);

// Register ACF Gravity Forms field
if (class_exists('theme\WlAcfGfField')) {
    // initialize
    new WlAcfGfField();
}

/** ========================================================================
 * Enqueue Scripts and Styles for Front-End.
 */
add_action('init', function () {
    wp_register_script('runtime.js', asset_path('scripts/runtime.js'), [], null, true);
    wp_register_script('ext.js', asset_path('scripts/ext.js'), [], null, true);
    if (file_exists(asset_path('styles/ext.css', true))) {
        wp_register_style('ext.css', asset_path('styles/ext.css'), [], null);
    }
});

add_action('wp_enqueue_scripts', function () {
    if (!is_admin()) {
        // Disable gutenberg built-in styles
        // wp_dequeue_style('wp-block-library');

        wp_enqueue_script('jquery');

        wp_enqueue_style('ext.css');
        wp_enqueue_style('main.css', asset_path('styles/main.css'), [], null);
        wp_enqueue_script(
            'main.js',
            asset_path('scripts/main.js'),
            ['jquery', 'runtime.js', 'ext.js'],
            null,
            true
        );

        wp_localize_script(
            'main.js',
            'ajax_object',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('project_nonce'),
            ]
        );
    }
});

/** ========================================================================
 * Additional Functions.
 */

// Dynamic Admin
if (class_exists('theme\DynamicAdmin') && is_admin()) {
    $dynamic_admin = new DynamicAdmin();
    //    $dynamic_admin->addField('page', 'template', __('Page Template', 'fwp'), 'template_detail_field_for_page');
    $dynamic_admin->run();
}

// Apply lazyload to whole page content
if (class_exists('theme\CreateLazyImg')) {
    add_action('template_redirect', function () {
        ob_start(function ($html) {
            $lazy = new CreateLazyImg();
            $buffer = $lazy->ignoreScripts($html);
            $buffer = $lazy->ignoreNoscripts($buffer);
            $html = $lazy->lazyloadImages($html, $buffer);
            $html = $lazy->lazyloadPictures($html, $buffer);

            return $lazy->lazyloadBackgroundImages($html, $buffer);
        });
    });
}

/** ========================================================================
 * PUT YOU FUNCTIONS BELOW.
 */

// Custom media library's image sizes
add_image_size('full_hd', 1920, 0, ['center', 'center']);
add_image_size('large_high', 1024, 0, false);
// add_image_size( 'name', width, height, ['center','center']);

// Disable gutenberg
add_filter('use_block_editor_for_post_type', '__return_false');
