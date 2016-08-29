<?php
//related listings
global $full_page;
global $is_widget;
global $show_sim_two;
global $property_unit_slider;
$is_widget=0;
$full_page=1;
$counter = 0;
$post_category          = get_the_terms($post->ID, 'property_category');
$post_action_category   = get_the_terms($post->ID, 'property_action_category');
$post_city_category     = get_the_terms($post->ID, 'property_city');
$similar_no             = 3;
$args                   = '';
$items[]                = '';
$items_actions[]        = '';
$items_city[]           = '';
$categ_array            = '';
$action_array           = '';
$city_array             = '';
$not_in                 = array();
$not_in[]               = $post->ID;

$listing_page_type    =   get_option('wp_estate_listing_page_type','');

 if($listing_page_type == 1){
    $full_page=0;
 }
////////////////////////////////////////////////////////////////////////////
/// compose taxomomy categ array
////////////////////////////////////////////////////////////////////////////

if ($post_category!=''):
    foreach ($post_category as $item) {
        $items[] = $item->term_id;
    }
    $categ_array=array(
            'taxonomy' => 'property_category',
            'field' => 'id',
            'terms' => $items
        );
endif;

////////////////////////////////////////////////////////////////////////////
/// compose taxomomy action array
////////////////////////////////////////////////////////////////////////////

if ($post_action_category!=''):
    foreach ($post_action_category as $item) {
        $items_actions[] = $item->term_id;
    }
    $action_array=array(
            'taxonomy' => 'property_action_category',
            'field' => 'id',
            'terms' => $items_actions
        );
endif;

////////////////////////////////////////////////////////////////////////////
/// compose taxomomy action city
////////////////////////////////////////////////////////////////////////////

if ($post_city_category!=''):
    foreach ($post_city_category as $item) {
        $items_city[] = $item->term_id;
    }
    $city_array=array(
            'taxonomy' => 'property_city',
            'field' => 'id',
            'terms' => $items_city
        );
endif;

////////////////////////////////////////////////////////////////////////////
/// compose wp_query
////////////////////////////////////////////////////////////////////////////

if(isset($show_sim_two) && $show_sim_two==1){
    $similar_no=2;
}

$args=array(
    'showposts'             => $similar_no,      
    'ignore_sticky_posts'   => 0,
    'post_type'             => 'estate_property',
    'post_status'           => 'publish',
    'post__not_in'          => $not_in,
    'tax_query'             => array(
    'relation'              => 'AND',
                               $categ_array,
                               $action_array,
                               $city_array
                               )
);

global $listing_type;
$listing_type   =   get_option('wp_estate_listing_unit_type','');
$compare_submit =   wpestate_get_compare_link();
$my_query = new WP_Query($args);
   
    if ($my_query->have_posts()) { ?>	
        <div class="similar_listings_wrapper">
            <div class="similar_listings">
                <?php  // get_template_part('templates/compare_list'); ?> 

                <h3 class="agent_listings_title_similar" ><?php esc_html_e('Similar Listings', 'wpestate'); ?></h3>   
                <?php
                $property_unit_slider= esc_html ( get_option('wp_estate_prop_list_slider','') );    
    
                while ($my_query->have_posts()):$my_query->the_post();
                    get_template_part('templates/property_unit');  
                endwhile;
                ?>
            </div>
        </div>
    <?php } //endif have post
    ?>


<?php
wp_reset_query();
?> 
