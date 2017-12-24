<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.1

  update functions
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

function mh_db_check_index_on_column_exists($table, $index_column) {
  $query_raw = "SHOW INDEX FROM " . $table . "";
  $query = mh_db_query($query_raw);
  while ($item = mh_db_fetch_array($query)) {
    if ($item['Column_name'] == $index_column) {
      return $item;
    }
  }
  // not found
  return false;
}

function mh_db_check_field_exists($table, $field) {
  $query_raw = "SHOW COLUMNS FROM " . $table . "";
  $query = mh_db_query($query_raw);
  while ($item = mh_db_fetch_array($query)) {
    if ($item['Field'] == $field) {
      return $item;
    }
  }
  // not found
  return false;
}

function mh_db_add_field($table, $field, $sql) {
  // check if exists
  $result = mh_db_check_field_exists($table, $field);

  if ($result == false) {
    if (is_array($sql)) {
      while (list(, $sql_item) = each($sql)) {
        mh_db_query($sql_item);
      }
    } else {
      mh_db_query($sql);
    }
  }
}

?>