<?php 
if( is_page() && wpestate_check_if_admin_page($post->ID) && is_user_logged_in()  ){
    
}else{

$facebook_link      =   esc_html( get_option('wp_estate_facebook_link', '') );
$twitter_link       =   esc_html( get_option('wp_estate_twitter_link', '') );
$google_link        =   esc_html( get_option('wp_estate_google_link', '') );
$linkedin_link      =   esc_html ( get_option('wp_estate_linkedin_link','') );
$pinterest_link     =   esc_html ( get_option('wp_estate_pinterest_link','') );
?>
<div class="social_share_wrapper">

    <?php if ($facebook_link!='' ){?>
    <a class="social_share share_facebook_side" href="<?php echo $facebook_link;?>" target="_blank"><i class="fa fa-facebook"></i></a>
    <?php } ?>
    
    <?php if ($twitter_link!='' ){?>
        <a class="social_share share_twiter_side" href="<?php echo $twitter_link;?>" target="_blank"><i class="fa fa-twitter"></i></a>
    <?php } ?>
    
    <?php if ($google_link!='' ){?>
        <a class="social_share share_google_side" href="<?php echo $google_link;?>" target="_blank"><i class="fa fa-google-plus"></i></a>
    <?php } ?>
    
    <?php if ($linkedin_link!='' ){?>
        <a class="social_share share_linkedin_side" href="<?php echo $linkedin_link;?>" target="_blank"><i class="fa fa-linkedin"></i></a>
    <?php } ?>
    
    <?php if ($pinterest_link!='' ){?>
        <a class="social_share share_pinterest_side" href="<?php echo $pinterest_link;?>" target="_blank"><i class="fa fa-pinterest-p"></i></a>
    <?php } ?>
    
</div>
<?php } ?>