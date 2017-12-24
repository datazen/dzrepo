<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

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

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez_mailer.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/classes/googleanalytics.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');

require_once(DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');

class dashboardbeez
{

    var $pathToCommonTemplates;
    var $pathToMailbeez;

// class constructor
    function dashboardbeez()
    {
        $this->code = ''; // unique id for report-module
        $this->module = ''; //
        $this->required_mb_version = 2.0;
        $this->title = '';
        $this->description = '';
        $this->sort_order = '';
        $this->enabled = '';
        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/filterbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = ''; // list of admin frontend action plugins ("file1;file2")
        $this->common_admin_action_plugins = ''; // list of common gui plugins ("file1;file2")
        $this->icon = '../../common/images/icon_module.png';
        $this->description_image = '../../common/images/icon_module_64.png';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/dashboardbeez/';
        $this->documentation_key = '';
        $this->hidden = false; // hide submodule / module
        $this->is_configurable = true;
    }

// class methods
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
            $remove_keys = array_merge($xtc_text_keys, $this->keys());
        }

        return mh_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $remove_keys) . "')");
    }

    function isEnabled()
    {
        return $this->enabled;
    }

    function get_module_id()
    {
        return $this->code;
    }


}

// end of class
?>
