<?php
/**
 * Home.
 *
 * Standard loop for the blog-page
 */
get_header(); ?>

<main class="main-content">
    <div class="grid-container">
        <div class="grid-x grid-margin-x posts-list">
            <div class="cell">
                <h2 class="stories-title"><?php echo get_the_archive_title(); ?></h2>
            </div>
            <!-- BEGIN of Blog posts -->
            <div class="cell">
                <?php if (have_posts()) { ?>
                    <?php while (have_posts()) {
                        the_post(); ?>
                        <?php get_template_part('parts/loop', 'post'); // Post item?>
                    <?php } ?>
                <?php } ?>
                <!-- BEGIN of pagination -->
                <?php foundation_pagination(); ?>
                <!-- END of pagination -->
            </div>
            <!-- END of Blog posts -->
        </div>
    </div>
</main>

<?php get_footer(); ?>
