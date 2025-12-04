<?php

// define custom blocks root path
$blocks_dir = get_theme_file_path() . '/assets/blocks/gutenberg';

add_action('init', function () use ($blocks_dir) {
    $blocks = glob(get_theme_file_path() . '/assets/blocks/gutenberg/**/index.php');

    foreach ($blocks as $filename) {
        (function () use ($filename, $blocks_dir) {
            require $filename;

            /**
             * @global $controller
             */
            $dir_name = str_replace($blocks_dir, '', dirname($filename));
            $isAcfBlock = 0 === strpos($dir_name, '/acf-');
            $template = dirname($filename) . '/template.php';
            $hasTemplate = file_exists($template);
            $hasController = isset($controller);

            if (!isset($name)) {
                user_error(
                    "error registering example block: {$filename} does not declare the block name in \$name",
                    E_USER_ERROR
                );

                return;
            }

            if (!$hasTemplate) {
                user_error(
                    "error registering example block: {$filename} doesn't have a template.php file next to it!",
                    E_USER_ERROR
                );
            }

            if ($isAcfBlock && $hasController) {
                \acf_register_block_type(array_merge(
                    [
                        'name' => $name,
                        'render_callback' => function ($block, $content, $is_preview, $post_id) use ($template, $controller) {
                            $atts = $controller([
                                'content' => $content,
                                'is_preview' => $is_preview,
                                'post_id' => $post_id,
                                'fields' => get_fields(),
                            ]);
                            render_block_tempalte($template, $atts);
                        },
                    ],
                    isset($settings) ? $settings : []
                ));
            } elseif ($isAcfBlock && !$hasController) {
                \acf_register_block_type(array_merge(
                    [
                        'name' => $name,
                        'render_callback' => function ($block, $content, $is_preview, $post_id) use ($template) {
                            $atts = [
                                'content' => $content,
                                'is_preview' => $is_preview,
                                'post_id' => $post_id,
                                'fields' => get_fields(),
                            ];

                            render_block_tempalte($template, $atts);
                        },
                    ],
                    isset($settings) ? $settings : []
                ));
            } else {
                register_block_type($name);
            }
        })();
    }
});

// Enqueue Styles & Scripts
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style(
        'blocks.css',
        \asset_path('styles/blocks.css')
    );
});
