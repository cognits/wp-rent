<?php
// Index
// Wp Estate Pack
get_header();
$options            =   wpestate_page_details($post->ID);
global $row_number_col;       
$unit_class="col-md-6";
$row_number_col=6;

if($options['content_class'] == "col-md-12"){
    $unit_class="col-md-4";    
    $row_number_col=4;
}

?>



<div class="row content-fixed">
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php print $options['content_class'];?> ">
        <?php get_template_part('templates/ajax_container'); ?>
        
        <div class="blog_list_wrapper row indexlist">    
        <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 0;
            $args = array(
                'post_type' => 'post',
                'paged'     => $paged,
                'status'    =>'published'
            );

            $blog_selection = new WP_Query($args);
            
            while ($blog_selection->have_posts()): $blog_selection->the_post();
                get_template_part('templates/blog_unit');
                         
            endwhile;
            wp_reset_query();
        ?>
        
           
        </div>
        <?php kriesi_pagination($blog_selection->max_num_pages, $range = 2); ?>    
    </div><!-- end 8col container-->
    
<?php  include(locate_template('sidebar.php')); ?>
</div>   

<?php get_footer(); ?>