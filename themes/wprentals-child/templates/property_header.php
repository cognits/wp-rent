<?php
global $post;
global $property_action_terms_icon;
global $property_action;
global $property_category_terms_icon;
global $property_category;
global $guests;
global $bedrooms;
global $favorite_text;
global $favorite_class;
global $options;
?>--><div class="property_header ">
        <div class="property_categs ">
            
            <div class="property_header_wrapper 
                <?php 
                if ( $options['content_class']=='col-md-12' || $options['content_class']=='none'){
                    print 'col-md-8';
                }else{
                   print  $options['content_class']; 
                }?> 
            ">
            
                <div class="category_wrapper ">
                    <div class="category_icon_wrapper">
                        <div class="category_icon">
                            <img src="<?php print $property_action_terms_icon;?>" alt="category" class="retina_ready"  height="25"></div>
                        <div class="category_text"><?php echo $property_action; ?></div>
                    </div>

                    <div class="category_icon_wrapper">
                        <div class="category_icon"><img src="<?php print $property_category_terms_icon;?>" alt="category" height="25" class="retina_ready"></div>
                        <div class="category_text"><?php echo $property_category;?></div>
                    </div>

                    <div class="category_icon_wrapper">
                        <div class="category_icon"><img src="<?php print get_template_directory_uri().'/img/guest_icon.png';?>" height="25" alt="guests" class="retina_ready"></div>
                        <div class="category_text"><?php print $guests.' '. esc_html__( 'Guests','wpestate');?></div>
                    </div>

                    <div class="category_icon_wrapper">
                        <div class="category_icon"><img src="<?php print get_template_directory_uri().'/img/beds_icon.png';?>" height="25" alt="bedrooms" class="retina_ready"></div>
                        <div class="category_text"><?php print $bedrooms.' '.esc_html__( 'Bedrooms','wpestate');?></div>
                    </div>
                </div>
                
                
                <div id="listing_description">
                <?php
                    $content = get_the_content();
                    $content = apply_filters('the_content', $content);
                    $content = str_replace(']]>', ']]&gt;', $content);
                    
                    $property_description_text =  get_option('wp_estate_property_description_text');
                    if (function_exists('icl_translate') ){
                        $property_description_text     =   icl_translate('wpestate','wp_estate_property_description_text', esc_html( get_option('wp_estate_property_description_text') ) );
                    }
                    
                    if($content!=''){   
                        print '<h4 class="panel-title-description">'.$property_description_text.'</h4>';
                        print '<div class="panel-body">'.$content.'</div>';       
                    }
                ?>
                </div>
                
                
               
               
                <div id="view_more_desc"><?php esc_html_e('View more','wpestate');?></div>
            
        </div>
    <?php  
        $post_id=$post->ID; 
        $guest_list= wpestate_get_guest_dropdown('noany');
    ?>
    
                
                
                
    
    <div class="booking_form_request  
        <?php
        if($options['sidebar_class']=='' || $options['sidebar_class']=='none' ){
            print ' col-md-4 '; 
        }else{
            print $options['sidebar_class'];
        }
        ?>
         " id="booking_form_request">
        <div id="booking_form_request_mess"></div>
            <h3 ><?php esc_html_e('Book Now','wpestate');?></h3>
             
                <div class="has_calendar calendar_icon">
                    <input type="text" id="start_date" placeholder="<?php esc_html_e('Check in','wpestate'); ?>"  class="form-control calendar_icon" size="40" name="start_date" value="">
                </div>

                <div class=" has_calendar calendar_icon">
                    <input type="text" id="end_date" disabled placeholder="<?php esc_html_e('Check Out','wpestate'); ?>" class="form-control calendar_icon" size="40" name="end_date" value="">
                </div>

                <div class=" has_calendar guest_icon ">
                    <?php 
                    $max_guest = get_post_meta($post_id,'guest_no',true);
                    print '
                    <div class="dropdown form-control">
                        <div data-toggle="dropdown" id="booking_guest_no_wrapper" class="filter_menu_trigger" data-value="all">
                            '. esc_html__( 'Guests','wpestate').' 
                            <span class="caret caret_filter"></span>
                        </div>           
                        <input type="hidden" name="booking_guest_no"  value="">
                        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="booking_guest_no_wrapper">
                            '.$guest_list.'
                        </ul>        
                    </div>';
                    ?> 
                </div>
            

                <p class="full_form " id="add_costs_here"></p>            

                <input type="hidden" id="listing_edit" name="listing_edit" value="<?php echo $post_id;?>" />

                <div class="submit_booking_front_wrapper">
                    <?php   
                    $overload_guest                 =   floatval   ( get_post_meta($post_id, 'overload_guest', true) );
                    $price_per_guest_from_one       =   floatval   ( get_post_meta($post_id, 'price_per_guest_from_one', true) );
                    ?>
                    <input type="submit" id="submit_booking_front" data-maxguest="<?php echo $max_guest; ?>" data-overload="<?php echo $overload_guest;?>" data-guestfromone="<?php echo $price_per_guest_from_one; ?>"  class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" value="<?php esc_html_e('Book Now','wpestate');?>" />
                    <?php wp_nonce_field( 'booking_ajax_nonce', 'security-register-booking_front' );?>
                </div>

                <div class="third-form-wrapper">
                    <div class="col-md-6 reservation_buttons">
                        <div id="add_favorites" class=" <?php print $favorite_class;?>" data-postid="<?php the_ID();?>">
                            <?php echo $favorite_text;?>
                        </div>                 
                    </div>

                    <div class="col-md-6 reservation_buttons">
                        <div id="contact_host" class="col-md-6"  data-postid="<?php the_ID();?>">
                            <?php esc_html_e('Contact Owner','wpestate');?>
                        </div>  
                    </div>
                </div>
                
                <?php 
                if (has_post_thumbnail()){
                    $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'wpestate_property_full_map');
                }
                ?>

                <div class="prop_social">
                    <span class="prop_social_share"><?php esc_html_e('Share','wpestate');?></span>
                    <a href="http://www.facebook.com/sharer.php?u=<?php echo esc_url(get_permalink()); ?>&amp;t=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_facebook"><i class="fa fa-facebook fa-2"></i></a>
                    <a href="http://twitter.com/home?status=<?php echo urlencode(get_the_title() .' '. esc_url ( get_permalink()) ); ?>" class="share_tweet" target="_blank"><i class="fa fa-twitter fa-2"></i></a>
                    <a href="https://plus.google.com/share?url=<?php echo esc_url(get_permalink()); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank" class="share_google"><i class="fa fa-google-plus fa-2"></i></a> 
                    <?php if (isset($pinterest[0])){ ?>
                        <a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&amp;media=<?php echo $pinterest[0];?>&amp;description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_pinterest"> <i class="fa fa-pinterest fa-2"></i> </a>      
                    <?php } ?>           
                </div>             

        </div>
    
    
    
    
     </div>
</div>