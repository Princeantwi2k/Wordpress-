<?php
/*
Plugin Name: ClearGage Payment Gateway
Description: WooCommerce payment gateway for ClearGage.
Version: 1.0
Author: Your Name
*/

// Activation hook
register_activation_hook(__FILE__, 'cleargage_activate');

function cleargage_activate() {
    // Activation code, if needed
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'cleargage_deactivate');

function cleargage_deactivate() {
    // Deactivation code, if needed
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-cleargage-payment.php';
