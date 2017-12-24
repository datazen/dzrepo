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

// could be in language-file
// just easier to define it in the mailbee
mh_define('MAILBEEZ_BIRTHDAY_TEXT_TITLE', 'Birthday Greetings');
mh_define('MAILBEEZ_BIRTHDAY_TEXT_DESCRIPTION', 'Be nice and remember the Birthday of your customers - this MailBeez Module sends birthday greetings.');

class birthday extends mailbeez {

  // class constructor
  function birthday() {
    // call constructor
    mailbeez::mailbeez();

    // set some stuff:	
    $this->code = 'birthday';
    $this->module = 'birthday';
    $this->version = '2.1'; // float value
    $this->required_mb_version = 2.03; // required mailbeez version
    $this->iteration = date('Y'); // year
    $this->title = MAILBEEZ_BIRTHDAY_TEXT_TITLE;
    $this->description = MAILBEEZ_BIRTHDAY_TEXT_DESCRIPTION;
    $this->description_image = 'birthday.png';
    $this->icon = '../../common/images/calendar_icon.png';
    $this->sort_order = MAILBEEZ_BIRTHDAY_SORT_ORDER;
    $this->enabled = ((MAILBEEZ_BIRTHDAY_STATUS == 'True') ? true : false);
    $this->googleanalytics_enabled = ((MAILBEEZ_BIRTHDAY_GA == 'True') ? 'True' : 'False');
    $this->googleanalytics_rewrite_mode = ((MAILBEEZ_BIRTHDAY_GA_MODE == 'default') ? MAILBEEZ_MAILHIVE_GA_REWRITE_MODE : MAILBEEZ_BIRTHDAY_GA_MODE);
    $this->sender = MAILBEEZ_BIRTHDAY_SENDER;
    $this->sender_name = MAILBEEZ_BIRTHDAY_SENDER_NAME;
    $this->status_key = 'MAILBEEZ_BIRTHDAY_STATUS';

    $this->documentation_key = $this->module; // leave empty if no documentation available
    // $this->documentation_root = 'http:://yoursite.com/' // modify documentation root if necessary

    $this->htmlBodyTemplateResource = 'body_html.tpl'; // located in folder of this module
    $this->txtBodyTemplateResource = 'body_txt.tpl'; // located in folder of this module			
    $this->subjectTemplateResource = 'subject.tpl'; // located in folder of this module

    $this->audience = array();
    $this->additionalFields = array('customers_id' => '007', 'date_of_birth' => '31.01.'); // list of additional fields to show in listing with testvalues
    // list of additional fields to show in listing with testvalues used for Test-Mail
  }

// class methods
  function getAudience() {

    /* old query - doesn't always find the right date 
      $query_raw = "select c.customers_firstname, c.customers_lastname,
      c.customers_id, c.customers_email_address, c.customers_dob, date_format(c.customers_dob, '%d.%m.') as date_of_birth
      from " . TABLE_CUSTOMERS . " c
      where date_format(c.customers_dob,'%m%d') < ( date_format(now(),'%m%d')+ " . MAILBEEZ_BIRTHDAY_BEFORE_DAYS . ") and
      date_format(c.customers_dob,'%m%d') > ( date_format(now(),'%m%d')- ". MAILBEEZ_BIRTHDAY_PASSED_DAYS_SKIP . ")";
     */

    // new query - thanks to Nico
    $query_raw = "select c.customers_firstname, c.customers_lastname, c.customers_id, c.customers_email_address, c.customers_dob, date_format(c.customers_dob, '%d.%m.') as date_of_birth
										from " . TABLE_CUSTOMERS . " c
										where 
											date_format(c.customers_dob,'%m%d') < date_format( DATE_ADD(now(), INTERVAL " . MAILBEEZ_BIRTHDAY_BEFORE_DAYS . " DAY),'%m%d') and
											date_format(c.customers_dob,'%m%d') > date_format(SUBDATE(now(), INTERVAL " . MAILBEEZ_BIRTHDAY_PASSED_DAYS_SKIP . " DAY), '%m%d')";



    $query = mh_module_query($this, $query_raw);

    // for early check
    $mb_chk = new mailbeez_mailer($this);

    while ($item = mh_db_fetch_array($query)) {
      // mandatory fields:
      // - firstname
      // - lastname
      // - email_address
      // - customers-id -> block
      // other keys are replaced while sending: $<key>
      // early check to avoid processing when email was already sent
      $chk_result = $mb_chk->check($this->module, $this->iteration, $item['customers_id']);
      if ($chk_result != false) {
        // this iteration for this customer was already sent -> skip
        continue;
      }

      $this->audience[$item['customers_id']] = array('firstname' => $item['customers_firstname'],
          'lastname' => $item['customers_lastname'],
          'email_address' => $item['customers_email_address'],
          'customers_id' => $item['customers_id'],
          'date_of_birth' => $item['date_of_birth']
      );
    }
    return $this->audience;
  }

