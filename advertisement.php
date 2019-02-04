<?php
/*
Plugin Name: Advertisement
Plugin URI: http://wordpress.org/plugins/#/
Description: Advertisement plugin will let you add advertisement blocks and use them via shortcodes.
Author: Debarati Datta.
Version: 0.2.9
Author URI: https://faces.tap.ibm.com/bluepages/profile.html?email=debadat1@in.ibm.com
*/
global $adv_db_version;
$adv_db_version = '1.0';

function advertisement_activate() {

    global $wpdb;
    global $adv_db_version;
    $main_table_name = $wpdb->prefix . 'advertisement';
    $cat_table_name = $wpdb->prefix . 'advertisement_category';
    $charset_collate = $wpdb->get_charset_collate();

    $advTableSql = "CREATE TABLE $main_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title tinytext NOT NULL,
        content text NOT NULL,
        image varchar(55) DEFAULT '' NOT NULL,
        shortcode varchar(55) DEFAULT '' NOT NULL,
        category int(10) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    $advCategorySql = "CREATE TABLE $cat_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        category varchar(55) DEFAULT '' NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $advTableSql);
    dbDelta( $advCategorySql);


    add_option( 'adv_db_version', $adv_db_version );

  /* activation code here */
}
register_activation_hook( __FILE__, 'advertisement_activate' );



function advertisement_deactivate() {

echo "You have successfully activated this plugin";
  /* deactivation code here */
}
register_deactivation_hook( __FILE__, 'advertisement_deactivate' );



function themeslug_enqueue_style() {
    wp_enqueue_style( 'bootstrap', 'http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', false ); 
}

function themeslug_enqueue_script() {
    wp_enqueue_script( 'bootstrap-js', 'http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', false );
}

add_action( 'admin_enqueue_scripts', 'themeslug_enqueue_style' );
add_action( 'admin_enqueue_scripts', 'themeslug_enqueue_script' );
/**
 * Load media files needed for Uploader
 */
function load_wp_media_files() {
  wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
add_action('admin_menu', 'advertisement_setup_menu');
 
function advertisement_setup_menu(){
        add_menu_page( 'Advertisment', 'Advertisement', 'manage_options', 'advertisement', 'advertisement_init' );
        add_submenu_page('advertisement','Category','Add Category', 'manage_options', 'adv_category', 'advertisement_category');
}
 
include('advertisement_init.php');
include('advertisement_category.php');


//advertisement shortcodes
function advertisement_shortcode( $atts ) {
 
    $output = '';
 
    $advertisement_atts = shortcode_atts( array(
        'id' => 0,
    ), $atts );
 
    $id =  $advertisement_atts[ 'id' ];

    global $wpdb;
    $get_results = $wpdb->get_results("SELECT * FROM `ibm_advertisement` WHERE `id`=$id ");
    foreach ($get_results as $res) {
        $advertisementTitle = $res->title;
        $advertisementContent = $res->content;
        $advertisementImage = $res->image;
        $advertisementCategory = $res->category;
    }

    $output .= '<div class="advertisement-title">';
    $output .= $advertisementTitle;
    $output .= '</div>';
    $output .= '<div class="advertisement-content">';
    $output .= $advertisementContent;
    $output .= '</div>';
    $output .= '<div class="advertisement-image">';
    $output .= '<img src="'.$advertisementImage.'">';
    $output .= '</div>';
    $output .= '<div class="advertisement-caategory">';
    $output .= $advertisementCategory;
    $output .= '</div>';
 
    return $output;
 
}
add_shortcode( 'advertisement', 'advertisement_shortcode' );