<?php
/**
 * Plugin Name: Smart Rest
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: This plugin provides capabilities to read JSON data from WordPress
 * Version: 0.1-alpha
 * Author: SmartDroidies
 * Author URI: http://smartdroidies.com
 * License: GNU General Public License v2 or later
 */
function smartrest_add_endpoint() {
        // register a "json" endpoint to be applied to posts and pages
        add_rewrite_endpoint( 'json', EP_ALL );
}
add_action( 'init', 'smartrest_add_endpoint' );

function smartrest_template_redirect() {
        global $wp_query;
        // if this is not a request for json or it's not a singular object then bail
        if ( ! isset( $wp_query->query_vars['json']))
                return;
        // include custom template
        include dirname( __FILE__ ) . '/json-template.php';
        exit;
}
add_action( 'template_redirect', 'smartrest_template_redirect' );

function smartrest_activate() {
        // ensure our endpoint is added before flushing rewrite rules
        smartrest_add_endpoint();
        // flush rewrite rules - only do this on activation as anything more frequent is bad!
        flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'smartrest_activate' );

function smartrest_deactivate() {
        // flush rules on deactivate as well so they're not left hanging around uselessly
        flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'smartrest_deactivate' );

?>
 