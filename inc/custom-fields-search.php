<?php

/**
 * Extend WordPress search to include custom fields.
 */

// Join Postmeta table
add_filter('posts_join', function ($join) {
    global $wpdb;
    if (is_search() && false === strpos($join, 'postmeta')) {
        $join .= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
});

// Add meta_value field to where to search
add_filter('posts_where', function ($where) {
    global $pagenow, $wpdb;
    if (is_search()) {
        $where = preg_replace('/\(\s*' . $wpdb->posts . ".post_title\\s+LIKE\\s*('.*?')\\s*\\)/", '(' . $wpdb->posts . '.post_title LIKE $1) OR (' . $wpdb->postmeta . '.meta_value LIKE $1)', $where);
    }

    return $where;
});

// Prevent duplicates of posts
add_filter('posts_distinct', function ($where) {
    global $wpdb;

    return is_search() ? 'DISTINCT' : $where;
});
