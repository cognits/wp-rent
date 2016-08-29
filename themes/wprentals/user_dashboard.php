<?php
// Template Name: User Dashboard
// Wp Estate Pack
if ( !is_user_logged_in() ) {   
    wp_redirect(  esc_html( home_url() ) );exit();
} 

if ( !wpestate_check_user_level()){
   wp_redirect(  esc_html( home_url() ) );exit(); 
}

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
$floor_link                     =   '';
$processor_link                 =   wpestate_get_procesor_link();
 $th_separator                  =   get_option('wp_estate_prices_th_separator','');
if( isset( $_GET['delete_id'] ) ) {
    if( !is_numeric($_GET['delete_id'] ) ){
        exit('you don\'t have the right to delete this');
    }else{
        $delete_id= intval ( $_GET['delete_id']);
        $the_post= get_post( $delete_id); 
        if( $current_user->ID != $the_post->post_author ) {
            exit('you don\'t have the right to delete this');;
        }else{
            // delete attchaments
            $arguments = array(
                'numberposts'   => -1,
                'post_type'     => 'attachment',
                'post_parent'   => $delete_id,
                'post_status'   => null,
                'exclude'       => get_post_thumbnail_id(),
                'orderby'       => 'menu_order',
                'order'         => 'ASC'
            );
            $post_attachments = get_posts($arguments);
            
            foreach ($post_attachments as $attachment) {
                wp_delete_post($attachment->ID);                      
            }
            wp_delete_post( $delete_id ); 
        }  
    }
}  
  
get_header();
$options=wpestate_page_details($post->ID);
$new_mess=0;

$title_search='';
if( isset($_POST['wpestate_prop_title']) ){
    $title_search=sanitize_text_field($_POST['wpestate_prop_title']);
}
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
        <div class="search_dashborad_header">
            <form method="post" action="<?php echo wpestate_get_dashboard_link();?>">
            <div class="col-md-4">
                <input type="text" id="title" class="form-control" value="" size="20" name="wpestate_prop_title" placeholder="<?php esc_html_e('Search by property name.','wpestate');?>">
            </div>
            <div class="col-md-6">
                <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" value="<?php esc_html_e('Search','wpestate');?>">
            </div>
            </form>    
        </div>  
        
        
        
        
        <div class="row admin-list-wrapper flex_wrapper_list">    
        <?php
        $prop_no      =   intval( get_option('wp_estate_prop_no', '') );
        $paged        = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
                'post_type'        =>  'estate_property',
                'author'           =>  $current_user->ID,
                'paged'             => $paged,
                'posts_per_page'    => $prop_no,
                'post_status'      =>  array( 'any' )
            );


        if($title_search!=''){
            $args['s']= $title_search;
            add_filter( 'posts_search', 'wpestate_search_by_title_only', 500, 2 );
            $prop_selection = new WP_Query($args);
            remove_filter( 'posts_search', 'wpestate_search_by_title_only', 500 );
            $new_mess=1;
        }else{
            $prop_selection = new WP_Query($args);
        }
        
        if( !$prop_selection->have_posts() ){
            if($new_mess==1){
                print '<h4 class="no_favorites">'.esc_html__( 'No results!','wpestate').'</h4>';
            }else{
                print '<h4 class="no_list_yet">'.esc_html__( 'You don\'t have any properties yet!','wpestate').'</h4>';
            }
         }

        while ($prop_selection->have_posts()): $prop_selection->the_post();          
            get_template_part('templates/dashboard_listing_unit'); 
        endwhile;
        
        kriesi_pagination($prop_selection->max_num_pages, $range =2);
        ?>    
        </div>
    </div>
</div>  

<?php 
wp_reset_query();
get_footer(); 
?>