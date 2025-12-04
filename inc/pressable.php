<?php

/**
 * Remove random filter added from the class methods.
 *
 * @param string $hook     Hook name
 * @param string $function Class Method name
 * @param int    $priority Priority
 */
function st_remove_filter(string $hook, string $function, string $class = '', int $priority = 10): void
{
    global $wp_filter;

    if (empty($wp_filter[$hook]->callbacks[$priority])) {
        return;
    }

    $wp_filter[$hook]->callbacks[$priority] = array_filter($wp_filter[$hook]->callbacks[$priority], function ($v, $k) use ($function, $class) {
        return false !== stripos($k, $function) && is_a($v['function']['0'], $class) ? false : true;
    }, ARRAY_FILTER_USE_BOTH);
}

/**
 * Remove WP actions.
 */
add_action('wp', function () {
    $actions_to_remove = [
        ['hook' => 'wp_footer', 'class' => 'Pressable_Mu_Plugin', 'callback' => 'gauges_init', 'priority' => 99],
    ];

    foreach ($actions_to_remove as $data) {
        st_remove_filter($data['hook'], $data['callback'], $data['class'], $data['priority']);
    }
}, 25);
