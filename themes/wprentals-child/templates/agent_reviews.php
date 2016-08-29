<?php 
global $agent_id;
global $prop_selection;
global $comments_data; 
global $post;
        
$options            =   wpestate_page_details($agent_id);

if( isset($comments_data['coments_no']) && $comments_data['coments_no'] >0 ){?>

    <div class=" <?php print $options['content_class'];?> ">
        <div class="listing-reviews-wrapper">

        <h3 id="listing_reviews" class="panel-title">
                <?php
                print esc_html_e('Reviews', 'wpestate').'<span class="owner_total_reviews"> ('.$comments_data['coments_no'].')</span>';
                ?>
                
        </h3>

    <?php    
        print $comments_data['templates'];
        print '</div>';
        print '</div>';
        print '<div class=" '.$options['sidebar_class'].' widget-area-sidebar" id="primary" >
        <ul class="xoxo">';
            dynamic_sidebar('owner-page-widget-area');
        print'    
        </ul>
    </div>';
}
   
?>

   

 

