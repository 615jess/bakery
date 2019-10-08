=== Responsive Slider ===
Contributors: Griden, ThemeFurnace
Donate link: 
Tags: responsive slider, slider, responsive, flex slider, flexslider, slides, jquery slider, slideshow, content slider, gallery slider, image slider, Photo Slider, slide, slider plugin, slideshow, wordpress slider, wordpress slideshow
Requires at least: 3.3
Tested up to: 4.5.2
Stable tag: 0.1.9

A responsive slider for integrating into themes via a simple shortcode.

== Description ==

The *Responsive Slider* plugin allows you to create slides that consist of linked (to any url) images and titles. The slider would then take those slides and present them as a jQuery-powered slideshow - at a chosen location within your theme, page, or post. In whatever order you want them.

Check out the Demo to see the plugin in action:
**[See the Demo](http://demo.alienwp.com/responsive-slider/)**

> Upgrade to a paid membership for support, themes & Pro version of plugin:
> **[Upgrade for Support](http://alienwp.com/register/)**
> Includes 20 WordPress Themes, Customer Support for just $59

The main purpose of the *Responsive Slider* is to serve as an effective addition to **responsive WordPress themes**, as it would automatically adjust to its container. This would work out of the box - there is no need for additional CSS or JavaScript tweaks from your theme.

Please note, this plugin is only for use on Self-Hosted WordPress installations, not WordPress.com. See this <a href="https://themefurnace.com/blog/best-wordpress-hosting/">Guide to the Best WordPress Hosting</a> if you need any help choosing one.

[How to Install and Use the plugin?](http://wordpress.org/extend/plugins/responsive-slider/installation/)
Make sure to read the plugin documentation in `docs/readme.html` as well.

= About AlienWP =

Responsive Slider was created by AlienWP, a <a href="https://alienwp.com">WordPress Theme Shop</a> which offers over 20 WordPress themes for you to download including 8 <a href="https://alienwp.com/themes/">free themes</a>. We also provide amazing <a href="https://alienwp.com/wordpress-coupon-codes/">WordPress Deals and Coupons</a> and <a href="https://alienwp.com/collections/">Theme Collections</a> for every possible type of WP site. 

If you like this plugin, check out our other projects:

* <a href="https://alienwp.com/" rel="friend" title="AlienWP">AlienWP</a> - Minimal WordPress Themes
* <a href="https://themefurnace.com/" rel="friend" title="ThemeFurnace">ThemeFurnace</a> - WordPress Themes Club
* <a href="http://kooc.co.uk/" rel="friend" title="Kooc Media">Kooc Media</a> - Kooc Media


== Installation ==

1. Upload the plugin folder `responsive-slider` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the "Plugins" menu in WordPress. A new item **"Slides"** would appear in the admin menu (under "Pages"). 
1. Go to **Slides -> Settings** and configure the slider options.
1. Go to **Slides -> Add New Slide** and create a few slides.
1. Place `<?php echo do_shortcode( '[responsive_slider]' ); ?>` in your template - wherever you want it displayed. Alternatively you can use `[responsive_slider]` into a post or a page - just like any other shortcode.
1. That's it. Your site should now display the slider at the chosen location.

== Frequently Asked Questions ==

= My slider disappeared when I upgraded!? =

Make sure to use `<?php echo do_shortcode( '[responsive_slider]' ); ?>` instead of `<?php do_shortcode( '[responsive_slider]' ); ?>` if you are adding the slider directly to your template file.

= But the slider is not responsive! =

The slider addapts to it's container width. If you are using a theme with non-responsive layout, the slider won't behave 'responsively' as well.

= Would the Responsive Slider work in my theme? =

The plugin has been tested with more than 20 popular WordPress themes. It should work in yours too.

= Does my theme need to be 'responsive' in order to use this plugin? =

No, not at all. You can use it with any theme.

= Can I change the way it looks? =

Sure, you can easily override the slider CSS in your theme. The easiest approach is to use a tool like Firebug to find the snippet you need to override. Then copy it to your theme CSS file (usually `style.css`) and edit it there, using a CSS selector with higher priority.

= Can I create more than one slider? =
No, currently the plugin can maintain only one slider.

== Changelog ==

= 0.1.9 =
* Updated some links, documentation in preparation for new version of plugin along with pro version

= 0.1.8 =
* Fix: omit the anchor tag if the 'URL' field is empty.

= 0.1.7 =
* Important: use `<?php echo do_shortcode( '[responsive_slider]' ); ?>` instead of `<?php do_shortcode( '[responsive_slider]' ); ?>` if you are adding the slider directly to your template file. Ignore this if you are using the `[responsive_slider]` shortcode.
* Fix: the slider output is now correctly returned instead of echoed.
* Added entries to the FAQ section.

= 0.1.6 =
* Updated some credit links in the readme.html file.
* Added a question to the FAQ.

= 0.1.5 =
* Added wp_reset_query() to the output function - responsive_slider().

= 0.1.4 =
* Internationalized some strings.

= 0.1.3 =
* Fixed some translation bugs like loading the textdomain in the frontend.
* Added Finnish translation.

= 0.1.2 =
* The slides are now ordered by the page attribute 'order' in the backend.
* Internationalized some strings.
* Fixed some translation errors.

= 0.1.1 =
* Removed the 'Layout' and 'Stylesheet' Hybrid Core meta boxes from the slide editor.
* Added readme.html in a new 'doc' folder.
* Added French translation.
* Corrected some typos.
* Updated plugin description.

= 0.1 =
* Initial release.