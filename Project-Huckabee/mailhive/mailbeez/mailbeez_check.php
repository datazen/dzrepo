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

class mailbeez_check extends mailbeez {

  // class constructor
  function mailbeez_check() {
    // call constructor
    mailbeez::mailbeez();

    // set some stuff:	
    $this->code = 'mailbeez_check';
    $this->module = 'mailbeez_check';
    $this->version = '2.2'; // float value
    $this->required_mb_version = 2.2; // required mailbeez version
    $this->iteration = $this->_getCheckInterval();
    $this->title = MAILBEEZ_MAILBEEZ_CHECK_TEXT_TITLE;
    $this->description = MAILBEEZ_MAILBEEZ_CHECK_TEXT_DESCRIPTION;
    $this->description_image = 'icon_64.png';
    $this->icon = 'icon.png';
    $this->sort_order = 1010;
    $this->enabled = ((MAILBEEZ_MAILBEEZ_CHECK_STATUS == 'True') ? true : false);
    $this->sender = MAILBEEZ_MAILBEEZ_CHECK_SENDER;
    $this->sender_name = MAILBEEZ_MAILBEEZ_CHECK_SENDER_NAME;
    $this->status_key = 'MAILBEEZ_MAILBEEZ_CHECK_STATUS';

    $this->documentation_key = $this->module; // leave empty if no documentation available
    // $this->documentation_root = 'http:://yoursite.com/' // modify documentation root if necessary			

    $this->htmlBodyTemplateResource = 'body_html.tpl'; // located in folder of this module
    $this->txtBodyTemplateResource = 'body_txt.tpl'; // located in folder of this module			
    $this->subjectTemplateResource = 'subject.tpl'; // located in folder of this module


    $this->htmlUpdateListTemplateResource = 'updatelist_html.tpl'; // located in folder of this module
    $this->txtUpdateListResource = 'updatelist_txt.tpl'; // located in folder of this module			

    $this->audience = array();
    $this->additionalFields = array('modules' => MAILBEEZ_INSTALLED_VERSIONS, 'iteration' => $this->iteration, 'check_result_html' => 'run module to see list', 'check_result_txt' => 'run module to see list'); // list of additional fields to show in listing with testvalues
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

    $query_sql = "select count(*) as count from " . TABLE_MAILBEEZ_TRACKING . " ";

    $count_query = mh_db_query($query_sql);
    $count = mh_db_fetch_array($count_query);

    $htmlListOut = '';
    $txtListOut = '';
    $check_result_content = $this->_getCurlContent(MAILBEEZ_VERSION_CHECK_URL . '&api=true');
    preg_match('/###(.*)###/', $check_result_content, $matches);
    list($check_result_upd_ser, $check_result_new_ser) = explode("#", $matches[1]);
    $check_result_upd_array = unserialize($check_result_upd_ser);
    $check_result_new_array = unserialize($check_result_new_ser);

    $check_result_array = array_merge_recursive($check_result_upd_array, $check_result_new_array);

    // load subtemplates
    $htmlUpdateListTemplate = $this->loadResource($this->pathToMailbeez . $this->module . '/email/' . $this->htmlUpdateListTemplateResource);
    $txtUpdatetListTemplate = $this->loadResource($this->pathToMailbeez . $this->module . '/email/' . $this->txtUpdateListResource);

    if (is_array($check_result_array) && sizeof($check_result_array) > 0) {
      foreach ($check_result_array as $module_type_key => $module_type_array) {
        $htmlListOut .= '<h2>' . $module_type_key . '</h2>';
        $txtListOut .= $module_type_key . "\n\r";
        foreach ($module_type_array as $module_key => $module_item) {
          $subTemplateVars = array('url' => $module_item['url'],
              'title' => $module_item['title'],
              'version' => $module_item['version'],
              'teaser' => $module_item['teaser'],
              'module_type' => $module_type_key,
              'img' => ($module_item['pro']) ? '<img src="' . $module_item['image'] . '" width="32" height="32" align="left" hspace="5">' : '',
              'type' => ($module_item['is_new']) ? '<b><font color="#ff0000">' . MAILBEEZ_MAILBEEZ_FEEDBACK_TAG_NEW . '</font></b> ' : '<b>' . MAILBEEZ_MAILBEEZ_FEEDBACK_TAG_UPDATE . '</b> ',
              'code' => $module_key . '/' . MH_LINKID_1,
              'pro' => ($module_item['pro']) ? 'Premium Download: ' . $module_item['price'] . '<br>' : ''
          );
          $htmlListOut .= $this->replace_variables($htmlUpdateListTemplate, $subTemplateVars);
          $txtListOut .= $this->replace_variables($txtUpdatetListTemplate, $subTemplateVars);
        }
      }
    } else {
      
    }

    preg_match('/#!#(.*)#!#/', $check_result_content, $matches_msg);
    $msg_ser = $matches_msg[1];
    $msg = unserialize($msg_ser);

    $this->audience[$id] = array('firstname' => MAILBEEZ_MAILBEEZ_CHECK_SENDER_NAME,
        'lastname' => ' - MailBeez Version Check',
        'email_address' => MAILBEEZ_MAILBEEZ_CHECK_SENDER,
        'customers_id' => $id,
        'iteration' => $this->iteration,
        'check_result_html' => $htmlListOut,
        'check_result_txt' => $txtListOut,
        'msg' => $msg,
        'number_of_emails' => $count['count']
    );

    return $this->audience;
  }

  function _getCheckInterval() {
    switch (MAILBEEZ_MAILBEEZ_CHECK_INTERVAL) {
      case 'every day':
        return date('Yz');
        break;
      case 'every week':
        return date('YW');
        break;
      case 'every month':
        return date('Ym');
        break;
    }
  }

  function _getCurlContent($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $content = curl_exec($ch);

    if (empty($content)) {
      print curl_error($ch);
    } else {
      $info = curl_getinfo($ch);
      //print_r($info);
    }

    curl_close($ch);
    return $content;
  }

  // installation methods

  function keys() {
    return array('MAILBEEZ_MAILBEEZ_CHECK_STATUS', 'MAILBEEZ_MAILBEEZ_CHECK_SENDER', 'MAILBEEZ_MAILBEEZ_CHECK_SENDER_NAME', 'MAILBEEZ_MAILBEEZ_CHECK_INTERVAL');
  }

  function install() {

    mh_insert_config_value(array('configuration_title' => 'Send mailbeez_check reminder',
        'configuration_key' => 'MAILBEEZ_MAILBEEZ_CHECK_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Do you want to send  mailbeez_check emails?',
        'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
    ));

    mh_insert_config_value(array('configuration_title' => 'Interval',
        'configuration_key' => 'MAILBEEZ_MAILBEEZ_CHECK_INTERVAL',
        'configuration_value' => 'every week',
        'configuration_description' => 'How often do yo want to receive an email with update information?',
        'set_function' => 'mh_cfg_select_option(array(\'every day\', \'every week\', \'every month\'), '
    ));


    mh_insert_config_value(array('configuration_title' => 'sender and receiver email',
        'configuration_key' => 'MAILBEEZ_MAILBEEZ_CHECK_SENDER',
        'configuration_value' => STORE_OWNER_EMAIL_ADDRESS,
        'configuration_description' => 'sender email',
        'set_function' => ''
    ));

    mh_insert_config_value(array('configuration_title' => 'sender and receiver name',
        'configuration_key' => 'MAILBEEZ_MAILBEEZ_CHECK_SENDER_NAME',
        'configuration_value' => STORE_NAME,
        'configuration_description' => 'sender email',
        'set_function' => ''
    ));
  }

}

?>
