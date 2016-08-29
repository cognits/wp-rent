<?php
// Template Name: Terms and Conditions
// Wp Estate Pack
global $post;
get_header(); 
$options=wpestate_page_details($post->ID); 
?>

<div class="row">
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class="col-xs-12 <?php print $options['content_class'];?> ">
        
        <?php get_template_part('templates/ajax_container'); ?>        
        <?php while (have_posts()) : the_post(); ?>
            <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php } ?>
            <div class="single-content"><?php the_content();?></div><!-- single content-->
        <!-- #comments start-->
        <?php comments_template('', true);?> 	
        <!-- end comments -->   
        <?php endwhile; // end of the loop. ?>
    </div>
<?php  include(locate_template('sidebar.php')); ?>
</div>   
<?php get_footer(); ?>