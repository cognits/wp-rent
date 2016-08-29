<?php
class Wpestate_Multiple_currency_widget extends WP_Widget {
	
	//function Wpestate_Multiple_currency_widget(){
        function __construct(){
		$widget_ops = array('classname' => 'multiple_currency_widget', 'description' => 'Put multiple currency demo on a sidebar');
		$control_ops = array('id_base' => 'wpestate_multiple_currency_widget');
                parent::__construct('wpestate_multiple_currency_widget', 'Wp Estate: Multiple currency widget', $widget_ops, $control_ops);
	}
	
	function form($instance){
		$defaults = array(
                            'title' => esc_html__( 'Change Your Currency','wpestate'),
                            );
		$instance = wp_parse_args((array) $instance, $defaults);
		$display='
                <p>
                    <label for="'.$this->get_field_id('title').'">Title:</label>
		</p>
                <p>
                    <input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />
		</p>';
		print $display;
	}


	function update($new_instance, $old_instance){
		$instance                     = $old_instance;
		$instance['title']            = $new_instance['title'];
		
	
                if( function_exists('icl_register_string') ){
                    icl_register_string('wpestate_Multiple_currency_widget','Multiple_currency_widget_title',$new_instance['title']);
                }
                
                
               return $instance;
	}



	function widget($args, $instance){
		extract($args);
                $display='';
                $cur_list='';
		$title = apply_filters('widget_title', $instance['title']);

		print $before_widget;

		if($title) {
			print $before_title.$title.$after_title;
		}
                
                $multiple_cur = get_option( 'wp_estate_multi_curr', true);  
                $where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
	
                $i=0;
                $normal_cur= esc_html( get_option('wp_estate_currency_symbol') );
                $normal_label   =    get_option('wp_estate_currency_label_main','');
                $cur_list.='<li  role="presentation" data-curpos="'.$where_currency.'"  data-coef="1" data-value="'.$normal_label.'" data-symbol="'.$normal_label.'" data-pos="-1">'.$normal_label .' </li>';
                
                //$cur_list.='<li  role="presentation" data-curpos="'.$where_currency.'"  data-coef="1" data-value="'.$normal_cur.'" data-symbol="'.$normal_cur.'" data-pos="-1">'.$normal_cur .' </li>';
                
                if( !empty($multiple_cur)){  
                    while($i< count($multiple_cur) ){
                        $cur_list.='<li  role="presentation" data-curpos="'.$multiple_cur[$i][3].'" data-coef="'.$multiple_cur[$i][2].'" data-value="'.$multiple_cur[$i][1].'" data-symbol="'.$multiple_cur[$i][0].'" data-pos="'.$i.'"> '. $multiple_cur[$i][1].' </li>';
                        $i++;
                    }
                }$normal_label   =    get_option('wp_estate_currency_label_main','');
             
                
                $display.='<div class="dropdown form-control">
                            <div data-toggle="dropdown" id="sidebar_currency_list" class="sidebar_filter_menu">';
                    if(isset( $_COOKIE['my_custom_curr'] ) ) {
                        $display.= $_COOKIE['my_custom_curr'];
                    }else{
                         $display.= esc_html( $normal_label );
                    }              
                 
                                      
                $display.='  <span class="caret caret_sidebar"></span> 
                                </div>           
                            
                                <input type="hidden" name="filter_curr[]" value="">
                                <ul id="list_sidebar_curr" class="dropdown-menu filter_menu list_sidebar_currency" role="menu" aria-labelledby="sidebar_currency_list">
                                    '.$cur_list.'
                                </ul>        
                            </div>';
  
                                      
                                                 
    
	
		print $display;
		print $after_widget;
	}

}

?>