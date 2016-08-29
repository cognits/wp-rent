<?php
global $custom_advanced_search;  
global $adv_search_what;
global $adv_search_label;


?>
<div class="search_unit_wrapper">
    <h4> <?php the_title(); ?> </h4>
    <a class="delete_search" data-searchid="<?php print $post->ID; ?>"><?php esc_html_e('delete search','wpestate');?></a>
    <?php  
    $search_arguments=  get_post_meta($post->ID, 'search_arguments', true) ;
    $search_arguments_decoded= json_decode($search_arguments);
    
    
    //print  json_last_error();
    //print_r($search_arguments_decoded);
    
    print '<div class="search_param"><strong>'.esc_html__( 'Search Parameters: ','wpestate').'</strong>';
    foreach($search_arguments_decoded->tax_query as $key=>$query ){
        
        if ( isset($query->taxonomy) && isset($query->terms[0]) && $query->taxonomy=='property_category'){
            $page = get_term_by( 'slug',$query->terms[0] ,'property_category');
            if( !empty($page) ){
                print '<strong>'.esc_html__( 'Category','wpestate').':</strong> '. $page->name .', ';  
            }
        }
        
        if ( isset($query->taxonomy) && isset($query->terms[0]) && $query->taxonomy=='property_action_category'){
           $page = get_term_by( 'slug',$query->terms[0] ,'property_action_category');
            if( !empty($page) ){
                print '<strong>'.esc_html__( 'For','wpestate').':</strong> '.$page->name.', ';  
            }
            
        }
        
        if ( isset($query->taxonomy) && isset($query->terms[0]) && $query->taxonomy=='property_city'){
            $page = get_term_by( 'slug',urldecode($query->terms[0]) ,'property_city');
            if( !empty($page) ){
                print '<strong>'.esc_html__( 'City','wpestate').':</strong> '.$page->name.', ';  
            }
            
        }
        
        if ( isset($query->taxonomy) && isset($query->terms[0]) && $query->taxonomy=='property_area'){
            $page = get_term_by( 'slug',urldecode($query->terms[0] ),'property_area');
            if( !empty($page) ){
                print '<strong>'.esc_html__( 'Area','wpestate').':</strong> '.$page->name.', ';  
            }
                
        }
    }
 
    foreach($search_arguments_decoded->meta_query as $key=>$query ){
        if($custom_advanced_search==='yes'){
            
            $custm_name = wpestate_get_custom_field_name($query->key,$adv_search_what,$adv_search_label);
            if ( isset($query->compare) ){
                
                
                
                
                if ($query->compare=='CHAR'){
                    print esc_html__( 'has','wpestate').' <strong>'.str_replace('_',' ',$custm_name).'</strong>, ';       
                }else if ($query->compare=='<='){
                    print '<strong>'.$custm_name.'</strong> '.esc_html__( 'smaller than ','wpestate').' '.$query->value.', ';            
                }  else{
                    print '<strong>'.$custm_name.'</strong> '.esc_html__( 'bigger than','wpestate').' '.$query->value.', ';   
                }                
            }else{
                print '<strong>'.$custm_name.':</strong> '.$query->value.', ';
            } //end elese query compare
            
            
        }else{
            if ( isset($query->compare) ){
                if ($query->compare=='CHAR'){
                    print esc_html__( 'has','wpestate').' <strong>'.str_replace('_',' ',$query->key).'</strong>, ';       
                }else if ($query->compare=='<='){
                    print '<strong>'.str_replace('_',' ',$query->key).'</strong> '.esc_html__( 'smaller than ','wpestate').' '.$query->value.', ';            
                } else{
                     print '<strong>'.str_replace('_',' ',$query->key).'</strong> '.esc_html__( 'bigger than ','wpestate').' '.$query->value.', ';            
                }                 
            }else{
                print '<strong>'.str_replace('_',' ',$query->key).':</strong> '.$query->value.', ';
            } //end elese query compare
       
        }//end else if custom adv search
        
        
       
    }
    
    print '</div>';
    
    
    ?>
</div>

