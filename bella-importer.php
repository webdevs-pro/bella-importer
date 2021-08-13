<?php
/**
 * Plugin Name: BellaCiao Importer
 * Description: BellaCiao importer plugin
 * Author: Alex Ischenko
 * Version: 0.7
 */

define( 'BCI_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'BCI_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

require_once ( BCI_PLUGIN_DIR . '/admin/admin.php' );


class BC_Importer {

   public static $required_plugins = array(
      'elementor-pro/elementor-pro.php',
      'elementor/elementor.php',
      'jet-blocks/jet-blocks.php',
      'jet-compare-wishlist/jet-cw.php',
      'jet-elements/jet-elements.php',
      'jet-engine/jet-engine.php',
      'jet-menu/jet-menu.php',
      'jet-reviews/jet-reviews.php',
      'jet-search/jet-search.php',
      'jet-smart-filters/jet-smart-filters.php',
      'jet-woo-builder/jet-woo-builder.php',
      'jet-woo-product-gallery/jet-woo-product-gallery.php',
      'mae-tabs/mae-tabs.php',
      'mae-taxonomy-megamenu/mae-taxonomy-megamenu.php',
      'magnific-addons-for-elementor/magnific-addons-for-elementor.php',
      'woocommerce/woocommerce.php',
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


   public static function check_environment() {
      $check = array();

      $plugins_check = self::check_plugins();

      $check['status'] = 'OK';

      return $check;
   }


   public static function check_plugins() {
      $check = array();
      $active_plugins = get_option( 'active_plugins' ); 


      $arr3 = array_diff( self::$required_plugins, $active_plugins );
      error_log( "arr3\n" . print_r($arr3, true) . "\n" );
      if (count($arr3) == 0) {
         // all of $arr1 is in $arr2
      }

      foreach ( self::$required_plugins as $plugin ) {

      }

      error_log( "plugins\n" . print_r($plugins, true) . "\n" );

      return $check;
   }



}
new BC_Importer();


