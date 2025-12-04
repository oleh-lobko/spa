<?php
/**
 * Theme customizations.
 */

// Add correct theme textdomain for loco translate
load_theme_textdomain('fwp', get_template_directory() . '/languages');

// WP 5.2 wp_body_open backward compatibility
if (!function_exists('wp_body_open')) {
    function wp_body_open()
    {
        do_action('wp_body_open');
    }
}

// ACF Pro Options Page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => __('Theme General Settings', 'fwp'),
        'menu_title' => __('Theme Settings', 'fwp'),
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
    ]);
}

// By adding theme support, we declare that this theme does not use a
// hard-coded <title> tag in the document head, and expect WordPress to
// provide it for us.
add_theme_support('title-tag');

// Add widget support shortcodes
add_filter('widget_text', 'do_shortcode');

// Support for Featured Images
add_theme_support('post-thumbnails');

// Custom Background
add_theme_support('custom-background', ['default-color' => 'fff']);

// Custom Logo
add_theme_support('custom-logo', [
    'height' => '150',
    'flex-height' => true,
    'flex-width' => true,
]);

// Add HTML5 elements
add_theme_support('html5', [
    'comment-list',
    'search-form',
    'comment-form',
    'gallery',
    'caption',
    'script',
    'style',
]);

// Add RSS Links generation
add_theme_support('automatic-feed-links');

// Hide comments feed link
add_filter('feed_links_show_comments_feed', '__return_false');

// Add excerpt to pages
add_post_type_support('page', 'excerpt');

// Register Navigation Menu
register_nav_menus([
    'header-menu' => __('Header Menu', 'fwp'),
    'footer-menu' => __('Footer Menu', 'fwp'),
]);

// Register Sidebars
add_action('widgets_init', function () {
    // Sidebar Right
    register_sidebar([
        'id' => 'foundation_sidebar_right',
        'name' => __('Sidebar Right', 'fwp'),
        'description' => __('This sidebar is located on the right-hand side of each page.', 'fwp'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h5 class="widget__title">',
        'after_title' => '</h5>',
    ]);
});

// Remove #more anchor from posts
add_filter('the_content_more_link', function ($link) {
    $offset = strpos($link, '#more-');
    if ($offset) {
        $end = strpos($link, '"', $offset);
    }
    if (!empty($end)) {
        $link = substr_replace($link, '', $offset, $end - $offset);
    }

    return $link;
});

// Remove more tag <span> anchor
add_filter('the_content', function ($content) {
    return str_replace('<p><span id="more-' . get_the_ID() . '"></span></p>', '', $content);
});

// Remove author archive pages
add_action('template_redirect', function () {
    /**
     * @var object|WP_Query $wp_query
     */
    global $wp_query;

    if (is_author()) {
        $wp_query->set_404();
        status_header(404);
        // Redirect to homepage
        // wp_redirect(get_option('home'));
    }
});

// Enable revisions for all custom post types
add_filter('cptui_user_supports_params', function () {
    return ['revisions'];
});

// Limit revisions number to 10
add_filter('wp_revisions_to_keep', function () {
    return 10;
});

// Add ability ro reply to comments
add_filter('wpseo_remove_reply_to_com', '__return_false');

// Remove comments feed links
add_filter('post_comments_feed_link', '__return_null');

/**
 * Copyright field functionality.
 *
 * @param array $field ACF Field settings
 *
 * @return array
 */
add_action('acf/load_field/name=copyright', function ($field) {
    $field['instructions'] = 'Input <code>@year</code> to replace static year with dynamic, so it will always shows current year.';

    return $field;
});

if (!is_admin()) {
    // Replace @year with current year
    add_filter('acf/load_value/name=copyright', function ($value) {
        return str_replace('@year', date('Y'), $value);
    });

    // Stick Admin Bar To The Top
    add_action('get_header', function () {
        remove_action('wp_head', '_admin_bar_bump_cb');
    });

    $wl_stick_admin_bar = function () {
        ob_start(); ?>
        <style type='text/css'>
            body.admin-bar {
                margin-top: 32px !important
            }

            @media screen and (max-width: 782px) {
                body.admin-bar {
                    margin-top: 46px !important
                }
            }
        </style>
        <?php
        echo ob_get_clean();
    };

    add_action('admin_head', $wl_stick_admin_bar);
    add_action('wp_head', $wl_stick_admin_bar);
}

// Customize Login Screen
add_action('login_enqueue_scripts', function () {
    if ($custom_logo_id = get_theme_mod('custom_logo')) {
        $custom_logo_img = wp_get_attachment_image_src($custom_logo_id, 'medium');
        $custom_logo_src = $custom_logo_img[0];
    } else {
        $custom_logo_src = 'wp-admin/images/wordpress-logo.svg?ver=20131107';
    }
    ob_start(); ?>
    <style type="text/css">
        .login #login h1 a {
            background-image: url('<?php echo $custom_logo_src; ?>');
            background-size: contain;
            background-position: 50% 50%;
            width: auto;
            height: 120px;
        }

        body.login {
            background-color: #f1f1f1;
            background-position: center center;
            background-repeat: repeat;
        <?php if ($bg_image = get_background_image()) { ?> background-image: url('<?php echo $bg_image; ?>') !important;
        <?php } ?>
        }
    </style>
    <?php
    echo ob_get_clean();
});

