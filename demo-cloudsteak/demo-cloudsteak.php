<?php
/**
 * Plugin Name: Maintenance Mode (CloudSteak)
 * Description: Simple maintenance mode plugin.
 * Version: 1.0.4
 * Author: CloudSteak
 */

 add_action('admin_menu', 'mm_plugin_menu');
 function mm_plugin_menu() {
     add_menu_page('Maintenance Mode Settings', 'Maintenance Mode', 'administrator', 'mm-settings', 'mm_settings_page');
     add_action('admin_init', 'register_mm_settings');
 }
 
 function register_mm_settings() {
    register_setting('mm-settings-group', 'mm_background_image');
    register_setting('mm-settings-group', 'mm_header_text');
    register_setting('mm-settings-group', 'mm_custom_text');
}
 
function mm_settings_page() {
    wp_enqueue_media();
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
            <th scope="row">Custom Text</th>
            <td><textarea name="mm_custom_text"><?php echo esc_textarea(get_option('mm_custom_text')); ?></textarea></td>
            </tr>
            <tr valign="top">
            <th scope="row">Comment</th>
            <td><label>Select a background image then configure the maintenance mode text.</label></td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    </div>
    <script>
    jQuery(document).ready(function($){
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
        $custom_text = get_option('mm_custom_text');

        wp_die('<div style="background: url('.esc_url($background_image).') no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; height: 100vh; display: flex; justify-content: center; align-items: center; text-align: center;"><h1>'.esc_html($header_text).'</h1><p>'.esc_html($custom_text).'</p></div>');
    }
}

add_action('template_redirect', 'mm_maintenance_mode');
?>
