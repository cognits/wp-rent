<?php
class Wpestate_Contact_widget extends WP_Widget {
	
	//function Wpestate_Contact_widget(){
        function __construct(){
		$widget_ops = array('classname' => 'contact_sidebar', 'description' => 'Put you contact info on sidebar.');
		$control_ops = array('id_base' => 'wpestate_contact_widget');
                parent::__construct('wpestate_contact_widget', 'Wp Estate: Contact', $widget_ops, $control_ops);
	}
	
	function form($instance){
		$defaults = array('title' => 'Contact',
                                  'address_info'=>'',
                                  'phone_no'=>'',
                                  'fax_no'=>'',
                                  'email'=>'',
                                  'skype'=>'',
                                  'website_url_text'=>'',
                                  'website_url'=>'');
		$instance = wp_parse_args((array) $instance, $defaults);
		$display='<p>
			<label for="'.$this->get_field_id('title').'">Title:</label>
		</p><p>
			<input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />
		</p><p>
			<label for="'.$this->get_field_id('address_info').'">Address:</label>
		</p><p>
			<input id="'.$this->get_field_id('address_info').'" name="'.$this->get_field_name('address_info').'" value="'.$instance['address_info'].'" />
		</p><p>
			<label for="'.$this->get_field_id('phone_no').'">Phone:</label>
		</p><p>
			<input  id="'.$this->get_field_id('phone_no').'" name="'.$this->get_field_name('phone_no').'" value="'.$instance['phone_no'].'" />
		</p><p>
			<label for="'.$this->get_field_id('fax_no').'">Fax:</label>
		</p><p>	
			<input   id="'.$this->get_field_id('fax_no').'" name="'.$this->get_field_name('fax_no').'" value="'.$instance['fax_no'].'" />
		</p><p>
			<label for="'.$this->get_field_id('email').'">Email:</label>
		</p><p>	
			<input   id="'.$this->get_field_id('email').'" name="'.$this->get_field_name('email').'" value="'.$instance['email'].'" />
		</p><p>
			<label for="'.$this->get_field_id('skype').'">Skype:</label>
		</p><p>	
			<input   id="'.$this->get_field_id('skype').'" name="'.$this->get_field_name('skype').'" value="'.$instance['skype'].'" />
		</p><p>
			<label for="'.$this->get_field_id('website_url').'">URL:</label>
		</p><p>	
			<input id="'.$this->get_field_id('website_url').'" name="'.$this->get_field_name('website_url').'" value="'.$instance['website_url'].'" />
		</p><p>
			<label for="'.$this->get_field_id('website_url_text').'">URL Text:</label>
		</p><p>	
			<input id="'.$this->get_field_id('website_url_text').'" name="'.$this->get_field_name('website_url_text').'" value="'.$instance['website_url_text'].'" />
		</p>';
		print $display;
	}


	function update($new_instance, $old_instance){
		$instance                     = $old_instance;
		$instance['title']            = $new_instance['title'];
		$instance['address_info']     = $new_instance['address_info'];
		$instance['phone_no']         = $new_instance['phone_no'];
		$instance['fax_no']           = $new_instance['fax_no'];
		$instance['email']            = $new_instance['email'];
		$instance['skype']            = $new_instance['skype'];
		$instance['website_url']      = $new_instance['website_url'];
                $instance['website_url_text'] = $new_instance['website_url_text'];
	
                if( function_exists('icl_register_string') ){
                    icl_register_string('wpestate_contact_widget','contact_widget_title',$new_instance['title']);
                    icl_register_string('wpestate_contact_widget','contact_widget_address_info',$new_instance['address_info']);
                    icl_register_string('wpestate_contact_widget','contact_widget_phone_no',$new_instance['phone_no']);
                    icl_register_string('wpestate_contact_widget','contact_widget_fax_no',$new_instance['fax_no']);
                    icl_register_string('wpestate_contact_widget','contact_widget_email',$new_instance['email']);
                    icl_register_string('wpestate_contact_widget','contact_widget_skype',$new_instance['skype']);
                    icl_register_string('wpestate_contact_widget','contact_widget_website_url',$new_instance['website_url']);
                    icl_register_string('wpestate_contact_widget','contact_widget_website_url_text',$new_instance['website_url_text']);
                }
                
                
                return $instance;
	}



	function widget($args, $instance){
		extract($args);
                $display='';
		$title = apply_filters('widget_title', $instance['title']);

		print $before_widget;

		if($title) {
			print $before_title.$title.$after_title;
		}
		$display.='<div class="contact_sidebar_wrap">';
		if($instance['address_info']){
                    if (function_exists('icl_t') ){
                        $co_address         =    icl_t('wpestate_contact_widget','contact_widget_address_info', $instance['address_info'] );
                    }else{
                        $co_address         = $instance['address_info'];
                    }
                    $display.='<p class="widget_contact_addr"><i class="fa fa-building-o"></i>'.$co_address.'</p>';
		}

		if($instance['phone_no']){
                    if (function_exists('icl_t') ){
                        $co_phone_no= icl_t('wpestate_contact_widget','contact_widget_phone_no', $instance['phone_no'] );
                    }else{
                        $co_phone_no = $instance['phone_no'];
                    }
                    $display.='<p class="widget_contact_phone"><i class="fa fa-phone"></i><a href="tel:'.urlencode( $co_phone_no ).'">'.$co_phone_no.'</a></p>';
		}
		
		if($instance['fax_no']){
                    if (function_exists('icl_t') ){
                        $co_fax_no= icl_t('wpestate_contact_widget','contact_widget_fax_no', $instance['fax_no'] );
                    }else{
                        $co_fax_no = $instance['fax_no'];
                    }
                    $display.='<p class="widget_contact_fax"><i class="fa fa-print"></i>'. $co_fax_no .'</p>';
		}
		
		if($instance['email']){
                    if (function_exists('icl_t') ){
                        $co_email= icl_t('wpestate_contact_widget','contact_widget_email', $instance['email'] );
                    }else{
                        $co_email = $instance['email'];
                    }
                    $display.='<p class="widget_contact_email"><i class="fa fa-envelope-o"></i><a href="mailto:'.$co_email.'">'.$co_email.'</a></p>';
		}
		
		if($instance['skype']){
                    if (function_exists('icl_t') ){
                        $co_skype= icl_t('wpestate_contact_widget','contact_widget_skype', $instance['skype'] );
                    }else{
                        $co_skype = $instance['skype'];
                    }
                    $display.='<p class="widget_contact_skype"><i class="fa fa-skype"></i>'.$co_skype.'</p>';
		}
		
		if($instance['website_url'] && $instance['website_url_text']){
                    if (function_exists('icl_t') ){
                        $co_website_url         = icl_t('wpestate_contact_widget','contact_widget_website_url', $instance['website_url'] );
                        $co_website_url_text    = icl_t('wpestate_contact_widget','contact_widget_website_url_text', $instance['website_url_text'] );
                    }else{
                        $co_website_url         = $instance['website_url'];
                        $co_website_url_text    = $instance['website_url_text'];
                    }
                    
                    $display.='<p class="widget_contact_url"><i class="fa fa-desktop"></i><a href="'.$co_website_url.'">'.$co_website_url_text.'</a></p>';
		}
		$display.='</div>';
		print $display;
		print $after_widget;
	}

}

?>