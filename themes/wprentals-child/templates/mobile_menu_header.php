<?php 
global $is_top_bar_class;
$logo=get_option('wp_estate_logo_image','');  
?> 
<div class="mobile_header <?php echo $is_top_bar_class;?>">
    <div class="mobile-trigger"><i class=" fa fa-bars"></i></div>
    <div class="mobile-logo">
        <a href="<?php echo home_url('','login');?>">
        <?php
            $mobilelogo              =   esc_html( get_option('wp_estate_mobile_logo_image','') );
            if ( $mobilelogo!='' ){
               print '<img src="'.$mobilelogo.'" class="img-responsive retina_ready" alt="logo"/>';	
            } else {
               print '<img class="img-responsive retina_ready" src="'. get_template_directory_uri().'/img/logo.png" alt="logo"/>';
            }
        ?>
        </a>
    </div>   
    <div class="mobile-trigger-user"><i class=" fa fa-cogs"></i></div>
</div>
