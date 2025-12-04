<?php

/**
 * ACF functions fallback.
 */
add_action('wp', function () {
    if (!function_exists('the_field')) {
        function the_field($selector, $post_id = false, $format_value = true)
        {
            echo '';
        }
    }

    if (!function_exists('get_field')) {
        function get_field($selector, $post_id = false, $format_value = true)
        {
            return '';
        }
    }

    if (!function_exists('have_rows')) {
        function have_rows($selector, $post_id = false)
        {
            return '';
        }
    }

    if (!function_exists('the_sub_field')) {
        function the_sub_field($field_name, $format_value = true)
        {
            echo '';
        }
    }

    if (!function_exists('get_sub_field')) {
        function get_sub_field($selector = '', $format_value = true)
        {
            return '';
        }
    }
});
