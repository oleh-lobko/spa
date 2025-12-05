<?php
/**
 * Footer.
 */

// Get footer settings from ACF
$footer_sections = get_field('footer_sections', 'options');

// Separate sections by type
$get_in_touch_section = null;
$connect_with_us_section = null;
$main_partners_section = null;

if ($footer_sections) {
    foreach ($footer_sections as $section) {
        switch ($section['section_type']) {
            case 'get_in_touch':
                $get_in_touch_section = $section;
                break;
            case 'connect_with_us':
                $connect_with_us_section = $section;
                break;
            case 'main_partners':
                $main_partners_section = $section;
                break;
        }
    }
}
?>

<div class="pre-footer-stripe">
</div>

<!-- BEGIN of footer -->
<footer class="footer spa-footer">

    <!-- Main Footer Content: Left Column (Get in Touch + Connect) and Right Column (Partners) -->
    <div class="footer-main-content">
        <!-- Left Column: Get in Touch + Connect with Us -->
        <div class="footer-left-column">
            <!-- Get in Touch -->
            <?php if ($get_in_touch_section): ?>
                <div class="footer-get-in-touch">
                    <?php if (!empty($get_in_touch_section['section_link'])): ?>
                        <a href="<?php echo esc_url($get_in_touch_section['section_link']['url']); ?>"
                           class="get-in-touch-link"
                           <?php if ($get_in_touch_section['section_link']['target']): ?>target="<?php echo esc_attr($get_in_touch_section['section_link']['target']); ?>"<?php endif; ?>>
                            <?php echo esc_html($get_in_touch_section['section_title'] ?: 'Get in touch'); ?>
                        </a>
                    <?php else: ?>
                        <span class="get-in-touch-text">
                            <?php echo esc_html($get_in_touch_section['section_title'] ?: 'Get in touch'); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Connect with Us -->
            <?php if ($connect_with_us_section): ?>
                <div class="footer-connect-with-us">
                    <div class="connect-title">
                        <?php echo esc_html($connect_with_us_section['section_title'] ?: 'Connect with us'); ?>
                    </div>
                    <?php if (!empty($connect_with_us_section['social_links'])): ?>
                        <div class="social-links">
                            <?php foreach ($connect_with_us_section['social_links'] as $social): ?>
                                <?php
                                // Determine social network type for styling
                                $social_class = '';
                                if (strpos($social['icon'], 'facebook') !== false) {
                                    $social_class = 'social-facebook';
                                } elseif (strpos($social['icon'], 'twitter') !== false) {
                                    $social_class = 'social-twitter';
                                } elseif (strpos($social['icon'], 'linkedin') !== false) {
                                    $social_class = 'social-linkedin';
                                }
                                ?>
                                <a href="<?php echo esc_url($social['url']); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="social-link <?php echo esc_attr($social_class); ?>"
                                   title="<?php echo esc_attr(ucfirst(str_replace(['fab fa-', '-'], ['', ' '], $social['icon']))); ?>">
                                    <i class="<?php echo esc_attr($social['icon']); ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Main Partners -->
        <?php if ($main_partners_section): ?>
            <div class="footer-right-column">
                <div class="footer-main-partners">
                    <div class="partners-title">
                        <?php echo esc_html($main_partners_section['section_title'] ?: 'Main partners'); ?>
                    </div>
                    <?php if (!empty($main_partners_section['partner_logos'])): ?>
                        <div class="partner-logos">
                            <?php foreach ($main_partners_section['partner_logos'] as $partner): ?>
                                <div class="partner-logo">
                                    <?php if (!empty($partner['url'])): ?>
                                    <a href="<?php echo esc_url($partner['url']); ?>"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       title="<?php echo esc_attr($partner['name']); ?>">
                                        <?php endif; ?>

                                        <img src="<?php echo esc_url($partner['logo']['url']); ?>"
                                             alt="<?php echo esc_attr($partner['name']); ?>"
                                             loading="lazy">

                                        <?php if (!empty($partner['url'])): ?>
                                    </a>
                                <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Copyright Section -->
    <?php if ($copyright = get_field('copyright', 'options')) { ?>
        <div class="footer__copy">
            <div class="grid-container">
                <div class="grid-x grid-margin-x">
                    <div class="cell">
                        <?php echo $copyright; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</footer>
<!-- END of footer -->

<?php wp_footer(); ?>
</body>
</html>
