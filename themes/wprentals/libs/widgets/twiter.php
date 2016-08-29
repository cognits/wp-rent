<?php

class Wpestate_Tweet_Widget extends WP_Widget {	
	
        //function Wpestate_Tweet_Widget(){
        function __construct(){    
		$widget_ops = array('classname' => 'twitter_wrapper', 'description' => 'show your latest tweets');
		$control_ops = array('id_base' => 'wpestate_tweet_widget');
                parent::__construct('wpestate_tweet_widget', 'Wp Estate Twitter Widget', $widget_ops, $control_ops);
	}

	function form($instance)
	{
		$defaults = array('title' => 'Latest Tweets', 'twitter_id' => '','tweets_no' => 3);
		$instance = wp_parse_args((array) $instance, $defaults);
		$display='<p><label for="'.$this->get_field_id('title').'">'.esc_html__( 'Title','wpestate').':</label>
		</p><p><input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />
		</p><p><label for="'.$this->get_field_id('twitter_id').'">'.esc_html__( 'Your Twitter Username','wpestate').':</label>
		</p><p><input id="'.$this->get_field_id('twitter_id').'" name="'.$this->get_field_name('twitter_id').'" value="'.$instance['twitter_id'].'" />
		</p><p><label for="'.$this->get_field_id('tweets_no').'">'.esc_html__( 'How many Tweets','wpestate').':</label>
		</p><p><input id="'.$this->get_field_id('tweets_no').'" name="'.$this->get_field_name('tweets_no').'" value="'.$instance['tweets_no'].'" />
		</p>';
		print $display;
	}


	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['twitter_id'] = $new_instance['twitter_id'];
		$instance['tweets_no'] = $new_instance['tweets_no'];
		return $instance;
	}


	function widget($args, $instance)
	{       
                $display='';
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		print $before_widget;
	
                
		$twitter_consumer_key       = get_option('wp_estate_twitter_consumer_key','');
                $twitter_consumer_secret    = get_option('wp_estate_twitter_consumer_secret','');
                $twitter_access_token       = get_option('wp_estate_twitter_access_token','');
                $twitter_access_secret      = get_option('wp_estate_twitter_access_secret','');
      
                $twitter_cache_time         = get_option('wp_estate_twitter_cache_time','');
                $username                   = $instance['twitter_id'];
		$how_many                   = $instance['tweets_no'];
                
		
                $tw_last_cache_time = get_option('$tw_last_cache_time');
                $diff = time() - $tw_last_cache_time;
                $crt = $twitter_cache_time * 3600;
                
                if($diff >= $crt || empty($tp_twitter_plugin_last_cache_time)){   
                    require_once get_template_directory().'/libs/widgets/twitter-api-wordpress.php';
                }
                
                $settings = array(
                        'oauth_access_token' => $twitter_access_token,
                        'oauth_access_token_secret' =>$twitter_access_secret,
                        'consumer_key' => $twitter_consumer_key,
                        'consumer_secret' => $twitter_consumer_secret
                );
                $url            = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
                $getfield       = '?screen_name='.$username;
                $request_method = 'GET';
                $twitter_instance = new Twitter_API_WordPress( $settings );

          


                if( $twitter_consumer_key!='' && $twitter_consumer_secret!=''  && $twitter_access_token!=''  && $twitter_access_secret!=''  ){
                
                    if($username!=''){
                    
                        $got_tweets = $twitter_instance
                        ->set_get_field( $getfield )
                        ->build_oauth( $url, $request_method )
                        ->process_request();
                        $got_tweets=  json_decode($got_tweets);

                        
                        if(!empty($got_tweets->errors)){
                          print $display='<strong>'.$got_tweets->errors[0]->message.'</strong>';
                      
                        }else{
                           
                            for($i = 0;$i <= count($got_tweets); $i++){
                                if(!empty($got_tweets[$i])){
                                        $got_tweets_array[$i]['when'] =    $got_tweets[$i]->created_at;
                                        $got_tweets_array[$i]['text'] =  $got_tweets[$i]->text;			
                                        $got_tweets_array[$i]['status'] = $got_tweets[$i]->id_str;			
                                }	
                            }

                            update_option('twiter_array_serial',serialize($got_tweets_array));							
                            update_option('tw_last_cache_time',time());
                            $wpestate_tweets = maybe_unserialize(get_option('twiter_array_serial'));

                            if(!empty($wpestate_tweets)){

                                $fctr       =   1;
                                $counter    =   0;
                                $slides     =   '';
                                $indicators =   '';
                                foreach($wpestate_tweets as $tweet){
                                        


                               
                                    $string_twet = preg_replace(
                                                  "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
                                                  "<a href=\"\\0\">\\0</a>", 
                                                  $tweet['text']);

                                    $slides.= '
                                    <div class="item">
                                        <span>'.$string_twet.'</span><br />
                                        <a class="twitter_time" target="_blank" href="http://twitter.com/'.$username.'/statuses/'.$tweet['status'].'">'.wpestate_relative_time($tweet['when']).'</a>
                                    </div>';



                                    if($fctr == $how_many){ 
                                        break; 
                                    }
                                    $fctr++;
                                    $counter++;
                                }
                                print '<div class="carousel slide wpestate_recent_tweets" >';
                                if($title) {
                                    print $before_title.$title.$after_title;
                                }
                                print $slides.'</div>';
                           
                        }
                        else{
                           $display.=esc_html__( 'Please add your Twitter ID!','wpestate');
                        }
                } }
                else{
                    $display.=esc_html__( 'Please add Twitter Api access info in Theme Options ','wpestate');
                }
                
                print $display;
		print $after_widget;
	}
    }
}



//convert dates to readable format	
function wpestate_relative_time($a) {
    //get current timestampt
    $b = strtotime("now"); 
    //get timestamp when tweet created
    $c = strtotime($a);
    //get difference
    $d = $b - $c;
    //calculate different time values
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;

    if(is_numeric($d) && $d > 0) {
            //if less then 3 seconds
            if($d < 3) return "right now";
            //if less then minute
            if($d < $minute) return floor($d) . " seconds ago";
            //if less then 2 minutes
            if($d < $minute * 2) return "about 1 minute ago";
            //if less then hour
            if($d < $hour) return floor($d / $minute) . " minutes ago";
            //if less then 2 hours
            if($d < $hour * 2) return "about 1 hour ago";
            //if less then day
            if($d < $day) return floor($d / $hour) . " hours ago";
            //if more then day, but less then 2 days
            if($d > $day && $d < $day * 2) return "yesterday";
            //if less then year
            if($d < $day * 365) return floor($d / $day) . " days ago";
            //else return more than a year
            return "over a year ago";
    }
}


?>