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
</div>
<!-- END of main content -->

<?php get_footer(); ?>
