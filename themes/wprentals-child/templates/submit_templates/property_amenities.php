<?php
global $feature_list_array;
global $edit_id;
global $moving_array;
global $edit_link_calendar;
?>
  

<div class="col-md-12">
    <div class="user_dashboard_panel">
    <h4 class="user_dashboard_panel_title"><?php esc_html_e('Amenities and Features','wpestate');?></h4>                 
    <div class="col-md-12" id="profile_message"></div>
                
<?php

foreach($feature_list_array as $key => $value){
    $post_var_name =   str_replace(' ','_', trim($value) );
    $post_var_name =   wpestate_limit45(sanitize_title( $post_var_name ));
    $post_var_name =   sanitize_key($post_var_name);

    $value_label=$value;
    if (function_exists('icl_translate') ){
        $value_label    =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
    }

    print ' <div class="col-md-3"><p>
           <input type="hidden"    name="'.$post_var_name.'" value="" style="display:block;">
           <input type="checkbox"   id="'.$post_var_name.'" name="'.$post_var_name.'" value="1" ';

    if (esc_html(get_post_meta($edit_id, $post_var_name, true)) == 1) {
        print' checked="checked" ';
    }else{
        if(is_array($moving_array) ){                      
            if( in_array($post_var_name,$moving_array) ){
                print' checked="checked" ';
            }
        }
    }
    print' /><label for="'.$post_var_name.'">'.stripslashes ( $value_label ).'</label></p></div>';  
}
?>
    
    <div class="col-md-12" style="display: inline-block;">  
        <input type="hidden" name="" id="listing_edit" value="<?php echo $edit_id;?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_ammenities" value="<?php esc_html_e('Save', 'wpestate') ?>" />
        <a href="<?php echo  $edit_link_calendar;?>" class="next_submit_page"><?php esc_html_e('Go to Calendar settings (*make sure you click save first).','wpestate');?></a>
  
    </div>
</div>
