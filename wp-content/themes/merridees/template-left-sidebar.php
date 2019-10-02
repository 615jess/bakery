<?php
/** 
 * Template Name: Left Sidebar
 * Description: A page template with a left sidebar area
 *
 * @package WordPress
 * @subpackage WP-Skeleton
 */

get_header(); 
get_template_part( 'menu', 'index' ); //the  menu + logo/site title ?>
</diV>
<div id="primary">
	<div id="content">
		<?php the_post(); ?>
		<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'page-header' );
		$url = $thumb['0'];?>
		 //<div id="page-header" style="background-image:url('<?php echo $url; ?>');">
		<h1><?php the_title();?></h1>
		</div>
		<div class="container">
			<section>
				<header class="entry-header">
					<?php if( get_field( "second_title" ) ): ?> <h2 class="entry-title"><?php the_field('second_title')?></h2>
					<?php else : ?> <h2 class="entry-title"><?php the_title() ?></h2>
					<?php endif ?>
				</header><!-- .entry-header -->
				<div class="border">
					<span class="border-vert"></span>
	
					<div class="entry-content eleven columns omega left">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
				</div>
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
			<center><div class="five columns alpha" style="margin:auto;">
						<?php if ( is_active_sidebar( 'left-sidebar' ) ) : ?> <?php dynamic_sidebar( 'left-sidebar' ); ?>
			            <?php else : ?><p>You need to drag a widget into your sidebar in the WordPress Admin</p>
				        <?php endif; ?>
					</div></center>
			<?php edit_post_link( __( 'Edit', 'themename' ), '<span class="edit-link">', '</span>' ); ?>
		</div>


	</div><!-- #content -->
</div><!-- #primary -->
<div class="container">
<?php get_footer(); ?>