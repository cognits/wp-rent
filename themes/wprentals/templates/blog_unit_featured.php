<?php
// blog listing
global $options;
global $unit_class;
global $design_class;
$thumb_id   =   get_post_thumbnail_id($post->ID);
$preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
$link       =   esc_url(get_permalink());
?>

<div class=" blog_featured <?php echo  $design_class; ?>">
    <div class="blog_unit places1" data-link="<?php print $link;?>"> 
        <?php 
        $title      =   get_the_title();
        $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_featured');
        print   '<div class="listing-hover-gradient"></div><div class="listing-hover" ></div>';
        print   '<div class="listing-unit-img-wrapper shortcodefull" style="background-image:url('.$preview[0].')"></div>';

        ?>
    </div>

    <div class="blog-title">
      <a href="<?php echo esc_url(get_permalink()); ?>" class="blog-title-link"><?php print get_the_title(); ?></a>
    </div>

</div>