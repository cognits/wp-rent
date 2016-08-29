<!-- begin sidebar -->
<?php  
if( ('no sidebar' != $options['sidebar_class']) && ('' != $options['sidebar_class'] ) && ('none' != $options['sidebar_class']) ){
?>    
    <ul class="xoxo listingsidebar">
        <?php generated_dynamic_sidebar( $options['sidebar_name'] ); ?>
    </ul>
<?php
}
?>
<!-- end sidebar -->