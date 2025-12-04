<?php

use theme\Util;

add_action('init', function () {
    Util::registerPostType(
        'example-posttype',
        'Example posttype',
        'Examples posttype',
        ['supports' => ['title', 'thumbnail']]
    );

    Util::registerTaxonomy('example-taxonomy', 'example-posttype', 'Example taxonomy', 'Example taxonomies');
});
