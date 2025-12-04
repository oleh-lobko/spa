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

<!-- BEGIN of main content -->
<div class="grid-container">
    <div class="grid-x grid-margin-x">
        <div class="cell">
            <?php if (have_posts()) { ?>
                <?php while (have_posts()) {
                    the_post(); ?>
                    <?php the_content(); ?>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
<!-- END of main content -->

<?php get_footer(); ?>
