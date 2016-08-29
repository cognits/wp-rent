<!-- Google Map -->
<?php
global $post;
if( isset($post->ID) ){
    $gmap_lat           =   floatval( get_post_meta($post->ID, 'property_latitude', true));
    $gmap_long          =   floatval( get_post_meta($post->ID, 'property_longitude', true));
    $property_add_on    =   ' data-post_id="'.$post->ID.'" data-cur_lat="'.$gmap_lat.'" data-cur_long="'.$gmap_long.'" ';
    $closed_height      =   wpestate_get_current_map_height($post->ID);
    $open_height        =   wpestate_get_map_open_height($post->ID);
    $open_close_status  =   wpestate_get_map_open_close_status($post->ID);
    
}else{
    $gmap_lat           =   esc_html( get_option('wp_estate_general_latitude','') );
    $gmap_long          =   esc_html( get_option('wp_estate_general_longitude','') );
    $property_add_on    =   ' data-post_id="" data-cur_lat="'.$gmap_lat.'" data-cur_long="'.$gmap_long.'" ';
    $closed_height      =   intval (get_option('wp_estate_min_height',''));
    $open_height        =   get_option('wp_estate_max_height','');
    $open_close_status  =   esc_html( get_option('wp_estate_keep_min','' ) ); 
}

?>



<div id="gmap_wrapper"  <?php print $property_add_on; ?> style="height:<?php print $closed_height;?>px"  >
    <span id="isgooglemap" data-isgooglemap="1"></span>       
   
    <div id="gmap-controls-wrapper">
        <div id="gmapzoomplus"></div>
        <div id="gmapzoomminus"></div>
    
        <div  id="geolocation-button"></div>
        <div  id="gmap-full"></div>
        <div  id="gmap-prev"></div>
        <div  id="gmap-next" ></div>
    </div>
    
    
    <?php
        $street_view_class=" ";
        if(  get_option('wp_estate_show_g_search','') ==='yes'){
            $street_view_class=" lower_street ";
        }
    ?>


 
        
        
        
    <div id="googleMap"  style="height:<?php print $closed_height;?>px">   
    </div>    
    
   
  
   <div class="tooltip"> <?php esc_html_e('click to enable zoom','wpestate');?></div>
   <div id="gmap-loading"><?php esc_html_e('Loading Maps','wpestate');?>
        <div class="loader-inner ball-pulse"  id="listing_loader_maps">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
   </div>
   
   
   <div id="gmap-noresult">
       <?php esc_html_e('We didn\'t find any results','wpestate');?>
   </div>
   
   <div class="gmap-controls">
        <?php
        // show or not the open close map button
        if( isset($post->ID) ){
            if (wpestate_get_map_open_close_status($post->ID) == 0 ){
                print ' <div id="openmap"><i class="fa fa-angle-down"></i>'.esc_html__( 'open map','wpestate').'</div>';
            }
        }else{
            if( esc_html( get_option('wp_estate_keep_min','' ) )==0){
                print ' <div id="openmap"><i class="fa fa-angle-down"></i>'.esc_html__( 'open map','wpestate').'</div>';
            }
        }
        ?>
   
    
        
       
   </div>
 

</div>    
<!-- END Google Map --> 