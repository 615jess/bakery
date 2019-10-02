<?php
/*
Plugin Name: EmbedStories by EmbedSocial
Plugin URI: http://www.embedsocial.com
Description: Embed Instagram stories on your website
Author: EmbedSocial
Author URI: http://www.embedsocial.com
Version: 0.7.3
 */

defined( 'ABSPATH' ) or die;
class EmbedStoriesPlugin {

    private $url = "https://embedsocial.com/facebook_album/";
    private $urlEmbedScripts = "https://embedsocial.com/embedscript/";

    private function send_error_msg() {
        $current_user = wp_get_current_user();
        if (user_can($current_user, 'administrator')) {
            $msg = "An error occured(E1135). Please contact us.<br/>";
        } else {
            $msg = "";
        } 

        echo $msg;
        wp_die();
    }

    public function __construct() {}

    public function hook_embed_gallery_js() {
        wp_register_script('EmbedSocialGalleryScript', $this->urlEmbedScripts.'biw.js');
        wp_enqueue_script('EmbedSocialGalleryScript', $this->urlEmbedScripts.'biw.js');
    }

    public function hook_embed_instagram_js() {
        wp_register_script('EmbedSocialInstagramScript', $this->urlEmbedScripts.'in.js');
        wp_enqueue_script('EmbedSocialinstagramScript', $this->urlEmbedScripts.'in.js');
    }

    public function hook_embed_twitter_js() {
        wp_register_script('EmbedSocialTwitterScript', $this->urlEmbedScripts.'ti.js');
        wp_enqueue_script('EmbedSocialtwitterScript', $this->urlEmbedScripts.'ti.js');
    }

    public function hook_embed_album_js() {
        wp_register_script('EmbedSocialScript', $this->urlEmbedScripts.'eiw.js');
        wp_enqueue_script('EmbedSocialScript', $this->urlEmbedScripts.'eiw.js');
    }

    public function hook_embed_google_album_js() {
        wp_register_script('EmbedSocialGoogleScript', $this->urlEmbedScripts.'gi.js');
        wp_enqueue_script('EmbedSocialGoogleScript', $this->urlEmbedScripts.'gi.js');
    }

    public function hook_embed_socialfeed_js() {
        wp_register_script('EmbedSocialSocialFeedScript', $this->urlEmbedScripts.'sf.js');
        wp_enqueue_script('EmbedSocialSocialFeedScript', $this->urlEmbedScripts.'sf.js');
    }

    public function hook_embed_reviews_js() {
        wp_register_script('EmbedSocialReviewsScript', $this->urlEmbedScripts.'ri.js');
        wp_enqueue_script('EmbedSocialReviewsScript', $this->urlEmbedScripts.'ri.js');
    }

    public function hook_embed_google_reviews_js() {
        wp_register_script('EmbedSocialGoogleReviewsScript', $this->urlEmbedScripts.'gri.js');
        wp_enqueue_script('EmbedSocialGoogleReviewsScript', $this->urlEmbedScripts.'gri.js');
    }

    public function hook_embed_custom_reviews_js() {
        wp_register_script('EmbedSocialCustomReviewsScript', $this->urlEmbedScripts.'cri.js');
        wp_enqueue_script('EmbedSocialCustomReviewsScript', $this->urlEmbedScripts.'cri.js');
    }

    public function hook_embed_story_js() {
        wp_register_script('EmbedSocialStoriesScript', $this->urlEmbedScripts.'st.js');
        wp_enqueue_script('EmbedSocialStoriesScript', $this->urlEmbedScripts.'st.js');
    }

    public function hook_embed_story_popup_js() {
        wp_register_script('EmbedSocialStoriesPopupScript', $this->urlEmbedScripts.'stp.js');
        wp_enqueue_script('EmbedSocialStoriesPopupScript', $this->urlEmbedScripts.'stp.js');
    }

    public function hook_embed_story_gallery_js() {
        wp_register_script('EmbedSocialStoryGalleryScript', $this->urlEmbedScripts.'stg.js');
        wp_enqueue_script('EmbedSocialStoryGalleryScript', $this->urlEmbedScripts.'stg.js');
    }

    public function embedsocial_fb_album_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this,"hook_embed_album_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']); 
            $out .= "<div class='embedsocial-album' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_fb_gallery_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_gallery_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-gallery' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }   

    public function embedsocial_instagram_album_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_instagram_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-instagram' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_twitter_album_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_twitter_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-twitter' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_google_album_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_google_album_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-google-place' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_feed_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_socialfeed_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-socialfeed' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_reviews_album_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_reviews_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-reviews' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_google_reviews_album_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_google_reviews_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-google-reviews' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }
    
    public function embedsocial_custom_reviews_album_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_custom_reviews_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-custom-reviews' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_stories_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_story_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-stories' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_stories_popup_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_story_popup_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-stories-popup' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }

    public function embedsocial_story_gallery_shortcode( $atts ) 
    {
        add_action('wp_footer', array($this, "hook_embed_story_gallery_js"));

        $shortcodeId = (shortcode_atts(array(
            'id' => ''
        ), $atts));

        $out = "";
        if ($shortcodeId['id']) {
            $shortcodeId['id'] = sanitize_text_field($shortcodeId['id']);
            $out .= "<div class='embedsocial-story-gallery' data-ref='{$shortcodeId['id']}'></div>";
        }
        return $out;
    }
}

$plugin = new EmbedStoriesPlugin();

add_shortcode('embedsocial_album', array($plugin, 'embedsocial_fb_album_shortcode'));
add_shortcode('embedsocial_gallery', array($plugin, 'embedsocial_fb_gallery_shortcode'));
add_shortcode('embedsocial_instagram', array($plugin, 'embedsocial_instagram_album_shortcode'));
add_shortcode('embedsocial_twitter', array($plugin, 'embedsocial_twitter_album_shortcode'));   
add_shortcode('embedsocial_google_album', array($plugin, 'embedsocial_google_album_shortcode'));  
add_shortcode('embedsocial_feed', array($plugin, 'embedsocial_feed_shortcode'));   
add_shortcode('embedsocial_reviews', array($plugin, 'embedsocial_reviews_album_shortcode'));
add_shortcode('embedsocial_google_reviews', array($plugin, 'embedsocial_google_reviews_album_shortcode'));
add_shortcode('embedsocial_custom_reviews', array($plugin, 'embedsocial_custom_reviews_album_shortcode'));
add_shortcode('embedsocial_stories', array($plugin, 'embedsocial_stories_shortcode'));
add_shortcode('embedsocial_stories_popup', array($plugin, 'embedsocial_stories_popup_shortcode'));
add_shortcode('embedsocial_story_gallery', array($plugin, 'embedsocial_story_gallery_shortcode'));


?>
