<?php 
$allowed_html=array();
if( is_tax() ){
    
    $current_adv_filter_search_label    ='All Sizes';
    $current_adv_filter_category_label  ='All Types';
    $current_adv_filter_city_label      ='All Cities';
    $current_adv_filter_area_label      ='All Areas';
    $taxonmy                            = get_query_var('taxonomy');
//  $term                               = get_query_var( 'name' );
    $term                               = single_cat_title('',false);
    
    if ($taxonmy == 'property_city'){
        $current_adv_filter_city_label = ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_area'){
        $current_adv_filter_area_label = ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_category'){
        $current_adv_filter_category_label = ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_action_category'){
        $current_adv_filter_search_label = ucwords( str_replace('-',' ',$term) );
    }
?>

<div  id="a_filter_action" data-value="<?php print wp_kses($current_adv_filter_search_label,$allowed_html);?>"> </div>           
<div  id="a_filter_categ"  data-value="<?php print wp_kses($current_adv_filter_category_label,$allowed_html);?> "> </div>           
<div  id="a_filter_cities" data-value="<?php print wp_kses($current_adv_filter_city_label,$allowed_html);?>"> </div>           
<div  id="a_filter_areas"  data-value="<?php print wp_kses($current_adv_filter_area_label,$allowed_html);?>">  </div>           

<?php
}
?>                                              