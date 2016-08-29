<?php
$adv_submit             =   wpestate_get_adv_search_link();
$guest_list             =   wpestate_get_guest_dropdown();

//  show cities or areas that are empty ?
$args = wpestate_get_select_arguments();
$allowed_html = array();
$allowed_html_list =    array('li' => array(
                                        'data-value'        =>array(),
                                        'role'              => array(),
                                        'data-parentcity'   =>array(),
                                        'data-value2'       =>array()
                        ) );
$action_select_list =   wpestate_get_action_select_list($args);
$categ_select_list  =   wpestate_get_category_select_list($args);
$min_price_slider   =   floatval(get_option('wp_estate_show_slider_min_price',''));
$max_price_slider   =   floatval(get_option('wp_estate_show_slider_max_price',''));
$where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
       

if ($where_currency == 'before') {
    $price_slider_label = $currency . number_format($min_price_slider).' '.esc_html__( 'to','wpestate').' '.$currency . number_format($max_price_slider);
}else {
    $price_slider_label =  number_format($min_price_slider).$currency.' '.esc_html__( 'to','wpestate').' '.number_format($max_price_slider).$currency;
} 


?>

<div id="advanced_search_map_list">
    <div class="advanced_search_map_list_container">
 
  
        <div class="col-md-6 map_icon">
             <?php
            $show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
            $wpestate_internal_search           =   '';
            if($show_adv_search_general=='no'){
                $wpestate_internal_search='_autointernal';
                
                if( isset($_GET['stype']) && $_GET['stype']=='meta'){
                    print '<input type="hidden" class="stype" id="stype" name="stype" value="meta">';
                }else{
                   print '<input type="hidden" class="stype" id="stype" name="stype" value="tax">'; 
                }
                
            }
            ?>
            <input type="text"   id="search_location_filter<?php echo $wpestate_internal_search;?>" class="form-control search_location_city" name="search_location" placeholder="<?php esc_html_e('Where do you want to go ?','wpestate');?>" value="<?php if(isset( $_GET['search_location'] )){echo wp_kses( esc_attr($_GET['search_location']),$allowed_html );}?>" >              
            <input type="hidden" id="search_location_city" value="<?php if(isset( $_GET['advanced_city'] )){echo wp_kses( esc_attr($_GET['advanced_city']),$allowed_html);}?>" >
            <input type="hidden" id="search_location_area" value="<?php if(isset( $_GET['advanced_area'] )){echo wp_kses ( esc_attr($_GET['advanced_area']),$allowed_html);}?>" >
            <input type="hidden" id="search_location_country"    value="<?php if(isset( $_GET['advanced_country'] )){echo wp_kses ( esc_attr($_GET['advanced_country']),$allowed_html);}?>" >              
            <input type="hidden" id="property_admin_area" name="property_admin_area"  value="<?php if(isset( $_GET['property_admin_area'] )){echo wp_kses ( esc_attr($_GET['property_admin_area']),$allowed_html);}?>" >
           
        </div>

        
                        
        <div class="col-md-3 has_calendar calendar_icon ">
            <input type="text" id="check_in_list"        class="form-control" name="check_in"  placeholder="<?php esc_html_e('Check in','wpestate');?>" value="<?php if(isset( $_GET['check_in'] )){echo wp_kses (  esc_attr($_GET['check_in']),$allowed_html);}?>" >       
        </div>


        <div class="col-md-3 has_calendar calendar_icon ">
            <input type="text" id="check_out_list"       class="form-control" name="check_out" placeholder="<?php esc_html_e('Check Out','wpestate');?>" value="<?php if(isset( $_GET['check_out'] )){echo wp_kses( esc_attr($_GET['check_out']),$allowed_html);}?>">
        </div>

        <div class="col-md-3">
            <div class="dropdown form-control guest_form" >
                <div data-toggle="dropdown" id="guest_no" class="filter_menu_trigger" data-value="all">
                <?php 
                if(isset($_GET['guest_no']) && $_GET['guest_no']!=''){
                    echo wp_kses( esc_html($_GET['guest_no']), $allowed_html);
                }else{
                    esc_html_e('Guests','wpestate');
                }
                ?> 
                    
                    
               <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="guest_noz" id="guest_no_input" value="<?php if(isset( $_GET['guest_no'] )){echo wp_kses ( esc_attr($_GET['guest_no']),$allowed_html);}?>">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="guest_no_input">
                    <?php print $guest_list;?>
                </ul>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="dropdown form-control rooms_icon" >
                <div data-toggle="dropdown" id="rooms_no" class="filter_menu_trigger" data-value="all"> <?php esc_html_e('Rooms','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="rooms_no"  id="rooms_no_input" value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="rooms_no">
                    <?php echo wpestate_get_rooms_dropdown();?>
                </ul>
            </div>
        </div>
            
        
        <div class="col-md-3">
            <div class="dropdown form-control types_icon" id="categ_list" >
                <div data-toggle="dropdown" id="adv_categ" class="filter_menu_trigger" data-value="all"> <?php esc_html_e('All Types','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="filter_search_type[]" value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                    <?php  print wp_kses($categ_select_list,$allowed_html_list); ?>
                </ul>        
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="dropdown form-control actions_icon">
                <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger" data-value="all"> <?php esc_html_e('All Sizes','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="filter_search_action[]" value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                    <?php print wp_kses($action_select_list,$allowed_html_list);?>
                </ul>        
            </div>
        </div>
     

       
        
        <div class="col-md-3">
            <div class="dropdown form-control bedrooms_icon" >
                <div data-toggle="dropdown" id="beds_no" class="filter_menu_trigger" data-value="all"><?php echo  esc_html__( 'Bedrooms','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="beds_no" id="beds_no_input" value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="beds_no">
                    <?php echo wpestate_get_bedrooms_dropdown(); ?>
                </ul>
            </div>
        </div>
                 
        <div class="col-md-3">
            <div class="dropdown form-control baths_icon" >
                <div data-toggle="dropdown" id="baths_no" class="filter_menu_trigger" data-value="all"><?php echo esc_html__( 'Baths','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="baths_no" id="baths_no_input"  value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="baths_no">
                    <?php echo wpestate_get_baths_dropdown(); ?>
                </ul>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="adv_search_slider">
                <p>
                    <label><?php esc_html_e('Price range:','wpestate');?></label>
                    <span id="amount"  style="border:0; color:#f6931f; font-weight:bold;"><?php print wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$currency,$where_currency);?></span>
                </p>
                <div id="slider_price"></div>
                <input type="hidden" id="price_low"  name="price_low"  value="<?php echo wpestate_price_default_convert ($min_price_slider);?>" />
                <input type="hidden" id="price_max"  name="price_max"  value="<?php echo wpestate_price_default_convert ($max_price_slider);?>" />
            </div>
        </div>
        
        <?php   wpestate_show_extended_search(''); ?>
        <!--
        <div class="col-md-2">
            <input name="submit" type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="advanced_submit_2" value="<?php esc_html_e('Search','wpestate');?>">
        </div>
         -->
  
        <div class="adv_extended_options_text" id="adv_extended_options_text_adv" data-pageid="<?php echo $post->ID; ?>"><?php esc_html_e('More Options','wpestate');?></div>
      
        <?php get_template_part('libs/internal_autocomplete_wpestate'); ?>
       
       

    </div>
</div>


<div id="advanced_search_map_list_hidden">
    <div class="col-md-2">
        <div class="show_filters" id="adv_extended_options_show_filters"><?php esc_html_e('Search Options','wpestate')?></div>
    </div>  
</div>    

