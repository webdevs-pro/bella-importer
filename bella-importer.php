<?php
/**
 * Plugin Name: BellaCiao Importer
 * Description: BellaCiao importer plugin
 * Author: Alex Ischenko
 * Version: 0.2.4
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
      'jet-woo-product-gallery/jet-woo-product-gallery.php' => 'JetProductGallery For Elementor',
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
      $html = '';

      $environment = self::check_environment();
      $plugins = self::check_plugins();

      if ( $environment['status'] && $plugins['status'] ) {
         $html .= '<h2 class="bci-checks-status status-ok">Status: Ready for import</h2>';
         $status = true;
      } else {
         $html .= '<h2 class="bci-checks-status status-bad">Status: Not ready</h2>';
         $status = false;
      }

      $html .= '<h3>Environment:</h3>';
      foreach ( $environment['environment'] as $item ) {
         $html .=  '<div class="bci-check-item status-' . ( $item['status'] ? 'good' : 'bad' ) . '">';
            $html .=  $item['label'] . ': ' . $item['current'];
            if ( ! $item['status'] ) {
               $html .=  ' (minimum ' . $item['required'] . ' version required)';
            }
         $html .=  '</div>';
      }

      $html .= '<h3>Plugins:</h3>';
      foreach ( $plugins['plugins'] as $item ) {
         $html .=  '<div class="bci-check-item status-' . ( $item['status'] ? 'good' : 'bad' ) . '">';
            $html .=  $item['name'];
         $html .=  '</div>';
      }

      $html .= '<div class="bci-step-nav">';
         $html .= '<div class="button button-primary bci-show-step bci-show-step-2' . ( $status ? '' : ' disabled' ) . '">Continue</div>';
      $html .= '</div>';

      ob_start();
         echo '<details>';
            echo '<summary>Debug</summary>';
            echo '<pre>';
               print_r( $environment );
               print_r( $plugins );
            echo '</pre>';
         echo '</details>';
      $html .= ob_get_clean();

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
               'status' => true
            );
         } else {
            $plugins[] = array(
               'name' => $name,
               'status' => false
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
      $environment['php_version'] = array(
         'label' => 'PHP Version',
         'required' => self::$required_environment['php_version'],
         'current' => PHP_VERSION,
         'status' => version_compare( PHP_VERSION, self::$required_environment['php_version'], '>=' ),
      );
      $environment['wp_version'] = array(
         'label' => 'WordPress Version',
         'required' => self::$required_environment['wp_version'],
         'current' => get_bloginfo( 'version' ),
         'status' => version_compare( get_bloginfo( 'version' ), self::$required_environment['wp_version'], '>=' ),
      );

      return array(
         'environment' => $environment,
         'status' => $environment['php_version']['status'] && $environment['wp_version']['status'] ? true : false,
      );
   }






   
}
new BC_Importer();


