<?php
/**
 * Plugin Name: Marquee Advanced for Elementor
 * Description: Adds an advanced marquee widget to Elementor
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: marquee-advanced
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check if Elementor is active
if (!did_action('elementor/loaded')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-warning is-dismissible"><p>';
        esc_html_e('Marquee Advanced requires Elementor to be installed and activated.', 'marquee-advanced');
        echo '</p></div>';
    });
    return;
}

// Register the widget
add_action('elementor/widgets/register', function($widgets_manager) {
    require_once __DIR__ . '/includes/widget.php';
    $widgets_manager->register(new \Elementor\Marquee_Advanced());
});

// Enqueue frontend scripts
add_action('elementor/frontend/after_register_scripts', function() {
    wp_register_script(
        'marquee-advanced-frontend',
        plugins_url('/includes/frontend.js', __FILE__),
        ['jquery', 'elementor-frontend', 'imagesloaded'],
        '1.0.0',
        true
    );
});