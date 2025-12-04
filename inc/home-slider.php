<?php

use theme\Util;

// Register Post Type Slider
add_action('init', function () {
    Util::registerPostType('slider', 'Slide', 'Slides', [
        'public' => false,
        'hierarchical' => true,
        'menu_icon' => 'dashicons-format-gallery',
        'menu_position' => 5,
        'supports' => [
            'title',
            'thumbnail',
            'page-attributes',
            'editor',
            'post-formats',
        ],
    ]);
    add_theme_support('post-formats', ['video']);
    remove_post_type_support('post', 'post-formats');
});

add_action('add_meta_boxes', function () {
    $screens = ['slider'];
    add_meta_box(
        'slide_background',
        __('Slide background', 'fwp'),
        function ($post, $meta) {
            wp_nonce_field('save_video_bg', 'project_nonce'); ?>
            <style>
                .fields-list {
                    margin-left: -12px;
                    margin-right: -12px;
                }

                .fields-list::after {
                    content: '';
                    display: table;
                    clear: both;
                }

                .field-wrap {
                    float: left;
                    padding-left: 12px;
                    padding-right: 12px;
                    box-sizing: border-box;
                }
            </style>
            <div class="fields-list">
                <div class="field-wrap" style="width: 70%">
                    <p class="label-wrapper">
                        <label for="slide_video" style="display: block;">
                            <b><?php _e('Video background', 'fwp'); ?></b>
                        </label>
                        <em><?php _e('Enter here link to video from Media Library or YouTube', 'fwp'); ?></em>
                    </p>
                    <input
                        type="text"
                        id="slide_video"
                        name="slide_video_bg"
                        value="<?php echo get_post_meta($post->ID, 'slide_video_bg', true); ?>"
                        style="width: 100%;"
                    />
                </div>
                <div class="field-wrap" style="width: 30%">
                    <p class="label-wrapper">
                        <label for="video_aspect_ratio" style="display: block;">
                            <b><?php _e('Video aspect ratio', 'fwp'); ?></b>
                        </label>
                    </p>
                    <?php $aspect_ratio = get_post_meta($post->ID, 'video_aspect_ratio', true) ?: '16:9'; ?>
                    <?php $ratio_list = ['16:9', '4:3', '2.39:1']; ?>
                    <select name="video_aspect_ratio" id="video_aspect_ratio" style="width: 100%;">
                        <?php foreach ($ratio_list as $item) { ?>
                            <option
                                value="<?php echo $item; ?>" <?php selected($aspect_ratio, $item); ?>><?php echo $item; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="clearfix" style="clear:both"></div>
            </div>
            <?php
        },
        $screens
    );
});

// Update slide background on slide save
add_action('save_post', function ($post_id) {
    if (!isset($_POST['slide_video_bg']) && !isset($_POST['video_aspect_ratio'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['project_nonce'], 'save_video_bg')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    update_post_meta($post_id, 'video_aspect_ratio', $_POST['video_aspect_ratio']);
    update_post_meta($post_id, 'slide_video_bg', $_POST['slide_video_bg']);
});

/**
 * Print script to hande appearance of metabox.
 */
$wl_display_metaboxes = function () {
    if ('slider' == get_post_type()) {
        ?>
        <script type="text/javascript">// <![CDATA[
            $ = jQuery;

            function displayMetaboxes() {
                $('#slide_background').hide();
                var selectedFormat = $('input[name=\'post_format\']:checked').val();
                if (selectedFormat == 'video') {
                    $('#slide_background').show();
                }
            }

            $(function() {
                displayMetaboxes();
                $('input[name=\'post_format\']').change(function() {
                    displayMetaboxes();
                });
            });
            // ]]></script>
        <?php
    }
};

// add_action('admin_enqueue_scripts', $wl_display_metaboxes);
add_action('admin_footer', $wl_display_metaboxes);

// HOME Slider Shortcode
add_shortcode('slider', function () {
    ob_start();
    ?>
    <script type="text/javascript">
        // Send command to iframe youtube player
        function postMessageToPlayer(player, command) {
            if (player == null || command == null) return;
            player.contentWindow.postMessage(JSON.stringify(command), '*');
        }

        jQuery(document).on('ready', function() {
            var $homeSlider = jQuery('#home-slider');
            $homeSlider
                .on('init', function(event, slick) {
                    slick.$slides.not(':eq(0)').find('.video--local').each(function() {
                        this.pause();
                    });

                    if (slick.$slides.eq(0).find('.video--local').length) {
                        slick.$slides.eq(0).find('.video--local')[0].play();
                    }
                    if (slick.$slides.eq(0).find('.video--embed').length) {
                        var playerId = slick.$slides.eq(0).find('iframe').attr('id');
                        var player = jQuery('#' + playerId).get(0);
                        postMessageToPlayer(player, {
                            'event': 'command',
                            'func': 'playVideo',
                        });
                    }
                })
                .on('beforeChange', function(event, slick, currentSlide, nextSlide) {
                    // Pause youtube video on slide change
                    if (slick.$slides.eq(currentSlide).find('.video--embed').length) {
                        var playerId = slick.$slides.eq(currentSlide).find('iframe').attr('id');
                        var player = jQuery('#' + playerId).get(0);
                        postMessageToPlayer(player, {
                            'event': 'command',
                            'func': 'pauseVideo',
                        });
                    }
                    // Pause local video on slide change
                    if (slick.$slides.eq(currentSlide).find('.video--local').length) {
                        slick.$slides.eq(currentSlide).find('.video--local')[0].pause();
                    }

                })
                .on('afterChange', function(event, slick, currentSlide) {
                    // Start playing local video on current slide
                    if (slick.$slides.eq(currentSlide).find('.video--local').length) {
                        slick.$slides.eq(currentSlide).find('.video--local')[0].play();
                    }

                    // Start playing youtube video on current slide
                    if (slick.$slides.eq(currentSlide).find('.video--embed').length) {
                        var playerId = slick.$slides.eq(currentSlide).find('iframe').attr('id');
                        var player = jQuery('#' + playerId).get(0);
                        postMessageToPlayer(player, {
                            'event': 'command',
                            'func': 'playVideo',
                        });
                    }
                });

            if (typeof jQuery('body').slick === 'function') {
                $homeSlider.slick({
                    cssEase: 'ease',
                    fade: true,  // Cause trouble if used slidesToShow: more than one
                    // arrows: false,
                    dots: true,
                    infinite: true,
                    speed: 500,
                    autoplay: true,
                    pauseOnHover: true,
                    autoplaySpeed: 5000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    rows: 0, // Prevent generating extra markup
                    slide: '.slick-slide', // Cause trouble with responsive settings
                });
            }
        });
    </script>

    <?php
    $slider = new WP_Query([
        'post_type' => 'slider',
        'order' => 'ASC',
        'orderby' => 'menu_order',
        'posts_per_page' => -1,
    ]);
    if ($slider->have_posts()) { ?>
        <div id="home-slider" class="slick-slider home-slider">
            <?php while ($slider->have_posts()) {
                $slider->the_post(); ?>
                <div class="slick-slide home-slide">
                    <div class="home-slide__inner" <?php bg(get_attached_img_url(get_the_ID(), 'full_hd')); ?>>
                        <?php $bg_video_url = get_post_meta(get_the_ID(), 'slide_video_bg', true); ?>
                        <?php if ('video' == get_post_format() && $bg_video_url) { ?>
                            <div class="videoHolder show-for-large"
                                 data-ratio="<?php echo get_post_meta(get_the_ID(), 'video_aspect_ratio', true) ?: '16:9'; ?>">
                                <?php
                                $allowed_video_format = [
                                    'webm' => 'video/webm',
                                    'mp4' => 'video/mp4',
                                    'ogv' => 'video/ogg',
                                    'mkv' => 'video/mkv',
                                ]; ?>
                                <?php $file_info = wp_check_filetype($bg_video_url, $allowed_video_format); ?>
                                <?php if ($file_info['ext']) { ?>
                                    <video src="<?php echo $bg_video_url; ?>"
                                           autoplay
                                           preload="none"
                                           muted="muted"
                                           loop="loop"
                                           class="video video--local"></video>
                                <?php } elseif (is_embed_video($bg_video_url)) { ?>
                                    <div class="video video--embed responsive-embed widescreen">
                                        <?php echo wp_oembed_get($bg_video_url, ['location' => 'home_slider', 'id' => 'slide-' . get_the_ID()]); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="grid-container home-slide__caption">
                            <div class="grid-x grid-margin-x">
                                <div class="cell">
                                    <h3><?php the_title(); ?></h3>
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <?php } ?>
        </div><!-- END of  #home-slider-->
    <?php }
    wp_reset_query();

    return ob_get_clean();
});
