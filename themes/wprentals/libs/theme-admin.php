<?php
if( !function_exists('wpestate_new_general_set') ):
function wpestate_new_general_set() {  
   if($_SERVER['REQUEST_METHOD'] === 'POST'){	
       
      
        $allowed_html   =   array();
        if( isset( $_POST['add_field_name'] ) ){
            $new_custom=array();  
            foreach( $_POST['add_field_name'] as $key=>$value ){
                $temp_array=array();
                $temp_array[0]=$value;
                $temp_array[1]= wp_kses( $_POST['add_field_label'][sanitize_key($key)] ,$allowed_html);
                $temp_array[2]= wp_kses( $_POST['add_field_type'][sanitize_key($key)] ,$allowed_html);
                $temp_array[3]= wp_kses ( $_POST['add_field_order'][sanitize_key($key)],$allowed_html);
                $new_custom[]=$temp_array;
            }

          
            usort($new_custom,"wpestate_sorting_function");
            update_option( 'wp_estate_custom_fields', $new_custom );   
        }
          
        // multiple currencies
        if( isset( $_POST['add_curr_name'] ) ){
            foreach( $_POST['add_curr_name'] as $key=>$value ){
                $temp_array=array();
                $temp_array[0]=$value;
                $temp_array[1]= wp_kses( $_POST['add_curr_label'][sanitize_key($key)] ,$allowed_html);
                $temp_array[2]= wp_kses( $_POST['add_curr_value'][sanitize_key($key)] ,$allowed_html);
                $temp_array[3]= wp_kses( $_POST['add_curr_order'][sanitize_key($key)] ,$allowed_html);
                $new_custom_cur[]=$temp_array;
            }
            
            update_option( 'wp_estate_multi_curr', $new_custom_cur );   

       }else{
           
       }


        if( isset( $_POST['theme_slider'] ) ){
            update_option( 'wp_estate_theme_slider', true);  
        }
        
       
        foreach($_POST as $variable=>$value){	

            if ($variable!='submit'){
                if ($variable!='add_field_name'&& $variable!='add_field_label' && $variable!='add_field_type' && $variable!='add_field_order' && $variable!= 'adv_search_how' && $variable!='adv_search_what' && $variable!='adv_search_label'){
                    $variable   =   sanitize_key($variable);
                    if($variable=='co_address'){
                        $allowed_html_br=array(
                                'br' => array(),
                                'em' => array(),
                                'strong' => array()
                        );
                        $postmeta   =   wp_kses($value,$allowed_html_br);
                    }else{
                        $postmeta   =   wp_kses($value,$allowed_html);
                    
                    }
                    
               
                    update_option( wpestate_limit64('wp_estate_'.$variable), $postmeta );                
                }else{
                
                    update_option( 'wp_estate_'.$variable, $value );
                }	
            }	
        }
        
        if( isset($_POST['is_custom']) && $_POST['is_custom']== 1 && !isset($_POST['add_field_name']) ){
                 update_option( 'wp_estate_custom_fields', '' ); 
        }
        
        if( isset($_POST['is_custom_cur']) && $_POST['is_custom_cur']== 1 && !isset($_POST['add_curr_name']) ){
            update_option( 'wp_estate_multi_curr', '' );
        }
        
      
        
    
        
        if ( isset($_POST['paid_submission']) ){
            if( $_POST['paid_submission']=='membership'){
                wp_estate_schedule_user_check();  
            }else{
                wp_clear_scheduled_hook('wpestate_check_for_users_event');
            }
        }
        
        if ( isset($_POST['delete_orphan']) ){
            if( $_POST['delete_orphan']=='yes'){
                setup_wp_estate_delete_orphan_lists();  
            }else{
                wp_clear_scheduled_hook('prefix_wp_estate_delete_orphan_lists');
            }
        }
        
           
        if( isset($_POST['wpestate_autocomplete'])  ){  
            if( $_POST['wpestate_autocomplete']=='no' ){
                wpestate_create_auto_data();
            }else{
                wp_clear_scheduled_hook('event_wp_estate_create_auto');
            }  
     
        }
    
        if ( isset($_POST['auto_curency']) ){
            if( $_POST['auto_curency']=='yes' ){
                wp_estate_enable_load_exchange();
            }else{
                wp_clear_scheduled_hook('wpestate_load_exchange_action');
            }
        }
        
        if( isset($_POST['on_child_theme']) && intval( $_POST['on_child_theme']==1) ){
          
          
          print '<script type="text/javascript">
            
            //<![CDATA[
            jQuery(document).ready(function(){
           
            
                jQuery("#css_modal").show();
             
                    
            });
            //]]>
            </script>';

            
        }
    
}
    


    
$allowed_html   =   array();  
$active_tab = isset( $_GET[ 'tab' ] ) ? wp_kses( $_GET[ 'tab' ],$allowed_html ) : 'general_settings';  
require_once get_template_directory().'/libs/help_content.php';
print ' <div class="wrap">
        <form method="post" action="">
        <div class="wpestate_admin_search_bar">
            <label class="wpestate_adv_search_label">'.__('Theme Help Search - there are over 170 articles to help you setup and use the theme. Please use this search and if your question is not here, please open a ticket in our client support system.','wpestate').'</label>
            <input type="text" id="wpestate_search_bar" placeholder="'.__('Search help documentation. For ex. type: Adv ','wpestate').'">
            <div id="wpestate_admin_results">
            </div>
        </div>
        <div class="wpestate-tab-wrapper-container">
        <div class="wpestate-tab-wrapper">';
            print '<div class="ourlogo"><a href="http://wpestate.org/" target="_blank"><img src="'.get_template_directory_uri().'/img/logoadmin.png" alt="logo"></a></div>';
            
            print '<div class="wpestate-tab-item '; 
            print $active_tab == 'general_settings'  ? 'wpestate-tab-active' : '';
            print '"><a href="themes.php?page=libs/theme-admin.php&tab=general_settings">'.esc_html__( 'General Settings','wpestate').'</a></div>';
                
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'social_contact' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=social_contact">'.esc_html__( 'Social & Contact','wpestate').'</a></div>';
           
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'appearance' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=appearance">'.esc_html__( 'Appearance','wpestate').'</a></div>';
             
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'price_set' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=price_set">'.esc_html__( 'Price & Currency','wpestate').'</a></div>';
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'mapsettings' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=mapsettings">'.esc_html__( 'Google Maps Settings','wpestate').'</a></div>';
                      
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'membership' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=membership">'.esc_html__( 'Membership & Payment Settings ','wpestate').'</a></div>';
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'design' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=design">'.esc_html__( 'Design','wpestate').'</a></div>';
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'pin_management' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=pin_management">'.esc_html__( 'Pin Management','wpestate').'</a></div>';
            
            //     print '<div class="wpestate-tab-item ';
            //     print $active_tab == 'icon_management' ? 'wpestate-tab-active' : ''; 
            //     print'"><a href="themes.php?page=libs/theme-admin.php&tab=icon_management">'.esc_html__( 'Icon Management','wpestate').'</a></div>';
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'custom_fields' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=custom_fields">'.esc_html__( 'Listings Custom Fields','wpestate').'</a></div>';
         
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'adv_search' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=adv_search">'.esc_html__( 'Advanced Search','wpestate').'</a></div>';
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'display_features' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=display_features">'.esc_html__( 'Listings Features & Amenities ','wpestate').'</a></div>';
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'listings_labels' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=listings_labels">'.esc_html__( 'Listings Labels','wpestate').'</a></div>';
          
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'theme-slider' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=theme-slider">'.esc_html__( 'Set Theme Slider','wpestate').'</a></div>';
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'help_custom' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=help_custom">'.esc_html__( 'Help & Custom','wpestate').'</a></div>';
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'email_management' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=email_management">'.esc_html__( 'Email Management','wpestate').'</a></div>';
      
            
            print '<div class="wpestate-tab-item ';
            print $active_tab == 'generate_pins' ? 'wpestate-tab-active' : ''; 
            print'"><a href="themes.php?page=libs/theme-admin.php&tab=generate_pins">'.esc_html__( 'Generate Pins','wpestate').'</a></div>';
       print '</div>';

   
  print '<script type="text/javascript">
    //<![CDATA[
    
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
        return split( term ).pop();
    }
    function decodeHtml(html) {
  
        var txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
    }
                jQuery(document).ready(function(){
                    var autofill='.$help_content.';
                    jQuery("#wpestate_search_bar" ).autocomplete({
                   
                    source: function( request, response ) {
                   
                     response( jQuery.ui.autocomplete.filter(
                       autofill, extractLast( request.term ) ) );
                   },
                    focus: function( event, ui ) {
                        jQuery( "#wpestate_admin_results" ).val( decodeHtml( ui.item.label ) );
                        return false;
                    }, select: function( event, ui ) { 
                        window.open(ui.item.value,"_blank");
                    }
                    
                    
                    
                }).autocomplete( "instance" )._renderItem = function( ul, item ) {
                        return jQuery( "<li>" )
                        .append( "" + decodeHtml( item.label )+ "" )
                            .appendTo( ul );
                    };
                

           });
           //]]>
           </script>';
    switch ($active_tab) {
            case "general_settings":
                wpestate_theme_admin_general_settings();
                break;
            case "social_contact":
                wpestate_theme_admin_social();
                break;
              case "appearance":
                wpestate_theme_admin_apperance();
                break;
              case "design":
                wpestate_theme_admin_design();
                break;
              case "help_custom":
                wpestate_theme_admin_help();
                break;
              case "mapsettings":
                wpestate_theme_admin_mapsettings();
                break;
              case "membership":
                wpestate_theme_admin_membershipsettings();
                break;
              case "adv_search":
                wpestate_theme_admin_adv_search();
                break;
              case "pin_management":
                wpestate_show_pins();
                break;
              
              case "custom_fields":
                wpestate_custom_fields();
                break;
              case "display_features":
                wpestate_display_features();
                break;
              case "listings_labels":
                wpestate_display_labels();
                break;   
               case "theme-slider":
                wpestate_theme_slider();
                break;
            case "price_set":
                wpestate_price_set();
                break;
            case "email_management":
                wpestate_email_management();
                break;
            case "generate_pins":
                wpestate_generate_file_pins();
                break;
    }
            
         
     
        
                   
print '</div></form></div>';
}
endif; // end   wpestate_new_general_set  




if( !function_exists('wpestate_show_advanced_search_options') ):

function  wpestate_show_advanced_search_options($i,$adv_search_what){
    $return_string='';

    $curent_value='';
    if(isset($adv_search_what[$i])){
        $curent_value=$adv_search_what[$i];        
    }
    
   // $curent_value=$adv_search_what[$i];
    $admin_submission_array=array('types',
                                  'categories',
                                  'cities',
                                  'areas',
                                  'property price',
                                  'property size',
                                  'property lot size',
                                  'property rooms',
                                  'property bedrooms',
                                  'property bathrooms',
                                  'property address',
                                  'property county',
                                  'property state',
                                  'property zip',
                                  'property country',
                                  'property status'
                                );
    
    foreach($admin_submission_array as $value){

        $return_string.='<option value="'.$value.'" '; 
        if($curent_value==$value){
             $return_string.= ' selected="selected" ';
        }
        $return_string.= '>'.$value.'</option>';    
    }
    
    $i=0;
    $custom_fields = get_option( 'wp_estate_custom_fields', true); 
    if( !empty($custom_fields)){  
        while($i< count($custom_fields) ){          
            $name =   $custom_fields[$i][0];
            $type =   $custom_fields[$i][1];
            $slug =   str_replace(' ','-',$name);

            $return_string.='<option value="'.$slug.'" '; 
            if($curent_value==$slug){
               $return_string.= ' selected="selected" ';
            }
            $return_string.= '>'.$name.'</option>';    
            $i++;  
        }
    }  
    $slug='none';
    $name='none';
    $return_string.='<option value="'.$slug.'" '; 
    if($curent_value==$slug){
        $return_string.= ' selected="selected" ';
    }
    $return_string.= '>'.$name.'</option>';    

       
    return $return_string;
}
endif; // end   wpestate_show_advanced_search_options  



