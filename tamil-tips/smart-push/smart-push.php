<?php
/**
 * Plugin Name: Smart Push
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: This plugin provides features to send GCM push notification to android devices. 
 * Version: 1.1-0
 * Author: SmartDroidies
 * Author URI: http://smartdroidies.com
 * License: GNU General Public License v2 or later
 */
 
define('SMARTPUSH_PLUGIN_DIR',plugin_dir_path( __FILE__ ));
 
require_once(SMARTPUSH_PLUGIN_DIR . 'FCM.php');
 
 /* Method to send push notification */
function sd_push($post_ID) {

    $post = get_post($post_ID);
    $permalink = get_permalink( $post_ID );
    $title = $post->post_title;
    $categories = get_the_category();
    if (!empty( $categories ) ) {
        $category_slug = $categories[0]->slug;
    }

    if(isset($title) && isset($post) & isset($permalink)) {
        $fcm = new FCM();
        $data = array("body" => $title, "title" => "Tamil Kuripugal", "id" => $post_ID);
        $resultFCM = $fcm->send_notification($data, $category_slug);

        echo $result; 
        //exit(0);
    }
    return $post_ID;
} 
 
add_action('publish_post', 'sd_push');
?>