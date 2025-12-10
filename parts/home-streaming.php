<div class="grid-container">
    <div class="grid-x grid-margin-x align-stretch">
        <!-- Hero Section (WYSIWYG) -->
        <?php
        $hero_section = get_field('hero_section');
        if ($hero_section) { ?>
            <div class="cell large-8 medium-12 small-12">
                <div class="hero-section">
                    <div class="hero-content">
                        <?php echo $hero_section; ?>
                    </div>
                </div>
            </div>

            <?php
            get_template_part('parts/home', 'sidebar');
            ?>
        <?php } ?>
    </div>
</div>
