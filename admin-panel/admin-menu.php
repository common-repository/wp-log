<?php
if( !defined('wl_url') ) die();
// create custom plugin settings menu
add_action('admin_menu', 'wl_options_menu');

function wl_options_menu() {

	//create new top-level menu
	add_menu_page(
		__('Log Settings','wl'),
		__('Log Settings','wl'),
		'administrator',
		'wp_log_settings',
		'wl_settings_page',
		wl_url.'images/icon.png'
	);

	//call register settings function
	add_action( 'admin_init', 'wl_register_settings_page' );
}


function wl_register_settings_page() {
	register_setting( 'wl_options_group', 'wl_user_login_option' );
	register_setting( 'wl_options_group', 'wl_user_logout_option' );
	register_setting( 'wl_options_group', 'wl_user_register_option' );
	register_setting( 'wl_options_group', 'wl_user_deleted_option' );
	register_setting( 'wl_options_group', 'wl_post_edit_option' );
	register_setting( 'wl_options_group', 'wl_post_delete_option' );
	register_setting( 'wl_options_group', 'wl_theme_switch_option' );
	register_setting( 'wl_options_group', 'wl_new_comment_option' );
	register_setting( 'wl_options_group', 'wl_comment_approved_option' );
	register_setting( 'wl_options_group', 'wl_comment_unapproved_option' );
	register_setting( 'wl_options_group', 'wl_plugin_activated_option' );
	register_setting( 'wl_options_group', 'wl_plugin_deactivated_option' );
	register_setting( 'wl_options_group', 'wl_receiver_mail' );
	register_setting( 'wl_options_group', 'wl_emails_subject' );
}

function wl_settings_page() {
global $pagenow;
?>
<div class="wrap">

<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ){?>

<div id="message" class="updated fade"><?php _e('Settings Successfully Changed','wl'); ?></div>


<?php } ?>

<h2><?php _e('Logs Settings','wl'); ?></h2>

<form method="post" action="options.php">
        
    <?php settings_fields('wl_options_group'); ?>
    <table class="form-table wl_options_form <?php echo get_bloginfo('text_direction'); ?>">
        
        <tr>
			<th>
				<label><?php _e('User Login','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_user_login_option[]" value="db" <?php wl_checkboxes(get_option('wl_user_login_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_user_login_option[]" value="mail" <?php wl_checkboxes(get_option('wl_user_login_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('User Logout','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_user_logout_option[]" value="db" <?php wl_checkboxes(get_option('wl_user_logout_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_user_logout_option[]" value="mail" <?php wl_checkboxes(get_option('wl_user_logout_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('User Register','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_user_register_option[]" value="db" <?php wl_checkboxes(get_option('wl_user_register_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_user_register_option[]" value="mail" <?php wl_checkboxes(get_option('wl_user_register_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('User Deleted','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_user_deleted_option[]" value="db" <?php wl_checkboxes(get_option('wl_user_deleted_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_user_deleted_option[]" value="mail" <?php wl_checkboxes(get_option('wl_user_deleted_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('Post Edit','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_post_edit_option[]" value="db" <?php wl_checkboxes(get_option('wl_post_edit_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_post_edit_option[]" value="mail" <?php wl_checkboxes(get_option('wl_post_edit_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('Post Delete','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_post_delete_option[]" value="db" <?php wl_checkboxes(get_option('wl_post_delete_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_post_delete_option[]" value="mail" <?php wl_checkboxes(get_option('wl_post_delete_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('Theme Switch','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_theme_switch_option[]" value="db" <?php wl_checkboxes(get_option('wl_theme_switch_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_theme_switch_option[]" value="mail" <?php wl_checkboxes(get_option('wl_theme_switch_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('New Comment Insert','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_new_comment_option[]" value="db" <?php wl_checkboxes(get_option('wl_new_comment_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_new_comment_option[]" value="mail" <?php wl_checkboxes(get_option('wl_new_comment_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('Comment Approved','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_comment_approved_option[]" value="db" <?php wl_checkboxes(get_option('wl_comment_approved_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_comment_approved_option[]" value="mail" <?php wl_checkboxes(get_option('wl_comment_approved_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('Comment Unapproved','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_comment_Unapproved_option[]" value="db" <?php wl_checkboxes(get_option('wl_comment_Unapproved_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_comment_Unapproved_option[]" value="mail" <?php wl_checkboxes(get_option('wl_comment_Unapproved_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('Plugin Activated','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_plugin_activated_option[]" value="db" <?php wl_checkboxes(get_option('wl_plugin_activated_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_plugin_activated_option[]" value="mail" <?php wl_checkboxes(get_option('wl_plugin_activated_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label><?php _e('Plugin Deactivated','wl'); ?></label>
			</th>
			<td>
				<label><?php _e('Save in Database', 'wl'); ?></label><input type="checkbox" name="wl_plugin_deactivated_option[]" value="db" <?php wl_checkboxes(get_option('wl_plugin_deactivated_option'),'db'); ?> />
				<label><?php _e('Send with Email', 'wl'); ?></label><input type="checkbox" name="wl_plugin_deactivated_option[]" value="mail" <?php wl_checkboxes(get_option('wl_plugin_deactivated_option'),'mail'); ?> />
			</td>
        </tr>
        
        <tr>
			<th>
				<label for="wl_receiver_mail"><?php _e('Logs Receiver Mail','wl'); ?></label>
			</th>
			<td>
				<input type="email" name="wl_receiver_mail" id="wl_receiver_mail" value="<?php echo get_option('wl_receiver_mail',get_bloginfo('admin_email')); ?>"/>
			</td>
        </tr>
        
        <tr>
			<th>
				<label for="wl_emails_subject"><?php _e('Emails Subject','wl'); ?></label>
			</th>
			<td>
				<input type="text" name="wl_emails_subject" id="wl_emails_subject" value="<?php echo get_option('wl_emails_subject',__('New Activity - {action}','wl')); ?>"/>
			</td>
        </tr>
		
    </table>
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>