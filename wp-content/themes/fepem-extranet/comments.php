<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fepem-extranet
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
                    Commentaires
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<div class="comment-list">
			<?php
				wp_list_comments( array(
                                                    'type'=>'comment',
                                                    'style' => 'ul',
                                                    'callback'=>'extranetcp_comment'
                                                    )
                                                );
			?>
		</div><!-- .comment-list -->

		<?php the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) : ?>
			<p class="no-comments">Il n'est plus possible de laisser de commentaire à ce message</p>
		<?php
		endif;

	endif; // Check for have_comments().

	comment_form(array(
                        'title_reply'=>'Déposer un commentaire',
                        'label_submit' => "Ajouter le commentaire",
                        'comment_field' => '<div>' .
                                                '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>' .

                                            '</div>'
                        )
                    );
	?>

</div><!-- #comments -->
