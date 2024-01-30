<?php
/**
 * Plugin Name: Maintenance Mode (CloudSteak)
 * Description: Simple maintenance mode plugin.
 * Version: 1.0.1
 * Author: CloudSteak
 */

 function mm_maintenance_mode() {
    if (!current_user_can('edit_themes') || !is_user_logged_in()) {
        wp_die('<h1>Under Maintenance</h1><p>We are currently performing maintenance. Please check back soon.</p>');
    }
}

add_action('template_redirect', 'mm_maintenance_mode');
?>
