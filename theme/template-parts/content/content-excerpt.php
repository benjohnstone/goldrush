<?php
/**
 * Template part for displaying post archives and search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package goldrush
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('not-prose'); ?>>

	<?php goldrush_post_thumbnail(); ?>

	<header class="entry-header">
		<?php
		if ( is_sticky() && is_home() && ! is_paged() ) {
			printf( '%s', esc_html_x( 'Featured', 'post', 'goldrush' ) );
		}
		the_title( sprintf( '<h2 class="entry-title text-lg mb-3"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		?>

		<div class="entry-meta uppercase text-sm text-gray-300 mb-5">
			<?php goldrush_entry_meta(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	

	<div <?php goldrush_content_class( 'entry-content text-base' ); ?>>
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->



</article><!-- #post-${ID} -->
