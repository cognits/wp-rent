<?php


///////////////////////////////////////////////////////////////////////////////////////////
// category icons
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_pins') ):


function wpestate_show_pins(){
    $pins       =   array();
    $taxonomy = 'property_action_category';
    $tax_terms = get_terms($taxonomy,'hide_empty=0');

    $taxonomy_cat = 'property_category';
    $categories = get_terms($taxonomy_cat,'hide_empty=0');

    // add only actions
    foreach ($tax_terms as $tax_term) {
        $name                    =  sanitize_key ( wpestate_limit64('wp_estate_'.$tax_term->slug) );
        $limit54                 =  sanitize_key ( wpestate_limit54($tax_term->slug) );
        $pins[$limit54]          =  esc_html( get_option($name) );  
    } 

    // add only categories
    foreach ($categories as $categ) {
        $name                           =   sanitize_key( wpestate_limit64('wp_estate_'.$categ->slug));
        $limit54                        =   sanitize_key(wpestate_limit54($categ->slug));
        $pins[$limit54]                 =   esc_html( get_option($name) );
    }
    
    // add combinations
    foreach ($tax_terms as $tax_term) {
        foreach ($categories as $categ) {
            $limit54            =   sanitize_key ( wpestate_limit27($categ->slug).wpestate_limit27($tax_term->slug) );
            $name               =   'wp_estate_'.$limit54;
            $pins[$limit54]     =   esc_html( get_option($name) ) ;        
        }
    }

  
    $name='wp_estate_idxpin';
    $pins['idxpin']=esc_html( get_option($name) );  

    $name='wp_estate_userpin';
    $pins['userpin']=esc_html( get_option($name) );  
    
    

    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">Pin Management</h1>';  
    print'<form method="post" action="">';

    $taxonomy = 'property_action_category';
    $tax_terms = get_terms($taxonomy,'hide_empty=0');

    $taxonomy_cat = 'property_category';
    $categories = get_terms($taxonomy_cat,'hide_empty=0');

    print'<p class="admin-exp">'.esc_html__( 'Add new Google Maps pins for single actions / single categories.','wpestate').'</p>
        <table class="form-table">';

    foreach ($tax_terms as $tax_term) { 
            $limit54   =  $post_name  =   sanitize_key(wpestate_limit54($tax_term->slug));
            print '    
            <tr valign="top">
                <th scope="row" width="500"><label for="'.$limit54.'">'.esc_html__( 'For action ','wpestate').'<strong>'.$tax_term->name.' </strong> </label></th>
                <td>
                    <input type="text"    class="pin-upload-form" size="36" name="'.$post_name.'" value="'.$pins[$limit54].'" />
                    <input type="button"  class="upload_button button pin-upload" value="'.esc_html__( 'Upload Pin','wpestate').'" />
                </td>
            </tr>';       
    }
     
    
    foreach ($categories as $categ) {  
            $limit54   =   $post_name  =   sanitize_key(wpestate_limit54($categ->slug));
            print '    
            <tr valign="top">
                <th scope="row" width="500"><label for="'.$limit54.'">'.esc_html__( 'for category: ','wpestate').'<strong>'.$categ->name.' </strong>  </label></th>
                <td>
                    <input type="text"    class="pin-upload-form" size="36" name="'.$post_name.'" value="'.$pins[$limit54].'" />
                    <input type="button"  class="upload_button button pin-upload" value="'.esc_html__( 'Upload Pin','wpestate').'"  />
                </td>
            </tr>';       
    }
     print'</table>';
    
    print '<p class="admin-exp">'.esc_html__( 'Add new Google Maps pins for actions & categories combined (example: \'apartments in sales\')','wpestate').'</p>
        <table class="form-table">';  
      
    foreach ($tax_terms as $tax_term) {
    
        foreach ($categories as $categ) {
            $limit54=sanitize_key(wpestate_limit27($categ->slug)).sanitize_key( wpestate_limit27($tax_term->slug) );
            print '    
            <tr valign="top">
                <th scope="row" width="500"><label for="'.$limit54.'">'.esc_html__( 'For action','wpestate').' <strong>'.$tax_term->name.'</strong>, '.esc_html__( 'category','wpestate').': <strong>'.$categ->name.'</strong>  </label></th>
                <td>
                    <input id="'.$limit54.'" type="text" size="36" name="'. $limit54.'" value="'.$pins[$limit54].'" />
                    <input type="button"  class="upload_button button pin-upload" value="'.esc_html__( 'Upload Pin','wpestate').'" />
                </td>
            </tr> 
            ';       
        }
    }


    print ' 
  
 
   <tr valign="top">
        <th scope="row" width="500"><label for="userpinn">'.esc_html__( 'Userpin in geolocation','wpestate').'</label></th>
        <td>
            <input id="userpin" type="text" size="36" name="userpin" value="'.$pins['userpin'].'" />
            <input type="button"  class="upload_button button pin-upload" value="'.esc_html__( 'Upload Pin','wpestate').'" />
        </td>
   </tr> 

    </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary"  value="'.esc_html__( 'Save Changes','wpestate').'" />
    </p>
</form>
</div>

';
}
endif; // end   wpestate_show_pins  

?>