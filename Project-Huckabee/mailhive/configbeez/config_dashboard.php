<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/configbeez.php');

class config_dashboard extends configbeez
{

// class constructor
    function config_dashboard()
    {
        configbeez::configbeez();
        $this->code = 'config_dashboard';
        $this->module = 'config_dashboard'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_DASHBOARD_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_DASHBOARD_TEXT_DESCRIPTION;
        $this->icon = '../../common/images/icon_module.png';
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->display_as_submodule_of = 'config';
        $this->sort_order = 1;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_CONFIG_DASHBOARD_STATUS';
        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = 'dashboard_modules.php';

        $this->documentation_key = 'config'; // leave empty if no documentation available
        $this->documentation_root = 'http://www.mailbeez.com/documentation/installation/';

    }

// class methods

    function getAudience()
    {
        return false;
    }

    function check()
    {
        return true;
    }

    function remove()
    {
        return false;
    }

    // installation methods

    function keys()
    {
        return array('MAILBEEZ_CONFIG_DASHBOARD_START');
    }

    function install()
    {
        return false;
    }

}

?>
