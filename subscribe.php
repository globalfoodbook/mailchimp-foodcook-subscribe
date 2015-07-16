<?php
/**
 * @package MailChimp Subscribe For Food & Cook Theme by Global Food Book
 * @version 1.0
 */
/*
Plugin Name: MailChimp Subscribe For Food & Cook Theme
Plugin URI: http://wordpress.org/extend/plugins/mailchimp-foodcook-subscribe/
Description: This plugin is an extract from <a href='http://globalfoodbook.com' target='_blank'>globalfoodbook.com</a>. This plugin will work only on websites that have the <a href='http://themeforest.net/item/food-cook-multipurpose-food-recipe-wp-theme/4915630'>food-cook</a> template installed  and have setup mailchimp connect url, See food-cooke docs and <a href='http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id' target='_blank'>this</a> for more details. This plugin is built to help other <a href='http://themeforest.net/item/food-cook-multipurpose-food-recipe-wp-theme/4915630'>food-cook</a> site owners (from the support group) who require this utility. It is implemented to allow easy setup and customization of a website's newsletter subscription widget and modal popup. It is best used with food and cook recipe theme made with woo themes.
Author: Ikenna N. Okpala
Version: 1.0
Author URI: http://ikennaokpala.com/
*/
// File Security Check
if (!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die('You do not have sufficient permissions to access this page');
}
/*---------------------------------------------------------------------------------*/
/* GFB Newsletter Subscribe widget */
/*---------------------------------------------------------------------------------*/

class GFB_Subscribe extends WP_Widget
{
    var $settings = array('title', 'summary', 'button_txt', 'm_title', 'm_summary', 'm_img', 'privacy_policy');

    function GFB_Subscribe()
    {
        $widget_ops = array(
            'classname' => 'gfb_subscribe',
            'description' => 'MailChimp Subscribe For Food & Cook Theme .'
        );
        parent::WP_Widget(false, __('MailChimp Subscribe For Food Cook Theme', 'woothemes'), $widget_ops);
    }

    function widget($args, $instance)
    {
        $settings = $this->woo_get_settings();
        extract($args, EXTR_SKIP);
        $instance = wp_parse_args($instance, $settings);
        extract($instance, EXTR_SKIP);

        // Enforce defaults
        foreach (array(
            'title',
            'summary',
            'button_txt',
            'm_title',
            'm_summary',
            'm_img',
            'privacy_policy',
            'button_color'
        ) as $setting) {
            if (!$$setting)
                $$setting = $settings[$setting];
        }

        echo $before_widget;
        if ($title) {
            echo "<h3 style='margin:0;padding:0;'>" . $title . "</h3>";
        }
        if ($summary) {
            echo "<center><p style='margin-top:5px;padding:0;font-size:14px;'>" . $summary . "</center></p>";
        }?>

        <center style="font-family:georgia, serif;vertical-align:baseline;border:0;margin:0;padding:0;color:#fff;">
          <div style="margin-top:25px;margin-bottom:35px;font-size:22px;">
            <a href="#" onclick="gfb_subscribe.overlay()" style="cursor:pointer;font-family:'Open Sans', sans-serif;font-weight:bold;text-transform:uppercase;color:#572641;transition:all 0.1s ease-in-out;padding:7px 12px;background-color:<?php
            if ($button_color) {
                echo $button_color;
            } else {
                echo '#512D8C';
            } ?>;border:1px solid #512d8c;font-size:18px;">
            <span style="color: #fff;">
          <?php
          if ($button_txt) {
              echo $button_txt;
          } else {
              echo "Subscribe via Email";
          }?>
          </span></a>
          </div>
        </center>
        <?php
        $this->subscribe_form($m_title, $m_summary, $m_img, $privacy_policy, $button_color);
        echo $after_widget;
    }
    function update($new_instance, $old_instance)
    {
        $new_instance['summary']   = wp_kses_post($new_instance['summary']);
        $new_instance['m_summary'] = wp_kses_post($new_instance['m_summary']);
        return $new_instance;
    }

    /**
     * Provides an array of the settings with the setting name as the key and the default value as the value
     * This cannot be called get_settings() or it will override WP_Widget::get_settings()
     */
    function woo_get_settings()
    {
        // Set the default to a blank string
        $settings = array_fill_keys($this->settings, '');
        return $settings;
    }

