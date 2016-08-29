<?php
$adv_submit             =   wpestate_get_adv_search_link();

//  show cities or areas that are empty ?
$args = wpestate_get_select_arguments();

$action_select_list =   wpestate_get_action_select_list($args);
$categ_select_list  =   wpestate_get_category_select_list($args);
$select_city_list   =   wpestate_get_city_select_list($args); 
$select_area_list   =   wpestate_get_area_select_list($args);

$home_small_map_status              =   esc_html ( get_option('wp_estate_home_small_map','') );
$show_adv_search_map_close          =   esc_html ( get_option('wp_estate_show_adv_search_map_close','') );
$class                              =   'hidden';
$class_close                        =   '';
$guest_list             =   wpestate_get_guest_dropdown();
?>


<div id="adv-search-header-mobile"> 
    <?php esc_html_e('Advanced Search','wpestate');?> 
</div>   




<div class="adv-search-mobile"  id="adv-search-mobile"> 
   
    <form method="get"  id="form-search-mobile" action="<?php print $adv_submit; ?>" >
        <?php
        if (function_exists('icl_translate') ){
            print do_action( 'wpml_add_language_form_field' );
        }
        ?>
        <div class="col-md-4 map_icon">  
            <?php
            $show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
            $wpestate_internal_search           =   '';
            if($show_adv_search_general=='no'){
                $wpestate_internal_search='_autointernal';
                print '<input type="hidden" class="stype" id="stype" name="stype" value="tax">';
            }
            ?>
            
            <input type="text" id="search_location_mobile<?php echo $wpestate_internal_search;?>"      class="form-control" name="search_location" placeholder="<?php esc_html_e('Where are you going?','wpestate');?>" value="" >              
            <input type="hidden" id="advanced_city_mobile"      class="form-control" name="advanced_city" data-value=""   value="" >              
            <input type="hidden" id="advanced_area_mobile"      class="form-control" name="advanced_area"   data-value="" value="" >              
            <input type="hidden" id="advanced_country_mobile"   class="form-control" name="advanced_country"   data-value="" value="" >              
             <input type="hidden" id="property_admin_area_mobile" name="property_admin_area" value="">
        </div>
        
        <div class="col-md-2 has_calendar calendar_icon">
            <input type="text" id="check_in_mobile"    class="form-control " name="check_in"  placeholder="<?php esc_html_e('Check in','wpestate');?>" value="" >       
        </div>
        
        <div class="col-md-2  has_calendar calendar_icon">
            <input type="text" id="check_out_mobile" disabled  class="form-control" name="check_out" placeholder="<?php esc_html_e('Check Out','wpestate');?>" value="">
        </div>
        
        <div class="col-md-2">
            <div class="dropdown form-control guest_form">
                <div data-toggle="dropdown" id="guest_no_mobile" class="filter_menu_trigger" data-value="all"> <?php esc_html_e('Guests','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="guest_no" id="guest_no_main_mobile" value="">
                <ul  class="dropdown-menu filter_menu"  id="guest_no_main_list_mobile" role="menu" aria-labelledby="guest_no_mobile">
                    <?php print $guest_list;?>
                </ul>        
            </div>
        </div>
        
        <div class="col-md-2">
            <input name="submit" type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="advanced_submit_2_mobile" value="<?php esc_html_e('Search','wpestate');?>">
        </div>
    </form>   
</div>  
<?php get_template_part('libs/internal_autocomplete_wpestate'); ?>