<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.3
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


require_once(DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');

class reportbeez
{

    var $pathToCommonTemplates;
    var $pathToMailbeez;

// class constructor
    function reportbeez()
    {
        $this->code = ''; // unique id for report-module
        $this->module = ''; //
        $this->version = '1.0'; // float value
        $this->required_mb_version = 2.0;
        $this->title = '';
        $this->description = '';
        $this->sort_order = '';
        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/reportbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = ''; // list of admin frontend action plugins ("file1;file2")
        $this->common_admin_action_plugins = ''; // list of common gui plugins ("file1;file2")
        $this->status_key = '';
        $this->icon = '../../common/images/reportbeez_module_icon.png';
        $this->description_image = '';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/reportbeez/';
        $this->documentation_key = '';
        $this->hidden = false; // hide submodule / module
        $this->is_configurable = true;
    }

// class methods
    function keys()
    {
        // to be done by instance
    }

    function install()
    {
        // to be done by instance
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // common methods


    function check()
    {
        if (!isset($this->_check)) {
            $check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $this->status_key . "'");
            $this->_check = mh_db_num_rows($check_query);
        }
        return $this->_check;
    }

    function remove()
    {
        $remove_keys = $this->keys();
        if (MH_PLATFORM == 'xtc') {
            // remove additional fields
            $xtc_text_keys = array();
            $keys = $this->keys();
            while (list(, $key_name) = each($keys)) {
                $xtc_text_keys[] = $key_name . '_TITLE';
                $xtc_text_keys[] = $key_name . '_DESC';
            }
            $remove_keys = array_merge($xtc_text_keys, $remove_keys);
        }

        return mh_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $remove_keys) . "')");
    }

    function get_module_id()
    {
        return $this->code;
    }

}

// end of class
?>
