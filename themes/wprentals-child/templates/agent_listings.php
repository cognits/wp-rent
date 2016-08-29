<!-- GET AGENT LISTINGS-->
<?php
global $agent_id;
global $leftcompare;
global $prop_selection;
global $wp_query;
global $curent_fav;
global $full_page;
global $comments_data;
global $listing_type;
global $property_unit_slider;
$listing_type   =   get_option('wp_estate_listing_unit_type','');
$current_user = wp_get_current_user();
$userID             =   $current_user->ID;
$user_option        =   'favorites'.$userID;
$curent_fav         =   get_option($user_option);
$show_compare_link  =   'no';
$currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
$where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$leftcompare        =   1;
$owner_id           =   get_post_meta($agent_id, 'user_agent_id', true);
$property_unit_slider       =   esc_html ( get_option('wp_estate_prop_list_slider','') ); 


if ( $comments_data['prop_selection']!='' &&  $comments_data['prop_selection']->have_posts() ) {
    $show_compare   =   1;
    $compare_submit =   wpestate_get_compare_link();
    ?>
    <div class="mylistings">
        <?php   
        $full_page=1;
        print'<h3 id="other_listings">'.esc_html__( 'My Listings','wpestate').'</h3>';
        while ($comments_data['prop_selection']->have_posts()): $comments_data['prop_selection']->the_post();    
            get_template_part('templates/property_unit');  
        endwhile;
        // Reset postdata
        wp_reset_postdata();
        ?>
        
    <?php 
        //second_loop_pagination($prop_selection->max_num_pages,$range =2,$paged,esc_url(get_permalink()));
        //kriesi_pagination_agent($prop_selection->max_num_pages, $range =2);    
    ?>     
    </div>
<?php        
} 
?>
    
<?php //wp_localize_script('wpestate_googlecode_regular', 'googlecode_regular_vars2', array( 'markers2' =>  $selected_pins) ); ?>