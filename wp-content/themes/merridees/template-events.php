<?php
/**
 * Template Name: Events
 * Description: An events page template
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
			$url = $thumb[0];?>
			<div id="page-header" style="background-image:url('<?php echo $url; ?>');">
			</div>
			<div class="container">
				<section class="sixteen columns alpha">
					<header class="entry-header">
						<h2 class="entry-title"><?php the_title();?></h2>
					</header>
					<div class="entry-content ">
						<?php 
						// WP_Query arguments
						$args = array (
							'post_type'              => 'events',
							'order'                  => 'DESC',
						);
						
						// The Query
						$eventsQuery = new WP_Query( $args );
						
						// The Loop
						if ( $eventsQuery->have_posts() ) {
							while ( $eventsQuery->have_posts() ) {
								$eventsQuery->the_post();
							?>
							<article id="post-<?php the_ID(); ?>" class="event">
					          <div class="title">            
					             <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title('<h3>', '</h3>'); ?></a>  <!--Post titles-->
					          </div>
					          <div class="event-img"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('event-img'); ?></a></div>
					            <?php the_content("Continue reading " . the_title('', '', false)); ?> <!--The Content-->
					        </article>
					        <?
							}
						} else {
							// no posts found
						}
						
						// Restore original Post Data
						wp_reset_postdata(); ?>
					</div><!-- .entry-content -->
				</section>
				<div class="clear"></div>
				
				<div class="five columns alpha mobile-only">
					<?php if ( is_active_sidebar( 'home-sidebar' ) ) : ?> <?php dynamic_sidebar( 'home-sidebar' ); ?>
		            <?php else : ?><p>You need to drag a widget into your sidebar in the WordPress Admin</p>
			        <?php endif; ?>
				</div>
			</div>
	
	
		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_footer(); ?>