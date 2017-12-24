<?php
/*
/*
  $Id: crespayflowpro.php,v 3.0 2009/12/15 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  DUMMY MODULE
*/

  class crespayflowpro{
    var $code, $title, $description, $enabled;

    // class constructor
    function crespayflowpro() {
      $this->code = 'crespayflowpro';
      $this->title = MODULE_PAYMENT_CRESPAYFLOWPRO_TEXT_TITLE;
      $this->subtitle = MODULE_PAYMENT_CRESPAYFLOWPRO_TEXT_SUBTITLE;
      $this->description = MODULE_PAYMENT_CRESPAYFLOWPRO_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_CRESPAYFLOWPRO_SORT_ORDER;
      $this->enabled = false;
      $this->pci = true;
    }

    // class methods
    function update_status() {
      $this->enabled = false;
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return false;
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      return false;
    }

    function install() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");       
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayPal Payflow Pro via CRE Secure', 'MODULE_PAYMENT_CRESPAYFLOWPRO_STATUS', 'True', 'Do you want to enable PayPal Payflow Pro?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_CRESPAYFLOWPRO_STATUS');
    }
  }
?>