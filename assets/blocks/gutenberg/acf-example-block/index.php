<?php

/**
 * The name variable is required and should NOT include the namespace.
 */
$name = 'example-block';

/**
 * @see https://www.advancedcustomfields.com/resources/acf_register_block_type/
 */
$settings = [
    'title' => __('Example block', 'fwp'),
    'description' => __('A custom example block.', 'fwp'),
    'category' => 'common',
    'icon' => 'block-default',
];

$controller = function ($block_attributes) {
    return array_merge($block_attributes, []);
};
