<?php
// Template Name: Blog list page
// Wp Estate Pack
get_header();
$options            =   wpestate_page_details($post->ID);
global $row_number_col;       
global $full_row;
$unit_class="col-md-6";
$row_number_col=6;
$full_row=0;

if($options['content_class'] == "col-md-12"){
    $unit_class="col-md-4";    
    $row_number_col=4;
    $full_row=1;
}
?>


<div class="row content-fixed">
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php print $options['content_class'];?> ">
        <?php get_template_part('templates/ajax_container'); ?>
        <?php while (have_posts()) : the_post(); ?>
        <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) == 'yes') { ?>
              <h1 class="entry-title title_prop"><?php the_title(); ?></h1>
        <?php } ?>
        <div class="single-content blog-list-content"><?php the_content();?></div>   
        <?php endwhile; // end of the loop.  ?>  

              
        <div class="blog_list_wrapper row">    
        <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 0;
            $args = array(
                'post_type' => 'post',
                'paged'     => $paged,
                'status'    =>'published'
            );

            $blog_selection = new WP_Query($args);
            
            while ($blog_selection->have_posts()): $blog_selection->the_post();
                get_template_part('templates/blog_unit_new');            
            endwhile;
            wp_reset_query();
        ?>
        
           
        </div>
        <?php kriesi_pagination($blog_selection->max_num_pages, $range = 2); ?>    
    </div><!-- end 8col container-->
    
<?php  include(locate_template('sidebar.php')); ?>
</div>   

<?php get_footer(); ?>