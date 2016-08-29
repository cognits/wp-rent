<?php
global $global_header_type;
global $header_type;
$adv_submit             =   wpestate_get_adv_search_link();
if(isset($_GET['guest_no'])){
    $guest_list             =   wpestate_get_guest_dropdown('', intval($_GET['guest_no']) );
}else{
    $guest_list             =   wpestate_get_guest_dropdown();
}
$search_position        =   '';

if( $header_type==0 ){ // global
    switch ($global_header_type) {
    case 0:
        $search_position="advpos_none";
        break;
    case 1:
        $search_position="advpos_image";
        break;
    case 2:
        $search_position="advpos_themeslider";
        break;
    case 3:
        $search_position="advpos_revslider";
        break;
     case 4:
        $search_position="advpos_map";
        break;
    }   
    
}else{
    
    switch ($header_type) {
    case 1:
        $search_position="advpos_none";
        break;
    case 2:
        $search_position="advpos_image";
        break;
    case 3:
        $search_position="advpos_themeslider";
        break;
    case 4:
        $search_position="advpos_revslider";
        break;
     case 5:
        $search_position="advpos_map";
        break;
    }   
    
   
}
?>


<?php
$search_type    =   get_option('wp_estate_adv_search_type','');
 
if($search_type == 'oldtype'){ ?>
    <div class="search_wrapper <?php echo $search_position; ?>" id="search_wrapper">
        <?php  
        if ( isset($post->ID) && is_page($post->ID) &&  basename( get_page_template() ) == 'contact_page.php' ) {
            //
        }else {
            include(locate_template('templates/advanced_search_type1-1.php'));
        }               
        ?>
    </div>
<?php
 }else{
 ?>
    <div class="search_wrapper type2 <?php echo $search_position; ?>" id="search_wrapper">
        <?php  
        if ( isset($post->ID) && is_page($post->ID) &&  basename( get_page_template() ) == 'contact_page.php' ) {
            //
        }else {
            include(locate_template('templates/advanced_search_type2.php'));
        }               
        ?>
    </div>

   
<?php
 }
?>