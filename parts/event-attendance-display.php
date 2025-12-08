<?php
/**
 * Event Attendance Display Part
 *
 * Displays attendance information and booking button
 *
 * Expected variables:
 * - $post_id: Post ID for getting field data (optional, defaults to current post)
 * - $show_button: Whether to show booking button (optional, defaults to true)
 */

$post_id = isset($post_id) ? $post_id : get_the_ID();
$show_button = isset($show_button) ? $show_button : true;

$attendance_text = get_field('attendance_text', $post_id);
$event_attendance_info = get_field('event_attendance_info', $post_id);
$event_registration_link = get_field('event_registration_link', $post_id) ?: get_field('signup_button_link', $post_id);

// Use attendance_text or event_attendance_info
$attendance_info = $event_attendance_info ?: $attendance_text;
?>

<?php if ($attendance_info) { ?>
    <div class="attendance-text"><?php echo nl2br(esc_html($attendance_info)); ?></div>
<?php } else { ?>
    <div class="attendance-text">The event is free, but seating is limited to 50.</div>
<?php } ?>
