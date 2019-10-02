<?php
/**
 Plugin Name: Instagram Poster - Wordpress to Instagram Post/Story
 Plugin URI: http://developerhouse.com/blog/product/instagram-poster-wordpress-to-instagram-post-story-pro/
 Description: The plugin allows to publish posts/stories on your instagram account.
 Version: 1.2.4
 Author: Developer House
 Author URI: http://developerhouse.com
 Copyright 2019 Developer House - All Rights Reserved.
 This Software should not be used or changed without the permission
 of Developer House.
 */
// Set Time Limit for execution of script
set_time_limit(0);
date_default_timezone_set('UTC');
/**
 * @integrate the Instagram api for posting stories/post
 */
require_once 'lib/vendor/autoload.php';

/**
 * @integrate the Grafika api for image watermark+
 */
require_once 'lib/grafika/autoloader.php';

/**
 * @use namespace for Grafika library
 */
use Grafika\Grafika;
use Grafika\GD\Image;
use Grafika\Color;
/**
 * @create and initialize InstagramAutoPoster class
 */
$instagramAutoPoster = new InstagramAutoPoster();
$instagramAutoPoster->init();
class InstagramAutoPoster

{
    protected $debug = false;
    protected $pageName = 'wordpress-instagram-poster.php';
    protected $settingsGroup = 'wordpress_instagram_poster_settings';
    protected $domain = 'wordpress-instagram-poster';
    protected $ig;
    // Main function to add action & filters
    public function init()

