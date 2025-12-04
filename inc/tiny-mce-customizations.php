<?php

/**
 * Custom styles in TinyMCE.
 */
add_filter('mce_buttons_2', function ($buttons) {
    array_unshift($buttons, 'styleselect');

    return $buttons;
});

add_filter('tiny_mce_before_init', function ($init_array) {
    // Define the style_formats array
    $headings = [
        'title' => __('Headings', 'bwp'),
        'items' => [
            [
                'title' => 'Heading 1',
                'classes' => 'h1',
                'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
                'wrapper' => false,
            ],
            [
                'title' => 'Heading 2',
                'classes' => 'h2',
                'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
                'wrapper' => false,
            ],
            [
                'title' => 'Heading 3',
                'classes' => 'h3',
                'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
                'wrapper' => false,
            ],
            [
                'title' => 'Heading 4',
                'classes' => 'h4',
                'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
                'wrapper' => false,
            ],
            [
                'title' => 'Heading 5',
                'classes' => 'h5',
                'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
                'wrapper' => false,
            ],
            [
                'title' => 'Heading 6',
                'classes' => 'h6',
                'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
                'wrapper' => false,
            ],
        ],
    ];

    $text = [
        'title' => __('Text', 'bwp'),
        'items' => [
            [
                'title' => 'Small',
                'inline' => 'small',
            ],
            [
                'title' => 'Large',
                'classes' => 'text-large',
                'inline' => 'span',
            ],
            [
                'title' => 'Uppercase',
                'classes' => 'text-uppercase',
                'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
            ],
            [
                'title' => 'Justify',
                'classes' => 'text-align-justify',
                'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
            ],
            [
                'title' => 'Underlined',
                'inline' => 'u',
            ],
        ],
    ];

    $lists = [
        'title' => __('Lists', 'bwp'),
        'items' => [
            [
                'title' => 'List unstyled',
                'classes' => 'list-unstyled',
                'selector' => 'ul',
            ],
            [
                'title' => 'Two columns',
                'classes' => 'two-columns',
                'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
            ],
            [
                'title' => 'Three columns',
                'classes' => 'three-columns',
                'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
            ],
        ],
    ];

    $buttons = [
        'title' => __('Buttons', 'bwp'),
        'items' => [
            [
                'title' => 'Button',
                'classes' => 'button',
                'selector' => 'a',
                'wrapper' => false,
            ],
            [
                'title' => 'Fancybox open',
                'classes' => 'fancybox',
                'selector' => 'a',
                'wrapper' => false,
            ],
        ],
    ];

    $spaces = [
        'title' => __('Spaces', 'bwp'),
        'items' => [
            [
                'title' => 'None',
                'classes' => 'mb-0',
                'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
            ],
            [
                'title' => 'Small',
                'classes' => 'mb-2 mb-lg-3',
                'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
            ],
            [
                'title' => 'Medium',
                'classes' => 'mb-3 mb-lg-4',
                'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
            ],
            [
                'title' => 'Large',
                'classes' => 'mb-3 mb-lg-5',
                'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
            ],
        ],
    ];

    $style_formats = [$headings, $text, $lists, $buttons, $spaces];

    $init_array['style_formats'] = json_encode($style_formats);

    return $init_array;
});

add_editor_style();

// Add custom color to TinyMCE editor text color selector
add_filter('tiny_mce_before_init', function ($init) {
    $default_colours = [
        'Black' => '000000',
        'Burnt orange' => '993300',
        'Dark olive' => '333300',
        'Dark green' => '003300',
        'Dark azure' => '003366',
        'Navy Blue' => '000080',
        'Indigo' => '333399',
        'Very dark gray' => '333333',
        'Maroon' => '800000',
        'Orange' => 'ff6600',
        'Olive' => '808000',
        'Green' => '008000',
        'Teal' => '008080',
        'Blue' => '0000ff',
        'Grayish blue' => '666699',
        'Gray' => '808080',
        'Red' => 'ff0000',
        'Amber' => 'ff9900',
        'Yellow green' => '99cc00',
        'Sea green' => '339966',
        'Turquoise' => '33cccc',
        'Royal blue' => '3366ff',
        'Purple' => '800080',
        'Medium gray' => '999999',
        'Magenta' => 'ff00ff',
        'Gold' => 'ffcc00',
        'Yellow' => 'ffff00',
        'Lime' => '00ff00',
        'Aqua' => '00ffff',
        'Sky blue' => '00ccff',
        'Brown' => '993366',
        'Silver' => 'c0c0c0',
        'Pink' => 'ff99cc',
        'Peach' => 'ffcc99',
        'Light yellow' => 'ffff99',
        'Pale green' => 'ccffcc',
        'Pale cyan' => 'ccffff',
        'Light sky blue' => '99ccff',
        'Plum' => 'cc99ff',
        'White' => 'ffffff',
    ];

    /**
     * By using the same array keys as the default values you'll override (replace) them.
     */
    $custom_colours = [
        'Navy' => '123154',
        'Light Navy' => '173a62',
        'Red' => 'e21c54',
        'Black' => '1d1d1d',
        'Gray' => '737373',
    ];

    $textcolor_map = [];
    foreach (array_merge($default_colours, $custom_colours) as $name => $color) {
        $textcolor_map[] = "\"{$color}\", \"{$name}\"";
    }

    if (!empty($textcolor_map)) {
        $init['textcolor_map'] = '[' . implode(', ', $textcolor_map) . ']';
        $init['textcolor_rows'] = 6; // expand colour grid to 6 rows
    }

    return $init;
});
