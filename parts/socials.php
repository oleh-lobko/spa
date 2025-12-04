<?php if (!empty(get_field('socials', 'options'))) { ?>
    <div class="stay-tuned">
        <?php while (have_rows('socials', 'options')) {
            the_row();
            $social_network = get_sub_field('social_network');
            $social_icon = asset_path('images/socials/' . $social_network['value'] . '.svg');
            $social_link = get_sub_field('social_profile');
            if ($social_link) { ?>
                <a class="stay-tuned__item"
                   href="<?php echo esc_url($social_link); ?>"
                   target="_blank"
                   aria-label="<?php echo $social_network['label']; ?>"
                   rel="noopener"
                >
                    <?php echo file_get_contents($social_icon) ?: $social_network['label']; ?>
                </a>
            <?php }
        } ?>
    </div>
<?php } ?>
