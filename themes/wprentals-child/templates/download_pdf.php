<?php

$args = array(  'post_mime_type'    => 'application/pdf', 
                'post_type'         => 'attachment', 
                'numberposts'       => -1,
                'post_status'       => null, 
                'post_parent'       => $post->ID 
        );

$attachments = get_posts($args);

if ($attachments) {
  
    print '<div class="download_docs">'.esc_html__( 'Documents','wpestate').'</div>';
    foreach ( $attachments as $attachment ) {
        print '<div class="document_down"><a href="'. wp_get_attachment_url($attachment->ID).'" target="_blank">'.$attachment->post_title.'<i class="fa fa-download"></i></a></div>';
    }
}

?>