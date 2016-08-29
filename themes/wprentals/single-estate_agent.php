<?php
// Single Agent
// Wp Estate Pack
get_header();
$options                    =   wpestate_page_details($post->ID);
$show_compare               =   1;
$currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
$where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );

global $agent_id;
global $current_user;
global $prop_selection;
global $userID;
global $comments_data;
?>


<?php 
$agent_id           = get_the_ID();
$owner_id           =   get_post_meta($agent_id, 'user_agent_id', true);
get_template_part('templates/agent_listings');  ?>

 
<?php
$mapargs = array(
    'post_type'         =>  'estate_property',
    'post_status'       =>  'publish',
    'posts_per_page'    =>  -1,
    'author'            =>  $owner_id
);


//$selected_pins  =   wpestate_listing_pins($mapargs,1);//call the new pins   
if ( get_option('wp_estate_readsys','') =='yes' ){
    $path               = wpestate_get_pin_file_path_read();
    $request            = wp_remote_get($path);
    $selected_pins      = wp_remote_retrieve_body( $request );   
}else{
    $selected_pins  = wpestate_listing_pins($mapargs,1,1);//call the new pins  
}

wp_localize_script('wpestate_googlecode_regular', 'googlecode_regular_vars2', 
    array(  
        'markers2'  =>  $selected_pins,
    )
);
get_footer(); 
?>