<?php
/**
 * Template Name: Full-width-Catering, no sidebar
 * Description: A full-width template with no sidebar
 *
 * @package WordPress
 * @subpackage WP-Skeleton
 */

get_header();
get_template_part( 'menu-t', 'index' ); //the  menu + logo/site title ?>
</diV>
	<div id="primary" class="cater">
		<div id="content">
			<?php the_post(); ?>
			<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'page-header' );
			$url = $thumb['0'];?>
			<div id="page-header" style="background-image:url('<?php echo $url; ?>');">
				<h2 class="entry-title"><?php the_title();?></h2>
				<a href="tel:123-456-7890" class="btn call order">CLICK TO PLACE ORDER</a>
			</div>
			<div class="container">
				<section class="sixteen columns alpha">
					<header class="entry-header">
						<h2 class="entry-title"><?php the_title();?></h2>
					</header>
					<div class="entry-content ">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
				</section>
				<div class="clear"></div>
				<div id="three-images">
					<div class="one-third column">
						<img src="<?php the_field('secondary_image_1')?>">
					</div>
					<div class="one-third column">
						<img src="<?php the_field('secondary_image_2')?>">
					</div>
					<div class="one-third column">
						<img src="<?php the_field('secondary_image_3')?>">
					</div>
				</div>

				<div class="five columns alpha mobile-only">
						<?php if ( is_active_sidebar( 'home-sidebar' ) ) : ?> <?php dynamic_sidebar( 'home-sidebar' ); ?>
			            <?php else : ?><p>You need to drag a widget into your sidebar in the WordPress Admin</p>
				        <?php endif; ?>
					</div>

				<?php edit_post_link( __( 'Edit', 'themename' ), '<span class="edit-link">', '</span>' ); ?>
			</div>


		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(foot2); ?>