    function form($instance)
    {
        $instance = wp_parse_args($instance, $this->woo_get_settings());
        extract($instance, EXTR_SKIP);?>
		<p>
		   <label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title (optional):', 'woothemes');?></label>
		   <input type="text" name="<?php echo $this->get_field_name('title');?>"  value="<?php echo esc_attr($title);?>" class="widefat" id="<?php echo $this->get_field_id('title');?>" />
		</p>
		<p>
		   <label for="<?php echo $this->get_field_id('summary'); ?>"><?php _e('Summary (optional):', 'woothemes');?></label>
			<textarea name="<?php echo $this->get_field_name('summary'); ?>" class="widefat" id="<?php echo $this->get_field_id('summary'); ?>"><?php echo esc_textarea($summary);?></textarea>
		</p>
		<p>
		   <label for="<?php echo $this->get_field_id('button_txt'); ?>"><?php _e('Button Text (optional):', 'woothemes');?></label>
       <input type="text" name="<?php echo $this->get_field_name('button_txt'); ?>"  value="<?php echo esc_attr($button_txt); ?>" class="widefat" id="<?php echo $this->get_field_id('button_txt'); ?>" placeholder="Default text is Subscribe via Email" />
	  </p>
		<p>
		   <label for="<?php echo $this->get_field_id('button_color'); ?>"><?php
        _e('Button Color:', 'woothemes');
?></label>
       <input type="text" name="<?php echo $this->get_field_name('button_color'); ?>"  value="<?php echo esc_attr($button_color); ?>" class="widefat" id="<?php echo $this->get_field_id('button_color'); ?>" placeholder="For hex colors don't forget to add #."/>
	  </p>
		<p>
		   <label for="<?php echo $this->get_field_id('m_title'); ?>"><?php
        _e('Popup Title:', 'woothemes');
?></label>
       <input type="text" name="<?php echo $this->get_field_name('m_title'); ?>"  value="<?php echo esc_attr($m_title); ?>" class="widefat" id="<?php echo $this->get_field_id('m_title'); ?>"/>
	  </p>
		<p>
		   <label for="<?php echo $this->get_field_id('m_summary'); ?>"><?php
        _e('Popup Summary:', 'woothemes');
?></label>
			<textarea name="<?php echo $this->get_field_name('m_summary'); ?>" class="widefat" id="<?php echo $this->get_field_id('m_summary'); ?>"><?php echo esc_textarea($m_summary); ?></textarea>
		</p>
		<p>
		   <label for="<?php echo $this->get_field_id('m_img'); ?>"><?php
        _e('Popup Image URL:', 'woothemes');
?></label>
       <input type="text" name="<?php echo $this->get_field_name('m_img'); ?>"  value="<?php echo esc_attr($m_img); ?>" class="widefat" id="<?php echo $this->get_field_id('m_img'); ?>"/>
	  </p>
		<p>
		   <label for="<?php echo $this->get_field_id('privacy_policy'); ?>"><?php _e('Privacy Policy:', 'woothemes'); ?></label>
       <input type="text" name="<?php echo $this->get_field_name('privacy_policy'); ?>"  value="<?php echo esc_attr($privacy_policy); ?>" class="widefat" id="<?php echo $this->get_field_id('privacy_policy'); ?>" placeholder="Set to override default"/>
	  </p>
		<?php
    }
    function subscribe_form($m_title, $m_summary, $m_img, $privacy_policy, $button_color)
    {
        $settings = array(
            'connect_newsletter_id' => '',
            'connect_mailchimp_list_url' => '',
            'feed_url' => ''
        );
        $settings = woo_get_dynamic_values($settings);
        if ($settings['connect_mailchimp_list_url'] != "" && $settings['connect_newsletter_id'] == ""):?>
           <script type="text/javascript">
             var GFBSubscribe = function(){};
             var gfb_subscribe = new GFBSubscribe()

             GFBSubscribe.prototype.overlay = function() {
               el = document.getElementById("overlay");
               document.getElementById("gfb_response_message_success").style.display = "none";
               document.getElementById("gfb_response_message_error").style.display = "none";
               document.getElementById("gfb_subscribe_email_text").value = "";
               document.getElementById("gfb_intial_message").style.display = "inline";
               el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
             }

             GFBSubscribe.prototype.postData = function(email){
              if(this.isValidEmail(email) == true){
                // console.log("isValidEmail: "+this.isValidEmail(email));
                var xmlhttp;
                if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp=new XMLHttpRequest();
                } else {// code for IE6, IE5
                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function(){
                  // console.log("in ready state", xmlhttp.status);
                  if (xmlhttp.status == 0 || xmlhttp.status == 200)
                    {
                      var respTag = document.getElementById("gfb_response_message_success");
                      // console.log("Email: ", respTag);
                      respTag.innerHTML= "A confirmation email has been sent to your email address. <br/> Click the confirmation link to recieve the ebook.";
                      respTag.style.display = "block";
                      document.getElementById("gfb_intial_message").style.display = "none";
                      document.getElementById("gfb_response_message_error").style.display = "none";

                      setTimeout(function(){ gfb_subscribe.overlay(); }, 15000);
                    }
                  }

                xmlhttp.open("POST","<?php echo $settings['connect_mailchimp_list_url']; ?>",true);
                xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xmlhttp.send("EMAIL="+email);

              } else {
                var respTag = document.getElementById("gfb_response_message_error");
                // console.log("Email: ", respTag);
                document.getElementById("gfb_response_message_success").style.display = "none";

                respTag.innerHTML= "Valid email address required";
                respTag.style.display = "block";
                // console.log("Not valid: "+this.isValidEmail(email));
              }
            }

            GFBSubscribe.prototype.isValidEmail = function(entry) {
                return (entry.indexOf(".") > 2) && (entry.indexOf("@") > 0);
            }

            if(<?php
                if (is_user_logged_in()) {
                    echo "true";
                } else {
                    echo "false";
                }?>) {
            }else{
              var docBody = document.body,
              docElement = document.documentElement,
              height

              if (typeof document.height !== 'undefined') {
                  height = document.height // For webkit browsers
              } else {
                  height = Math.max( docBody.scrollHeight, docBody.offsetHeight,docElement.clientHeight, docElement.scrollHeight, docElement.offsetHeight );
              }

              window.onscroll = function () {
                if (window.pageYOffset > (height/2)){
                  if (document.getElementById("overlay").style.visibility == false ){
                    gfb_subscribe.overlay();
                  }
                }
              }
            }
           </script>
        <style>
          #overlay {
            visibility: hidden;
            position: absolute;
            left: 0px;
            top: 0px;
            right: 0px;
            bottom: 0px;
            margin: auto;
            width: 100%;
            height: 100%;
            text-align: center;
            z-index: 10000;
            background-color: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
            text-align: center;
          }
          #overlay div#gfb_newsletter_signup_form {
            max-width: 750px;
            min-width: 250px;
            max-height: 600px;
            min-height: 280px;
            height: 30%;
            margin: 5px auto;
            background-color: #fff;
            border: 1px solid #000;
            padding: 20px;
            text-align: center;
            position: absolute;
          }
          a.boxclose{
             float:right;
             position:absolute;
             top:-10px;
             right:-10px;
             cursor:pointer;
             color: #fff;
             border: 1px solid #AEAEAE;
             border-radius: 30px;
             background: #605F61;
             font-size: 17px;
             display: inline-block;
             line-height: 0px;
             /*padding: 11px 7px 17px 7px;*/
             padding: 11px 8px 15px;
           }
         .boxclose:before {
             content: "x";
         }
         .gfb_ebook_img {
           display: block;
           clear: both;
           position: relative;
         }
         #gfb_newsletter_signup_form  h1 {
           font-family: "Open Sans",Arial,Helvetica,sans-serif;
           display: block;
           font-size: 30px;
           font-weight: bold;
           color: #333;
           padding: 0px 10px;
           text-align: center;
         }
         div#p-footer {
          padding:15px;
          display: block !important;
          position: relative;
         }
        </style>
       <div id="overlay">
        <div id="gfb_newsletter_signup_form">
          <a class="boxclose" onclick="gfb_subscribe.overlay();" id="boxclose" style=""></a>
          <?php if ($m_img) {?>
            <img class="gfb_ebook_img" src="<?php echo $m_img;?>" style="float:left; width:30%;min-width:50px;max-width:200px;max-height:250px;min-height:100px;"/>
          <?php } ?>
          <div id="" style="<?php
            if ($m_img) {
                echo 'float:right;width:70%; border:0;';
            } else {
                echo 'width:100%;border:0;';
            }?>">
            <h1><?php echo $m_title;?></h1>
            <center>
              <p id="gfb_response_message_success" style="display:none;font-size:14px;font-weight:bold!important;color:#009933 !important;"></p>
              <p id="gfb_response_message_error" style="display:none;font-size:14px;font-weight:bold!important;color:#FF0000 !important;"></p>
              <p id="gfb_intial_message" style='max-width:350px;min-width:150px;margin-top:5px;padding:0;font-size:14px;'>
                <?php echo $m_summary;?>
              </p>
            </center>
            <input id="gfb_subscribe_email_text" type="text" name="EMAIL" value="" placeholder="Enter your E-mail" id="mce-EMAIL" style="border:1px solid #DBDBDB;max-width:250px;min-width:60px;color:#000;margin-bottom:10px;height:27.5px;">
            <button name="subscribe" id="mc-embedded-subscribe" class="btn submit button" type="button" onclick="gfb_subscribe.postData(document.getElementById('gfb_subscribe_email_text').value)" style="background:  <?php
            if ($button_color) {
                echo $button_color;
            } else {
                echo '#512D8C';
            }?> none repeat scroll 0%" ><?php _e('Sign Up', 'woothemes');?></button>
            <div id="p-footer"><b> Privacy Policy: <?php
            if ($privacy_policy) {
                echo $privacy_policy;
            } else {
                echo "We dislike SPAM E-Mail. We pledge to keep your email address safe.";
            }?></b></div>
          </div>
        </div>
      </div>
     <?php
        endif;
    }
}
add_action('widgets_init', create_function('', 'return register_widget("GFB_Subscribe");'), 1);
?>
