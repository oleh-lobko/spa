<?php
/**
 * Template Name: Home Page.
 */
get_header(); ?>

<!--HOME PAGE SLIDER-->
<?php
get_template_part('parts/home', 'slider');
?>
<!--END of HOME PAGE SLIDER-->

<div class="spa-layout">
 <?php
    get_template_part('parts/home', 'streaming');
    get_template_part('parts/home', 'events');
    get_template_part('parts/home', 'stories');
    get_template_part('parts/home', 'twitter');
    ?>


<?php get_footer(); ?>