add_filter('login_headerurl', function () {
    return get_bloginfo('url');
});

// Disable Emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('tiny_mce_plugins', function ($plugins) {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
});

// Enable control over YouTube iframe through API + add unique ID
add_filter('oembed_result', function ($html, $url, $args) {
    // Modify video parameters.
    if (strstr($html, 'youtube.com/embed/') && !empty($args['location'])) {
        preg_match_all('|embed/(.*)\?|', $html, $matches);
        $html = str_replace('?feature=oembed', '?feature=oembed&enablejsapi=1&autoplay=1&mute=1&controls=0&loop=1&showinfo=0&rel=0&playlist=' . $matches[1][0], $html);
        $html = str_replace('<iframe', '<iframe rel="0" enablejsapi="1" id=slide-' . get_the_ID(), $html);
    }

    return $html;
}, 10, 3);

/**
 * Wrap any iframe and emved tag into div for responsive view.
 *
 * @param $content
 *
 * @return string
 */
$wl_iframe_wrapper = function ($content) {
    // match any iframes
    $pattern = '~<iframe.*?<\/iframe>|<embed.*?<\/embed>~';
    preg_match_all($pattern, $content, $matches);

    foreach ($matches[0] as $match) {
        // Check if it is a video player iframe
        if (strpos($match, 'youtu') || strpos($match, 'vimeo')) {
            // wrap matched iframe with div
            $wrappedframe = '<div class="responsive-embed widescreen">' . $match . '</div>';
            // replace original iframe with new in content
            $content = str_replace($match, $wrappedframe, $content);
        }
    }

    return $content;
};

add_filter('the_content', $wl_iframe_wrapper);
add_filter('acf_the_content', $wl_iframe_wrapper);

// Custom outline color
add_action('wp_head', function () {
    $outline_color = get_theme_mod('outline_color');
    if ($outline_color) {
        ob_start(); ?>
        <style>
            a, input, button, textarea, select {
                outline-color: <?php echo $outline_color; ?>
            }
        </style>
        <?php
        echo ob_get_clean();
    }
});

// Replace Wordpress email Sender name
add_filter('wp_mail_from_name', function () {
    return get_bloginfo();
});

// Move Yoast Meta Box to bottom
add_filter('wpseo_metabox_prio', function () {
    return 'low';
});

// Disable Robin Image optimizer backup
add_filter('wbcr/factory/populate_option_backup_origin_images', function () {
    return !empty(get_option('wbcr_io_backup_origin_images')) ? get_option('wbcr_io_backup_origin_images') : 0;
});

add_action('wbcr/factory/plugin_activated', function () {
    update_option('wbcr_io_backup_origin_images', 0);
});

// Disable Robin Image resize image
add_filter('wbcr/factory/populate_option_resize_larger', function () {
    return !empty(get_option('wbcr_io_resize_larger')) ? get_option('wbcr_io_resize_larger') : 0;
});

/**
 * Get SVG real size (width+height / viewbox) and use it in `<img>` width, height attr.
 */
add_filter(
    'wp_get_attachment_image_src',
    /**
     * @param array|false $image Either array with src, width & height, icon src, or false
     * @param int $attachment_id Image attachment ID
     * @param array|string $size Size of image. Image size or array of width and height values
     *                           (in that order). Default 'thumbnail'.
     * @param bool $icon Whether the image should be treated as an icon. Default false.
     *
     * @return array
     */
    function ($image, $attachment_id, $size, $icon) {
        if (is_array($image) && preg_match('/\.svg$/i', $image[0]) && $image[1] <= 1) {
            if (is_array($size)) {
                $image[1] = $size[0];
                $image[2] = $size[1];
            //            } elseif (($xml = simplexml_load_file($image[0])) !== false) {
            //                $attr = $xml->attributes();
            //                $viewbox = explode(' ', $attr->viewBox);
            //                $image[1] = isset($attr->width) && preg_match('/\d+/', $attr->width, $value) ? (int) $value[0] : (4 == count($viewbox) ? (int) $viewbox[2] : null);
            //                $image[2] = isset($attr->height) && preg_match('/\d+/', $attr->height, $value) ? (int) $value[0] : (4 == count($viewbox) ? (int) $viewbox[3] : null);
            } else {
                $image[1] = $image[2] = null;
            }
        }

        return $image;
    },
    10,
    4
);
