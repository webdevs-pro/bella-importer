<?php
/**
 * Plugin Name: BellaCiao Importer
   * Description: BellaCiao importer plugin
   * Author: Alex Ischenko
   * Version: 0.5
   */

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

class Bella_Importer {

   const COMPARE_PAGE_SLUG = 'compare';
   const WISHLIST_PAGE_SLUG = 'wishlist';

   public $posts;

   public function __construct() {
      add_action( 'init', array( $this, 'init_update_conditions' ) );
      add_filter( 'wp_import_posts', array( $this, 'filter_imported_posts' ), 10 );
      add_action( 'import_end', array( $this, 'import_end' ), 10 );
   }
   




   public function init_update_conditions() {
      $update = get_option('my_option');
      if ( get_option( 'bella_importer_update_condition' ) == 'update' ){
         $this->update_templates_conditions();
         delete_option( 'bella_importer_update_condition' );
      }
   }
   



   
   public function filter_imported_posts( $posts ) {
      $this->posts = $posts;
      // error_log( "posts\n" . print_r($posts, true) . "\n" );
      return $posts;
   }
   



   
   public function import_end() {

      echo '<script>window.scrollTo( 0,document.body.scrollHeight );</script>';
      // 1. save elementor templates display condition 
      $this->update_templates_conditions(); 
      update_option( 'bella_importer_update_condition', 'update' );

      // 2. import elementor kit data (fonts, colors, custom CSS, etc.)
      $this->set_kit_data();

      // 3. Update JetCompareWishlist plugin settings
      $this->update_jcw_settings();
   }
   



   
   public function update_templates_conditions() {
      $cache = new ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Cache();
      $cache->regenerate();
      echo '<b>BellaCiao:</b> Elementor templates display conditions updated.<br>';
   }
   



   
   public function set_kit_data() {
      $new_settings = '';
      foreach ( $this->posts as $post ) {
         if ( $post['post_type'] == 'elementor_library' ) {
            $post_meta = array_column( $post['postmeta'], 'value', 'key' );
            if ( $post_meta['_elementor_template_type'] == 'kit' ) {
               $new_settings = unserialize( $post_meta['_elementor_page_settings'] );
               break;
            }
         }
      }
      if ( $new_settings ) {
         $kit = Elementor\Plugin::$instance->kits_manager->get_active_kit();
         $old_settings = $kit->get_meta( Elementor\Core\Settings\Page\Manager::META_KEY );
         
         if ( ! $old_settings ) {
            $old_settings = [];
         }
         
         if ( ! empty( $old_settings['custom_colors'] ) ) {
            $new_settings['custom_colors'] = array_merge( $old_settings['custom_colors'], $new_settings['custom_colors'] );
         }
         
         if ( ! empty( $old_settings['custom_typography'] ) ) {
            $new_settings['custom_typography'] = array_merge( $old_settings['custom_typography'], $new_settings['custom_typography'] );
         }
         
         $new_settings = array_replace_recursive( $old_settings, $new_settings );
         $new_settings['template'] = 'default';

         // $content = print_r( $new_settings, true);
         // file_put_contents( $_SERVER['DOCUMENT_ROOT'] . 'bella.txt', print_r( $content, true) );

         $kit->save( [ 'settings' => $new_settings ] );

         echo '<b>BellaCiao:</b> Elementor Kit updated.<br>';
      }
   }
   



   
   public function update_jcw_settings() {
      $compare_page = get_page_by_path( self::COMPARE_PAGE_SLUG );
      $wishlist_page = get_page_by_path( self::WISHLIST_PAGE_SLUG );

      // reqiured settings to correct import templates
      $settings = array (
         array ( // enabled widgets
           'jet-compare-button' => 'true',
           'jet-compare-count-button' => 'true',
           'jet-compare-widget' => 'true',
           'jet-wishlist-button' => 'true',
           'jet-wishlist-count-button' => 'true',
           'jet-wishlist-widget' => 'true',
         ),
         'enable_compare' => 'true',
         'compare_page' => $compare_page->ID ?? '',
         'compare_page_max_items' => '3',
         'enable_wishlist' => 'true',
         'wishlist_page' => $wishlist_page->ID ?? '',
      );

      update_option( 'jet-cw-settings', $settings );
      
      echo '<b>BellaCiao:</b> JetCompareWishlist settings updated.<br>';
      echo '&nbsp;&nbsp;&nbsp;Compare page ID: ' . ( isset( $compare_page->ID ) ? $compare_page->ID : 'NOT EXIST, Please set Compare page <a target="_blank" href="' . get_admin_url() . 'admin.php?page=jet-dashboard-settings-page&subpage=jet-cw-compare-settings">here</a>' ) . '<br>';
      echo '&nbsp;&nbsp;&nbsp;Wishlist page ID: ' . ( isset( $wishlist_page->ID ) ? $wishlist_page->ID : 'NOT EXIST, Please set Wishlist page <a target="_blank" href="' . get_admin_url() . 'admin.php?page=jet-dashboard-settings-page&subpage=jet-cw-wishlist-settings">here</a>' ) . '<br>';
   }
   



   
}
new Bella_Importer();


