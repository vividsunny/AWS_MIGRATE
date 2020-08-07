<?php
/**
 * summary
 */
class VAIMPORT
{
    /**
     * summary
     */
    public function __construct()
    {


       #adding menu page
       add_action( 'admin_menu', array( $this, 'va_import_admin_menu' ) );
       add_action('wp_ajax_va_do_ajax__import', array( $this, 'va_do_ajax__import' ) );
       add_action( 'admin_enqueue_scripts', array( $this, 'va_admin_scripts' ) );

    }

    public function va_import_admin_menu(){
    	#adding as main menu
    	//add_menu_page( 'va import', 'va import', 'manage_options', 'va_import_page', array( $this, 'va_import_html' ), 'dashicons-tickets', 6  );

    	#if you need this on any submenu use below code

     $current_blog_id = get_current_blog_id();
     $availabel_blog = array( 13 );
     if(in_array( $current_blog_id , $availabel_blog ) ){
      add_submenu_page('all_subscriptions', __('Import Subscription'), __('Import Subscription'), 'manage_options', 'import-subscription', array( $this,'va_import_html') );
    }
      
      
    	
    }

    public function va_import_html(){
      require_once( 'admin/html/va-import-html.php' );  
    }

    public function va_admin_scripts(){
      wp_register_script( 'va-import', get_stylesheet_directory_uri().'/asset/va-import/admin/js/va_import.js' );
     
    }

    public function va_do_ajax__import(){
        $file   = wc_clean( wp_unslash( $_POST['file'] ) ); // PHPCS: input var ok.

        $params = array(
          'delimiter'       => ! empty( $_POST['delimiter'] ) ? wc_clean( wp_unslash( $_POST['delimiter'] ) ) : ',', // PHPCS: input var ok.
          'start_pos'       => isset( $_POST['position'] ) ? absint( $_POST['position'] ) : 0, // PHPCS: input var ok.
          'mapping'         => isset( $_POST['mapping'] ) ? (array) wc_clean( wp_unslash( $_POST['mapping'] ) ) : array(), // PHPCS: input var ok.
          'update_existing' => isset( $_POST['update_existing'] ) ? (bool) $_POST['update_existing'] : false, // PHPCS: input var ok.
          'lines'           => 30,
          'parse'           => true,
          'imported'=>isset($_POST['imported']) ? $_POST['imported']:0,
          'updated'=>isset($_POST['updated']) ? $_POST['updated']:0,          
          'failed'=>isset($_POST['failed']) ? $_POST['failed']:0,          
          'skipped'=>isset($_POST['skipped']) ? $_POST['skipped']:0,          
        );

        $results = $this->read_csv_file($file,$params);

        if ( 100 === $results['percentage'] ) {
          wp_send_json_success(
            array(
              'position'   => 'done',
              'percentage' => 100,
             // 'url'        => add_query_arg( array( 'nonce' => wp_create_nonce( 'product-csv' ) ), admin_url( 'edit.php?post_type=product&page=product_importer&step=done' ) ),
              'imported'   => $results['imported'] ,
              'failed'     => $results['failed'] ,
              'updated'    => $results['updated'] ,
              'skipped'    => $results['skipped'] ,
              'data'  => $results['inserted_data'],
              'va_message' => $results['message'],
            )
          );
        }else{
            wp_send_json_success(
              array(
                'position'   => $results['position'],//2,//$importer->get_file_position(),
                'percentage' => $results['percentage'],
                'imported'   => $results['imported'],
                'failed'     => $results['failed'],
                'updated'    => $results['updated'],
                'skipped'    => $results['skipped'],
                'data'  => $results['inserted_data'],
                'va_message' => $results['message'],
              )
            );
        }
        

    }

