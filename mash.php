<?php
/**
* Plugin Name: Mash - Monetize, Earn, and Grow your Experiences w/ Bitcoin Lightning
* Plugin URI: https://github.com/getmash/wordpress-mash-plugin
* Description: Setup and configure a Mash Wallet on your wordpress site. Earn more in an entirely new and interactive way!
* Version: 1.3.3
* Author: Mash
* Author URI: https://www.getmash.com/
**/

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
  die;
}

/**
 * Load blocks
 */
require_once plugin_dir_path( __FILE__ ) . 'build/boosts/index.php';

/**
 * Load shortcodes
 * 
 */
foreach ( glob( plugin_dir_path( __FILE__ ) . "shortcodes/*.php" ) as $file ) {
  require_once $file;
}

/**
 * Configure Custom Gutenberg Block category
 */

function register_mash_block_category( $categories ) {
  $categories[] = array(
    'slug' => 'mash-blocks',
    'title' => 'Mash'
  );
  return $categories;
}

if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
  add_filter( 'block_categories_all', 'register_mash_block_category');
} else {
  add_filter( 'block_categories', 'register_mash_block_category');
}

/**
 * Mash Plugin configuration
 */

register_activation_hook(__FILE__, array( 'MASH_PLUGIN', 'mash_plugin_install' ));
add_action('plugins_loaded', array( 'MASH_PLUGIN', 'mash_plugin_updated' ));
add_action('admin_enqueue_scripts', array( 'MASH_PLUGIN', 'mash_enqueue_assets' ));
add_action('admin_menu', array( 'MASH_PLUGIN', 'create_menu' ));
add_action('wp_head', array( 'MASH_PLUGIN', 'mash_load_wallet' ));
add_action('wp_body_open', array( 'MASH_PLUGIN', 'mash_load_boosts' ));

add_action('wp_ajax_mash-request', array( 'MASH_PLUGIN', 'mash_request_handler' ));
add_action('wp_ajax_mash-save-boosts', array( 'MASH_PLUGIN', 'mash_save_boosts' ));

