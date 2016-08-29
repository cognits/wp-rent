<?php

global $prop_id ;
global $agent_email;

$owner_id   =   wpsestate_get_author($prop_id);
//$agent_id   = intval( get_post_meta($post->ID, 'property_agent', true) );
$agent_id   =   get_user_meta($owner_id, 'user_agent_id', true);

$prop_id    = $post->ID;  
$author_email=get_the_author_meta( 'user_email'  );
$preview_img    = '';
$content       = '';

if ($agent_id!=0){        

        $args = array(
            'post_type' => 'estate_agent',
            'p' => $agent_id
        );

        $agent_selection = new WP_Query($args);
        $thumb_id       = '';
       
        $agent_skype    = '';
        $agent_phone    = '';
        $agent_mobile   = '';
        $agent_email    = '';
        $agent_pitch    = '';
        $link           = '';
        $name           = 'No agent';

        if( $agent_selection->have_posts() ){
        
               while ($agent_selection->have_posts()): $agent_selection->the_post();  
                    $thumb_id           = get_post_thumbnail_id($post->ID);
                    $preview            = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                    $preview_img         = $preview[0];
                    $agent_skype         = esc_html( get_post_meta($post->ID, 'agent_skype', true) );
                    $agent_phone         = esc_html( get_post_meta($post->ID, 'agent_phone', true) );
                    $agent_mobile        = esc_html( get_post_meta($post->ID, 'agent_mobile', true) );
                    $agent_email         = esc_html( get_post_meta($post->ID, 'agent_email', true) );
                    if($agent_email==''){
                        $agent_email=$author_email;
                    }
                    $agent_pitch         = esc_html( get_post_meta($post->ID, 'agent_pitch', true) );
                  
                    if (function_exists('icl_translate') ){
                        $agent_posit      =   icl_translate('wpestate','agent_position', esc_html( get_post_meta($post->ID, 'agent_position', true) ) );
                    }else{
                        $agent_posit        = esc_html( get_post_meta($post->ID, 'agent_position', true) );
                    }
            
                    $agent_facebook      = esc_html( get_post_meta($post->ID, 'agent_facebook', true) );
                    $agent_twitter       = esc_html( get_post_meta($post->ID, 'agent_twitter', true) );
                    $agent_linkedin      = esc_html( get_post_meta($post->ID, 'agent_linkedin', true) );
                    $agent_pinterest     = esc_html( get_post_meta($post->ID, 'agent_pinterest', true) );
                    $link                = esc_url ( get_permalink() );
                    $name                = get_the_title();
                    $content             = get_the_excerpt();
                    $content             = apply_filters('the_content', $content);
                    $content             = str_replace(']]>', ']]&gt;', $content);

             
               endwhile;
               wp_reset_query();
              
       } // end if have posts
}   // end if !=0
if($preview_img==''){
    $preview_img    =   get_template_directory_uri().'/img/default_user_agent.gif';
}
?>

<div class="agentpic-wrapper">
    <div class="owner_listing_image " style="background-image: url('<?php echo $preview_img;?>');"></div>
    <h3><?php print $name;?></h3>
    <a class="owner_read_more " href="<?php echo $link?>"><?php esc_html_e('See Owner Profile','wpestate');?></a>
</div>

<div class="agentpic-wrapper">
  
    <?php
        if($content!=''){                            
            print $content;     
        }
    ?>

     <div  id="contact_me_long" class="  " data-postid="<?php the_ID();?>" ><?php esc_html_e('Contact Owner','wpestate');?></div>
</div>