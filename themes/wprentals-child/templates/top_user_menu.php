<?php
$current_user               =   wp_get_current_user();
$user_custom_picture        =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
$user_small_picture_id      =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
if( $user_small_picture_id == '' ){

    $user_small_picture[0]=get_template_directory_uri().'/img/default_user_small.png';
}else{
    $user_small_picture=wp_get_attachment_image_src($user_small_picture_id,'wpestate_user_thumb');
    
}
?>

   
<?php if(is_user_logged_in()){ ?>
    <div class="user_menu user_loged" id="user_menu_u">
        <div class="menu_user_picture" style="background-image: url('<?php print $user_small_picture[0]; ?>');"></div>
        <a class="menu_user_tools dropdown" id="user_menu_trigger" data-toggle="dropdown">    
        <?php
        // <i class="fa fa-bars"></i>
        echo '<span class="menu_username">'.ucwords($current_user->user_login).'</span>'; 
        ?>   <i class="fa fa-caret-down"></i> 
         </a>
       
<?php }else{ ?>
    <div class="user_menu" id="user_menu_u">   
        <div class="signuplink" id="topbarlogin"><?php esc_html_e('Login','wpestate');?></div>
        <div class="signuplink" id="topbarregister"><?php esc_html_e('Signup','wpestate')?></div>
        
        <?php if(  esc_html ( get_option('wp_estate_show_submit','') ) ==='yes'){ ?>
            <a href="<?php print  wpestate_get_dasboard_add_listing();?>" id="submit_action"><?php esc_html_e('Submit Property','wpestate');?></a>
        <?php } ?>                   
       
<?php } ?>   
                  
    </div> 
        
        
<?php 

if ( 0 != $current_user->ID  && is_user_logged_in() ) {
    $username               =   $current_user->user_login ;
    $add_link               =   wpestate_get_dasboard_add_listing();
    $dash_profile           =   wpestate_get_dashboard_profile_link();
    $dash_favorite          =   wpestate_get_dashboard_favorites();
    $dash_link              =   wpestate_get_dashboard_link();
    $dash_searches          =   wpestate_get_searches_link(); 
    $dash_reservation       =   wpestate_get_my_reservation_link();
    $dash_bookings          =   wpestate_get_my_bookings_link();
    $dash_inbox             =   get_inbox_wpestate_booking();
    $dash_invoices          =   get_invoices_wpestate();
    $logout_url             =   wp_logout_url();      
    $home_url               =   esc_html( home_url() );
    
?> 
    <div id="user_menu_open"> 
        <?php if($home_url!=$dash_profile){?>
                <a href="<?php print $dash_profile;?>" ><i class="fa fa-cog"></i><?php esc_html_e('My Profile','wpestate');?></a>   
        <?php   
        }
        ?>
        
        <?php if($home_url!=$dash_link && wpestate_check_user_level()){?>
            <a href="<?php print $dash_link;?>" ><i class="fa fa-map-marker"></i><?php esc_html_e('My Properties','wpestate');?></a>
        <?php   
        }
        ?>
        
        <?php if($home_url!=$add_link && wpestate_check_user_level()){?>
            <a href="<?php print $add_link;?>" ><i class="fa fa-plus"></i><?php esc_html_e('Add New Property','wpestate');?></a>        
        <?php   
        }
        ?>
        
        <?php if($home_url!=$dash_favorite){?>
            <a href="<?php print $dash_favorite;?>" class="active_fav"><i class="fa fa-heart"></i><?php esc_html_e('Favorites','wpestate');?></a>
        <?php   
        }
        ?>
        
        <?php if($home_url!=$dash_searches && wpestate_check_user_level() ){?>
            <a href="<?php print $dash_searches;?>" class="active_fav"><i class="fa fa-search"></i><?php esc_html_e('Saved Searches','wpestate');?></a>
        <?php   
        }
        if($home_url!=$dash_reservation  ){?>
            <a href="<?php print $dash_reservation;?>" class="active_fav"><i class="fa fa-folder-open"></i><?php esc_html_e('Reservations','wpestate');?></a>
        <?php   
        }
        
        if($home_url!=$dash_bookings && wpestate_check_user_level() ){?>
            <a href="<?php print $dash_bookings;?>" class="active_fav"><i class="fa fa-folder-open-o"></i><?php esc_html_e('Bookings','wpestate');?></a>
        <?php   
        }
        if($home_url!=$dash_inbox){?>
            <a href="<?php print $dash_inbox;?>" class="active_fav"><i class="fa fa-inbox"></i><?php esc_html_e('Inbox','wpestate');?></a>
        <?php   
         }
        if($home_url!=$dash_invoices && wpestate_check_user_level() ){?>
            <a href="<?php print $dash_invoices;?>" class="active_fav"><i class="fa fa-file-o"></i><?php esc_html_e('Invoices','wpestate');?></a>
        <?php   
        }
        ?>
           
       
        <a href="<?php echo wp_logout_url();?>" title="Logout" class="menulogout"><i class="fa fa-power-off"></i><?php esc_html_e('Log Out','wpestate');?></a>
    </div>
    
<?php } ?>