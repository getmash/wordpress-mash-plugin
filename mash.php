<?php
/**
* Plugin Name: Mash - Monetize, Earn, and Grow your Experiences w/ Bitcoin Lightning
* Plugin URI: https://github.com/getmash/wordpress-mash-plugin
* Description: Setup and configure a Mash Wallet on your wordpress site. Earn more in an entirely new and interactive way!
* Version: 2.0.0
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
require_once plugin_dir_path( __FILE__ ) . 'build/boost/index.php';
require_once plugin_dir_path( __FILE__ ) . 'build/paywall/index.php';

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
add_action('wp_ajax_mash-request', array( 'MASH_PLUGIN', 'mash_request_handler' ));

if (!class_exists("MASH_PLUGIN")) :
  
  class MASH_PLUGIN
  {

    public static $mash_db_version = "4";
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
        `earner_id` varchar(36) DEFAULT '',
        `last_revision_date` datetime DEFAULT NULL,
        `last_modified_by` varchar(300) DEFAULT NULL,
        PRIMARY KEY (`earner_id`)
      ) $charset_collate";


      $boost_table_name = $wpdb->prefix . self::$mash_boosts_table;
      $delete_boosts_sql = "DROP TABLE IF EXISTS `{$boost_table_name}`";

      include_once ABSPATH . 'wp-admin/includes/upgrade.php';
      dbDelta( array( $settings_sql, $delete_boosts_sql ) );
      
      add_option('mash_db_version', self::$mash_db_version);

      $settings = $wpdb->get_row( "SELECT * FROM $settings_table_name LIMIT 1" );

      if ( !$settings ) {
        $wpdb->insert(
          // Table Name
          $settings_table_name,
  
          // Data
          array(
            'earner_id' => '',
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
      
      global $wpdb;
      global $current_user;
      $table_name = $wpdb->prefix . self::$mash_settings_table;

      $wpdb->query(
        $wpdb->prepare(
          "UPDATE $table_name 
          SET `earner_id` = %s, 
          `last_revision_date` = %s,
          `last_modified_by` = %s",
          $earner_id, 
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

      if ($earner_id != '<earner_id>' && $earner_id != '') {
        $out = self::mash_render_script($earner_id);
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
          window.Mash.init();
        };
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.defer = true;
        script.onload = loader;
        script.src = "https://wallet.getmash.com/sdk/sdk.js";
        var head = document.getElementsByTagName("head")[0];
        head.appendChild(script);
      </script>
      ' . "<!--" . is_home() . "-->";
      return $output;
    }

  }

endif;
