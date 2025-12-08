<?php
/**
 * Template Name: Home Page.
 */
get_header(); ?>

<!--HOME PAGE SLIDER-->
<?php if (shortcode_exists('slider')) {
    echo do_shortcode('[slider]');
} ?>
<!--END of HOME PAGE SLIDER-->

<div class="spa-layout">
    <div class="grid-container">
        <div class="grid-x grid-margin-x align-stretch">
            <!-- Hero Section (WYSIWYG) -->
            <?php
            $hero_section = get_field('hero_section');
            if ($hero_section) { ?>
                <div class="cell large-8">
                    <div class="hero-section">
                        <div class="hero-content">
                            <?php echo $hero_section; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar CTA -->
                <?php
                $sidebar_cta = get_field('sidebar_cta');
                if ($sidebar_cta) { ?>
                    <section class='cta-block'>
                        <div class="cell large-4 medium-8">
                            <div class="sidebar-cta">
                                <?php if ($sidebar_cta['title']) { ?>
                                    <h3 class="cta-title"><?php echo esc_html($sidebar_cta['title']); ?></h3>
                                <?php } ?>

                                <?php if ($sidebar_cta['description']) { ?>
                                    <div class="cta-description">
                                        <?php echo nl2br(esc_html($sidebar_cta['description'])); ?>
                                    </div>
                                <?php } ?>

                                <?php if ($sidebar_cta['button']) { ?>
                                    <div class="cta-button">
                                        <a href="<?php echo esc_url($sidebar_cta['button']['url']); ?>"
                                           class="btn-cta"
                                           <?php if ($sidebar_cta['button']['target']) { ?>target="<?php echo esc_attr($sidebar_cta['button']['target']); ?>"<?php } ?>>
                                            <?php echo esc_html($sidebar_cta['button']['title']); ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </section>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <!-- SPA Events Section -->
    <?php
    // Get events section settings from ACF, with fallbacks
    $events_section_title = get_field('events_section_title') ?: 'Upcoming Events';
    $events_section_link = get_field('events_section_link') ?: ['url' => get_post_type_archive_link('spa_events'), 'title' => 'SEE ALL'];
    $events_background_image = get_field('events_background_image');
    $post_per_page = get_field('events_post_per_page');

    // Get current date for comparison
    $current_date = date('Y-m-d');

    // Query for all events to get specific ones by position
    $all_events_query = new WP_Query([
        'post_type' => 'spa_events',
        'posts_per_page' =>  $post_per_page,
        'post_status' => 'publish',
        'meta_key' => 'start_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'start_date',
                'value' => $current_date,
                'compare' => '>=',
                'type' => 'DATE',
            )
        )
    ]);
    $all_events = $all_events_query->posts;

    // Get specific events: 1st as featured, 3rd and 4th as regular
    $featured_event = isset($all_events[0]) ? $all_events[0] : null; // First event
    $regular_events = [];

    // Add 3rd event (index 2)
    if (isset($all_events[2])) {
        $regular_events[] = $all_events[2];
    }

    // Add 4th event (index 3)
    if (isset($all_events[3])) {
        $regular_events[] = $all_events[3];
    }


    ?>
    <section class="spa-events-section"
        <?php if ($events_background_image) { ?>
            style="background-image: url('<?php echo esc_url($events_background_image['url']); ?>');"
        <?php } ?>>
        <div class="spa-events-overlay">


            <div class="grid-container">
                <div class="grid-x grid-margin-x">
                    <!-- Decorative stripe for events -->

                    <!-- Section Header -->
                    <div class="cell">
                        <div class="spa-events-header">
                            <?php if ($events_section_title) { ?>
                                <h2 class="spa-events-title"><?php echo esc_html($events_section_title); ?></h2>
                            <?php } ?>

                            <?php if ($events_section_link) { ?>
                                <a href="<?php echo esc_url($events_section_link['url']); ?>"
                                   class="spa-events-see-all"
                                   <?php if ($events_section_link['target']) { ?>target="<?php echo esc_attr($events_section_link['target']); ?>"<?php } ?>>
                                    <?php echo esc_html($events_section_link['title'] ?: 'SEE ALL'); ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Unified Events Container -->
    <div class="spa-events-white-container">
        <!-- Static stripe for Featured Event -->
        <div class="featured-event-stripe">
            <div class="stripe-text">
                <?php
                $stripe_text = get_field('featured_events_stripe_text', 'options');
                echo esc_html($stripe_text ?: 'FEATURED EVENTS - FEATURED EVENTS - FEATURED EVENTS - FEATURED EVENTS');
                ?>
            </div>
        </div>

        <!-- All Events in One Section -->
        <div class="unified-events-section">
            <?php
            // Get posts per page setting from Theme Options
            $current_date = current_time('Y-m-d');

            // Query upcoming events sorted by start date
            $events_query = new WP_Query([
                'post_type' => 'spa_events',
                'posts_per_page' => $post_per_page,
                'post_status' => 'publish',
                'meta_key' => 'start_date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'start_date',
                        'value' => $current_date,
                        'compare' => '>=',
                        'type' => 'DATE',
                    )
                )
            ]);

            $events = $events_query->posts;

            ?>
            <!-- Featured Event -->
            <?php if ($featured_event) { ?>
                <div class="featured-event">
                    <div class="event-date-block">
                        <?php
                        $start_date = get_field('start_date', $featured_event->ID);
                        if ($start_date) { ?>
                            <?php
                            $timestamp = strtotime($start_date);
                            if ($timestamp) {
                                $month = date('M', $timestamp);
                                $day = date('j', $timestamp);
                                ?>
                                <div class="event-month"><?php echo esc_html($month); ?></div>
                                <div class="event-day"><?php echo esc_html($day); ?></div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="event-content">
                        <?php
                        $event_location = get_field('event_location', $featured_event->ID);
                        $event_logo = get_field('event_logo', $featured_event->ID);
                        $home_description = get_field('home_description', $featured_event->ID);
                        $home_link = get_field('home_link', $featured_event->ID);
                        ?>

                        <?php if ($event_location) { ?>
                            <div class="event-location"><?php echo esc_html($event_location); ?></div>
                        <?php } else { ?>
                            <div class="event-location">Helsinki</div>
                        <?php } ?>

                        <h3 class="event-title"><?php echo esc_html($featured_event->post_title); ?></h3>

                        <?php if ($home_description) { ?>
                            <div class="event-description">
                                <?php echo wp_kses_post($home_description); ?>
                            </div>
                        <?php } else { ?>
                            <div class="event-description">
                            </div>
                        <?php } ?>

                        <?php if ($home_link) { ?>
                            <a href="<?php echo esc_url($home_link['url']); ?>"
                               class="event-read-more"
                               <?php if ($home_link['target']) { ?>target="<?php echo esc_attr($home_link['target']); ?>"<?php } ?>>
                                <?php echo esc_html($home_link['title'] ?: 'READ MORE'); ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo get_permalink($featured_event->ID); ?>" class="event-read-more">
                                READ MORE
                            </a>
                        <?php } ?>
                    </div>
                    <?php if ($event_logo) { ?>
                        <div class="event-logo">
                            <img src="<?php echo esc_url($event_logo['url']); ?>"
                                 alt="<?php echo esc_attr($event_logo['alt']); ?>">
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- Regular Events -->
            <?php if (!empty($regular_events)) { ?>
                <div class="regular-events">
                    <?php foreach ($regular_events as $event) { ?>
                        <div class="regular-event">
                            <div class="event-date-block">
                                <?php
                                $start_date = get_field('start_date', $event->ID);
                                if ($start_date) { ?>
                                    <?php
                                    $timestamp = strtotime($start_date);
                                    if ($timestamp) {
                                        $month = date('M', $timestamp);
                                        $day = date('j', $timestamp);
                                        ?>
                                        <div class="event-month"><?php echo esc_html($month); ?></div>
                                        <div class="event-day"><?php echo esc_html($day); ?></div>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                            <div class="event-content">
                                <?php
                                $event_location = get_field('event_location', $event->ID);
                                $event_subtitle = get_field('event_subtitle', $event->ID);
                                $home_decripthion = get_field('home_decripthion', $event->ID);
                                $home_link = get_field('home_link', $event->ID);
                                ?>

                                <?php if ($event_location) { ?>
                                    <div class="event-location"><?php echo esc_html($event_location); ?></div>
                                <?php } else { ?>
                                    <div class="event-location">Tampere</div>
                                <?php } ?>

                                <h4 class="event-title"><?php echo esc_html($event->post_title); ?></h4>

                                <?php if ($event_subtitle) { ?>
                                    <div class="event-subtitle"><?php echo esc_html($event_subtitle); ?></div>
                                <?php } ?>

                                <?php if ($home_description) { ?>
                                    <div class="event-description">
                                        <?php echo wp_kses_post($home_description); ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="event-description">
                                        <?php echo wp_kses_post($event->post_excerpt ?: wp_trim_words($event->post_content, 20)); ?>
                                    </div>
                                <?php } ?>

                                <?php if ($home_link) { ?>
                                    <a href="<?php echo esc_url($home_link['url']); ?>"
                                       class="event-read-more"
                                       <?php if ($home_link['target']) { ?>target="<?php echo esc_attr($home_link['target']); ?>"<?php } ?>>
                                        <?php echo esc_html($home_link['title'] ?: 'READ MORE'); ?>
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo get_permalink($event->ID); ?>" class="event-read-more">
                                        READ MORE
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php
    // Reset post data after custom queries
    wp_reset_postdata();
    ?>
</div>
<!-- END of main content -->

<?php get_footer(); ?>