if( !function_exists('wpestate_show_advanced_search_how') ):
function  wpestate_show_advanced_search_how($i,$adv_search_how){
    $return_string='';
    $curent_value='';
    if (isset($adv_search_how[$i])){
         $curent_value=$adv_search_how[$i];
    }
   
    
    
    $admin_submission_how_array=array('equal',
                                      'greater',
                                      'smaller',
                                      'like',
                                      'date bigger',
                                      'date smaller');
    
    foreach($admin_submission_how_array as $value){
        $return_string.='<option value="'.$value.'" '; 
        if($curent_value==$value){
             $return_string.= ' selected="selected" ';
        }
        $return_string.= '>'.$value.'</option>';    
    }
    return $return_string;
}
endif; // end   wpestate_show_advanced_search_how  




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Advanced Search Settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_theme_admin_adv_search') ):
function wpestate_theme_admin_adv_search(){
    $cache_array                    =   array('yes','no');  
    
    $custom_advanced_search= get_option('wp_estate_custom_advanced_search','');
    $adv_search_what    = get_option('wp_estate_adv_search_what','');
    $adv_search_how     = get_option('wp_estate_adv_search_how','');
    $adv_search_label   = get_option('wp_estate_adv_search_label','');
    
    
    $custom_advanced_search_select ='';
    $custom_advanced_status= esc_html ( get_option('wp_estate_custom_advanced_search','') );
    $value_array=array('no','yes');

    foreach($value_array as $value){
            $custom_advanced_search_select.='<option value="'.$value.'"';
            if ($custom_advanced_status==$value){
                $custom_advanced_search_select.='selected="selected"';
            }
            $custom_advanced_search_select.='>'.$value.'</option>';
    }
  
    
    $show_adv_search_general_select     =   '';
    $show_adv_search_general            =   get_option('wp_estate_show_adv_search_general','');

    foreach($cache_array as $value){
            $show_adv_search_general_select.='<option value="'.$value.'"';
            if ($show_adv_search_general    ==  $value){
                    $show_adv_search_general_select.=' selected="selected" ';
            }
            $show_adv_search_general_select.='> '.$value.'</option>';
    }
    
    
    $wpestate_autocomplete_select     =   '';
    $wpestate_autocomplete           =   get_option('wp_estate_wpestate_autocomplete','');

    foreach($cache_array as $value){
            $wpestate_autocomplete_select.='<option value="'.$value.'"';
            if ($wpestate_autocomplete    ==  $value){
                    $wpestate_autocomplete_select.=' selected="selected" ';
            }
            $wpestate_autocomplete_select.='> '.$value.'</option>';
    }
    
    
    
    
    
    
    
    $show_adv_search_slider_select     =   '';
    $show_adv_search_slider            =   get_option('wp_estate_show_adv_search_slider','');

    foreach($cache_array as $value){
            $show_adv_search_slider_select.='<option value="'.$value.'"';
            if ($show_adv_search_slider    ==  $value){
                    $show_adv_search_slider_select.=' selected="selected" ';
            }
            $show_adv_search_slider_select.='> '.$value.'</option>';
    }
    
    
    
    $show_adv_search_visible_select     =   '';
    $show_adv_search_visible            =   get_option('wp_estate_show_adv_search_visible','');

    foreach($cache_array as $value){
            $show_adv_search_visible_select.='<option value="'.$value.'"';
            if ($show_adv_search_visible    ==  $value){
                    $show_adv_search_visible_select.=' selected="selected" ';
            }
            $show_adv_search_visible_select.='> '.$value.'</option>';
    }
    
   
    $show_adv_search_slider_select     =   '';
    $show_adv_search_slider            =   get_option('wp_estate_show_adv_search_slider','');

    foreach($cache_array as $value){
            $show_adv_search_slider_select.='<option value="'.$value.'"';
            if ($show_adv_search_slider    ==  $value){
                    $show_adv_search_slider_select.=' selected="selected" ';
            }
            $show_adv_search_slider_select.='> '.$value.'</option>';
    }
    
    $search_array   =   array( 
                            "newtype" => esc_html__( 'Type 1','wpestate'),
                            "oldtype" => esc_html__( 'Type 2','wpestate')
                            );
    
    $search_type    =   get_option('wp_estate_adv_search_type','');
    $search_type_select  =   '';
    
    foreach( $search_array as $key=>$value){
        $search_type_select.='<option value="'.$key.'" ';
        if($key==$search_type){
            $search_type_select.=' selected="selected" ';
        }
        $search_type_select.='>'.$value.'</option>'; 
    }
    
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'Advanced Search','wpestate').'</h1>';  
    
    print '
        <table class="form-table">
        <!--   -->
        <tr valign="top">
            <th scope="row"><label for="adv_search_type">'.esc_html__( 'Search type ?','wpestate').'</label></th>
           
            <td><select id="adv_search_type" name="adv_search_type">
                    '.$search_type_select.'
		 </select>
            </td>
        </tr>
      
        
        <tr valign="top">
            <th scope="row"><label for="show_adv_search_inclose">'.esc_html__( 'Show Advanced Search ?','wpestate').'</label></th>
           
            <td> <select id="show_adv_search_general" name="show_adv_search_general">
                    '.$show_adv_search_general_select.'
		 </select>
            </td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="wpestate_autocomplete">'.esc_html__( 'Use Google Places autocomplete for Search? (if not the autocomplete will be made with data from already inserted properties)','wpestate').'</label></th>
           
            <td> <select id="wpestate_autocomplete" name="wpestate_autocomplete">
                    '.$wpestate_autocomplete_select.'
		 </select>
            </td>
            <td>'.esc_html__('Due to speed reasons the data for NON-Google autocomplete is generated 1 time per day. If you want to manually generate the data, click ','wpestate')
                .'<a href="themes.php?page=libs/theme-admin.php&tab=generate_pins"> '.esc_html__('this link','wpestate').'</a></td>
        </tr>


       
        <tr valign="top">
            <th scope="row"><label for="show_adv_search_slider">'.esc_html__( 'Show Advanced Search over sliders or images ?','wpestate').'</label></th>
           
            <td> <select id="show_adv_search_slider" name="show_adv_search_slider">
                    '.$show_adv_search_slider_select.'
		 </select>
            </td>
        </tr>
      
        
        
         <tr valign="top">
            <th scope="row"><label for="show_slider_price_values">'.esc_html__( 'Minimum and Maximum value for Price Slider','wpestate').'</label></th>
           
            <td>
                <input type="text" name="show_slider_min_price"  class="inptxt " value="'.floatval(get_option('wp_estate_show_slider_min_price','')).'"/>
                -   
                <input type="text" name="show_slider_max_price"  class="inptxt " value="'.floatval(get_option('wp_estate_show_slider_max_price','')).'"/>
            </td>
        </tr>


        </table>';
   
        print '<h1 class="wpestate-tabh1">'.esc_html__( 'Amenities and Features for half map Advanced Search','wpestate').'</h1>'; 
        $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
        $feature_list_array =   explode( ',',$feature_list);
       
        $advanced_exteded =  get_option('wp_estate_advanced_exteded');
        
        print ' <p style="margin-left:10px;">  '.esc_html__( '*Hold CTRL for multiple selection','wpestate').'</p>'
        . '<input type="hidden" name="advanced_exteded[]" value="none">'
        . '<p style="margin-left:10px;"> <select name="advanced_exteded[]" multiple="multiple" style="height:400px;">';
        foreach($feature_list_array as $checker => $value){
            $post_var_name  =   str_replace(' ','_', trim($value) );
            print '<option value="'.$post_var_name.'"' ;
            if(is_array($advanced_exteded)){
                if( in_array ($post_var_name,$advanced_exteded) ){
                    print ' selected="selected" ';
                } 
            }
            
            print '>'.$value.'</option>';                
        }
        print '</select></p>';
        print'
        <p class="submit">
           <input type="submit" name="submit" id="submit" class="button-primary"  value="'.esc_html__( 'Save Changes','wpestate').'" />
        </p>
        
        ';
}
endif; // end   wpestate_theme_admin_adv_search  




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Membership Settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_theme_admin_membershipsettings') ):
function wpestate_theme_admin_membershipsettings(){
    $price_submission               =   floatval( get_option('wp_estate_price_submission','') );
    $price_featured_submission      =   floatval( get_option('wp_estate_price_featured_submission','') );    
    $paypal_client_id               =   esc_html( get_option('wp_estate_paypal_client_id','') );
    $paypal_client_secret           =   esc_html( get_option('wp_estate_paypal_client_secret','') );
    $paypal_api_username            =   esc_html( get_option('wp_estate_paypal_api_username','') );
    $paypal_api_password            =   esc_html( get_option('wp_estate_paypal_api_password','') );
    $paypal_api_signature           =   esc_html( get_option('wp_estate_paypal_api_signature','') );
    $paypal_rec_email               =   esc_html( get_option('wp_estate_paypal_rec_email','') );
    $free_feat_list                 =   esc_html( get_option('wp_estate_free_feat_list','') );
    $free_mem_list                  =   esc_html( get_option('wp_estate_free_mem_list','') );
    $cache_array                    =   array('yes','no');  
    $book_down                      =   esc_html( get_option('wp_estate_book_down','') );
    $book_down_fixed_fee            =   esc_html( get_option('wp_estate_book_down_fixed_fee','') );
    $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
    $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );
            
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $free_mem_list_unl='';
    if ( intval( get_option('wp_estate_free_mem_list_unl', '' ) ) == 1){
      $free_mem_list_unl=' checked="checked" ';  
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $paypal_api_select='';
    $paypal_array   =   array( esc_html__( 'sandbox','wpestate'), esc_html__( 'live','wpestate') );
    $paypal_status  =   esc_html( get_option('wp_estate_paypal_api','') );
    
  
    foreach($paypal_array as $value){
	$paypal_api_select.='<option value="'.$value.'"';
	if ($paypal_status==$value){
            $paypal_api_select.=' selected="selected" ';
	}
	$paypal_api_select.='>'.$value.'</option>';
}



    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $submission_curency_array=array('USD','EUR','AUD','BRL','CAD','CZK','DKK','HKD','HUF','ILS','JPY','MYR','MXN','NOK','NZD','PHP','PLN','GBP','SGD','SEK','CHF','TWD','THB','TRY','RUB');
    $submission_curency_status = esc_html( get_option('wp_estate_submission_curency','') );
    $submission_curency_symbol='';

    foreach($submission_curency_array as $value){
            $submission_curency_symbol.='<option value="'.$value.'"';
            if ($submission_curency_status==$value){
                $submission_curency_symbol.=' selected="selected" ';
            }
            $submission_curency_symbol.='>'.$value.'</option>';
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $paypal_array=array('no','per listing','membership');
    $paid_submission_symbol='';
    $paid_submission_status= esc_html ( get_option('wp_estate_paid_submission','') );

    foreach($paypal_array as $value){
            $paid_submission_symbol.='<option value="'.$value.'"';
            if ($paid_submission_status==$value){
                    $paid_submission_symbol.=' selected="selected" ';
            }
            $paid_submission_symbol.='>'.$value.'</option>';
    }
    
    $merch_array=array('yes','no');
    $enable_paypal_symbol='';
    $enable_paypal_status= esc_html ( get_option('wp_estate_enable_paypal','') );

    foreach($merch_array as $value){
            $enable_paypal_symbol.='<option value="'.$value.'"';
            if ($enable_paypal_status==$value){
                    $enable_paypal_symbol.=' selected="selected" ';
            }
            $enable_paypal_symbol.='>'.$value.'</option>';
    }
    
    
    $enable_stripe_symbol='';
    $enable_stripe_status= esc_html ( get_option('wp_estate_enable_stripe','') );

    foreach($merch_array as $value){
            $enable_stripe_symbol.='<option value="'.$value.'"';
            if ($enable_stripe_status==$value){
                    $enable_stripe_symbol.=' selected="selected" ';
            }
            $enable_stripe_symbol.='>'.$value.'</option>';
    }
       

    $merch_array=array('yes','no');
    $enable_wire_symbol='';
    $enable_wire_status= esc_html ( get_option('wp_estate_enable_direct_pay','') );

    foreach($merch_array as $value){
            $enable_wire_symbol.='<option value="'.$value.'"';
            if ($enable_wire_status==$value){
                    $enable_wire_symbol.=' selected="selected" ';
            }
            $enable_wire_symbol.='>'.$value.'</option>';
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $admin_submission_symbol    =   '';
    $admin_submission_status    =   esc_html ( get_option('wp_estate_admin_submission','') );
    $submission_curency_custom  =   esc_html ( get_option('wp_estate_submission_curency_custom','') );
    
    foreach($cache_array as $value){
            $admin_submission_symbol.='<option value="'.$value.'"';
            if ($admin_submission_status==$value){
                    $admin_submission_symbol.=' selected="selected" ';
            }
            $admin_submission_symbol.='>'.$value.'</option>';
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////
    
    $free_feat_list_expiration  =   intval ( get_option('wp_estate_free_feat_list_expiration','') );
    $direct_payment_details     = stripslashes (esc_html (get_option('wp_estate_direct_payment_details','')));
    
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'Membership & Payment Settings','wpestate').'</h1>';  
    
    print '
        <table class="form-table">
         <tr valign="top">
            <th scope="row"><label for="paid_submission">'.esc_html__( 'Enable Paid Submission?','wpestate').'</label></th>
           
            <td> <select id="paid_submission" name="paid_submission">
                    '.$paid_submission_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="enable_paypal">'.esc_html__( 'Enable Paypal?','wpestate').'</label></th>
           
            <td> <select id="enable_paypal" name="enable_paypal">
                    '.$enable_paypal_symbol.'
		 </select>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="enable_stripe">'.esc_html__( 'Enable Stripe?','wpestate').'</label></th>
           
            <td> <select id="enable_stripe" name="enable_stripe">
                    '.$enable_stripe_symbol.'
		 </select>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="admin_submission">'.esc_html__( 'Submited Listings should be approved by admin?','wpestate').'</label></th>
           
            <td> <select id="admin_submission" name="admin_submission">
                    '.$admin_submission_symbol.'
		 </select>
            </td>
        </tr>
   

        <tr valign="top">
            <th scope="row"><label for="price_submission">'.esc_html__( 'Price Per Submission','wpestate').'</label></th>
           <td><input  type="text" id="price_submission" name="price_submission"  value="'.$price_submission.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="price_featured_submission">'.esc_html__( 'Price to make the listing featured','wpestate').'</label></th>
           <td><input  type="text" id="price_featured_submission" name="price_featured_submission"  value="'.$price_featured_submission.'"/> </td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="submission_curency">'.esc_html__( 'Currency For Payments','wpestate').'</label></th>
            <td>
                <select id="submission_curency" name="submission_curency">
                    '.$submission_curency_symbol.'
                </select> 
            </td>
        </tr>
        
         <tr valign="top">
            <th scope="row"><label for="paypal_client_id">'.esc_html__( 'Paypal Client id','wpestate').'</label></th>
            <td><input  type="text" id="paypal_client_id" name="paypal_client_id" class="regular-text"  value="'.$paypal_client_id.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="paypal_client_secret ">'.esc_html__( 'Paypal Client Secret Key ','wpestate').'</label></th>
            <td><input  type="text" id="paypal_client_secret" name="paypal_client_secret"  class="regular-text" value="'.$paypal_client_secret.'"/> </td>
        </tr>
        
         <tr valign="top">
            <th scope="row"><label for="paypal_api">'.esc_html__( 'Paypal Api ','wpestate').'</label></th>
            <td>
              <select id="paypal_api" name="paypal_api">
                    '.$paypal_api_select.'
                </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="paypal_api_username">'.esc_html__( 'Paypal Api User Name ','wpestate').'</label></th>
            <td><input  type="text" id="paypal_api_username" name="paypal_api_username"  class="regular-text" value="'.$paypal_api_username.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="paypal_api_password ">'.esc_html__( 'Paypal API Password ','wpestate').'</label></th>
            <td><input  type="text" id="paypal_api_password" name="paypal_api_password"  class="regular-text" value="'.$paypal_api_password.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="paypal_api_signature">'.esc_html__( 'Paypal API Signature','wpestate').'</label></th>
            <td><input  type="text" id="paypal_api_signature" name="paypal_api_signature"  class="regular-text" value="'.$paypal_api_signature.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="paypal_rec_email">'.esc_html__( 'Paypal receiving email','wpestate').'</label></th>
            <td><input  type="text" id="paypal_rec_email" name="paypal_rec_email"  class="regular-text" value="'.$paypal_rec_email.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="stripe_secret_key">'.esc_html__( 'Stripe Secret Key','wpestate').'</label></th>
            <td><input  type="text" id="stripe_secret_key" name="stripe_secret_key"  class="regular-text" value="'.$stripe_secret_key.'"/> </td>
        </tr>
       
        <tr valign="top">
            <th scope="row"><label for="stripe_publishable_key">'.esc_html__( 'Stripe Publishable Key','wpestate').'</label></th>
            <td><input  type="text" id="stripe_publishable_key" name="stripe_publishable_key"  class="regular-text" value="'.$stripe_publishable_key.'"/> </td>
        </tr>
        
      
        
        <tr valign="top">
            <th scope="row"><label for="enable_direct_pay">'.esc_html__( 'Enable Direct Payment / Wire Payment? ','wpestate').'</label></th>
            <td>
              <select id="enable_direct_pay" name="enable_direct_pay">
                    '.$enable_wire_symbol.'
                </select>
            </td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="direct_payment_details">'.esc_html__( 'Wire instructions for direct payment','wpestate').'</label></th>
            <td><textarea cols="50" id="direct_payment_details" name="direct_payment_details"  class="regular-text"/>'.$direct_payment_details.'</textarea></td>
        </tr>
        

       
         
        <tr valign="top">
            <th scope="row"><label for="submission_curency_custom">'.esc_html__( 'Custom Currency Symbol - to be used if the currency is not in the "Currency For Payments" list.','wpestate').' </label></th>
            <td>
                <input  type="text" id="submission_curency_custom" name="submission_curency_custom" style="margin-right:20px;"    value="'.$submission_curency_custom.'"/>
            </td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="free_mem_list">'.esc_html__( 'Free Membership - no of listings','wpestate').' </label></th>
            <td>
                <input  type="text" id="free_mem_list" name="free_mem_list" style="margin-right:20px;"  value="'.$free_mem_list.'"/> 
       
                <input type="hidden" name="free_mem_list_unl" value="">
                <input type="checkbox"  id="free_mem_list_unl" name="free_mem_list_unl" value="1" '.$free_mem_list_unl.' />
                <label for="free_mem_list_unl">'.esc_html__( 'Unlimited listings ?','wpestate').'</label>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="free_feat_list">'.esc_html__( 'Free Membership - no of featured listings','wpestate').' </label></th>
            <td>
                <input  type="text" id="free_feat_list" name="free_feat_list" style="margin-right:20px;"    value="'.$free_feat_list.'"/>
              
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="book_down">'.esc_html__( 'Admin Booking Fee - % booking fee (excludes city and cleaning fee)','wpestate').' </label></th>
            <td>
                <input  type="text" id="book_down" name="book_down" style="margin-right:20px;"    value="'.$book_down.'"/>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="$book_down_fixed_fee">'.esc_html__( 'Admin Booking Fee - fixed value booking fee. If you add a value here, admin gets a fixed booking fee.','wpestate').' </label></th>
            <td>
                <input  type="text" id="book_down_fixed_fee" name="book_down_fixed_fee" style="margin-right:20px;"    value="'.$book_down_fixed_fee.'"/>
            </td>
        </tr>
        
        
        <tr valign="top">
            <th scope="row"><label for="free_feat_list_expiration">'.esc_html__( 'Free Membership Listings - no of days until a free listing will expire. *Starts from the moment the property is published on the website. (for "membership" mode) ','wpestate').' </label></th>
            <td>
                <input  type="text" id="free_feat_list_expiration" name="free_feat_list_expiration" style="margin-right:20px;"    value="'.$free_feat_list_expiration.'"/>
              
            </td>
        </tr>

        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button-primary" value="'.esc_html__( 'Save Changes','wpestate').'" />
        </p>  
    ';
    print '</div>';
}
endif; // end   wpestate_theme_admin_membershipsettings  




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Map Settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_theme_admin_mapsettings') ):
function wpestate_theme_admin_mapsettings(){
    $general_longitude              =   esc_html( get_option('wp_estate_general_longitude') );
    $general_latitude               =   esc_html( get_option('wp_estate_general_latitude') );
    $api_key                        =   esc_html( get_option('wp_estate_api_key') );
    $cache_array                    =   array('yes','no');
    $default_map_zoom               =   intval   ( get_option('wp_estate_default_map_zoom','') );
    $zoom_cluster                   =   esc_html ( get_option('wp_estate_zoom_cluster ','') );
    $hq_longitude                   =   esc_html ( get_option('wp_estate_hq_longitude') );
    $hq_latitude                    =   esc_html ( get_option('wp_estate_hq_latitude') );
    $min_height                     =   intval   ( get_option('wp_estate_min_height','') );
    $max_height                     =   intval   ( get_option('wp_estate_max_height','') );

    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////    
    $readsys_symbol='';
    $readsys_array_status= esc_html ( get_option('wp_estate_readsys','') );

    foreach($cache_array as $value){
            $readsys_symbol.='<option value="'.$value.'"';
            if ($readsys_array_status==$value){
                    $readsys_symbol.=' selected="selected" ';
            }
            $readsys_symbol.='>'.$value.'</option>';
    }

    $ssl_map_symbol='';
    $ssl_map_status= esc_html ( get_option('wp_estate_ssl_map','') );

    foreach($cache_array as $value){
        $ssl_map_symbol.='<option value="'.$value.'"';
        if ($ssl_map_status==$value){
            $ssl_map_symbol.=' selected="selected" ';
        }
        $ssl_map_symbol.='>'.$value.'</option>';
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////    
    $cache_symbol='';
    $cache_array_status= esc_html ( get_option('wp_estate_cache','') );

    foreach($cache_array as $value){
            $cache_symbol.='<option value="'.$value.'"';
            if ($cache_array_status==$value){
                    $cache_symbol.=' selected="selected" ';
            }
            $cache_symbol.='>'.$value.'</option>';
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $show_filter_map_symbol='';
    $show_filter_map_status= esc_html ( get_option('wp_estate_show_filter_map','') );

    foreach($cache_array as $value){
            $show_filter_map_symbol.='<option value="'.$value.'"';
            if ($show_filter_map_status==$value){
                    $show_filter_map_symbol.=' selected="selected" ';
            }
            $show_filter_map_symbol.='>'.$value.'</option>';
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $home_small_map_symbol='';
    $home_small_map_status= esc_html ( get_option('wp_estate_home_small_map','') );

    foreach($cache_array as $value){
            $home_small_map_symbol.='<option value="'.$value.'"';
            if ($home_small_map_status==$value){
                    $home_small_map_symbol.=' selected="selected" ';
            }
            $home_small_map_symbol.='>'.$value.'</option>';
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $pin_cluster_symbol='';
    $pin_cluster_status= esc_html ( get_option('wp_estate_pin_cluster','') );

    foreach($cache_array as $value){
            $pin_cluster_symbol.='<option value="'.$value.'"';
            if ($pin_cluster_status==$value){
                    $pin_cluster_symbol.=' selected="selected" ';
            }
            $pin_cluster_symbol.='>'.$value.'</option>';
    }
    
    $geolocation_radius         =   esc_html ( get_option('wp_estate_geolocation_radius','') );
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
   /* $geolocation_symbol='';
    $geolocation_status= esc_html ( get_option('wp_estate_geolocation','') );

    foreach($cache_array as $value){
            $geolocation_symbol.='<option value="'.$value.'"';
            if ($geolocation_status==$value){
                    $geolocation_symbol.=' selected="selected" ';
            }
            $geolocation_symbol.='>'.$value.'</option>';
    }
*/
  
     ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $cache_array2=array('no','yes');
    $keep_min_symbol='';
    $keep_min_status= esc_html ( get_option('wp_estate_keep_min','') );
    
    foreach($cache_array2 as $value){
            $keep_min_symbol.='<option value="'.$value.'"';
            if ($keep_min_status==$value){
                    $keep_min_symbol.=' selected="selected" ';
            }
            $keep_min_symbol.='>'.$value.'</option>';
    }
    
    $show_adv_search_symbol_map_close='';
    $show_adv_search_map_close= esc_html ( get_option('wp_estate_show_adv_search_map_close','') );
    
    foreach($cache_array as $value){
            $show_adv_search_symbol_map_close.='<option value="'.$value.'"';
            if ($show_adv_search_map_close==$value){
                    $show_adv_search_symbol_map_close.=' selected="selected" ';
            }
            $show_adv_search_symbol_map_close.='>'.$value.'</option>';
    }
    
     ///////////////////////////////////////////////////////////////////////////////////////////////////////
 
    $on_demand_map_syumbol='';
    $on_demand_map_status= esc_html ( get_option('wp_estate_ondemandmap','') );
    
    
      foreach($cache_array as $value){
            $on_demand_map_syumbol.='<option value="'.$value.'"';
            if ($on_demand_map_status==$value){
                    $on_demand_map_syumbol.=' selected="selected" ';
            }
            $on_demand_map_syumbol.='>'.$value.'</option>';
    }
    
    $map_style  =   esc_html ( get_option('wp_estate_map_style','') );
    
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'Google Maps Settings','wpestate').'</h1>';  
    
    print '
        <table class="form-table">
         <tr valign="top">
            <th scope="row"><label for="readsys">'.esc_html__( 'Use file reading for pins? (*recommended for over 200 listings. Read the manual for diffrences betwen file and mysql reading)','wpestate').'</label></th>
           
            <td> <select id="readsys" name="readsys">
                    '.$readsys_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="ssl_map">'.esc_html__( 'Use Google maps with SSL ?','wpestate').'</label></th>
           
            <td> <select id="ssl_map" name="ssl_map">
                    '.$ssl_map_symbol.'
		 </select>
            </td>
        </tr>

        <tr valign="top">
           <th scope="row"><label for="api_key">'.esc_html__( 'Google Maps API KEY','wpestate').'</label></th>
           <td><input  type="text" id="api_key" name="api_key" class="regular-text" value="'.$api_key.'"/></td>
        </tr>
          <tr valign="top">
            <th scope="row"></th>
            <td>'.esc_html__( 'The Google Maps JavaScript API v3 REQUIRES an API key to function correctly. Get an APIs Console key and post the code in Theme Options. You can get it from ','wpestate').'<a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">'.esc_html__('here','wpestate').'</a>.</td>
        </tr>
        <tr valign="top">
            <th scope="row"> <label for="general_latitude">'.esc_html__( 'Starting Point Latitude','wpestate').'</label></th>
            <td><input  type="text" id="general_latitude"  name="general_latitude"   value="'.$general_latitude.'"/></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"> <label for="general_longitude">'.esc_html__( 'Starting Point Longitude','wpestate').'</label></th>
            <td><input  type="text" id="general_longitude" name="general_longitude"  value="'.$general_longitude.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="default_map_zoom">'.esc_html__( ' Default Map zoom (1 to 20) ','wpestate').'</label></th>
            <td>
                <input type="text" id="default_map_zoom" name="default_map_zoom" value="'.$default_map_zoom.'">   
            </td>
        </tr>'; 
       
        print'
        <tr valign="top">
            <th scope="row"><label for="ondemandmap">'.esc_html__( 'Use on demand pins when moving the map, in Properties list half map and Advanced search results half map pages (see this ','wpestate').
                '<a href="http://help.wprentals.org/2016/07/28/use-on-demand-pins-when-moving-the-map-in-properties-list-half-map-and-advanced-search-results-half-map-pages/" target="_blank">'.esc_html__('help article before','wpestate').'</a> )</label></th>
           
            <td> <select id="ondemandmap" name="ondemandmap">
                    '.$on_demand_map_syumbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="pin_cluster">'.esc_html__( 'Use Pin Cluster on map','wpestate').'</label></th>
           
            <td> <select id="pin_cluster" name="pin_cluster">
                    '.$pin_cluster_symbol.'
		 </select>
            </td>
        </tr>
        
        
         <tr valign="top">
            <th scope="row"><label for="zoom_cluster">'.esc_html__( 'Maximum zoom level for Cloud Cluster to appear','wpestate').'</label></th>
            <td><input id="zoom_cluster" type="text" size="36" name="zoom_cluster" value="'.$zoom_cluster.'" /></td>       
        </tr>
        
         <tr valign="top">
            <th scope="row"> <label for="hq_latitude">'.esc_html__( 'Contact Page - Company HQ Latitude','wpestate').'</label></th>
            <td><input  type="text" id="hq_latitude"  name="hq_latitude"   value="'.$hq_latitude.'"/></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"> <label for="hq_longitude">'.esc_html__( 'Contact Page - Company HQ Longitude','wpestate').'</label></th>
            <td><input  type="text" id="hq_longitude" name="hq_longitude"  value="'.$hq_longitude.'"/> </td>
        </tr>
        ';
        /*
         <tr valign="top">
            <th scope="row"><label for="geolocation">'.esc_html__( 'Enable Geolocation','wpestate').'</label></th>
           
            <td> <select id="geolocation" name="geolocation">
                    '.$geolocation_symbol.'
		 </select>
            </td>
        </tr>
         */        
        print'
         <tr valign="top">
            <th scope="row"><label for="geolocation_radius">'.esc_html__( 'Geolocation Circle over map (in meters)','wpestate').'</label></th>
            <td>  <input id="geolocation_radius" type="text" size="36" name="geolocation_radius" value="'.$geolocation_radius.'" /></td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="min_height">'.esc_html__( 'Height of the Google Map when closed','wpestate').'</label></th>
            <td>  <input id="min_height" type="text" size="36" name="min_height" value="'.$min_height.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="max_height">'.esc_html__( 'Height of Google Map when open','wpestate').'</label></th>
            <td>  <input id="max_height" type="text" size="36" name="max_height" value="'.$max_height.'" /></td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="keep_min">'.esc_html__( 'Force Google Map at the "closed" size ? ','wpestate').'</label></th>
           
            <td> <select id="keep_min" name="keep_min">
                    '.$keep_min_symbol.'
		 </select>
            </td>
        </tr>


         
        <tr valign="top">
            <th scope="row"><label for="map_style">'.esc_html__( 'Style for Google Map. Use https://snazzymaps.com/ to create styles ','wpestate').'</label></th>
            <td> 
           
                <textarea id="map_style" style="width:270px;height:350px;" name="map_style">'.stripslashes($map_style).'</textarea>
            </td>
        </tr>
        


        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button-primary"  value="'.esc_html__( 'Save Changes','wpestate').'" />
        </p>  
    ';
    print '</div>';
}
endif; // end   wpestate_theme_admin_mapsettings  



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  General Settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_theme_admin_general_settings') ):
function wpestate_theme_admin_general_settings(){
    $cache_array                    =   array('yes','no');
    $social_array                   =   array('no','yes');
    $logo_image                     =   esc_html( get_option('wp_estate_logo_image','') );
    $transparent_logo_image         =   esc_html( get_option('wp_estate_transparent_logo_image','') );
    $mobile_logo_image              =   esc_html( get_option('wp_estate_mobile_logo_image','') );
    
    $logo_image_retina              =   esc_html( get_option('wp_estate_logo_image_retina','') );
    $mobile_logo_image_retina       =   esc_html( get_option('wp_estate_mobile_logo_image_retina','') );
    $transparent_logo_image_retina  =   esc_html( get_option('wp_estate_mobile_logo_image_retina','') );
    
    
   
    $footer_logo_image              =   esc_html( get_option('wp_estate_footer_logo_image','') );
    $favicon_image                  =   esc_html( get_option('wp_estate_favicon_image','') );
    $google_analytics_code          =   esc_html ( get_option('wp_estate_google_analytics_code','') );
  
    $general_country                =   esc_html( get_option('wp_estate_general_country') );

    $currency_symbol                =   esc_html( get_option('wp_estate_currency_symbol') );
    $front_end_register             =   esc_html( get_option('wp_estate_front_end_register','') );
    $front_end_login                =   esc_html( get_option('wp_estate_front_end_login','') );  
   

    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $measure_sys='';
    $measure_array=array( esc_html__( 'feet','wpestate')     =>esc_html__( 'ft','wpestate'),
                          esc_html__( 'meters','wpestate')   =>esc_html__( 'm','wpestate') 
                        );
    
    $measure_array_status= esc_html( get_option('wp_estate_measure_sys','') );

    foreach($measure_array as $key => $value){
            $measure_sys.='<option value="'.$value.'"';
            if ($measure_array_status==$value){
                $measure_sys.=' selected="selected" ';
            }
            $measure_sys.='>'.esc_html__( 'square','wpestate').' '.$key.' - '.$value.'<sup>2</sup></option>';
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $enable_top_bar_symbol='';
    $top_bar_status= esc_html ( get_option('wp_estate_enable_top_bar','') );

    foreach($cache_array as $value){
            $enable_top_bar_symbol.='<option value="'.$value.'"';
            if ($top_bar_status==$value){
                    $enable_top_bar_symbol.=' selected="selected" ';
            }
            $enable_top_bar_symbol.='>'.$value.'</option>';
    }

   
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $date_lang_symbol='';
    $date_lang_status= esc_html ( get_option('wp_estate_date_lang','') );
    $date_languages=array(  'xx'=> 'default',
                            'af'=>'Afrikaans',
                            'ar'=>'Arabic',
                            'ar-DZ' =>'Algerian',
                            'az'=>'Azerbaijani',
                            'be'=>'Belarusian',
                            'bg'=>'Bulgarian',
                            'bs'=>'Bosnian',
                            'ca'=>'Catalan',
                            'cs'=>'Czech',
                            'cy-GB'=>'Welsh/UK',
                            'da'=>'Danish',
                            'de'=>'German',
                            'el'=>'Greek',
                            'en-AU'=>'English/Australia',
                            'en-GB'=>'English/UK',
                            'en-NZ'=>'English/New Zealand',
                            'eo'=>'Esperanto',
                            'es'=>'Spanish',
                            'et'=>'Estonian',
                            'eu'=>'Karrikas-ek',
                            'fa'=>'Persian',
                            'fi'=>'Finnish',
                            'fo'=>'Faroese',
                            'fr'=>'French',
                            'fr-CA'=>'Canadian-French',
                            'fr-CH'=>'Swiss-French',
                            'gl'=>'Galician',
                            'he'=>'Hebrew',
                            'hi'=>'Hindi',
                            'hr'=>'Croatian',
                            'hu'=>'Hungarian',
                            'hy'=>'Armenian',
                            'id'=>'Indonesian',
                            'ic'=>'Icelandic',
                            'it'=>'Italian',
                            'it-CH'=>'Italian-CH',
                            'ja'=>'Japanese',
                            'ka'=>'Georgian',
                            'kk'=>'Kazakh',
                            'km'=>'Khmer',
                            'ko'=>'Korean',
                            'ky'=>'Kyrgyz',
                            'lb'=>'Luxembourgish',
                            'lt'=>'Lithuanian',
                            'lv'=>'Latvian',
                            'mk'=>'Macedonian',
                            'ml'=>'Malayalam',
                            'ms'=>'Malaysian',
                            'nb'=>'Norwegian',
                            'nl'=>'Dutch',
                            'nl-BE'=>'Dutch-Belgium',
                            'nn'=>'Norwegian-Nynorsk',
                            'no'=>'Norwegian',
                            'pl'=>'Polish',
                            'pt'=>'Portuguese',
                            'pt-BR'=>'Brazilian',
                            'rm'=>'Romansh',
                            'ro'=>'Romanian',
                            'ru'=>'Russian',
                            'sk'=>'Slovak',
                            'sl'=>'Slovenian',
                            'sq'=>'Albanian',
                            'sr'=>'Serbian',
                            'sr-SR'=>'Serbian-i18n',
                            'sv'=>'Swedish',
                            'ta'=>'Tamil',
                            'th'=>'Thai',
                            'tj'=>'Tajiki',
                            'tr'=>'Turkish',
                            'uk'=>'Ukrainian',
                            'vi'=>'Vietnamese',
                            'zh-CN'=>'Chinese',
                            'zh-HK'=>'Chinese-Hong-Kong',
                            'zh-TW'=>'Chinese Taiwan',
        );  

    foreach($date_languages as $key=>$value){
            $date_lang_symbol.='<option value="'.$key.'"';
            if ($date_lang_status==$key){
                    $date_lang_symbol.=' selected="selected" ';
            }
            $date_lang_symbol.='>'.$value.'</option>';
    }






    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $where_currency_symbol          =   '';
    $where_currency_symbol_array    =   array('before','after');
    $where_currency_symbol_status   =   esc_html( get_option('wp_estate_where_currency_symbol') );
    foreach($where_currency_symbol_array as $value){
            $where_currency_symbol.='<option value="'.$value.'"';
            if ($where_currency_symbol_status==$value){
                $where_currency_symbol.=' selected="selected" ';
            }
            $where_currency_symbol.='>'.$value.'</option>';
    }

    
     ///////////////////////////////////////////////////////////////////////////////////////////////////////    
    $orphan_symbol='';
    $orphan_array_status= esc_html ( get_option('wp_estate_delete_orphan','') );

    foreach($social_array as $value){
            $orphan_symbol.='<option value="'.$value.'"';
            if ($orphan_array_status==$value){
                    $orphan_symbol.=' selected="selected" ';
            }
            $orphan_symbol.='>'.$value.'</option>';
    }
    
    
    $separate_users_symbol='';
    $separate_users_status= esc_html ( get_option('wp_estate_separate_users','') );

    foreach($social_array as $value){
            $separate_users_symbol.='<option value="'.$value.'"';
            if ($separate_users_status==$value){
                    $separate_users_symbol.=' selected="selected" ';
            }
            $separate_users_symbol.='>'.$value.'</option>';
    }       
            
    $publish_only               =   esc_html( get_option('wp_estate_publish_only') );
    
    
    
    
    $show_submit_symbol='';
    $show_submit_status= esc_html ( get_option('wp_estate_show_submit','') );

    foreach($social_array as $value){
            $show_submit_symbol.='<option value="'.$value.'"';
            if ($show_submit_status==$value){
                    $show_submit_symbol.=' selected="selected" ';
            }
            $show_submit_symbol.='>'.$value.'</option>';
    }       
       
      
    $setup_weekend_symbol='';
    $setup_weekend_status= esc_html ( get_option('wp_estate_setup_weekend','') );
    $weekedn = array( 
            0 => __("Sunday and Saturday","wpestate"),
            1 => __("Friday and Saturday","wpestate"),
            2 => __("Friday, Saturday and Sunday","wpestate")
            );
    
    foreach($weekedn as $key=>$value){
            $setup_weekend_symbol.='<option value="'.$key.'"';
            if ($setup_weekend_status==$key){
                    $setup_weekend_symbol.=' selected="selected" ';
            }
            $setup_weekend_symbol.='>'.$value.'</option>';
    }       
       
    
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'General Settings','wpestate').'</h1>';  
    print '<table class="form-table">
        <tr valign="top">
            <th scope="row"><label for="logo_image">'.esc_html__( 'Your Logo','wpestate').'</label></th>
            <td>
	         <input id="logo_image" type="text" size="36" name="logo_image" value="'.$logo_image.'" />
		<input id="logo_image_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Logo','wpestate').'" />
            </td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="transparent_logo_image">'.esc_html__( 'Your Logo (*transparent header)','wpestate').'</label></th>
            <td>
	         <input id="transparent_logo_image" type="text" size="36" name="transparent_logo_image" value="'.$transparent_logo_image.'" />
		<input id="transparent_logo_image_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Logo','wpestate').'" />
            </td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="mobile_logo_image">'.esc_html__( 'Your Logo for mobile devices','wpestate').'</label></th>
            <td>
	         <input id="mobile_logo_image" type="text" size="36" name="mobile_logo_image" value="'.$mobile_logo_image.'" />
		<input id="mobile_logo_image_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Logo','wpestate').'" />
            </td>
        </tr> 
        
        <!--
        <tr valign="top">
            <th scope="row"><label for="footer_logo_image">'.esc_html__( 'Retina ready logo (add _2x after the name. For ex logo_2x.jpg) ','wpestate').'</label></th>
            <td>
	         <input id="footer_logo_image" type="text" size="36" name="footer_logo_image" value="'.$footer_logo_image.'" />
		<input id="footer_logo_image_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Logo','wpestate').'" />
            </td>
        </tr> 
        -->
        <tr valign="top">
            <th scope="row"><label for="favicon_image">'.esc_html__( 'Your Favicon','wpestate').'</label></th>
            <td>
	        <input id="favicon_image" type="text" size="36" name="favicon_image" value="'.$favicon_image.'" />
		<input id="favicon_image_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Favicon','wpestate').'" />
            </td>
        </tr> 
        

        <tr valign="top">
            <th scope="row"><label for="logo_image"></label></th>
            <td>
              '.esc_html__( 'Make sure you upload the retina logos in the same time with normal logos(the normal and retina logo need to be in the same folder).Add _2x after the name. For ex logo_2x.jpg)','wpestate').'
            </td>
        </tr> 
        <tr valign="top">
            <th scope="row"><label for="logo_image">'.esc_html__( 'Your Retina Logo','wpestate').'</label></th>
            <td>
                <input id="logo_image_retina" type="text" size="36" name="logo_image_retina" value="'.$logo_image_retina.'" />
		<input id="logo_image_retina_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Logo','wpestate').'" />
            </td>
        </tr> 

        <tr valign="top">
            <th scope="row"><label for="logo_image">'.esc_html__( 'Your Transparent Retina Logo','wpestate').'</label></th>
            <td>
	         <input id="transparent_logo_image_retina" type="text" size="36" name="transparent_logo_image_retina" value="'.$transparent_logo_image_retina.'" />
		<input id="transparent_logo_image_retina_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Logo','wpestate').'" />
            </td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="logo_image">'.esc_html__( 'Your Mobile Retina Logo','wpestate').'</label></th>
            <td>
	         <input id="mobile_logo_image_retina" type="text" size="36" name="mobile_logo_image_retina" value="'.$mobile_logo_image_retina.'" />
		<input id="mobile_logo_image_retina_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Logo','wpestate').'" />
            </td>
        </tr> 
        



        <tr valign="top">
            <th scope="row"><label for="google_analytics_code">'.esc_html__( 'Google Analytics Tracking id (ex UA-41924406-1)','wpestate').'</label></th>
            <td><input cols="57" rows="2" name="google_analytics_code" id="google_analytics_code" value="'.$google_analytics_code.'"></input></td>
        </tr>
        
    
        
       
          <tr valign="top">
             <th scope="row"><label for="country_list">'.esc_html__( 'Country:','wpestate').'</label></th>
             <td>'.wpestate_general_country_list($general_country).'</td>
        </tr>
        
         <tr valign="top">
            <th scope="row"><label for="">'.esc_html__( 'Measurement Unit','wpestate').'</label></th>
            <td> <select id="measure_sys" name="measure_sys">
                    '.$measure_sys.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="">'.esc_html__( 'Language for datepicker','wpestate').'</label></th>
            <td> <select id="date_lang" name="date_lang">
                    '.$date_lang_symbol.'
		 </select>
            </td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="separate_users">'.esc_html__( 'Show submit property button on header ?','wpestate').'</label></th>
            <td> <select id="show_submit" name="show_submit">
                    '.$show_submit_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="setup_weekend">'.esc_html__( 'Select Weekend days','wpestate').'</label></th>
            <td> <select id="setup_weekend" name="setup_weekend">
                    '.$setup_weekend_symbol.'
		 </select>
            </td>
        </tr>';
        
        $enable_user_pass_symbol='';
        $enable_user_pass_status= esc_html ( get_option('wp_estate_enable_user_pass','') );

        foreach($social_array as $value){
                $enable_user_pass_symbol.='<option value="'.$value.'"';
                if ($enable_user_pass_status==$value){
                        $enable_user_pass_symbol.=' selected="selected" ';
                }
                $enable_user_pass_symbol.='>'.$value.'</option>';
        }       

        $use_captcha_symbol='';
        $use_captcha_status= esc_html ( get_option('wp_estate_use_captcha','') );

        foreach($social_array as $value){
                $use_captcha_symbol.='<option value="'.$value.'"';
                if ($use_captcha_status==$value){
                        $use_captcha_symbol.=' selected="selected" ';
                }
                $use_captcha_symbol.='>'.$value.'</option>';
        }   
        
        
        $recaptha_sitekey               =   esc_html( get_option('wp_estate_recaptha_sitekey') );
        $recaptha_secretkey               =   esc_html( get_option('wp_estate_recaptha_secretkey') );
        
        print'
        <tr valign="top">
            <th scope="row"><label for="enable_user_pass">'.esc_html__( 'Users can type the password on registration form','wpestate').'</label></th>
            <td> <select id="enable_user_pass" name="enable_user_pass">
                    '.$enable_user_pass_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="use_captcha">'.esc_html__( 'Use reCaptcha on register ?','wpestate').'</label></th>
            <td> <select id="use_captcha" name="use_captcha">
                    '.$use_captcha_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="recaptha_sitekey">'.esc_html__( 'reCaptha site key. Get this key after you signup  ','wpestate').'<a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">'.esc_html__('here','wpestate').'</a></label></th>
           <td><textarea  name="recaptha_sitekey" id="recaptha_sitekey" >'.$recaptha_sitekey.'</textarea></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="recaptha_secretkey">'.esc_html__( 'reCaptha secret key. Get this key after you signup ','wpestate').'<a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">'.esc_html__('here','wpestate').'</a></label></th>
           <td><textarea  name="recaptha_secretkey" id="recaptha_secretkey" >'.$recaptha_secretkey.'</textarea></td>
        </tr>
        
        


        <tr valign="top">
            <th scope="row"><label for="delete_orphan">'.esc_html__( 'Auto delete orphan listings (Listings that users started to submit but did not complete - cron will run 1 time per day)','wpestate').'</label></th>
            <td> <select id="delete_orphan" name="delete_orphan">
                    '.$orphan_symbol.'
		 </select>
            </td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="separate_users">'.esc_html__( 'Separate users on registration - there will be 2 user types: who can only book and who can rent & book','wpestate').'</label></th>
            <td> <select id="separate_users" name="separate_users">
                    '.$separate_users_symbol.'
		 </select>
            </td>
        </tr>
        

         <tr valign="top">
            <th scope="row"><label for="publish_only">'.esc_html__( 'Only these users can publish (separate SUBCRIBERS usernames with ,). It must be used with the option "Separate users on registration" set on NO.','wpestate').'</label></th>
           <td><textarea  name="publish_only" id="publish_only" >'.$publish_only.'</textarea></td>
        </tr>

        </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="'.esc_html__( 'Save Changes','wpestate').'" />
    </p>    
    ';
    
 print '</div>';   
}
endif; // end   wpestate_theme_admin_general_settings  



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Social $  Contact
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_theme_admin_social') ):
function wpestate_theme_admin_social(){
    $fax_ac                     =   esc_html ( get_option('wp_estate_fax_ac','') );
    $skype_ac                   =   esc_html ( get_option('wp_estate_skype_ac','') );
    $telephone_no               =   esc_html ( get_option('wp_estate_telephone_no','') );
    $mobile_no                  =   esc_html ( get_option('wp_estate_mobile_no','') );
    $company_name               =   esc_html ( stripslashes( get_option('wp_estate_company_name','') ) );
    $email_adr                  =   esc_html ( get_option('wp_estate_email_adr','') );
  
    
    $co_address                 =   esc_html ( get_option('wp_estate_co_address','') );
    $facebook_link              =   esc_html ( get_option('wp_estate_facebook_link','') );
    $twitter_link               =   esc_html ( get_option('wp_estate_twitter_link','') );
    $google_link                =   esc_html ( get_option('wp_estate_google_link','') );
    $linkedin_link              =   esc_html ( get_option('wp_estate_linkedin_link','') );
    $pinterest_link             =   esc_html ( get_option('wp_estate_pinterest_link','') );
    
    $twitter_consumer_key       =   esc_html ( get_option('wp_estate_twitter_consumer_key','') );
    $twitter_consumer_secret    =   esc_html ( get_option('wp_estate_twitter_consumer_secret','') );
    $twitter_access_token       =   esc_html ( get_option('wp_estate_twitter_access_token','') );
    $twitter_access_secret      =   esc_html ( get_option('wp_estate_twitter_access_secret','') );
    $twitter_cache_time         =   intval   ( get_option('wp_estate_twitter_cache_time','') );
   
    $facebook_api               =   esc_html ( get_option('wp_estate_facebook_api','') );
    $facebook_secret            =   esc_html ( get_option('wp_estate_facebook_secret','') );
   
    
    $google_oauth_api           =   esc_html ( get_option('wp_estate_google_oauth_api','') );
    $google_oauth_client_secret =   esc_html ( get_option('wp_estate_google_oauth_client_secret','') );
    $google_api_key             =   esc_html ( get_option('wp_estate_google_api_key','') );
    
    
    $social_array               =   array('no','yes');
    $facebook_login_select='';
    $facebook_status  =   esc_html( get_option('wp_estate_facebook_login','') );

    foreach($social_array as $value){
            $facebook_login_select.='<option value="'.$value.'"';
            if ($facebook_status==$value){
                $facebook_login_select.=' selected="selected" ';
            }
            $facebook_login_select.='>'.$value.'</option>';
    }


    $google_login_select='';
    $google_status  =   esc_html( get_option('wp_estate_google_login','') );

    foreach($social_array as $value){
            $google_login_select.='<option value="'.$value.'"';
            if ($google_status==$value){
                $google_login_select.=' selected="selected" ';
            }
            $google_login_select.='>'.$value.'</option>';
    }


    $yahoo_login_select='';
    $yahoo_status  =   esc_html( get_option('wp_estate_yahoo_login','') );

    foreach($social_array as $value){
            $yahoo_login_select.='<option value="'.$value.'"';
            if ($yahoo_status==$value){
                $yahoo_login_select.=' selected="selected" ';
            }
            $yahoo_login_select.='>'.$value.'</option>';
    }

    
    $social_register_select='';
    $social_register_on  =   esc_html( get_option('wp_estate_social_register_on','') );

    foreach($social_array as $value){
            $social_register_select.='<option value="'.$value.'"';
            if ($social_register_on==$value){
                $social_register_select.=' selected="selected" ';
            }
            $social_register_select.='>'.$value.'</option>';
    }

    
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">Social</h1>';
    
    print '<table class="form-table">     
        <tr valign="top">
            <th scope="row"><label for="company_name">'.esc_html__( 'Company Name','wpestate').'</label></th>
            <td>  <input id="company_name" type="text" size="36" name="company_name" value="'.$company_name.'" /></td>
        </tr>   
        
    	<tr valign="top">
            <th scope="row"><label for="email_adr">'.esc_html__( 'Email','wpestate').'</label></th>
            <td>  <input id="email_adr" type="text" size="36" name="email_adr" value="'.$email_adr.'" /></td>
        </tr>    
        
        <tr valign="top">
            <th scope="row"><label for="telephone_no">'.esc_html__( 'Telephone','wpestate').'</label></th>
            <td>  <input id="telephone_no" type="text" size="36" name="telephone_no" value="'.$telephone_no.'" /></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="mobile_no">'.esc_html__( 'Mobile','wpestate').'</label></th>
            <td>  <input id="mobile_no" type="text" size="36" name="mobile_no" value="'.$mobile_no.'" /></td>
        </tr> 
        
         <tr valign="top">
            <th scope="row"><label for="fax_ac">'.esc_html__( 'Fax','wpestate').'</label></th>
            <td>  <input id="fax_ac" type="text" size="36" name="fax_ac" value="'.$fax_ac.'" /></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="skype_ac">'.esc_html__( 'Skype','wpestate').'</label></th>
            <td>  <input id="skype_ac" type="text" size="36" name="skype_ac" value="'.$skype_ac.'" /></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="co_address">'.esc_html__( 'Address','wpestate').'</label></th>
            <td><textarea cols="57" rows="2" name="co_address" id="co_address">'.$co_address.'</textarea></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="facebook_link">'.esc_html__( 'Facebook Link','wpestate').'</label></th>
            <td>  <input id="facebook_link" type="text" size="36" name="facebook_link" value="'.$facebook_link.'" /></td>
        </tr>        
        
        <tr valign="top">
            <th scope="row"><label for="twitter_link">'.esc_html__( 'Twitter Page Link','wpestate').'</label></th>
            <td>  <input id="twitter_link" type="text" size="36" name="twitter_link" value="'.$twitter_link.'" /></td>
        </tr>
         
        <tr valign="top">
            <th scope="row"><label for="google_link">'.esc_html__( 'Google+ Link','wpestate').'</label></th>
            <td>  <input id="google_link" type="text" size="36" name="google_link" value="'.$google_link.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="pinterest_link">'.esc_html__( 'Pinterest Link','wpestate').'</label></th>
            <td>  <input id="pinterest_link" type="text" size="36" name="pinterest_link" value="'.$pinterest_link.'" /></td>
        </tr>
        
      <tr valign="top">
            <th scope="row"><label for="linkedin_link">'.esc_html__( 'Linkedin Link','wpestate').'</label></th>
            <td>  <input id="linkedin_link" type="text" size="36" name="linkedin_link" value="'.$linkedin_link.'" /></td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="twitter_consumer_key">'.esc_html__( 'Twitter Consumer Key','wpestate').'</label></th>
            <td>  <input id="twitter_consumer_key" type="text" size="36" name="twitter_consumer_key" value="'.$twitter_consumer_key.'" /></td>
        </tr>
        
         <tr valign="top">
            <th scope="row"><label for="twitter_consumer_secret">'.esc_html__( 'Twitter Consumer Secret','wpestate').'</label></th>
            <td>  <input id="twitter_consumer_secret" type="text" size="36" name="twitter_consumer_secret" value="'.$twitter_consumer_secret.'" /></td>
        </tr>
        
         <tr valign="top">
            <th scope="row"><label for="twitter_access_token">'.esc_html__( 'Twitter Access Token','wpestate').'</label></th>
            <td>  <input id="twitter_account" type="text" size="36" name="twitter_access_token" value="'.$twitter_access_token.'" /></td>
        </tr>
        
         <tr valign="top">
            <th scope="row"><label for="twitter_access_secret">'.esc_html__( 'Twitter Access Token Secret','wpestate').'</label></th>
            <td>  <input id="twitter_access_secret" type="text" size="36" name="twitter_access_secret" value="'.$twitter_access_secret.'" /></td>
        </tr>
        
         <tr valign="top">
            <th scope="row"><label for="twitter_cache_time">'.esc_html__( 'Twitter Cache Time in hours','wpestate').'</label></th>
            <td>  <input id="twitter_cache_time" type="text" size="36" name="twitter_cache_time" value="'.$twitter_cache_time.'" /></td>
        </tr>
         
        <tr valign="top">
            <th scope="row"><label for="facebook_api">'.esc_html__( 'Facebook Api Key (for Facebook login)','wpestate').'</label></th>
            <td>  <input id="facebook_api" type="text" size="36" name="facebook_api" value="'.$facebook_api.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="facebook_secret">'.esc_html__( 'Facebook secret code (for Facebook login) ','wpestate').'</label></th>
            <td>  <input id="facebook_secret" type="text" size="36" name="facebook_secret" value="'.$facebook_secret.'" /></td>
        </tr>
       
        <tr valign="top">
            <th scope="row"><label for="google_oauth_api">'.esc_html__( 'Google OAuth client id (for Google login)','wpestate').'</label></th>
            <td>  <input id="google_oauth_api" type="text" size="36" name="google_oauth_api" value="'.$google_oauth_api.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="google_oauth_client_secret">'.esc_html__( 'Google Client Secret (for Google login)','wpestate').'</label></th>
            <td>  <input id="google_oauth_client_secret" type="text" size="36" name="google_oauth_client_secret" value="'.$google_oauth_client_secret.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="google_api_key">'.esc_html__( 'Google Api key (for Google login)','wpestate').'</label></th>
            <td>  <input id="google_api_key" type="text" size="36" name="google_api_key" value="'.$google_api_key.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="facebook_login">'.esc_html__( 'Allow login via Facebook ? ','wpestate').'</label></th>
            <td> <select id="facebook_login" name="facebook_login">
                    '.$facebook_login_select.'
                </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="google_login">'.esc_html__( 'Allow login via Google ?','wpestate').' </label></th>
            <td> <select id="google_login" name="google_login">
                    '.$google_login_select.'
                </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="yahoo_login">'.esc_html__( 'Allow login via Yahoo ? ','wpestate').'</label></th>
            <td> <select id="yahoo_login" name="yahoo_login">
                    '.$yahoo_login_select.'
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="social_register_on">'.esc_html__( 'Display social login also on register modal window ? ','wpestate').'</label></th>
            <td> <select id="social_register_on" name="social_register_on">
                    '.$social_register_select.'
                </select>
            </td>
        </tr>



    </table>
    <p class="submit">
      <input type="submit" name="submit" id="submit" class="button-primary"  value="'.esc_html__( 'Save Changes','wpestate').'" />
    </p>';
print '</div>';
}
endif; // end   wpestate_theme_admin_social  




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Apperance
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_theme_admin_apperance') ):
function wpestate_theme_admin_apperance(){
    $cache_array                =   array('yes','no');
    $prop_no                    =   intval   ( get_option('wp_estate_prop_no','') );
    $blog_sidebar_name          =   esc_html ( get_option('wp_estate_blog_sidebar_name','') );
    $copyright_message          =   stripslashes ( esc_html ( get_option('wp_estate_copyright_message','') ) );
    $logo_margin                =   intval( get_option('wp_estate_logo_margin','') ); 
    ///////////////////////////////////////////////////////////////////////////////////////////////////////    

            


    
    $show_empty_city_status_symbol='';
    $show_empty_city_status= esc_html ( get_option('wp_estate_show_empty_city','') );

    foreach($cache_array as $value){
            $show_empty_city_status_symbol.='<option value="'.$value.'"';
            if ($show_empty_city_status==$value){
                    $show_empty_city_status_symbol.=' selected="selected" ';
            }
            $show_empty_city_status_symbol.='>'.$value.'</option>';
    }


    $show_top_bar_user_menu_symbol='';
    $show_top_bar_user_menu_status= esc_html ( get_option('wp_estate_show_top_bar_user_menu','') );    
    
    foreach($cache_array as $value){
       $show_top_bar_user_menu_symbol.='<option value="'.$value.'"';
       if ($show_top_bar_user_menu_status==$value){
               $show_top_bar_user_menu_symbol.=' selected="selected" ';
       }
       $show_top_bar_user_menu_symbol.='>'.$value.'</option>';
    }
 
        
    $show_top_bar_user_login_symbol='';
    $show_top_bar_user_login_status= esc_html ( get_option('wp_estate_show_top_bar_user_login','') );    
    
    foreach($cache_array as $value){
       $show_top_bar_user_login_symbol.='<option value="'.$value.'"';
       if ($show_top_bar_user_login_status==$value){
               $show_top_bar_user_login_symbol.=' selected="selected" ';
       }
       $show_top_bar_user_login_symbol.='>'.$value.'</option>';
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    $prop_list_slider_symbol='';
    $prop_list_slider_status= esc_html ( get_option('wp_estate_prop_list_slider','') );    
    
    foreach($cache_array as $value){
       $prop_list_slider_symbol.='<option value="'.$value.'"';
       if ($prop_list_slider_status==$value){
               $prop_list_slider_symbol.=' selected="selected" ';
       }
       $prop_list_slider_symbol.='>'.$value.'</option>';
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $blog_sidebar_name_select='';
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { 
        $blog_sidebar_name_select.='<option value="'.($sidebar['id'] ).'"';
            if($blog_sidebar_name==$sidebar['id']){ 
               $blog_sidebar_name_select.=' selected="selected"';
            }
        $blog_sidebar_name_select.=' >'.ucwords($sidebar['name']).'</option>';
    } 
    
  
            
    

    ///////////////////////////////////////////////////////////////////////////////////////////////////////    
    $blog_sidebar_select ='';
    $blog_sidebar= esc_html ( get_option('wp_estate_blog_sidebar','') );
    $blog_sidebar_array=array('no sidebar','right','left');

    foreach($blog_sidebar_array as $value){
            $blog_sidebar_select.='<option value="'.$value.'"';
            if ($blog_sidebar==$value){
                    $blog_sidebar_select.='selected="selected"';
            }
            $blog_sidebar_select.='>'.$value.'</option>';
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $general_font_select='';
    $general_font= esc_html ( get_option('wp_estate_general_font','') );
    if($general_font!='x'){
    $general_font_select='<option value="'.$general_font.'">'.$general_font.'</option>';
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////
  


    $wide_array=array(
               "1"  =>  esc_html__( "wide","wpestate"),
               "2"  =>  esc_html__( "boxed","wpestate")
            );
    $wide_status_symbol     =   '';
    $wide_status_status     =   esc_html(get_option('wp_estate_wide_status',''));
    
    
    foreach($wide_array as $key => $value){
        $wide_status_symbol.='<option value="'.$key.'"';
        if ($wide_status_status == $key){
                $wide_status_symbol.=' selected="selected" ';
        }
        $wide_status_symbol.='> '.$value.'</option>';
    }
  
    
    $prop_list_array=array(
               "1"  =>  esc_html__( "standard ","wpestate"),
               "2"  =>  esc_html__( "half map","wpestate")
            );
    $property_list_type_symbol =    '';
    $property_list_type_status =    esc_html(get_option('wp_estate_property_list_type',''));
    
    foreach($prop_list_array as $key => $value){
        $property_list_type_symbol.='<option value="'.$key.'"';
        if ($property_list_type_status == $key){
                $property_list_type_symbol.=' selected="selected" ';
        }
        $property_list_type_symbol.='> '.$value.'</option>';
    }
  
    
    $property_list_type_symbol_adv =    '';
    $property_list_type_status_adv =    esc_html(get_option('wp_estate_property_list_type_adv',''));
    
    foreach($prop_list_array as $key => $value){
        $property_list_type_symbol_adv.='<option value="'.$key.'"';
        if ($property_list_type_status_adv == $key){
                $property_list_type_symbol_adv.=' selected="selected" ';
        }
        $property_list_type_symbol_adv.='> '.$value.'</option>';
    }
  
    
    $use_upload_tax_page_symbol='';
    $use_upload_tax_page_status= esc_html ( get_option('wp_estate_use_upload_tax_page','') );

    foreach($cache_array as $value){
            $use_upload_tax_page_symbol.='<option value="'.$value.'"';
            if ($use_upload_tax_page_status==$value){
                    $use_upload_tax_page_symbol.=' selected="selected" ';
            }
            $use_upload_tax_page_symbol.='>'.$value.'</option>';
    }
    

    $google_fonts_array = array(                          
                                                            "Abel" => "Abel",
                                                            "Abril Fatface" => "Abril Fatface",
                                                            "Aclonica" => "Aclonica",
                                                            "Acme" => "Acme",
                                                            "Actor" => "Actor",
                                                            "Adamina" => "Adamina",
                                                            "Advent Pro" => "Advent Pro",
                                                            "Aguafina Script" => "Aguafina Script",
                                                            "Aladin" => "Aladin",
                                                            "Aldrich" => "Aldrich",
                                                            "Alegreya" => "Alegreya",
                                                            "Alegreya SC" => "Alegreya SC",
                                                            "Alex Brush" => "Alex Brush",
                                                            "Alfa Slab One" => "Alfa Slab One",
                                                            "Alice" => "Alice",
                                                            "Alike" => "Alike",
                                                            "Alike Angular" => "Alike Angular",
                                                            "Allan" => "Allan",
                                                            "Allerta" => "Allerta",
                                                            "Allerta Stencil" => "Allerta Stencil",
                                                            "Allura" => "Allura",
                                                            "Almendra" => "Almendra",
                                                            "Almendra SC" => "Almendra SC",
                                                            "Amaranth" => "Amaranth",
                                                            "Amatic SC" => "Amatic SC",
                                                            "Amethysta" => "Amethysta",
                                                            "Andada" => "Andada",
                                                            "Andika" => "Andika",
                                                            "Angkor" => "Angkor",
                                                            "Annie Use Your Telescope" => "Annie Use Your Telescope",
                                                            "Anonymous Pro" => "Anonymous Pro",
                                                            "Antic" => "Antic",
                                                            "Antic Didone" => "Antic Didone",
                                                            "Antic Slab" => "Antic Slab",
                                                            "Anton" => "Anton",
                                                            "Arapey" => "Arapey",
                                                            "Arbutus" => "Arbutus",
                                                            "Architects Daughter" => "Architects Daughter",
                                                            "Arimo" => "Arimo",
                                                            "Arizonia" => "Arizonia",
                                                            "Armata" => "Armata",
                                                            "Artifika" => "Artifika",
                                                            "Arvo" => "Arvo",
                                                            "Asap" => "Asap",
                                                            "Asset" => "Asset",
                                                            "Astloch" => "Astloch",
                                                            "Asul" => "Asul",
                                                            "Atomic Age" => "Atomic Age",
                                                            "Aubrey" => "Aubrey",
                                                            "Audiowide" => "Audiowide",
                                                            "Average" => "Average",
                                                            "Averia Gruesa Libre" => "Averia Gruesa Libre",
                                                            "Averia Libre" => "Averia Libre",
                                                            "Averia Sans Libre" => "Averia Sans Libre",
                                                            "Averia Serif Libre" => "Averia Serif Libre",
                                                            "Bad Script" => "Bad Script",
                                                            "Balthazar" => "Balthazar",
                                                            "Bangers" => "Bangers",
                                                            "Basic" => "Basic",
                                                            "Battambang" => "Battambang",
                                                            "Baumans" => "Baumans",
                                                            "Bayon" => "Bayon",
                                                            "Belgrano" => "Belgrano",
                                                            "Belleza" => "Belleza",
                                                            "Bentham" => "Bentham",
                                                            "Berkshire Swash" => "Berkshire Swash",
                                                            "Bevan" => "Bevan",
                                                            "Bigshot One" => "Bigshot One",
                                                            "Bilbo" => "Bilbo",
                                                            "Bilbo Swash Caps" => "Bilbo Swash Caps",
                                                            "Bitter" => "Bitter",
                                                            "Black Ops One" => "Black Ops One",
                                                            "Bokor" => "Bokor",
                                                            "Bonbon" => "Bonbon",
                                                            "Boogaloo" => "Boogaloo",
                                                            "Bowlby One" => "Bowlby One",
                                                            "Bowlby One SC" => "Bowlby One SC",
                                                            "Brawler" => "Brawler",
                                                            "Bree Serif" => "Bree Serif",
                                                            "Bubblegum Sans" => "Bubblegum Sans",
                                                            "Buda" => "Buda",
                                                            "Buenard" => "Buenard",
                                                            "Butcherman" => "Butcherman",
                                                            "Butterfly Kids" => "Butterfly Kids",
                                                            "Cabin" => "Cabin",
                                                            "Cabin Condensed" => "Cabin Condensed",
                                                            "Cabin Sketch" => "Cabin Sketch",
                                                            "Caesar Dressing" => "Caesar Dressing",
                                                            "Cagliostro" => "Cagliostro",
                                                            "Calligraffitti" => "Calligraffitti",
                                                            "Cambo" => "Cambo",
                                                            "Candal" => "Candal",
                                                            "Cantarell" => "Cantarell",
                                                            "Cantata One" => "Cantata One",
                                                            "Cardo" => "Cardo",
                                                            "Carme" => "Carme",
                                                            "Carter One" => "Carter One",
                                                            "Caudex" => "Caudex",
                                                            "Cedarville Cursive" => "Cedarville Cursive",
                                                            "Ceviche One" => "Ceviche One",
                                                            "Changa One" => "Changa One",
                                                            "Chango" => "Chango",
                                                            "Chau Philomene One" => "Chau Philomene One",
                                                            "Chelsea Market" => "Chelsea Market",
                                                            "Chenla" => "Chenla",
                                                            "Cherry Cream Soda" => "Cherry Cream Soda",
                                                            "Chewy" => "Chewy",
                                                            "Chicle" => "Chicle",
                                                            "Chivo" => "Chivo",
                                                            "Coda" => "Coda",
                                                            "Coda Caption" => "Coda Caption",
                                                            "Codystar" => "Codystar",
                                                            "Comfortaa" => "Comfortaa",
                                                            "Coming Soon" => "Coming Soon",
                                                            "Concert One" => "Concert One",
                                                            "Condiment" => "Condiment",
                                                            "Content" => "Content",
                                                            "Contrail One" => "Contrail One",
                                                            "Convergence" => "Convergence",
                                                            "Cookie" => "Cookie",
                                                            "Copse" => "Copse",
                                                            "Corben" => "Corben",
                                                            "Cousine" => "Cousine",
                                                            "Coustard" => "Coustard",
                                                            "Covered By Your Grace" => "Covered By Your Grace",
                                                            "Crafty Girls" => "Crafty Girls",
                                                            "Creepster" => "Creepster",
                                                            "Crete Round" => "Crete Round",
                                                            "Crimson Text" => "Crimson Text",
                                                            "Crushed" => "Crushed",
                                                            "Cuprum" => "Cuprum",
                                                            "Cutive" => "Cutive",
                                                            "Damion" => "Damion",
                                                            "Dancing Script" => "Dancing Script",
                                                            "Dangrek" => "Dangrek",
                                                            "Dawning of a New Day" => "Dawning of a New Day",
                                                            "Days One" => "Days One",
                                                            "Delius" => "Delius",
                                                            "Delius Swash Caps" => "Delius Swash Caps",
                                                            "Delius Unicase" => "Delius Unicase",
                                                            "Della Respira" => "Della Respira",
                                                            "Devonshire" => "Devonshire",
                                                            "Didact Gothic" => "Didact Gothic",
                                                            "Diplomata" => "Diplomata",
                                                            "Diplomata SC" => "Diplomata SC",
                                                            "Doppio One" => "Doppio One",
                                                            "Dorsa" => "Dorsa",
                                                            "Dosis" => "Dosis",
                                                            "Dr Sugiyama" => "Dr Sugiyama",
                                                            "Droid Sans" => "Droid Sans",
                                                            "Droid Sans Mono" => "Droid Sans Mono",
                                                            "Droid Serif" => "Droid Serif",
                                                            "Duru Sans" => "Duru Sans",
                                                            "Dynalight" => "Dynalight",
                                                            "EB Garamond" => "EB Garamond",
                                                            "Eater" => "Eater",
                                                            "Economica" => "Economica",
                                                            "Electrolize" => "Electrolize",
                                                            "Emblema One" => "Emblema One",
                                                            "Emilys Candy" => "Emilys Candy",
                                                            "Engagement" => "Engagement",
                                                            "Enriqueta" => "Enriqueta",
                                                            "Erica One" => "Erica One",
                                                            "Esteban" => "Esteban",
                                                            "Euphoria Script" => "Euphoria Script",
                                                            "Ewert" => "Ewert",
                                                            "Exo" => "Exo",
                                                            "Expletus Sans" => "Expletus Sans",
                                                            "Fanwood Text" => "Fanwood Text",
                                                            "Fascinate" => "Fascinate",
                                                            "Fascinate Inline" => "Fascinate Inline",
                                                            "Federant" => "Federant",
                                                            "Federo" => "Federo",
                                                            "Felipa" => "Felipa",
                                                            "Fjord One" => "Fjord One",
                                                            "Flamenco" => "Flamenco",
                                                            "Flavors" => "Flavors",
                                                            "Fondamento" => "Fondamento",
                                                            "Fontdiner Swanky" => "Fontdiner Swanky",
                                                            "Forum" => "Forum",
                                                            "Francois One" => "Francois One",
                                                            "Fredericka the Great" => "Fredericka the Great",
                                                            "Fredoka One" => "Fredoka One",
                                                            "Freehand" => "Freehand",
                                                            "Fresca" => "Fresca",
                                                            "Frijole" => "Frijole",
                                                            "Fugaz One" => "Fugaz One",
                                                            "GFS Didot" => "GFS Didot",
                                                            "GFS Neohellenic" => "GFS Neohellenic",
                                                            "Galdeano" => "Galdeano",
                                                            "Gentium Basic" => "Gentium Basic",
                                                            "Gentium Book Basic" => "Gentium Book Basic",
                                                            "Geo" => "Geo",
                                                            "Geostar" => "Geostar",
                                                            "Geostar Fill" => "Geostar Fill",
                                                            "Germania One" => "Germania One",
                                                            "Give You Glory" => "Give You Glory",
                                                            "Glass Antiqua" => "Glass Antiqua",
                                                            "Glegoo" => "Glegoo",
                                                            "Gloria Hallelujah" => "Gloria Hallelujah",
                                                            "Goblin One" => "Goblin One",
                                                            "Gochi Hand" => "Gochi Hand",
                                                            "Gorditas" => "Gorditas",
                                                            "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
                                                            "Graduate" => "Graduate",
                                                            "Gravitas One" => "Gravitas One",
                                                            "Great Vibes" => "Great Vibes",
                                                            "Gruppo" => "Gruppo",
                                                            "Gudea" => "Gudea",
                                                            "Habibi" => "Habibi",
                                                            "Hammersmith One" => "Hammersmith One",
                                                            "Handlee" => "Handlee",
                                                            "Hanuman" => "Hanuman",
                                                            "Happy Monkey" => "Happy Monkey",
                                                            "Henny Penny" => "Henny Penny",
                                                            "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
                                                            "Holtwood One SC" => "Holtwood One SC",
                                                            "Homemade Apple" => "Homemade Apple",
                                                            "Homenaje" => "Homenaje",
                                                            "IM Fell DW Pica" => "IM Fell DW Pica",
                                                            "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
                                                            "IM Fell Double Pica" => "IM Fell Double Pica",
                                                            "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
                                                            "IM Fell English" => "IM Fell English",
                                                            "IM Fell English SC" => "IM Fell English SC",
                                                            "IM Fell French Canon" => "IM Fell French Canon",
                                                            "IM Fell French Canon SC" => "IM Fell French Canon SC",
                                                            "IM Fell Great Primer" => "IM Fell Great Primer",
                                                            "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
                                                            "Iceberg" => "Iceberg",
                                                            "Iceland" => "Iceland",
                                                            "Imprima" => "Imprima",
                                                            "Inconsolata" => "Inconsolata",
                                                            "Inder" => "Inder",
                                                            "Indie Flower" => "Indie Flower",
                                                            "Inika" => "Inika",
                                                            "Irish Grover" => "Irish Grover",
                                                            "Istok Web" => "Istok Web",
                                                            "Italiana" => "Italiana",
                                                            "Italianno" => "Italianno",
                                                            "Jim Nightshade" => "Jim Nightshade",
                                                            "Jockey One" => "Jockey One",
                                                            "Jolly Lodger" => "Jolly Lodger",
                                                            "Josefin Sans" => "Josefin Sans",
                                                            "Josefin Slab" => "Josefin Slab",
                                                            "Judson" => "Judson",
                                                            "Julee" => "Julee",
                                                            "Junge" => "Junge",
                                                            "Jura" => "Jura",
                                                            "Just Another Hand" => "Just Another Hand",
                                                            "Just Me Again Down Here" => "Just Me Again Down Here",
                                                            "Kameron" => "Kameron",
                                                            "Karla" => "Karla",
                                                            "Kaushan Script" => "Kaushan Script",
                                                            "Kelly Slab" => "Kelly Slab",
                                                            "Kenia" => "Kenia",
                                                            "Khmer" => "Khmer",
                                                            "Knewave" => "Knewave",
                                                            "Kotta One" => "Kotta One",
                                                            "Koulen" => "Koulen",
                                                            "Kranky" => "Kranky",
                                                            "Kreon" => "Kreon",
                                                            "Kristi" => "Kristi",
                                                            "Krona One" => "Krona One",
                                                            "La Belle Aurore" => "La Belle Aurore",
                                                            "Lancelot" => "Lancelot",
                                                            "Lato" => "Lato",
                                                            "League Script" => "League Script",
                                                            "Leckerli One" => "Leckerli One",
                                                            "Ledger" => "Ledger",
                                                            "Lekton" => "Lekton",
                                                            "Lemon" => "Lemon",
                                                            "Lilita One" => "Lilita One",
                                                            "Limelight" => "Limelight",
                                                            "Linden Hill" => "Linden Hill",
                                                            "Lobster" => "Lobster",
                                                            "Lobster Two" => "Lobster Two",
                                                            "Londrina Outline" => "Londrina Outline",
                                                            "Londrina Shadow" => "Londrina Shadow",
                                                            "Londrina Sketch" => "Londrina Sketch",
                                                            "Londrina Solid" => "Londrina Solid",
                                                            "Lora" => "Lora",
                                                            "Love Ya Like A Sister" => "Love Ya Like A Sister",
                                                            "Loved by the King" => "Loved by the King",
                                                            "Lovers Quarrel" => "Lovers Quarrel",
                                                            "Luckiest Guy" => "Luckiest Guy",
                                                            "Lusitana" => "Lusitana",
                                                            "Lustria" => "Lustria",
                                                            "Macondo" => "Macondo",
                                                            "Macondo Swash Caps" => "Macondo Swash Caps",
                                                            "Magra" => "Magra",
                                                            "Maiden Orange" => "Maiden Orange",
                                                            "Mako" => "Mako",
                                                            "Marck Script" => "Marck Script",
                                                            "Marko One" => "Marko One",
                                                            "Marmelad" => "Marmelad",
                                                            "Marvel" => "Marvel",
                                                            "Mate" => "Mate",
                                                            "Mate SC" => "Mate SC",
                                                            "Maven Pro" => "Maven Pro",
                                                            "Meddon" => "Meddon",
                                                            "MedievalSharp" => "MedievalSharp",
                                                            "Medula One" => "Medula One",
                                                            "Megrim" => "Megrim",
                                                            "Merienda One" => "Merienda One",
                                                            "Merriweather" => "Merriweather",
                                                            "Metal" => "Metal",
                                                            "Metamorphous" => "Metamorphous",
                                                            "Metrophobic" => "Metrophobic",
                                                            "Michroma" => "Michroma",
                                                            "Miltonian" => "Miltonian",
                                                            "Miltonian Tattoo" => "Miltonian Tattoo",
                                                            "Miniver" => "Miniver",
                                                            "Miss Fajardose" => "Miss Fajardose",
                                                            "Modern Antiqua" => "Modern Antiqua",
                                                            "Molengo" => "Molengo",
                                                            "Monofett" => "Monofett",
                                                            "Monoton" => "Monoton",
                                                            "Monsieur La Doulaise" => "Monsieur La Doulaise",
                                                            "Montaga" => "Montaga",
                                                            "Montez" => "Montez",
                                                            "Montserrat" => "Montserrat",
                                                            "Moul" => "Moul",
                                                            "Moulpali" => "Moulpali",
                                                            "Mountains of Christmas" => "Mountains of Christmas",
                                                            "Mr Bedfort" => "Mr Bedfort",
                                                            "Mr Dafoe" => "Mr Dafoe",
                                                            "Mr De Haviland" => "Mr De Haviland",
                                                            "Mrs Saint Delafield" => "Mrs Saint Delafield",
                                                            "Mrs Sheppards" => "Mrs Sheppards",
                                                            "Muli" => "Muli",
                                                            "Mystery Quest" => "Mystery Quest",
                                                            "Neucha" => "Neucha",
                                                            "Neuton" => "Neuton",
                                                            "News Cycle" => "News Cycle",
                                                            "Niconne" => "Niconne",
                                                            "Nixie One" => "Nixie One",
                                                            "Nobile" => "Nobile",
                                                            "Nokora" => "Nokora",
                                                            "Norican" => "Norican",
                                                            "Nosifer" => "Nosifer",
                                                            "Nothing You Could Do" => "Nothing You Could Do",
                                                            "Noticia Text" => "Noticia Text",
                                                            "Nova Cut" => "Nova Cut",
                                                            "Nova Flat" => "Nova Flat",
                                                            "Nova Mono" => "Nova Mono",
                                                            "Nova Oval" => "Nova Oval",
                                                            "Nova Round" => "Nova Round",
                                                            "Nova Script" => "Nova Script",
                                                            "Nova Slim" => "Nova Slim",
                                                            "Nova Square" => "Nova Square",
                                                            "Numans" => "Numans",
                                                            "Nunito" => "Nunito",
                                                            "Odor Mean Chey" => "Odor Mean Chey",
                                                            "Old Standard TT" => "Old Standard TT",
                                                            "Oldenburg" => "Oldenburg",
                                                            "Oleo Script" => "Oleo Script",
                                                            "Open Sans" => "Open Sans",
                                                            "Open Sans Condensed" => "Open Sans Condensed",
                                                            "Orbitron" => "Orbitron",
                                                            "Original Surfer" => "Original Surfer",
                                                            "Oswald" => "Oswald",
                                                            "Over the Rainbow" => "Over the Rainbow",
                                                            "Overlock" => "Overlock",
                                                            "Overlock SC" => "Overlock SC",
                                                            "Ovo" => "Ovo",
                                                            "Oxygen" => "Oxygen",
                                                            "PT Mono" => "PT Mono",
                                                            "PT Sans" => "PT Sans",
                                                            "PT Sans Caption" => "PT Sans Caption",
                                                            "PT Sans Narrow" => "PT Sans Narrow",
                                                            "PT Serif" => "PT Serif",
                                                            "PT Serif Caption" => "PT Serif Caption",
                                                            "Pacifico" => "Pacifico",
                                                            "Parisienne" => "Parisienne",
                                                            "Passero One" => "Passero One",
                                                            "Passion One" => "Passion One",
                                                            "Patrick Hand" => "Patrick Hand",
                                                            "Patua One" => "Patua One",
                                                            "Paytone One" => "Paytone One",
                                                            "Permanent Marker" => "Permanent Marker",
                                                            "Petrona" => "Petrona",
                                                            "Philosopher" => "Philosopher",
                                                            "Piedra" => "Piedra",
                                                            "Pinyon Script" => "Pinyon Script",
                                                            "Plaster" => "Plaster",
                                                            "Play" => "Play",
                                                            "Playball" => "Playball",
                                                            "Playfair Display" => "Playfair Display",
                                                            "Podkova" => "Podkova",
                                                            "Poiret One" => "Poiret One",
                                                            "Poller One" => "Poller One",
                                                            "Poly" => "Poly",
                                                            "Pompiere" => "Pompiere",
                                                            "Pontano Sans" => "Pontano Sans",
                                                            "Port Lligat Sans" => "Port Lligat Sans",
                                                            "Port Lligat Slab" => "Port Lligat Slab",
                                                            "Prata" => "Prata",
                                                            "Preahvihear" => "Preahvihear",
                                                            "Press Start 2P" => "Press Start 2P",
                                                            "Princess Sofia" => "Princess Sofia",
                                                            "Prociono" => "Prociono",
                                                            "Prosto One" => "Prosto One",
                                                            "Puritan" => "Puritan",
                                                            "Quantico" => "Quantico",
                                                            "Quattrocento" => "Quattrocento",
                                                            "Quattrocento Sans" => "Quattrocento Sans",
                                                            "Questrial" => "Questrial",
                                                            "Quicksand" => "Quicksand",
                                                            "Qwigley" => "Qwigley",
                                                            "Radley" => "Radley",
                                                            "Raleway" => "Raleway",
                                                            "Rammetto One" => "Rammetto One",
                                                            "Rancho" => "Rancho",
                                                            "Rationale" => "Rationale",
                                                            "Redressed" => "Redressed",
                                                            "Reenie Beanie" => "Reenie Beanie",
                                                            "Revalia" => "Revalia",
                                                            "Ribeye" => "Ribeye",
                                                            "Ribeye Marrow" => "Ribeye Marrow",
                                                            "Righteous" => "Righteous",
                                                            "Rochester" => "Rochester",
                                                            "Rock Salt" => "Rock Salt",
                                                            "Rokkitt" => "Rokkitt",
                                                            "Ropa Sans" => "Ropa Sans",
                                                            "Rosario" => "Rosario",
                                                            "Rosarivo" => "Rosarivo",
                                                            "Rouge Script" => "Rouge Script",
                                                            "Ruda" => "Ruda",
                                                            "Ruge Boogie" => "Ruge Boogie",
                                                            "Ruluko" => "Ruluko",
                                                            "Ruslan Display" => "Ruslan Display",
                                                            "Russo One" => "Russo One",
                                                            "Ruthie" => "Ruthie",
                                                            "Sail" => "Sail",
                                                            "Salsa" => "Salsa",
                                                            "Sancreek" => "Sancreek",
                                                            "Sansita One" => "Sansita One",
                                                            "Sarina" => "Sarina",
                                                            "Satisfy" => "Satisfy",
                                                            "Schoolbell" => "Schoolbell",
                                                            "Seaweed Script" => "Seaweed Script",
                                                            "Sevillana" => "Sevillana",
                                                            "Shadows Into Light" => "Shadows Into Light",
                                                            "Shadows Into Light Two" => "Shadows Into Light Two",
                                                            "Shanti" => "Shanti",
                                                            "Share" => "Share",
                                                            "Shojumaru" => "Shojumaru",
                                                            "Short Stack" => "Short Stack",
                                                            "Siemreap" => "Siemreap",
                                                            "Sigmar One" => "Sigmar One",
                                                            "Signika" => "Signika",
                                                            "Signika Negative" => "Signika Negative",
                                                            "Simonetta" => "Simonetta",
                                                            "Sirin Stencil" => "Sirin Stencil",
                                                            "Six Caps" => "Six Caps",
                                                            "Slackey" => "Slackey",
                                                            "Smokum" => "Smokum",
                                                            "Smythe" => "Smythe",
                                                            "Sniglet" => "Sniglet",
                                                            "Snippet" => "Snippet",
                                                            "Sofia" => "Sofia",
                                                            "Sonsie One" => "Sonsie One",
                                                            "Sorts Mill Goudy" => "Sorts Mill Goudy",
                                                            "Special Elite" => "Special Elite",
                                                            "Spicy Rice" => "Spicy Rice",
                                                            "Spinnaker" => "Spinnaker",
                                                            "Spirax" => "Spirax",
                                                            "Squada One" => "Squada One",
                                                            "Stardos Stencil" => "Stardos Stencil",
                                                            "Stint Ultra Condensed" => "Stint Ultra Condensed",
                                                            "Stint Ultra Expanded" => "Stint Ultra Expanded",
                                                            "Stoke" => "Stoke",
                                                            "Sue Ellen Francisco" => "Sue Ellen Francisco",
                                                            "Sunshiney" => "Sunshiney",
                                                            "Supermercado One" => "Supermercado One",
                                                            "Suwannaphum" => "Suwannaphum",
                                                            "Swanky and Moo Moo" => "Swanky and Moo Moo",
                                                            "Syncopate" => "Syncopate",
                                                            "Tangerine" => "Tangerine",
                                                            "Taprom" => "Taprom",
                                                            "Telex" => "Telex",
                                                            "Tenor Sans" => "Tenor Sans",
                                                            "The Girl Next Door" => "The Girl Next Door",
                                                            "Tienne" => "Tienne",
                                                            "Tinos" => "Tinos",
                                                            "Titan One" => "Titan One",
                                                            "Trade Winds" => "Trade Winds",
                                                            "Trocchi" => "Trocchi",
                                                            "Trochut" => "Trochut",
                                                            "Trykker" => "Trykker",
                                                            "Tulpen One" => "Tulpen One",
                                                            "Ubuntu" => "Ubuntu",
                                                            "Ubuntu Condensed" => "Ubuntu Condensed",
                                                            "Ubuntu Mono" => "Ubuntu Mono",
                                                            "Ultra" => "Ultra",
                                                            "Uncial Antiqua" => "Uncial Antiqua",
                                                            "UnifrakturCook" => "UnifrakturCook",
                                                            "UnifrakturMaguntia" => "UnifrakturMaguntia",
                                                            "Unkempt" => "Unkempt",
                                                            "Unlock" => "Unlock",
                                                            "Unna" => "Unna",
                                                            "VT323" => "VT323",
                                                            "Varela" => "Varela",
                                                            "Varela Round" => "Varela Round",
                                                            "Vast Shadow" => "Vast Shadow",
                                                            "Vibur" => "Vibur",
                                                            "Vidaloka" => "Vidaloka",
                                                            "Viga" => "Viga",
                                                            "Voces" => "Voces",
                                                            "Volkhov" => "Volkhov",
                                                            "Vollkorn" => "Vollkorn",
                                                            "Voltaire" => "Voltaire",
                                                            "Waiting for the Sunrise" => "Waiting for the Sunrise",
                                                            "Wallpoet" => "Wallpoet",
                                                            "Walter Turncoat" => "Walter Turncoat",
                                                            "Wellfleet" => "Wellfleet",
                                                            "Wire One" => "Wire One",
                                                            "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
                                                            "Yellowtail" => "Yellowtail",
                                                            "Yeseva One" => "Yeseva One",
                                                            "Yesteryear" => "Yesteryear",
                                                            "Zeyada" => "Zeyada",
                                                    );

    $font_select='';
    foreach($google_fonts_array as $key=>$value){
        $font_select.='<option value="'.$key.'">'.$value.'</option>';
    }

    $headings_font_subset   =   esc_html ( get_option('wp_estate_headings_font_subset','') );
    $header_array   =   array(
                            'none',
                            'image',
                            'theme slider',
                            'revolution slider',
                            'google map'
                            );
    
    $header_type    =   get_option('wp_estate_header_type','');
    $header_select  =   '';
    
    foreach($header_array as $key=>$value){
       $header_select.='<option value="'.$key.'" ';
       if($key==$header_type){
           $header_select.=' selected="selected" ';
       }
       $header_select.='>'.$value.'</option>'; 
    }
    
    
    $user_header_type    =   get_option('wp_estate_user_header_type','');
    $user_header_select  =   '';
    
    foreach($header_array as $key=>$value){
        $user_header_select.='<option value="'.$key.'" ';
        if($key==$user_header_type){
            $user_header_select.=' selected="selected" ';
        }
        $user_header_select.='>'.$value.'</option>'; 
    }
    

    ///
    $listing_array   =   array( 
                            "1" => esc_html__( 'Type 1','wpestate'),
                            "2" => esc_html__( 'Type 2','wpestate')
                            );
    
    $listing_type    =   get_option('wp_estate_listing_unit_type','');
    $listing_select  =   '';
    
    foreach( $listing_array as $key=>$value){
       $listing_select.='<option value="'.$key.'" ';
       if($key==$listing_type){
           $listing_select.=' selected="selected" ';
       }
       $listing_select.='>'.$value.'</option>'; 
    }
    
   
    
    //
    
    $listing_array   =   array( 
                            "1" => esc_html__( 'Type 1','wpestate'),
                            "2" => esc_html__( 'Type 2','wpestate')
                            );
    
    $listing_page_type    =   get_option('wp_estate_listing_page_type','');
    $listing_page_select  =   '';
    
    foreach( $listing_array as $key=>$value){
        $listing_page_select.='<option value="'.$key.'" ';
        if($key==$listing_page_type){
            $listing_page_select.=' selected="selected" ';
        }
        $listing_page_select.='>'.$value.'</option>'; 
    }
    
    //
    
    $listing_array   =   array( 
                            "1" => esc_html__( 'List','wpestate'),
                            "2" => esc_html__( 'Grid','wpestate')
                            );
    
    $listing_unit_style_half    =   get_option('wp_estate_listing_unit_style_half','');
    $listing_select_half        =   '';
    
    foreach( $listing_array as $key=>$value){
        $listing_select_half.='<option value="'.$key.'" ';
        if($key==$listing_unit_style_half){
            $listing_select_half.=' selected="selected" ';
        }
        $listing_select_half.='>'.$value.'</option>'; 
    }
    
    
    
    $transparent_menu = get_option('wp_estate_transparent_menu','');
    $transparent_menu_select='';
    
     foreach($cache_array as $value){
            $transparent_menu_select.='<option value="'.$value.'"';
            if ($transparent_menu==$value){
                    $transparent_menu_select.=' selected="selected" ';
            }
            $transparent_menu_select.='>'.$value.'</option>';
    }
    
    
    $transparent_menu = get_option('wp_estate_transparent_menu_listing','');
    $transparent_menu_select_listing='';
    
     foreach($cache_array as $value){
            $transparent_menu_select_listing.='<option value="'.$value.'"';
            if ($transparent_menu==$value){
                    $transparent_menu_select_listing.=' selected="selected" ';
            }
            $transparent_menu_select_listing.='>'.$value.'</option>';
    }
    
    
    

    $global_revolution_slider   =  get_option('wp_estate_global_revolution_slider','');
    $global_header  =   get_option('wp_estate_global_header','');

    $footer_background    =   get_option('wp_estate_footer_background','');
    
    $repeat_array=array('repeat','repeat x','repeat y','no repeat');
    $repeat_footer_back_status  =   get_option('wp_estate_repeat_footer_back','');
    $repeat_footer_back_symbol  =   '';
    foreach($repeat_array as $value){
            $repeat_footer_back_symbol.='<option value="'.$value.'"';
            if ($repeat_footer_back_status==$value){
                    $repeat_footer_back_symbol.=' selected="selected" ';
            }
            $repeat_footer_back_symbol.='>'.$value.'</option>';
    }
    
    
    
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'Appearance','wpestate').'</h1>';
    print '<table class="form-table">     
         
        <tr valign="top">
            <th scope="row"><label for="wide_status">'.esc_html__( 'Wide or Boxed?','wpestate').' </label></th>
               <td> <select id="wide_status" name="wide_status">
                    '.$wide_status_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="show_top_bar_user_menu">'.esc_html__( 'Show top bar widget menu ?','wpestate').' </label></th>
               <td> <select id="show_top_bar_user_menu" name="show_top_bar_user_menu">
                    '.$show_top_bar_user_menu_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="show_top_bar_user_login">'.esc_html__( 'Show user login menu in header ?','wpestate').' </label></th>
               <td> <select id="show_top_bar_user_login" name="show_top_bar_user_login">
                    '.$show_top_bar_user_login_symbol.'
		 </select>
            </td>
        </tr>


        <tr valign="top">
            <th scope="row"><label for="header_type">'.esc_html__( 'Header Type?','wpestate').' </label></th>
               <td> <select id="header_type" name="header_type">
                    '.$header_select.'
		 </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="header_type">'.esc_html__( 'Header Type for Owners page?(overwrite the header type option)','wpestate').' </label></th>
               <td> <select id="user_header_type" name="user_header_type">
                    '.$user_header_select.'
		 </select>
            </td>
        </tr>
        
        
        <tr valign="top">
            <th scope="row"><label for="transparent_menu">'.esc_html__( 'Transparent Menu over Header ?','wpestate').' </label></th>
               <td> <select id="transparent_menu" name="transparent_menu">
                    '.$transparent_menu_select.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="transparent_menu_listing">'.esc_html__( 'For Properties page: Transparent Menu over Header ? (*overwrite the above option)','wpestate').' </label></th>
               <td> <select id="transparent_menu_listing" name="transparent_menu_listing">
                    '.$transparent_menu_select_listing.'
		 </select>
            </td>
        </tr>


        
        <tr valign="top">
            <th scope="row"><label for="prop_list_slider">'.esc_html__( 'Use Slider in Property Unit?(*not working for featured property unit and full row property unit)','wpestate').' </label></th>
               <td> <select id="prop_list_slider" name="prop_list_slider">
                    '.$prop_list_slider_symbol.'
		 </select>
            </td>
        </tr>

        
        <tr valign="top">
            <th scope="row"><label for="logo_margin">'.esc_html__( 'Margin Top for logo','wpestate').' </label></th>
            <td> 
                <input type="text" id="logo_margin" name="logo_margin" value="'.$logo_margin.'">   
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="global_revolution_slider">'.esc_html__( 'Global Revolution Slider','wpestate').' </label></th>
             <td> 	
               <input type="text" id="global_revolution_slider" name="global_revolution_slider" value="'.$global_revolution_slider.'">   
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="global_header">'.esc_html__( 'Global Header Static Image','wpestate').' </label></th>
             <td> 	
                <input id="global_header" type="text" size="36" name="global_header" value="'.$global_header.'" />
		<input id="global_header_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Header Image','wpestate').'" />
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="footer_background">'.esc_html__( 'Background for Footer','wpestate').' </label></th>
             <td> 	
                <input id="footer_background" type="text" size="36" name="footer_background" value="'.$footer_background.'" />
		<input id="footer_background_button" type="button"  class="upload_button button" value="'.esc_html__( 'Upload Background Image for Footer','wpestate').'" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="repeat_footer_back">'.esc_html__( 'Repeat Footer background ?','wpestate').' </label></th>
               <td> <select id="repeat_footer_back" name="repeat_footer_back">
                    '.$repeat_footer_back_symbol.'
		 </select>
            </td>
        </tr>
     


        <tr valign="top">
            <th scope="row"><label for="prop_no">'.esc_html__( 'Number of posts per page in properties list, properties list half','wpestate').'</label></th>
            <td>
                <input type="text" id="prop_no" name="prop_no" value="'.$prop_no.'">   
            </td>
        </tr> 
      
      
   
        <tr valign="top">
            <th scope="row"><label for="show_empty_city">'.esc_html__( 'Show Cities and Areas with 0 properties in advanced search?','wpestate').' </label></th>
               <td> <select id="show_empty_city" name="show_empty_city">
                    '.$show_empty_city_status_symbol.'
		 </select>
            </td>
        </tr>
      
        
        <tr valign="top">
            <th scope="row"><label for="blog_sidebar">'.esc_html__( 'Property Page/Blog Category/Archive Sidebar Position','wpestate').'</label></th>
            <td><select id="blog_sidebar" name="blog_sidebar">
                    '.$blog_sidebar_select.'
                </select>
            </td>
        </tr> 
              
        <tr valign="top">
            <th scope="row"><label for="blog_sidebar_name">'.esc_html__( 'Blog Category/Archive Sidebar','wpestate').'</label></th>
            <td><select id="blog_sidebar_name" name="blog_sidebar_name">
                    '.$blog_sidebar_name_select.'
                 </select></td>
         </tr>
        
         <tr valign="top">
            <th scope="row"><label for="property_list_type">'.esc_html__( 'Property List Type for Taxonomy pages','wpestate').'</label></th>
            <td><select id="property_list_type" name="property_list_type">
                    '.$property_list_type_symbol.'
                 </select></td>
         </tr>
         
           <!--  -->
         <tr valign="top">
            <th scope="row"><label for="listing_unit_type">'.esc_html__( 'Property Unit Type','wpestate').'</label></th>
            <td><select id="listing_unit_type" name="listing_unit_type">
                    '.$listing_select.'
                 </select></td>
         </tr>
         
        <tr valign="top">
            <th scope="row"><label for="listing_unit_style_half">'.esc_html__( 'Property Unit Style for Half Map','wpestate').'</label></th>
            <td><select id="listing_unit_style_half" name="listing_unit_style_half">
                    '.$listing_select_half.'
                 </select></td>
         </tr>
         
        
        <tr valign="top">
            <th scope="row"><label for="listing_page_type">'.esc_html__( 'Property Page Design Type','wpestate').'</label></th>
            <td><select id="listing_page_type" name="listing_page_type">
                    '.$listing_page_select.'
                 </select></td>
         </tr>
   

        <tr valign="top">
            <th scope="row"><label for="property_list_type_adv">'.esc_html__( 'Property List Type for Advanced Search','wpestate').'</label></th>
            <td><select id="property_list_type_adv" name="property_list_type_adv">
                    '.$property_list_type_symbol_adv.'
                 </select></td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="use_upload_tax_page">'.esc_html__( 'Use uploaded Image for City and Area taxonomy page Header?','wpestate').'</label></th>
            <td><select id="use_upload_tax_page" name="use_upload_tax_page">
                    '.$use_upload_tax_page_symbol.'
                 </select></td>
         </tr>
        
        <tr valign="top">
            <th scope="row"><label for="general_font">'.esc_html__( 'Main Font','wpestate').'</label></th>
            <td><select id="general_font" name="general_font">
                    '.$general_font_select.'
                    <option value="">- original font -</option>
                    '.$font_select.'                   
		</select>   </td>
         </tr> 
    
        <tr valign="top">
            <th scope="row"><label for="headings_font_subset">'.esc_html__( 'Main Font subset( like greek,cyrillic, etc..)','wpestate').'</label></th>
            <td>
                <input type="text" id="headings_font_subset" name="headings_font_subset" value="'.$headings_font_subset.'">    
            </td>
         </tr>
       
         
         
         <tr valign="top">
            <th scope="row"><label for="copyright_message">'.esc_html__( 'Copyright Message','wpestate').'</label></th>
            <td><textarea cols="57" rows="2" id="copyright_message" name="copyright_message">'.$copyright_message.'</textarea></td>
        </tr>
        
         
        
      
        
        
        
        
        
       
        
      
        
    </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary"  value="'.esc_html__( 'Save Changes','wpestate').'" />
    </p>';
    print '</div>';
}
endif; // end   wpestate_theme_admin_apperance  




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Design
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_theme_admin_design') ):

function wpestate_theme_admin_design(){ 
    $main_color                     =  esc_html ( get_option('wp_estate_main_color','') );
    $background_color               =  esc_html ( get_option('wp_estate_background_color','') );
    $content_back_color             =  esc_html ( get_option('wp_estate_content_back_color','') );
    $header_color                   =  esc_html ( get_option('wp_estate_header_color','') );
  
    $breadcrumbs_font_color         =  esc_html ( get_option('wp_estate_breadcrumbs_font_color','') );
    $font_color                     =  esc_html ( get_option('wp_estate_font_color','') );
    $link_color                     =  esc_html ( get_option('wp_estate_link_color','') );
    $headings_color                 =  esc_html ( get_option('wp_estate_headings_color','') );
  
    $footer_back_color              =  esc_html ( get_option('wp_estate_footer_back_color','') );
    $footer_font_color              =  esc_html ( get_option('wp_estate_footer_font_color','') );
    $footer_copy_color              =  esc_html ( get_option('wp_estate_footer_copy_color','') );
    $sidebar_widget_color           =  esc_html ( get_option('wp_estate_sidebar_widget_color','') );
    $sidebar_heading_color          =  esc_html ( get_option('wp_estate_sidebar_heading_color','') );
    $sidebar_heading_boxed_color    =  esc_html ( get_option('wp_estate_sidebar_heading_boxed_color','') );
    $menu_font_color                =  esc_html ( get_option('wp_estate_menu_font_color','') );
    $menu_hover_back_color          =  esc_html ( get_option('wp_estate_menu_hover_back_color','') );
    $menu_hover_font_color          =  esc_html ( get_option('wp_estate_menu_hover_font_color','') );
    $agent_color                    =  esc_html ( get_option('wp_estate_agent_color','') );
    $sidebar2_font_color            =  esc_html ( get_option('wp_estate_sidebar2_font_color','') );
    $top_bar_back                   =  esc_html ( get_option('wp_estate_top_bar_back','') );
    $top_bar_font                   =  esc_html ( get_option('wp_estate_top_bar_font','') );
    $adv_search_back_color          =  esc_html ( get_option('wp_estate_adv_search_back_color ','') );
    $adv_search_font_color          =  esc_html ( get_option('wp_estate_adv_search_font_color','') );  
    $box_content_back_color         =  esc_html ( get_option('wp_estate_box_content_back_color','') );
    $box_content_border_color       =  esc_html ( get_option('wp_estate_box_content_border_color','') );
    
    $hover_button_color       =  esc_html ( get_option('wp_estate_hover_button_color ','') );
    
    $custom_css                     =  esc_html ( stripslashes( get_option('wp_estate_custom_css','') ) );
    
    $color_scheme_select ='';
    $color_scheme= esc_html ( get_option('wp_estate_color_scheme','') );
    $color_scheme_array=array('no','yes');

    foreach($color_scheme_array as $value){
            $color_scheme_select.='<option value="'.$value.'"';
            if ($color_scheme==$value){
                $color_scheme_select.='selected="selected"';
            }
            $color_scheme_select.='>'.$value.'</option>';
    }

    
    $on_child_theme= esc_html ( get_option('wp_estate_on_child_theme','') );
    
    $on_child_theme_symbol='';
    if($on_child_theme==1){
        $on_child_theme_symbol = " checked ";
    }
    
     
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'Design','wpestate').'</h1>';
    print '<table class="form-table desgintable">     
         <tr valign="top">
            <th scope="row"><label for="color_scheme">'.esc_html__( 'Use Custom Colors ?','wpestate').'</label></th>
            <td><select id="color_scheme" name="color_scheme">
                   '.$color_scheme_select.'
                </select>   
            </td>
         </tr> 
         
        <tr valign="top">
            <th scope="row"><label for="color_scheme">'.esc_html__( 'On save give me the css code to save in child theme style.css - Recomended option?','wpestate').'</label></th>
            <td>
                <input type="hidden"  name="on_child_theme" value="0" id="on_child_theme">
                <input type="checkbox" '.$on_child_theme_symbol.' name="on_child_theme" value="1" id="on_child_theme"></br>
                '.esc_html__('If you use this option you will need to copy paste code that will apear when you click save and use the code in child theme style.css - The colors will NOT change otherwise!','wpestate').'
           
            </td>
            
         
             
        </tr> 
        
      
      
        
        <tr valign="top">
            <th scope="row"><label for="main_color">'.esc_html__( 'Main Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="main_color" maxlength="7" class="inptxt " value="'.$main_color.'"/>
            	<div id="main_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$main_color.';"  ></div></div>
            </td>
        </tr> 

         <tr valign="top">
            <th scope="row"><label for="background_color">'.esc_html__( 'Background Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="background_color" maxlength="7" class="inptxt " value="'.$background_color.'"/>
            	<div id="background_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$background_color.';"  ></div></div>
            </td>
        </tr> 
   
         <!--
        <tr valign="top">
            <th scope="row"><label for="content_back_color">'.esc_html__( 'Content Background Color','wpestate').'</label></th>
            <td>
                <input type="text" name="content_back_color" value="'.$content_back_color.'" maxlength="7" class="inptxt" />
            	<div id="content_back_color" class="colorpickerHolder" ><div class="sqcolor"  style="background-color:#'.$content_back_color.';" ></div></div>
            </td>
        </tr> -->
        
     
        <tr valign="top">
            <th scope="row"><label for="breadcrumbs_font_color">'.esc_html__( 'Breadcrumbs, Meta and Property Info Font Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="breadcrumbs_font_color" value="'.$breadcrumbs_font_color.'" maxlength="7" class="inptxt" />
            	<div id="breadcrumbs_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$breadcrumbs_font_color.';" ></div></div>
            </td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="font_color">'.esc_html__( 'Font Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="font_color" value="'.$font_color.'" maxlength="7" class="inptxt" />
            	<div id="font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$font_color.';" ></div></div>
            </td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="link_color">'.esc_html__( 'Link Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="link_color" value="'.$link_color.'" maxlength="7" class="inptxt" />
            	<div id="link_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$link_color.';" ></div></div>
            </td>
        </tr> 
        
        
        <tr valign="top">
            <th scope="row"><label for="headings_color">'.esc_html__( 'Headings Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="headings_color" value="'.$headings_color.'" maxlength="7" class="inptxt" />
            	<div id="headings_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$headings_color.';" ></div></div>
            </td>
        </tr>
        
     
        <tr valign="top">
            <th scope="row"><label for="footer_back_color">'.esc_html__( 'Footer Background Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="footer_back_color" value="'.$footer_back_color.'" maxlength="7" class="inptxt" />
            	<div id="footer_back_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$footer_back_color.';" ></div></div>
            </td>
        </tr> 
          
        <tr valign="top">
            <th scope="row"><label for="footer_font_color">'.esc_html__( 'Footer Font Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="footer_font_color" value="'.$footer_font_color.'" maxlength="7" class="inptxt" />
            	<div id="footer_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$footer_font_color.';" ></div></div>
            </td>
        </tr> 
          
        <tr valign="top">
            <th scope="row"><label for="footer_copy_color">'.esc_html__( 'Footer Copyright Font Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="footer_copy_color" value="'.$footer_copy_color.'" maxlength="7" class="inptxt" />
            	<div id="footer_copy_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$footer_copy_color.';" ></div></div>
            </td>
        </tr> 
          
          
        <tr valign="top">
            <th scope="row"><label for="sidebar_widget_color">'.esc_html__( 'Sidebar Widget Background Color( for "boxed" widgets)','wpestate').'</label></th>
            <td>
	        <input type="text" name="sidebar_widget_color" value="'.$sidebar_widget_color.'" maxlength="7" class="inptxt" />
            	<div id="sidebar_widget_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$sidebar_widget_color.';" ></div></div>
            </td>
        </tr> 
          
        <tr valign="top">
            <th scope="row"><label for="sidebar_heading_boxed_color">'.esc_html__( 'Sidebar Heading Color (boxed widgets)','wpestate').'</label></th>
            <td>
	        <input type="text" name="sidebar_heading_boxed_color" value="'.$sidebar_heading_boxed_color.'" maxlength="7" class="inptxt" />
            	<div id="sidebar_heading_boxed_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$sidebar_heading_boxed_color.';"></div></div>
            </td>
        </tr>
          
        <tr valign="top">
            <th scope="row"><label for="sidebar_heading_color">'.esc_html__( 'Sidebar Heading Color ','wpestate').'</label></th>
            <td>
	        <input type="text" name="sidebar_heading_color" value="'.$sidebar_heading_color.'" maxlength="7" class="inptxt" />
            	<div id="sidebar_heading_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$sidebar_heading_color.';"></div></div>
            </td>
        </tr>
          
        <tr valign="top">
            <th scope="row"><label for="sidebar2_font_color">'.esc_html__( 'Sidebar Font color','wpestate').'</label></th>
            <td>
	        <input type="text" name="sidebar2_font_color" value="'.$sidebar2_font_color.'" maxlength="7" class="inptxt" />
            	<div id="sidebar2_font_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$sidebar2_font_color.';"></div></div>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="header_color">'.esc_html__( 'Header Background Color','wpestate').'</label></th>
            <td>
	         <input type="text" name="header_color" value="'.$header_color.'" maxlength="7" class="inptxt" />
            	<div id="header_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$header_color.';" ></div></div>
            </td>
        </tr> 
          
        <tr valign="top">
            <th scope="row"><label for="menu_font_color">'.esc_html__( 'Top Menu Font Color','wpestate').'</label></th>
            <td>
	        <input type="text" name="menu_font_color" value="'.$menu_font_color.'"  maxlength="7" class="inptxt" />
            	<div id="menu_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_font_color.';" ></div></div>
            </td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="menu_hover_back_color">'.esc_html__( 'Top Menu - submenu background color','wpestate').'</label></th>
            <td>
	        <input type="text" name="menu_hover_back_color" value="'.$menu_hover_back_color.'"  maxlength="7" class="inptxt" />
           	<div id="menu_hover_back_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_hover_back_color.';"></div></div>
            </td>
        </tr>
          
        <tr valign="top">
            <th scope="row"><label for="menu_hover_font_color">'.esc_html__( 'Top Menu hover font color','wpestate').'</label></th>
            <td>
	        <input type="text" name="menu_hover_font_color" value="'.$menu_hover_font_color.'" maxlength="7" class="inptxt" />
            	<div id="menu_hover_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_hover_font_color.';" ></div></div>
            </td>
        </tr> 
 
        <tr valign="top">
            <th scope="row"><label for="top_bar_back">'.esc_html__( 'Top Bar Background Color (Header Widget Menu)','wpestate').'</label></th>
            <td>
	         <input type="text" name="top_bar_back" value="'.$top_bar_back.'" maxlength="7" class="inptxt" />
            	<div id="top_bar_back" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$top_bar_back.';"></div></div>
            </td>
        </tr> 
          
        <tr valign="top">
            <th scope="row"><label for="top_bar_font">'.esc_html__( 'Top Bar Font Color (Header Widget Menu)','wpestate').'</label></th>
            <td>
	         <input type="text" name="top_bar_font" value="'.$top_bar_font.'" maxlength="7" class="inptxt" />
            	<div id="top_bar_font" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$top_bar_font.';"></div></div>
            </td>
        </tr> 
          
      
        
        <tr valign="top">
            <th scope="row"><label for="box_content_back_color">'.esc_html__( 'Boxed Content Background Color','wpestate').'</label></th>
            <td>
	         <input type="text" name="box_content_back_color" value="'.$box_content_back_color.'" maxlength="7" class="inptxt" />
            	<div id="box_content_back_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$box_content_back_color.';"></div></div>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="box_content_border_color">'.esc_html__( 'Border Color','wpestate').'</label></th>
            <td>
	         <input type="text" name="box_content_border_color" value="'.$box_content_border_color.'" maxlength="7" class="inptxt" />
            	<div id="box_content_border_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$box_content_border_color.';"></div></div>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="hover_button_color">'.esc_html__( 'Hover Button Color','wpestate').'</label></th>
            <td>
                <input type="text" name="hover_button_color" value="'.$hover_button_color.'" maxlength="7" class="inptxt" />
            	<div id="hover_button_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$hover_button_color.';"></div></div>
            </td>
        </tr>
         
        <tr valign="top">
            <th scope="row"><label for="custom_css">'.esc_html__( 'Custom Css','wpestate').'</label></th>
            <td><textarea cols="57" rows="5" name="custom_css" id="custom_css">'.$custom_css.'</textarea></td>
        </tr>
        
 </table>    
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="'.esc_html__( 'Save Changes','wpestate').'" />
    </p>';
    
    $on_child_theme= esc_html ( get_option('wp_estate_on_child_theme','') );
    
    print'<div class="" id="css_modal" tabindex="-1"><div class="css_modal_close">x</div> <textarea onclick="this.focus();this.select()" class="modal-content">';
      
            $general_font   = esc_html(get_option('wp_estate_general_font', ''));
            if ( $general_font != '' && $general_font != 'x'){
                require_once get_template_directory().'/libs/custom_general_font.php';
            }
            require_once get_template_directory().'/libs/customcss.php';
      
    print '</textarea><span style="margin-left:30px;">'.esc_html__('Copy the above code and add it into your child theme style.css','wpestate').'</span></div>'; 
    
    
    print '</div>';
}
endif; // end   wpestate_theme_admin_design  



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  help and custom
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_theme_admin_help') ):
function wpestate_theme_admin_help(){
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'Help','wpestate').'</h1>';
    $support_link='http://support.wpestate.org/';
    print '<table class="form-table">  
 
        <tr valign="top">
            <td> '.esc_html__( 'For support please go to ','wpestate').'<a href="'.$support_link.'" target="_blank">'.$support_link.'</a>,'.esc_html__('create an account and post a ticket. The registration is simple and as soon as you post we are notified. We usually answer in the next 24h (except weekends). Please use this system and not the email. It will help us answer much faster. Thank you!','wpestate').'
            </td>             
        </tr>
        
        <tr valign="top">
            <td> '.esc_html__( 'For custom work on this theme please go to','wpestate').' <a href="'.$support_link.'" target="_blank">'.$support_link.'</a>,'.esc_html__(' create a ticket with your request and we will offer a free quote.','wpestate').'
            </td>             
        </tr>
        
        <tr valign="top">
            <td> '.esc_html__( 'For help files please go to ','wpestate').'<a href="http://help.wprentals.org/">http://help.wprentals.org</a>
            </td>             
        </tr>
        
         
        <tr valign="top">
            <td>  '.esc_html__( 'Subscribe to our mailing list in order to receive news about new features and theme upgrades.','wpestate').' <a href="http://eepurl.com/CP5U5">Subscribe Here!</a>
            </td>             
        </tr>
        </table>
        
      ';
    print '</div>';
}
endif; // end   wpestate_theme_admin_help  



if( !function_exists('wpestate_general_country_list') ):
    function wpestate_general_country_list($selected){
        $countries = array(esc_html__('Afghanistan','wpestate'),esc_html__('Albania','wpestate'),esc_html__('Algeria','wpestate'),esc_html__('American Samoa','wpestate'),esc_html__('Andorra','wpestate'),esc_html__('Angola','wpestate'),esc_html__('Anguilla','wpestate'),esc_html__('Antarctica','wpestate'),esc_html__('Antigua and Barbuda','wpestate'),esc_html__('Argentina','wpestate'),esc_html__('Armenia','wpestate'),esc_html__('Aruba','wpestate'),esc_html__('Australia','wpestate'),esc_html__('Austria','wpestate'),esc_html__('Azerbaijan','wpestate'),esc_html__('Bahamas','wpestate'),esc_html__('Bahrain','wpestate'),esc_html__('Bangladesh','wpestate'),esc_html__('Barbados','wpestate'),esc_html__('Belarus','wpestate'),esc_html__('Belgium','wpestate'),esc_html__('Belize','wpestate'),esc_html__('Benin','wpestate'),esc_html__('Bermuda','wpestate'),esc_html__('Bhutan','wpestate'),esc_html__('Bolivia','wpestate'),esc_html__('Bosnia and Herzegowina','wpestate'),esc_html__('Botswana','wpestate'),esc_html__('Bouvet Island','wpestate'),esc_html__('Brazil','wpestate'),esc_html__('British Indian Ocean Territory','wpestate'),esc_html__('Brunei Darussalam','wpestate'),esc_html__('Bulgaria','wpestate'),esc_html__('Burkina Faso','wpestate'),esc_html__('Burundi','wpestate'),esc_html__('Cambodia','wpestate'),esc_html__('Cameroon','wpestate'),esc_html__('Canada','wpestate'),esc_html__('Cape Verde','wpestate'),esc_html__('Cayman Islands','wpestate'),esc_html__('Central African Republic','wpestate'),esc_html__('Chad','wpestate'),esc_html__('Chile','wpestate'),esc_html__('China','wpestate'),esc_html__('Christmas Island','wpestate'),esc_html__('Cocos (Keeling) Islands','wpestate'),esc_html__('Colombia','wpestate'),esc_html__('Comoros','wpestate'),esc_html__('Congo','wpestate'),esc_html__('Congo, the Democratic Republic of the','wpestate'),esc_html__('Cook Islands','wpestate'),esc_html__('Costa Rica','wpestate'),esc_html__('Cote dIvoire','wpestate'),esc_html__('Croatia (Hrvatska)','wpestate'),esc_html__('Cuba','wpestate'),esc_html__('Curacao','wpestate'),esc_html__('Cyprus','wpestate'),esc_html__('Czech Republic','wpestate'),esc_html__('Denmark','wpestate'),esc_html__('Djibouti','wpestate'),esc_html__('Dominica','wpestate'),esc_html__('Dominican Republic','wpestate'),esc_html__('East Timor','wpestate'),esc_html__('Ecuador','wpestate'),esc_html__('Egypt','wpestate'),esc_html__('El Salvador','wpestate'),esc_html__('Equatorial Guinea','wpestate'),esc_html__('Eritrea','wpestate'),esc_html__('Estonia','wpestate'),esc_html__('Ethiopia','wpestate'),esc_html__('Falkland Islands (Malvinas)','wpestate'),esc_html__('Faroe Islands','wpestate'),esc_html__('Fiji','wpestate'),esc_html__('Finland','wpestate'),esc_html__('France','wpestate'),esc_html__('France Metropolitan','wpestate'),esc_html__('French Guiana','wpestate'),esc_html__('French Polynesia','wpestate'),esc_html__('French Southern Territories','wpestate'),esc_html__('Gabon','wpestate'),esc_html__('Gambia','wpestate'),esc_html__('Georgia','wpestate'),esc_html__('Germany','wpestate'),esc_html__('Ghana','wpestate'),esc_html__('Gibraltar','wpestate'),esc_html__('Greece','wpestate'),esc_html__('Greenland','wpestate'),esc_html__('Grenada','wpestate'),esc_html__('Guadeloupe','wpestate'),esc_html__('Guam','wpestate'),esc_html__('Guatemala','wpestate'),esc_html__('Guinea','wpestate'),esc_html__('Guinea-Bissau','wpestate'),esc_html__('Guyana','wpestate'),esc_html__('Haiti','wpestate'),esc_html__('Heard and Mc Donald Islands','wpestate'),esc_html__('Holy See (Vatican City State)','wpestate'),esc_html__('Honduras','wpestate'),esc_html__('Hong Kong','wpestate'),esc_html__('Hungary','wpestate'),esc_html__('Iceland','wpestate'),esc_html__('India','wpestate'),esc_html__('Indonesia','wpestate'),esc_html__('Iran (Islamic Republic of)','wpestate'),esc_html__('Iraq','wpestate'),esc_html__('Ireland','wpestate'),esc_html__('Israel','wpestate'),esc_html__('Italy','wpestate'),esc_html__('Jamaica','wpestate'),esc_html__('Japan','wpestate'),esc_html__('Jordan','wpestate'),esc_html__('Kazakhstan','wpestate'),esc_html__('Kenya','wpestate'),esc_html__('Kiribati','wpestate'),esc_html__('Korea, Democratic People Republic of','wpestate'),esc_html__('Korea, Republic of','wpestate'),esc_html__('Kuwait','wpestate'),esc_html__('Kyrgyzstan','wpestate'),esc_html__('Lao, People Democratic Republic','wpestate'),esc_html__('Latvia','wpestate'),esc_html__('Lebanon','wpestate'),esc_html__('Lesotho','wpestate'),esc_html__('Liberia','wpestate'),esc_html__('Libyan Arab Jamahiriya','wpestate'),esc_html__('Liechtenstein','wpestate'),esc_html__('Lithuania','wpestate'),esc_html__('Luxembourg','wpestate'),esc_html__('Macau','wpestate'),esc_html__('Macedonia, The Former Yugoslav Republic of','wpestate'),esc_html__('Madagascar','wpestate'),esc_html__('Malawi','wpestate'),esc_html__('Malaysia','wpestate'),esc_html__('Maldives','wpestate'),esc_html__('Mali','wpestate'),esc_html__('Malta','wpestate'),esc_html__('Marshall Islands','wpestate'),esc_html__('Martinique','wpestate'),esc_html__('Mauritania','wpestate'),esc_html__('Mauritius','wpestate'),esc_html__('Mayotte','wpestate'),esc_html__('Mexico','wpestate'),esc_html__('Micronesia, Federated States of','wpestate'),esc_html__('Moldova, Republic of','wpestate'),esc_html__('Monaco','wpestate'),esc_html__('Mongolia','wpestate'),esc_html__('Montserrat','wpestate'),esc_html__('Morocco','wpestate'),esc_html__('Mozambique','wpestate'),esc_html__('Montenegro','wpestate'),esc_html__('Myanmar','wpestate'),esc_html__('Namibia','wpestate'),esc_html__('Nauru','wpestate'),esc_html__('Nepal','wpestate'),esc_html__('Netherlands','wpestate'),esc_html__('Netherlands Antilles','wpestate'),esc_html__('New Caledonia','wpestate'),esc_html__('New Zealand','wpestate'),esc_html__('Nicaragua','wpestate'),esc_html__('Niger','wpestate'),esc_html__('Nigeria','wpestate'),esc_html__('Niue','wpestate'),esc_html__('Norfolk Island','wpestate'),esc_html__('Northern Mariana Islands','wpestate'),esc_html__('Norway','wpestate'),esc_html__('Oman','wpestate'),esc_html__('Pakistan','wpestate'),esc_html__('Palau','wpestate'),esc_html__('Panama','wpestate'),esc_html__('Papua New Guinea','wpestate'),esc_html__('Paraguay','wpestate'),esc_html__('Peru','wpestate'),esc_html__('Philippines','wpestate'),esc_html__('Pitcairn','wpestate'),esc_html__('Poland','wpestate'),esc_html__('Portugal','wpestate'),esc_html__('Puerto Rico','wpestate'),esc_html__('Qatar','wpestate'),esc_html__('Reunion','wpestate'),esc_html__('Romania','wpestate'),esc_html__('Russian Federation','wpestate'),esc_html__('Rwanda','wpestate'),esc_html__('Saint Kitts and Nevis','wpestate'),esc_html__('Saint Lucia','wpestate'),esc_html__('Saint Vincent and the Grenadines','wpestate'),esc_html__('Samoa','wpestate'),esc_html__('San Marino','wpestate'),esc_html__('Sao Tome and Principe','wpestate'),esc_html__('Saudi Arabia','wpestate'),esc_html__('Serbia','wpestate'),esc_html__('Senegal','wpestate'),esc_html__('Seychelles','wpestate'),esc_html__('Sierra Leone','wpestate'),esc_html__('Singapore','wpestate'),esc_html__('Slovakia (Slovak Republic)','wpestate'),esc_html__('Slovenia','wpestate'),esc_html__('Solomon Islands','wpestate'),esc_html__('Somalia','wpestate'),esc_html__('South Africa','wpestate'),esc_html__('South Georgia and the South Sandwich Islands','wpestate'),esc_html__('Spain','wpestate'),esc_html__('Sri Lanka','wpestate'),esc_html__('St. Helena','wpestate'),esc_html__('St. Pierre and Miquelon','wpestate'),esc_html__('Sudan','wpestate'),esc_html__('Suriname','wpestate'),esc_html__('Svalbard and Jan Mayen Islands','wpestate'),esc_html__('Swaziland','wpestate'),esc_html__('Sweden','wpestate'),esc_html__('Switzerland','wpestate'),esc_html__('Syrian Arab Republic','wpestate'),esc_html__('Taiwan, Province of China','wpestate'),esc_html__('Tajikistan','wpestate'),esc_html__('Tanzania, United Republic of','wpestate'),esc_html__('Thailand','wpestate'),esc_html__('Togo','wpestate'),esc_html__('Tokelau','wpestate'),esc_html__('Tonga','wpestate'),esc_html__('Trinidad and Tobago','wpestate'),esc_html__('Tunisia','wpestate'),esc_html__('Turkey','wpestate'),esc_html__('Turkmenistan','wpestate'),esc_html__('Turks and Caicos Islands','wpestate'),esc_html__('Tuvalu','wpestate'),esc_html__('Uganda','wpestate'),esc_html__('Ukraine','wpestate'),esc_html__('United Arab Emirates','wpestate'),esc_html__('United Kingdom','wpestate'),esc_html__('United States','wpestate'),esc_html__('United States Minor Outlying Islands','wpestate'),esc_html__('Uruguay','wpestate'),esc_html__('Uzbekistan','wpestate'),esc_html__('Vanuatu','wpestate'),esc_html__('Venezuela','wpestate'),esc_html__('Vietnam','wpestate'),esc_html__('Virgin Islands (British)','wpestate'),esc_html__('Virgin Islands (U.S.)','wpestate'),esc_html__('Wallis and Futuna Islands','wpestate'),esc_html__('Western Sahara','wpestate'),esc_html__('Yemen','wpestate'),esc_html__('Yugoslavia','wpestate'),esc_html__('Zambia','wpestate'),esc_html__('Zimbabwe','wpestate'));
        $country_select='<select id="general_country" style="width: 200px;" name="general_country">';

        foreach($countries as $country){
            $country_select.='<option value="'.$country.'"';  
            if($selected==$country){
                $country_select.='selected="selected"';
            }
            $country_select.='>'.$country.'</option>';
        }

        $country_select.='</select>';
        return $country_select;
    }
endif; // end   wpestate_general_country_list  


function wpestate_sorting_function($a, $b) {
    return $a[3] - $b[3];
};



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Wpestate Price settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_price_set') ):
function wpestate_price_set(){
    $custom_fields = get_option( 'wp_estate_multi_curr', true);     
    $current_fields='';
    
    $currency_symbol                =   esc_html( get_option('wp_estate_currency_symbol') );
    $where_currency_symbol          =   '';
    $where_currency_symbol_array    =   array('before','after');
    $where_currency_symbol_status   =   esc_html( get_option('wp_estate_where_currency_symbol') );
    foreach($where_currency_symbol_array as $value){
            $where_currency_symbol.='<option value="'.$value.'"';
            if ($where_currency_symbol_status==$value){
                $where_currency_symbol.=' selected="selected" ';
            }
            $where_currency_symbol.='>'.$value.'</option>';
    }
    $enable_auto_symbol             =   '';
    $enable_auto_symbol_array       =   array('yes','no');
    $where_enable_auto_status       =    esc_html( get_option('wp_estate_auto_curency') );
     foreach($enable_auto_symbol_array as $value){
            $enable_auto_symbol.='<option value="'.$value.'"';
            if ($where_enable_auto_status==$value){
                $enable_auto_symbol.=' selected="selected" ';
            }
            $enable_auto_symbol.='>'.$value.'</option>';
    }
    
    $i=0;
    if( !empty($custom_fields)){    
        while($i< count($custom_fields) ){
            $current_fields.='
                <div class=field_row>
                <div    class="field_item"><strong>'.esc_html__( 'Currency Symbol','wpestate').'</strong></br><input   type="text" name="add_curr_name[]"   value="'.$custom_fields[$i][0].'"  ></div>
                <div    class="field_item"><strong>'.esc_html__( 'Currency Label','wpestate').'</strong></br><input  type="text" name="add_curr_label[]"   value="'.$custom_fields[$i][1].'"  ></div>
                <div    class="field_item"><strong>'.esc_html__( 'Currency Value','wpestate').'</strong></br><input  type="text" name="add_curr_value[]"   value="'.$custom_fields[$i][2].'"  ></div>
                <div    class="field_item"><strong>'.esc_html__( 'Currency Position','wpestate').'</strong></br><input  type="text" name="add_curr_order[]"   value="'.$custom_fields[$i][3].'"  ></div>
                
                <a class="deletefieldlink" href="#">'.esc_html__( 'delete','wpestate').'</a>
            </div>';    
            $i++;
        }
    }
    
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'Price and Currency','wpestate').'</h1>';

    print '<table class="form-table">';
    
    print '<tr valign="top">
            <th scope="row"><label for="prices_th_separator">'.esc_html__( 'Price - thousands separator','wpestate').'</label></th>
            <td>
                <input type="text" name="prices_th_separator" id="prices_th_separator" value="'.get_option('wp_estate_prices_th_separator','').'"> 
            </td>
        </tr>   
        

        <tr valign="top">
            <th scope="row"><label for="">'.esc_html__( 'Currency symbol','wpestate').'</label></th>
            <td><input  type="text" id="currency_symbol" name="currency_symbol"  value="'.$currency_symbol.'"/> </td>
        </tr>
       
        <tr valign="top">
            <th scope="row"><label for="">'.esc_html__( 'Where to show the currency symbol?','wpestate').'</label></th>
            <td>
                <select id="where_currency_symbol" name="where_currency_symbol">
                    '.$where_currency_symbol.'
                </select> 
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">'.esc_html__( 'Currency label - will appear on front end in multi currency dropdown','wpestate').'</th>
            <td>
            <input  type="text" id="currency_label_main"  name="currency_label_main"   value="'. get_option('wp_estate_currency_label_main','').'" size="40"/>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="">'.__('Enable auto loading of exchange rates from Yahoo(1 time per day)?','wpestate').'</label></th>
            <td>
                <select id="auto_curency" name="auto_curency">
                    '.$enable_auto_symbol.'
                </select> 
            </td>
        </tr>
        
    </table> ';
    
    
    
 
     print' <form method="post" action="">
        <h3 style="margin-left:10px;width:100%;float:left;">'.esc_html__( 'Add Currencies for Multi Currency features','wpestate').'</h3>
     
        <div id="custom_fields">
             '.$current_fields.'
            <input type="hidden" name="is_custom_cur" value="1">   
        </div>

       <table class="form-table">
            <tbody>
                <tr valign="top">
                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Currency','wpestate').'</th>
                        <td>
                            <input  type="text" id="currency_name"  name="currency_name"   value="" size="40"/>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Currency label - will appear on front end in multi currency dropdown','wpestate').'</th>
                        <td>
                            <input  type="text" id="currency_label"  name="currency_label"   value="" size="40"/>
                        </td>
                    </tr>
                    
                    
                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Currency Value compared with the based currency','wpestate').'</th>
                        <td>
                            <input  type="text" id="currency_value"  name="currency_value"   value="" size="40"/>
                        </td>
                    </tr>
                    
                     
                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Show currency before or after price - in front pages','wpestate').'</th>
                        <td>
                             <select id="where_cur" name="where_cur"  style="width:236px;">
                                <option value="before"> before </option>
                                <option value="after">  after </option>
                              
                            </select>
                        </td>
                    </tr>  
                    
                   
            </tbody>
        </table>   
        
       <a href="#" id="add_curency">'.esc_html__( ' click to add currency','wpestate').'</a><br>

      

    </form> ';
    print '
 
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="'.esc_html__( 'Save Changes','wpestate').'" />
    </p>';
}
endif;



if( !function_exists('wpestate_generate_file_pins') ):
function   wpestate_generate_file_pins(){
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__( 'Generate pins','wpestate').'</h1>';
    //print '<a href="http://help.wprentals.org/#!/googlemaps" target="_blank" class="help_link">'.esc_html__( 'help','wpestate').'</a>';
  
    print '<table class="form-table">   <tr valign="top">
           <td>';  
    
    $show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
    if($show_adv_search_general=='no'){
        event_wp_estate_create_auto_function();
        esc_html_e('Autcomplete file was generated','wpestate');
        print '</td></tr><tr valign="top"><td>';
    }
       
    
    if ( get_option('wp_estate_readsys','') =='yes' ){
        
        $path= wpestate_get_pin_file_path_write();
   
        if ( file_exists ($path) && is_writable ($path) ){
            wpestate_listing_pins();
            esc_html_e('File was generated','wpestate');
        }else{
            print ' <div class="notice_file">'.esc_html__( 'the file Google map does NOT exist or is NOT writable','wpestate').'</div>';
        }
   
    }else{
        esc_html_e('Pin Generation works only if the file reading option in Google Map setting is set to yes','wpestate');
    }
    
    print '</td>
           </tr></table>';
    print '</div>';   
}
endif;


?>