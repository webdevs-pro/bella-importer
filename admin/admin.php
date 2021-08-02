<?php

// create custom plugin settings menu
add_action('admin_menu', function() {
   add_management_page( 
      'BellaCiao Import', 
      'BellaCiao Import', 
      'manage_options', 
      'bella-import', 
      'bella_import_admin' 
   );
} );



function bella_import_admin() {
   ?>
<div class="wrap">
   <h1>BellaCiao Import</h1>

   <div class="card">
      <form method="post" action="" enctype="multipart/form-data">

         <table class="form-table">


            <!-- FETCH NOW BUTTON -->
            <tr valign="top">
               <th scope="row">Upload XML</th>
               <td>
                  <input type="file" id="bella_importer_uload_file" name="bella_importer_uload_file">
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

      </form>
   </div>


</div>
<?php 
}