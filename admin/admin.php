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
               <form id="bella-upload">
                  <table class="form-table">
                     <tr valign="top">
                        <th scope="row">Upload XML</th>
                        <td>
                           <input type="file" id="bella-import-file" name="bella-import-file">
                        </td>
                     </tr>
                  </table>

                  <div id="error-response"></div>
         
                  <div class="bci-step-nav">
                     <span class='spinner'></span>
                     <input type="submit" class="button button-primary" value="Upload XML">
                  </div>
         
               </form>

            </div>



            <div class="card bci-step bci-step-3">
               <form id="bella-import-settings">
                  <table class="form-table">
         
                     <tr valign="top">
                        <th scope="row">Posts author</th>
                        <td>
                           <select id="bci_author" name="bci_author">
                              <?php 
                                 $users = get_users();
                                 foreach ( $users as $user ) {
                                    echo '<option value="' . $user->ID . '">' . $user->user_nicename . '</option>';
                                 }
                              ?>
                           </select>
                        </td>
                     </tr>
         
                     <tr valign="top">
                        <th scope="row">Import Settings</th>
                        <td>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_upload_attachments" name="bci_upload_attachments" checked autocomplete="off">
                              Import and upload attachments
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_kit" name="bci_import_kit" checked autocomplete="off">
                              Import Elementor kit
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_elementor_templates" name="bci_import_elementor_templates" checked autocomplete="off">
                              Import Elementor templates
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_jetengine_templates" name="bci_import_jetengine_templates" checked autocomplete="off">
                              Import JetEngine templates
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_jetwoobuilder_templates" name="bci_import_jetwoobuilder_templates" checked autocomplete="off">
                              Import JetWooBuilder templates
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_jetsmartfilters" name="bci_import_jetsmartfilters" checked autocomplete="off">
                              Import JetSmartFilters
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_pages" name="bci_import_pages" checked autocomplete="off">
                              Import pages
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_categories" name="bci_import_categories" checked autocomplete="off">
                              Import blog categories
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_posts" name="bci_import_posts" checked autocomplete="off">
                              Import blog posts
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_products" name="bci_import_products" checked autocomplete="off">
                              Import products
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_product_categories" name="bci_import_product_categories" checked autocomplete="off">
                              Import products categories
                           </label>
                           <label class="bci_checkbox_label">
                              <input type="checkbox" id="bci_import_product_attributes" name="bci_import_product_attributes" checked autocomplete="off">
                              Import products attributes
                           </label>
                        </td>
                     </tr>
         
                  </table>

                  <div class="bci-step-nav">
                     <input type="submit" class="button button-primary" value="Start import">
                  </div>
         
               </form>
            </div>
         </div>



            <div class="card bci-step bci-step-4">

               <label>
                  Step <span class="bci_current_step">1</span> of <span class="bci_total_steps">1</span>
                  <progress id="import_steps_progress" max="0" value="1" style="width: 100%;"></progress>
               </label>

               <label>
                  Importing <span class="bci_current_item">1</span> of <span class="bci_total_items">1</span> items
                  <progress id="import_steps_progress" max="0" value="1" style="width: 100%;"></progress>
               </label>

               <form id="bella-import">


                  <div class="bci-step-nav">
                     <span class='spinner'></span>
                     <input type="submit" class="button button-primary" value="Start import">
                  </div>
         
               </form>


            </div>
         
         
         </div>
      <?php 


      
   }

}







