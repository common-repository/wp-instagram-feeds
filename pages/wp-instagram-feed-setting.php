<?php
global $wpdb;
//sanitize all post values
$instagram_setting_submit= sanitize_text_field( $_POST['instagram_setting_submit'] );
$netgo_instagram_user_id= sanitize_text_field( $_POST['netgo_instagram_user_id'] );
$netgo_instagram_access_token= sanitize_text_field( $_POST['netgo_instagram_access_token'] );
$netgo_instagram_photo_count= sanitize_text_field( $_POST['netgo_instagram_photo_count'] );
$saved= sanitize_text_field( $_POST['saved'] );

if($instagram_setting_submit!='') { 
    if(isset($netgo_instagram_user_id) ) {
		update_option('netgo_instagram_user_id', $netgo_instagram_user_id);
    }
	if(isset($netgo_instagram_access_token) ) {
		update_option('netgo_instagram_access_token', $netgo_instagram_access_token);
    }
	if(isset($netgo_instagram_photo_count) ) {
		update_option('netgo_instagram_photo_count', $netgo_instagram_photo_count);
    }
	if($saved==true) {
		$message='saved';
	} 
}
?>
  <?php
        if ( $message == 'saved' ) {
		echo ' <div class="added-success"><p><strong>Settings Saved.</strong></p></div>';
		}
   ?>
   
<div class="wrap netgo-instagram-post-setting">
    <form method="post" id="wpifSettingForm" action="">
	<h2><?php _e('Instagram Feed Setting','');?></h2>
		<table class="form-table">
			<tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="netgo_instagram_user_id"><?php _e('Instagram User Id','');?></label>
				</th>
				<td><input type="text" name="netgo_instagram_user_id" size="50" value="<?php echo get_option('netgo_instagram_user_id'); ?>" />
				<a target="_blank" href="http://jelled.com/instagram/lookup-user-id"><span class="help-link">Click here to know how to get your instagram user id.</span></a>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="netgo_instagram_access_token"><?php _e('Access Token','');?></label>
				</th>
				<td><input type="text" name="netgo_instagram_access_token" size="50" value="<?php echo get_option('netgo_instagram_access_token'); ?>" />
				<a target="_blank" href="http://jelled.com/instagram/access-token"><span class="help-link">Click here to know how to generate access token.</span></a>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="netgo_instagram_photo_count"><?php _e('Number of Photos','');?></label>
				</th>
				<td><input type="text" name="netgo_instagram_photo_count" size="50" value="<?php echo get_option('netgo_instagram_photo_count'); ?>" />
				<span class="help-link">Do not put any value if you want to show all instagram photos.</span>
				</td>
			</tr>
		</table>
		
        <p class="submit">
		<input type="hidden" name="saved" value="saved"/>
        <input type="submit" name="instagram_setting_submit" class="button-primary" value="Save Changes" />
		  <?php if(function_exists('wp_nonce_field')) wp_nonce_field('instagram_setting_submit', 'instagram_setting_submit'); ?>
        </p>
    </form>
</div>

