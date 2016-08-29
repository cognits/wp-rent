<?php
// blog listing
global $options;
global $unit_class;
global $row_number;
global $row_number_col;
global $full_row;
$row_number_col=12;
$thumb_id   =   get_post_thumbnail_id($post->ID);
$preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
$link       =   esc_url(get_permalink());
?>
<div class="col-md-<?php echo $row_number_col;?> new_blog ">
    <div class="blog_unit_back full_blog " data-link="<?php echo $link;?>">
        <?php 
        $title      =   get_the_title();
        if( has_post_thumbnail($post->ID) ){
            if($full_row==1){
                $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_featured');
            }else{
                $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_blog_unit2');
            }
            print '<div class="listing-unit-img-wrapper"> <div class="cross"></div><img src="'.$preview[0].'" class=" b-lazy img-responsive" alt="'.$title.'" ></div>';
        
            
        }else{
            $preview_img = get_template_directory_uri().'/img/defaultimage.jpg';
            //print '<div class="listing-unit-img-wrapper"><img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.$preview_img.'" class=" b-lazy  img-responsive" alt="'.$title.'" ></div>';
            print '<div class="listing-unit-img-wrapper"> <div class="cross"></div><img src="'.$preview_img.'" class=" b-lazy  img-responsive" alt="'.$title.'" ></div>';
        }
       
        ?>
        <div class="blog-title">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="blog-title-link">
            <?php
            $title=get_the_title();
            echo mb_substr( html_entity_decode($title),0,58); 
            if(strlen($title)>58){
                echo '...';   
            }
            ?>
            </a>
            
            <div class="blog-unit-content"> 
                <?php echo  wp_strip_all_tags ( wpestate_the_excerpt_max_charlength(200) );?>
            </div>
            
            <div class="category_tagline">
                <span class="span_widemeta"> <?php print get_the_date('M d, Y');?></span>  
                <span class="span_widemeta"><?php esc_html_e('Category: ','wpestate');the_category(', '); ?></span> 
                <!-- <span class="span_widemeta span_comments"><i class="fa fa-comment-o"></i> <?php //comments_number( '0','1','%');?></span>
                -->
                <span class="span_widemeta span_comments"><?php comments_number( '0','1','%');echo ' ';esc_html_e('Comments','wpestate');?></span>

            </div>
            
        </div>

    </div>    
</div>