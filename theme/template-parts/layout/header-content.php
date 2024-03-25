<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package goldrush
 */




?>

<header id="masthead" class="fixed z-40 top-0 left-0 right-0 <?php if(is_user_logged_in()): ?>top-[32px]<?php endif; ?>)">

	<div id="top-bar" class="flex bg-charcoal text-white/60 text-sm p-2 px-5">
		
		<div class="left mr-auto ml-0 flex flex-row items-center gap-4">
			<a href="#" class="flex flex-row items-center hover:text-white"><span class="material-symbols-outlined mr-1">monetization_on</span> <span class="hidden md:inline">DONATE</span></a>
			<a href="#" class="flex flex-row items-center hover:text-white"><span class="material-symbols-outlined mr-1">person_add</span> <span class="hidden md:inline">BECOME A MEMBER</span></a>	
		</div>

		<div class="right ml-auto mr-0 flex flex-row items-center gap-4">
			<a href="/cart/" class="flex flex-row items-center hover:text-white"><span class="material-symbols-outlined">shopping_cart</span></a>
			
			<a href="/my-account/" class="flex flex-row items-center hover:text-white"><span class="material-symbols-outlined mr-1">person_pin</span> <?php if(is_user_logged_in()): ?>MY ACCOUNT<?php else: ?><span class="hidden md:inline">MEMBER</span>&nbsp; LOGIN<?php endif; ?></a>
		</div>
	</div>

	<div class="bg-primary text-white items-center px-5">
		<div id="navbar" class="flex flex-row items-center justify-between transition-all duration-300 py-3">

			<div class="hidden basis-5/12 lg:block py-5 leading-tight ">
				<a href="/" class="font-mono brand-name uppercase font-normal tracking-wide no-underline text-balance block leading-none"><?php echo get_bloginfo('name'); ?></a>
				<div class="brand-tagline text-balance"><?php the_field('tagline_below_title', 'option'); ?></div>
			</div>
			
			<a href="/" class="basis-1/2 lg:basis-2/12 ml-0 lg:ml-0 lg:mr-auto text-center -mt-8 -mb-12 relative flex items-center justify-center">
			<?php
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
				if ( has_custom_logo() ) {
					echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '" class="max-w-48 navbar-logo aspect-square object-contain ml-0 lg:mx-auto lg:object-center mx-auto transition-all duration-300">';
				} 
			?>
			</a>

			<nav id="site-navigation" class="ml-auto mr-0 lg:basis-5/12 py-5" aria-label="<?php esc_attr_e( 'Main Navigation', 'goldrush' ); ?>">
				
				

				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'items_wrap'     => '<ul id="%1$s" class="%2$s" aria-label="submenu">%3$s</ul>',
					)
				);
				?>
			</nav><!-- #site-navigation -->

		</div>
	</div>
</header><!-- #masthead -->
