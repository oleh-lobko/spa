<?php

/**
 * Custom Post Type for SPA Events.
 */

// Register Custom Post Type
function create_spa_event_cpt()
{
    $labels = [
        'name' => _x('SPA Events', 'Post Type General Name', 'fwp'),
        'singular_name' => _x('SPA Event', 'Post Type Singular Name', 'fwp'),
        'menu_name' => __('SPA Events', 'fwp'),
        'name_admin_bar' => __('SPA Event', 'fwp'),
        'archives' => __('Event Archives', 'fwp'),
        'attributes' => __('Event Attributes', 'fwp'),
        'parent_item_colon' => __('Parent Event:', 'fwp'),
        'all_items' => __('All Events', 'fwp'),
        'add_new_item' => __('Add New Event', 'fwp'),
        'add_new' => __('Add New', 'fwp'),
        'new_item' => __('New Event', 'fwp'),
        'edit_item' => __('Edit Event', 'fwp'),
        'update_item' => __('Update Event', 'fwp'),
        'view_item' => __('View Event', 'fwp'),
        'view_items' => __('View Events', 'fwp'),
        'search_items' => __('Search Event', 'fwp'),
        'not_found' => __('Not found', 'fwp'),
        'not_found_in_trash' => __('Not found in Trash', 'fwp'),
        'featured_image' => __('Featured Image', 'fwp'),
        'set_featured_image' => __('Set featured image', 'fwp'),
        'remove_featured_image' => __('Remove featured image', 'fwp'),
        'use_featured_image' => __('Use as featured image', 'fwp'),
        'insert_into_item' => __('Insert into event', 'fwp'),
        'uploaded_to_this_item' => __('Uploaded to this event', 'fwp'),
        'items_list' => __('Events list', 'fwp'),
        'items_list_navigation' => __('Events list navigation', 'fwp'),
        'filter_items_list' => __('Filter events list', 'fwp'),
    ];

    $args = [
        'label' => __('SPA Event', 'fwp'),
        'description' => __('SPA Events and activities', 'fwp'),
        'labels' => $labels,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'taxonomies' => ['spa_event_category', 'spa_event_tag'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
    ];

    register_post_type('spa_event', $args);
}
add_action('init', 'create_spa_event_cpt', 0);

// Register Custom Taxonomies
function create_spa_event_taxonomies()
{
    // Event Categories
    $labels = [
        'name' => _x('Event Categories', 'Taxonomy General Name', 'fwp'),
        'singular_name' => _x('Event Category', 'Taxonomy Singular Name', 'fwp'),
        'menu_name' => __('Categories', 'fwp'),
        'all_items' => __('All Categories', 'fwp'),
        'parent_item' => __('Parent Category', 'fwp'),
        'parent_item_colon' => __('Parent Category:', 'fwp'),
        'new_item_name' => __('New Category Name', 'fwp'),
        'add_new_item' => __('Add New Category', 'fwp'),
        'edit_item' => __('Edit Category', 'fwp'),
        'update_item' => __('Update Category', 'fwp'),
        'view_item' => __('View Category', 'fwp'),
        'separate_items_with_commas' => __('Separate categories with commas', 'fwp'),
        'add_or_remove_items' => __('Add or remove categories', 'fwp'),
        'choose_from_most_used' => __('Choose from the most used', 'fwp'),
        'popular_items' => __('Popular Categories', 'fwp'),
        'search_items' => __('Search Categories', 'fwp'),
        'not_found' => __('Not Found', 'fwp'),
        'no_terms' => __('No categories', 'fwp'),
        'items_list' => __('Categories list', 'fwp'),
        'items_list_navigation' => __('Categories list navigation', 'fwp'),
    ];

    $args = [
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_rest' => true,
    ];

    register_taxonomy('spa_event_category', ['spa_event'], $args);

    // Event Tags
    $labels = [
        'name' => _x('Event Tags', 'Taxonomy General Name', 'fwp'),
        'singular_name' => _x('Event Tag', 'Taxonomy Singular Name', 'fwp'),
        'menu_name' => __('Tags', 'fwp'),
        'all_items' => __('All Tags', 'fwp'),
        'parent_item' => __('Parent Tag', 'fwp'),
        'parent_item_colon' => __('Parent Tag:', 'fwp'),
        'new_item_name' => __('New Tag Name', 'fwp'),
        'add_new_item' => __('Add New Tag', 'fwp'),
        'edit_item' => __('Edit Tag', 'fwp'),
        'update_item' => __('Update Tag', 'fwp'),
        'view_item' => __('View Tag', 'fwp'),
        'separate_items_with_commas' => __('Separate tags with commas', 'fwp'),
        'add_or_remove_items' => __('Add or remove tags', 'fwp'),
        'choose_from_most_used' => __('Choose from the most used', 'fwp'),
        'popular_items' => __('Popular Tags', 'fwp'),
        'search_items' => __('Search Tags', 'fwp'),
        'not_found' => __('Not Found', 'fwp'),
        'no_terms' => __('No tags', 'fwp'),
        'items_list' => __('Tags list', 'fwp'),
        'items_list_navigation' => __('Tags list navigation', 'fwp'),
    ];

    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_rest' => true,
    ];

    register_taxonomy('spa_event_tag', ['spa_event'], $args);
}
add_action('init', 'create_spa_event_taxonomies', 0);

// Flush rewrite rules on activation
function spa_events_rewrite_flush()
{
    create_spa_event_cpt();
    create_spa_event_taxonomies();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'spa_events_rewrite_flush');

// Modify main query for spa_event archive to show only upcoming events
// Disable archive for spa_event - redirect to Events page template
add_action('template_redirect', function () {
    if (is_post_type_archive('spa_event')) {
        // Check if Events page exists before redirecting
        $events_page = get_page_by_path('events');
        if ($events_page) {
            wp_redirect(get_permalink($events_page));

            exit;
        }
    }
});