    public function read_csv_file($file,$params){

        $filename = $file;
        $seriestable  = new series_subscription();
        if (($handle = fopen($filename, "r")) !== FALSE) {
           $start_pos = $params['start_pos'];

            $end_pos = (int)$params['start_pos'] + 1;
            $row = 0;
            //$row = (int)$start_pos;
            $parse_data = array();
            
          
             $update_count = (int)$params['updated'];
             $insert_count =  (int)$params['imported'];
             $failed_count = (int)$params['failed'];
             $skipped_count = (int)$params['skipped'];
          $header = fgetcsv( $handle, 0);


          $total_row = $this->count_total_file_row($file);
          $total_row = (int)$total_row; //remove header
          /*if($end_pos > $total_row){
            $end_pos = $total_row;
          }*/
          $d = date("j-M-Y H:i:s");
          while (($data = fgetcsv($handle)) !== FALSE) {
               

           // $total_row = count($data) - 1;
           // $total_row = count($data);
            //echo $total_row;
            foreach($header as $i => $key){
                  $parse_data[$key] = $data[$i]; 

            }
       
            
            //echo "\n <p> $num fields in line $row: <br /></p>\n";
            $row++;
            //$results['message'] .= "end_pos = $end_pos ,row = $row ,total_row = $total_row";
            if($end_pos == $row || $end_pos == $total_row){//&& $end_pos <= $total_row
             $total_row = (int)$total_row -1;//remove header
              $results['position'] = $row;
              $results['percentage'] = $this->get_percent_complete( $total_row, $end_pos );
              $results['inserted_data'] = $parse_data;

              //insert code
              if(isset( $parse_data['code'] ) && !empty( $parse_data['code'] ) ){
                //debug($parse_data);
               //debug($parse_data);
$qresults = $seriestable->check_series($parse_data['code']);
if($qresults){
//update
if($qresults->description != $parse_data['description'] || $qresults->active != $parse_data['active'] || $qresults->publisher != $parse_data['publisher'] || $qresults->numissues != $parse_data['numissues'] || $qresults->frequencycode != $parse_data['frequencycode'] || $qresults->override != $parse_data['override'] || $qresults->notes != $parse_data['notes'] ){

$seriestable->update_series($parse_data);
$update_count = (int)$update_count + 1;
//$update_data[] = $parse_data['code'];
$str_message = '- Updated!'; 
}else{
$skipped_count = (int)$skipped_count + 1;
}
                }else{
                  $seriestable->insert_series($parse_data);  
                 // $insert_data[] = $parse_data['code'];
                $insert_count = $insert_count + 1;
                 $str_message = '- Inserted!';
                  
                }
                //insert

                //alrady
                $results['updated'] = $update_count;
                $results['imported'] = $insert_count;
                $results['skipped'] = $skipped_count;
                $results['message'] = '['.$d.'] - '.$parse_data['code'].$str_message;

                
              }else{
               
                 $results['failed'] = $failed_count;
               
                $results['message'] = '['.$d.'] - '.$parse_data['code'].' - failed'.PHP_EOL;
              }
             
              

              break;
            }

          }
          fclose($handle);
        }
        return $results;
    }
    public function old_va_do_ajax__import(){
      	$file   = wc_clean( wp_unslash( $_POST['file'] ) ); // PHPCS: input var ok.

        $params = array(
        	'delimiter'       => ! empty( $_POST['delimiter'] ) ? wc_clean( wp_unslash( $_POST['delimiter'] ) ) : ',', // PHPCS: input var ok.
        	'start_pos'       => isset( $_POST['position'] ) ? absint( $_POST['position'] ) : 0, // PHPCS: input var ok.
        	'mapping'         => isset( $_POST['mapping'] ) ? (array) wc_clean( wp_unslash( $_POST['mapping'] ) ) : array(), // PHPCS: input var ok.
        	'update_existing' => isset( $_POST['update_existing'] ) ? (bool) $_POST['update_existing'] : false, // PHPCS: input var ok.
        	'lines'           => 30,
        	'parse'           => true,
      	);

        $results = $this->read_csv_file($file,$params);

        if ( 100 === $results['percentage'] ) {
          wp_send_json_success(
            array(
              'position'   => 'done',
              'percentage' => 100,
             // 'url'        => add_query_arg( array( 'nonce' => wp_create_nonce( 'product-csv' ) ), admin_url( 'edit.php?post_type=product&page=product_importer&step=done' ) ),
              'imported'   => count( $results['imported'] ),
              'failed'     => count( $results['failed'] ),
              'updated'    => count( $results['updated'] ),
              'skipped'    => count( $results['skipped'] ),
              'data'  => $results['inserted_data'],
              'va_message' => $results['message'],
            )
          );
        }else{
            wp_send_json_success(
              array(
                'position'   => $results['position'],//2,//$importer->get_file_position(),
                'percentage' => $results['percentage'],
                'imported'   => count( $results['imported'] ),
                'failed'     => count( $results['failed'] ),
                'updated'    => count( $results['updated'] ),
                'skipped'    => count( $results['skipped'] ),
                'data'  => $results['inserted_data'],
                'va_message' => $results['message'],
              )
            );
        }
        

    }

