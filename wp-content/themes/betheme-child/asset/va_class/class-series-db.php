<?php
class series_subscription
{
  public $table = 'series_subscription';
  public $sub_table = 'subscribers_data';
  /**
   * summary
   */
  public function __construct()
  { }

  public function check_series($code)
  {
    global $wpdb;
    $results = $wpdb->get_row("SELECT * FROM $this->table WHERE code = $code ");
    if ($results) {
      return $results;
    } else {
      return false;
    }
  }

  public function insert_series($data)
  {
    global $wpdb;

    $insert_data = array(
      'code' => $data['code'],
      'active' => $data['active'],
      'description' => $data['description'],
      'publisher' => $data['publisher'],
      'numissues' => $data['numissues'],
      'frequencycode' => $data['frequencycode'],
      'override' => $data['override'],
      'notes' => $data['notes'],
      'meta'  => '',

    );
    $format = array(
      '%d',
      '%s',
      '%s',
      '%d',
      '%d',
      '%s',
      '%s',
      '%s',
      '%s',
    );

    $wpdb->insert($this->table, $insert_data, $format);
  }
  public function update_series($data)
  {
    global $wpdb;

    $insert_data = array(

      'active' => $data['active'],
      'description' => $data['description'],
      'publisher' => $data['publisher'],
      'numissues' => $data['numissues'],
      'frequencycode' => $data['frequencycode'],
      'override' => $data['override'],
      'notes' => $data['notes'],
      'meta'  => '',

    );
    $format = array(

      '%s',
      '%s',
      '%d',
      '%d',
      '%s',
      '%s',
      '%s',
      '%s',
    );
    $where = array('code' => $data['code'],);
    $where_format = array('%d');
    $wpdb->update($this->table, $insert_data, $where, $format, $where_format);
  }
  public function series_data($code){
    global $wpdb;
    if( !empty( $code ) ){
        $sql_query = "SELECT * FROM $this->table WHERE code = $code";
        $results = $wpdb->get_row($sql_query);
        return $results;  
    }else{
        return false;
    }
    
  }
  public function get_series_title($code)
  {
    global $wpdb;
    $results = $wpdb->get_row("SELECT description FROM $this->table WHERE code = $code");
    return $results->description;
  }

  public function subscribe_user($user_id)
  { }

  public function get_subscriber_user($code,$blog_id)
  {
    global $wpdb;
    $results = $wpdb->get_results("SELECT user_id FROM $this->sub_table WHERE series_id = $code AND blog_id = $blog_id AND status = 'active'");
    if ($results) {
      return $results;
    } else {
      return false;
    }
  }

  public function check_subscribers($series_id,$user_id,$blog_id){
    global $wpdb;
    $results = $wpdb->get_row("SELECT * FROM $this->sub_table WHERE series_id = $series_id AND user_id = $user_id AND blog_id = $blog_id");
       
    if($results){
      return true;
    }else{
      return false;
    }
  }

  public function add_subscriber($data)
  {
    global $wpdb;
    $check = $this->check_subscribers( $data['series_id'], $data['user_id'] , $data['blog_id'] );
    if( $check ){
     
      $insert_data = array(
        'status' => isset($data['status']) ? $data['status'] : '',
        'create_date' => isset($data['create_date']) ? $data['create_date'] : '',
        'delete_data' => '',
      );
      $format = array(
        '%s',
        '%s',
        '%s',
      );
  
      $where = array('series_id' => $data['series_id'],'blog_id'=>$data['blog_id'],'user_id'=>$data['user_id']);
      $where_format = array('%d','%d','%d');
      $wpdb->update($this->sub_table, $insert_data, $where, $format, $where_format);
      
      
    }else{
      $insert_data = array(
        'series_id' => isset($data['series_id']) ? $data['series_id'] : '',
        'user_id' => isset($data['user_id']) ? $data['user_id'] : '',
        'blog_id' => isset($data['blog_id']) ? $data['blog_id'] : '',
        'status' => isset($data['status']) ? $data['status'] : '',
        'create_date' => isset($data['create_date']) ? $data['create_date'] : '',
        'delete_data' => isset($data['delete_data']) ? $data['delete_data'] : '',
      );
      $format = array(
        '%d',
        '%d',
        '%d',
        '%s',
        '%s',
        '%s',
      );
  
      $wpdb->insert($this->sub_table, $insert_data, $format);
    }
   


    /*
      $subscriber = $this->get_subscriber_user($code);
      //debug($subscriber);
      if( empty( $subscriber ) ){
        $subscriber = array();
        $subscriber[] = $user_id;
      }else{
        $subscriber = unserialize($subscriber);
        $subscriber[] = $user_id;
      }
      $subscriber = array_unique( $subscriber );
      $this->update_subscriber($subscriber,$code);*/
  }
  public function update_subscriber($subscriber, $code)
  {
    global $wpdb;
    $insert_data = array(
      'meta'  => serialize($subscriber),
    );

    $format = array(
      '%s',
    );
    $where = array('code' => $code,);
    $where_format = array('%d');
    $wpdb->update($this->table, $insert_data, $where, $format, $where_format);
  }
  public function remove_subscriber($data)
  {
    global $wpdb;

    $insert_data = array(
      'status' => 'deactive',
      'delete_data' => isset($data['delete_data']) ? $data['delete_data'] : '',
    );
    $format = array(
      '%s',
      '%s',
    );

    $where = array('series_id' => $data['series_id'],'blog_id'=>$data['blog_id'],'user_id'=>$data['user_id']);
    $where_format = array('%d','%d','%d');
    $wpdb->update($this->sub_table, $insert_data, $where, $format, $where_format);

    /*$subscribers = $this->get_subscriber_user($code);
    if (!empty($subscribers)) {
      $subscribers = unserialize($subscribers);
      $key = array_search($user_id, $subscribers);
      if ($key) {
        unset($subscribers[$key]);
        $subscribers = array_values(array_filter($subscribers));

        $this->update_subscriber($subscribers, $code);
      }

      //
    }*/
  }
}
