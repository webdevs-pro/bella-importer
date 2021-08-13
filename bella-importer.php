<?php
/**
 * Plugin Name: BellaCiao Importer
 * Description: BellaCiao importer plugin
 * Author: Alex Ischenko
 * Version: 0.2.1
 */

define( 'BCI_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'BCI_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

require_once ( BCI_PLUGIN_DIR . '/admin/admin.php' );


class BC_Importer {

   public static $required_plugins = array(
      'elementor-pro/elementor-pro.php' => 'Elementor Pro',
      'elementor/elementor.php' => 'Elementor',
      'jet-blocks/jet-blocks.php' => 'JetBlocks For Elementor',
      'jet-compare-wishlist/jet-cw.php' => 'JetCompareWishlist For Elementor',
      'jet-elements/jet-elements.php' => 'JetElements For Elementor',
      'jet-engine/jet-engine.php' => 'JetEngine',
      'jet-menu/jet-menu.php' => 'JetMenu',
      'jet-reviews/jet-reviews.php' => 'JetReviews For Elementor',
      'jet-search/jet-search.php' => 'JetSearch For Elementor',
      'jet-smart-filters/jet-smart-filters.php' => 'JetSmartFilters',
      'jet-woo-builder/jet-woo-builder.php' => 'JetWooBuilder For Elementor',
      'jet-woo-product-gallery/jet-woo-product-gallery.php' => '',
      'mae-tabs/mae-tabs.php' => 'MAE Tabs',
      'mae-taxonomy-megamenu/mae-taxonomy-megamenu.php' => 'MAE Taxonomy Megamenu',
      'magnific-addons-for-elementor/magnific-addons-for-elementor.php' => 'Magnific Addons for Elementor',
      'woocommerce/woocommerce.php' => 'WooCommerce',
   );

   public static $required_environment = array(
      'wp_version' => '5.8',
      'php_version' => '7.3',
   );

   const bella_theme_slug = 'bellaciao-theme';


   public function __construct() {
      if ( get_option( 'stylesheet' ) == self::bella_theme_slug ) {
         new BCI_Admin();
      } else {
         $this->show_no_theme_notice();
      }


   }






   
   public function show_no_theme_notice() {
      add_action( 'admin_notices', function() {
         ?>
         <div class="notice notice-error is-dismissible">
            <p><b>Bella Ciao importer:</b> Bella Ciao theme not installed/activated</p>
         </div>
         <?php
      } );
   }






   
   public static function before_import_checks() {
      $response = array();
      $html = '';

      $plugins = self::check_plugins();
      $environment = self::check_environment();

error_log( "environment\n" . print_r($environment, true) . "\n" );
print_r( $environment );


      $check['status'] = 'OK';

      return array(
         'html' => $html,
         'status' => $status
      );
   }







   public static function check_plugins() {
      $plugins = array();
      $active_plugins = get_option( 'active_plugins' );
      $status = true;
      foreach ( self::$required_plugins as $slug => $name ) {
         if ( in_array( $slug, $active_plugins ) ) {
            $plugins[] = array(
               'name' => $name,
               'status' => 'active'
            );
         } else {
            $plugins[] = array(
               'name' => $name,
               'status' => 'inactive'
            );
            $status = false;
         }
      }
      return array(
         'plugins' => $plugins,
         'status' => $status
      );
   }







   public static function check_environment() {
      $environment = array();
      $status = true;
      if ( version_compare( PHP_VERSION, self::$required_environment['php_version'], '>=' ) ) {
         $environment['php_version'] = array(
            'required' => self::$required_environment['php_version'],
            'version' => PHP_VERSION,
            'status' => true,
         );
      } else {
         $environment['php_version'] = array(
            'required' => self::$required_environment['php_version'],
            'version' => PHP_VERSION,
            'status' => false,
         );
         $status = false;
      }
      if ( version_compare( get_bloginfo( 'version' ), self::$required_environment['wp_version'], '>=' ) ) {
         $environment['wp_version'] = array(
            'required' => self::$required_environment['wp_version'],
            'version' => get_bloginfo( 'version' ),
            'status' => true,
         );
      } else {
         $environment['wp_version'] = array(
            'required' => self::$required_environment['wp_version'],
            'version' => get_bloginfo( 'version' ),
            'status' => false,
         );
         $status = false;
      }
      return array(
         'environment' => $environment,
         'status' => $status
      );
   }






   
}
new BC_Importer();


