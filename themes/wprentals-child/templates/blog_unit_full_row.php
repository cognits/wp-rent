<?php
// blog listing
global $options;
global $unit_class;
global $row_number;
$thumb_id   =   get_post_thumbnail_id($post->ID);
$preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
$link       =   esc_url(get_permalink());
?>

<div class="places_wrapper   places_wrapper<?php echo $row_number;?>" data-link="<?php echo $link;?>">
    <div class="places<?php echo $row_number;?>">
        <?php 
        $title      =   get_the_title();
        $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_featured');

        print   '<div class="listing-hover-gradient"></div><div class="listing-hover" ></div>';
        print   '<div class="listing-unit-img-wrapper shortcodefull" style="background-image:url('.$preview[0].')"></div>';

        ?>
    </div>
    
   
    <a href="<?php echo esc_url(get_permalink()); ?>" class="blog-title-link"><?php print get_the_title(); ?></a>
    
    
</div>