<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package goldrush
 */
$facebook = get_field('facebook', 'option');
$instagram = get_field('instagram', 'option');
$twitter = get_field('twitter', 'option');
?>

<footer id="colophon" class="pt-20">

	<div class="w-auto mx-auto relative h-40">
		
		<div class="max-w-[850px] mx-auto relative  overflow-hidden translate-y-16 h-20 md:translate-y-0 md:h-40">
			<img id="mtns1" src="<?php echo get_template_directory_uri(); ?>/images/mountains1.svg?Asdf" class="absolute md:w-[80%] left-[10%] top-0">
			<img id="mtns2" src="<?php echo get_template_directory_uri(); ?>/images/mountains2.svg?ASdf" class="absolute w-full top-0">
		</div>
		<div class="grass absolute left-0 right-0 bottom-0 top-auto z-10 h-20 w-full bg-repeat-x" style="background-image: url('<?php echo get_template_directory_uri(); ?>/images/grass.svg');"></div>
		<div id="footer-logo" class="absolute z-10 left-0 right-0 top-0 text-center">
			<a href="/" class="top-0 block w-40 mx-auto">
			<?php
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
			if ( has_custom_logo() ) {
				echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '" class="w-full max-w-[180px] aspect-square object-contain object-center mx-auto transition-all duration-300">';
			} 
			?>
			</a>
		</div>
	</div>



	<div class="bg-thunder prose overflow-hidden  pb-20">
		<div id="footer-content" class="p-5 max-w-wide mx-auto">
			<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
				<aside role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'goldrush' ); ?>">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
				</aside>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
				<nav aria-label="<?php esc_attr_e( 'Footer Menu', 'goldrush' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-2',
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="text-sm text-center text-tertiary uppercase mx-4">
				

				<hr class="border-tertiary/50 max-w-content mx-auto w-full"/>

				&copy;<?php echo date('Y');
				$goldrush_blog_info = get_bloginfo( 'name' );
				if ( ! empty( $goldrush_blog_info ) ) :
					?>
					<?php bloginfo( 'name' ); ?>, Quesnel, British Columbia
					
					<?php
				endif;

				
				?> <a href="/privacy/" class="no-underline text-tertiary mx-4">Privacy Policy</a>
			</div>
		</div>
	</div>
</footer><!-- #colophon -->
