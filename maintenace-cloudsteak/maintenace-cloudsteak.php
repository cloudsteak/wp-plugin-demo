<?php
/**
 * Plugin Name: Maintenance Mode (CloudSteak)
 * Description: Simple maintenance mode plugin.
 * Version: 1.1.13
 * Author: CloudSteak
 * Author URI: https://cloudsteak.com
 * License: MIT
 * Plugin URI: https://github.com/cloudsteak/wp-plugin-demo
 * Requires PHP: 7.4
 * Requires at least: 6.2
 * Tested up to: 6.4.2
 */

 add_action('admin_menu', 'mm_plugin_menu');
 function mm_plugin_menu() {
     add_menu_page('Maintenance Mode Settings', 'Maintenance Mode', 'administrator', 'mm-settings', 'mm_settings_page');
     add_action('admin_init', 'register_mm_settings');
 }
 
 function register_mm_settings() {
    register_setting('mm-settings-group', 'mm_background_image');
    register_setting('mm-settings-group', 'mm_header_text');
    register_setting('mm-settings-group', 'mm_header_text_color');
    register_setting('mm-settings-group', 'mm_custom_text');
    register_setting('mm-settings-group', 'mm_custom_text_color');
}
 
function mm_settings_page() {
    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
?>
    <div class="wrap">
    <h1>Maintenance Mode Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields('mm-settings-group'); ?>
        <?php do_settings_sections('mm-settings-group'); ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row">Background Image</th>
            <td>
                <input type="text" id="mm_background_image" name="mm_background_image" value="<?php echo esc_attr(get_option('mm_background_image')); ?>" />
                <button type="button" class="button" id="mm_upload_image_button">Upload Image</button>
            </td>
            </tr>
             
            <tr valign="top">
            <th scope="row">Header Text</th>
            <td><input type="text" name="mm_header_text" value="<?php echo esc_attr(get_option('mm_header_text')); ?>" /></td>
            </tr>

            <tr valign="top">
            <th scope="row">Header Text Color</th>
            <td><input type="text" name="mm_header_text_color" value="<?php echo esc_attr(get_option('mm_header_text_color')); ?>" class="color-picker" data-default-color="#000000" /></td>
            </tr>
            
            <tr valign="top">
            <th scope="row">Custom Text</th>
            <td><textarea name="mm_custom_text"><?php echo esc_textarea(get_option('mm_custom_text')); ?></textarea></td>
            </tr>

            <tr valign="top">
            <th scope="row">Custom Text Color</th>
            <td><input type="text" name="mm_custom_text_color" value="<?php echo esc_attr(get_option('mm_custom_text_color')); ?>" class="color-picker" data-default-color="#000000" /></td>
            </tr>
            <tr valign="top">
            <th scope="row">Comment</th>
            <td><label>Select a background image then configure the maintenance mode text.</label></td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($){
        $('.color-picker').wpColorPicker();
        $('#mm_upload_image_button').click(function(e) {
            e.preventDefault();
            var image = wp.media({ 
                title: 'Upload Image',
                multiple: false
            }).open()
            .on('select', function(e){
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#mm_background_image').val(image_url);
            });
        });
    });
    </script>
 <?php
 }
 
 function mm_maintenance_mode() {
    if (!current_user_can('edit_themes') || !is_user_logged_in()) {
        $background_image = get_option('mm_background_image');
        $header_text = get_option('mm_header_text');
        $header_text_color = get_option('mm_header_text_color'); // Fetch the header text color
        $custom_text = get_option('mm_custom_text');
        $custom_text_color = get_option('mm_custom_text_color'); // Fetch the custom text color

        // Check if the user is logged in
        if (is_user_logged_in()) {
            // If the user is logged in, you can choose to not display the background image
            // by setting $background_image to an empty string or a placeholder
            $background_image = ''; // No background image for logged-in users
            $header_text = ''; // No header text for logged-in users
            $custom_text = ''; // No custom text for logged-in users
        }

        wp_die('<div id="cloudsteak-maintenance-mode" class="maintenance-mode" style="padding-left:10%;position: fixed;background-position:center;background-repeat: no-repeat;background-size: cover;left: 0px;top: 0px;height: 100vh;width: 100vw;background-image: url('.esc_url($background_image).');"><h1 style="font-family: sans-serif;font-size: 2.8em;color: '.esc_attr($header_text_color).'">'.esc_html($header_text).'</h1><p style="font-family: sans-serif;font-size: 2em;color: '.esc_attr($custom_text_color).'">'.esc_html($custom_text).'</p></div>');
    }
}


add_action('template_redirect', 'mm_maintenance_mode');
?>
