<?php
/**
 * Single SPA Event
 */
$featured_image = get_field('featured_image');
$start_date = get_field('start_date');
$end_date = get_field('end_date');

// Extract start date components for blue date block
if ($start_date) {
    $start_timestamp = strtotime($start_date);
    $event_month = strtoupper(date('F', $start_timestamp));
    $event_day = date('j', $start_timestamp);
    $event_year = date('Y', $start_timestamp);
} else {
    $event_month = '';
    $event_day = '';
    $event_year = '';
}
get_header(); ?>

<main class="main-content single-event-page">
    <div class="titlte">  <!-- Event Title -->
        <h1 class="event-main-title"><?php the_title(); ?></h1>
    </div>
    <?php if (have_posts()) { ?>
        <?php while (have_posts()) {
            the_post(); ?>
 <div class="grid-container">
    <div class="grid-x grid-margin-x">
        <div class="cell medium-2 small-6 small-order-1 medium-order-1">
            <div class="event-date-right">
                <?php if ($event_month) { ?>
                    <div class="date-month"><?php echo esc_html($event_month); ?></div>
                <?php } ?>
                <?php if ($event_day) { ?>
                    <div class="date-day"><?php echo esc_html($event_day); ?></div>
                <?php } ?>
                <?php if ($event_year) { ?>
                    <div class="date-year"><?php echo esc_html($event_year); ?></div>
                <?php } ?>
            </div>
        </div>
        <div class="cell medium-8 small-order-3 medium-order-2">
            <!-- Main Content Area -->
            <div class="event-main-content">
                <!-- Event Featured Image with Date and Social -->


                <!-- Image and Date Container -->
                <div class="event-image-date-container">
                    <?php if ($featured_image) { ?>
                        <div class="event-main-image">
                            <img src="<?php echo esc_url($featured_image['url']); ?>"
                                 alt="<?php echo esc_attr($featured_image['alt'] ?: get_the_title()); ?>">
                        </div>
                    <?php } elseif (has_post_thumbnail()) { ?>
                        <div class="event-main-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php } ?>




                    <?php
                    $event_description_wg = get_field('event_description_wg');
                    if ($event_description_wg) { ?>
                        <div class="cell large-8">
                            <div class="hero-section">
                                <div class="hero-content">
                                    <?php echo $event_description_wg; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Event Map -->
                    <?php if ($event_map = get_field('event_map')) { ?>
                        <div class="event-map-container">
                            <div class="event-map-display">
                                <div class="marker" data-lat="<?php echo $event_map['lat']; ?>" data-lng="<?php echo $event_map['lng']; ?>"
                                     data-marker-icon="<?php echo get_template_directory_uri(); ?>/assets/images/event.png">
                                    <div class="map-marker">
                                        <div class="marker-label"><?php echo __('Event', 'fwp'); ?></div>
                                        <div class="marker-address"><?php echo esc_html($event_map['address']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Event Details Grid -->
                    <div class="event-details-grid">
                        <!-- WHEN -->
                        <div class="detail-column">
                            <h3 class="detail-heading"><?php echo __('When', 'fwp'); ?></h3>
                            <div class="detail-info">
                                <div class="detail-value">
                                    <?php
                                    $start_date_field = get_field('start_date');
                                    $end_date_field = get_field('end_date');

                                    if ($start_date_field) {
                                        $start_timestamp = strtotime($start_date_field);

                                        if ($end_date_field) {
                                            $end_timestamp = strtotime($end_date_field);

                                            // Check if same date (year, month, day)
                                            if (date('Y-m-d', $start_timestamp) === date('Y-m-d', $end_timestamp)) {
                                                // Same day - show: January 9, 2015<br>16:30 - 21:00
                                                echo date('F j, Y', $start_timestamp) . '<br>' . date('G:i', $start_timestamp) . ' - ' . date('G:i', $end_timestamp);
                                            } elseif (date('Y-m', $start_timestamp) === date('Y-m', $end_timestamp)) {
                                                // Same month and year - show: January 9 - 15, 2015<br>16:30 - 21:00
                                                echo date('F j', $start_timestamp) . ' - ' . date('j, Y', $end_timestamp) . '<br>' . date('G:i', $start_timestamp) . ' - ' . date('G:i', $end_timestamp);
                                            } elseif (date('Y', $start_timestamp) === date('Y', $end_timestamp)) {
                                                // Same year - show: January 9 - February 15, 2015<br>16:30 - 21:00
                                                echo date('F j', $start_timestamp) . ' - ' . date('F j, Y', $end_timestamp) . '<br>' . date('G:i', $start_timestamp) . ' - ' . date('G:i', $end_timestamp);
                                            } else {
                                                // Different years - show full dates
                                                echo date('F j, Y', $start_timestamp) . ' - ' . date('F j, Y', $end_timestamp) . '<br>' . date('G:i', $start_timestamp) . ' - ' . date('G:i', $end_timestamp);
                                            }
                                        } else {
                                            // Only start date
                                            echo date('F j, Y', $start_timestamp) . '<br>' . date('G:i', $start_timestamp);
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- WHERE -->
                        <div class="detail-column">
                            <h3 class="detail-heading">WHERE</h3>
                            <div class="detail-info">
                                <div class="detail-value">
                                    <?php get_template_part('parts/event-location-display'); ?>
                                </div>
                            </div>
                        </div>

                        <!-- ATTENDANCE -->
                        <div class="detail-column">
                            <h3 class="detail-heading">ATTENDANCE</h3>
                            <div class="detail-info">
                                <?php
                                get_template_part('parts/event-attendance-display');
                                ?>
                                <?php if ($attendance_text) { ?>
                                    <div class="attendance-text"><?php echo nl2br(esc_html($attendance_text)); ?></div>
                                <?php }  { ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sign Up Button -->
                    <?php $signup_button = get_field('signup_button_link'); ?>
                    <?php if ($signup_button) { ?>
                        <div class="signup-button-container">
                            <a href="<?php echo esc_url($signup_button['url']); ?>"
                               class="signup-button"
                               <?php if ($signup_button['target']) { ?>target="<?php echo esc_attr($signup_button['target']); ?>"<?php } ?>>
                                <?php echo esc_html($signup_button['title'] ?: 'SIGN UP'); ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="cell medium-2 small-6 small-order-2 medium-order-3">
            <!-- Social Icons - Right Side of Image -->
            <div class="event-social-right-horizontal">
                <?php if (get_field('social_facebook')) { ?>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" class="social-circle social-facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                <?php } ?>

                <?php if (get_field('social_twitter')) { ?>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                       target="_blank" class="social-circle social-twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                <?php } ?>

                <?php if (get_field('social_linkedin')) { ?>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" class="social-circle social-linkedin">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                <?php } ?>
            </div>
            </div>
        </div>
    </div>
<div class="grid-container">
    <div class="grid-x grid-margin-x">
        <div class="cell large-12">
            <!-- Supporters Title Section - After Sign Up Button -->
            <?php
            $supporter_logos = get_field('supporter_logos_repeater');
            $supporters_title = get_field('supporters_title');
            if ($supporter_logos) { ?>
                <div class="supporters-title-section">
                    <h3 class="supporters-heading-main">
                        <?php echo esc_html($supporters_title ?: 'THE EVENT IS SUPPORTED BY'); ?>
                    </h3>
                </div>
            <?php } ?>
        <!-- Event Supporters - Full Width Outside Content -->
        <?php if ($supporter_logos) { ?>
            <div class="event-supporters-full-width">
                <div class="supporters-container">
                    <div class="supporters-logos">
                        <?php foreach ($supporter_logos as $supporter) { ?>
                            <div class="supporter-logo">
                                <?php if ($supporter['url']) { ?>
                                <a href="<?php echo esc_url($supporter['url']); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    <?php } ?>

                                    <img src="<?php echo esc_url($supporter['logo']['url']); ?>"
                                         alt="<?php echo esc_attr($supporter['name']); ?>">

                                    <?php if ($supporter['url']) { ?>
                                </a>
                            <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>

</div>
    </div>
</div>
        <?php } ?>
    <?php } else { ?>
        <div class="no-event-found">
            <h2>Event not found</h2>
            <p>Sorry, the requested event does not exist.</p>
            <a href="<?php echo home_url(); ?>" class="btn-back-home">Return to home page</a>
        </div>
    <?php } ?>
</main>

<?php get_footer(); ?>
