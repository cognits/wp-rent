<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="text" class="form-control" name="s" id="s" placeholder="<?php esc_html_e( 'Search', 'wpestate' ); ?>" />
    <button class="search_form_but"> <i class="fa fa-search"></i> </button>
    <?php
    if (function_exists('icl_translate') ){
        print do_action( 'wpml_add_language_form_field' );
    }
    ?>
</form>
