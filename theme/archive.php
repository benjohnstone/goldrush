<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package goldrush
 */

get_header();
?>

	<section id="primary">
		<main id="main">

		<?php if ( have_posts() ) : ?>
			
			<header class="page-header text-center mb-10">
				<?php the_archive_title( '<h1 class="page-title  font-mono font-normal uppercase">', '</h1>' ); ?>
			</header><!-- .page-header -->

			<div class="max-w-wide gap-0 gap-y-14 mx-auto grid md:grid-cols-2 lg:grid-cols-3">
			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/content', 'excerpt' );

				// End the loop.
			endwhile;

			?>
			</div>
			<?php
			// Previous/next page navigation.
			goldrush_the_posts_navigation();

		else :

			// If no content, include the "No posts found" template.
			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
