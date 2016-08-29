<?php
class Wpestate_Advanced_Search_widget extends WP_Widget {
	
//	function Wpestate_Advanced_Search_widget(){
        function __construct(){
		$widget_ops = array('classname' => 'advanced_search_sidebar', 'description' => 'Advanced Search Widget');
		$control_ops = array('id_base' => 'wpestate_advanced_search_widget');
		parent::__construct('wpestate_advanced_Search_widget', 'Wp Estate: Advanced Search', $widget_ops, $control_ops);
	}
	
	function form($instance){
		$defaults = array('title' => 'Advanced Search' );
		$instance = wp_parse_args((array) $instance, $defaults);
		$display='
                <p>
                    <label for="'.$this->get_field_id('title').'">Title:</label>
		</p><p>
                    <input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />
		</p>';
		print $display;
	}


	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		
		return $instance;
	}



	function widget($args, $instance){
		extract($args);
                $display='';
                $select_tax_action_terms='';
                $select_tax_category_terms='';
                
		$title = apply_filters('widget_title', $instance['title']);

		print $before_widget;

		if($title) {
                    print $before_title.$title.$after_title;
		}else{
                    print '<div class="widget-title-sidebar_blank"></div>';
                }
                
                $adv_submit=wpestate_get_adv_search_link();
                
                //  show cities or areas that are empty ?
                $args = wpestate_get_select_arguments();
                $action_select_list =   wpestate_get_action_select_list($args);
                $categ_select_list  =   wpestate_get_category_select_list($args);
                $select_city_list   =   wpestate_get_city_select_list($args); 
                $select_area_list   =   wpestate_get_area_select_list($args);


    
                $where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                $currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
                $min_price_slider   = ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                $max_price_slider   = ( floatval(get_option('wp_estate_show_slider_max_price','')) );
                $price_slider_label = wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$currency,$where_currency);
                  
             
                print '<form id="widget_search" method="get"   action="'.$adv_submit.'" >';
                          
            
                if (function_exists('icl_translate') ){
                    print do_action( 'wpml_add_language_form_field' );
                }
      
                print '
                <div class="map_icon">';
                
                $show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
                $wpestate_internal_search           =   '';
                if($show_adv_search_general=='no'){
                    $wpestate_internal_search='_autointernal';
                    print '<input type="hidden" class="stype" id="stype" name="stype" value="tax">';
                }
                
                print'
                    <input type="text" id="search_location_filter_widget'.$wpestate_internal_search.'" class="form-control search_location_city" name="search_location" placeholder="'.esc_html__( 'Where are you going?','wpestate').'" value="" autocomplete="off">
                    <input type="hidden" id="advanced_city_widget"   class="form-control" name="advanced_city" data-value=""   value="" >              
                    <input type="hidden" id="advanced_area_widget"   class="form-control" name="advanced_area"   data-value="" value="" >              
                    <input type="hidden" id="advanced_country_widget"   class="form-control" name="advanced_country"   data-value="" value="" >              
                    <input type="hidden" id="property_admin_area_widget" name="property_admin_area" value="">
           
                </div>
                
                <div class="has_calendar calendar_icon ">
                    <input type="text" id="checkinwidget" class="form-control " name="check_in" placeholder="'.esc_html__( 'Check in','wpestate').'">       
                </div>
                
                <div class="has_calendar calendar_icon ">
                    <input type="text" id="checkoutwidget" disabled class="form-control " name="check_out" placeholder="'.esc_html__( 'Check Out','wpestate').'">
                </div>
                
                <div class="dropdown form-control guest_form">
                    <div data-toggle="dropdown" id="guest_no_widget" class="filter_menu_trigger" data-value="all"> '.esc_html__( 'Guests','wpestate').' <span class="caret caret_filter"></span> </div>           
                    <input type="hidden" name="guest_no" id="guest_no_wid" value="">
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="guest_no_wid">'. wpestate_get_guest_dropdown().'
                    </ul>
                </div>
                
      
               
                <div class="adv_search_widget">
                    <p>
                        <label>'.esc_html__( 'Price range:','wpestate').'</label>
                        <span id="amount_wd"  style="border:0;">'.$price_slider_label.'</span>
                    </p>
                    <div id="slider_price_widget"></div>
                    <input type="hidden" id="price_low_widget"  name="price_low"  value="'.$min_price_slider.'"/>
                    <input type="hidden" id="price_max_widget"  name="price_max"  value="'.$max_price_slider.'"/>
                </div>';
                
                
                
                
                print'<button class="wpb_btn-info wpb_regularsize wpestate_vc_button  vc_button" id="advanced_submit_widget">'.esc_html__( 'Search','wpestate').'</button>
                </form>  
                '; 
                get_template_part('libs/internal_autocomplete_wpestate');
		print $after_widget;
                
	}

        
        
        
        
        
        
         function normal_fields_widget($action_select_list,$cate_select_list,$select_city_list,$select_area_list){
                        if( !empty($action_select_list) ){
                            print'
                              <div class="dropdown form-control" >
                                <div data-toggle="dropdown" id="sidebar_filter_action" class="sidebar_filter_menu"> '.esc_html__( 'All Sizes','wpestate').' <span class="caret caret_sidebar"></span> </div>           
                                <input type="hidden" name="filter_search_action[]" value="">
                                <ul id="list_sidebar_filter_action" class="dropdown-menu filter_menu" role="menu" aria-labelledby="sidebar_filter_action">
                                    '.$action_select_list.'
                                </ul>        
                              </div>';
                        }
                 
                        if( !empty($cate_select_list) ){
                             print'                                            
                                <div class="dropdown form-control" >
                                    <div data-toggle="dropdown" id="a_sidebar_filter_categ" class="sidebar_filter_menu"> '. esc_html__( 'All Types','wpestate').' <span class="caret caret_sidebar"></span> </div>           
                                        <input type="hidden" name="filter_search_type[]" value="">
                                        <ul id="sidebar_filter_categ" class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_sidebar_filter_categ">
                                        '.$cate_select_list.'
                                    </ul>        
                                  </div>';
                        }
                   
                      if( !empty($select_city_list) ){
                        print'
                             <div class=" dropdown form-control" >
                                <div data-toggle="dropdown" id="sidebar_filter_cities" class="sidebar_filter_menu"> '. esc_html__( 'All Cities','wpestate').' <span class="caret caret_sidebar"></span> </div>           
                                <input type="hidden" name="advanced_city" value="">
                                <ul id="sidebar_filter_city" class="dropdown-menu filter_menu" role="menu" aria-labelledby="sidebar_filter_cities">
                                    '. $select_city_list.'
                                </ul>        
                              </div>  ';
                        }
                  
                      if( !empty($select_area_list) ){
                        print'
                            <div class="dropdown form-control" >
                                <div data-toggle="dropdown" id="sidebar_filter_areas" class="sidebar_filter_menu">'. esc_html__( 'All Areas','wpestate').'<span class="caret caret_sidebar"></span> </div>           
                                <input type="hidden" name="advanced_area" value="">
                                <ul id="sidebar_filter_area" class="dropdown-menu filter_menu" role="menu" aria-labelledby="sidebar_filter_areas">
                                    '.$select_area_list.'
                                </ul>        
                              </div>';
                      }
                    print'    
                    <input type="text" id="adv_rooms_widget" name="advanced_rooms" placeholder="'.esc_html__( 'Type Bedrooms No.','wpestate').'"      class="advanced_select form-control">
                    <input type="text" id="adv_bath_widget"  name="advanced_bath"  placeholder="'.esc_html__( 'Type Bathrooms No.','wpestate').'"  class="advanced_select form-control">';
                  
                 
                    $show_slider_price  =   get_option('wp_estate_show_slider_price','');
                            
                    if ($show_slider_price =='yes'){
                        $where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                        $currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
                        $min_price_slider   = ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                        $max_price_slider   = ( floatval(get_option('wp_estate_show_slider_max_price','')) );

                        $price_slider_label = wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$currency,$where_currency);
                        print'
                            <div class="adv_search_widget">
                                <p>
                                    <label>'.esc_html__( 'Price range:','wpestate').'</label>
                                    <span id="amount_wd"  style="border:0; ">'.$price_slider_label.'</span>
                                </p>
                                <div id="slider_price_widget"></div>
                                <input type="hidden" id="price_low_widget"  name="price_low"  value="'.wpestate_price_default_convert( $min_price_slider).'"/>
                                <input type="hidden" id="price_max_widget"  name="price_max"  value="'.wpestate_price_default_convert ( $max_price_slider).'"/>
                            </div>';
        
                    }else{
                    print '
                    <input type="text" id="price_low_widget" name="price_low"  class="advanced_select form-control" placeholder="'.esc_html__( 'Type Min. Price','wpestate').'"/>
                    <input type="text" id="price_max_widget" name="price_max"  class="advanced_select form-control" placeholder="'.esc_html__( 'Type Max. Price','wpestate').'"/>';
                    }
       
         }
    
}// end class
?>