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
global $is_widget;
global $row_number_col;
global $full_page;
global $listing_type;
global $property_unit_slider;
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
$title=get_the_title($post->ID);

if(isset($is_shortcode) && $is_shortcode==1 ){
    $col_class='col-md-'.$row_number_col.' shortcode-col';
}

if(isset($is_widget) && $is_widget==1 ){
    $col_class='col-md-12';
    $col_org    =   12;
}

if(isset($full_page) && $full_page==1 ){
    $col_class='col-md-4 ';
    $col_org    =   3;
}

$link           =   esc_url ( get_permalink());
$preview        =   array();
$preview[0]     =   '';
$favorite_class =   'icon-fav-off';
$fav_mes        =   esc_html__( 'add to favorites','wpestate');
if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
    $favorite_class =   'icon-fav-on';   
    $fav_mes        =   esc_html__( 'remove from favorites','wpestate');
    } 
}

$listing_type_class='property_unit_v2';
if($listing_type==1){
    $listing_type_class='';
}
$property_status= stripslashes ( get_post_meta($post->ID, 'property_status', true) );
?>  



<div class="listing_wrapper col-md-12 wide_property property_flex <?php echo $listing_type_class;?>" data-org="<?php echo $col_org;?>" data-listid="<?php echo $post->ID;?>" > 
    <div class="property_listing " data-link="<?php echo $link;?>">
        <?php
  
            $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_full_map');
            $preview   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_listings');
            $compare   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_slider_thumb');
            $extra= array(
                'data-original' =>  $preview[0],
                'class'         =>  'b-lazy img-responsive',    
            );
            
            //$thumb_prop         =  '<img data-src="'.$preview[0].'"  src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="b-lazy img-responsive wp-post-image lazy-hidden" alt="no thumb" />';   
            $thumb_prop         =  '<img src="'.$preview[0].'"   class="b-lazy img-responsive wp-post-image lazy-hidden" alt="no thumb" />';   
          
            if($preview[0] == ''){
                $thumb_prop_default =  get_template_directory_uri().'/img/defaultimage_prop.jpg';
               // $thumb_prop         =  '<img data-src="'.$thumb_prop_default.'"  src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="b-lazy img-responsive wp-post-image" lazy-hidden alt="no thumb" />';   
                $thumb_prop         =  '<img src="'.$thumb_prop_default.'" class="b-lazy img-responsive wp-post-image  lazy-hidden" alt="no thumb" />';   
            }
            
            $featured               =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );
            $property_rooms         =   get_post_meta($post->ID, 'property_bedrooms', true);
            if($property_rooms!=''){
                $property_rooms=intval($property_rooms);
            }
            
            $property_bathrooms     =   get_post_meta($post->ID, 'property_bathrooms', true) ;
            if($property_bathrooms!=''){
                $property_bathrooms=floatval($property_bathrooms);
            }
            
            $property_size          =   get_post_meta($post->ID, 'property_size', true) ;
            if($property_size){
                $property_size=number_format(intval($property_size));
            }
            
            
            $agent_id           =   wpsestate_get_author($post->ID);
            $agent_id           =   get_user_meta($agent_id, 'user_agent_id', true);
            $thumb_id_agent     =   get_post_thumbnail_id($agent_id);
            $preview_agent      =   wp_get_attachment_image_src($thumb_id_agent, 'wpestate_user_thumb');
            $preview_agent_img  =   $preview_agent[0];
            
            if($preview_agent_img==''){
            $preview_agent_img    =   get_template_directory_uri().'/img/default_user_small.png';
            }
            
            $agent_link         =   esc_url( get_permalink($agent_id));
            $measure_sys        =   esc_html ( get_option('wp_estate_measure_sys','') ); 
            
            $price = intval( get_post_meta($post->ID, 'property_price', true) );
            $property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
            $property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
            $property_action    =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');   
            $property_categ     =   get_the_term_list($post->ID, 'property_category', '', ', ', '');   
            ?>
        
          
            <div class="listing-unit-img-wrapper">
                <?php
                if(  $property_unit_slider=='yes'){
                //slider
                    $arguments      = array(
                        'numberposts'       => -1,
                        'post_type'         => 'attachment',
                        'post_mime_type'    => 'image',
                        'post_parent'       => $post->ID,
                        'post_status'       => null,
                        'exclude'           => get_post_thumbnail_id(),
                        'orderby'           => 'menu_order',
                        'order'             => 'ASC'
                    );
                    $post_attachments   = get_posts($arguments);

                    $slides='';

                    $no_slides = 0;
                    foreach ($post_attachments as $attachment) { 
                        $no_slides++;
                        $preview    =   wp_get_attachment_image_src($attachment->ID, 'wpestate_property_listings');
                        $slides     .= '<div class="item lazy-load-item">
                                            <a href="'.$link.'"><img  data-lazy-load-src="'.$preview[0].'" alt="'.$title.'" class="img-responsive" /></a>
                                        </div>';

                    }// end foreach
                    $unique_prop_id=uniqid();
                    print '
                    <div id="property_unit_carousel_'.$unique_prop_id.'" class="carousel property_unit_carousel slide  " data-ride="carousel" data-interval="false">
                        <div class="carousel-inner">         
                            <div class="item active">    
                                <a href="'.$link.'">'.$thumb_prop.'</a>     
                            </div>
                            '.$slides.'
                        </div>


                   

                    <a href="'.$link.'"> </a>';

                    if( $no_slides>0){
                        print '<a class="left  carousel-control" href="#property_unit_carousel_'.$unique_prop_id.'" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>

                        <a class="right  carousel-control" href="#property_unit_carousel_'.$unique_prop_id.'" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>';
                    }
                    print'</div>';
                
           
                }else{ ?>
                
                <div class="cross"></div>
                <a href="<?php echo $link; ?>"><?php echo $thumb_prop; ?></a> 
                <?php } ?>
            </div>
          
                 
            <?php        
            if($featured==1){
                print '<div class="featured_div">'.esc_html__( 'featured','wpestate').'</div>';
            }
            
            if($property_status!='normal' && $property_status!=''){
                print '<div class="property_status status_'.$property_status.'">'.$property_status.'</div>';
            }
            
            ?>
          
            <div class="title-container">
                <div class="price_unit_wrapper">
                    <div class="price_unit"><?php  
                    wpestate_show_price($post->ID,$currency,$where_currency,0);
                    if($is_widget==1){
                        echo '<span class="pernight">'.esc_html__( 'per night','wpestate').'</span>';
                    }
                    ?></div> 
                </div>
                
                <a href="<?php  echo $agent_link;?>" class="owner_thumb" style="background-image: url('<?php echo $preview_agent_img;?>')"></a>
           
                <div class="category_name">
                    <a href="<?php echo $link;?>" class="listing_title_unit">

                        <?php 
                        // print ''.get_post_meta($post->ID, 'prop_featured', true).'-';
                      
                        echo mb_substr ( html_entity_decode ($title), 0, 36, "UTF8") ; 
                        if(strlen($title)>36){
                            echo '...';   
                        } 
                    ?></a>
                    
                    <div class="listing_content">
                        
                       <?php print wpestate_strip_words( get_the_excerpt(),15).' ...'; ?>
                    </div>
                    
                </div>
            </div>    
                <div class="category_tagline_wrapper">
                    <div class="category_tagline">
                        <img src="<?php echo get_template_directory_uri() ;?>/img/unit_pin.png"  alt="location">
                       
                        <?php  
                        if ($property_area != '') {
                            echo $property_area.', ';
                        } 
                        echo $property_city;?>
                    </div>
                    
                    <div class="category_tagline">
                        <img src="<?php echo get_template_directory_uri() ;?>/img/unit_category.png"  alt="location">
                        <?php echo $property_categ.' / '.$property_action;?>
                    </div>
             
                
                    <div class="property_unit_action">
                        <span class="icon-fav <?php echo $favorite_class; ?>" data-original-title="<?php echo $fav_mes; ?>" data-postid="<?php echo $post->ID; ?>"><i class="fa fa-heart"></i></span>
                    </div>   
                </div>
            
            
        
        <?php 
 
        if ( isset($show_remove_fav) && $show_remove_fav==1 ) {
            print '<span class="icon-fav icon-fav-on-remove" data-postid="'.$post->ID.'"> '.$fav_mes.'</span>';
        }
        ?>

        </div>          
    </div>