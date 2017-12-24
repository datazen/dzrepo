<?php
/*
  WebMakers.com Added: Free Payments and Shipping
  Written by Linda McGrath osCOMMERCE@WebMakers.com
  http://www.thewebmakerscorner.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class freecharger {
    var $code, $title, $description, $enabled;

// class constructor
    function freecharger() {
      global $order;
      $this->code = 'freecharger';
      $this->title = MODULE_PAYMENT_FREECHARGER_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_FREECHARGER_TEXT_DESCRIPTION;
      if (defined('MODULE_PAYMENT_FREECHARGER_SORT_ORDER')) {
        $this->sort_order = (int)MODULE_PAYMENT_FREECHARGER_SORT_ORDER;
      } else {
        $this->sort_order = '';
      }

      if (defined('MODULE_PAYMENT_FREECHARGER_STATUS')) {
        $this->enabled = ((MODULE_PAYMENT_FREECHARGER_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }

      if (defined('MODULE_PAYMENT_FREECHARGER_ORDER_STATUS_ID')) {
        if ((int)MODULE_PAYMENT_FREECHARGER_ORDER_STATUS_ID > 0) {
          $this->order_status = MODULE_PAYMENT_FREECHARGER_ORDER_STATUS_ID;
          $_SESSION['payment']='freecharger';
        } else {
          if (isset($_SESSION['payment']) &&  $_SESSION['payment'] == 'freecharger') {
            $_SESSION['payment']='';
          }
        }
      } else {
        $this->order_status = 0;
      }
      $this->pci = TRUE;
      if (is_object($order)) $this->update_status();

      $this->email_footer = MODULE_PAYMENT_FREECHARGER_TEXT_EMAIL_FOOTER;
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_FREECHARGER_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_FREECHARGER_ZONE . "' and ( zone_country_id = 0 or zone_country_id = '" . $order->billing['country']['id'] . "' ) order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return array('title' => MODULE_PAYMENT_FREECHARGER_TEXT_DESCRIPTION);
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
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_FREECHARGER_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Free Charge Module', 'MODULE_PAYMENT_FREECHARGER_STATUS', 'True', 'Do you want to accept Free Charge payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Free Charge Sort order of display.', 'MODULE_PAYMENT_FREECHARGER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Free Charge Payment Zone', 'MODULE_PAYMENT_FREECHARGER_ZONE', '0', 'If a zone is selected, enable this payment method for that zone only.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Free Charge Set Order Status', 'MODULE_PAYMENT_FREECHARGER_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_FREECHARGER_STATUS', 'MODULE_PAYMENT_FREECHARGER_ZONE', 'MODULE_PAYMENT_FREECHARGER_ORDER_STATUS_ID', 'MODULE_PAYMENT_FREECHARGER_SORT_ORDER');
    }
  }
?>