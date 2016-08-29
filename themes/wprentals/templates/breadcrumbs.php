<?php
global $post;

if( isset($post->ID) ){
    $postid         =   $post->ID;
    $custom_image   =   esc_html( get_post_meta($post->ID ,'page_custom_image', true) );
    $rev_slider     =   esc_html( esc_html(get_post_meta($post->ID, 'rev_slider', true)) );
}else{
    $postid         =   '';
    $custom_image   =   '';
    $rev_slider     =   '';
}

$category       =   get_the_term_list($postid, 'property_category', '', ', ', '');

if ( $category == '' ){
    $category=get_the_category_list(', ',$postid);
}
       
     
     
print '<div class="col-md-12 breadcrumb_container">';

if( !is_404() && !is_front_page() ){
    print '<ol class="breadcrumb">
           <li><a href="'.esc_html( home_url() ).'">'.esc_html__( 'Home','wpestate').'</a></li>';
    if (is_archive()) {
        if( is_category() || is_tax() ){
           print '<li class="active">'. single_cat_title('', false).'</li>';
        }  else{
           print '<li class="active">'.esc_html__( 'Archives','wpestate').'</li>';
        }
    }elseif (is_search() ){
        print '<li class="active">'.esc_html__( 'Search Results','wpestate').'</li>';
    }else{
        if( $category!=''){
           print '<li>'.$category.'</li>';
        }
        if(!is_front_page()){
            global $post;
            $parents    = get_post_ancestors( $post->ID );
            if($parents){
                
                $id         = ($parents) ? $parents[count($parents)-1]: $post->ID;
                $parent     = get_page( $id );
                print '<li><a href="'.esc_url(get_permalink($parent)).'">'.get_the_title($parent).'</a></li>';
            }
            
        
           print '<li class="active">'.get_the_title().'</li>';   
        }
    } 
    print '</ol>';
}else{
    //print '<div style="height:30px;"></div>';
}
print '</div>';



  
?>