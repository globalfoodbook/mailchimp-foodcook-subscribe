<?php
/**
 * @package MailChimp Subscribe For Food & Cook Theme by Global Food Book
 * @version 1.15
 */
/*
Plugin Name: MailChimp Subscribe For Food & Cook Theme
Plugin URI: http://wordpress.org/extend/plugins/mailchimp-subscribe-for-food-cook-theme/
Description: This plugin is an extract from <a href='http://globalfoodbook.com' target='_blank'>globalfoodbook.com</a>. This plugin will work only on websites that have the <a href='http://themeforest.net/item/food-cook-multipurpose-food-recipe-wp-theme/4915630'>food-cook</a> template installed  and have setup mailchimp connect url, See food-cooke docs and <a href='http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id' target='_blank'>this</a> for more details. This plugin is built to help other <a href='http://themeforest.net/item/food-cook-multipurpose-food-recipe-wp-theme/4915630'>food-cook</a> site owners (from the support group) who require this utility. It is implemented to allow easy setup and customization of a website's newsletter subscription widget and modal popup. It is best used with food and cook recipe theme made with woo themes.
Author: Ikenna N. Okpala
Version: 1.15
Author URI: http://ikennaokpala.com/
*/
// File Security Check
if (!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die('You do not have sufficient permissions to access this page');
}

// Enqueue script and styles
if ( !function_exists( 'gfb_add_to_head' ) ):
  function gfb_add_to_head() {
     wp_register_script( 'add-gfb-sub-js', plugin_dir_url( __FILE__ ) . 'includes/assets/javascript/gfb_subscribe.js', '', null,''  );
     wp_register_style( 'add-gfb-sub-css', plugin_dir_url( __FILE__ ) . 'includes/assets/css/gfb_subscribe.css','','', 'screen' );
     wp_enqueue_script( 'add-gfb-sub-js' );
     wp_enqueue_style( 'add-gfb-sub-css' );
  }
endif;

add_action( 'wp_enqueue_scripts', 'gfb_add_to_head' );

/*---------------------------------------------------------------------------------*/
/* GFB Newsletter Subscribe widget */
/*---------------------------------------------------------------------------------*/

class GFB_MailChimp_Subscribe extends WP_Widget
{
    var $plugin_name = 'MailChimp Subscribe For Food & Cook Theme.';
    var $settings = array('title', 'summary', 'button_txt', 'button_color', 'm_title', 'm_summary', 'm_img', 'privacy_policy', 'scroller_activity_status');

    function GFB_MailChimp_Subscribe()
    {
        $widget_ops = array(
            'classname' => 'gfb_subscribe',
            'description' => $this->plugin_name
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
            'button_color',
            'm_title',
            'm_summary',
            'm_img',
            'privacy_policy',
            'scroller_activity_status'
        ) as $setting) {
            if (!$$setting)
                $$setting = $settings[$setting];
        }

        echo $before_widget;
        if (function_exists('woo_get_dynamic_values')){
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
                echo "Subscribe";
            }?>
            </span></a>
            </div>
          </center>
          <?php
          $this->subscribe_form($m_title, $m_summary, $m_img, $privacy_policy, $button_color, $scroller_activity_status);
        } else{ $this->dependency_message();  }
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
      if (function_exists('woo_get_dynamic_values')){
        $instance = wp_parse_args($instance, $this->woo_get_settings());
        extract($instance, EXTR_SKIP);

        ?>
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
           <input type="text" name="<?php echo $this->get_field_name('button_txt'); ?>"  value="<?php echo esc_attr($button_txt); ?>" class="widefat" id="<?php echo $this->get_field_id('button_txt'); ?>" placeholder="Default text is Subscribe" />
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
    		<p>
    		   <label for="<?php echo $this->get_field_id('scroller_activity_status'); ?>"><?php _e('Display Popup On Scroll (optional):','woothemes'); ?></label>
    		   <input type="checkbox" name="<?php echo $this->get_field_name('scroller_activity_status'); ?>"  <?php checked($instance['scroller_activity_status'], 'on'); ?> class="widefat" id="<?php echo $this->get_field_id('scroller_activity_status'); ?>" />
    		</p>
		<?php
      } else {
        $this->dependency_message();
      }
    }
    function subscribe_form($m_title, $m_summary, $m_img, $privacy_policy, $button_color, $scroller_activity_status)
    {
        $settings = array(
            'connect_newsletter_id' => '',
            'connect_mailchimp_list_url' => '',
            'feed_url' => ''
        );
        $settings = woo_get_dynamic_values($settings);
        if ($settings['connect_mailchimp_list_url'] != "" && $settings['connect_newsletter_id'] == ""):?>
          <script type="text/javascript">
            gfb_subscribe.scrollManager(<?php
               if (is_user_logged_in()) {
                   echo "false";
               } else {
                   echo "true";
               }?>, <?php
                  if ($scroller_activity_status) {
                      echo "true";
                  } else {
                      echo "false";
                  }?>)
          </script>
          <div id="gfb_widget_overlay">
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
                  <div id="gfb_form_div">
                    <input id="gfb_subscribe_email_text" type="text" name="EMAIL" value="" placeholder="Enter your E-mail address" id="mce-EMAIL" style="border:1px solid #ccc;font-size:22px;font-family:'Open Sans', Arial, Helvetica, sans-serif;font-weight:100;min-width:60px;color:#000;margin-bottom:10px;height:47.5px;display:block;width:100%;border-radius:4px;font: -webkit-small-control;letter-spacing:normal;line-height: normal;text-align:start;text-indent:0;text-shadow: none;text-transform:none;word-spacing:normal">
                    <input type="hidden" id="gfb_connect_mailchimp_list_url" name="Language" value="<?php echo $settings['connect_mailchimp_list_url']; ?>">
                    <button name="subscribe" id="gfb_subscribe_button" class="btn submit button" type="button" onclick="gfb_subscribe.postData(document.getElementById('gfb_subscribe_email_text').value, document.getElementById('gfb_connect_mailchimp_list_url').value)" style="font-weight:bold;font-size:20px;cursor: pointer;height:47.5px;display:block;width:100%;background: -moz-linear-gradient(top, #ffa200 0%, #ff6800 100%);background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffa200), color-stop(100%,#ff6800));background: -webkit-linear-gradient(top, #ffa200 0%,#ff6800 100%);background: -o-linear-gradient(top, #ffa200 0%,#ff6800 100%);background: -ms-linear-gradient(top, #ffa200 0%,#ff6800 100%);background: linear-gradient(to bottom, #ffa200 0%,#ff6800 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffa200', endColorstr='#ff6800',GradientType=0 );box-shadow: inset 0px 1px 0px rgba(255,202,0,1), inset 0px -3px 0px rgba(0,0,0,0.12);border-radius:4px;background:  <?php
                      if ($button_color) {
                          echo $button_color;
                      } else {
                          echo '#512D8C';
                      }?> none repeat scroll 0%;" ><?php _e('Sign Up', 'woothemes');?></button>
                  </div>
               </center>
               <br/><br/>
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

    function dependency_message(){
      echo "<p style='color:red;'>This plugin (". $this->plugin_name.") requires a theme built with wootheme framework to work properly</p>";
    }
}
add_action('widgets_init', create_function('', 'return register_widget("GFB_MailChimp_Subscribe");'), 1);
?>
