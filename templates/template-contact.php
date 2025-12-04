<?php
/**
 * Template Name: Contact Page.
 */
get_header(); ?>

<main class="main-content">
    <section class="contact">
        <?php if (have_posts()) { ?>
            <?php while (have_posts()) {
                the_post(); ?>
                <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="grid-container">
                        <div class="grid-x grid-margin-x">
                            <div class="cell medium-6">
                                <h1 class="page-title"><?php the_title(); ?></h1>
                                <div class="contact__content">
                                    <?php the_content(); ?>
                                </div>
                                <div class="contact__links">
                                    <?php if ($address = get_field('address', 'option')) { ?>
                                        <address class="contact-link contact-link--address">
                                            <?php echo $address; ?>
                                        </address>
                                    <?php } ?>
                                    <?php if ($email = get_field('email', 'options')) { ?>
                                        <p class="contact-link contact-link--email">
                                            <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                                        </p>
                                    <?php } ?>
                                    <?php if ($phone = get_field('phone', 'options')) { ?>
                                        <p class="contact-link contact-link--phone">
                                            <a href="tel:<?php echo sanitize_number($phone); ?>"><?php echo $phone; ?></a>
                                        </p>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php $contact_form = get_field('contact_form'); ?>
                            <?php if (class_exists('GFAPI') && !empty($contact_form) && is_array($contact_form)) { ?>
                                <div class="cell medium-6">
                                    <div class="contact__form">
                                        <?php echo do_shortcode("[gravityform id='{$contact_form['id']}' title='false' description='false' ajax='true']"); ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($location = get_field('location', 'options')) { ?>
                                <div class="cell contact__map-wrap">
                                    <div class="acf-map contact__map">
                                        <div class="marker"
                                             data-lat="<?php echo $location['lat']; ?>"
                                             data-lng="<?php echo $location['lng']; ?>"
                                             data-marker-icon="<?php echo asset_path('images/map_marker.png'); ?>"
                                        >
                                            <p><?php echo $location['address']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </article>
            <?php } ?>
        <?php } ?>
    </section>
</main>

<?php get_footer(); ?>
