<?php
global $property_latitude;
global $property_longitude;
global $google_camera_angle;
global $property_address;
global $property_zip;
global $property_latitude;
global $property_longitude;
global $google_view_check;
global $google_camera_angle;
global $property_area;
global $property_city;
global $property_country;
global $edit_id;
global $property_county;
global $property_state;
global $edit_link_amenities;
?>




<div class="col-md-12">
    <div class="user_dashboard_panel">
    <h4 class="user_dashboard_panel_title"><?php  esc_html_e('Listing Location','wpestate');?></h4>

    <div class="col-md-12" id="profile_message"></div>
       
    
    <div class="col-md-4"> 
        <p>
            <label for="property_address"><?php esc_html_e('Address','wpestate');?></label>
            <input type="text" id="property_address" class="form-control" size="40" name="property_address" value="<?php print $property_address;?>">
       </p>
    </div>

    <div class="col-md-2">
        <p>
            <label for="property_zip"><?php esc_html_e('Zip','wpestate');?></label>
            <input type="text" id="property_zip" class="form-control" size="40" name="property_zip" value="<?php print $property_zip;?>">
        </p>
    </div>
    
    
    
    <div class="col-md-3 ">
        <p>
            <label for="property_state"><?php esc_html_e('State','wpestate');?></label>
            <input type="text" id="property_state" class="form-control" size="40" name="property_state" value="<?php print $property_state?>">
        </p>
    </div>
   
   <div class="col-md-3">
        <p>
            <label for="property_county"><?php esc_html_e('County','wpestate');?></label>
            <input type="text" id="property_county" class="form-control" size="40" name="property_state" value="<?php print $property_county?>">
        </p>
    </div>
        
   
    
    <div class="col-md-6">
        <div id="google_capture"  class="wpb_btn-small wpestate_vc_button  vc_button"><?php esc_html_e('Place Pin with Address','wpestate');?></div>
    </div>

    
    <div class="col-md-12">
        <div id="googleMapsubmit" ></div>   
    </div>
    
    

    
    <div class="col-md-3">
        <p>            
             <label for="property_latitude"><?php esc_html_e('Latitude (for Google Maps Pin Position)','wpestate'); ?></label>
             <input type="text" id="property_latitude" class="form-control" style="margin-right:20px;" size="40" name="property_latitude" value="<?php print $property_latitude; ?>">
        </p>
    </div>
    
    <div class="col-md-3">
        <p>    
            <label for="property_longitude"><?php esc_html_e('Longitude (for Google Maps Pin Position)','wpestate');?></label>
            <input type="text" id="property_longitude" class="form-control" style="margin-right:20px;" size="40" name="property_longitude" value="<?php print $property_longitude;?>">
        </p>
    </div>
    
   

    <div class="col-md-3">
        <p>
            <label for="google_camera_angle"><?php esc_html_e('Street View - Camera Angle (value from 0 to 360)','wpestate');?></label>
            <input type="text" id="google_camera_angle" class="form-control" style="margin-right:0px;" size="5" name="google_camera_angle" value="<?php print $google_camera_angle;?>">
        </p>
    </div>
    
    <input type="hidden" id="property_city_submit" value="<?php echo $property_city?>">
    <input id="property_country" type="hidden" value="<?php echo $property_country;?>">
    
    <div class="col-md-12" style="display: inline-block;">  
        <input type="hidden" name="" id="listing_edit" value="<?php echo $edit_id;?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_locations" value="<?php esc_html_e('Save', 'wpestate') ?>" />
        <a href="<?php echo  $edit_link_amenities;?>" class="next_submit_page"><?php esc_html_e('Go to Amenities settings (*make sure you click save first).','wpestate');?></a>
  
    </div>
</div> 
