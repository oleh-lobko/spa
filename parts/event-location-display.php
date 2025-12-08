<?php
/**
 * Event Location Display Part
 *
 * Displays event location from map field
 *
 * Expected variables:
 * - $event_map: ACF map field data (optional)
 * - $post_id: Post ID for getting field data (optional, defaults to current post)
 */

// Get map data from ACF map field
if (!isset($event_map)) {
    $post_id = isset($post_id) ? $post_id : get_the_ID();
    $event_map = get_field('event_map', $post_id);
}

if ($event_map && !empty($event_map['address'])) {
    // Display the full address from the map
    echo nl2br(esc_html($event_map['address']));
} else {
    // Default fallback - could be customized per usage
    echo '';
}
?>
