<div class="header_media">
<?php 
global $page_tax;
global $global_header_type;
global $header_type;

$show_adv_search_status     =   get_option('wp_estate_show_adv_search','');

if(isset($post->ID)){
    $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
}

$global_header_type         =   get_option('wp_estate_header_type','');

if(is_singular('estate_agent')){
    $global_header_type         =   get_option('wp_estate_user_header_type','');
}


if(!is_404()){
    
    if( is_tax()   ){
        $taxonmy    =   get_query_var('taxonomy');
        
        if ( $taxonmy !=='property_action_category' && $taxonmy!='property_category' && esc_html(get_option('wp_estate_use_upload_tax_page',''))==='yes' ){
            $term       =   get_query_var( 'term' );
            $term_data  =   get_term_by('slug', $term, $taxonmy);
            //print_r($term_data);
            $place_id                       =$term_data->term_id;
            $category_attach_id             ='';
            $category_tax                   ='';
            $category_featured_image        ='';
            $category_name                  ='';
            $category_featured_image_url    ='';
            $term_meta                      = get_option( "taxonomy_$place_id");
            $category_tagline               ='';
            $page_tax                       ='';

            if(isset($term_meta['category_featured_image'])){
                $category_featured_image=$term_meta['category_featured_image'];
            }

            if(isset($term_meta['category_attach_id'])){
                $category_attach_id=$term_meta['category_attach_id'];
                $category_featured_image= wp_get_attachment_image_src( $category_attach_id, 'full');
                $category_featured_image_url=$category_featured_image[0];
            }

            if(isset($term_meta['category_tax'])){
                $category_tax=$term_meta['category_tax'];
                $term= get_term( $place_id, $category_tax);
                $category_name=$term->name;
            }

            if(isset($term_meta['category_tagline'])){
                $category_tagline=stripslashes ( $term_meta['category_tagline'] );           
            }

            if(isset($term_meta['page_tax'])){
                $page_tax=$term_meta['page_tax'];           
            }



            print '<div class="listing_main_image" id="listing_main_image_photo"  style="background-image: url('.$category_featured_image_url.')">';
            print '<h1 class="entry-title entry-tax">'.$term_data->name.'</h1>';
            print '<div class="tax_tagline">'.$category_tagline.'</div>';
            print '<div class="img-overlay"></div>';
            print '</div>';
        }else{
            wpestate_show_media_header('global', $global_header_type,'','','');
        }
        
        
        
    }else{ 
        
        if(isset($post->ID)){
            $custom_image               =   esc_html( esc_html(get_post_meta($post->ID, 'page_custom_image', true)) );  
            $rev_slider                 =   esc_html( esc_html(get_post_meta($post->ID, 'rev_slider', true)) ); 
            
        }
        
        
        
        if(  is_category() || is_tag()|| is_search() ){ // dashbaord page
            wpestate_show_media_header('global', $global_header_type,'','','');
        }
        else if (!$header_type==0){  // is not global settings
            if( ! wpestate_check_if_admin_page($post->ID) ){
                wpestate_show_media_header('NOT global', $global_header_type,$header_type,$rev_slider,$custom_image);
            }else{
                wpestate_show_media_header('global', 0,'','','');
            }
        }
        else{    // we don't have particular settings - applt global header
            if( ! wpestate_check_if_admin_page($post->ID) ){
                wpestate_show_media_header('global', $global_header_type,'','','');
            }else{
                 wpestate_show_media_header('global', 0,'','','');
            }
           
        } // end if header
    
    }
    
}// end if 404    

$show_adv_search_general    =   get_option('wp_estate_show_adv_search_general','');
if( isset($post->ID)){
    $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
}
$global_header_type         =   get_option('wp_estate_header_type','');
if(is_singular('estate_agent')){
    $global_header_type         =   get_option('wp_estate_user_header_type','');
}


$show_mobile                =   0;  
$show_adv_search_slider     =   get_option('wp_estate_show_adv_search_slider','');

if($show_adv_search_general ==  'yes' && !is_404() ){
    if( !is_tax() && !is_category() && !is_archive() && !is_tag() && !is_search() ){
        if(  wpestate_check_if_admin_page($post->ID) ){

        }else if($header_type == 1 ){
          //nothing  
        }else if($header_type == 0){ 
            
          
            if($global_header_type==4){
                $show_mobile=1;
                get_template_part('templates/advanced_search');  
            }else if( $global_header_type==0){
               //nonthing 
            }else{
                if($show_adv_search_slider=='yes'){             
                    $show_mobile=1;
                    get_template_part('templates/advanced_search');  
                }
            }

        }else if($header_type == 5){
                $show_mobile=1;
                get_template_part('templates/advanced_search');  
        }else{
            if($show_adv_search_slider=='yes'){
                $show_mobile=1;
                get_template_part('templates/advanced_search');  
            }
        }      
    }else{
        
            $show_mobile=1;  
            if($global_header_type!==0){
                get_template_part('templates/advanced_search');  
            }
    
    } 
}

?>   
</div>

<?php 
if( $show_mobile == 1 ){
    get_template_part('templates/adv_search_mobile');
}
?>