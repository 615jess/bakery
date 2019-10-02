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
					<div class="container">
						<div class="logo">
						    <a href="<?php echo home_url(); //make logo a home link?>"><img src="<?php echo get_template_directory_uri();?>/images/merridees-logo.png" alt="Merridee's Breadbasket - Franklin, TN"> </a>
						</div>
						<div class="mobile-info">
							<span><a href="tel:6157903755">615.790.3755</a></span>
							<span id="reg-hours">OPEN MON - WED 7:00 AM - 5:00PM, THU - SAT 7:00AM - 9:00PM</span>
							<span id="mobile-hours">OPEN MON - WED 7:00 AM - 5:00PM <br> THU - SAT 7:00AM - 9:00PM</span>
							<a id="cd-menu-trigger" href="#0"><span class="cd-menu-icon"></span></a>
						</div>

						<div id="menu-meta">
							<div id="header-meta">
								<div id="hours">We're Open Mon - Wed 7:00 AM - 5:00PM &amp; Thu - Sat 7:00AM - 9:00PM</div>
								<div id="phone">615.790.3755</div>
								<div id="social-head">
									<a href="https://www.facebook.com/merridees" target="_blank"><i class="icon-fbk" id="header_fbk"></i></a>
									<a href="https://twitter.com/merridees" target="_blank"><i class="icon-twt" id="header_twt"></i></a>
									<a href="https://www.instagram.com/merrideesbreadbasket/" target="_blank"><i class="icon-ins" id="header_ins"></i></a>

								</div>
							</div>
						    <!--  the Menu -->
						    <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
						</div>
					</div>
				</div> <!--  End blog header -->

				<div class="container push">