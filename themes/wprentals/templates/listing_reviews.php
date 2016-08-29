<?php 
$args = array(
    'number' => '15',
    'post_id' => $post->ID, // use post_id, not post_ID
);


$comments   =   get_comments($args);
$coments_no =   0;
$stars_total=   0;
$review_templates=' ';

foreach($comments as $comment) :
    $coments_no++;
    
    $userId         =   $comment->user_id;
    if($userId == 1){
        $reviewer_name="admin";
        $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
    }else{
        $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
        $reviewer_name  =   get_the_title($userid_agent); 
    }
  
    
    $thumb_id           = get_post_thumbnail_id($userid_agent);
    $preview            = wp_get_attachment_image_src($thumb_id, 'thumbnail');
    $preview_img         = $preview[0];
    if($preview_img==''){
        $preview_img    =   get_template_directory_uri().'/img/default_user_agent.gif';
    }
    
    $rating= get_comment_meta( $comment->comment_ID , 'review_stars', true );
    $stars_total+=$rating;
    $review_templates.='  
         <div class="listing-review">
                     

                        <div class="col-md-8 review-list-content norightpadding">
                            <div class="reviewer_image"  style="background-image: url('.$preview_img.');"></div>
                          
                            <div class="reviwer-name">'.$reviewer_name.'</div>
                            
                            <div class="review-date">
                                '.esc_html__( 'Posted on ','wpestate' ). ' '. get_comment_date('j F Y',$comment->comment_ID).' 
                            </div>
                            
                            <div class="property_ratings">';

                                $counter=0; 
                                    while($counter<5){
                                        $counter++;
                                        if( $counter<=$rating ){
                                            $review_templates.=' <i class="fa fa-star"></i>';
                                        }else{
                                           $review_templates.=' <i class="fa fa-star-o"></i>'; 
                                        }

                                    }
                            $review_templates.=' <span class="ratings-star">('. $rating.' ' .esc_html__( 'of','wpestate').' 5)</span> 
                            </div>


                            <div class="review-content">
                                '. $comment->comment_content .'

                                
                            </div>



                        </div>
                    </div>       ';

endforeach;
?>




<?php 
if($coments_no>0){
    $list_rating= ceil($stars_total/$coments_no);
  
?>
<div class="property_page_container for_reviews">
    <div class="listing_reviews_wrapper">
            <div class="listing_reviews_container">
                <h3 id="listing_reviews" class="panel-title">
                        <?php
                        print $coments_no.' ';
                        esc_html_e('Reviews', 'wpestate');
                        ?>
                        <span class="property_ratings">
                             <?php 
                            $counter=0; 
                                while($counter<5){
                                    $counter++;
                                    if( $counter<=$list_rating ){
                                        print '<i class="fa fa-star"></i>';
                                    }else{
                                        print '<i class="fa fa-star-o"></i>'; 
                                    }

                                }
                            ?>
                        </span>
                </h3>

                <?php     print $review_templates; ?>   
        </div>
    </div>
</div>
<?php } ?>