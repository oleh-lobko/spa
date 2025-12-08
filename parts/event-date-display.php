<?php
/**
 * Event Date Display Part
 *
 * Displays formatted date range for events
 *
 * Expected variables:
 * - $start_date_field: Start date field value
 * - $end_date_field: End date field value (optional)
 */

if (!isset($start_date_field)) {
    $start_date_field = get_field('start_date');
}

if (!isset($end_date_field)) {
    $end_date_field = get_field('end_date');
}

if ($start_date_field) {
    $start_timestamp = strtotime($start_date_field);

    if ($end_date_field) {
        $end_timestamp = strtotime($end_date_field);

        // Check if same date (year, month, day)
        if (date('Y-m-d', $start_timestamp) === date('Y-m-d', $end_timestamp)) {
            // Same day - show: January 9, 2015<br>16:30 - 21:00
            echo date('F j, Y', $start_timestamp) . '<br>' . date('G:i', $start_timestamp) . ' - ' . date('G:i', $end_timestamp);
        } elseif (date('Y-m', $start_timestamp) === date('Y-m', $end_timestamp)) {
            // Same month and year - show: January 9 - 15, 2015<br>16:30 - 21:00
            echo date('F j', $start_timestamp) . ' - ' . date('j, Y', $end_timestamp) . '<br>' . date('G:i', $start_timestamp) . ' - ' . date('G:i', $end_timestamp);
        } elseif (date('Y', $start_timestamp) === date('Y', $end_timestamp)) {
            // Same year - show: January 9 - February 15, 2015<br>16:30 - 21:00
            echo date('F j', $start_timestamp) . ' - ' . date('F j, Y', $end_timestamp) . '<br>' . date('G:i', $start_timestamp) . ' - ' . date('G:i', $end_timestamp);
        } else {
            // Different years - show full dates
            echo date('F j, Y', $start_timestamp) . ' - ' . date('F j, Y', $end_timestamp) . '<br>' . date('G:i', $start_timestamp) . ' - ' . date('G:i', $end_timestamp);
        }
    } else {
        // Only start date
        echo date('F j, Y', $start_timestamp) . '<br>' . date('G:i', $start_timestamp);
    }
} else {
    // Fallback if no date is set
    echo 'Date to be announced';
}
?>
