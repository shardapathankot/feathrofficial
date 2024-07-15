<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to bootstrapwp_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage villenoir
 */
// Return early no password has been entered for protected posts.
if (post_password_required())
  return;

// If is page and general page comments are disabled return
if (is_page() && !_get_field('gg_page_comments', 'option', true) )
  return;

// If is post and general page comments are disabled return
if (is_single() && !_get_field('gg_post_comments', 'option', true) )
  return;
?>
<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>

        <h2 class="comments-title">
          <?php
            printf( _nx( 'One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'villenoir' ),
              number_format_i18n( get_comments_number() ), get_the_title() );
          ?>
        </h2>

        <ul class="media-list">
            <?php wp_list_comments(array('callback' => 'villenoir_comment')); ?>
        </ul><!-- /.commentlist -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav id="comment-nav-below" class="navigation" role="navigation">
                <div class="nav-previous">
                    <?php previous_comments_link( esc_html_e('&larr; Older Comments', 'villenoir')); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link(esc_html_e('Newer Comments &rarr;', 'villenoir')); ?>
                </div>
            </nav>
        <?php endif; // check for comment navigation ?>

        <?php elseif (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
            <p class="nocomments"><?php esc_html_e('Comments are closed.', 'villenoir'); ?></p>
    <?php endif; ?>

<?php $comment_args = array(
   'comment_field' =>  '<div class="form-group"><p class="comment-form-comment">
   <label class="sr-only" for="comment">' . _x( 'Comment', 'noun', 'villenoir' ) . '</label>
   <textarea id="comment" placeholder="Comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p></div>',
  'comment_notes_after' => '',  
  'fields' => apply_filters( 'comment_form_default_fields', array(

    'author' =>
      '<div class="form-inline row"><div class="form-group col-xs-6 col-sm-4 col-md-4"><p class="comment-form-author">' .
      '<label class="sr-only" for="author">' . esc_html__( 'Name', 'villenoir' ) . '</label>
      <input placeholder="Name*" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
      '" /></p></div>',

    'email' =>
      '<div class="form-group col-xs-6 col-sm-4 col-md-4"><p class="comment-form-email"><label class="sr-only" for="email">' . esc_html__( 'Email', 'villenoir' ) . '</label>
      <input id="email" placeholder="Email*" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" /></p></div>',

    'url' =>
      '<div class="form-group col-xs-6 col-sm-4 col-md-4"><p class="comment-form-url"><label class="sr-only" for="url">' .
      esc_html__( 'Website', 'villenoir' ) . '</label>' .
      '<input id="url" name="url" placeholder="Website" type="text" value="' . esc_url( $commenter['comment_author_url'] ) .
      '" /></p></div></div>'
    )
  ),
);
comment_form($comment_args); ?>
</div><!-- #comments .comments-area -->