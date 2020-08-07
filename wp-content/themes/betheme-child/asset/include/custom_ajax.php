<?php

add_action('wp_ajax_getsubscriptionID','getsubscriptionID');
add_action( 'wp_ajax_nopriv_getsubscriptionID', 'getsubscriptionID' );
function getsubscriptionID(){
    global $wpdb;
    // we will pass post IDs and titles to this array
    $return = array();

    $results = $wpdb->get_results("SELECT * FROM `series_subscription` WHERE `description` LIKE '%".$_GET['q']."%' OR `code` LIKE '%".$_GET['q']."%'");
    if($results){
        foreach ($results as $key => $data){
            $title = ( mb_strlen( $data->description ) > 50 ) ? mb_substr( $data->description, 0, 49 ) . '...' : $data->description;
            $return[] = array( $data->code, $title );
        }
    }
    
    echo json_encode( $return );

    die;
}

/* User GET */

add_action('wp_ajax_comics_getUserID','comics_getUserID');
add_action( 'wp_ajax_nopriv_comics_getUserID', 'comics_getUserID' );
function comics_getUserID(){

    // we will pass post IDs and titles to this array
    $return = array();


    $users = new WP_User_Query( array(
    'search'         => '*'.esc_attr( $_GET['q'] ).'*',
    'role'  => 'customer',
        'search_columns' => array(
            'user_login',
            'user_nicename',
            'user_email',
            'user_url',
        ),
    ) );
    $users_found = $users->get_results();


    if ( ! empty( $users_found ) ) {
        foreach ( $users_found as $user ) {
            //echo '<p>' . $user->display_name . '</p>';
            $title = ( mb_strlen( $user->display_name ) > 50 ) ? mb_substr( $user->display_name, 0, 49 ) . '...' : $user->display_name;
            $return[] = array( $user->ID, $title ); /*// array( Post ID, Post Title )*/

        }
    }

    echo json_encode( $return );
    die;
}

add_action('wp_ajax_getParentSubData','getParentSubData');
add_action( 'wp_ajax_nopriv_getParentSubData', 'getParentSubData' );
function getParentSubData(){
    $sdata = new series_subscription();
    $series_data = $sdata->series_data($_POST['parent_product_id']);
    
    $series_code = $series_data->code;
    $series_title = $series_data->description;
    $html = '';
    $html .='<div class="ax_series_list_item_container">
        <div class="ax_series_seg_1">
            <label class="ax_series_code">'.$series_code.'</label>
        </div>
        <div class="ax_series_seg_2">
            <label class="ax_series_title">'.$series_title.'</label>
        </div>
        <div class="ax_series_seg_3">
            <button name="ax_add_series_to_customer" class="ax_add_series_to_customer" type="button" data-id="'.$series_code.'">Add User</button>
        </div>
        <div class="ax_series_seg_4">
            <button name="ax_view_customers" class="ax_view_customers" type="button" data-id="'.$series_code.'">View</button>
        </div>
        <input type="hidden" id="hidden_parent_product_id" value="'.$series_code.'">
    </div>';

    echo $html;
    die();
}

add_action( 'wp_ajax_get_parent_child_ajax', 'va_get_parent_child_ajax' );
add_action( 'wp_ajax_nopriv_get_parent_child_ajax', 'va_get_parent_child_ajax' );
function va_get_parent_child_ajax(){

	$order_id = $_POST['order_id'];
    //$parent_product_ids = get_post_meta( $order_id , 'parent_product_ids' , true );

    $args = array(
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'post_type'     => 'product',
                'fields'        => 'ids',
                'meta_key'      => 'series_code',
                'meta_value'    => $order_id,
            );

    $query = new WP_Query( $args );

    $child_product_id = $query->posts;
    $total_ids = count($child_product_id);

    if(!empty( $child_product_id )){
        $html = '';
        $html.='<table>
        <thead>
        <tr> 
        <th scope="col">Subscription Products</th> 
        </tr>
        </thead>';
        $html.='<tr>';
        $html .= '<td data-label="Child">';
        foreach ($child_product_id as $child_value) {
            $child_product = wc_get_product( $child_value );
            $html .= '<p>'.$child_product->get_name().'</p>';

        }
        $html.='</td></tr>';
        $html.='</table>';
    }else{
        $html = 'No child found!.';
    }
    

    echo $html;

    die();
}



