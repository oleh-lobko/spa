<?php

use theme\Util;

add_action('init', function () {
    Util::registerPostType(
        'spa_event',
        'SPA Event',
        'SPA Events',
        ['supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions']]
    );

    Util::registerTaxonomy('spa_event_sponsor', 'spa_event', 'Sponsor', 'Sponsors');
});