if (!class_exists("MASH_PLUGIN")) :
  
  class MASH_PLUGIN
  {

    public static $mash_db_version = "3";
    public static $mash_settings_table = "mash_settings";
    public static $mash_boosts_table = "mash_boost_settings";

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
      $charset_collate = $wpdb->get_charset_collate();

      // Mash wallet settings table
      $settings_table_name = $wpdb->prefix . self::$mash_settings_table;
      $settings_sql = "CREATE TABLE `{$settings_table_name}` (
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

      // Mash boosts settings table
      $boosts_table_name = $wpdb->prefix . self::$mash_boosts_table;
      $boosts_sql = "CREATE TABLE `{$boosts_table_name}` (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `display_on` enum('None', 'All','s_pages') NOT NULL DEFAULT 'All',
        `s_pages` MEDIUMTEXT DEFAULT NULL,
        `s_posts` MEDIUMTEXT DEFAULT NULL,
        `ex_pages` MEDIUMTEXT DEFAULT NULL,
        `ex_posts` MEDIUMTEXT DEFAULT NULL,
        `location` TINYTEXT NOT NULL,
        `variant` TINYTEXT NOT NULL,
        `icon` TINYTEXT NOT NULL,
        `last_revision_date` datetime DEFAULT NULL,
        `last_modified_by` varchar(300) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) $charset_collate";

      include_once ABSPATH . 'wp-admin/includes/upgrade.php';
      dbDelta( array( $settings_sql, $boosts_sql ) );
      
      add_option('mash_db_version', self::$mash_db_version);

      $settings = $wpdb->get_row( "SELECT * FROM $settings_table_name LIMIT 1" );

      if ( !$settings ) {
        $wpdb->insert(
          // Table Name
          $settings_table_name,
  
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

      $boost_settings = $wpdb->get_row( "SELECT * FROM $boosts_table_name LIMIT 1" );
      if ( !$boost_settings ) {
        $wpdb->insert(
          // Table Name
          $boosts_table_name,

          // Data
          array(
            'display_on' => 'None',
            'location' => 'bottom-center',
            'icon' => 'lightning',
            'variant' => 'colorized',
            's_pages' => wp_json_encode( [] ),
            's_posts' => wp_json_encode( [] ),
            'ex_pages' => wp_json_encode( [] ),
            'ex_posts' => wp_json_encode( [] ),
          )
        );
      }
    }

    /**
     * Function that runs when a plugin has been updated
     */
    public static function mash_plugin_updated() {
      if (get_option('mash_db_version') != self::$mash_db_version) {
        self::mash_plugin_install();
      }
      update_option('mash_db_version', self::$mash_db_version);
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
        array( 'MASH_PLUGIN', 'mash_settings_page' ),
        'dashicons-mash'
      );

       // This submenu is HIDDEN, however, we need to add it anyways
      add_submenu_page(
        null,
        'Mash Request Handler Script',
        'Mash Request Handler',
        'manage_options',
        'mash-request-handler',
        array( 'MASH_PLUGIN', 'mash_request_handler')
      );

      add_submenu_page(
        null,
        'Mash Boosts Save Handler Script',
        'Mash Boosts Save Handler',
        'manage_options',
        'mash-save-boosts',
        array( 'MASH_PLUGIN', 'mash_save_boosts')
      );
    }

    /**
     * Handles settings up the Mash settings pages and core variables
     */
    public static function mash_settings_page()
    {

      current_user_can('administrator');

      global $wpdb;

      // Retrieve and set variables that will be used in mash_settings.php for wallet settings
      $settings_table_name = $wpdb->prefix . self::$mash_settings_table;
      $settings = $wpdb->get_row( "SELECT * FROM $settings_table_name LIMIT 1" );

      $settings_earner_id  = $settings->earner_id;
      $settings_display_on = $settings->display_on;
      $settings_s_pages    = json_decode($settings->s_pages);
      $settings_s_posts    = json_decode($settings->s_posts);
      $settings_ex_pages   = json_decode($settings->ex_pages);
      $settings_ex_posts   = json_decode($settings->ex_posts);


      // Retrieve and set variables that will be used in mash_settings.php for boosts
      $boosts_table_name = $wpdb->prefix . self::$mash_boosts_table;
      $boost_settings = $wpdb->get_row( "SELECT * FROM $boosts_table_name LIMIT 1" );

      $boosts_display_on = $boost_settings->display_on;
      $boosts_s_pages    = json_decode($boost_settings->s_pages);
      $boosts_s_posts    = json_decode($boost_settings->s_posts);
      $boosts_ex_pages   = json_decode($boost_settings->ex_pages);
      $boosts_ex_posts   = json_decode($boost_settings->ex_posts);
      $boosts_location   = $boost_settings->location;
      $boosts_variant    = $boost_settings->variant;
      $boosts_icon       = $boost_settings->icon;

      include_once plugin_dir_path( __FILE__ ) . 'includes/mash_settings.php';
    }

    /**
     * Loads assets needed for the mash settings page
     */
    public static function mash_enqueue_assets( $hook ) {
      wp_register_style('mash_general_admin_assets', plugins_url('css/style-general-admin.css', __FILE__), array(), true);
      wp_enqueue_style('mash_general_admin_assets');

      wp_register_style('selectize-css', plugins_url('css/selectize.bootstrap.css', __FILE__), array(), true);
      wp_enqueue_style('selectize-css');

      wp_register_script('selectize-js', plugins_url('js/selectize.min.js', __FILE__), array( 'jquery' ), true);
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
      $table_name = $wpdb->prefix . self::$mash_settings_table;

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


      // Boosts should only show up on pages with Wallet enabled,
      // Filter existing boosts table to match what is changing in the wallet
      $boosts_table_name = $wpdb->prefix . self::$mash_boosts_table;
      $boosts            = $wpdb->get_row( "SELECT * FROM $boosts_table_name LIMIT 1" );
      $next_boosts_s_pages = json_decode($boosts->s_pages);
      $next_boosts_s_posts = json_decode($boosts->s_posts);
      if ($boosts->display_on === 's_pages') {

        if ($display_on === 'All') {
          // Remove excluded pages from currently selected pages
          $next_boosts_s_pages  = array_diff($next_boosts_s_pages, $ex_pages);
          $next_boosts_s_posts  = array_diff($next_boosts_s_posts, $ex_posts);
        } else if ($display_on === 's_pages') {

          // Keep only selected pages
          $next_boosts_s_pages  = array_intersect($next_boosts_s_pages, $s_pages);
          $next_boosts_s_posts  = array_intersect($next_boosts_s_posts, $s_posts);
        }
      } 

      // Updated Boosts table
      $wpdb->query(
        $wpdb->prepare(
          "UPDATE $boosts_table_name 
          SET `s_pages` = %s, 
          `s_posts` = %s, 
          `last_revision_date` = %s,
          `last_modified_by` = %s",
          wp_json_encode(array_values($next_boosts_s_pages)), 
          wp_json_encode(array_values($next_boosts_s_posts)),
          current_time('Y-m-d H:i:s'),
          sanitize_text_field($current_user->display_name),
        )
      );

      self::mash_redirect(admin_url('admin.php?page=mash-wallet-settings'));
    }

    public static function mash_save_boosts() {

      current_user_can('administrator');

      $boosts_display_on = self::mash_sanitize_text('display_on');
      $boosts_s_pages    = self::mash_sanitize_array('s_pages');
      $boosts_s_posts    = self::mash_sanitize_array('s_posts');
      $boosts_ex_pages   = self::mash_sanitize_array('ex_pages');
      $boosts_ex_posts   = self::mash_sanitize_array('ex_posts');
      $boosts_location   = self::mash_sanitize_text('location');
      $boosts_variant    = self::mash_sanitize_text('variant');
      $boosts_icon       = self::mash_sanitize_text('icon');

      global $wpdb;
      global $current_user;
      $table_name = $wpdb->prefix . self::$mash_boosts_table;

      if ($boosts_display_on === 'None') {
        $boosts_s_pages = [];
        $boosts_s_posts = [];
        $boosts_ex_pages = [];
        $boosts_ex_posts = [];
      } else if ($boosts_display_on === 'All') {
        $boosts_s_pages = [];
        $boosts_s_posts = [];
      } else if ($boosts_display_on === 's_pages') {
        $boosts_ex_pages = [];
        $boosts_ex_posts = [];
      }

      $wpdb->query(
        $wpdb->prepare(
          "UPDATE $table_name 
          SET `display_on` = %s, 
          `s_pages` = %s, 
          `s_posts` = %s, 
          `ex_pages` = %s, 
          `ex_posts` = %s,
          `location` = %s,
          `variant` = %s,
          `icon` = %s,
          `last_revision_date` = %s,
          `last_modified_by` = %s",
          $boosts_display_on, 
          wp_json_encode( $boosts_s_pages ), 
          wp_json_encode( $boosts_s_posts ), 
          wp_json_encode( $boosts_ex_pages ), 
          wp_json_encode( $boosts_ex_posts ),
          $boosts_location,
          $boosts_variant,
          $boosts_icon,
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
      $table_name = $wpdb->prefix . self::$mash_settings_table;

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

    public static function mash_load_boosts() {

      global $wpdb;
      $boosts_table_name = $wpdb->prefix . self::$mash_boosts_table;
      $boosts   = $wpdb->get_row( "SELECT * FROM $boosts_table_name LIMIT 1" );

      $settings_table_name = $wpdb->prefix . self::$mash_settings_table;
      $settings = $wpdb->get_row( "SELECT * FROM $settings_table_name LIMIT 1" );

      $out = '';

      if (self::is_mash_on_site($settings)) {

        $post_type = get_post_type();
        $id = get_queried_object_id();

        switch ($boosts->display_on) {
          case 'All':
            $arr = $post_type === 'page' ? json_decode($boosts->ex_pages) : json_decode($boosts->ex_posts);
            $is_arr_not_empty = self::mash_not_empty($arr);
            if (!$is_arr_not_empty || !in_array($id, $arr)) {
              $out = self::mash_render_boost($boosts->location, $boosts->variant, $boosts->icon);
            }
            break;
          case 's_pages':
            $arr = $post_type === 'page' ? json_decode($boosts->s_pages) : json_decode($boosts->s_posts);
            $is_arr_not_empty = self::mash_not_empty($arr);
            if ($is_arr_not_empty && in_array($id, $arr)) {
              $out = self::mash_render_boost($boosts->location, $boosts->variant, $boosts->icon);
            }
            break;
        }
      }
        
      echo $out;  
    }

    public static function is_mash_on_site($settings) {
      $post_type = get_post_type();
      $id = get_queried_object_id();
      $wallet_arr = 'page' ? json_decode($settings->s_pages) : json_decode($settings->s_post);

      switch ($settings->display_on) {
        case 'All':
          if (empty($wallet_arr) || !in_array($id, $wallet_arr)) {
            return true;
          }
          break;
        case 's_pages':
          if (!empty($wallet_arr) && in_array($id, $wallet_arr)) {
            return true;
          }
          break;
      }

      return false;
    }

    public static function mash_render_boost($location, $variant, $icon) {
      $output = '<script defer src="https://components.getmash.com/boost/boost.js"></script>';
      $output .= '<mash-boost-button ';
      $output .= 'icon="' . esc_attr( $icon ) . '" ';
      $output .= 'layout-mode="float"';
      $output .= 'variant="' . esc_attr( $variant ) . '" ';
      $output .= 'float-location="' . esc_attr( $location ) . '" ';
      $output .= '></mash-boost-button>';
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
