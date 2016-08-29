<?php
global $options;
global $unit_class;
$thumb_id           = get_post_thumbnail_id($post->ID);
$preview            = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_blog_unit');
$name               = get_the_title();
$link               = esc_url(get_permalink());



//$thumb_prop = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.$preview[0].'" alt="agent-images" class="b-lazy">';
$thumb_prop = '<img src="'.$preview[0].'" alt="agent-images" class="b-lazy">';

if($preview[0]==''){
    //$thumb_prop = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.get_template_directory_uri().'/img/default_user.png" class="b-lazy" alt="agent-images">';
    $thumb_prop = '<img src="'.get_template_directory_uri().'/img/default_user.png" class="b-lazy" alt="agent-images">';   
}

$col_class=4;
if($options['content_class']=='col-md-12'){
    $col_class=3;
}    
?>

<div class="<?php echo $unit_class;?> agent-flex property_flex">
    <div class="agent_unit" data-link="<?php print $link;?>">
        <div class="agent-unit-img-wrapper">
            <?php print $thumb_prop; ?>
        </div>
        
        <div class="agent-title">
            <?php print '<h4> <a href="' . $link . '" class="agent-title-link">' . $name. '</a></h4>';?>
        
            <div class="category_tagline">    
                <?php
                $where = esc_html(get_post_meta($post->ID, 'live_in', true));
                echo esc_html__( 'Lives in','wpestate');echo': ';
                if ($where==''){
                    esc_html_e('non disclosed','wpestate');
                }else{
                    echo $where;
                }
                ?>
            </div> 

        </div>     
    </div>
</div>   