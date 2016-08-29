<?php
// Sigle - Blog post
// Wp Estate Pack
get_header();
$options=wpestate_page_details($post->ID); 
global $more;
$more = 0;

if ( 'wpestate_message' == get_post_type() || 'wpestate_invoice' == get_post_type() || 'wpestate_booking' == get_post_type() ){
    exit();
}
?>

<div id="post" <?php post_class('row content-fixed');?>>
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php print $options['content_class'];?> ">
        <?php get_template_part('templates/ajax_container'); ?>      
        <div class="single-content single-blog">
            <?php      
             
            while ( have_posts() ) : the_post();
            if (esc_html( get_post_meta($post->ID, 'post_show_title', true) ) != 'no') { ?> 
               
                <h1 class="entry-title single-title" ><?php the_title(); ?></h1>
                
                <div class="meta-element-head"> 
                    <?php print ''.esc_html__( 'Published on','wpestate').' '.the_date('', '', '', FALSE).' '.esc_html__( 'by', 'wpestate').' '.get_the_author();  ?>
                </div>
        
            <?php 
            } 
            get_template_part('templates/postslider');
            if (has_post_thumbnail()){
                $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'wpestate_property_full_map');
            }
      
            the_content('Continue Reading');                     
            $args = array(
                'before'           => '<p>' . esc_html__( 'Pages:','wpestate'),
                'after'            => '</p>',
                'link_before'      => '',
                'link_after'       => '',
                'next_or_number'   => 'number',
                'nextpagelink'     => esc_html__( 'Next page','wpestate'),
                'previouspagelink' => esc_html__( 'Previous page','wpestate'),
                'pagelink'         => '%',
                'echo'             => 1
            ); 
            wp_link_pages( $args ); 
            ?>  
            
            <div class="meta-info"> 
                <div class="meta-element">
                    <?php print '<strong>'.esc_html__( 'Category','wpestate').': </strong>';the_category(', ')?>
                </div>
             
            
                <div class="prop_social_single">
                    <a href="http://www.facebook.com/sharer.php?u=<?php echo esc_url(get_permalink()); ?>&amp;t=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_facebook"><i class="fa fa-facebook fa-2"></i></a>
                    <a href="http://twitter.com/home?status=<?php echo urlencode(get_the_title() .' '. esc_url(get_permalink())); ?>" class="share_tweet" target="_blank"><i class="fa fa-twitter fa-2"></i></a>
                    <a href="https://plus.google.com/share?url=<?php echo esc_url(get_permalink()); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank" class="share_google"><i class="fa fa-google-plus fa-2"></i></a> 
                    <?php if (isset($pinterest[0])){ ?>
                        <a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&amp;media=<?php echo $pinterest[0];?>&amp;description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_pinterest"> <i class="fa fa-pinterest fa-2"></i> </a>      
                    <?php } ?>
                </div>
            </div> 
        </div>    
     
            
        <!-- #related posts start-->    
        <?php  get_template_part('templates/related_posts');?>    
        <!-- #end related posts -->   
        
        <!-- #comments start-->
        <?php comments_template('', true);?> 	
        <!-- end comments -->   
        
        <?php endwhile; // end of the loop. ?>
    </div>
       
<?php  include(locate_template('sidebar.php')); ?>
</div>   

<?php get_footer(); ?>