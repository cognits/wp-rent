<?php
class WPestate_Facebook_Widget extends WP_Widget {	
	
        //
        //function WPestate_Facebook_Widget(){
        function __construct(){
                $widget_ops = array('classname' => 'facebook_widget_like', 'description' => 'Insert a Facebook Like Box.');
		$control_ops = array('id_base' => 'wpestate_facebook_widget');
                parent::__construct('wpestate_facebook_widget', 'Wp Estate: Facebook Box', $widget_ops, $control_ops);
	}


	function form($instance)
	{
		$defaults = array('title' =>'Find us on Facebook', 'url' => '', 'box_width' => '373', 'color_theme' => 'light', 'faces' => 'on', 'stream' => false, 'header' => false);
		$instance = wp_parse_args((array) $instance, $defaults);
		
		$theme_light=$theme_dark='';
		if ($instance['color_theme']=='light'){
			$theme_light='selected="selected"';
		}
		if ($instance['color_theme']=='dark'){
			$theme_dark='selected="selected"';
		}	
				
		$display='<p><label for="'.$this->get_field_id('title').'">Title:</label>
			<input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />
		</p><p>
			<label for="'.$this->get_field_id('url').'">Facebook Page URL:</label>
		</p><p>
			<input   id="'.$this->get_field_id('url').'" name="'.$this->get_field_name('url').'" value="'.$instance['url'].'" />
		</p><p>
			<label for="'.$this->get_field_id('box_width').'">Width:</label>
		</p><p>	
			<input id="'.$this->get_field_id('box_width').'" name="'.$this->get_field_name('box_width').'" value="'.$instance['box_width'].'" />
		</p><p>
			<label for="'.$this->get_field_id('color_theme').'">Color Scheme:</label> 
		</p><p>	
			<select id="'.$this->get_field_id('color_theme').'" name="'.$this->get_field_name('color_theme').'">
					<option value="light" '.$theme_light.'>light</option>
				    <option value="dark" '.$theme_dark.'>dark</option>
			</select>
		</p><p>
			<label for="'.$this->get_field_id('faces').'">Show faces</label>
		</p><p>
			<input type="checkbox" id="'.$this->get_field_id('faces').'" name="'.$this->get_field_name('faces').'"    '.checked($instance['faces'],'on',false).'  /> 
		</p><p>
			<label for="'.$this->get_field_id('stream').'">Show stream</label>
		</p><p>
			<input type="checkbox"  id="'.$this->get_field_id('stream').'" name="'.$this->get_field_name('stream').'" '.checked($instance['stream'],'on',false).'/> 
		</p>';
	print $display;
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['url'] = $new_instance['url'];
		$instance['box_width'] = $new_instance['box_width'];
		$instance['color_theme'] = $new_instance['color_theme'];
		$instance['faces'] = $new_instance['faces'];
		$instance['stream'] = $new_instance['stream'];
		$instance['header'] = $new_instance['header'];	
		return $instance;
	}

	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$page_url = $instance['url'];
		$box_width = $instance['box_width'];
		$color_theme = $instance['color_theme'];
		$box_height = '75';
		$box_height_extra='75';
                $color_scheme='';
		if( isset($instance['faces']) ){
			 $faces='true';
			 $box_height = '260';
			 $box_height_extra='360';
		}else{
			$faces='false';
			}

		if( isset($instance['stream']) ){
				if ($faces=='false'){
				   $stream='true';
				   $box_height = '360';
				   $box_height_extra='425';
				}else{
				   $stream='true';
				   $box_height = '600';
				   $box_height_extra='690';
				  }
		}else{
			$stream='false';
			}
			
			
		if( isset($instance['header']) ){
			 $header='true';
			// $box_height = '600';
			 // $box_height_extra='690';
		}else{
			$header='false';
			}
		
		print $before_widget;
		if($title) {
			print $before_title.$title.$after_title;
		}
		
		if($page_url){
			
		$display='<iframe id="facebook_wid" src="http://www.facebook.com/plugins/likebox.php?href='.urlencode($page_url).'&amp;width='.$box_width.'&amp;height='.$box_height.'&amp;colorscheme='.$color_scheme.'&amp;show_faces='.$faces.'&amp;stream='.$stream.'&amp;header='.$header.'&amp;"  style="border:none; overflow:hidden; width:'.$box_width.'px; height:'.$box_height.'px;background-color:white;" ></iframe>';
		}
		print $display;
		print $after_widget;
	}
	

}


?>