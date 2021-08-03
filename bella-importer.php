<?php
/**
 * Plugin Name: BellaCiao Importer
 * Description: BellaCiao importer plugin
 * Author: Alex Ischenko
 * Version: 0.2
 */




// // include files
// require_once ( dirname( __FILE__ ) . '/inc/class-bella-wxr-parser-regex.php' );
// require_once ( dirname( __FILE__ ) . '/inc/class-bella-wxr-parser-simplexml.php' );
// require_once ( dirname( __FILE__ ) . '/inc/class-bella-wxr-parser-xml.php' );
// require_once ( dirname( __FILE__ ) . '/inc/class-bella-wxr-parser.php' );
// require_once ( dirname( __FILE__ ) . '/admin/admin.php' );



// add_action( 'init', function() {

//    if (isset($_FILES['bella_importer_uload_file']) && ($_FILES['bella_importer_uload_file']['error'] == UPLOAD_ERR_OK)) {

//       $parser = new WXR_Bella_Parser();
//       $data = $parser->parse( $_FILES['bella_importer_uload_file']['tmp_name'] );
//       foreach ( $data['posts'] as $post ) {
//          if ( $post['post_type'] == 'nav_menu_item' ) {
//             error_log( "post\n" . print_r($post, true) . "\n" );
//          }
//       }

//    }

// });



// add_action( 'import_end', 'bella_setup_templates', 10 );
// function bella_setup_templates() {

// }


// add_action( 'wp_import_insert_post', 'bella_post_import', 10, 4 );
// function bella_post_import( $post_id, $original_post_ID, $postdata, $post ) {
//    error_log( "postdata\n" . print_r($postdata, true) . "\n" );

// }


// add_filter( 'wp_import_posts', 'bella_filter_imported_posts', 10 );
// function bella_filter_imported_posts( $posts ) {

//    error_log( 'wp_import_posts' );


//    add_action('import_end', function() use ( &$posts ) { 
//       bella_process_imported_posts( $posts );
//    }, 10 );

//    return $posts;
// }

// function bella_process_imported_posts( $posts ) {
//    bella_update_templates_conditions();
//    update_option( 'bella_importer_update_condition', 'update' );
// }

// add_action('init', function() {
//    $update = get_option('my_option');
//    if ( get_option( 'bella_importer_update_condition' ) == 'update' ){
//       bella_update_templates_conditions();
//       delete_option( 'bella_importer_update_condition' );
//    }
// } );

//  function bella_update_templates_conditions() {
//    $cache = new \ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Cache();
//    $cache->regenerate();
//  }





 class Bella_Importer {

   public $posts;

   public function __construct() {
      add_action( 'init', [ $this, 'init_update_conditions' ] );
      add_filter( 'wp_import_posts', [ $this, 'filter_imported_posts' ], 10 );
      add_action( 'import_end', [ $this, 'import_end' ], 10 );
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
      $this->update_templates_conditions();
      update_option( 'bella_importer_update_condition', 'update' );

      $this->set_kit_data();
   }

   public function update_templates_conditions() {
      $cache = new ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Cache();
      $cache->regenerate();
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

         error_log( "old_settings bella\n" . print_r($old_settings, true) . "\n" );
         
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
      }
   }

 }
 new Bella_Importer();


