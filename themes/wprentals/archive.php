<?php
// Archive
// Wp Estate Pack
global $row_number_col;   
get_header();
$options    =   wpestate_page_details('');
$unit_class =   "col-md-6";
$row_number_col=6;

if($options['content_class'] == "col-md-12"){
    $unit_class="col-md-4";    
    $row_number_col=4;
}



if ( 'wpestate_message' == get_post_type() || 'wpestate_invoice' == get_post_type() || 'wpestate_booking' == get_post_type() ){
    exit();
}
?>



<div class="row content-fixed">
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php print $options['content_class'];?> ">
  
        <h1 class="entry-title"> 
            <?php 
            if (is_category() ) {
                   printf(esc_html__( 'Category Archives: %s', 'wpestate'), '<span>' . single_cat_title('', false) . '</span>');
            }else if (is_day()) {
                   printf(esc_html__( 'Daily Archives: %s', 'wpestate'), '<span>' . get_the_date() . '</span>'); 
            } elseif (is_month()) {
                   printf(esc_html__( 'Monthly Archives: %s', 'wpestate'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'wpestate')) . '</span>'); 
            } elseif (is_year()) {
                   printf(esc_html__( 'Yearly Archives: %s', 'wpestate'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'wpestate')) . '</span>');
            } else {
               esc_html_e('Blog Archives', 'wpestate'); 
            }
            ?>
        </h1>
             
        <div class="blog_list_wrapper row">    
            <?php
            while (have_posts()) : the_post(); 
                get_template_part('templates/blog_unit');
            endwhile;
            wp_reset_query();
            ?>
            
        </div>
        <?php kriesi_pagination('', $range = 2); ?>     
    </div><!-- end 8col container-->
    
<?php  include(locate_template('sidebar.php')); ?>
</div>   

<?php get_footer(); ?>