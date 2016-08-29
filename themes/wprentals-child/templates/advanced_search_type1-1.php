<?php 
global $post;
$adv_search_what            =   get_option('wp_estate_adv_search_what','');
$show_adv_search_visible    =   get_option('wp_estate_show_adv_search_visible','');
$close_class                =   '';

if($show_adv_search_visible=='no'){
    $close_class='adv-search-1-close';
}

if(isset( $post->ID)){
    $post_id = $post->ID;
}else{
    $post_id = '';
}

$extended_search    =   get_option('wp_estate_show_adv_search_extended','');
$extended_class     =   '';

if ( $extended_search =='yes' ){
    $extended_class='adv_extended_class';
    if($show_adv_search_visible=='no'){
        $close_class='adv-search-1-close-extended';
    }      
}
    
$header_type                =   get_post_meta ( $post->ID, 'header_type', true);
$global_header_type         =   get_option('wp_estate_header_type','');

$google_map_lower_class='';
 if (!$header_type==0){  // is not global settings
    if ($header_type==5){ 
        $google_map_lower_class='adv_lower_class';
    }
}else{    // we don't have particular settings - applt global header          
    if($global_header_type==4){
        $google_map_lower_class='adv_lower_class';
    }
} // end if header
    
    
    
    
?>

 
 <div class="adv-1-wrapper"> 
    </div>  

<div class="adv-search-1 <?php echo $google_map_lower_class.' '.$close_class.' '.$extended_class;?>" id="adv-search-1" data-postid="<?php echo $post_id; ?>"> 

  
    <form  method="get"  id="main_search" action="<?php print $adv_submit; ?>" >
        <?php
        if (function_exists('icl_translate') ){
            print do_action( 'wpml_add_language_form_field' );
        }
        ?>
        <div style="display:none;" id="searchmap"></div>

        <div class="col-md-2">
            <div class="dropdown form-control " >
                <div data-toggle="dropdown" id="search_location" class="filter_menu_trigger" data-value="all"> <?php esc_html_e('colonia/barrio','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="search_location"  id="search_location" value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="search_location">
                    <?php echo wpestate_get_city_select_list();?>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <div class="dropdown form-control" >
                <div data-toggle="dropdown" id="rooms_no" class="filter_menu_trigger" data-value="all"> <?php esc_html_e('Rooms','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="rooms_no"  id="rooms_no_input" value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="rooms_no">
                    <?php echo wpestate_get_rooms_dropdown();?>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <div class="dropdown form-control" >
                <div data-toggle="dropdown" id="baths_no" class="filter_menu_trigger" data-value="all"><?php echo esc_html__( 'Baths','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="baths_no" id="baths_no_input"  value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="baths_no">
                    <?php echo wpestate_get_baths_dropdown(); ?>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <div class="dropdown form-control" >
                <div data-toggle="dropdown" id="plazo_arrendamiento" class="filter_menu_trigger" data-value="all"><?php echo esc_html__( 'plazo arrendamiento','wpestate');?> <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="plazo_arrendamiento" id="plazo_arrendamiento_input"  value="">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="plazo_arrendamiento">
                    <li role="presentation" data-value="3-meses">+3 meses</li>
                    <li role="presentation" data-value="6-meses">6 meses</li>
                    <li role="presentation" data-value="12-meses">12 meses</li>
                    <li role="presentation" data-value="otro">Otro</li>
                </ul>
            </div>
        </div>

        <div class="col-md-2 has_calendar calendar_icon">
            <input type="text" id="check_in"    class="form-control " name="move_in"  placeholder="<?php esc_html_e('Move in','wpestate');?>" 
                value="<?php  
                if(isset($_GET['move_in'])){
                    echo esc_attr($_GET['move_in']);
                }?>" >       
        </div>

        <div class="col-md-2">
        <input name="submit" type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="advanced_submit_2" value="<?php esc_html_e('Search','wpestate');?>">
        </div>
              
        <div id="results">
            <?php esc_html_e('We found ','wpestate')?> <span id="results_no">0</span> <?php esc_html_e('results.','wpestate'); ?>  
            <span id="showinpage"> <?php esc_html_e('Do you want to load the results now ?','wpestate');?> </span>
        </div>

    </form>   

</div>  
<?php get_template_part('libs/internal_autocomplete_wpestate'); ?>