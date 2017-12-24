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

class config_template_engine extends configbeez
{

// class constructor
    function config_template_engine()
    {
        configbeez::configbeez();
        $this->code = 'config_template_engine';
        $this->module = 'config_template_engine'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_DESCRIPTION;
        $this->icon = '../../common/images/icon_module.png';
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->display_as_submodule_of = 'config';
        $this->sort_order = 5;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_STATUS';
        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = 'clear_compile_dir.php';

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

        if (MH_PLATFORM != 'xtc' && MH_PLATFORM != 'gambio') {
            return array('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE', 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH');
        }

        return array('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE');
    }

    function install()
    {
        return false;
    }

}

?>
