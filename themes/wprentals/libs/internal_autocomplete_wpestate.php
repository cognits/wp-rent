<?php
 $show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
if($show_adv_search_general=='no'){
 
    $availableTags=get_option('wpestate_autocomplete_data',true);
    
    print '<script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready(function(){
            var availableTags = ['.$availableTags.'];
            jQuery("#search_location_autointernal,#search_location_mobile_autointernal,#search_location_filter_widget_autointernal,#search_location_filter_shortcode_autointernal,#search_location_filter_autointernal").autocomplete({
                source: function(request, response) {
                    var results = jQuery.ui.autocomplete.filter(availableTags, request.term);
                    response(results.slice(0, 10));
                },
                select: function (a, b) {
                    jQuery(".stype").val(b.item.category);    
                
                    if (document.getElementById("search_location_filter_autointernal")) {
                   
                        jQuery("#search_location_filter_autointernal").val(b.item.label);
                        start_filtering_ajax_map(1);
                    }
                }
            });
        });
        //]]>
    </script>';
    
    
    
    

}




?>