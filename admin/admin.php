<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BCI_Admin {

   public function __construct() {
      add_action( 'admin_menu', array( $this, 'admin_menu' ), 100 );
      add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_css_and_js' ) );
   }


   public function admin_menu() {
      add_submenu_page( 
         'bella-settings',
         'BellaCiao Import', 
         'BellaCiao Import', 
         'manage_options', 
         'bella-importer', 
         array( $this, 'import_page' ),
         10
      );
   }



   public function enqueue_admin_css_and_js( $hook ) {
      $current_screen = get_current_screen();
      if ( $current_screen->base == 'bellaciao_page_bella-importer' ) {
         wp_enqueue_style( 'bci-admin', BCI_PLUGIN_DIR_URL . '/admin/assets/admin.css', array(), time() );
         wp_register_script( 'bci-admin', BCI_PLUGIN_DIR_URL . '/admin/assets/admin.js', array( 'jquery' ), time() );
         wp_enqueue_script( 'bci-admin' );
      }
   }


   public function import_page() {
      ?>
         <div class="wrap">
            <h1>BellaCiao Import</h1>
         
            <div class="card bci-step bci-step-1">

               <?php 
                  $check = BC_Importer::before_import_checks();
                  echo $check['html'];
               ?>
         
                  
               

               
            </div>



            <div class="card bci-step bci-step-2">

               <form id="bella-import">
         
                  <table class="form-table">
         
         
                     <!-- FETCH NOW BUTTON -->
                     <tr valign="top">
                        <th scope="row">Upload XML</th>
                        <td>
                           <input type="file" id="bella-import-file" name="bella-import-file">
                        </td>
                     </tr>
         
                     <!-- UPDATE EXISTING -->
                     <tr valign="top">
                        <th scope="row">Update existing episodes</th>
                        <td>
                           <input type="checkbox" id="rfpi_update_existing" name="rfpi_update_existing"  value="1" <?php checked(get_option('rfpi_update_existing')); ?> autocomplete="off">
                        </td>
                     </tr>
         
         
                  </table>
         
                  
                  <input type="submit" class="button button-primary" value="Import">

                  <?php echo get_option('stylesheet'); ?>
         
               </form>

            </div>
         
         
         </div>
      <?php 


      
   }

}







