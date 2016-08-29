<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
       
        <?php
        wp_head(); 
        $favicon = esc_html(get_option('wp_estate_favicon_image', ''));

        if ($favicon != '') {
            echo '<link rel="shortcut icon" href="' . $favicon . '" type="image/x-icon" />';
        } else {
            echo '<link rel="shortcut icon" href="' . get_template_directory_uri() . '/img/favicon.gif" type="image/x-icon" />';
        }

        $wide_class = 'boxed';
        $wide_status = esc_html(get_option('wp_estate_wide_status', ''));
        if ($wide_status == 1) {
            $wide_class = " wide ";
        }
   
        if( !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) && wpestate_check_if_admin_page($post->ID)){
            $wide_class = " wide ";
        }
  

        $wide_page_class = '';
        $map_template = '';
        $header_map_class = '';


        if ( !is_search() && !is_404() && !is_tax() && !is_category()  && !is_tag() && basename(get_page_template($post->ID)) == 'property_list_half.php') {
            $header_map_class = 'google_map_list_header';
            $map_template = 1;
            $wide_class = " wide ";
        }
        
        if (( is_category() || is_tax() ) && get_option('wp_estate_property_list_type', '') == 2) {
            $header_map_class = 'google_map_list_header';
            $map_template = 1;
            $wide_class = " wide ";   
            if( !is_tax() ){
                $map_template = 2;
            }
        }

        if (is_page_template('advanced_search_results.php') && get_option('wp_estate_property_list_type_adv', '') == 2) {
            $header_map_class = 'google_map_list_header';
            $map_template = 1;
            $wide_class = " wide ";
        }
        ?>
    </head>

    <?php
    global $is_top_bar_class;
    $is_top_bar_class = "";
    if (wpestate_show_top_bar()) {
        $is_top_bar_class = " top_bar_on";
    }
    
    
    $transparent_menu_global        =    get_option('wp_estate_transparent_menu','');
    $transparent_class              =    ' ';
    $property_list_type_status      =    esc_html(get_option('wp_estate_property_list_type',''));
    $property_list_type_status_adv  =    esc_html(get_option('wp_estate_property_list_type_adv',''));

    if($transparent_menu_global == 'yes'){
        if(is_tax() && $property_list_type_status == 2 ){
            $transparent_class = '';
        }else{
            $transparent_class = ' transparent_header ';
        }
        
        if( !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) && basename(get_page_template($post->ID)) == 'property_list_half.php' ){
            $transparent_class = '';
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }
    
        if (  !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) && basename(get_page_template($post->ID)) == 'advanced_search_results.php' && $property_list_type_status_adv == 2 ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }
        
        if ( is_tax() && $property_list_type_status == 2 ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }
        
        if( is_single() || is_page() ){
            if( get_post_meta($post->ID, 'transparent_status', true) === 'no' ){
                $transparent_class='';
            }
        }
        
    }else{
        
        if ( !is_search() && !is_404() && !is_tax() && !is_category() && !is_tag() && get_post_meta($post->ID, 'transparent_status', true) === 'yes' && basename(get_page_template($post->ID)) != 'property_list_half.php') {
             $transparent_class = ' transparent_header ';
        }     
          
        if(  !is_404() && !is_tax() && !is_category() && !is_tag()  && isset($post->ID) && basename(get_page_template($post->ID)) == 'property_list_half.php' ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        } 
     
        if (  !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) && basename(get_page_template($post->ID)) == 'advanced_search_results.php' && $property_list_type_status_adv == 2 ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }
  
        if ( is_tax() && $property_list_type_status == 2 ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }   
    }
    
    
    $is_dashboard_page='';

    
    if( is_page() && wpestate_check_if_admin_page($post->ID) && is_user_logged_in()  ){
        $is_dashboard_page='is_dashboard_page';
    }
    
    
    if(is_singular('estate_property')){
        $transparent_menu_listing = get_option('wp_estate_transparent_menu_listing','');
        if( $transparent_menu_listing == 'no'){
            $transparent_class = '';
        }else{
            $transparent_class = ' transparent_header ';
        }
        
    }
       
        
    ?>

    <body <?php body_class($is_top_bar_class); ?>> 
        <?php get_template_part('templates/mobile_menu'); ?>
        
        <div class="website-wrapper <?php echo $is_top_bar_class;?>"  id="all_wrapper">
            <div class="container main_wrapper <?php print $wide_class; print $is_dashboard_page; ?> ">
                <div class="master_header <?php print $wide_class.' '.$header_map_class; ?>">

                <?php
                if (wpestate_show_top_bar()) {
                    get_template_part('templates/top_bar');
                }
                ?>
                    
                 <?php get_template_part('templates/mobile_menu_header'); ?>    
                    

                    <div class="header_wrapper <?php echo $transparent_class . $is_top_bar_class; ?>">
                        <div class="header_wrapper_inside">
                            <?php $logo_margin = intval(get_option('wp_estate_logo_margin', '')); ?>
                            <div class="logo" <?php echo 'style="margin-top:' . $logo_margin . 'px"'; ?>> 

                                <a href="<?php echo esc_url ( home_url('', 'login') ); ?>">
                                <?php
                                $logo='';
                                if( trim($transparent_class)!==''){
                                    $logo = get_option('wp_estate_transparent_logo_image', '');  
                                }else{
                                    $logo = get_option('wp_estate_logo_image', '');  
                                }
                                
                                if ($logo != '') {
                                    print '<img src="' . $logo . '" class="img-responsive retina_ready"  alt="logo"/>';
                                } else {
                                    print '<img class="img-responsive retina_ready" src="' . get_template_directory_uri() . '/img/logo.png" alt="logo"/>';
                                }
                                ?>
                                </a>
                            
                            </div>   
                            
                            <?php
                            if (esc_html(get_option('wp_estate_show_top_bar_user_login', '')) == "yes") {
                                get_template_part('templates/top_user_menu');
                            }
                            ?>   
                            
                            <nav id="access">
                                <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false)); ?>
                            </nav><!-- #access -->
                        </div>
                    </div>

                </div> 

