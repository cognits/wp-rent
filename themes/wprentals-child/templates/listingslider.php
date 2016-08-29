<?php
global $post_attachments;
global $post;
$post_thumbnail_id  =   get_post_thumbnail_id( $post->ID );
$preview            =   wp_get_attachment_image_src($post_thumbnail_id, 'full');
$currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
$where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$price              =   intval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label        =   esc_html ( get_post_meta($post->ID, 'property_label', true) );  
$property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');

              

?>

<div class="listing_main_image" id="listing_main_image_photo" style="background-image: url('<?php echo $preview[0];?>')">
    
 
    <div id="tooltip-pic"> <?php esc_html_e('click to see all images','wpestate');?></div>
    <h1 class="entry-title entry-prop"><?php the_title(); ?>
    
    
        <span class="property_ratings listing_slider">
            <?php 
            $args = array(
                'number' => '15',
                'post_id' => $post->ID, // use post_id, not post_ID
            );
            $comments   =   get_comments($args);
            $coments_no =   0;
            $stars_total=   0;

            foreach($comments as $comment) :
                $coments_no++;
                $rating= get_comment_meta( $comment->comment_ID , 'review_stars', true );
                $stars_total+=$rating;
            endforeach;

            if($stars_total!= 0 && $coments_no!=0){
                $list_rating= ceil($stars_total/$coments_no);
                $counter=0; 
                while($counter<5){
                    $counter++;
                    if( $counter<=$list_rating ){
                        print '<i class="fa fa-star"></i>';
                    }else{
                        print '<i class="fa fa-star-o"></i>'; 
                    }

                }
            }  
            ?>         
        </span> 
    </h1> 
    <div class="listing_main_image_location">
        <?php print  $property_city.', '.$property_area; ?>        
    </div>    
    
    <div class="listing_main_image_price">
        <?php  
            wpestate_show_price($post->ID,$currency,$where_currency,0); 
            $price          = intval( get_post_meta($post->ID, 'property_price', true) );
            if($price!=0){
                echo ' '.esc_html__( 'per night','wpestate'); 
            }
          
        ?>
    </div>
    
     <div class="listing_main_image_text_wrapper"></div> 
    
    <div class="hidden_photos">
        <?php
       
        print ' <a href="'. $preview[0].'"  rel="data-fancybox-thumb" class="fancybox-thumb prettygalery listing_main_image" > 
                    <img  src="'. $preview[0].'" data-original="'. $preview[0].'"  class="img-responsive" alt="gallery" />
                </a>';
            
        $arguments      = array(
                            'numberposts'   =>  -1,
                            'post_type'     =>  'attachment',
                            'post_mime_type'=>  'image',
                            'post_parent'   =>  $post->ID,
                            'post_status'   =>  null,
                            'exclude'       =>  $post_thumbnail_id,
                            'orderby'         => 'menu_order',
                            'order'           => 'ASC'
                      
                        );
 
        $post_attachments   = get_posts($arguments);
        foreach ($post_attachments as $attachment) {
            $full_prty          = wp_get_attachment_image_src($attachment->ID, 'full');
            print ' <a href="'.$full_prty[0].'" rel="data-fancybox-thumb" class="fancybox-thumb prettygalery listing_main_image" > 
                        <img  src="'. $full_prty[0].'" data-original="'.$full_prty[0].'" alt="'.$attachment->post_excerpt.'" class="img-responsive " />
                    </a>';

        }
        ?>
    </div>
    
</div><!--