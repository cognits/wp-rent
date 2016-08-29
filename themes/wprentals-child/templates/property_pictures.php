 <?php
    global $post_attachments;
    global $post;
    $count=0;
    
    $total_pictures=count ($post_attachments);
    
    foreach ($post_attachments as $attachment) {
        $count++;
     
        if($count>5){
          //  break;
        }
        
        if($total_pictures<=4){
            $new_total_pictures=$total_pictures;
            if($total_pictures==4){
                $new_total_pictures=3;
            }
            
            $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_listings_page');
            if($count!=4){
                if( $count <= $new_total_pictures-1){
                    print '<div class="col-md-4 image_gallery" style="background-image:url('.$full_prty[0].')"> <div class="img_listings_overlay" ></div> </div>';
                }else{
                    print '<div class="col-md-4 image_gallery" style="background-image:url('.$full_prty[0].')">
                   <div class="img_listings_overlay img_listings_overlay_last" ></div>
                   <span class="img_listings_mes">'.esc_html__( 'See all','wpestate').' '. $total_pictures .' '.esc_html__( 'photos','wpestate').'</span></div>';
                }
            }
        }
        
        if( $total_pictures> 4){
            if($count <= 3 ){
                $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_places');
                print '<div class="col-md-4 image_gallery" style="background-image:url('.$full_prty[0].')"> <div class="img_listings_overlay" ></div> </div>';
          
            }
            if($count ==4 ){
                $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_places');
                print '<div class="col-md-8 image_gallery" style="background-image:url('.$full_prty[0].')  ">   <div class="img_listings_overlay" ></div></div>';
            }

            if($count ==5 ){
                $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_listings_page');
                print '<div class="col-md-4 image_gallery" style="background-image:url('.$full_prty[0].')  ">
                    <div class="img_listings_overlay img_listings_overlay_last" ></div>
                    <span class="img_listings_mes">'.esc_html__( 'See all','wpestate').' '. $total_pictures .' '.esc_html__( 'photos','wpestate').'</span></div>';
            }
        }
    
        
    }

    
    
    if($count!=0){ ?>
    <?php
     /*   
   */
    }
    ?>