<?php
if (!is_search() && !is_tag() && !is_404() && !is_tax() && !is_category() && ( basename(get_page_template($post->ID)) === 'property_list_half.php' || get_post_type() === 'estate_property' )) {
    //do nothing for now  
} else if (( is_category() || is_tax() ) && get_option('wp_estate_property_list_type', '') ==  2 ) {
    if( !is_tax() ){
        get_template_part('header_media');
    }
    
} else if (is_page_template('advanced_search_results.php') && get_option('wp_estate_property_list_type_adv', '') == 2) {
    //do nothing for now 
} else {
    get_template_part('header_media');
}

if (get_post_type() === 'estate_property' && !is_tax() && !is_search()) {
    get_template_part('templates/property_menu_hidden');
}
?>



<?php
if ($map_template === 1) {
    print '  <div class="full_map_container">';
} else {
    if (!is_404() && !is_tax() && !is_category() && !is_search() && !is_tag()) {
        if ( wpestate_check_if_admin_page($post->ID)) {
            print '  <div class="container content_wrapper_dashboard">';
        } else {
            if ('estate_property' == get_post_type()) {
                if ( is_404()) {
                    print '<div class="content_wrapper  ' . $wide_page_class . ' row ">';
                } else {
                    print '<div class="content_wrapper listing_wrapper ' . $wide_page_class . ' row ">';
                }
            } else {
                if ( is_singular('estate_agent') ) {
                    get_template_part('templates/owner_details_header');
                  
                }
                print '  <div class="content_wrapper ' . $wide_page_class . ' row ">';
            }
        }
    } else {
        print '  <div class="content_wrapper ' . $wide_page_class . 'row ">';
    }
}


?>