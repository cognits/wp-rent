<?php
// Template Name: User Dashboard Favorite
// Wp Estate Pack

if ( !is_user_logged_in() ) {   
     wp_redirect(  esc_html( home_url() ) );exit();
} 

$current_user = wp_get_current_user();   
$paid_submission_status         =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( get_option('wp_estate_price_submission','') );
$submission_curency_status      =   wpestate_curency_submission_pick();
$userID                         =   $current_user->ID;
$user_option                    =   'favorites'.$userID;
$curent_fav                     =   get_option($user_option);
$show_remove_fav                =   1;   
$show_compare                   =   1;
$show_compare_only              =   'no';
$currency                       =   esc_html( get_option('wp_estate_currency_symbol', '') );
$where_currency                 =   esc_html( get_option('wp_estate_where_currency_symbol', '') );

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
            

        
        <div class="row admin-list-wrapper flex_wrapper_list">    
        <?php
        if( !empty($curent_fav)){
            $args = array(
                'post_type'        => 'estate_property',
                'post_status'      => 'publish',
                'posts_per_page'   => -1 ,
                'post__in'         => $curent_fav 
            );


            $prop_selection = new WP_Query($args);
            $counter = 0;
            $options['related_no']=4;

            while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                get_template_part('templates/dashboard_listing_unit'); 
            endwhile;
            
        }else{
            print '<h4 class="no_favorites">'.esc_html__( 'You don\'t have any favorite properties yet!','wpestate').'</h4>';
        }
        ?>    
        </div>          
    </div>
</div>   

<?php 
wp_reset_query();
get_footer(); 
?>