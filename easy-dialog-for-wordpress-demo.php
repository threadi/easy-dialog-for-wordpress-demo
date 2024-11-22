<?php
/**
 * Plugin Name:       Easy Block Dialog for WordPress Example
 * Description:       Example usage of Easy Block Dialog for WordPress.
 * Requires at least: 5.0
 * Requires PHP:      8.0
 * Version:           1.0.0
 * Author:            Thomas Zwirner
 * Author URI:        https://www.thomaszwirner.de
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easy-dialog-for-wordpress-demo
 *
 * @package easy-dialog-for-wordpress-demo
 */

/**
 * Add a new dashboard widget.
 *
 * @return void
 */
function edfwd_add_dashboard_widgets(): void {
    wp_add_dashboard_widget(
        'edfwd_dashboard_widget',
        __( 'Easy Dialog for WordPress Example', 'easy-dialog-for-wordpress-demo' ),
        'edfwd_dashboard',
        null,
        null,
        'normal',
        'high'
    );
}
add_action( 'wp_dashboard_setup', 'edfwd_add_dashboard_widgets' );

/**
 * Show link to start demo dialog from dashboard.
 *
 * @return void
 */
function edfwd_dashboard(): void {
    // create dialog.
    $dialog = array(
        'title' => __( 'Simple Demo Dialog Title', 'easy-dialog-for-wordpress-demo' ),
        'texts' => array(
            '<p>' . __( 'This is a demo dialog.', 'easy-dialog-for-wordpress-demo' ) . '</p>'
        ),
        'buttons' => array(
            array(
                'action' => 'closeDialog();',
                'variant' => 'primary',
                'text' => __( 'OK to close it', 'easy-dialog-for-wordpress-demo' ),
            )
        )
    );

    // output.
    echo '<a href="#" class="easy-dialog-for-wordpress" data-dialog="' . esc_attr( wp_json_encode( $dialog ) ) . '">' . __( 'Show simple demo dialog', 'easy-dialog-for-wordpress-demo' ) .  '</a><br>';
}


/**
 * Add the scripts and styles of Easy Dialog for WordPress in the plugin.
 */
function edfwd_dialog_embed(): void {
    // define paths: adjust if necessary.
    $path = trailingslashit(plugin_dir_path(__FILE__)).'vendor/threadi/easy-dialog-for-wordpress/';
    $url = trailingslashit(plugin_dir_url(__FILE__)).'vendor/threadi/easy-dialog-for-wordpress/';

    // bail if path does not exist.
    if( ! file_exists( $path ) ) {
        return;
    }

    // get assets path.
    $script_asset_path = $path . 'build/index.asset.php';

    // bail if assets does not exist.
    if( ! file_exists( $script_asset_path ) ) {
        return;
    }

    // embed the dialog-components JS-script.
    $script_asset      = require( $script_asset_path );
    wp_enqueue_script(
        'easy-dialog-for-wordpress',
        $url . 'build/index.js',
        $script_asset['dependencies'],
        $script_asset['version'],
        true
    );

    // embed the dialog-components CSS-script.
    $admin_css      = $url . 'build/style-index.css';
    $admin_css_path = $path . 'build/style-index.css';
    wp_enqueue_style(
        'easy-dialog-for-wordpress',
        $admin_css,
        array( 'wp-components' ),
        filemtime( $admin_css_path )
    );
}
add_action( 'admin_enqueue_scripts', 'edfwd_dialog_embed' );
