<?php
/**
 * Plugin Name: Smart Resty
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: This plugin provides capabilities to read JSON data from WordPress
 * Version: 1.0
 * Author: SmartDroidies
 * Author URI: http://smartdroidies.com
 * License: GNU General Public License v2 or later
 */
function smartyrest_add_endpoint() {
        // register a "json" endpoint to be applied to posts and pages
        add_rewrite_endpoint( 'json2', EP_ALL );
}
add_action( 'init', 'smartrest_add_endpoint' );

function smartyrest_template_redirect() {
        global $wp_query;
        // if this is not a request for json or it's not a singular object then bail
        if ( ! isset( $wp_query->query_vars['json2']))
                return;
        // include custom template
        include dirname( __FILE__ ) . '/resty-template.php';
        exit;
}
add_action( 'template_redirect', 'smartyrest_template_redirect' );

function smartyrest_activate() {
        // ensure our endpoint is added before flushing rewrite rules
        smartyrest_add_endpoint();
        // flush rewrite rules - only do this on activation as anything more frequent is bad!
        flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'smartyrest_activate' );

function smartyrest_deactivate() {
        // flush rules on deactivate as well so they're not left hanging around uselessly
        flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'smartyrest_deactivate' );

?>
 