<?php
/**
 * Archive.
 */

$featured_image = get_field('featured_image');
$start_date = get_field('start_date');
$end_date = get_field('end_date');

get_template_part('parts/event-date-block', null, [
    'start_date' => $start_date,
    'end_date' => $end_date
]);

get_header(); ?>

    <main class="main-content events-page">
        <div class="grid-container">
            <div class="grid-x grid-margin-x">
                <div class="cell">
                    <!-- Page Title - Centered -->
                    <div class="events-page-header">
                        <h1 class="events-page-title"><?php echo __('Upcoming events', 'fwp'); ?></h1>
                    </div>

                    <!-- Single Featured Events Stripe - Outside of posts loop -->
                    <div class="cell large-10 large-push-2">
                    <div class="featured-events-stripe">

                        <div class="stripe-content">
                            <?php echo implode(' - ', array_fill(0, 4, __('FEATURED EVENTS', 'fwp'))); ?>
                        </div>
                    </div>
                    </div>

                    <!-- Events List -->
                    <div class="events-list">
                        <div class="cell large-10 large-push-2">
                        <?php
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                $event_date = get_field('event_date');
                                $event_location = get_field('event_location');
                                $event_subtitle = get_field('event_subtitle');
                                $event_description = get_field('event_description_wysiwyg');
                                $event_link = get_field('event_link');
                                $event_logo = get_field('event_logo');
                                $featured_image = get_field('featured_image');
                                $start_date = get_field('start_date');
                                $end_date = get_field('end_date');

                                // Extract start date components for blue date block
                                if ($start_date) {
                                    $start_timestamp = strtotime($start_date);
                                    $event_month = strtoupper(date('M', $start_timestamp));
                                    $event_day = date('j', $start_timestamp);
                                    $event_year = date('Y', $start_timestamp);
                                } else {
                                    $event_month = '';
                                    $event_day = '';
                                    $event_year = '';
                                }
                                ?>

                                <div class="event-item">
                                    <div class="event-item-wrapper">
                                        <!-- Event Date Block - Left side, outside content -->
                                        <div class="event-date-block">
                                            <?php if ($event_month && $event_day) { ?>
                                                <div class="event-month"><?php echo esc_html($event_month); ?></div>
                                                <div class="event-day"><?php echo esc_html($event_day); ?></div>
                                            <?php } ?>
                                        </div>

                                        <!-- Event Content -->
                                        <div class="event-content">
                                            <h2 class="event-title"><?php the_title(); ?></h2>

                                            <!-- Event Featured Image -->
                                            <?php if ($featured_image) { ?>
                                                <div class="event-image">
                                                    <img src="<?php echo esc_url($featured_image['url']); ?>" alt="<?php echo esc_attr($featured_image['alt']); ?>">
                                                </div>
                                            <?php } elseif (has_post_thumbnail()) { ?>
                                                <div class="event-image">
                                                    <?php the_post_thumbnail('large'); ?>
                                                </div>
                                            <?php } ?>
                                            <div class="event-description">
                                            <?php
                                            $meta_key = 'event_description_wg';
                                            $full_content = get_field('event_description_wg');
                                            if ( ! empty( $full_content ) ) :
                                                $content_parts = get_extended( $full_content );
                                                $excerpt = $content_parts['main'];
                                                echo apply_filters( 'the_content', $excerpt );

                                            else :
                                            endif;
                                            ?>
                                            </div>

                                            <!-- Read More Button -->
                                            <div class="event-read-more-section">
                                                <a href="<?php echo get_permalink(); ?>" class="event-read-more-btn">
                                                    <?php echo __('To the event page', 'fwp'); ?>
                                                </a>
                                            </div>

                                            <!-- Event Details Table - 3 columns -->
                                            <div class="event-details">
                                                <div class="event-details-row">
                                                    <div class="event-detail-item">
                                                        <div class="detail-label"><?php echo __('When', 'fwp'); ?></div>
                                                        <?php
                                                        $start = get_field('event_start');
                                                        $end = get_field('event_end');

                                                        if ($start) {
                                                            $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $start);
                                                        }

                                                        if ($end) {
                                                            $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $end);
                                                        }
                                                        ?>
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
                                                    <div class="event-detail-item">
                                                        <div class="detail-label"><?php echo __('Where', 'fwp'); ?></div>
                                                        <div class="detail-value">
                                                            <?php get_template_part('parts/event-location-display'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="event-detail-item">
                                                        <div class="detail-label"><?php echo __('Attedence', 'fwp'); ?></div>
                                                        <div class="detail-value">
                                                            <?php
                                                            get_template_part('parts/event-attendance-display');
                                                            $event_registration_link = get_field('event_registration_link') ?: get_field('soldout_button_link');
                                                            ?>
                                                            <?php
                                                            if ($event_registration_link) {
                                                                $button_title = $event_registration_link['title'] ?: 'BOOK TICKETS';
                                                                $button_class = ('sold out' === strtolower(trim($button_title))) ? 'btn-sold-out' : 'btn-available';
                                                                ?>
                                                                <a href="<?php echo esc_url($event_registration_link['url']); ?>"
                                                                   class="book-tickets-btn <?php echo esc_attr($button_class); ?>"
                                                                   <?php if ($event_registration_link['target']) { ?>target="<?php echo esc_attr($event_registration_link['target']); ?>"<?php } ?>>
                                                                    <?php echo esc_html($button_title); ?>
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="no-events">
                                <p>No events found.</p>
                            </div>
                        <?php } ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <?php foundation_pagination(); ?>
                </div>
            </div>
        </div>
    </main>

<?php get_footer(); ?>
