<?php
/**
 * Event Date Block Processing Part
 *
 * Processes event start and end dates and extracts date components
 * for display in the blue date block.
 *
 * Expected variables:
 * - $start_date: Start date field value
 * - $end_date: End date field value (optional)
 *
 * Sets variables:
 * - $event_month: Formatted month (uppercase)
 * - $event_day: Day number
 * - $event_year: Year
 * - $start_timestamp: Start date timestamp
 * - $end_timestamp: End date timestamp (if available)
 */

// Initialize variables
$event_month = '';
$event_day = '';
$event_year = '';
$start_timestamp = null;
$end_timestamp = null;

// Get the date fields if not passed as variables
if (!isset($start_date)) {
    $start_date = get_field('start_date');
}

if (!isset($end_date)) {
    $end_date = get_field('end_date');
}

// Extract start date components for blue date block
if ($start_date) {
    $start_timestamp = strtotime($start_date);
    if ($start_timestamp) {
        $event_month = strtoupper(date('M', $start_timestamp));
        $event_day = date('j', $start_timestamp);
        $event_year = date('Y', $start_timestamp);
    }
}

// Process end date if available
if ($end_date) {
    $end_timestamp = strtotime($end_date);
}
?>
