
<?php
global $current_user;
global $feature_list_array;
global $propid ;
global $post_attachments;
global $options;
global $where_currency;
global $property_description_text;     
global $property_details_text;
global $property_features_text;
global $property_adr_text;  
global $property_price_text;   
global $property_pictures_text;    
global $propid;
global $gmap_lat;  
global $gmap_long;
global $unit;
global $currency;
global $use_floor_plans;
get_template_part('templates/listingslider'); 
get_template_part('templates/property_header2');
?>


<div class="row content-fixed-listing">
    <?php //get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php 
    if ( $options['content_class']=='col-md-12' || $options['content_class']=='none'){
        print 'col-md-8';
    }else{
       print  $options['content_class']; 
    }?> ">
    
        <?php get_template_part('templates/ajax_container'); ?>
        <?php
        while (have_posts()) : the_post();
            $image_id       =   get_post_thumbnail_id();
            $image_url      =   wp_get_attachment_image_src($image_id, 'wpestate_property_full_map');
            $full_img       =   wp_get_attachment_image_src($image_id, 'full');
            $image_url      =   $image_url[0];
            $full_img       =   $full_img [0];     
        ?>
        
        
        <div class="single-content listing-content">
    
            
     
        
      
        <!-- property images   -->   
        <div class="panel-wrapper imagebody_wrapper">
           
            <div class="panel-body imagebody imagebody_new">
                <?php  
                get_template_part('templates/property_pictures');
                ?>
            </div>
            
            
            <div class="panel-body video-body">
                <?php
                $video_id           = esc_html( get_post_meta($post->ID, 'embed_video_id', true) );
                $video_type         = esc_html( get_post_meta($post->ID, 'embed_video_type', true) );

                if($video_id!=''){
                    if($video_type=='vimeo'){
                        echo wpestate_custom_vimdeo_video($video_id);
                    }else{
                        echo wpestate_custom_youtube_video($video_id);
                    }    
                }
                ?>
            </div>
     
        </div>
          
 
          
        <!-- property price   -->   
        <div class="panel-wrapper" id="listing_price">
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseOne"> <span class="panel-title-arrow"></span>
                <?php if($property_price_text!=''){
                    echo $property_price_text;
                } else{
                    esc_html_e('Property Price','wpestate');
                }  ?>
            </a>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    <?php print estate_listing_price($post->ID); ?>
                    <?php  wpestate_show_custom_details($post->ID); ?>
                </div>
            </div>
        </div>
        
        
        
        <div class="panel-wrapper">
            <!-- property address   -->             
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTwo">  <span class="panel-title-arrow"></span>
                <?php if($property_adr_text!=''){
                    echo $property_adr_text;
                } else{
                    esc_html_e('Property Address','wpestate');
                }
                ?>
            </a>    
            <div id="collapseTwo" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    <?php print estate_listing_address($post->ID); ?>
                </div>
            </div>
        </div>
                
        <!-- property details   -->  
        <div class="panel-wrapper">
            <?php                                       
            if($property_details_text=='') {
                print'<a class="panel-title" id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html__( 'Property Details', 'wpestate').'  </a>';
            }else{
                print'<a class="panel-title"  id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.$property_details_text.'  </a>';
            }
            ?>
            <div id="collapseTree" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    <?php print estate_listing_details($post->ID);?>
                </div>
            </div>
        </div>


        <!-- Features and Amenities -->
        <div class="panel-wrapper">
            <?php 

            if ( count( $feature_list_array )!=0 && !count( $feature_list_array )!=1 ){ //  if are features and ammenties
                if($property_features_text ==''){
                    print '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'.esc_html__( 'Amenities and Features', 'wpestate').'</a>';
                }else{
                    print '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'. $property_features_text.'</a>';
                }
                ?>
                <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        <?php print estate_listing_features($post->ID); ?>
                    </div>
                </div>
            <?php
            } // end if are features and ammenties
            ?>
        </div>
        
        <?php
        get_template_part ('/templates/show_avalability');
    
        wp_reset_query();
        ?>  
         
        <?php
        endwhile; // end of the loop
        $show_compare=1;
        ?>
        </div><!-- end single content -->
    </div><!-- end 8col container-->
    
    
    <div class="clearfix visible-xs"></div>
    <div class=" 
        <?php
        if($options['sidebar_class']=='' || $options['sidebar_class']=='none' ){
            print ' col-md-4 '; 
        }else{
            print $options['sidebar_class'];
        }
        ?> 
        widget-area-sidebar listingsidebar" id="primary" >
        <?php // include(locate_template('templates/listing-col.php')); ?>
        <?php  include(locate_template('sidebar-listing.php')); ?>
    </div>
</div>   



<div class="full_width_row">
    
            <?php     get_template_part ('/templates/listing_reviews'); ?>
     
    
    
    <div class="owner-page-wrapper">
        <div class="owner-wrapper  content-fixed-listing row" id="listing_owner">
            <?php get_template_part ('/templates/owner_area'); ?>
        </div>
    </div>
    
    <div class="google_map_on_list_wrapper">    
         
           
        <div id="gmapzoomplus"></div>
        <div id="gmapzoomminus"></div>
        <div id="gmapstreet"></div>
    
        <div id="google_map_on_list" 
            data-cur_lat="<?php   echo $gmap_lat;?>" 
            data-cur_long="<?php echo $gmap_long ?>" 
            data-post_id="<?php echo $post->ID; ?>">
        </div>
    </div>    
    
 
            <?php   get_template_part ('/templates/similar_listings');?>
    

</div>

<?php get_footer(); ?>