  // installation methods

  function keys() {
    return array('MAILBEEZ_BIRTHDAY_STATUS', 'MAILBEEZ_BIRTHDAY_BEFORE_DAYS', 'MAILBEEZ_BIRTHDAY_PASSED_DAYS_SKIP', 'MAILBEEZ_BIRTHDAY_SENDER', 'MAILBEEZ_BIRTHDAY_SENDER_NAME', 'MAILBEEZ_BIRTHDAY_SORT_ORDER', 'MAILBEEZ_BIRTHDAY_GA', 'MAILBEEZ_BIRTHDAY_GA_MODE');
  }

  function install() {
    mh_insert_config_value(array('configuration_title' => 'Send birthday reminder',
        'configuration_key' => 'MAILBEEZ_BIRTHDAY_STATUS',
        'configuration_value' => 'False',
        'configuration_description' => 'Do you want to send birthday emails?',
        'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
    ));

    mh_insert_config_value(array('configuration_title' => 'Set days before',
        'configuration_key' => 'MAILBEEZ_BIRTHDAY_BEFORE_DAYS',
        'configuration_value' => '1',
        'configuration_description' => 'number of days before birthday sending the emails',
        'set_function' => ''
    ));

    mh_insert_config_value(array('configuration_title' => 'Set days to skip after',
        'configuration_key' => 'MAILBEEZ_BIRTHDAY_PASSED_DAYS_SKIP',
        'configuration_value' => '2',
        'configuration_description' => 'number of days after which do skip the birthday email (in case cron job failed)',
        'set_function' => ''
    ));

    mh_insert_config_value(array('configuration_title' => 'sender email',
        'configuration_key' => 'MAILBEEZ_BIRTHDAY_SENDER',
        'configuration_value' => STORE_OWNER_EMAIL_ADDRESS,
        'configuration_description' => 'sender email',
        'set_function' => ''
    ));

    mh_insert_config_value(array('configuration_title' => 'sender name',
        'configuration_key' => 'MAILBEEZ_BIRTHDAY_SENDER_NAME',
        'configuration_value' => STORE_NAME,
        'configuration_description' => 'sender email',
        'set_function' => ''
    ));


    mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
        'configuration_key' => 'MAILBEEZ_BIRTHDAY_SORT_ORDER',
        'configuration_value' => '20',
        'configuration_description' => 'Sort order of display. Lowest is displayed first.',
        'set_function' => ''
    ));


    mh_insert_config_value(array('configuration_title' => 'Google Analytics Integration',
        'configuration_key' => 'MAILBEEZ_BIRTHDAY_GA',
        'configuration_value' => 'True',
        'configuration_description' => 'Do you want to enable URL rewrite to track this MailBeez Module in Google Analytics?',
        'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
    ));

    mh_insert_config_value(array('configuration_title' => 'Google Analytics Integration URL rewrite mode',
        'configuration_key' => 'MAILBEEZ_BIRTHDAY_GA_MODE',
        'configuration_value' => 'default',
        'configuration_description' => 'Google Analytics URL Rewrite Mode',
        'set_function' => 'mh_cfg_select_option(array(\'default\', \'all\', \'shop\'), '
    ));
  }

}

?>
