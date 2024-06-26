<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package goldrush
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function goldrush_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'goldrush_pingback_header' );

/**
 * Changes comment form default fields.
 *
 * @param array $defaults The default comment form arguments.
 *
 * @return array Returns the modified fields.
 */
function goldrush_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];

	// Adjust height of comment form.
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

	return $defaults;
}
add_filter( 'comment_form_defaults', 'goldrush_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function goldrush_get_the_archive_title() {
	if ( is_category() ) {
		$title = __( 'Category Archives: ', 'goldrush' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'goldrush' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'goldrush' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'goldrush' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'goldrush' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'goldrush' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'goldrush' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'goldrush' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			/* translators: %s: Post type singular name */
			esc_html__( '%s Archives', 'goldrush' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			/* translators: %s: Taxonomy singular name */
			esc_html__( '%s Archives', 'goldrush' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archives:', 'goldrush' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'goldrush_get_the_archive_title' );

/**
 * Determines whether the post thumbnail can be displayed.
 */
function goldrush_can_show_post_thumbnail() {
	return apply_filters( 'goldrush_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns the size for avatars used in the theme.
 */
function goldrush_get_avatar_size() {
	return 60;
}

/**
 * Create the continue reading link
 *
 * @param string $more_string The string shown within the more link.
 */
function goldrush_continue_reading_link( $more_string ) {

	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( ' ... Continue reading %s', 'goldrush' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="sr-only">"', '"</span>', false )
		);

		$more_string = '<a href="' . esc_url( get_permalink() ) . '" class="font-bold text-primary">' . $continue_reading . '</a>';
	}

	return $more_string;
}

// Filter the excerpt more link.
add_filter( 'excerpt_more', 'goldrush_continue_reading_link' );

// Filter the content more link.
add_filter( 'the_content_more_link', 'goldrush_continue_reading_link' );

/**
 * Outputs a comment in the HTML5 format.
 *
 * This function overrides the default WordPress comment output in HTML5
 * format, adding the required class for Tailwind Typography. Based on the
 * `html5_comment()` function from WordPress core.
 *
 * @param WP_Comment $comment Comment to display.
 * @param array      $args    An array of arguments.
 * @param int        $depth   Depth of the current comment.
 */
function goldrush_html5_comment( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

	$commenter          = wp_get_current_commenter();
	$show_pending_links = ! empty( $commenter['comment_author'] );

	if ( $commenter['comment_author_email'] ) {
		$moderation_note = __( 'Your comment is awaiting moderation.', 'goldrush' );
	} else {
		$moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'goldrush' );
	}
	?>
	<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					$comment_author = get_comment_author_link( $comment );

					if ( '0' === $comment->comment_approved && ! $show_pending_links ) {
						$comment_author = get_comment_author( $comment );
					}

					printf(
						/* translators: %s: Comment author link. */
						wp_kses_post( __( '%s <span class="says">says:</span>', 'goldrush' ) ),
						sprintf( '<b class="fn">%s</b>', wp_kses_post( $comment_author ) )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<?php
					printf(
						'<a href="%s"><time datetime="%s">%s</time></a>',
						esc_url( get_comment_link( $comment, $args ) ),
						esc_attr( get_comment_time( 'c' ) ),
						esc_html(
							sprintf(
							/* translators: 1: Comment date, 2: Comment time. */
								__( '%1$s at %2$s', 'goldrush' ),
								get_comment_date( '', $comment ),
								get_comment_time()
							)
						)
					);

					edit_comment_link( __( 'Edit', 'goldrush' ), ' <span class="edit-link">', '</span>' );
					?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
				<em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div <?php goldrush_content_class( 'comment-content' ); ?>>
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			if ( '1' === $comment->comment_approved || $show_pending_links ) {
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					)
				);
			}
			?>
		</article><!-- .comment-body -->
	<?php
}


function goldrush_custom_logo_setup() {
	$defaults = array(
		'height'               => 200,
		'width'                => 150,
		'flex-height'          => true,
		'flex-width'           => true,
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => true, 
	);
	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'goldrush_custom_logo_setup' );

register_block_style(
	'core/paragraph',
		array(
			'name'  => 'material',
			'label' => __( 'Material', 'goldrush' ),
		)
);
register_block_style(
	'core/paragraph',
		array(
			'name'  => 'title-font',
			'label' => __( 'Title font', 'goldrush' ),
		)
);
register_block_style(
	'core/heading',
		array(
			'name'  => 'title-font',
			'label' => __( 'Title font', 'goldrush' ),
		)
);
register_block_style(
	'uagb/slider',
		array(
			'name'  => 'image-slider',
			'label' => __( 'Image slider', 'goldrush' ),
		)
);
register_block_style(
	'core/list',
		array(
			'name'  => 'checkmark',
			'label' => __( 'Checkmark', 'goldrush' ),
		)
);

add_action( 'init', 'goldrush_register_acf_blocks', 5);
function goldrush_register_acf_blocks() {
    register_block_type( __DIR__ . '/blocks/member-counter' );
}



add_filter( 'the_category_list',  function( $categories ){
    // Loop through all the categories that are found
    foreach ( $categories as $index => $category ) {
		
        // if the category object slug equals "uncategorized"
        if ( $category->slug === 'uncategorized' ) :
            // remove it from the list of categories
            unset($categories[$index]);
        endif;
    }
	
    return $categories;
});


function goldrush_add_nav_item($items, $args) {
	if($args->menu_id == 'mega-menu-menu-1') {
		
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		if ( has_custom_logo() ) {
			$items =  '<li class="md:hidden"><a href="/" class="w-20 my-10 text-center">
				<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '" class="max-w-96 navbar-logo mx-auto">

			</a>
		</li>' . $items;
		}
		$items .= '<li class="grid grid-cols-3 md:hidden mt-10 items-center text-center justify-center border-y border-white/30">
				<a href="/donate/" class="text-white text-base basis-1/3 border-r border-white/30 py-3">Donate</a>
				<a href="/my-account/" class="text-white text-base basis-1/3 py-3">Login</a>
				<a href="/cart/" class="text-white text-base basis-1/3 border-l border-white/30 py-3">Cart</a>
		</li>';
	}
	
	return $items;
}
  add_filter('wp_nav_menu_items','goldrush_add_nav_item',10, 2 );



add_action('wp_ajax_goldrush_edit_member_field', 'goldrush_edit_member_field');
add_action('wp_ajax_nopriv_goldrush_edit_member_field', 'goldrush_edit_member_field');

function goldrush_edit_member_field() {
	$nonce_name = 'goldrush_nonce';
    $nonce = $_REQUEST['_wpnonce'];

	if(check_ajax_referer($nonce_name ) && wp_verify_nonce($nonce, $nonce_name)) {

		write_log($_REQUEST);

		$fieldName = $_REQUEST['_fieldName'];

		if($fieldName == 'interests') {
			$interestsField = GFAPI::get_field(1, 11);
			$entry = GFAPI::get_entry($_REQUEST['_gfEntryId']);
			$curInterests = rs_gf_get_checked_boxes( $entry, $_REQUEST['_gfFieldId'] );
			$interests = $curInterests;
			

			if($_REQUEST['_isChecked'] == 'true') {
				
				foreach($interestsField['choices'] as $index => $choice) {
					
					if($_REQUEST['_fieldVal'] == $choice['text']) {
						$interestFieldName = $_REQUEST['_gfFieldId'] . '.' . ($index +1);
						
					}
					//write_log($index . ' -- ' . $choice['text']);
				}
				GFAPI::update_entry_field($_REQUEST['_gfEntryId'], $interestFieldName, $_REQUEST['_fieldVal'], null);
			} else {
				write_log($entry);
				foreach($entry as $index => $value) {
					write_log($value);
					if(stripslashes($value) == stripslashes($_REQUEST['_fieldVal'])) {
						unset($entry[$index]);
						
						GFAPI::update_entry($entry);
						wp_send_json_success('this is the result');
					}
				}

			}
			
			
		} else {
			GFAPI::update_entry_field($_REQUEST['_gfEntryId'], $_REQUEST['_gfFieldId'], $_REQUEST['_fieldVal'], null);
			wp_send_json_success('this is the result');
		}

		
	}
}
  
function rs_gf_get_checked_boxes( $entry, $field_id ) {
	$items = array();
	
	$field_keys = array_keys( $entry );
	
	// Loop through every field of the entry in search of each checkbox belonging to this $field_id
	foreach ( $field_keys as $input_id ) {
		
		// Individual checkbox fields such as "14.1" belongs to field int(14)
		if ( is_numeric( $input_id ) && absint( $input_id ) == $field_id ) {
			$value = rgar( $entry, $input_id );
			
			// If checked, $value will be the value from the checkbox (not the label, though sometimes they are the same)
			// If unchecked, $value will be an empty string
			if ( "" !== $value ) $items[ $input_id ] = $value;
		}
		
	}
	
	return $items;
}

add_filter( 'body_class', function( $classes ) {
	if(get_field('transparent_navbar')) {
		$classes[] = 'transparent-navbar';
	}

	return $classes;
} );