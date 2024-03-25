<?php 

$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => '3',
);
$the_query = new WP_Query( $args );


if ( $the_query->have_posts() ) :
?>
<div id="recent-posts" class="max-w-content mx-auto mt-20">
    <div class="mx-5 border-t-2 border-concrete-500">
        <h2 class="text-2xl font-mono text-center my-8">RECENT POSTS</h2>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-0 gap-y-14 ">
    <?php while ( $the_query->have_posts() ) {
		$the_query->the_post(); ?>
         <div>
            <?php 
            get_template_part( 'template-parts/content/content', 'excerpt' ); ?>
        </div>

         <?php
	} ?>
    </div>
</div>
<?php endif; ?>