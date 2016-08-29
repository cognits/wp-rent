<?php
require get_template_directory().'/libs/widgets/twiter.php';
require get_template_directory().'/libs/widgets/facebook.php';
require get_template_directory().'/libs/widgets/contact_widget.php';
require get_template_directory().'/libs/widgets/social_widget.php';
require get_template_directory().'/libs/widgets/featured_widget.php';
require get_template_directory().'/libs/widgets/footer_latest_widget.php';
require get_template_directory().'/libs/widgets/advanced_search.php';
require get_template_directory().'/libs/widgets/login_widget.php';
require get_template_directory().'/libs/widgets/social_widget_top_bar.php';
require get_template_directory().'/libs/widgets/multiple_currency.php';

if( !function_exists('register_wpestate_widgets') ):
 
function register_wpestate_widgets() {    
    wpestate_widgets_init();
    register_widget('Wpestate_Tweet_Widget');
    register_widget('WPestate_Facebook_Widget');
    register_widget('Wpestate_Contact_widget');
    register_widget('Wpestate_Social_widget');
    register_widget('Wpestate_Featured_widget');
    register_widget('Wpestate_footer_latest_widget');
    register_widget('Wpestate_Advanced_Search_widget');
    register_widget('Wpestate_Login_widget');
    register_widget('Wpestate_Social_widget_top');
    register_widget('Wpestate_Multiple_currency_widget');
}  

endif; // end   register_wpestate_widgets  
?>