add_action('wp_ajax_func_import_date_filter', 'vb_reg_func_import_date_filter');
add_action('wp_ajax_nopriv_func_import_date_filter', 'vb_reg_func_import_date_filter');
function vb_reg_func_import_date_filter() {

    global $wp;
    $current_url = home_url();

    $latest_import_date = $_POST['sel_import_date'];
    $args = array(
        'posts_per_page' =>  -1,
        'post_type' => array('product'/* ,'product_variation' */),
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'desc',
        'meta_query' => array (
            'relation' => 'OR',
            'import_date' => array (
                'key' => 'import_date',
                'value' => $latest_import_date,
                'compare' => '=',
            ),
        ),
        
    );

    $prod_query = new WP_Query($args);

    if ( ! $prod_query->have_posts() ) {
        $json['error'] = true;
        $json['message'] =  __("No Product found!.", "tamberra") ;
        echo json_encode($json);
        die();
    } 

    
    $html = '';
    while ( $prod_query->have_posts() ): $prod_query->the_post();
        $product = new WC_Product(get_the_ID());

        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

        if(!empty($feat_image)){
            $prod_img =  $feat_image[0];
        }else{
            $prod_img = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
        }

        $add_to_cart = '?add-to-cart='.get_the_ID();
        $price = $product->get_price_html();
        $loader = "/wp-admin/images/spinner.gif";


        $currency = get_woocommerce_currency_symbol();
        $price = get_post_meta( get_the_ID(), '_regular_price', true);
        $sale = get_post_meta( get_the_ID(), '_sale_price', true);

        if($sale) : 
            $price = '<del>'.$currency.$price.'</del>'.$currency.$sale;    
        elseif($price) :
            $price = $currency.$price;   
        endif; 

        $html .='<article>
            <div class="product_column product-block">
                <div class="product-img">
                    <a href="'.get_permalink(get_the_ID()).'">
                        <div class="vyte-overlay"></div>
                        <img src="'.$prod_img.'" alt = "">
                    </a>
                </div>
                <div class="product-info">
                    <div class="prod_links">
                        <a href="'.get_permalink(get_the_ID()).'">'.get_the_title().' </a>
                    </div>

                    <div class="prod_price">
                        '.$price.'
                    </div>

                    <div class="add_to_cart_link">

                    <a href="'.$add_to_cart.'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.get_the_ID().'" data-product_sku="" aria-label="" rel="nofollow">Add to cart</a><img src='.$loader.' class="img_loader" style="display:none;">

                    </div>


                </div>
            </div>
        </article>';

        
    endwhile; 
       
       $json['success'] = true;
        $json['html'] = $html;
        echo json_encode($json);

    die();
}


