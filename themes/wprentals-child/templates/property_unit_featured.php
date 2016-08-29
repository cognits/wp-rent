<?php
global $curent_fav;
global $currency;
global $where_currency;
global $show_compare;
global $show_compare_only;
global $show_remove_fav;
global $options;
global $isdashabord;
global $align;
global $align_class;
global $is_shortcode;
global $row_number_col;

$pinterest          =   '';
$previe             =   '';
$compare            =   '';
$extra              =   '';
$property_size      =   '';
$property_bathrooms =   '';
$property_rooms     =   '';
$measure_sys        =   '';

$col_class  =   'col-md-6';
$col_org    =   4;
 

if(isset($is_shortcode) && $is_shortcode==1 ){
    $col_class='col-md-'.$row_number_col.' shortcode-col';
}

$link           =   esc_url(get_permalink());
$preview        =   array();
$preview[0]     =   '';
$property_status= stripslashes ( get_post_meta($post->ID, 'property_status', true) );
?>  



<div class="listing_wrapper" data-org="12" data-listid="<?php echo $post->ID;?>" > 
    <div class="property_listing" data-link="<?php echo $link;?>">
        <?php
        if ( has_post_thumbnail() ):
            $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_full_map');
            $preview   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_listings');
            $compare   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_slider_thumb');
            $extra= array(
                'data-original' =>  $preview[0],
                'class'         =>  'lazyload img-responsive',    
            );
       
         
            $thumb_prop             =   get_the_post_thumbnail($post->ID, 'wpestate_property_listings',$extra);
            $thumb_id               =   get_post_thumbnail_id($post->ID);
            $thumb_prop_url         =   wp_get_attachment_image_src($thumb_id,'wpestate_property_featured');
            $prop_stat              =   stripslashes ( esc_html( get_post_meta($post->ID, 'property_status', true) ) );
            $featured               =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );
            
            $agent_id           =   intval( get_post_meta($post->ID, 'property_agent', true) );
            $thumb_id_agent     =   get_post_thumbnail_id($agent_id);
            $preview_agent      =   wp_get_attachment_image_src($thumb_id_agent, 'wpestate_user_thumb');
            $preview_agent_img  =   $preview_agent[0];
            $agent_link         =   esc_url(get_permalink($agent_id));
            $measure_sys        =   esc_html ( get_option('wp_estate_measure_sys','') ); 
            
            $price = intval( get_post_meta($post->ID, 'property_price', true) );
            $property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
            $property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
            $price_label        =   '<span class="price_label">'.esc_html ( get_post_meta($post->ID, 'property_label', true) ).'</span>';
        
        
        
            if ($price != 0) {
               $price = number_format($price);

               if ($where_currency == 'before') {
                   $price = $currency . ' ' . $price;
               } else {
                   $price = $price . ' ' . $currency;
               }
            }else{
                $price='';
            }
        
            print   '<div class="listing-hover-gradient"></div><div class="listing-hover" ></div>';
            
            print   '<div class="listing-unit-img-wrapper" style="background-image:url('.$thumb_prop_url[0].')"></div>';
           
            if($property_status!='normal' && $property_status!=''){
                print '<div class="property_status status_'.$property_status.'">'.$property_status.'</div>';
            }
            
            print   '<div class="category_name"><a class="featured_listing_title" href="'.$link.'">';
            $title=get_the_title();
            echo mb_substr( html_entity_decode($title), 0, 40); 
            if(strlen($title)>40){
                echo '...';   
            }
            
            print   '</a><div class="category_tagline">';
            if ($property_area != '') {
                echo $property_area.', ';
            }       
            print $property_city.'</div>';
           
           
            print '</div>';
          
        endif;
        
        ?>
      </div>          
    </div>