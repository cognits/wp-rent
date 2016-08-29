<?php
// Search
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
$row_number_col=4;
?>



<div class="row content-fixed">
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class=" col-md-12 ">
  
        <div class="blog_list_wrapper row">    
            <?php
                if (have_posts()){
                    print ' <h1 class="entry-title-search">'. esc_html__(  'Search Results for: ','wpestate');print '"' . get_search_query() . '"'.'</h1>';
                    while (have_posts()) : the_post(); 
                        get_template_part('templates/blog_unit');                
                    endwhile;
                }else{
                ?>
                    <h1 class="entry-title-search"><?php esc_html_e( 'We didn\'t find any results. Please try again with different search parameters. ', 'wpestate' ); ?></h1>
                    <form method="get" class="searchform" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <input type="text" class="form-control search_res_form" name="s" id="s" value="<?php esc_attr (esc_html_e( 'Type Keyword', 'wpestate' )); ?>" />
                        <input type="submit" id="submit-form" class="wpb_btn-info wpb_regularsize wpestate_vc_button  vc_button" value="<?php esc_attr ( esc_html_e( 'Search', 'wpestate') ); ?>">
                        <?php
                        if (function_exists('icl_translate') ){
                            print do_action( 'wpml_add_language_form_field' );
                        }
                        ?>
                    </form>

                <?php
                }
                wp_reset_query();
            ?>
            
        </div>
        <?php kriesi_pagination('', $range = 2); ?>     
    </div><!-- end 8col container-->
    
<?php // include(locate_template('sidebar.php')); ?>
</div>   

<?php get_footer(); ?>