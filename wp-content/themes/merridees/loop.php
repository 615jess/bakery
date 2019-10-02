<?php 
/**
 * @package WordPress
 * @subpackage WP-Skeleton
 */
?>
</div>
<div id="primary">
	<div id="content">
		<?php the_post(); ?>
			<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID('full') ), 'page-header' );
			$url = $thumb[0];?>
			<div id="page-header" style="background-image:url('<?php echo $url; ?>');">
			</div>
		<div style="clear"></div>
        <div class="container">
			<section class="sixteen columns alpha">
				<header class="entry-header">
					<h2 class="entry-title">Events</h2>
				</header>
				<div class="entry-content ">
					<?php while ( have_posts() ) : the_post(); ?> <!--  the Loop -->
			        <article id="post-<?php the_ID(); ?>" class="event">
			          <div class="title">            
			             <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title('<h3>', '</h3>'); ?></a>  <!--Post titles-->
			          </div>
			          	<div class="event-img"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('event-img'); ?></a></div>
			            <?php the_content("Continue reading " . the_title('', '', false)); ?> <!--The Content-->
			        </article>
			        <?php endwhile; ?><!--  End the Loop -->
				</div>
			</section>
		<div>
    </div>  <!-- End two-thirds column -->
	</div><!-- End Content -->
  
</diV>