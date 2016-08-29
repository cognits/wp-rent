<?php
global $favorite_class;
global $favorite_text;
?>
<div class="fav_wrapper">
    <div id="add_favorites" class=" <?php print $favorite_class;?>" data-postid="<?php the_ID();?>">
        <?php echo $favorite_text;?>
    </div>                 
</div>              