<?php
  $fields = mysql_list_fields(DB_DATABASE, 'customers');
  $columns = mysql_num_fields($fields);
  for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}
  if (in_array('customers_group_id', $field_array)) {
    // criteria to show only Allowed Group Products
    $customer_group_id = array();
    if (isset($_SESSION['customer_id']) ) {
      if($_SESSION['customer_id'] == "") {
        $customer_group_id[] = "G";
      } else {
        $customer_group_id = tep_get_customers_access_group($_SESSION['customer_id']);
      }
    } else {
      $customer_group_id[] = "G";
    }
    $customer_access = tep_get_access_sql('p.products_group_access', $customer_group_id);
  }
?>