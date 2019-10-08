<?php
/**
 * @package WordPress
 * @subpackage WP-Skeleton
 */
?>

<div class="site-wrapper">
		<div class="scroller"><!-- this is for emulating position fixed of the nav -->
            <div class="scroller-inner">
				<div class="header">
					<div class="container flexhdr">
						<div class="logo">
						    <a href="<?php echo home_url(); //make logo a home link?>"><img src="<?php echo get_template_directory_uri();?>/images/merridees-logo.png" alt="Merridee's Breadbasket - Franklin, TN"> </a>
						</div>
						<div class="mobile-info">
							<span><a href="tel:6157903755">615.790.3755</a></span>
							<span id="reg-hours">Mon - Wed 7 to 5 <br/>Thu - Sat 7 to 9</span>
							<span id="mobile-hours">Mon - Wed 7 to 5 <br/>Thu - Sat 7 to 9</span>
							<a id="cd-menu-trigger" href="#0"><span class="cd-menu-icon"></span></a>
						</div>

						<div id="tmenu">
							<div id="header-meta2"class="flxrow">
								<div id="hors" class="menuinfo"><div class="ctr">Mon - Wed 7 to 5 <br/>Thu - Sat 7 to 9</div></div>
								<div id="addrs" class="menuinfo"><div class="ctr">110 Fourth Ave. South<br/>Franklin, TN 37064</div></div>
								<div id="phne" class="menuinfo"><div class="ctr">615.790.3755</div></div>
							</div>
						   <div class="flxrow"> <!--  the Menu -->
						    <?php wp_nav_menu( array( 'theme_location' => 'transit' ) ); ?>
							</div>
						</div>
						<div id="orderonline" style="display:inline-block;"><a class="button orderbtn" href="https://merridees.alohaorderonline.com/">ORDER ONLINE!</a></div>
					</div>

				</div>

					<!--  End blog header -->

				<div class="container push">