add_action('wp_ajax_comics_subscribe_data', 'vb_reg_comics_subscribe_data');
add_action('wp_ajax_nopriv_comics_subscribe_data', 'vb_reg_comics_subscribe_data');
function vb_reg_comics_subscribe_data() {

    $series_id = $_POST['parent_product_id'];
    $user_id = get_current_user_id();

    if(!empty($series_id)){
            
            #get user subscriber series 
            $user_subscriber_series = get_user_meta($user_id,'_user_subscription_series_id',true);
            if(empty($user_subscriber_series)){
                $user_subscriber_series = array();
                $user_subscriber_series[] = $series_id;
            }else{
                $user_subscriber_series[] = $series_id;
            }
            $user_subscriber_series = array_unique( $user_subscriber_series );
            $user_subscriber_series = array_values( array_filter( $user_subscriber_series ) );
            
            update_user_meta($user_id,'_user_subscription_series_id',$user_subscriber_series);
             
            $json['success'] = true;
            $json['message'] =  __("Done", "tamberra") ;
            echo json_encode($json);
            die();
    }
    die();
}
add_action('wp_ajax_add_customer_into_series','add_customer_into_series');
function add_customer_into_series(){
    #comic subscription post ID
    $series_id = $_POST['parent_product_id'];
    $user_id = $_POST['user_id'];



    if(!empty($series_id)){
            $arr_data = array();
            #get user subscriber series 
            $user_subscriber_series = get_user_meta($user_id,'_user_subscription_series_id',true);
            if(empty($user_subscriber_series)){
                $user_subscriber_series = array();
                $user_subscriber_series[] = $series_id;
            }else{
                $user_subscriber_series[] = $series_id;
            }
            $user_subscriber_series = array_unique( $user_subscriber_series );
            $user_subscriber_series = array_values( array_filter( $user_subscriber_series ) );
            
            update_user_meta($user_id,'_user_subscription_series_id',$user_subscriber_series);

            update_user_meta($user_id,'series_subscribers','yes');

            #store in custom table
            $sdata = new series_subscription();
            $arr_data['series_id'] = $series_id;
            $arr_data['user_id'] = $user_id;
            $arr_data['blog_id'] = get_current_blog_id();
            $arr_data['status'] = 'active';
            $arr_data['create_date'] = date('Y-m-d H:i:s');

            $sdata->add_subscriber( $arr_data );

            $subscription_time = get_user_meta($user_id,'subscription_time_',true);
            $time_ = date('Y-m-d H:i:s');
            if(empty($subscription_time)){
                $subscription_time = array();
                $subscription_time[$series_id] = $time_;
            }else{
                $subscription_time[$series_id] = $time_;
            }
            $subscription_time = array_unique( $subscription_time );
            //$subscription_time = array_values( array_filter( $subscription_time ) );
            
            update_user_meta($user_id,'subscription_time_',$subscription_time);
            
            /* Email Notification */
            $sdata = new series_subscription();
            $series_data = $sdata->series_data( $series_id );

            $series_code = $series_data->code;
            $description = $series_data->description;

            $admin_email = get_site_option( 'admin_email' ); 
            $user = get_user_by( 'id', $user_id );
            $u_login = $user->user_login;
            
            $to =  $admin_email;
            $subject = '#'.$series_code.' subscribe';
            $body= 'Hello,</br>';
            $body.= '<b>'.$u_login.'</b> has subscribe #'.$series_code.' '.$description.' series.';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail( $to, $subject, $body, $headers ); 


            $json['success'] = true;
            $json['message'] =  __("Done", "tamberra") ;
            echo json_encode($json);
            die();
    }
    die();
}
add_action('wp_ajax_view_series_subscribers','view_series_subscribers');
function view_series_subscribers(){
    $series_id = $_POST['series_id'];
    $blog_id = get_current_blog_id();
   /* $sdata = new series_subscription();
    $subscribers = $sdata->get_subscriber_user($series_id);*/

   // $subscribers = get_post_meta($series_id,'_subscriber_user_id',true);
  /*  echo '<pre>';
    print_r($subscribers);
    echo '</pre>';*/
    $sdata = new series_subscription();
    $subscribers = $sdata->get_subscriber_user($series_id , $blog_id);
    
   
    if( $subscribers ){
        
        foreach($subscribers as $key => $user){
        
                $user = get_userdata($user->user_id);
                $user_id = $user->ID;
                $username = $user->user_login;
               // $username = $user->first_name .' '.$user->last_name;
                if(empty($username)){
                   
                }
                ?>
                <div class="ax_customer_item_container">
                    <div class="ax_customer_list_seg_1">
                        <label class="ax_customer_id"><?php echo $user_id; ?></label>
                    </div>
                    <div class="ax_customer_list_seg_2">
                        <label class="ax_customer_name"><?php echo $username; ?></label>
                    </div>
                    <div class="ax_customer_list_seg_3">
                        <button type="button" name="ax_popup_remove_btn" class="ax_popup_remove_btn sub_remove_user" data-seriesid="<?php echo $series_id;?>" data-user_id="<?php echo $user_id; ?>">Delete</button>
                    </div>
                </div>
                <?php
            
        }
            
    }else{
        echo 'No data';
    }
    wp_die();
}
add_action('wp_ajax_subscribtion_remove_','subscribtion_remove_');
function subscribtion_remove_(){
    $series_id = $_POST['series_id'];
    $user_id = $_POST['user_id'];
    
    if( !empty( $series_id ) && !empty( $user_id ) ){
        $arr_data = array();
        #remove for table
        $sdata = new series_subscription();
        $arr_data['series_id'] = $series_id;
        $arr_data['user_id'] = $user_id;
        $arr_data['blog_id'] = get_current_blog_id();
        $arr_data['status'] = 'deactive';
        $arr_data['delete_data'] = date('Y-m-d H:i:s');
        $sdata->remove_subscriber( $arr_data );
        #renmove from user meta
        $user_series = get_user_meta($user_id,'_user_subscription_series_id',true);
        $key = array_search($series_id,$user_series);
        if(isset($key)){
            unset($user_series[$key]);

            $user_series = array_values( array_filter( $user_series ) );

            update_user_meta( $user_id, '_user_subscription_series_id', $user_series );
            if( empty( $user_series ) ){
                delete_user_meta($user_id,'series_subscribers');
            }
        }

        $subscription_time = get_user_meta($user_id,'remove_subscription_time_',true);
        $time_ = date('Y-m-d H:i:s');
        if(empty($subscription_time)){
            $subscription_time = array();
            $subscription_time[$series_id] = $time_;
        }else{
            $subscription_time[$series_id] = $time_;
        }
        $subscription_time = array_unique( $subscription_time );
        //$subscription_time = array_values( array_filter( $subscription_time ) );
        
        update_user_meta($user_id,'remove_subscription_time_',$subscription_time);

        $sub_time = get_user_meta($user_id,'subscription_time_',true);
        if(isset($sub_time[$series_id])){
            unset($sub_time[$series_id]);
            //$sub_time = array_values( array_filter( $sub_time ) );
            update_user_meta( $user_id, 'subscription_time_', $sub_time );
        }

        /* Email Notification */
        $sdata = new series_subscription();
        $series_data = $sdata->series_data( $series_id );

        $series_code = $series_data->code;
        $description = $series_data->description;

        $admin_email = get_site_option( 'admin_email' ); 
        $user = get_user_by( 'id', $user_id );
        $u_login = $user->user_login;

        $to = $admin_email;
        $subject = '#'.$series_code.' Unsubscribe';
        $body= 'Hello,</br>';
        $body.= '<b>'.$u_login.'</b> has unsubscribe #'.$series_code.' '.$description.' series.';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail( $to, $subject, $body, $headers ); 
        
    }
    wp_die();
}

