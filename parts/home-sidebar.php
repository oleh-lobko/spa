<!-- Sidebar CTA -->
<?php
$sidebar_cta = get_field('sidebar_cta');
if ($sidebar_cta) { ?>
    <div class="cell large-4 medium-12 small-12">
    <section class='cta-block'>
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
    </section>
    </div>
<?php } ?>
