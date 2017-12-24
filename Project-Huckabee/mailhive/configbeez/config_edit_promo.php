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

class config_edit_promo extends configbeez
{

// class constructor
    function config_edit_promo()
    {
        configbeez::configbeez();
        $this->code = 'config_edit_promo';
        $this->module = 'config_edit_promo'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_EDIT_PROMO_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_EDIT_PROMO_TEXT_DESCRIPTION;
        if (defined('MAILBEEZ_CONFIG_TMPLMNGR_STATUS') || defined('MAILBEEZ_CONFIG_TMPLMNGR_LNG_STATUS')) {
            $this->hidden = true;
        }
        else {
            $this->icon = '../../common/images/lock.png';
        }
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module

        $this->sort_order = 10;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);

        $this->documentation_key = 'config_tmplmgnr'; // leave empty if no documentation available
        $this->is_configurable = false;
    }

// class methods

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
    }

    function install()
    {
        return false;
    }

}

?>
