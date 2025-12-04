<?php

/**
 * Example shortcode
 * [example_shortcode foo=bar].
 *
 * @param $atts array Shortcode attributes
 *
 * @return string
 */
add_shortcode('example_shortcode', function ($atts) {
    // Set white list of attributes and specify its default values
    $atts = shortcode_atts(
        ['foo' => 'no foo'],
        $atts,
        'example_shortcode'
    );

    return 'foo:' . $atts['foo'];
});
