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

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez.php');

class review_promo extends mailbeez
{

// class constructor
    function review_promo()
    {
        // call constructor
        mailbeez::mailbeez();

        $this->code = 'review_promo';
        $this->module = 'review_promo'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_REVIEW_PROMO_TEXT_TITLE;
        $this->description = MAILBEEZ_REVIEW_PROMO_TEXT_DESCRIPTION;
        if (defined('MAILBEEZ_REVIEW_ADVANCED_STATUS')) {
            $this->hidden = true;
        }
        else {
            $this->icon = '../../common/images/lock.png';
        }
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->sort_order = 1;
        $this->documentation_key = 'review_advanced'; // leave empty if no documentation available
        $this->common_admin_action_plugins = '';
        $this->do_process = false; // a processable module
        $this->is_editable = false; // allow editor
        $this->is_configurable = false;

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

    function install()
    {
        return false;
    }

}

?>
