<?php
/**
* Plugin Name: Mash - Monetize, Earn, and Grow your Experiences w/ Bitcoin Lightning
* Plugin URI: https://github.com/getmash/wordpress-mash-plugin
* Description: Setup and configure Mash on your wordpress site.
* Version: 1.0.0
* Author: Mash
* Author URI: https://www.getmash.com/
**/

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
  die;
}

register_activation_hook(__FILE__, array( 'MASH_WALLET', 'mash_plugin_install' ));
add_action('admin_enqueue_scripts', array( 'MASH_WALLET', 'mash_enqueue_assets' ));
add_action('admin_menu', array( 'MASH_WALLET', 'create_menu' ));
add_action('wp_head', array( 'MASH_WALLET', 'mash_load_wallet' ) );

add_action('wp_ajax_mash-request', array( 'MASH_WALLET', 'mash_request_handler' ));

if (!class_exists("MASH_WALLET")) :
  
  class MASH_WALLET
  {

    public static $mash_db_version = "1";
    public static $mash_table = "mash_settings";

    /**
     * Setup plugin (db, options, defaults)
     */
    public static function mash_plugin_install()
    {
      $activation_time = strtotime("now");
      add_option('mash_activation_date', $activation_time);
      update_option('mash_activation_date', $activation_time);

      // wordpress db
      global $wpdb;

      $table_name       = $wpdb->prefix . self::$mash_table;
      $charset_collate  = $wpdb->get_charset_collate();
      $sql              = "CREATE TABLE `{$table_name}` (
        `earner_id` varchar(36) DEFAULT '<earner_id>',
        `display_on` enum('All','s_pages') NOT NULL DEFAULT 'All',
        `s_pages` MEDIUMTEXT DEFAULT NULL,
        `s_posts` MEDIUMTEXT DEFAULT NULL,
        `ex_pages` MEDIUMTEXT DEFAULT NULL,
        `ex_posts` MEDIUMTEXT DEFAULT NULL,
        `last_revision_date` datetime DEFAULT NULL,
        `last_modified_by` varchar(300) DEFAULT NULL,
        PRIMARY KEY (`earner_id`)
      ) $charset_collate";

      include_once ABSPATH . 'wp-admin/includes/upgrade.php';
      dbDelta($sql);
      add_option('mash_db_version', self::$mash_db_version);

      $settings = $wpdb->get_row( "SELECT * FROM $table_name LIMIT 1" );

      if ( !$settings) {
        $wpdb->insert(
          // Table
          $table_name,
  
          // Data
          array(
            'earner_id' => '<earner_id>',
            's_pages' => wp_json_encode( [] ),
            's_posts' => wp_json_encode( [] ),
            'ex_pages' => wp_json_encode( [] ),
            'ex_posts' => wp_json_encode( [] ),
          )
        );
      }
    }

    /**
     * Function to create menu page, and submenu pages.
     */
    public static function create_menu()
    {
      add_menu_page(
        'Mash Wallet Settings',
        'Mash',
        'manage_options',
        'mash-wallet-settings',
        array( 'MASH_WALLET', 'mash_settings_page' ),
        'dashicons-mash'
      );

       // This submenu is HIDDEN, however, we need to add it anyways
      add_submenu_page(
        null,
        'Mash Request Handler Script',
        'Mash Request Handler',
        'manage_options',
        'mash-request-handler',
        array( 'MASH_WALLET', 'mash_request_handler')
      );
    }

    /**
     * Handles settings up the Mash settings pages and core variables
     */
    public static function mash_settings_page()
    {

      current_user_can('administrator');

      global $wpdb;
      $table_name = $wpdb->prefix . self::$mash_table;

      $settings = $wpdb->get_row( "SELECT * FROM $table_name LIMIT 1" );

      $earner_id  = $settings->earner_id;
      $display_on = $settings->display_on;
      $s_pages    = json_decode($settings->s_pages);
      $s_posts    = json_decode($settings->s_posts);
      $ex_pages   = json_decode($settings->ex_pages);
      $ex_posts   = json_decode($settings->ex_posts);

      include_once plugin_dir_path( __FILE__ ) . 'includes/mash_settings.php';
    }

    /**
     * Loads assets needed for the mash settings page
     */
    public static function mash_enqueue_assets( $hook ) {
      wp_register_style('mash_general_admin_assets', plugins_url('css/style-general-admin.css', __FILE__));
      wp_enqueue_style('mash_general_admin_assets');

      wp_register_style('selectize-css', plugins_url('css/selectize.bootstrap.css', __FILE__));
      wp_enqueue_style('selectize-css');

      wp_register_script('selectize-js', plugins_url('js/selectize.min.js', __FILE__), array( 'jquery' ));
      wp_enqueue_script('selectize-js');
    }

    /**
     * Handles save request from form
     */
    public static function mash_request_handler()
    {
      current_user_can('administrator');

      $earner_id   = self::mash_sanitize_text('earner_id');
      $display_on  = self::mash_sanitize_text('display_on');
      $s_pages     = self::mash_sanitize_array('s_pages');
      $s_posts     = self::mash_sanitize_array('s_posts');
      $ex_pages    = self::mash_sanitize_array('ex_pages');
      $ex_posts    = self::mash_sanitize_array('ex_posts');
      
      global $wpdb;
      global $current_user;
      $table_name = $wpdb->prefix . self::$mash_table;

      if ($display_on === 'All') {
        $s_pages = [];
        $s_posts = [];
      } else if ($display_on === 's_pages') {
        $ex_pages = [];
        $ex_posts = [];
      }

      $wpdb->query(
        $wpdb->prepare(
          "UPDATE $table_name 
          SET `earner_id` = %s, 
          `display_on` = %s, 
          `s_pages` = %s, 
          `s_posts` = %s, 
          `ex_pages` = %s, 
          `ex_posts` = %s,
          `last_revision_date` = %s,
          `last_modified_by` = %s",
          $earner_id, 
          $display_on, 
          wp_json_encode( $s_pages ), 
          wp_json_encode( $s_posts ), 
          wp_json_encode( $ex_pages ), 
          wp_json_encode( $ex_posts ),
          current_time('Y-m-d H:i:s'),
          sanitize_text_field($current_user->display_name),
        )
      );

      self::mash_redirect(admin_url('admin.php?page=mash-wallet-settings'));
    }

    /**
     * Handles redirecting the user based on the url
     */
    public static function mash_redirect( $url = '' )
    {
      // Register the script
      wp_register_script('mash_redirection', plugins_url('js/locations.js', __FILE__));

      // Localize the script with new data
      $translation_array = array( 'url' => $url );
      wp_localize_script('mash_redirection', 'mash_location', $translation_array);
   
      wp_enqueue_script('mash_redirection');
    }

    /*
    * function to sanitize POST data
    */
    public static function mash_sanitize_text( $key )
    {
      if (!empty($_POST['data'][$key]) ) {
          return sanitize_text_field( $_POST['data'][$key] );
      }
      return '';
    }

    /*
    * function to sanitize strings within POST data arrays
    */
    public static function mash_sanitize_array( $key, $type = 'integer' )
    {
      if (!empty($_POST['data'][$key]) ) {
          if (!is_array($_POST['data'][$key]) ) {
              return array();
          }
          if ('integer' === $type ) {
              return array_map( 'absint', $_POST['data'][$key] );
          }
          return array_map( 'sanitize_text_field',  $_POST['data'][$key] );
      }
      return array();
    }

    /**
     * Loads the mash wallet on the correct pages based on configuration
     * This is triggered by the wordpress "head" hook
     */
    public static function mash_load_wallet()
    {
      global $wpdb;
      $table_name = $wpdb->prefix . self::$mash_table;

      $out = '';

      $settings   = $wpdb->get_row( "SELECT * FROM $table_name LIMIT 1" );
      $earner_id  = $settings->earner_id;
      $s_pages    = json_decode($settings->s_pages);
      $s_posts    = json_decode($settings->s_posts);
      $ex_pages    = json_decode($settings->ex_pages);
      $ex_posts    = json_decode($settings->ex_posts);

      switch ( $settings->display_on ) {
      case 'All': 
        if ( get_post_type() === 'post' ) {
          $id = get_queried_object_id();
          if (!in_array($id, $ex_posts)) {
            $out = self::mash_render_script($earner_id);
          }
        } else if ( get_post_type() === 'page' ) {
          $id = get_queried_object_id();
          if (!in_array($id, $ex_pages)) {
            $out = self::mash_render_script($earner_id);
          }
        }   
        break;
      case 's_pages':
        if ( get_post_type() === 'post' ) {
          $is_not_empty_s_posts = self::mash_not_empty( $s_posts );
          if ( $is_not_empty_s_posts ) {
            $id = get_queried_object_id();
            if (in_array($id, $s_posts) ) {
              $out = self::mash_render_script($earner_id);
            }
          }
        } else if ( get_post_type() === 'page' ) {
          $is_not_empty_s_pages = self::mash_not_empty( $s_pages ); 
          if ( $is_not_empty_s_pages ) {
            // Gets the page ID of the blog page
            $page_id = get_queried_object_id();
            if (in_array($page_id, $s_pages) ) {
              $out = self::mash_render_script($earner_id);
            }
          }
        }
        break;
      }

      echo $out;
    }

    public static function mash_render_script( $earner_id ) {
      $output = '
        <script>
          window.MashSettings = {
            id: "' . esc_html($earner_id) . '"
          };
          var loader = function () {
            window.Mash.init(window.MashSettings);
          };
          var script = document.createElement("script");
          script.type = "text/javascript";
          script.async = true;
          script.onload = loader;
          script.src = "https://wallet.getmash.com/sdk/sdk.js";
          var head = document.getElementsByTagName("head")[0];
          head.appendChild(script);
        </script>
      ';
      return $output;
    }

    /**
     * Check if array is empty
     */
    public static function mash_not_empty( $arr ) {
      if (empty($arr) ) {
          return false;
      }
      return true;
    }
  }

endif;
