<div id="comments">
<?php
if (post_password_required()) : ?>
    <p class="nopassword"><?php esc_html_e('This post is password protected. Enter the password to view any comments.', 'wpestate'); ?></p>
</div><!-- #comments -->
    <?php
    return;
endif;
?>

<?php // You can start editing here -- including this comment!  ?>
<?php if (have_comments()) : ?>
    <h2>
        <?php
        printf(_n('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'wpestate'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
        ?>
    </h2>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>     
    <?php endif; // check for comment navigation  ?>

    <ul class="commentlist ">
        <?php
        wp_list_comments(array('callback' => 'wpestate_comment'));
        ?>
    </ul>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>
        <nav id="comment-nav-below">
            <h1 class="assistive-text"><?php esc_html_e('Comment navigation', 'wpestate'); ?></h1>
            <div class="nav-previous"><?php previous_comments_link(esc_html__( '&laquo; Older Comments', 'wpestate')); ?></div>
            <div class="nav-next"><?php next_comments_link(esc_html__( 'Newer Comments &raquo;', 'wpestate')); ?></div>
        </nav>
    <?php endif; // check for comment navigation  ?>

    <?php if (!comments_open() && get_comments_number()) : ?>
            <p class="nocomments"><?php esc_html_e('Comments are closed.', 'wpestate'); ?></p>
    <?php endif; ?>

<?php endif; // have_comments()  ?>

<?php
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
$required_text='  ';


$args = array(
        'id_form'           => 'commentform',
        'id_submit'         => 'submit',
        'title_reply'       => esc_html__(  'Leave a Reply','wpestate' ),
        'title_reply_to'    => esc_html__(  'Leave a Reply to %s','wpestate' ),
        'cancel_reply_link' => esc_html__(  'Cancel Reply','wpestate' ),
        'label_submit'      => esc_html__(  'Post Comment','wpestate' ),

        'comment_notes_before' => '<p class="comment-notes">' .
          esc_html__(  'Your email address will not be published.  ','wpestate' ) . ( $req ? $required_text : '' ) .
          '</p>',


        'comment_field' =>  '<p class="comment-form-comment"><label for="comment">'.
          '</label><textarea id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true" placeholder="'. esc_html__(  'Comment', 'wpestate' ) .'">' .
          '</textarea></p>',

    'fields' => apply_filters( 'comment_form_default_fields', array(

        'author' =>
          ' <p class="comment-form-author">' .
                '<input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . '  placeholder="'.esc_html__(  'Name', 'wpestate' ).'"/>
              </p>
           ',

        'email' =>
          '<p class="comment-form-email">' .
          '<input id="email" name="email" type="text" class="form-control"  value="' . esc_attr(  $commenter['comment_author_email'] ) .
          '" size="30"' . $aria_req . ' placeholder="'. esc_html__(  'Email', 'wpestate' ) .'" /></p>',

        'url' =>
          '<p class="comment-form-url">'.
          '<input id="url" name="url" type="text" class="form-control"  value="' . esc_attr( $commenter['comment_author_url'] ) .
          '" size="30" placeholder="'. esc_html__(  'Website', 'wpestate' ) .'"/></p>'
    )
  ),
);

comment_form($args); ?>

</div><!-- #comments -->