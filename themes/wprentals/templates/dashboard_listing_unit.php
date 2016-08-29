<?php
global $edit_link;
global $token;
global $processor_link;
global $paid_submission_status;
global $submission_curency_status;
global $price_submission;
global $floor_link;
global $show_remove_fav;
global $curent_fav;
global $th_separator;
global $user_pack;
$extra= array(
        'class'         =>  'lazyload img-responsive',    
        );

$post_id                    =   get_the_ID();
$preview                    =   wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'wpestate_property_listings',$extra);
$edit_link                  =   esc_url_raw ( add_query_arg( 'listing_edit', $post_id, $edit_link) ) ;
$edit_link                  =   esc_url_raw ( add_query_arg( 'action', 'description', $edit_link) ) ;               
$floor_link                 =   esc_url_raw ( add_query_arg( 'floor_edit', esc_url($post_id), $floor_link) ) ;
$post_status                =   get_post_status($post_id);

$property_address           =   esc_html ( get_post_meta($post_id, 'property_address', true) );
$property_city              =   get_the_term_list($post_id, 'property_city', '', ', ', '') ;
$property_category          =   get_the_term_list($post_id, 'property_category', '', ', ', '');
$property_action_category   =   get_the_term_list($post_id, 'property_action_category', '', ', ', '');
$price_label                =   esc_html ( get_post_meta($post_id, 'property_label', true) );
$price                      =   intval( get_post_meta($post->ID, 'property_price', true) );
$currency                   =   wpestate_curency_submission_pick();
$currency_title             =   esc_html( get_option('wp_estate_currency_symbol', '') );
$where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$status                     =   '';
$link                       =   '';
$pay_status                 =   '';
$is_pay_status              =   '';
$paid_submission_status     =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission           =   floatval( get_option('wp_estate_price_submission','') );
$price_featured_submission  =   floatval( get_option('wp_estate_price_featured_submission','') );

if ($price != 0) {
    
   $price =   number_format($price,0,'.',$th_separator);
 
   if ($where_currency == 'before') {
       $price_title =   $currency_title . ' ' . $price;
       $price       =   $currency . ' ' . $price;
   } else {
       $price_title = $price . ' ' . $currency_title;
       $price       = $price . ' ' . $currency;
     
   }
}else{
    $price='';
    $price_title='';
}

$fav_mes        =   esc_html__( 'add to favorites','wpestate');
if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
    $favorite_class =   'icon-fav-on';   
    $fav_mes        =   esc_html__( 'remove from favorites','wpestate');
    } 
}

if($post_status=='expired'){ 
    $status='<span class="label label-danger">'.esc_html__( 'Expired','wpestate').'</span>';
}else if($post_status=='publish'){ 
    $link= esc_url ( get_permalink() );
    $status='<span class="label label-success">'.esc_html__( 'Published','wpestate').'</span>';
}else if($post_status=='disabled'){ 
    $link= '';
    $status='<span class="label label-disabled">'.esc_html__( 'Disabled','wpestate').'</span>';
}else{
    $link='';
    $status='<span class="label label-info">'.esc_html__( 'Waiting for approval','wpestate').'</span>';
}


if ($paid_submission_status=='per listing'){
    $pay_status    = get_post_meta(get_the_ID(), 'pay_status', true);
    if($pay_status=='paid'){
        $is_pay_status.='<span class="label label-success">'.esc_html__( 'Paid','wpestate').'</span>';
    }
    if($pay_status=='not paid'){
        $is_pay_status.='<span class="label label-info">'.esc_html__( 'Not Paid','wpestate').'</span>';
    }
}
$featured  = intval  ( get_post_meta($post->ID, 'prop_featured', true) );

$free_feat_list_expiration= intval ( get_option('wp_estate_free_feat_list_expiration','') );
$pfx_date = strtotime ( get_the_date("Y-m-d",  $post->ID ) );
$expiration_date=$pfx_date+$free_feat_list_expiration*24*60*60;

?>




