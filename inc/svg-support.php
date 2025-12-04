<?php

/**
 * IF YOU HAVE ANY DIFFICULTIES WITH SVG UPLOAD USE SVG-SUPPORT PLUGIN
 * https://wordpress.org/plugins/svg-support/.
 */

// Add mime types
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_filter('upload_mimes', function ($mimes = []) {
    // allow SVG file upload
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
});

// Check Mime Types
add_filter('wp_check_filetype_and_ext', function ($checked, $file, $filename, $mimes) {
    if (!$checked['type']) {
        $check_filetype = wp_check_filetype($filename, $mimes);
        $ext = $check_filetype['ext'];
        $type = $check_filetype['type'];
        $proper_filename = $filename;

        if ($type && 0 === strpos($type, 'image/') && 'svg' !== $ext) {
            $ext = $type = false;
        }

        $checked = compact('ext', 'type', 'proper_filename');
    }

    return $checked;
}, 10, 4);

// Add ability to view thumbnails in wp 4.0+
add_action('admin_init', function () {
    ob_start();

    ob_start(function ($content) {
        return apply_filters('final_output', $content);
    });

    add_filter('final_output', function ($content) {
        $content = str_replace(
            '<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
            '<# } else if ( \'svg+xml\' === data.subtype ) { #>
                    <img class="details-image" src="{{ data.url }}" draggable="false" />
                    <# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
            $content
        );

        return str_replace(
            '<# } else if ( \'image\' === data.type && data.sizes ) { #>',
            '<# } else if ( \'svg+xml\' === data.subtype ) { #>
                    <div class="centered">
                        <img src="{{ data.url }}" class="thumbnail" draggable="false" />
                    </div>
                    <# } else if ( \'image\' === data.type && data.sizes ) { #>',
            $content
        );
    });
});

/**
 * Insert additional class to img tag and specify img dimensions if user select svg file.
 *
 * @param string $html img tag
 *
 * @return string
 */
add_filter('get_image_tag', function ($html) {
    if (false !== strpos($html, '.svg')) {
        $html = preg_replace('|class="(.+?)"|', 'class="$1 attachment-svg"', $html);
        $html = str_replace('width="1"', 'width="64"', $html);
        $html = str_replace('height="1"', 'height="64"', $html);
    }

    return $html;
});

add_filter('wp_prepare_attachment_for_js', function ($response, $attachment, $meta) {
    if ('image/svg+xml' == $response['mime'] && empty($response['sizes'])) {
        $svg_path = get_attached_file($attachment->ID);

        if (!file_exists($svg_path)) {
            // If SVG is external, use the URL instead of the path
            $svg_path = $response['url'];
        }

        $svg = simplexml_load_file($svg_path);

        if (false === $svg) {
            $width = '0';
            $height = '0';
        } else {
            $attributes = $svg->attributes();
            $width = (string) $attributes->width;
            $height = (string) $attributes->height;
        }

        $dimensions = (object) [
            'width' => $width,
            'height' => $height,
        ];

        $response['sizes'] = [
            'full' => [
                'url' => $response['url'],
                'width' => $dimensions->width,
                'height' => $dimensions->height,
                'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait',
            ],
        ];
    }

    return $response;
}, 10, 3);
