<?php
/**
 * Plugin Name: BellaCiao Importer
 * Description: BellaCiao importer plugin
 * Author: Alex Ischenko
 * Version: 0.1
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

   public function __construct() {
      add_filter( 'wp_import_posts', [ $this, 'filter_imported_posts' ], 10 );
      add_action( 'init', [ $this, 'init_update_conditions' ] );
    }

    public function filter_imported_posts( $posts ) {
      add_action('import_end', function() use ( &$posts ) { 
         $this->import_end( $posts );
      }, 10 );
   
      return $posts;
    }

    public function import_end( $posts ) {
      $this->update_templates_conditions();
      update_option( 'bella_importer_update_condition', 'update' );
    }

    public function init_update_conditions() {
      $update = get_option('my_option');
      if ( get_option( 'bella_importer_update_condition' ) == 'update' ){
         $this->update_templates_conditions();
         delete_option( 'bella_importer_update_condition' );
      }
    }

    public function update_templates_conditions() {
      $cache = new \ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Cache();
      $cache->regenerate();
    }

 }
 new Bella_Importer();