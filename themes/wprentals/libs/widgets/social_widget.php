<?php
class Wpestate_Social_widget extends WP_Widget {

	//function Wpestate_Social_widget(){
        function __construct(){    
		$widget_ops = array('classname' => 'social_sidebar', 'description' => 'Social Links on sidebar.');
		$control_ops = array('id_base' => 'wpestate_social_widget');
                parent::__construct('wpestate_social_widget', 'Wp Estate: Social Links', $widget_ops, $control_ops);
	}
	
function form($instance){
		$defaults = array(  'title' => 'Social Links:',
                                    'facebook'=>'',
                                    'rss'=>'',
                                    'twitter'=>'',
                                    'dribbble'=>'',
                                    'google'=>'',
                                    'linkedIn'=>'',
                                    'blogger'=>'',
                                    'tumblr'=>'',
                                    'pinterest'=>'',
                                    'yahoo'=>'',
                                    'deviantart'=>'',
                                    'youtube'=>'',
                                    'vimeo'=>'',
                                    'instagram'=>'',
                                    'foursquare'=>'',
                                    );
		$instance = wp_parse_args((array) $instance, $defaults);
		$display='<p>
			<label for="'.$this->get_field_id('title').'">Title:</label>
		</p><p>
			<input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />
		</p><p>
			<label for="'.$this->get_field_id('rss').'">Rss Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('rss').'" name="'.$this->get_field_name('rss').'" value="'.$instance['rss'].'" />
		</p><p>
			<label for="'.$this->get_field_id('facebook').'">Facebook Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('facebook').'" name="'.$this->get_field_name('facebook').'" value="'.$instance['facebook'].'" />
		</p><p>
			<label for="'.$this->get_field_id('twitter').'">Twitter Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('twitter').'" name="'.$this->get_field_name('twitter').'" value="'.$instance['twitter'].'" />
		</p><p>
			<label for="'.$this->get_field_id('dribbble').'">Dribbble Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('dribbble').'" name="'.$this->get_field_name('dribbble').'" value="'.$instance['dribbble'].'" />
		</p><p>
			<label for="'.$this->get_field_id('google').'">Google+ Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('google').'" name="'.$this->get_field_name('google').'" value="'.$instance['google'].'" />
		</p><p>
			<label for="'.$this->get_field_id('linkedIn').'">LinkedIn Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('linkedIn').'" name="'.$this->get_field_name('linkedIn').'" value="'.$instance['linkedIn'].'" />
		</p><p>
			<label for="'.$this->get_field_id('tumblr').'">Tumblr Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('tumblr').'" name="'.$this->get_field_name('tumblr').'" value="'.$instance['tumblr'].'" />
		</p><p>
			<label for="'.$this->get_field_id('pinterest').'">Pinterest Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('pinterest').'" name="'.$this->get_field_name('pinterest').'" value="'.$instance['pinterest'].'" />
		</p><p>	
			<label for="'.$this->get_field_id('youtube').'">You Tube Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('youtube').'" name="'.$this->get_field_name('youtube').'" value="'.$instance['youtube'].'" />
		</p><p>
			<label for="'.$this->get_field_id('vimeo').'">Vimeo Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('vimeo').'" name="'.$this->get_field_name('vimeo').'" value="'.$instance['vimeo'].'" />
		</p><p>
			<label for="'.$this->get_field_id('instagram').'">Instagram Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('instagram').'" name="'.$this->get_field_name('instagram').'" value="'.$instance['instagram'].'" />
		</p><p>
			<label for="'.$this->get_field_id('foursquare').'">Foursquare Link:</label>
		</p><p>
			<input id="'.$this->get_field_id('foursquare').'" name="'.$this->get_field_name('foursquare').'" value="'.$instance['foursquare'].'" />
		</p>
		';
		print $display;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title']      = $new_instance['title'];
		$instance['rss']        = $new_instance['rss'];
		$instance['facebook']   = $new_instance['facebook'];
		$instance['twitter']    = $new_instance['twitter'];
		$instance['email']      = $new_instance['email'];
		$instance['dribbble']   = $new_instance['dribbble'];
		$instance['google']     = $new_instance['google'];
		$instance['linkedIn']   = $new_instance['linkedIn'];
		$instance['phone_no']   = $new_instance['phone_no'];
		$instance['tumblr']     = $new_instance['tumblr'];
		$instance['pinterest']  = $new_instance['pinterest'];
		$instance['youtube']    = $new_instance['youtube'];
		$instance['vimeo']      = $new_instance['vimeo'];
                $instance['instagram']  = $new_instance['instagram'];
		$instance['foursquare'] = $new_instance['foursquare'];
                
		return $instance;
	}

	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
                $display='';
		print $before_widget;

		if($title) {
			print $before_title.$title.$after_title;
		}
		$display.='<div class="social_sidebar_internal">';
		
		if($instance['rss']){
			$display.='<a href="'.$instance['rss'].'" target="_blank"><i class="fa fa-rss  fa-fw"></i></a>';
		}

		if($instance['facebook']){
			$display.='<a href="'.$instance['facebook'].'" target="_blank"><i class="fa fa-facebook  fa-fw"></i></a>';
		}
		if($instance['twitter']){
			$display.='<a href="'.$instance['twitter'].'" target="_blank"><i class="fa fa-twitter  fa-fw"></i></a>';
		}
		
		if($instance['dribbble']){
			$display.='<a href="'.$instance['dribbble'].'" target="_blank"><i class="fa fa-dribbble  fa-fw"></i></a>';
		}
		if($instance['google']){
			$display.='<a href="'.$instance['google'].'" target="_blank"><i class="fa fa-google-plus  fa-fw"></i></a>';
		}
		if($instance['linkedIn']){
			$display.='<a href="'.$instance['linkedIn'].'" target="_blank"><i class="fa fa-linkedin  fa-fw"></i></a>';
		}
		
		if($instance['tumblr']){
			$display.='<a href="'.$instance['tumblr'].'" target="_blank"><i class="fa fa-tumblr  fa-fw"></i></a>';
		}
		if($instance['pinterest']){
			$display.='<a href="'.$instance['pinterest'].'" target="_blank"><i class="fa fa-pinterest  fa-fw"></i></a>';
		}
		
		
		if($instance['youtube']){
			$display.='<a href="'.$instance['youtube'].'" target="_blank"><i class="fa fa-youtube  fa-fw"></i></a>';
		}
		if($instance['vimeo']){
			$display.='<a href="'.$instance['vimeo'].'" target="_blank"><i class="fa fa-vimeo-square  fa-fw"></i></a>';
		}
		if($instance['instagram']){
			$display.='<a href="'.$instance['instagram'].'" target="_blank"><i class="fa fa-instagram  fa-fw"></i></a>';
		}
		if($instance['foursquare']){
			$display.='<a href="'.$instance['foursquare'].'" target="_blank"><i class="fa  fa-foursquare  fa-fw"></i></a>';
		}
		
		
		
		$display.='</div>';
		print $display;
		print $after_widget;
	}
}
















?>