<?php
global $unit;
global $edit_id;
global $property_size;
global $property_lot_size;
global $property_rooms;
global $property_bedrooms;
global $property_bathrooms;
global $custom_fields;    
global $custom_fields_array;
global $edit_link_location;

$measure_sys            = esc_html ( get_option('wp_estate_measure_sys','') ); 
?> 



<div class="col-md-12">
    <div class="user_dashboard_panel">
    <h4 class="user_dashboard_panel_title"><?php esc_html_e('Listing Details','wpestate');?></h4>

    <div class="col-md-12" id="profile_message"></div>
              
    <div class="col-md-4">
        <p>
            <label for="property_size"> <?php esc_html_e('Size in','wpestate');print ' '.$measure_sys.'<sup>2</sup>';?></label>
            <input type="text" id="property_size" size="40" class="form-control"  name="property_size" value="<?php print $property_size;?>">
        </p>
    </div>
    
    <div class="col-md-4">
        <p>
            <label for="property_rooms"><?php esc_html_e('Rooms','wpestate');?></label>
            <input type="text" id="property_rooms" size="40" class="form-control"  name="property_rooms" value="<?php print $property_rooms;?>">
        </p>
    </div>
    
    <div class="col-md-4">
        <p>
            <label for="property_bedrooms "><?php esc_html_e('Bedrooms','wpestate');?></label>
            <input type="text" id="property_bedrooms" size="40" class="form-control"  name="property_bedrooms" value="<?php print $property_bedrooms;?>">
        </p>
    </div>
    
    <div class="col-md-4">
        <p>
            <label for="property_bedrooms"><?php esc_html_e('Bathrooms','wpestate');?></label>
            <input type="text" id="property_bathrooms" size="40" class="form-control"  name="property_bathrooms" value="<?php print $property_bathrooms;?>">
        </p>
    </div>
    
  
    <!-- Add custom details -->

    <?php
     
    $i=0;
    if( !empty($custom_fields)){  
        while($i< count($custom_fields) ){
            $name  =   $custom_fields[$i][0];
            $label =   $custom_fields[$i][1];
            $type  =   $custom_fields[$i][2];
            $slug  =   str_replace(' ','_',$name);

            $slug         =   wpestate_limit45(sanitize_title( $name ));
            $slug         =   sanitize_key($slug);

            $i++;

            if (function_exists('icl_translate') ){
                $label     =   icl_translate('wpestate','wp_estate_property_custom_front_'.$label, $label ) ;
            }   

            print '<div class="col-md-4"><p><label for="'.$slug.'">'.stripslashes( $label ).'</label>';

            if ($type=='long text'){
                 print '<textarea type="text" class="form-control"  id="'.$slug.'"  size="0" name="'.$slug.'" rows="3" cols="42">'.$custom_fields_array[$slug].'</textarea>';
            }else{
                 print '<input type="text" class="form-control"  id="'.$slug.'" size="40" name="'.$slug.'" value="'.$custom_fields_array[$slug].'">';
            }
            print '</p>  </div>';

            if ($type=='date'){
                print '<script type="text/javascript">
                    //<![CDATA[
                    jQuery(document).ready(function(){
                        '.wpestate_date_picker_translation($slug).'
                    });
                    //]]>
                    </script>';
            }
        }
    }

    ?>
  
    
    <div class="col-md-12" style="display: inline-block;">  
        <input type="hidden" name="" id="listing_edit" value="<?php echo $edit_id;?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_details" value="<?php esc_html_e('Save', 'wpestate') ?>" />
        <a href="<?php echo  $edit_link_location;?>" class="next_submit_page"><?php esc_html_e('Go to Location settings (*make sure you click save first).','wpestate');?></a>
  
    </div>
</div>  
