<?php
class Wpestate_Featured_widget extends WP_Widget {
	
    
//	function Wpestate_Featured_widget(){
	function __construct(){
                $widget_ops = array('classname' => 'featured_sidebar', 'description' => 'Put a featured listing on sidebar.');
		$control_ops = array('id_base' => 'wpestate_featured_widget');
                parent::__construct('wpestate_featured_widget', 'Wp Estate: Featured Listing', $widget_ops, $control_ops);
	}
	
	function form($instance){
		$defaults = array('title' => 'Featured Listing',
                                  'prop_id'=>'',
                                  'second_line'=>''
                    );
		$instance = wp_parse_args((array) $instance, $defaults);
		$display='<p>
			<label for="'.$this->get_field_id('prop_id').'">Property Id:</label>
		</p><p>
			<input id="'.$this->get_field_id('prop_id').'" name="'.$this->get_field_name('prop_id').'" value="'.$instance['prop_id'].'" />
		</p>';
		print $display;
	}


	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['prop_id'] = $new_instance['prop_id'];
		$instance['second_line'] = $new_instance['second_line'];
		
		return $instance;
	}



	function widget($args, $instance){
		extract($args);
                $display='';
                global $property_unit_slider;

                $property_unit_slider       =   esc_html ( get_option('wp_estate_prop_list_slider','') ); 

		print $before_widget;
                //$display.='<div class="featured_sidebar_intern">';
		
                $args=array( 
                            'post_type'         => 'estate_property',
                            'post_status'       => 'publish',
                            'p'                 =>  $instance['prop_id']
                            );
                $the_query = new WP_Query( $args );
                
                $display.= '<div class="featured_property">';
                ob_start(); 
                // The Loop
                while ( $the_query->have_posts() ) :
                    $the_query->the_post();
                    get_template_part('templates/property_unit_featured'); 
                endwhile;
           
                $display .=  ob_get_contents();
                ob_end_clean();  
                wp_reset_query();
                $display.='</div>'; 
		
                print $display;
		print $after_widget;
	 }




}

?>