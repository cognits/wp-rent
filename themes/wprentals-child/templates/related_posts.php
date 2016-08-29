<?php
global $options;
global $unit_class;
global $row_number_col;  
$tags = wp_get_post_tags($post->ID);
$row_number_col=6;
$unit_class="col-md-6";
if($options['content_class']=='col-md-12'){
    $unit_class="col-md-4";  
    $row_number_col=4;
}


if ($tags) {       
        $first_tag = $tags[0]->term_id;
        $args = array(
            'tag__in'       =>  array($first_tag),
            'post__not_in'  =>  array($post->ID),
            'showposts'     =>  2,
            'meta_query'    =>  array(
                                    array(
                                        'key' => '_thumbnail_id',
                                        'compare' => 'EXISTS'
                                    ),
                                )
        );
        
        wp_reset_query();
        $my_query = new WP_Query($args);
        
        
        if ( $my_query->have_posts() ) { ?>	

            <div class="related_posts blog_list_wrapper row"> 
                <h2><?php esc_html_e('Related Posts', 'wpestate'); ?></h2>   
                <?php
                while ($my_query->have_posts()) {
                    $my_query->the_post();
                    if(has_post_thumbnail() ){
                    get_template_part('templates/blog_unit');   
                   }
                } //end while
                ?>
            </div>		

        <?php } //endif have post
}// end if tags

wp_reset_query();
?>