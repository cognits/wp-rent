<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:
        
if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style('wpestate_bootstrap',get_template_directory_uri().'/css/bootstrap.css', array(), '1.0', 'all');
        wp_enqueue_style('wpestate_bootstrap-theme',get_template_directory_uri().'/css/bootstrap-theme.css', array(), '1.0', 'all');
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css' ); 
        wp_enqueue_style('wpestate_media',get_template_directory_uri().'/css/my_media.css', array(), '1.0', 'all'); 
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css' );

// END ENQUEUE PARENT ACTION