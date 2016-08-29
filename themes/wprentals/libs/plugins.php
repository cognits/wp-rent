<?php
require_once get_template_directory().'/libs/plugins/multiple_sidebars_plugin.php';
require_once get_template_directory().'/libs/plugins/class-tgm-plugin-activation.php';

///////////////////////////////////////////////////////////////////////////////////////////
/////// Required Plugins 
///////////////////////////////////////////////////////////////////////////////////////////




if( !function_exists('wpestate_required_plugins') ):
function wpestate_required_plugins() {
	$plugins = array(
		array(
                    'name'     			=> 'Revolution Slider', 
                    'slug'     			=> 'revslider', 
                    'source'   			=> get_template_directory_uri()  . '/libs/plugins/revslider.zip',
                    'required' 			=> true,
                    'version' 			=> '',
                    'force_activation' 		=> true, 
                    'force_deactivation' 	=> true,
                    'external_url' 		=> '',
		),
               array(
                    'name'     			=> 'WPBakery Visual Composer', 
                    'slug'     			=> 'js_composer', 
                    'source'   			=> get_template_directory_uri()  . '/libs/plugins/js_composer.zip',
                    'required' 			=> true,
                    'version' 			=> '',
                    'force_activation' 		=> true, 
                    'force_deactivation' 	=> true,
                    'external_url' 		=> '',
		),
                array(
                    'name'     			=> 'Ultimate Addons for Visual Composer', 
                    'slug'     			=> 'Ultimate_VC_Addons', 
                    'source'   			=> get_template_directory_uri()  . '/libs/plugins/Ultimate_VC_Addons.zip',
                    'required' 			=> true,
                    'version' 			=> '',
                    'force_activation' 		=> true, 
                    'force_deactivation' 	=> true,
                    'external_url' 		=> '',
		)
           
	);

	
	
		$config = array(
		'domain'       		=> 'wpestate',         	
		'default_path' 		=> '',                         	
		'parent_slug'           => 'themes.php', 				
						
		'menu'         		=> 'install-required-plugins', 	
		'has_notices'      	=> true,                       
		'is_automatic'    	=> true,					   
		'message' 			=> '',							
		'strings'      		=> array(
			'page_title'                       			=> esc_html__(  'Install Required Plugins', 'wpestate' ),
			'menu_title'                       			=> esc_html__(  'Install Plugins', 'wpestate' ),
			'installing'                       			=> esc_html__(  'Installing Plugin: %s', 'wpestate' ), 
			'oops'                             			=> esc_html__(  'Something went wrong with the plugin API.', 'wpestate' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ,'wpestate'), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ,'wpestate'), // %1$s = plugin name(s)
			'notice_cannot_install'  				=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.','wpestate' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.','wpestate' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.','wpestate' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 				=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.','wpestate' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 					=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.','wpestate' ), // %1$s = plugin name(s)
			'notice_cannot_update' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'wpestate'), // %1$s = plugin name(s)
			'install_link' 					 	=> _n_noop( 'Begin installing plugin', 'Begin installing plugins','wpestate' ),
			'activate_link' 				  	=> _n_noop( 'Activate installed plugin', 'Activate installed plugins','wpestate' ),
			'return'                           			=> esc_html__(  'Return to Required Plugins Installer', 'wpestate' ),
			'plugin_activated'                 			=> esc_html__(  'Plugin activated successfully.', 'wpestate' ),
			'complete' 						=> esc_html__(  'All plugins installed and activated successfully. %s', 'wpestate' ), 
			'nag_type'						=> 'updated'
		)
	);
tgmpa($plugins, $config);
}
endif; // end   wpestate_required_plugins  
?>
