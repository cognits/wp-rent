<?php
global $post;
global $userID;
global $current_user;
$message_from_user    =   get_post_meta($post->ID, 'message_from_user', true);
$user = get_user_by( 'id', $message_from_user );

$message_from_user_name=   $user->user_login;

$message_status       =   get_post_meta($post->ID, 'message_status'.$userID, true);
$message_title        =   get_the_title($post->ID);
$message_content      =   get_the_content();
?>

<div class="col-md-12">
    <div class="message_listing " data-messid="<?php print $post->ID; ?> ">

        <div class="message_header">
            <div class="col-md-3">
                <?php
                if($message_status=='unread'){
                    print '<span class="mess_unread mess_tooltip" data-original-title="'. esc_html__( 'new message','wpestate').'"><i class="fa fa-exclamation-circle"></i></span>';        
                }else{
                   // print '<span class="mess_read">'.esc_html__( 'read','wpestate').'</span>';
                }
                ?>
           
                <?php 
                  
                if($current_user->user_login == $message_from_user_name ){
                    print ' <span class="mess_from"><strong>'.esc_html__( 'Conversation started by you ','wpestate'). '</strong></span>';
                }else{
                    print ' <span class="mess_from"><strong>'.esc_html__( 'From','wpestate'). ': </strong>' .$message_from_user_name. '</span>';       
                }
                
                ?>
             </div>
            
            <div class="col-md-4">
                <span class="mess_subject"> <strong><?php esc_html_e('Subject','wpestate');?>: </strong><?php print $message_title;?></span>
            </div>
            
            <div class="col-md-2">
                <span class="mess_date">    <?php echo get_the_date(); ?>   </span>
            </div>
            
            <div class=" message-action text-right" >
                <span data-original-title="<?php esc_html_e('reply to message','wpestate');?>"  class="mess_reply mess_tooltip">       
                    <i class="fa fa-reply"></i> 
                </span>
                <div class="delete_wrapper">
                    <span data-original-title="<?php esc_html_e('delete message','wpestate');?>"  class="mess_delete mess_tooltip">
                        <i class="fa fa-times deleteprop"></i>
                    </span>
                </div>
            </div>
            
          
        </div>   

        <div class="mess_content">
            <h4><?php print $message_title;?></h4>
            <div class="message_content">
                <?php 
                $pieces= explode('||',$message_content);
              
                print nl2br($pieces[0]);
                if(isset($pieces[1])){
                    print '</br>';
                    print esc_html(nl2br($pieces[1]));
                }
                ?>
                
                <?php
                print '<div class="mess_content-list-replies">';
                    $args_child = array(
                        'post_type'         => 'wpestate_message',
                        'post_status'       => 'publish',
                        'posts_per_page'             => -1,
                        'order'             => 'ASC',
                        'post_parent'       => $post->ID,
                    );

                    $message_selection_child = new WP_Query($args_child);
                    while ($message_selection_child->have_posts()): $message_selection_child->the_post(); 
                        $user = get_user_by( 'id', $post->post_author );
                        print '<div class="mess_content-list-replies_unit">'
                        . '<h4><strong>'.esc_html__( 'From: ','wpestate').'</strong> '.$user->user_login.' - ' .get_the_title($post->ID).'</h4>'
                                .nl2br(get_the_content()).'</div>';
                    endwhile;
                    wp_reset_query();
                print'</div>';
                
                ?>
                
                
                
            </div>


            <?php // wpestate_show_mess_reply($post->ID); ?>
        </div>

        <div class="mess_reply_form">
          
                <h4><?php esc_html_e('Reply','wpestate');?></h4>
                <input type="text" class="subject_reply" value="Re: <?php echo $message_title; ?>">
                <textarea name="message_reply_content" class="message_reply_content"></textarea></br>
                <span class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button mess_send_reply_button" >
                    <?php esc_html_e('Send Reply','wpestate');?>
                </span>
            
        </div>    


    </div>
</div>