<?php

namespace diviBlock;

if (!class_exists('\ET_Builder_Module')) {
    return;
}

class ExampleBlock extends \ET_Builder_Module
{
    public function init()
    {
        $this->name = esc_html__('Example Block', 'fwp');
        $this->slug = 'fwp_example_block';
        $this->vb_support = 'on';
        $this->settings_modal_toggles = [
            'general' => [
                'toggles' => [
                    'elements' => __('Elements', 'fwp'),
                ],
            ],
        ];
    }

    public function get_fields()
    {
        return [
            'title' => [
                'label' => esc_html__('Title', 'fwp'),
                'type' => 'text',
                'option_category' => 'basic_option',
                'toggle_slug' => 'text',
            ],
        ];
    }

    /**
     * @param array $unprocessed_props List of unprocessed attributes
     * @param string $content Content being processed
     * @param string $render_slug Slug of module that is used for rendering output
     *
     * @return string The module's HTML output
     */
    public function render($unprocessed_props, $content, $render_slug)
    {
        $args = [
            'content' => $this->content,
            'title' => $this->props['title'],
        ];

        ob_start();
        get_template_part(
            str_replace(get_stylesheet_directory() . DIRECTORY_SEPARATOR, '', __DIR__) . '/template',
            null,
            $args
        );

        return ob_get_clean();
    }
}
