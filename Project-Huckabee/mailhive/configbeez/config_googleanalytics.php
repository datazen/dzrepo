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

define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_STATUS', 'True');

class config_googleanalytics extends configbeez
{

// class constructor
    function config_googleanalytics()
    {
        configbeez::configbeez();
        $this->code = 'config_googleanalytics';
        $this->module = 'config_googleanalytics'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_DESCRIPTION;
        $this->icon = '../../common/images/icon_module.png';
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->display_as_submodule_of = 'config';
        $this->sort_order = 3;
        $this->enabled = ((MAILBEEZ_CONFIG_GOOGLEANALYTICS_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_CONFIG_GOOGLEANALYTICS_STATUS';

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
        return array('MAILBEEZ_MAILHIVE_GA_ENABLED', 'MAILBEEZ_MAILHIVE_GA_REWRITE_MODE', 'MAILBEEZ_MAILHIVE_GA_MEDIUM', 'MAILBEEZ_MAILHIVE_GA_SOURCE',);
    }

    function install()
    {
        return false;
    }

}

?>
