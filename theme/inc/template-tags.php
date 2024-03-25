<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some functionality here could be replaced by core features.
 *
 * @package goldrush
 */

if ( ! function_exists( 'goldrush_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function goldrush_posted_on() {
		$time_string = '<time datetime="%1$s" class="mr-2">%2$s</time>';
		// if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		// 	$time_string = '<time datetime="%1$s">%2$s</time><time datetime="%3$s">%4$s</time>';
		// }

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		printf(
			'%1$s',
			$time_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
endif;

if ( ! function_exists( 'goldrush_posted_by' ) ) :
	/**
	 * Prints HTML with meta information about theme author.
	 */
	function goldrush_posted_by() {
		printf(
		/* translators: 1: posted by label, only visible to screen readers. 2: author link. 3: post author. */
			'<span class="sr-only">%1$s</span><span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span>',
			esc_html__( 'Posted by', 'goldrush' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
endif;

if ( ! function_exists( 'goldrush_comment_count' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */
	function goldrush_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			/* translators: %s: Name of current post. Only visible to screen readers. */
			comments_popup_link( sprintf( __( 'Leave a comment<span class="sr-only"> on %s</span>', 'goldrush' ), get_the_title() ) );
		}
	}
endif;

if ( ! function_exists( 'goldrush_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 * This template tag is used in the entry header.
	 */
	function goldrush_entry_meta() {

		// Hide author, post date, category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			// Posted by.
			//goldrush_posted_by();

			// Posted on.
			goldrush_posted_on();

			/* translators: used between list items, there is a space after the comma. */
			$categories_list = get_the_category_list( __( ', ', 'goldrush' ) );
			
			?>
			<span class="mr-3">
			<?php
			if ( $categories_list ) {
				printf(
				/* translators: 1: posted in label, only visible to screen readers. 2: list of categories. */
					'<span class="sr-only">%1$s</span>%2$s',
					esc_html__( 'Posted in', 'goldrush' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
			?>
			</span>
			<?php

			/* translators: used between list items, there is a space after the comma. */
			// $tags_list = get_the_tag_list( '', __( ', ', 'goldrush' ) );
			// if ( $tags_list ) {
			// 	printf(
			// 	/* translators: 1: tags label, only visible to screen readers. 2: list of tags. */
			// 		'<span class="sr-only">%1$s</span>%2$s',
			// 		esc_html__( 'Tags:', 'goldrush' ),
			// 		$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			// 	);
			// }
		}

		// Comment count.
		if ( ! is_singular() ) {
			goldrush_comment_count();
		}

		// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="sr-only">%s</span>', 'goldrush' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
	}
endif;

if ( ! function_exists( 'goldrush_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function goldrush_entry_footer() {
		?>
		<div class="max-w-content mx-auto px-5">
		<?php
		// Hide author, post date, category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			// Posted by.
			//goldrush_posted_by();

			// Posted on.
			goldrush_posted_on();


			/* translators: used between list items, there is a space after the comma. */
			$categories_list = get_the_category_list( __( ', ', 'goldrush' ) );
			if ( $categories_list ) {
				?>
				<span class="mr-3">
				<?php
				printf(
				/* translators: 1: posted in label, only visible to screen readers. 2: list of categories. */
					'<span class="sr-only">%1$s</span>%2$s',
					esc_html__( 'Posted in', 'goldrush' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				?>
				</span>
				<?php
			}

			/* translators: used between list items, there is a space after the comma. */
			// $tags_list = get_the_tag_list( '', __( ', ', 'goldrush' ) );
			// if ( $tags_list ) {
			// 	printf(
			// 	/* translators: 1: tags label, only visible to screen readers. 2: list of tags. */
			// 		'<span class="sr-only">%1$s</span>%2$s',
			// 		esc_html__( 'Tags:', 'goldrush' ),
			// 		$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			// 	);
			// }
		}

		// Comment count.
		if ( ! is_singular() ) {
			goldrush_comment_count();
		}

		// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="sr-only">%s</span>', 'goldrush' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		?>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'goldrush_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail, wrapping the post thumbnail in an
	 * anchor element except when viewing a single post.
	 */
	function goldrush_post_thumbnail() {

		

		if ( ! goldrush_can_show_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<figure class="post-feature-image max-w-content mx-auto px-5">
				<?php echo $postId; the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->

			<?php
		else :
			?>

			<figure class="post-feature-image max-w-content mx-auto px-5">
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail(); ?>
				</a>
			</figure>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'goldrush_comment_avatar' ) ) :
	/**
	 * Returns the HTML markup to generate a user avatar.
	 *
	 * @param mixed $id_or_email The Gravatar to retrieve. Accepts a user_id, gravatar md5 hash,
	 *                           user email, WP_User object, WP_Post object, or WP_Comment object.
	 */
	function goldrush_get_user_avatar_markup( $id_or_email = null ) {

		if ( ! isset( $id_or_email ) ) {
			$id_or_email = get_current_user_id();
		}

		return sprintf( '<div class="vcard">%s</div>', get_avatar( $id_or_email, goldrush_get_avatar_size() ) );
	}
endif;

if ( ! function_exists( 'goldrush_discussion_avatars_list' ) ) :
	/**
	 * Displays a list of avatars involved in a discussion for a given post.
	 *
	 * @param array $comment_authors Comment authors to list as avatars.
	 */
	function goldrush_discussion_avatars_list( $comment_authors ) {
		if ( empty( $comment_authors ) ) {
			return;
		}
		echo '<ol>', "\n";
		foreach ( $comment_authors as $id_or_email ) {
			printf(
				"<li>%s</li>\n",
				goldrush_get_user_avatar_markup( $id_or_email ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		echo '</ol>', "\n";
	}
endif;

if ( ! function_exists( 'goldrush_the_posts_navigation' ) ) :
	/**
	 * Wraps `the_posts_pagination` for use throughout the theme.
	 */
	function goldrush_the_posts_navigation() {
		?>
		<div class="max-w-content mx-auto my-12">
		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 3,
				'prev_text' => __( 'Newer posts', 'goldrush' ),
				'next_text' => __( 'Older posts', 'goldrush' ),
			)
		);
		?>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'goldrush_content_class' ) ) :
	/**
	 * Displays the class names for the post content wrapper.
	 *
	 * This allows us to add Tailwind Typography’s modifier classes throughout
	 * the theme without repeating them in multiple files. (They can be edited
	 * at the top of the `../functions.php` file via the
	 * GOLDRUSH_TYPOGRAPHY_CLASSES constant.)
	 *
	 * Based on WordPress core’s `body_class` and `get_body_class` functions.
	 *
	 * @param array $classes Space-separated string or array of class names to
	 *                     add to the class list.
	 */
	function goldrush_content_class( $classes = '' ) {
		$all_classes = array( $classes, GOLDRUSH_TYPOGRAPHY_CLASSES );

		foreach ( $all_classes as &$class_groups ) {
			if ( ! empty( $class_groups ) ) {
				if ( ! is_array( $class_groups ) ) {
					$class_groups = preg_split( '#\s+#', $class_groups );
				}
			} else {
				// Ensure that we always coerce class to being an array.
				$class_groups = array();
			}
		}

		$combined_classes = array_merge( $all_classes[0], $all_classes[1] );
		$combined_classes = array_map( 'esc_attr', $combined_classes );

		// Separates class names with a single space, preparing them for the
		// post content wrapper.
		echo 'class="' . esc_attr( implode( ' ', $combined_classes ) ) . '"';
	}
endif;
