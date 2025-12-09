
<?php
/**
 * Twitter Integration for SPA Theme - Fixed Layout
 */

// Customize Custom Twitter Feeds plugin output
add_filter('ctf_feed_options', function($options, $feed_id) {
    $spa_options = array(
        'headertext' => '@HEALTHSPAFI',
        'showheader' => true,
        'showfollow' => false,
        'showlogo' => false,
        'num' => 3,
        'width' => '100%',
        'height' => 'auto',
        'bgcolor' => 'transparent',
        'textcolor' => '#ffffff',
        'linkcolor' => '#17c8ff',
        'includereplies' => false,
        'includeretweets' => true,
        'layout' => 'list',
        'cols' => 3,
        'colsmobile' => 1,
        'colstablet' => 3,
        'showloadmore' => false,
        'showmore' => false,
        'showfollow' => false,
        'showheader' => true,
        'showbio' => false,
        'showshare' => false,
        'showload' => false,
        'showfooter' => false,
    );

    return array_merge($options, $spa_options);
}, 10, 2);

// Add custom header with Twitter icon
add_filter('ctf_header_html', function($header_html, $options) {
    $custom_header = '<div class="ctf-header">';
    $custom_header .= '<div class="ctf-header-text">@HEALTHSPAFI</div>';
    $custom_header .= '</div>';

    return $custom_header;
}, 10, 2);

// Hide Load More button completely
add_filter('ctf_show_load_more_button', '__return_false');

// Remove footer content
add_filter('ctf_footer_html', function($footer_html, $options) {
    return ''; // Return empty string to hide footer
}, 10, 2);

// Remove "no more tweets" message
add_filter('ctf_no_more_tweets_text', function($text) {
    return ''; // Return empty string
}, 10, 1);

// Remove any "powered by" text
add_filter('ctf_credit_html', function($credit_html) {
    return ''; // Remove credit/powered by text
}, 10, 1);

// Hide share buttons
add_filter('ctf_show_share_buttons', '__return_false');

// Widget for Custom Twitter Feeds
class SPA_Custom_Twitter_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'spa_custom_twitter_widget',
            'SPA Custom Twitter Feed',
            array('description' => 'Display Custom Twitter Feeds with SPA styling')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        if (function_exists('ctf_init')) {
            echo do_shortcode('[custom-twitter-feeds num=3 cols=3 colsmobile=1 colstablet=3 showloadmore=false showfollow=false showmore=false showfooter=false showshare=false]');
        } else {
            // Simple fallback
            echo '<div class="ctf">';
            echo '<div class="ctf-header"><div class="ctf-header-text">@HEALTHSPAFI</div></div>';
            echo '<div class="ctf-tweets">';
            echo '<div class="ctf-tweet"><div class="ctf-tweet-time">ABOUT 1 HOUR AGO</div><div class="ctf-tweet-text">Our friends @cofoundermag had a look at what the #health #technology scene in Estonia looks like @htcluster</div></div>';
            echo '<div class="ctf-tweet"><div class="ctf-tweet-time">ABOUT 1 HOUR AGO</div><div class="ctf-tweet-text">Join the #Wellness and #Health Tech fair tomorrow in @messukesku! Meet @Nurse_Buddy, @Sensorend_Fi and others!</div></div>';
            echo '<div class="ctf-tweet"><div class="ctf-tweet-time">ABOUT 1 HOUR AGO</div><div class="ctf-tweet-text">Heard about the @WearableTech event coming up Feb 2-3? It\'s #huge! #wearables #mhealth</div></div>';
            echo '</div>';
            echo '</div>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        echo '<p>Twitter feed will automatically display @HEALTHSPAFI tweets in 3 columns.</p>';
    }

    public function update($new_instance, $old_instance) {
        return array();
    }
}

// Register the widget
add_action('widgets_init', function() {
    register_widget('SPA_Custom_Twitter_Widget');
});

// Additional CSS injection to ensure footer is hidden
add_action('wp_head', function() {
    echo '<style>
    .ctf .ctf-load-more,
    .ctf .ctf-more,
    .ctf-load-more-btn,
    .ctf-more-btn,
    .ctf .ctf-footer,
    .ctf-footer,
    .ctf .ctf-show-more,
    .ctf-show-more,
    .ctf .ctf-no-more,
    .ctf-no-more,
    .ctf .ctf-end,
    .ctf-end,
    .ctf .ctf-bottom,
    .ctf-bottom,
    .ctf .ctf-no-more-tweets,
    .ctf-no-more-tweets,
    .ctf .ctf-end-message,
    .ctf-end-message {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
    }
    </style>';
});
?>
