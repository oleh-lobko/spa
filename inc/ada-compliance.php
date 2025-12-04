<?php

/**
 * ADA complince fixes.
 */

// Add ADA support on Gravity form error message
add_filter('gform_validation_message', function ($msg) {
    return str_replace('class=', "role='alert' class=", $msg);
});

// Add ADA support on Gravity form success message
add_filter('gform_confirmation', function ($msg) {
    return str_replace("id='gform_confirmation_message", "role='alert' id='gform_confirmation_message", $msg);
});
