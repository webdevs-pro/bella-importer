<?php
/**
 * Plugin Name: BellaCiao Importer
   * Description: BellaCiao importer plugin
   * Author: Alex Ischenko
   * Version: 0.4
   */

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

class Bella_Importer {

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
      echo '<b>BellaCiao:</b> Elementor templates display conditions updated';
      echo '<br>';
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

         echo '<b>BellaCiao:</b> Elementor Kit updated';
         echo '<br>';
      }
   }
   



   
   public function update_jcw_settings() {
      $compare_page = get_page_by_path( 'compare' );
      $wishlist_page = get_page_by_path( 'wishlist' );

      $settings = array (
         array (
           'jet-compare-button' => 'true',
           'jet-compare-count-button' => 'true',
           'jet-compare-widget' => 'true',
           'jet-wishlist-button' => 'true',
           'jet-wishlist-count-button' => 'true',
           'jet-wishlist-widget' => 'true',
         ),
         'enable_compare' => 'true',
         'save_user_compare_list' => 'true',
         'compare_page' => $compare_page->ID ?? '',
         'compare_page_max_items' => '3',
         'add_default_compare_button' => 'true',
         'enable_wishlist' => 'true',
         'save_user_wish_list' => 'false',
         'wishlist_page' => $wishlist_page->ID ?? '',
         'add_default_wishlist_button' => 'false',
      );

      update_option( 'jet-cw-settings', $settings );
      
      echo '<b>BellaCiao:</b> JetCompareWishlist settings updated';
      echo 'Compare page ID: ' . $compare_page->ID ?? 'NOT EXIST, Please set proper Compare page <a href="' . get_admin_url() . 'admin.php?page=jet-dashboard-settings-page&subpage=jet-cw-compare-settings">here</a>';
      echo 'Compare page ID: ' . $compare_page->ID ?? 'NOT EXIST, Please set proper Wishlist page <a href="' . get_admin_url() . 'admin.php?page=jet-dashboard-settings-page&subpage=jet-cw-wishlist-settings">here</a>';
      echo '<br>';
   }
   



   
}
new Bella_Importer();