    {
        add_action('admin_head', array(
            $this,
            'stylesheet'
        ));
        add_action('admin_head', array(
            $this,
            'js'
        ));
        add_action('admin_menu', array(
            $this,
            'plugin_options'
        ));
        add_action('admin_init', array(
            $this,
            'register_settings'
        ));
        add_filter('publish_post', array(
            $this,
            'onPostSave'
        ));
		
		add_filter('publish_future_post', array(
            $this,
            'onPostSave'
        ));
		
		add_action('added_post_meta',array(
            $this, 
            'onPostAli'
        ),10,4);
        add_filter('publish_product', array(
            $this,
            'onPostSave'
        ));
        add_action('wp_ajax_instagram_send', array(
            $this,
            'ajaxSend'
        ));
        add_action('wp_ajax_preview_item', array(
            $this,
            'ajaxSendPreview'
        ));
        add_action('wp_ajax_do_login', array(
            $this,
            'ajaxSendLogin'
        ));
        add_action('wp_ajax_do_two_factor', array(
            $this,
            'ajaxSendLoginTwoFactor'
        ));
        add_action('wp_ajax_do_otp_login', array(
            $this,
            'ajaxSendLoginOtpFactor'
        ));
        add_action('add_meta_boxes', array(
            $this,
            'addMetaBox'
        ));
        add_action('instagram_scheduled', array(
            $this,
            'publishToInstagram'
        ) , 10, 3);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__) , array(
            $this,
            'settings_link'
        ));
    }
    // Settings link on wordpress installed plugin page
    public function settings_link($links)

    {
        $links[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=' . $this->pageName)) . '">Settings</a>';
        return $links;
    }
    protected function getOption($name)
    {
        $options = get_option($this->settingsGroup);
        if (empty($options) || !is_array($options))
        {
            return null;
        }
        return $options[$name];
    }
    // Stylesheet CSS for admin panel
    public function stylesheet()

    {
        echo <<<HTML
            <style type='text/css'>
                .instagram_result {
                    font-size: 16px;
                    margin: 10px 0 0 0;
                }
                 .instagram_send {
                     display:block;
                }
                 .instagram_story_send {
                 display:block;
                }  
				
				.success_settings{color: #32CD32;}
				.alert_settings{color: #D8000C;}
				.auth_error{color: #D8000C;}
            </style>
HTML;
        
    }
    // Javascript for admin panel
    public function js()

    {
        echo '
            <script type="text/javascript">
            jQuery(document).ready(function($) {

                jQuery("#preview_item").bind("click", function(){
                    
                    var data = {
                        action: "preview_item",
						"preview_access_token":jQuery("input[name=\"wordpress_instagram_poster_settings[f_access_token]\"]").val(),
						"preview_url_caption":jQuery("input[name=\"wordpress_instagram_poster_settings[f_url_caption]\"]:checked").val(),
						"preview_text_url_caption":jQuery("input[name=\"wordpress_instagram_poster_settings[f_url_text_caption]\"]:checked").val(),
						"preview_excerpt_caption":jQuery("input[name=\"wordpress_instagram_poster_settings[f_excerpt_caption]\"]:checked").val(),
						"preview_title_caption":jQuery("input[name=\"wordpress_instagram_poster_settings[f_title_caption]\"]:checked").val(),
						"preview_categories":jQuery("input[name=\"wordpress_instagram_poster_settings[f_categories]\"]:checked").val(),
						"preview_color_options":jQuery("input[name=\"wordpress_instagram_poster_settings[f_image_color_options]\"]").val(),
						"preview_text_color_options":jQuery("input[name=\"wordpress_instagram_poster_settings[f_image_text_color_options]\"]").val(),
						"preview_tags":jQuery("input[name=\"wordpress_instagram_poster_settings[f_tags]\"]:checked").val(),
						"preview_image_options":jQuery("input[name=\"wordpress_instagram_poster_settings[f_image_options]\"]:checked").val(),
						
                    };
    
                   jQuery.post(ajaxurl, data, function(json) {
                        if (json.status) {
                            var page=window.open("","_blank", "width=500, height=600");
                        page.document.body.innerHTML = "<img src=\""+json.html+"\" width=500 height=500></img><br /><p>"+json.caption+"</p>";
                      } else {
                            alert(json.error);
                        }
                    }, "json");
                });
				
				
                jQuery("#do_login").bind("click", function(){
                    
                    var data = {
                        action: "do_login",
						"username":jQuery("input[name=\"wordpress_instagram_poster_settings[username]\"]").val(),
						"password":jQuery("input[name=\"wordpress_instagram_poster_settings[password]\"]").val(),
						
                    };
    
                   jQuery.post(ajaxurl, data, function(json) {
                        if (json.status=="3") {
    var txt;
    var otp = prompt("Please enter your OTP:", "");
    if (otp == null || otp == "") {
        txt = "";
    } else {
        txt = otp;
    }
	var data = {
                        action: "do_otp_login",
						"username":jQuery("input[name=\"wordpress_instagram_poster_settings[username]\"]").val(),
						"password":jQuery("input[name=\"wordpress_instagram_poster_settings[password]\"]").val(),
						"otp":txt,
						"user_id":json.user_id,"challenge_id":json.challenge_id
						
                    };
    
                   jQuery.post(ajaxurl, data, function(json) {
					   if(json.status=="1"){
						   jQuery("#alert_settings").html(json.error);
						  jQuery("#alert_settings").attr("class","success_settings");
						   
					   }else{
						   jQuery("#alert_settings").html(json.error);
						  jQuery("#alert_settings").attr("class","alert_settings");
						   
					   }
						  
                    }, "json");
                
                      }else if (json.status=="2"){
    var txt;
    var otp = prompt("Please enter your Two Factor Code:", "");
    if (otp == null || otp == "") {
        txt = "";
    } else {
        txt = otp;
    }
	var data = {
                        action: "do_two_factor",
						"username":jQuery("input[name=\"wordpress_instagram_poster_settings[username]\"]").val(),
						"password":jQuery("input[name=\"wordpress_instagram_poster_settings[password]\"]").val(),
						"otp":txt,"two_factor_identifier":json.two_factor
						
                    };
    
                   jQuery.post(ajaxurl, data, function(json) {
					   if(json.status=="1"){
						   jQuery("#alert_settings").html(json.error);
						  jQuery("#alert_settings").attr("class","success_settings");
						   
					   }else{
						   jQuery("#alert_settings").html(json.error);
						  jQuery("#alert_settings").attr("class","alert_settings");
						   
					   }
						  
                    }, "json");
                
                      }else if(json.status=="1"){
						  jQuery("#alert_settings").html(json.error);
						  jQuery("#alert_settings").attr("class","success_settings");
						  
					  } else {
						  jQuery("#alert_settings").html(json.error);
						  jQuery("#alert_settings").attr("class","alert_settings");
                          
                        }
                    }, "json");
                });
                jQuery("#share_insta").bind("click", function(){
                    var post_id = jQuery(this).data("id");
					var isPost=false;
					if($("#insta_post").is(":checked")){
						isPost=true;
					}
                    var data = {
                        action: "instagram_send",
                        post_id: post_id,
						type_post:isPost,
                    };
    
                    jQuery("#instagram_result").html("' . __('Sending...', $this->domain) . '");
                    jQuery.post(ajaxurl, data, function(json) {
                        if (json.status) {
                            jQuery(".instagram_result").html(json.html);
                            jQuery(".instagram_result").html("<span class=\"success_settings\">' . __('Successfully sent', $this->domain) . '</span>");
                        } else {
                            jQuery(".instagram_result").html("<span class=\"alert_settings\">"+ json.error+"</span>");
                        }
                    }, "json"); return false;
                });
            
                
            });
            </script>
        ';
    }
    public function plugin_options()

    {
        add_menu_page(__('Settings - Instagram Poster', $this->domain) , __('Instagram Poster', $this->domain) , 'manage_options', $this->pageName, array(
            $this,
            'plugin_options_page'
        ) , 'dashicons-welcome-widgets-menus');
    }
    // Show output html on options page
    public function plugin_options_page()

    {
        echo '
        <div class="wrap">
            <h2>' . __('Instagram Poster', $this->domain) . '</h2>
            <p>' . __('The plugin allows to publish posts and stories on your Instagram account.', $this->domain) . '</p>
            <form method="post" enctype="multipart/form-data" action="options.php">
        ';
        echo settings_fields($this->settingsGroup);
        echo do_settings_sections($this->pageName);
        echo ' <p class="submit">
					<span id="alert_settings"></span><br /><br />
                    <span>Please click "Check Login" before proceeding to "Save Changes"</span><br /><br />
					<span type="button" id="do_login" class="button-primary">' . __('Check Login', $this->domain) . '</span>
                    <input type="submit" class="button-primary" value="' . __('Save Changes', $this->domain) . '" />
                    <span type="button" id="preview_item" class="button-primary">' . __('Preview', $this->domain) . '</span>
                </p>
            </form>
        </div>
        ';
    }
    public function register_settings()

    {
        if (get_option("hiddenIndex") == false)
        {
            add_option("hiddenIndex", '0');
        }
        register_setting($this->settingsGroup, $this->settingsGroup, array(
            $this,
            'true_validate_settings'
        ));
        add_settings_section('instagram_section', __('Settings', $this->domain) , '', $this->pageName);
        // login
        add_settings_field('username', __('Instagram login', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'text',
            'id' => 'username',
            'required' => '',
            'desc' => '',
            'label_for' => 'username'
        ));
        // passw
        add_settings_field('password', __('Instagram password', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'password',
            'id' => 'password',
            'required' => '',
            'desc' => '',
            'label_for' => 'password'
        ));
        // status
        add_settings_field('status', __('Plugin status', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'status',
            'vals' => array(
                'on' => __('Send post to instagram when Publish/Update', $this->domain) ,
                'off' => __('Manually post to instagram', $this->domain) ,
            )
        ));
        // make
        add_settings_field('make', __('How to make auto-posts?', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'make',
            'vals' => array(
                'off' => __('Use WP Cron to Schedule auto posts', $this->domain) ,
                'on' => __('Publish Immediately after posting', $this->domain) ,
            )
        ));
		  // status
        add_settings_field('post_Ali', __('Use AliPlugin', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'post_Ali_status','desc'=>__('Enable if you want to post products from <a href="http://shubhamm.yaronevsky.hop.clickbank.net/">AliPlugin</a> on Instagram'),
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) 
				
            )
        ));
		
        if ($this->getOption("f_image_options") == null)
        {
            $checked = "on";
            $disabled = false;
        }
        else if ($this->getOption("f_image_options") == "on")
        {
            $disabled = false;
            $checked = null;
        }
        else
        {
            $checked = null;
            $disabled = true;
        }
        // Image options
        add_settings_field('f_image_options', __('Use text on Image includes Title & URL', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'f_image_options',
            'checked' => $checked,
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) ,
            )
        ));
        // Image background color options
        add_settings_field('f_image_color_options', __('Image BG Color Code', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'text',
            'id' => 'f_image_color_options',
            'desc' => 'Type Hex color code which will be used as background if image is not suitable for Instagram aspect ratio!',
        ));
        // Text color on image
        add_settings_field('f_image_text_color_options', __('Image Text Color Code', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'text',
            'id' => 'f_image_text_color_options',
            'desc' => 'Type Hex color code which will be used as color for image text!',
        ));
        // Tags
        add_settings_field('f_tags', __('Insert tags of post/product as hashtags?', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'f_tags',
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) ,
            )
        ));
        // Categories
        add_settings_field('f_categories', __('Insert categories of post/product as hashtags?', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'f_categories',
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) ,
            )
        ));
          // URL Caption
        add_settings_field('f_url_text_caption', __('Insert URL of post/product in caption? <a href="http://developerhouse.com/blog/product/instagram-poster-wordpress-to-instagram-post-story-pro/">Pro Only Feature</a>', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio','disabled'=>'true',
            'id' => 'f_url_text_caption','disabled'=>'true',
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) ,
            )
        ));
        // Title Caption
        add_settings_field('f_title_caption', __('Insert title of post/product in caption?', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'f_title_caption',
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) ,
            )
        ));
        // Short Summary of post/product
        add_settings_field('f_excerpt_caption', __('Insert Excerpt of post/product in caption?', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'f_excerpt_caption',
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) ,
            )
        ));
        // URL Caption
        add_settings_field('f_url_caption', __('Insert url of post/product in Image? <a href="http://developerhouse.com/blog/product/instagram-poster-wordpress-to-instagram-post-story-pro/">Pro Only Feature</a>', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'f_url_caption','disabled'=>'true',
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) ,
            )
        ));
        // Random Story Upload
        add_settings_field('f_random_story', __('Enable posting story of post/product? <a href="http://developerhouse.com/blog/product/instagram-poster-wordpress-to-instagram-post-story-pro/">Pro Only Feature</a>', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'radio',
            'id' => 'f_random_story','disabled'=>'true',
            'desc' => 'Link available only for business account with followers more then 10,000 ',
            'vals' => array(
                'on' => __('Enable', $this->domain) ,
                'off' => __('Disable', $this->domain) ,
            )
        ));
        // Random Story Upload
        add_settings_field('f_random_interval', __('Story upload interval of posts? <a href="http://developerhouse.com/blog/product/instagram-poster-wordpress-to-instagram-post-story-pro/">Pro Only Feature</a>', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', array(
            'type' => 'number','disabled'=>'true',
            'id' => 'f_random_interval',
            'desc' => 'Use numeric value (For example after how many posted story next story will be posted)>',
        ));
        if ($this->getOption('f_random_interval') == null)
        {
            update_option('f_random_interval', '3');
        }
        $bitArray = array(
            'type' => 'text','disabled'=>'true',
            'id' => 'f_access_token',
            'desc' => 'Bit.ly API for link shortener required if you want to use url on Image',
        );
        // Bit.ly API access token
        add_settings_field('f_access_token', __('Bit.ly <a href="https://support.bitly.com/hc/en-us/articles/230647907-How-do-I-find-my-OAuth-access-token-">Access Token</a> for URL Shortener <a href="http://developerhouse.com/blog/product/instagram-poster-wordpress-to-instagram-post-story-pro/">Pro Only Feature</a>', $this->domain) , array(
            $this,
            'display_input_field'
        ) , $this->pageName, 'instagram_section', $bitArray);
    }
    // Validate Settings with input
    public function true_validate_settings($input)

    {
        foreach ($input as $k => $v)
        {
            $valid_input[$k] = trim($v);
        }
        return $valid_input;
    }
    // Add stats on post & product post types
    public function addMetaBox()

    {
        $screens = array(
            'post',
            'product'
        );
        foreach ($screens as $screen)
        {
            add_meta_box('instagram_box', __('Instagram Poster', $this->domain) , array(
                $this,
                'metaBoxGetPrintCheckResults'
            ) , $screen, 'side', 'default');
        }
    }
    public function metaBoxGetPrintCheckResults()

    {
        global $post;
        $login = $this->getOption('username');
        $passw = $this->getOption('password');
        if (empty($login) || empty($passw))
        {
            update_post_meta($post->ID, "auth-status", "auth-error");
        }
        else
        {
            update_post_meta($post->ID, "auth-status", "");
        }
        $image_path = $this->getImagePath($post->ID);
        if ($image_path == null)
        {
            $error_message = __('No Thumbnail<br />', $this->domain);
        }
        $status_post = get_post_meta($post->ID, "post-send", true);
        $auth_status = get_post_meta($post->ID, "auth-status", true);
        $text_story = (is_array($status_story) && isset($status_story['status']) && $status_story['status'] == 'fail') ? "Resend" : "Send";
        $text_post = (is_array($status) && isset($status['status']) && $status['status'] == 'fail') ? "Resend" : "Send";
        $post_status = get_post_status($post->ID);
        $error = "";
        $message = "";
        for ($i = 0;$i < 1;$i++)
        {
            $type ="post" ;
            $status = "status_" . $type;
            $status = $$status;
            // if(strlen(trim($message))>0){$message.="<br />";}
            if (is_array($status) && isset($status['status']) && $status['status'] == 'fail')
            {
                $message .= "<p>" . $status['message'] . "</p>";
            }
            elseif ((is_array($status) && isset($status['status']) && $status['status'] == 'ok') || $status == "1")
            {
                $date = $this->getPostedDate($post->ID);
                $message .= $date;
            }
            elseif ($status == "sending")
            {
                $message .= "<p>" . __('Instagram ' . ucfirst($type) . ' sending...', $this->domain) . "</p>";;
            }
            elseif ($status == "image-error")
            {
                //  echo '<div class="instagram_date"></div>';
                $message .= "<p>" . __('Instagram ' . ucfirst($type) . ' no thumbnail', $this->domain) . "</p>";;
            }
            elseif ($status == "not-sent")
            {
                $message .= "<p>" . __('Instagram ' . ucfirst($type) . ' not sent to instagram', $this->domain) . "</p>";;
            }
            elseif (is_array($status) && isset($status['status']) && $status['status'] == 'fail')
            {
                // echo '<div class="instagram_date"></div>';
                $message .= "<p>" . $status['message'] . "</p>";;
            }
            elseif ($auth_status == "auth-error")
            {
                // echo '<div class="instagram_date"></div>';
                $error_message = "<p>" . __('Authorization error<br />Please setup credentials', $this->domain) . "</p>";;
                // echo '<span class="instagram_story_send button" data-id="'.$post->ID.'">'.__($text_story.' to instagram as Story', $this->domain).'</span><br /><br />';
                // echo '<span class="instagram_send button" data-id="'.$post->ID.'">'.__($text_post.' to instagram as Post', $this->domain).'</span><br />';
                
            }
            else
            {
                $message .= "<p>" . __('Instagram ' . ucfirst($type) . ' not yet sent...', $this->domain) . "</p>";;
            }
        }
        $html = '<div id="minor-publishing"><div id="misc-publishing-actions"><div class="misc-pub-section"><fieldset>';
        if (strlen(trim($error_message)) > 0)
        {
            $html .= '<div class="auth_error">' . $error_message . '</div>';
        }
        else
        {
            $html .= '
<label>' . $message . '</label><br />
<input type="checkbox" name="insta_post" id="insta_post" checked="checked">
<label for="insta_post" class="post-format-standard">Post</label><br />
<input type="checkbox" name="insta_story" id="insta_story" checked="checked">
<label for="insta_story" class="post-format-standard">Story <a href="http://developerhouse.com/blog/product/instagram-poster-wordpress-to-instagram-post-story-pro/">Pro Only Feature</a></label>
</fieldset><div class="clear"></div>
<div class="instagram_result"></div><br /><div class="clear"></div>';
        }
        if ($post_status == "publish" && strlen(trim($error_message)) <= 0)
        {
            $html .= '<a class="button button-primary button-large" style="float:right;" data-id="' . $post->ID . '" id="share_insta">Share</a>
<div class="clear"></div></div></div></div>';
        }
        else
        {
            $html .= '<a disabled class="button button-primary button-large" style="float:right;" data-id="' . $post->ID . '" id="share_insta">Share</a>
<div class="clear"></div></div></div></div>';
        }
        echo $html;
    }
    // Get posted date from instagram
    public function getPostedDate($post_id, $type = "post")

    {
        if ($type == "post")
        {
            $time = get_post_meta($post_id, 'post-time', 1);
            $code = get_post_meta($post_id, 'post-code', 1);
        }
        return '<p><a href="' . $code . '" target="_blank">' . __('Posted ' . ucfirst($type) . ' on', $this->domain) . ' ' . '(' . date("d.m.Y H:i", $time) . ')</a></p>';
    }
	
	//Called when Ali products added_post_meta
	  public function onPostAli( $meta_id, $object_id, $meta_key, $meta_value ){
		 	if($meta_key=="ali_id"){
		    	$this->onPostSave($object_id);
		}
}
    // Called when post or product is published
    public function onPostSave($post_id)

    {
        if ($this->getOption('status') == "off")
        {
            return;
        }
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id))
        {
            return;
        }
        if (get_post_type($post_id) != "post" && get_post_type($post_id) != "product" && get_post_type($post_id) != "products")
        {
            return;
        }
		
		$d = (get_the_time('U',$post_id));

