<?php
/*
Plugin Name: WP Instagram Feeds
Plugin URI: http://www.netattingo.com/
Description: This plugin helps to display feeds Instagram feeds on your site.
Author: NetAttingo Technologies
Version: 1.0.0
Author URI: http://www.netattingo.com/
*/
define('WP_DEBUG',true);
define('WPIF_DIR', plugin_dir_path(__FILE__));
define('WPIF_URL', plugin_dir_url(__FILE__));
define('WPIF_PAGE_DIR', plugin_dir_path(__FILE__).'pages/');
define('WPIF_INCLUDE_URL', plugin_dir_url(__FILE__).'includes/');

//Include menu and assign page
function wpif_plugin_menu() {
    $icon = WPIF_URL. 'includes/icon.png';
	add_menu_page("Instagram Feeds", "Instagram Feeds", "administrator", "wp-instagram-feed-setting", "wpif_plugin_pages", $icon ,30);
	add_submenu_page("wp-instagram-feed-setting", "About Us", "About Us", "administrator", "wpif-about-us", "wpif_plugin_pages");
}
add_action("admin_menu", "wpif_plugin_menu");

function wpif_plugin_pages() {

   $pageitem = WPIF_PAGE_DIR.$_GET["page"].'.php';
   include($pageitem);
}

//Include front css 
function wpif_js_css_add_init() {
    wp_enqueue_style("inst_front_css", plugins_url('includes/wpif-front-style.css',__FILE__ )); 
	wp_enqueue_script('inst_front_css');
}
add_action( 'wp_enqueue_scripts', 'wpif_js_css_add_init' );


//add admin css
function wpif_admin_css() {
  wp_register_style('inst_admin_css', plugins_url('includes/wpif-admin-style.css',__FILE__ ));
  wp_enqueue_style('inst_admin_css');
}
add_action( 'admin_init','wpif_admin_css' );


function checkErrMsg1($code) {
  if($code == 400) {
    throw new Exception("Invalid 'User Id' or 'Access Token'. Please Put Correct Credentials");
  }
  return true;
}

//Netgo Shortcode list view
add_shortcode( 'instagram-feeds-list', 'wpif_shortcode_function_list_view' );
function wpif_shortcode_function_list_view( $atts ) {

	$inst_user_id = get_option('netgo_instagram_user_id');
	$inst_access_token = get_option('netgo_instagram_access_token');
	$photo_count = get_option('netgo_instagram_photo_count');
	
	$inst_url="https://api.instagram.com/v1/users/{$inst_user_id}/media/recent/?";
	$inst_url.="access_token={$inst_access_token}&count={$photo_count}";
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$inst_url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$output = curl_exec($ch);
	curl_close($ch);
	$inst_response = json_decode($output, true, 512, JSON_BIGINT_AS_STRING);
	
	$msgCode = $inst_response['meta']['code'];
	$flag=1;
	
	//trigger exception in a "try" block
	try {
		checkErrMsg1($msgCode);
		if(!empty($inst_response)){
?>
<div class="main-instagram-feeds-div">
<h1>Instagram Feeds</h1>
<ul id="list_instagram_feed" class="instagram-feed-list-view">
<?php
	foreach ($inst_response['data'] as $single_feed) {
    $pic_text=$single_feed['caption']['text'];
    $pic_link=$single_feed['link'];
    $pic_like_count=$single_feed['likes']['count'];
    $pic_comment_count=$single_feed['comments']['count'];
    $pic_src=str_replace("http://", "https://", $single_feed['images']['standard_resolution']['url']);
    $pic_created_time=date("F j, Y", $single_feed['caption']['created_time']);
    $pic_created_time=date("F j, Y", strtotime($pic_created_time . " +1 days"));
    ?>
        <li  id="insta-pic-<?php echo $flag; ?><?php echo ($flag%4); ?>">
			<div class="instagram_single_box">
				<div class="inst-img_box">
					<a target="_blank" href="<?php echo $pic_link; ?>">
						<img title='<?php echo $pic_text; ?>' src='<?php echo $pic_src; ?>' />
					</a>
				</div>
				<div class="content"> 
					<div class="contant_box1"><?php echo $pic_text; ?></div>
				</div>
			</div>
        </li>
<?php
		$flag++;
}
?>
</ul>
<div style="clear:both;"></div>
</div>
<?php
	}
	else{
	?>
	<div class="nopost"><h3>No Feed Found!</h3></div> 
	<?php
	}
}

    //catch exception
	catch(Exception $e) {
	  echo '<div class="err-msg"><span class="msg-details">' .$e->getMessage().'</span></div>';
	}

}


function checkErrMsg($code) {
  if($code == 400) {
    throw new Exception("Invalid 'User Id' or 'Access Token'. Please Put Correct Credentials");
  }
  return true;
}
//Netgo Shortcode grid view
add_shortcode( 'instagram-feeds-grid', 'wpif_shortcode_function_grid_view' );
function wpif_shortcode_function_grid_view( $atts ) {

	$inst_user_id = get_option('netgo_instagram_user_id');
	$inst_access_token = get_option('netgo_instagram_access_token');
	$photo_count = get_option('netgo_instagram_photo_count');
	
	$inst_url="https://api.instagram.com/v1/users/{$inst_user_id}/media/recent/?";
	$inst_url.="access_token={$inst_access_token}&count={$photo_count}";
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$inst_url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$output = curl_exec($ch);
	curl_close($ch);
	$inst_response = json_decode($output, true, 512, JSON_BIGINT_AS_STRING);
	$msgCode = $inst_response['meta']['code'];
	
	$flag=1;
	
	//trigger exception in a "try" block
	try {
		checkErrMsg($msgCode);
		if(!empty($inst_response)){
?>
<div class="main-instagram-feeds-div">
<h1>Instagram Feeds</h1>
<ul id="grid_instagram_feed" class="instagram-feed-grid-view">
<?php
	foreach ($inst_response['data'] as $single_feed) {
    $pic_text=$single_feed['caption']['text'];
    $pic_link=$single_feed['link'];
    $pic_like_count=$single_feed['likes']['count'];
    $pic_comment_count=$single_feed['comments']['count'];
    $pic_src=str_replace("http://", "https://", $single_feed['images']['standard_resolution']['url']);
    $pic_created_time=date("F j, Y", $single_feed['caption']['created_time']);
    $pic_created_time=date("F j, Y", strtotime($pic_created_time . " +1 days"));
    ?>
        <li  id="insta-pic-<?php echo $flag; ?><?php echo ($flag%4); ?>">
			<div class="instagram_single_box">
				<div class="inst-img_box">
					<a target="_blank" href="<?php echo $pic_link; ?>">
						<img title='<?php echo $pic_text; ?>' src='<?php echo $pic_src; ?>' />
					</a>
				</div>
				<div class="inst-content"> 
					<div class="inst-title"><?php echo $pic_text; ?></div>
				</div>
			</div>
        </li>
<?php
		$flag++;
}
?>
</ul>
</div>
<div style="clear:both;"></div>
<?php

}
else
		  {
		 ?>
		 <div class="nopost"><h3>No Feed Found!</h3></div> 
		 <?php
		  }
	}
    //catch exception
	catch(Exception $e) {
	  echo '<div class="err-msg"><span class="msg-details">' .$e->getMessage().'</span></div>';
	}
}