<?php
/**
 * @package WordPress
 * @subpackage WP-Skeleton
 */

get_header(); 
get_template_part( 'menu', 'index' ); //the  menu + logo/site title ?>
</diV>
<div id="primary">
	<div id="content">
		<div id="home-slider">
			<?php echo do_shortcode( '[responsive_slider]' ); ?>
		</div>
		<div class="container">
			<?php the_post(); ?>

			<section>
				<header class="entry-header">
					<h1 class="entry-title">Welcome to Merridee's</h1>
				</header><!-- .entry-header -->
				<div class="border">
					<div class="five columns alpha">
						<?php if ( is_active_sidebar( 'home-sidebar' ) ) : ?> <?php dynamic_sidebar( 'home-sidebar' ); ?>
			            <?php else : ?><p>You need to drag a widget into your sidebar in the WordPress Admin</p>
				        <?php endif; ?>
					</div>
	
					<div class="entry-content eleven columns omega left">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
				</div>
			</section>
			<section>
				<header class="entry-header">
					<h1 class="entry-title">Merridee McCray</h1>
				</header>
				<div class="border">
					<span class="border-vert"></span>
					<div class="eleven columns alpha right">
						<?php the_field('home_about');?>
						<button onclick="window.location='/about-merridees/merridee-mccray/'">Click to read more</button>					
					</div>
					<div class="five columns omega">
						<img src="<?php echo get_template_directory_uri();?>/images/merridee.jpg">
						
					</div>
				</div>
			</section>
			<?php edit_post_link( __( 'Edit', 'themename' ), '<span class="edit-link">', '</span>' ); ?>
		</div>


	</div><!-- #content -->
</div><!-- #primary -->
<div class="container">
<?php get_footer(); ?><?php get_footer(); ?>