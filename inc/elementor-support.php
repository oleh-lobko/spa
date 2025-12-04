<?php

// Check if Elementor plugin is installed and activated
add_action('init', function () {
    if (is_admin() && !is_plugin_active('elementor/elementor.php')) {
        add_action('admin_notices', function () {
            echo <<<'HTML'
                <div class='notice notice-error is-dismissible'>
                  <p><b>Custom Elementor widgets registration error:</b> <br/>
                  Elementor support was enabled without plugin installation<br/>
                  Please install and activate Elementor plugin</p>
                </div>
            HTML;
        });
    }
});

// Register custom elementor widgets
add_action('elementor/widgets/widgets_registered', function () {
    foreach (glob(get_stylesheet_directory() . '/assets/blocks/elementor/**/index.php') as $filename) {
        require_once $filename;
    }
});