$postTime=$d;
$d = (get_the_modified_time('U',$post_id));

$postTimeModified=$d;
	if(($postTime>=$postTimeModified ||$postTime==$postTimeModified) && get_post_status($post_id)=="publish"){

}else{return;}

        $isSending = $this->isSending($post_id);
        if ($this->getOption('make') == "on")
        {
            if (!$isSending)
            {
                $this->publishToInstagram($post_id, "post");
            }
        }
        else
        {
            if (!$isSending)
            {
                update_post_meta($post_id, "instagram-send", "sending");
                wp_schedule_single_event(time() + 10, 'instagram_scheduled', array(
                    $post_id
                ));
            }
        }
        if ($this->getOption('status') == "off")
        {
            update_post_meta($post_id, "instagram-send", "not-sent");
        }
    }
    // Function to check if post is in process of sending
    public function isSending($post_id)

    {
        $status = get_post_meta($post_id, "instagram-send", true);
        if ((is_array($status) && isset($status['status']) && $status['status'] == 'ok') || $status == "1")
        {
            return true;
        }
        return false;
    }
  
    // Validate login details
    public function ajaxSendLoginTwoFactor()

    {
        $login = $_POST["username"];
        $passw = $_POST["password"];
        \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
        $ig = new \InstagramAPI\Instagram($this->debug, false);
        try
        {
            $twoFactorIdentifier = $_POST["two_factor_identifier"];
            $verificationCode = trim($_POST["otp"]);
            $ig->finishTwoFactorLogin($login, $passw, $twoFactorIdentifier, $verificationCode);
            echo json_encode(array(
                'status' => 1,
                'error' => __("Logged in successfully", $this->domain)
            ));
        }
        catch(\InstagramAPI\Exception\InstagramException $e)
        {
            echo json_encode(array(
                'status' => 0,
                'error' => __($e->getMessage() , $this->domain)
            ));
        }
        wp_die();
    }
    // Validate Verification Challenge details
    public function ajaxSendLoginOtpFactor()

    {
        $login = $_POST["username"];
        $passw = $_POST["password"];
        $user_id = $_POST["user_id"];
        $challenge_id = $_POST["challenge_id"];
        \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
        $ig = new \InstagramAPI\Instagram($this->debug, false);
        try
        {
            $ig->_setUser($login, $passw);
            $customResponse = $ig->request("challenge/$user_id/$challenge_id/")->setNeedsAuth(false)
                ->addPost('security_code', trim($_POST["otp"]))->getDecodedResponse();
            echo "dd";
            //	$loginResponse= $ig->login($login, $passw);
            if (!is_array($customResponse))
            {
                echo json_encode(array(
                    'status' => 0,
                    'error' => __("An unkown error occured", $this->domain)
                ));
                wp_die();
            }
            if ($customResponse['status'] === 'ok' && (int)$customResponse['logged_in_user']['pk'] === (int)$user_id)
            {
                echo json_encode(array(
                    'status' => 1,
                    'error' => __("Logged in successfully", $this->domain)
                ));
            }
            else
            {
                echo json_encode(array(
                    'status' => 1,
                    'error' => __("Logged in successfully", $this->domain)
                ));
            }
            wp_die();
        }
        catch(\InstagramAPI\Exception\InstagramException $e)
        {
            echo json_encode(array(
                'status' => 0,
                'error' => __($e->getMessage() , $this->domain)
            ));
        }
        wp_die();
    }
    public function ajaxSendLogin()

    {
        $login = $_POST["username"];
        $passw = $_POST["password"];
        \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
        $ig = new \InstagramAPI\Instagram($this->debug, false);
        $loginResponse = null;
        try
        {
            $loginResponse = $ig->login($login, $passw);
            update_post_meta($post_id, "auth-status", "auth-success");
            if ($loginResponse !== null && $loginResponse->isTwoFactorRequired())
            {
                echo json_encode(array(
                    'status' => 2,
                    'error' => __('Two Factor Required', $this->domain) ,
                    'two_factor' => $loginResponse->getTwoFactorInfo()
                        ->getTwoFactorIdentifier()
                ));
                wp_die();
            }
            else
            {
                echo json_encode(array(
                    'status' => 1,
                    'error' => __('Logged in Successfully', $this->domain)
                ));
                wp_die();
            }
        }
        catch(\InstagramAPI\Exception\IncorrectPasswordException $e)
        {
            update_post_meta($post_id, "auth-status", "auth-error");
            echo json_encode(array(
                'status' => 0,
                'error' => __('Incorrect Password', $this->domain)
            ));
            wp_die();
        }
        catch(\InstagramAPI\Exception\InstagramException $e)
        {
            if ($loginResponse !== null && $loginResponse->isTwoFactorRequired())
            {
                echo json_encode(array(
                    'status' => 2,
                    'error' => __('Two Factor Required', $this->domain) ,
                    'two_factor' => $loginResponse->getTwoFactorInfo()
                        ->getTwoFactorIdentifier()
                ));
                wp_die();
            }
            else
            {
                $customResponse = $ig->request(substr($e->getResponse()
                    ->getChallenge()
                    ->getApiPath() , 1))
                    ->setNeedsAuth(false)
                    ->addPost('choice', 0)
                    ->getDecodedResponse();
                if (is_array($customResponse))
                {
                    $user_id = $customResponse['user_id'];
                    $challenge_id = $customResponse['nonce_code'];
                    echo json_encode(array(
                        'status' => 3,
                        'error' => __('Verification Required', $this->domain) ,
                        'user_id' => $user_id,
                        'challenge_id' => $challenge_id
                    ));
                    wp_die();
                }
                echo json_encode(array(
                    'status' => 0,
                    'error' => __('An unkown error occured', $this->domain)
                ));
                wp_die();
            }
            wp_die();
        }
    }
    // Check preview of last post made in wordpress
    public function ajaxSendPreview()

    {
        $args = array(
            'numberposts' => 1,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_status' => 'publish',
            'suppress_filters' => true
        );
        $postArray = wp_get_recent_posts($args);
        $post_id = $postArray[0]["ID"];
        $image = json_decode($this->createJPG($post_id, true, true) , true); //false or path
        $caption = $this->getCaption($post_id, true);
        if ($image["success"] == false)
        {
            echo json_encode(array(
                'status' => false,
                'error' => __($image["error"], $this->domain)
            ));
        }
        else
        {
            echo json_encode(array(
                'status' => true,
                'html' => wp_upload_dir() ["baseurl"] . "/insta_images/" . $image["path"],
                'caption' => $caption
            ));
        }
        wp_die();
    }
    // Ajax method to upload on instagram as post / story
    public function ajaxSend()

    {
        $is_post = $_POST["type_post"] === 'true';
        $type = "post";
        $post_id = $_POST['post_id'];
		if ($is_post)
        {
            $type = "post";
        }
        $result = array();
        if ($type == "post")
        {
            array_push($result, $this->publishToInstagram($post_id, "post"));
        }
        else
        {
            array_push($result, "<p>Wrong Post Type</p>");
        }
        if (count($result) > 1 && $result[0] === true && $result[1] === true)
        {
            $html = "";
            $html .= $this->getPostedDate($post_id, "post");
            echo json_encode(array(
                'status' => true,
                'html' => $html
            ));
        }
        else if ($result[0] === true)
        {
            echo json_encode(array(
                'status' => true,
                'html' => $this->getPostedDate($post_id, $type)
            ));
        }
        else
        {
            echo json_encode(array(
                'status' => false,
                'error' => $result[0]
            ));
        }
        wp_die();
    }
    // Publish to instagram as post
    public function publishToInstagram($post_id, $type = "post")

    {
        $login = $this->getOption('username');
        $passw = $this->getOption('password');
        if (empty($login) || empty($passw))
        {
            update_post_meta($post_id, "auth-status", "auth-error");
            return __('Authorization error', $this->domain);
        }
        \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
        $ig = new \InstagramAPI\Instagram($this->debug, false);
        try
        {
            $ig->login($login, $passw);
            update_post_meta($post_id, "auth-status", "auth-success");
        }
        catch(\InstagramAPI\Exception\IncorrectPasswordException $e)
        {
            update_post_meta($post_id, "auth-status", "auth-error");
            return __('Incorrect Password', $this->domain);
        }
        catch(\InstagramAPI\Exception\CheckpointRequiredException $e)
        {
            update_post_meta($post_id, "auth-status", "auth-error");
            return __('Checkpoint required please open web instagram', $this->domain);
        }
        catch(\InstagramAPI\Exception\ChallengeRequiredException $e)
        {
            update_post_meta($post_id, "auth-status", "auth-error");
            return __('Challenge required please open web instagram', $this->domain);
        }
        catch(\InstagramAPI\Exception\InstagramException $e)
        {
            update_post_meta($post_id, "auth-status", "auth-error");
            return __('Authorization error', $this->domain);
        }
        try
        {
            $timestamp = time() + get_option('gmt_offset') * 3600;
            if ($type == "post")
            {
                $image = json_decode($this->createJPG($post_id) , true); //false or path
                
            }
            $caption = $this->getCaption($post_id);
            if ($image["success"] == false)
            {
                update_post_meta($post_id, "post-send", "image-error");
                return __($image["error"], $this->domain);
            }
            if ($type == "post")
            {
                $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($image["path"]);
                $result = $ig
                    ->timeline
                    ->uploadPhoto($photo->getFile() , ['caption' => $caption]);
            }
            else
            {
                return __('Wrong Type', $this->domain);
            }
            if ($result->isStatus())
            {
                update_post_meta($post_id, $type . "-send", $result->isMedia());
                update_post_meta($post_id, $type . "-time", $timestamp);
                update_post_meta($post_id, $type . "-code", $result->getMedia()
                    ->getItemUrl());
                update_option('hiddenIndex', intval(get_option('hiddenIndex', '0')) + 1);
                return true;
            }
            else
            {
                update_post_meta($post_id, $type . "-send", $result);
            }
            return false;
        }
        catch(\InstagramAPI\Exception\InstagramException $e)
        {
            update_post_meta($post_id, $type . "-exception", $e->getMessage());
        }
    }
    public function get_plugin_dir_path($pluginFolderName)

    {
        if (defined('WPMU_PLUGIN_DIR') && file_exists(trailingslashit(WPMU_PLUGIN_DIR) . $pluginFolderName))
        {
            return trailingslashit(WPMU_PLUGIN_DIR) . $pluginFolderName;
        }
        elseif (defined('WP_PLUGIN_DIR') && file_exists(trailingslashit(WP_PLUGIN_DIR) . $pluginFolderName))
        {
            return trailingslashit(WP_PLUGIN_DIR) . $pluginFolderName;
        }
        return false;
    }
  public function return_image_path($postID) {
        
		if(get_post_type($postID)=="products"){
			global $wpdb;
		$db = $wpdb;
		$image_url = $db->get_var( $db->prepare("SELECT `subImageUrl` FROM `".$db->prefix . 'ae_products'."` WHERE `post_id` = %d", $postID) );
		$image_url = unserialize($image_url)[0];
            $dir_path = wp_upload_dir() ["basedir"] . "/alimages/"; 
            if (!file_exists($dir_path))
            {
                mkdir($dir_path, 0777, true);
            }
            $imagepath= $dir_path."postID.jpeg"; // to get file name;
	   
	 $gd=   imagecreatefromjpeg($image_url);
	 imagejpeg($gd,$imagepath);
	                if ( file_exists( $imagepath) ){return $imagepath;
                        
                        
                    }
                    return false;
			
			
		}else{
			  $thumbID = get_post_thumbnail_id($postID  );
                    $image= wp_get_attachment_image_src($thumbID,"large");
                    $upload_dir = wp_upload_dir();
                    $base_dir = $upload_dir['basedir'];
                    $base_url = $upload_dir['baseurl'];
                    $imagepath= str_replace($base_url, $base_dir, $image[0]);
                    if ( file_exists( $imagepath) ) return $imagepath;
					else
                    {$image=get_attached_file($thumbID);return $image;
                    }
                    return false;
			
		}
                  
}
    public function getImagePath($post_id)

    {
        
    $thumb = $this->return_image_path($post_id) ; 
    return $thumb;
    }
    // Create JPEG with options of title & url
    public function createJPG($post_id, $urlEmpty = false, $isPreview = false)

    {
        $path = $this->getImagePath($post_id);
        if (strlen($path) <= 0)
        {
            return json_encode(array(
                'success' => false,
                'error' => __("No thumbnail on post", $this->domain)
            ));
        }
        $fontttf = $this->get_plugin_dir_path("wordpress-instagram-poster") . '/font/LiberationSans-Regular.ttf';
        $editor = Grafika::createEditor(array(
            'Gd'
        ));
        if ($editor->isAvailable())
        {
            $editor->open($image, $path);
            $myWidth = 1080;
            $myHeight = 1080;
            $fileName = pathinfo($path);
            $dir_path = wp_upload_dir() ["basedir"] . "/insta_images/";
            if (!file_exists($dir_path))
            {
                mkdir($dir_path, 0777, true);
            }
            $tmp_dir = $dir_path . $fileName["basename"];
          
            if (file_exists($tmp_dir))
            {
                unlink($tmp_dir);
            }
            
            $image_options = "off";
            if ($isPreview)
            {
                $image_options = $_POST["preview_image_options"];
                $image_color = $_POST["preview_color_options"];
                $image_text_color = $_POST["preview_text_color_options"];
                $image_url_image= $_POST["preview_url_caption"];
            }
            else
            {
                $image_options = $this->getOption("f_image_options");
                $image_color = $this->getOption("f_image_color_options");
                $image_text_color = $this->getOption("f_image_text_color_options");
            }
            if ($image_options == "off")
            {
                $image2 = Image::createBlank($myWidth, $myHeight);
                $editor->fill($image2, new Color(($image_color != null) ? $image_color : "#FFFFFF"));
                if ($image->getWidth() > $image->getHeight())
                {
                    $editor->resizeExactWidth($image, $myWidth);
                }
                else
                {
                    $editor->resizeExactWidth($image, $myWidth);
                }
                if ($image->getHeight() > $myHeight)
                {
                    $editor->resizeExactHeight($image, $myHeight);
                }
                $editor->blend($image2, $image, 'normal', 1, 'center');
                $editor->save($image2, $tmp_dir);
                return ($urlEmpty == true) ? json_encode(array(
                    'success' => true,
                    'path' => $fileName["basename"]
                )) : json_encode(array(
                    'success' => true,
                    'path' => $tmp_dir
                ));
            }
            $image2 = Image::createBlank($myWidth, $myHeight);
            $editor->fill($image2, new Color(($image_color != null) ? $image_color : "#FFFFFF"));
            if ($image->getWidth() > $image->getHeight())
            {
                $editor->resizeExactWidth($image, $myWidth * 0.9);
            }
            else
            {
                $editor->resizeExactWidth($image, $myWidth * 0.9);
            }
            if ($image->getHeight() > $myHeight * 0.75)
            {
                $editor->resizeExactHeight($image, $myHeight * 0.75);
            }
            $str = $this->getTitle($post_id);
            if (strlen($str) > 45)
            {
                $str = substr($str, 0, 40) . "...";
            }
            $heightEmpty = ($myHeight - ($myHeight * 0.75));
            $str = "   " . $str . "   ";
            $url = "   " . $url . "   ";
            $bbox = imagettfbbox(20, 0, $fontttf, $str);
            $x = abs($bbox[2] - $bbox[0]);
            $fontsize = 20 * ($myWidth / $x);
            $bbox = imagettfbbox($fontsize, 0, $fontttf, $str);
            $x = abs($bbox[2] - $bbox[0]);
            $y = (($myHeight / 2) - ($image->getHeight() / 2));
            $y = ($y - abs((($bbox[5] - $bbox[1])))) / 2;
            $editor->text($image2, $str, $fontsize, ($myWidth / 2) - ($x / 2) , $y, new Color(($image_text_color != null) ? $image_text_color : "#000000") , $fontttf);
            $bbox = imagettfbbox(20, 0, $fontttf, $url);
            $x = $bbox[2] - $bbox[0];
            $fontSizeURL = 20 * ($myWidth / $x);
            $bbox = imagettfbbox($fontSizeURL, 0, $fontttf, $url);
            $x = $bbox[2] - $bbox[0];
            $y = (($myHeight / 2) + ($image->getHeight() / 2));
            
                $y = $y + ((($myHeight - $y) / 2)) - abs((($bbox[5] - $bbox[1]) / 2));
            
            $editor->text($image2, $url, $fontSizeURL, ($myWidth / 2) - ($x / 2) , $y, new Color(($image_text_color != null) ? $image_text_color : "#000000") , $fontttf);
            $editor->blend($image2, $image, 'normal', 1, 'center');
            $editor->save($image2, $tmp_dir);
            return ($urlEmpty == true) ? json_encode(array(
                'success' => true,
                'path' => $fileName["basename"]
            )) : json_encode(array(
                'success' => true,
                'path' => $tmp_dir
            ));
        }
        else
        {
            return json_encode(array(
                'success' => false,
                'error' => __("An unknown error occured", $this->domain)
            ));
        }
    }
    public function getCaption($post_id, $urlEmpty = false)

    {
        $data = array(
            'title' => $this->getParam($this->getTitle($post_id)) ,
            'excerpt' => $this->getParam($this->getExcerpt($post_id)) ,
            'hash_cats' => $this->getParam($this->getHcats($post_id)) ,
            'hash_tags' => $this->getParam($this->getHtags($post_id)) ,
        );
        $final_str = "";
        $final_str = "";
        foreach ($data as $key => $val)
        {
            if (strlen($val) > 0)
            {
                $final_str .= $val . " ";
            }
        }
        return $final_str;
    }
    public function getExcerpt($post_id)

    {
        if ($this->getOption("f_excerpt_caption") == "on")
        {
            global $post;
            $save_post = $post;
            $post = get_post($post_id);
            setup_postdata($post);
            $output = html_entity_decode(get_the_excerpt() , ENT_QUOTES, 'UTF-8');
            $post = $save_post;
            return trim($this->strip_all_shortcodes($output));
        }
        return "";
    }
    function strip_all_shortcodes($text)
    {
        $text = preg_replace("/\[[^\]]+\]/", '', $text); //strip shortcode
        return $text;
    }
    public function getParam($value)

    {
        return $value ? $value : '';
    }
    public function getTitle($post_id)

    {
        if ($this->getOption("f_title_caption") == "on")
        {
            return html_entity_decode(get_the_title($post_id) , ENT_QUOTES, 'UTF-8');
        }
        return "";
    }
    public function getHcats($post_id)

    {
        if ($this->getOption("f_categories") == "on")
        {
            $tags = array();
            if (get_post_type($post_id) == "product")
            {
                $catList = get_the_terms($post_id, 'product_cat');
            }
            else
            {
                $catList = get_the_category($post_id);
            }
            foreach ($catList as $cat)
            {
                $tags[] = strtolower(str_replace(array(
                    ' ',
                    '-'
                ) , '', $cat->name));
            }
            $htags = "";
            foreach ($tags as $tag)
            {
                $htags .= ", #" . $tag;
            }
            return trim(trim($htags, ','));
        }
        else
        {
            return "";
        }
    }
    public function getHtags($post_id)

    {
        if ($this->getOption("f_tags") == "on")
        {
            $tags = array();
            if (get_post_type($post_id) == "product")
            {
                $tagsList = get_the_terms($post_id, 'product_tag');
            }
            else
            {
                $tagsList = get_the_tags($post_id);
            }
            if ($tagsList)
            {
                foreach ($tagsList as $tag)
                {
                    $tags[] = strtolower(str_replace(array(
                        ' ',
                        '-'
                    ) , '', $tag->name));
                }
                $htags = "";
                foreach ($tags as $tag)
                {
                    $htags .= ", #" . $tag;
                }
                return trim(trim($htags, ','));
            }
            return "";
        }
        else
        {
            return "";
        }
    }
    public function display_input_field($args)

    {
        extract($args);
        if (!isset($disabled))
        {
            $disabled = false;
        }
        $option = $this->getOption($id);
        switch ($type)
        {
            case 'number':    $disabled = ($disabled == true) ? "disabled='true'" : '';
                echo sprintf("<input class='regular-text' type='number' id='%s' %s name='%s[%s]' value='%s' />", $id, $disabled,$this->settingsGroup, $id, esc_attr(stripslashes($option)));
                echo $desc ? "<br /><span class='description'>$desc</span>" : "";
            break;

            case 'text':
                $disabled = ($disabled == true) ? "disabled='true'" : '';
                echo sprintf("<input class='regular-text' type='text' id='%s' %s name='%s[%s]' value='%s' />", $id, $disabled, $this->settingsGroup, $id, esc_attr(stripslashes($option)));
                echo $desc ? "<br /><span class='description'>$desc</span>" : "";
            break;

            case 'password':
                echo sprintf("<input class='regular-text' type='password' id='%s' name='%s[%s]' value='%s' />", $id, $this->settingsGroup, $id, esc_attr(stripslashes($option)));
                echo $desc ? "<br /><span class='description'>$desc</span>" : "";
            break;

            case 'textarea':
                echo sprintf("<textarea class='code large-text' cols='50' rows='10' type='text' id='%s' name='%s[%s]'>%s</textarea>", $id, $this->settingsGroup, $id, esc_attr(stripslashes($option)));
                echo $desc ? "<br /><span class='description'>$desc</span>" : "";
            break;

            case 'checkbox':
                $checked = ($option == 'on') ? " checked='checked'" : '';
                echo sprintf("<label><input type='checkbox' id='%s' name='%s[%s]' %s /> ", $id, $this->settingsGroup, $id, $checked);
                echo $desc ? $desc : "";
                echo "</label>";
            break;

            case 'select':
                echo sprintf("<select id='%s' name='%s[%s]'>", $id, $this->settingsGroup, $id);
                foreach ($vals as $v => $l)
                {
                    $selected = ($option == $v) ? "selected='selected'" : '';
                    echo "<option value='$v' $selected>$l</option>";
                }
                echo $desc ? $desc : "";
                echo "</select>";
            break;

            case 'radio':
                echo "<fieldset>";
                foreach ($vals as $v => $l)
                {
                    $checked_state = (isset($checked) && $checked != null) ? (($checked == $v) ? "checked='checked'" : '') : (($option == $v || !$option) ? "checked='checked'" : '');
                    $disabled = ($disabled == true) ? "disabled='true'" : '';
                    echo sprintf("<label><input type='radio' %s name='%s[%s]' value='%s' %s />%s</label><br />", $disabled, $this->settingsGroup, $id, $v, $checked_state, $l);
                }
                echo $desc ? $desc : "";
                echo "</fieldset>";
            break;
        }
    }
}
?>
