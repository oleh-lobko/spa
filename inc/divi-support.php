<?php

// Check if theme set as a child of Divi theme
if (is_admin()) {
    $style_css_content = file_get_contents(get_stylesheet_directory() . '/style.css');
    if (!preg_match('/(?:.*)Template:(?: *)Divi\n(?:.*)/im', $style_css_content)) {
        add_action('admin_notices', function () {
            echo <<<HTML
    <div class='notice notice-error is-dismissible'>
      <p><b>Custom Divi blocks registration error:</b> <br/>
      Divi support was enabled in a non-child theme or the parent name is wrong.<br/>
      Please make sure you have this line in theme's style.css:<br/><br/>
      <code>
          \nTemplate: Divi\n
      </code><br/><br/>
    </p>
    </div>
HTML;
        });
    }
}

// Register custom divi blocks
add_action('et_builder_ready', function () {
    foreach (glob(get_stylesheet_directory() . '/assets/blocks/divi/**/index.php') as $filename) {
        require_once $filename;
    }
});

// Enqueue parent theme styles
add_action('wp_enqueue_scripts', function () {
    if (!is_admin()) {
        wp_enqueue_style('divi-main', get_template_directory_uri() . '/style.css');
    }
});
