<?php

namespace elementorWidget;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class ExampleWidget extends Widget_Base
{
    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @return string widget name
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function get_name()
    {
        return 'example_widget';
    }

    /**
     * Get widget title.
     *
     * Retrieve widget title.
     *
     * @return string widget title
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function get_title()
    {
        return __('Example widget', 'fwp');
    }

    /**
     * Get widget icon.
     *
     * Retrieve oEmbed widget icon.
     *
     * @return string widget icon
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function get_icon()
    {
        // Icon library https://elementor.github.io/elementor-icons/
        return 'eicon-square';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the oEmbed widget belongs to.
     *
     * @return array widget categories
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function get_categories()
    {
        return ['general'];
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @see https://code.elementor.com/classes/elementor-controls_manager/
     * @see https://developers.elementor.com/elementor-controls/
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section('content', [
            'label' => __('Content', 'fwp'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('example_text', [
            'label' => esc_html__('Example text', 'fwp'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings();

        $args = [
            'example_text' => $settings['example_text'],
        ];

        show_template('template', $args, str_replace(get_stylesheet_directory() . DIRECTORY_SEPARATOR, '', __DIR__));
    }
}
