<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// make path work from admin
require_once(DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez.php');

class mailbeez_feedback extends mailbeez {

  // class constructor
  function mailbeez_feedback() {
    // call constructor
    mailbeez::mailbeez();

    // set some stuff:	
    $this->code = 'mailbeez_feedback';
    $this->module = 'mailbeez_feedback';
    $this->version = '2.0'; // float value
    $this->required_mb_version = 2.0; // required mailbeez version
    $this->iteration = date('Ym', strtotime('-1 month')); // last month
    $this->title = MAILBEEZ_MAILBEEZ_FEEDBACK_TEXT_TITLE;
    $this->description = MAILBEEZ_MAILBEEZ_FEEDBACK_TEXT_DESCRIPTION;
    $this->description_image = 'icon_64.png';
    $this->icon = 'icon.png';
    $this->sort_order = 1000;
    $this->enabled = ((MAILBEEZ_MAILBEEZ_FEEDBACK_STATUS == 'True') ? true : false);
    $this->sender = MAILBEEZ_MAILBEEZ_FEEDBACK_SENDER;
    $this->sender_name = MAILBEEZ_MAILBEEZ_FEEDBACK_SENDER_NAME;
    $this->status_key = 'MAILBEEZ_MAILBEEZ_FEEDBACK_STATUS';

    $this->documentation_key = $this->module; // leave empty if no documentation available
    // $this->documentation_root = 'http:://yoursite.com/' // modify documentation root if necessary			

    $this->htmlBodyTemplateResource = 'body_html.tpl'; // located in folder of this module
    $this->txtBodyTemplateResource = 'body_txt.tpl'; // located in folder of this module			
    $this->subjectTemplateResource = 'subject.tpl'; // located in folder of this module

    $this->audience = array();
    $this->additionalFields = array('modules' => MAILBEEZ_INSTALLED_VERSIONS, 'number_of_emails' => 42, 'iteration' => $this->iteration, 'msg' => MAILBEEZ_MAILBEEZ_FEEDBACK_MSG, 'platform' => MH_PLATFORM . ' - ' . PROJECT_VERSION); // list of additional fields to show in listing with testvalues
    // list of additional fields to show in listing with testvalues used for Test-Mail
  }

// class methods
  function getAudience() {
    $id = -42; // the answer... ;-)
    // early check to avoid processing when email was already sent
    $mb_chk = new mailbeez_mailer($this);
    $chk_result = $mb_chk->check($this->module, $this->iteration, $id);
    if ($chk_result != false) {
      // this iteration was already sent
      return false;
    }

    $query_sql = "select count(*) as count from " . TABLE_MAILBEEZ_TRACKING . " where date_sent like '" . date('Y-m', strtotime('-1 month')) . "%'";

    $count_query = mh_db_query($query_sql);
    $count = mh_db_fetch_array($count_query);

    $this->audience[$id] = array('firstname' => 'MailBeez',
        'lastname' => 'Feedback',
        'email_address' => 'mailbeez_feedback@mailbeez.com',
        'customers_id' => $id,
        'modules' => MAILBEEZ_INSTALLED_VERSIONS,
        'number_of_emails' => $count['count'],
        'iteration' => $this->iteration,
        'msg' => MAILBEEZ_MAILBEEZ_FEEDBACK_MSG,
        'platform' => MH_PLATFORM . ' - ' . PROJECT_VERSION
    );

    return $this->audience;
  }

  // installation methods

  function keys() {
    return array('MAILBEEZ_MAILBEEZ_FEEDBACK_STATUS', 'MAILBEEZ_MAILBEEZ_FEEDBACK_SENDER', 'MAILBEEZ_MAILBEEZ_FEEDBACK_SENDER_NAME', 'MAILBEEZ_MAILBEEZ_FEEDBACK_MSG');
  }

  function install() {

    mh_insert_config_value(array('configuration_title' => 'Send mailbeez_feedback',
        'configuration_key' => 'MAILBEEZ_MAILBEEZ_FEEDBACK_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Do you want to send  mailbeez_feedback emails?',
        'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
    ));

    mh_insert_config_value(array('configuration_title' => 'your personal message',
        'configuration_key' => 'MAILBEEZ_MAILBEEZ_FEEDBACK_MSG',
        'configuration_value' => STORE_NAME . ' loves MailBeez :-) ',
        'configuration_description' => 'please enter your personal message',
        'set_function' => ''
    ));

    mh_insert_config_value(array('configuration_title' => 'sender email',
        'configuration_key' => 'MAILBEEZ_MAILBEEZ_FEEDBACK_SENDER',
        'configuration_value' => STORE_OWNER_EMAIL_ADDRESS,
        'configuration_description' => 'sender email',
        'set_function' => ''
    ));

    mh_insert_config_value(array('configuration_title' => 'sender name',
        'configuration_key' => 'MAILBEEZ_MAILBEEZ_FEEDBACK_SENDER_NAME',
        'configuration_value' => STORE_NAME,
        'configuration_description' => 'sender email',
        'set_function' => ''
    ));
  }

}

?>
