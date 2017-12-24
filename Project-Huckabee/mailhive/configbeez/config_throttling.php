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

class config_throttling extends configbeez
{

// class constructor
    function config_throttling()
    {
        configbeez::configbeez();
        $this->code = 'config_throttling';
        $this->module = 'config_throttling'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_THROTTLING_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_THROTTLING_TEXT_DESCRIPTION;
        if (!defined('MAILBEEZ_THROTTLING_RATE')) {
            $this->icon = '../../common/images/lock.png';
            $this->is_configurable = false;
        } else {
            $this->icon = '../../filterbeez/filter_do_throttling_simple/icon.png';
            $this->is_configurable = true;
        }
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->sort_order = 6;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        //$this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
        //$this->admin_action_plugins = 'dashboard_modules.php';

        $this->documentation_key = 'config'; // leave empty if no documentation available
        $this->documentation_root = 'http://www.mailbeez.com/documentation/installation/';

        $this->display_as_submodule_of = 'config';
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
        if (defined('MAILBEEZ_THROTTLING_RATE')) {
            return array('MAILBEEZ_THROTTLING_RATE', 'MAILBEEZ_THROTTLING_MAX');
        }
    }

    function install()
    {
        return false;
    }

}

?>