<div class="col-md-4 flexdashbaord">
    <div class="dasboard-prop-listing">
    
        <div class="blog_listing_image dashboard_imagine">
            <?php
            if($featured==1){
                print '<span class="label label-primary featured_div">'.esc_html__( 'featured','wpestate').'</span>';
            }
            if (has_post_thumbnail($post_id)){
            ?>
            <!--    <a href="<?php print $link; ?>"><img  src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php  print $preview[0]; ?>" class="b-lazy img-responsive " alt="slider-thumb" /></a> -->
            <a href="<?php print $link; ?>"><img src="<?php  print $preview[0]; ?>" class="b-lazy img-responsive " alt="slider-thumb" /></a>
            
            <?php 
            } else{ 
                $thumb_prop_default =  get_template_directory_uri().'/img/defaultimage_prop.jpg';?>
                <!-- <img data-src="<?php // echo $thumb_prop_default;?>"  src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="b-lazy img-responsive wp-post-image " alt="no thumb" />  -->       
                <img src="<?php echo $thumb_prop_default;?>"   class="b-lazy img-responsive wp-post-image " alt="no thumb" />         
            <?php    
            }
            ?>
        </div>
        
        <div class="user_dashboard_status">
            <?php print $status.$is_pay_status;?>      
        </div>

         <div class="prop-info">
           

            <h4 class="listing_title">
                <a href="<?php print $link; ?>">
                <?php
                $title=get_the_title();
                echo mb_substr( html_entity_decode( $title ), 0, 20); 
                if(strlen($title)>20){
                    echo '...';   
                }
                ?>
                </a> 
            </h4>

            <div class="user_dashboard_listed">
                <?php
                if($price_title!=''){
                    print esc_html__( 'Price','wpestate').': <span class="price_label"> '. $price_title.' '.$price_label.'</span>';
                    if ( $paid_submission_status=='membership' && $user_pack=='') {
                        echo ' | ' ; esc_html_e('expires on ','wpestate');echo date("Y-m-d",$expiration_date);
                    }
                }else{
                    esc_html_e('expires on ','wpestate');echo date("Y-m-d",$expiration_date);
                }
                ?>
            </div>

            <div class="user_dashboard_listed">
                 <?php esc_html_e('Listed in','wpestate');?>  
                 <?php print $property_action_category; ?> 
                 <?php if( $property_action_category!='') {
                         print' '.esc_html__( 'and','wpestate').' ';
                         } 
                       print $property_category;?>                     
            </div>

            <div class="user_dashboard_listed">
                 <?php print esc_html__( 'City','wpestate').': ';?>            
                 <?php print get_the_term_list($post_id, 'property_city', '', ', ', '');?>
                 <?php print ', '.esc_html__( 'Area','wpestate').': '?>
                 <?php print get_the_term_list($post_id, 'property_area', '', ', ', '');?>          
            </div>

            <?php 
            if ( isset($show_remove_fav) && $show_remove_fav==1 ) {
                print '<div class="info-container-payments favorite-wrapper"><span class="icon-fav icon-fav-on-remove" data-postid="'.$post->ID.'"> '.$fav_mes.'</span></div>';
            } else{ 
            ?>
         
             
                <div class="info-container">
                    <a  data-original-title="<?php esc_html_e('Edit property','wpestate');?>"   class="dashboad-tooltip" href="<?php  print $edit_link;?>"><i class="fa fa-pencil editprop"></i></a>
                    <a  data-original-title="<?php esc_html_e('Delete property','wpestate');?>" class="dashboad-tooltip" onclick="return confirm(' <?php echo esc_html__( 'Are you sure you wish to delete ','wpestate').get_the_title(); ?>?')" href="<?php print esc_url ( add_query_arg( 'delete_id', $post_id,esc_url($_SERVER['REQUEST_URI']) ) );?>"><i class="fa fa-times deleteprop"></i></a>  
                    <?php
                    if( $post_status == 'expired' ){ 
                        print'<span data-original-title="'.esc_html__( 'Resend for approval','wpestate').'" class="dashboad-tooltip resend_pending" data-listingid="'.$post_id.'"><i class="fa fa-arrow-up"></i></span>';   
                    }
                    
                    if($paid_submission_status=='membership'){
                        if ( intval(get_post_meta($post_id, 'prop_featured', true))==1){
                             print '<span class="label label-success is_featured">'.esc_html__( 'Property is featured','wpestate').'</span>';       
                        }
                        else{
                            print ' <span  data-original-title="'.esc_html__( 'Set as featured','wpestate').'" class="dashboad-tooltip make_featured" data-postid="'.$post_id.'" ><i class="fa fa-star favprop"></i></span>';
                        }
                    }
                    
                    if($paid_submission_status=='per listing'){
                        $pay_status    = get_post_meta($post_id, 'pay_status', true);
                        $featured= intval(get_post_meta($post_id, 'prop_featured', true));
                        if($pay_status=='paid' && $featured==1){
                            //nothing
                        }else{
                            print '<span class="activate_payments">'.esc_html__( 'Publish or Upgrade','wpestate').'</span>';
                        }
                    }
                    ?>
                    
                    <?php
                    if( $post_status == 'publish' ){ 
                        print ' <span  data-original-title="'.esc_html__( 'Disable Listing','wpestate').'" class="dashboad-tooltip disable_listing" data-postid="'.$post_id.'" ><i class="fa fa-pause"></i></span>';
                    }else if($post_status=='disabled') {
                        print ' <span  data-original-title="'.esc_html__( 'Enable Listing','wpestate').'" class="dashboad-tooltip disable_listing" data-postid="'.$post_id.'" ><i class="fa fa-play"></i></span>';
                  
                    }
                    ?>
                
                </div>
             
                <div class="info-container-payments"> 


                    <?php $pay_status    = get_post_meta($post_id, 'pay_status', true);
                        if( $post_status == 'expired' ){ 
                    //       print'<span data-original-title="'.esc_html__( 'Resend for approval','wpestate').'" class="dashboad-tooltip resend_pending" data-listingid="'.$post_id.'"><i class="fa fa-upload deleteprop"></i></span>';   
                        }else{

                           

                            if($paid_submission_status=='per listing'){
                                $enable_paypal_status   =   esc_html ( get_option('wp_estate_enable_paypal','') );
                                $enable_stripe_status   =   esc_html ( get_option('wp_estate_enable_stripe','') );
                                $enable_direct_pay      =   esc_html ( get_option('wp_estate_enable_direct_pay','') );
                                if($pay_status!='paid' ){
                                    print' 
                                        <div class="listing_submit">
                                        <button type="button"  class="close close_payments" data-dismiss="modal" aria-hidden="true">x</button>
                                        '.esc_html__( 'Submission Fee','wpestate').': <span class="submit-price submit-price-no">'.$price_submission.'</span><span class="submit-price"> '.$currency.'</span></br>
                                        <input type="checkbox" class="extra_featured" name="extra_featured" style="display:block;" value="1" >
                                        '.esc_html__( 'Featured Fee','wpestate').': <span class="submit-price submit-price-featured">'.$price_featured_submission.'</span><span class="submit-price"> '.$currency.'</span> </br>
                                        '.esc_html__( 'Total Fee','wpestate').': <span class="submit-price submit-price-total">'.$price_submission.'</span> <span class="submit-price">'.$currency.'</span>  </br> ';

                                        $stripe_class='';
                                        if($enable_paypal_status==='yes'){
                                            $stripe_class=' stripe_paypal ';
                                            print ' <div class="listing_submit_normal label label-danger" data-listingid="'.$post_id.'">'.esc_html__( 'Pay with Paypal','wpestate').'</div>';
                                        }

                                        if($enable_stripe_status==='yes'){
                                            wpestate_show_stripe_form_per_listing($stripe_class,$post_id,$price_submission,$price_featured_submission);
                                        }

                                        if($enable_direct_pay==='yes'){
                                            print '<div data-listing="'.$post_id.'" class="label label-danger perpack">'.__('Wire Transfer','wpestate').'</div>';
                                        }
                                        
                                    print  '</div>'; 
                                    /* 
                                    '.esc_html__( 'Submission Fee','wpestate').': <span class="submit-price submit-price-no">'.$price_submission.'</span><span class="submit-price"> '.$currency.'</span></br>
                                    '.esc_html__( 'Total Fee','wpestate').': <span class="submit-price submit-price-total">'.$price_submission.'</span> <span class="submit-price">'.$currency.'</span>  </br> 
                                    */
                                }else{
                                    print '<div class="listing_submit">
                                    <button type="button"  class="close close_payments" data-dismiss="modal" aria-hidden="true">x</button>';
                                    
                                   
                                    if ( $featured ==1 ){
                                        print ' <div class="listing_submit_spacer" style="height:118px;"><span class="label label-success  featured_label">'.esc_html__( 'Property is featured','wpestate').'</span>   </div>';  
                                    }else{
                                        print'
                                        <div class="listing_submit_spacer">
                                            '.esc_html__( 'Featured Fee','wpestate').': <span class="submit-price submit-price-featured">'.$price_featured_submission.'</span><span class="submit-price"> '.$currency.'</span> </br>
                                        </div>';
                                         
                                        $stripe_class='';
                                        if($enable_paypal_status==='yes'){
                                            // - '.$price_featured_submission.' '.$currency.'
                                            print'<span class="listing_upgrade label label-danger" data-listingid="'.$post_id.'">'.esc_html__( 'Set as Featured','wpestate').'</span>'; 
                                        }
                                        if($enable_stripe_status==='yes'){
                                            wpestate_show_stripe_form_upgrade($stripe_class,$post_id,$price_submission,$price_featured_submission);
                                        }
                                        if($enable_direct_pay==='yes'){
                                            print '<div data-listing="'.$post_id.'" data-isupgrade="1"  class="label label-danger perpack">'.__('Set as Featured - Wire','wpestate').'</div>';
                                        }
                                    } 
                                    print '</div>';
                                }
                            }

                        }?>

                </div>
            <?php 
            }
            ?>
        </div> 
    
    </div>
 </div>