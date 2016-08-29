<?php
// register the custom post type
add_action( 'after_setup_theme', 'wpestate_create_message_type' );

if( !function_exists('wpestate_create_message_type') ):

function wpestate_create_message_type() {
register_post_type( 'wpestate_message',
		array(
			'labels' => array(
				'name'          => esc_html__(  'Messages','wpestate'),
				'singular_name' => esc_html__(  'Message','wpestate'),
				'add_new'       => esc_html__( 'Add New Message','wpestate'),
                'add_new_item'          =>  esc_html__( 'Add Message','wpestate'),
                'edit'                  =>  esc_html__( 'Edit' ,'wpestate'),
                'edit_item'             =>  esc_html__( 'Edit Message','wpestate'),
                'new_item'              =>  esc_html__( 'New Message','wpestate'),
                'view'                  =>  esc_html__( 'View','wpestate'),
                'view_item'             =>  esc_html__( 'View Message','wpestate'),
                'search_items'          =>  esc_html__( 'Search Message','wpestate'),
                'not_found'             =>  esc_html__( 'No Message found','wpestate'),
                'not_found_in_trash'    =>  esc_html__( 'No Message found','wpestate'),
                'parent'                =>  esc_html__( 'Parent Message','wpestate')
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'message'),
		'supports' => array('title', 'editor'),
		'can_export' => true,
		'register_meta_box_cb' => 'wpestate_add_message_metaboxes',
                'menu_icon'=> get_template_directory_uri().'/img/message.png',
                'exclude_from_search'   => true    
		)
	); 
}
endif; // end   wpestate_message  

function wpestate_hide_add_new_wpestate_message()
{
    global $submenu;
    // replace my_type with the name of your post type
    unset($submenu['edit.php?post_type=wpestate_message'][10]);
}
add_action('admin_menu', 'wpestate_hide_add_new_wpestate_message');


////////////////////////////////////////////////////////////////////////////////////////////////
// Add booking metaboxes
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_message_metaboxes') ):
    function wpestate_add_message_metaboxes() {	
      add_meta_box(  'estate_message-sectionid', esc_html__(  'Message Details', 'wpestate' ), 'wpestate_message_meta_function', 'wpestate_message' ,'normal','default');
    }
endif; // end   



////////////////////////////////////////////////////////////////////////////////////////////////
// booking details
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_message_meta_function') ):
    function wpestate_message_meta_function( $post ) {
        wp_nonce_field( plugin_basename( __FILE__ ), 'estate_message_noncename' );
        global $post;

        $from_value=esc_html(get_post_meta($post->ID, 'message_from_user', true));
         $first_content=esc_html(get_post_meta($post->ID, 'first_content', true));
        if (wpestate_is_edit_page('new')){
            $from_value='administrator';
        }
        $to_val=esc_html(get_post_meta($post->ID, 'message_to_user', true));
      //  print 'originator '.$first_content.'parent ' .wp_get_post_parent_id($post->ID) . 'from '.$from_value.' to '.$to_val ;
        
        //print 'lalala'. get_post_meta($post->ID, 'message_status'.$from_value,true ).'lalala'. get_post_meta($post->ID, 'message_status'.$to_val,true );
        
        print'
        <p class="meta-options">
            <label for="message_from_user">'.esc_html__( 'From User:','wpestate').' </label><br />
            <input type="text" id="message_from_user" size="58" name="message_from_user" value="';
            //$from_value 
            $user = get_user_by( 'id', $from_value );
            echo $user->user_login;
            print '">
        </p>

        <p class="meta-options">
            <label for="message_to_user">'.esc_html__( 'To User:','wpestate').' </label><br />
            <select id="message_to_user" name="message_to_user">
                '.wpestate_get_user_list().'
            </select>   

        <input type="hidden" name="message_status" value="'.esc_html__( 'unread','wpestate').'">
        <input type="hidden" name="delete_source" value="0">
        <input type="hidden" name="delete_destination" value="0">    
        </p>';     
    }
endif; // end   estate_booking  





////////////////////////////////////////////////////////////////////////////////
// get_user_list
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_user_list') ):
    function wpestate_get_user_list(){
        global $post;
        $selected=  get_post_meta($post->ID,'message_to_user',true);
        
        $return_string='';
        $blogusers = get_users();
        foreach ($blogusers as $user) {
           $return_string.= '<option value="'.$user->ID.'" ';
           if( $selected == $user->ID ){
                $return_string.=' selected="selected" ';
           }
           $return_string.= '>' . $user->user_nicename . '</option>';
        }
     return $return_string;   
    }
endif;



if( !function_exists('wpestate_is_edit_page') ):
    function wpestate_is_edit_page($new_edit = null){
        global $pagenow;
        //make sure we are on the backend
        if (!is_admin()) return false;


        if($new_edit == "edit")
            return in_array( $pagenow, array( 'post.php',  ) );
        elseif($new_edit == "new") //check for new post page
            return in_array( $pagenow, array( 'post-new.php' ) );
        else //check for either new or edit
            return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
    }
endif;



if( !function_exists('wpestate_show_mess_reply') ):
    function wpestate_show_mess_reply($post_id){
        $args = array(
                    'post_type'         => 'wpestate_message',
                    'post_status'       => 'publish',
                    'paged'             => 1,
                    'posts_per_page'    => 30,
                    'order'             => 'DESC',
                    'post_parent'       => $post_id,
                 );

        $message_selection = new WP_Query($args);
        while ($message_selection->have_posts()): $message_selection->the_post(); 
            print  get_the_title().'</br>';
        endwhile;
        wp_reset_query();      
    }
endif;

add_filter( 'manage_edit-wpestate_message_columns', 'wpestate_my_mess_columns' );

if( !function_exists('wpestate_my_mess_columns') ):
    function wpestate_my_mess_columns( $columns ) {
        $slice=array_slice($columns,2,2);
        unset( $columns['comments'] );
        unset( $slice['comments'] );
        $splice=array_splice($columns, 2);   
        $columns['mess_from_who']= esc_html__( 'From','wpestate');
        $columns['mess_to_who']  = esc_html__( 'To','wpestate');
        return  array_merge($columns,array_reverse($slice));
    }
endif; // end   wpestate_my_columns  


add_action( 'manage_posts_custom_column', 'wpestate_populate_messages_columns' );
if( !function_exists('wpestate_populate_messages_columns') ):
    function wpestate_populate_messages_columns( $column ) {
    $the_id=get_the_ID();
   
    $from_value=esc_html(get_post_meta($the_id, 'message_from_user', true));
    $to_val=esc_html(get_post_meta($the_id, 'message_to_user', true));
        
    if( 'mess_from_who' == $column){
   
     if(intval($from_value)!=0){
        $user = get_user_by( 'id', $from_value );
        echo $user->user_login;  
     }else{
        echo $from_value;
     }
    
    }

    if( 'mess_to_who' == $column){        
        $user = get_user_by( 'id', $to_val );
        echo $user->user_login;
    }

    }

endif;

?>