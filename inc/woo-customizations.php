<?php

/**
 * WooCommerce customization.
 */

// Add WooCommerce support
add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});

// ======================================================================
// SHOP / ARCHIVE PAGE
// ======================================================================

// Remove Result count
// remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

// Remove Sorting dropdown
// remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

/*
 * Change Shop Loop product image size
 * @param string $size Image size
 * @return string
 */
add_filter('single_product_archive_thumbnail_size', function ($size) {
    return 'medium_large';
});

// ======================================================================
// SINGLE PRODUCT PAGE
// ======================================================================

// Replace excerpt with full content
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
// add_action('woocommerce_single_product_summary', function () {
//    the_content();
// }, 20);

// Remove info tabs under product info (Description / Reviews / ...)
// remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

// Add Quantity input control buttons
// add_action('woocommerce_before_quantity_input_field', function () {
//    echo '<span class="s-qty-dec"></span>';
// });

// add_action('woocommerce_after_quantity_input_field', function () {
//    echo '<span class="s-qty-inc"></span>';
// });

// ======================================================================
// CART PAGE
// ======================================================================

// ======================================================================
// CHECKOUT PAGE
// ======================================================================

// ======================================================================
// MY ACCOUNT PAGE
// ======================================================================

// ======================================================================
// DASHBOARD TWEAKS
// ======================================================================
