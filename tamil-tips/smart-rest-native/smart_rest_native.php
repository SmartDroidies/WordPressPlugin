<?php
/**
 * Plugin Name: Smart Rest Native
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: This plugin provides capabilities to read JSON data from WordPress
 * Version: 2.0-alpha
 * Author: SmartDroidies
 * Author URI: http://smartdroidies.com
 * License: GNU General Public License v2 or later
 */
function smartrestnative_add_endpoint() {
        // register a "json" endpoint to be applied to posts and pages
        add_rewrite_endpoint( 'rest', EP_ALL );
}
add_action( 'init', 'smartrestnative_add_endpoint' );

function smartrestnative_template_redirect() {
        global $wp_query;
        // if this is not a request for json or it's not a singular object then bail
        if ( ! isset( $wp_query->query_vars['rest']))
                return;
        // include custom template
        if (isset( $_GET['tip']))
           include dirname( __FILE__ ) . '/tip-native-template.php';       
        else
           include dirname( __FILE__ ) . '/tips-native-template.php';
        exit;
}
add_action( 'template_redirect', 'smartrestnative_template_redirect' );

function smartrestnative_activate() {
        // ensure our endpoint is added before flushing rewrite rules
        smartrestnative_add_endpoint();
        // flush rewrite rules - only do this on activation as anything more frequent is bad!
        flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'smartrestnative_activate' );

function smartrestnative_deactivate() {
        // flush rules on deactivate as well so they're not left hanging around uselessly
        flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'smartrestnative_deactivate' );

?>
 