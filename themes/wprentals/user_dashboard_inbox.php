<?php
// Template Name: User Dashboard Inbox
// Wp Estate Pack
if ( !is_user_logged_in() ) {   
    wp_redirect(  esc_html( home_url() ) );exit();
} 


global $user_login;
$current_user = wp_get_current_user();
$userID                         =   $current_user->ID;
$user_login                     =   $current_user->user_login;
$user_pack                      =   get_the_author_meta( 'package_id' , $userID );
$user_registered                =   get_the_author_meta( 'user_registered' , $userID );
$user_package_activation        =   get_the_author_meta( 'package_activation' , $userID );   
$paid_submission_status         =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( get_option('wp_estate_price_submission','') );
$submission_curency_status      =   wpestate_curency_submission_pick();
$edit_link                      =   wpestate_get_dasboard_edit_listing();
$processor_link                 =   wpestate_get_procesor_link();

get_header();
$options=wpestate_page_details($post->ID);
?> 
  
<div class="row is_dashboard">
    <?php
    if( wpestate_check_if_admin_page($post->ID) ){
        if ( is_user_logged_in() ) {   
            get_template_part('templates/user_menu'); 
        }  
    }
    ?> 
    <div class=" dashboard-margin">
        
        <div class="dashboard-header">
            <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h1 class="entry-title listings-title-dash"><?php the_title(); ?></h1>
            <?php } ?>
        </div>  
        
        <div class="row admin-list-wrapper inbox-wrapper">    
        <?php
          $args = array(
                'post_type'         => 'wpestate_message',
                'post_status'       => 'publish',
                'paged'             => $paged,
                'posts_per_page'    => 80,
                'order'             => 'DESC',
              
                'meta_query' => array(
                                    'relation' => 'AND',
                                    array(
                                        'relation' => 'OR',
                                        array(
                                                'key'       => 'message_to_user',
                                                'value'     => $userID,
                                                'compare'   => '='
                                        ),
                                        array(
                                                'key'       => 'message_from_user',
                                                'value'     => $userID,
                                                'compare'   => '='
                                        ),
                                    ),
                                    array(
                                        'key'       => 'first_content',
                                        'value'     => 1,
                                        'compare'   => '='
                                    ),  
                                    array(
                                        'key'       => 'delete_destination'.$userID,
                                        'value'     => 1,
                                        'compare'   => '!='
                                    ),
                            )
            );

          
            $message_selection = new WP_Query($args);
            while ($message_selection->have_posts()): $message_selection->the_post(); 
                get_template_part('templates/message-listing-unit'); 
            endwhile;
            wp_reset_query();
            ?>    
    </div>
    </div>
</div>  

<?php 
wp_reset_query();
get_footer(); 
?>