    public function old_read_csv_file($file,$params){

      $filename = $file;

      if (($handle = fopen($filename, "r")) !== FALSE) {
        $start_pos = $params['start_pos'];
        $end_pos = (int)$params['start_pos'] + 1;
        $row = 0;
        $parse_data = array();
        $skipped_data = array();
        $update_data = array();
        $insert_data = array();
        $failed_data = array();
        $updated_data = array();  
        $header = fgetcsv( $handle, 0);
        $total_row = $this->count_total_file_row($file);
        $total_row = (int)$total_row; /*remove header*/

        $d = date("j-M-Y H:i:s");
        while (($data = fgetcsv($handle)) !== FALSE) {

          foreach($header as $i => $key){
            $parse_data[$key] = $data[$i]; 

          }
          $row++;
          $post_content  = $parse_data['description'];
          $post_excerpt   = $parse_data['description'];
          $post_type    = 'comics_subscription';
          $post_status  = 'publish';
          $post_title   = 'Subscription : '.$parse_data['code'].' - '.$parse_data['description'];

          /*insert code*/
          if(isset( $parse_data['code'] ) && !empty( $parse_data['code'] ) ){

            $series_data = array(
              '_series_code'      => $parse_data['code'],
              '_series_active'    => $parse_data['active'],
              '_series_publisher'   => $parse_data['publisher'],
              '_series_numissues'   => $parse_data['numissues'],
              '_series_frequencycode' => $parse_data['frequencycode'],
              '_series_override'    => $parse_data['override'],
              '_series_notes'     => $parse_data['notes'],

            );

            $post_data = array(
              'post_content'  => $post_content,
              'post_title'  => $post_title,
              'post_excerpt'  => $post_excerpt,
              'post_status' => $post_status,
              'post_name'   => $post_title,
              'post_type'   => $post_type,
            );


            $check_post = post_exists( $post_title );
            if( $check_post ) {
              /* Main site meta update */
              update_post_meta( $check_post,'subscription_series_data',$series_data );
              foreach ($series_data as $key => $_value) {
                        # code...
                update_post_meta( $check_post,"$key",$_value );
              }
              $subsites = get_sites();
              foreach( $subsites as $subsite ) {
                $subsite_id = get_object_vars( $subsite )["blog_id"];
                $subsite_name = get_blog_details( $subsite_id )->blogname;

                /*For blog id - 4 */
                //if( $subsite_id == 4 ){
                  switch_to_blog( $subsite_id );

                  /*if post with the same slug exists, do nothing*/
                   $subsite_exists = post_exists( $post_title );
                  if( $subsite_exists ) {
                    
                    /* Subsite meta update */
                    update_post_meta( $subsite_exists,'subscription_series_data',$series_data );
                    foreach ($series_data as $metakey => $sub_value) {
                        # code...
                      update_post_meta( $subsite_exists,"$metakey",$sub_value );
                     }

                    restore_current_blog();
                    continue;
                  }
                  
                //}/* //condition blog_id - 4 */

              }
              restore_current_blog();
              //continue;
              //$results['message'] .= '-- UPDATE --';
              /*$results['message'] .= '['.$d.'] - '.$post_title.' - '.PHP_EOL;*/
              $updated_data[] = $post_title;
              $results['updated'] = $updated_data;
              $results['message'] = $post_title.'- UPDATE';
            }else{

                     $post_id = wp_insert_post( $post_data );
                     /*Create Order Meta*/
                     update_post_meta( $post_id,'subscription_series_data',$series_data );
                     foreach ($series_data as $key => $value) {
                        # code...
                      update_post_meta( $post_id,"$key",$value );
                     }

                      /* Insert in subsite */
                      $subsites = get_sites();
                      foreach( $subsites as $subsite ) {
                      $subsite_id = get_object_vars( $subsite )["blog_id"];
                      $subsite_name = get_blog_details( $subsite_id )->blogname;
                        switch_to_blog( $subsite_id );

                        /*if post with the same slug exists, do nothing*/
                        $check_post = post_exists( $post_title );

                        if( $check_post ) {
                          
                          restore_current_blog();
                          continue;
                        }
                        
                        $inserted_post_id = wp_insert_post( $post_data );
                        wp_set_object_terms( $inserted_post_id, $post_terms, 'category', false);
                        update_post_meta( $inserted_post_id,'subscription_series_data',$series_data );

                        foreach ($series_data as $metakey => $sub_value) {
                        # code...
                          update_post_meta( $inserted_post_id,"$metakey",$sub_value );
                        }

                        restore_current_blog();
                        }

                        $insert_data[] = $post_title;
                        $results['imported'] = $insert_data;
                        $results['message'] = $post_title.'- INSERT';
            }

           
            
          }else{
            $skipped_data[] = $parse_data['code'];
            $results['skipped'] = $skipped_data;
            $results['message'] = '['.$d.'] - '.$parse_data['code'].' - skipped'.PHP_EOL;
          }
          $results['failed'] = $failed_data;
          
          
          if($end_pos == $row || $end_pos == $total_row){
            $total_row = (int)$total_row -1;
            $results['position'] = $row;
            $results['percentage'] = $this->get_percent_complete( $total_row, $end_pos );
            $results['inserted_data'] = $parse_data;

            break;
          }

        }
        fclose($handle);
      }
      return $results;
    }

    public function get_percent_complete($total_row,$end_pos) {
     return absint( min( round( ( $end_pos / $total_row ) * 100 ), 100 ) );
    }

    public function count_total_file_row($filename){
     $fp = file($filename, FILE_SKIP_EMPTY_LINES);
     return count($fp);
    }
}

new VAIMPORT();
?>