add_action( 'wp_ajax_get_active_series_child_ajax', 'va_get_active_series_child_ajax' );
add_action( 'wp_ajax_nopriv_get_active_series_child_ajax', 'va_get_active_series_child_ajax' );
function va_get_active_series_child_ajax(){

    $order_id = $_POST['order_id'];
    //$parent_product_ids = get_post_meta( $order_id , 'parent_product_ids' , true );

    $args = array(
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post_type'     => 'product',
        'fields'        => 'ids',
        'meta_key'      => 'series_code',
        'meta_value'    => $order_id,
    );

    $query = new WP_Query( $args );

    $child_product_id = $query->posts;
    //vivid( $child_product_id );

    $total_ids = count($child_product_id);

    if(!empty( $child_product_id )){
        $html = '';
        $html.='<table>
        <thead>
        <tr> 
        <th scope="col">Subscription Products</th> 
        </tr>
        </thead>';
        $html.='<tr>';
        $html .= '<td data-label="Child">';
        foreach ($child_product_id as $child_value) {
            $child_product = wc_get_product( $child_value );
            $html .= '<p>'.$child_product->get_name().'</p>';

        }
        $html.='</td></tr>';
        $html.='</table>';
    }else{
        $html = 'No child found!.';
    }
    

    echo $html;

    die();
}
