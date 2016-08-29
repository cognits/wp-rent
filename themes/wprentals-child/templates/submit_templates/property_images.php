<?php
global $action;
global $edit_id;
global $embed_video_id;
global $option_video;
global $edit_link_details;
$images='';
$thumbid='';
$attachid='';


$arguments = array(
      'numberposts'     => -1,
      'post_type'       => 'attachment',
      'post_parent'     => $edit_id,
      'post_status'     => null,
      'exclude'         => get_post_thumbnail_id(),
      'orderby'         => 'menu_order',
      'order'           => 'ASC'
  );
$post_attachments = get_posts($arguments);
$post_thumbnail_id = $thumbid = get_post_thumbnail_id( $edit_id );

   
    foreach ($post_attachments as $attachment) {
        $preview =  wp_get_attachment_image_src($attachment->ID, 'wpestate_property_listings');    
        
        if($preview[0]!=''){
            $images .=  '<div class="uploaded_images" data-imageid="'.$attachment->ID.'"><img src="'.$preview[0].'" alt="thumb" /><i class="fa fa-trash-o"></i>';
            if($post_thumbnail_id == $attachment->ID){
                $images .='<i class="fa thumber fa-star"></i>';
            }
        }else{
            $images .=  '<div class="uploaded_images" data-imageid="'.$attachment->ID.'"><img src="'.get_template_directory_uri().'/img/pdf.png" alt="thumb" /><i class="fa fa-trash-o"></i>';
            if($post_thumbnail_id == $attachment->ID){
                $images .='<i class="fa thumber fa-star"></i>';
            }
        }
        
        
        $images .='</div>';
        $attachid.= ','.$attachment->ID;
    }


?>


<div class="col-md-12" id="new_post2">
    <div class="user_dashboard_panel">
    <h4 class="user_dashboard_panel_title"><?php  esc_html_e('Listing Media','wpestate');?></h4>
  


    <div class="col-md-12" id="profile_message"></div>
     
    <div class="col-md-12">
        <div id="upload-container">                 
            <div id="aaiu-upload-container">                 
                <div id="aaiu-upload-imagelist">
                    <ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
                </div>

                <div id="imagelist">
                <?php 
                    if($images!=''){
                        print $images;
                    }
                ?>  
                </div>

                <div id="aaiu-uploader"  class=" wpb_btn-small wpestate_vc_button  vc_button"><?php esc_html_e('Select Media','wpestate');?></div>
                <input type="hidden" name="attachid" id="attachid" value="<?php echo $attachid;?>">
                <input type="hidden" name="attachthumb" id="attachthumb" value="<?php echo $thumbid;?>">
                <p class="full_form full_form_image">
                    <?php esc_html_e('*Double Click on the image to select featured. ','wpestate');?></br>
                     <?php esc_html_e('**Change images order with Drag & Drop. ','wpestate');?>
                </p>
            </div>  
        </div>
    </div>

    <div class="col-md-4">
        <p>
            <label for="embed_video_type"><?php esc_html_e('Video from','wpestate');?></label>
            <select id="embed_video_type" name="embed_video_type" class="select-submit2">
                <?php print $option_video;?>
            </select>
        </p>
    </div>
    
    
    <div class="col-md-4">
        <p>     
           <label for="embed_video_id"><?php esc_html_e('Video id: ','wpestate');?></label>
           <input type="text" id="embed_video_id" class="form-control"  name="embed_video_id" size="40" value="<?php print $embed_video_id;?>">
        </p>
    </div>
    
    <div class="col-md-12" style="display: inline-block;"> 
        <input type="hidden" name="" id="listing_edit" value="<?php echo $edit_id;?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_image" value="<?php esc_html_e('Save', 'wpestate') ?>" />
        <a href="<?php echo  $edit_link_details;?>" class="next_submit_page"><?php esc_html_e('Go to Details settings (*make sure you click save first).','wpestate');?></a>
  
    </div>
   
</div>  