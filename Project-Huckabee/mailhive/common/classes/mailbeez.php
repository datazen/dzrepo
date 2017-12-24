<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.5
 */


///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez_mailer.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/classes/googleanalytics.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');
require_once(DIR_FS_CATALOG . 'mailhive/configbeez/config_process_control.php');

class mailbeez
{

    var $pathToCommonTemplates;
    var $pathToMailbeez;

// class constructor
    function mailbeez()
    {
        $this->code = ''; // unique id for admin-module
        $this->module = ''; // for tracking / can be shared by a group of modules /  same as folder name for e.g. templates
        $this->version = '1.0'; // float value
        $this->required_mb_version = 1.6;
        $this->iteration = 1; // e.g. for reminder
        $this->title = '';
        $this->description = '';
        $this->sort_order = '';
        $this->enabled = '';
        $this->googleanalytics_enabled = MAILBEEZ_MAILHIVE_GA_ENABLED; // by default global settings
        $this->googleanalytics_rewrite_mode = MAILBEEZ_MAILHIVE_GA_REWRITE_MODE; // by default rewrite all urls
        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/mailbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = ''; // list of admin frontend action plugins ("file1;file2")
        $this->common_admin_action_plugins = 'view_template.php;list_recipients.php;send_testemail.php;run_module.php'; // list of common gui plugins ("file1;file2")
        $this->sender = '';
        $this->sender_name = '';
        $this->status_key = '';
        $this->icon = '../../common/images/icon_module.png';
        $this->description_image = '../../common/images/icon_module_64.png';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/mailbeez/';
        $this->documentation_key = '';
        $this->has_submodules = false; // has submodules
        $this->is_submodule_of = ''; // is a submodule
        $this->display_as_submodule_of = ''; // display a submodule
        $this->removable = true; // can't be removed
        $this->stealth = false; // don't list as an installed module
        $this->hidden = false; // hide submodule / module
        $this->do_process = true; // a processable module
        $this->do_run = true; // run this module
        $this->is_editable = true; // allow editor
        $this->is_configurable = true; // edit configs
        $this->on_cfg_save_clear_template_c = false; // clear compiled templates when changing settings


        if (MH_PLATFORM == 'zencart' && defined('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE') && MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE == 'False') {
            // use mail templates from zencart
            $this->htmlTemplateResource = 'email_html_zencart.tpl'; // located in common/templates
        } else {
            $this->htmlTemplateResource = 'email_html.tpl'; // located in common/templates
        }

        $this->txtTemplateResource = 'email_txt.tpl'; // located in common/templates

        $this->htmlBodyTemplateResource = 'body_html.tpl'; // located in folder of this module
        $this->txtBodyTemplateResource = 'body_txt.tpl'; // located in folder of this module
        $this->subjectTemplateResource = 'subject.tpl'; // located in folder of this module

        $this->is_preview = false;
        $this->is_preview_theme = false;
        $this->is_preview_template = false;

        $this->txtTemplate = '';
        $this->htmlTemplate = '';
        $this->htmlBodyTemplate = '';
        $this->txtBodyTemplate = '';
        $this->subjectTemplate = '';

        $this->audience = array();
        $this->additionalFields = array();

        $this->pathToCommonTemplates = DIR_FS_CATALOG . 'mailhive/common/templates/';

        $this->pathToMailbeez = DIR_FS_CATALOG . 'mailhive/mailbeez/';

        @mhpi('mailbeez_3', $this);

        mh_event_log('MAILBEEZ_MODULE_INIT', 'class constructed', get_class($this), $class = '', $parameters = '');
    }

// class methods

    function dbdate($day)
    {
        $rawtime = strtotime(-1 * (int)$day . " days");
        $ndate = date("Ymd", $rawtime);
        return $ndate;
    }

    function getAudience()
    {
        // just an example
        $query_raw = "";

        $query = mh_db_query($query_raw);
        while ($item = mh_db_fetch_array($query)) {
            // mandatory fields:
            // - firstname
            // - lastname
            // - email_address
            // - customers_id (can be virtual but unique for this recipient)
            //
            // for dynamic iteration:
            // - _iteration
            // other keys are replaced while sending: $<key>

            $this->audience[$item['customers_id']] = array('firstname' => $item['customers_firstname'],
                                                           'lastname' => $item['customers_lastname'],
                                                           'email_address' => $item['customers_email_address']);
        }
    }

    function listAudience()
    {
        $this->getAudience();
        $this->outputListHeader();
        $row = 0;
        while (list($key, $mail) = each($this->audience)) {
            $row++;
            $this->outputListItem($mail, $row);
            echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">  scrolldown(); </SCRIPT>';
            echo str_repeat(" ", 4096); // force a flush
        }
        return $row;
    }

    function loadDefaultFields($email = 'TestEmail')
    {
        $fields = array();
        $fields = array('firstname' => 'TestFirstName',
                        'lastname' => 'TestLastName',
                        'email_address' => $email,
                        'customers_id' => 42,
                        'order_id' => 4242);

        $additionFields = $this->additionalFields;
        if (sizeof($additionFields) > 0) {
            foreach ($additionFields as $fieldKey => $testValue) {
                $fields[$fieldKey] = $testValue;
            }
        }

        return $fields;
    }

    function sendTest($email)
    {
        $audience = array();
        $audience[0] = $this->loadDefaultFields($email);

        list($audience[0]) = @mhpi('mailbeez_1', $audience[0], $this);

        return $this->process($audience, 'test');
    }

    function viewMail($format)
    {
        $out = 'default';

        $this->ga = new googleAnalytics(MAILBEEZ_MAILHIVE_GA_MEDIUM, $this->module, MAILBEEZ_MAILHIVE_GA_SOURCE);
        $this->loadAllTemplates();
        $mailbeez_mailer = new mailbeez_mailer($this);

        $smarty = new Smarty;
        $smarty->caching = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CACHING;
        $smarty->template_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEMPLATE_DIR; // root dir to templates
        $smarty->compile_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR;
        $smarty->config_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CONFIG_DIR;
        $smarty->compile_id = $this->code;

        $smarty->assign('viewTemplate', true);

        $mail = $this->loadDefaultFields();

        list($mail) = @mhpi('mailbeez_1', $mail);
        list($smarty, ,) = @mhpi('mailbeez_2', $smarty, $this, $mail);

        list($output_subject, $output_content_html, $output_content_txt) = mh_smarty_generate_mail($mailbeez_mailer, $mail, $this->module, $smarty);

        // build html email
        if ($format == 'html') {
            $out = $output_content_html;
        } elseif ($format == 'txt') {
            // build txt email
            $out = $output_content_txt;
        } else {
            $out = 'unknown format: ' . $format;
        }
        return $out;
    }

    //
    function getLastOrderId($customer_id)
    {
        $last_id_query = mh_db_query("select max(orders_id) as orders_id_last from " . TABLE_ORDERS . " where customers_id='" . (int)$customer_id . "'");
        $last_id = mh_db_fetch_array($last_id_query);
        return $last_id['orders_id_last'];
    }

    function keys()
    {
        // to be done by instance
    }

    function install()
    {
        // to be done by instance
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // common methods
    function process($audience = '', $mode = '')
    {
        //$customers_language = mh_get_languages_directory(DEFAULT_LANGUAGE);
        if ($audience == '') {
            $this->getAudience();
            $audience = $this->audience;
        } else {
            $this->audience = $audience;
        }


        $this->ga = new googleAnalytics(MAILBEEZ_MAILHIVE_GA_MEDIUM, $this->module, MAILBEEZ_MAILHIVE_GA_SOURCE);

        $this->loadAllTemplates();
        $mailbeez_mailer = new mailbeez_mailer($this);

        echo '<h2>Processing....<br>
			code: ' . $this->code . '<br>module: ' . $this->module . '</h2>';

        $this->update_process_lock();

        return $mailbeez_mailer->sendBeez($this->get_module_id(), $this->iteration, $mode);
    }

    function check()
    {
        if (!isset($this->_check)) {
            $check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $this->status_key . "'");
            $this->_check = mh_db_num_rows($check_query);
        }
        return $this->_check;
    }

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
            $remove_keys = array_merge($xtc_text_keys, $remove_keys);
        }

        return mh_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $remove_keys) . "')");
    }

    function get_module_id()
    {
        if ($this->is_submodule_of != '') {
            return $this->is_submodule_of . '/submodules/' . $this->code;
        }
        return $this->code;
    }

    function outputListHeader()
    {
        $submodule_str = '';
        echo '<h2>List Audience:</h2>
			code: ' . $submodule_str . $this->get_module_id() . ' -	module: ' . $this->module . '<br>
			';
    }

    function outputListItem($mail, $row)
    {
        echo '<div class="rn"><a name="1">' . $row . '</a></div>';
        echo '<div class="r">' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'] . '<br>';

        $additionFields = $this->additionalFields;
        if (sizeof($additionFields) > 0) {
            foreach ($additionFields as $fieldKey => $value) {
                echo ' ' . $fieldKey . ': ' . $mail[$fieldKey];
            }
        }
        echo '</div>';
    }

    function loadAllTemplates()
    {
        $this->loadTxtTemplateResource();
        $this->loadHtmlTemplateResource();
        $this->loadTxtBodyTemplateResource();
        $this->loadHtmlBodyTemplateResource();
        $this->loadSubjectTemplateResource();
    }

    function loadTxtTemplateResource()
    {
        list($this->txtTemplate, $this->txtTemplate_mtime) = $this->loadResource($this->pathToCommonTemplates . $this->txtTemplateResource);
    }

    function loadHtmlTemplateResource()
    {
        list($this->htmlTemplate, $this->htmlTemplate_mtime) = $this->loadResource($this->pathToCommonTemplates . $this->htmlTemplateResource);
    }

    function loadHtmlBodyTemplateResource()
    {
        list($this->htmlBodyTemplate, $this->htmlBodyTemplate_mtime) = $this->loadResource($this->pathToMailbeez . $this->module . '/email/' . $this->htmlBodyTemplateResource);
    }

    function loadTxtBodyTemplateResource()
    {
        list($this->txtBodyTemplate, $this->txtBodyTemplate_mtime) = $this->loadResource($this->pathToMailbeez . $this->module . '/email/' . $this->txtBodyTemplateResource);
    }

    function loadSubjectTemplateResource()
    {
        list($this->subjectTemplate, $this->subjectTemplate_mtime) = $this->loadResource($this->pathToMailbeez . $this->module . '/email/' . $this->subjectTemplateResource);
    }

    function loadResource($filename)
    {
        return array(file_get_contents($filename), filemtime($filename));
    }


    function beforeFilter($mail, $mode)
    {
        return $mail;
    }

    function beforeFilterData($mail, $mode)
    {
        return $mail;
    }

    function afterFilterData($mail, $mode)
    {
        return $mail;
    }

    function beforeFilterContent($mail, $mode)
    {
        return $mail;
    }

    function afterFilterContent($mail, $mode)
    {
        return $mail;
    }

    function beforeGenerate($mail, $mode)
    {
        return $mail;
    }

    function afterGenerateMail($mail, $mode)
    {
        return $mail;
    }

    function beforeFilterModify($mail, $mode)
    {
        return $mail;
    }

    function afterFilterModify($mail, $mode)
    {
        return $mail;
    }

    function afterFilter($mail, $mode)
    {
        return $mail;
    }


    function beforeSend($mail, $mode)
    {
        return $mail;
    }

    function afterSend($mail, $mode)
    {
        return false;
    }

    function replace_variables($text_in, $replace_variables)
    {
        $text = (is_array($text_in)) ? $text_in[0] : $text_in;

        if (is_array($replace_variables)) {
            uksort($replace_variables, "sortbykeylength");

            foreach ($replace_variables as $key => $value) {
                $text = str_replace('$' . $key, $value, $text);
            }
        }
        return $text;
    }

    function _rewriteImgSrc($input, $server)
    {
        return preg_replace('#<img src="#', '<img src="' . $server, $input);
    }

    // external callable methods, must start with external_
    // block this module
    function external_block($parameters)
    {
        list($customers_id, $email_address) = explode('|', base64_decode($parameters));

        $mailbeez_mailer = new mailbeez_mailer($this);

        $result = $mailbeez_mailer->block($this->module, $customers_id, $email_address, '0');

        mh_redirect(HTTP_SERVER . DIR_WS_CATALOG . FILENAME_MAILBEEZ_BLOCKGUI . '?module=' . $this->module . '&p=' . $parameters . '&result=' . $result . '&ub=' . base64_encode(HTTP_SERVER . DIR_WS_CATALOG . FILENAME_HIVE));
    }

    // external callable methods, must start with external_
    // block this module
    function external_unblock($parameters)
    {
        list($customers_id, $email_address) = explode('|', base64_decode($parameters));

        $mailbeez_mailer = new mailbeez_mailer($this);

        $result = $mailbeez_mailer->unblock($this->module, $customers_id, $email_address, '0');

        mh_redirect(HTTP_SERVER . DIR_WS_CATALOG . FILENAME_MAILBEEZ_UNBLOCKGUI . '?module=' . $this->module . '&p=' . $parameters . '&result=' . $result);
    }


    function update_process_lock($timestamp = '', $module = '')
    {
        if (MAILBEEZ_MAILHIVE_PROCESS_CONTROL == 'False' || !$GLOBALS['MAILBEEZ_MAILHIVE_PROCESS_CONTROL_INIT']) {
            return false;
        }

        if ($timestamp != -1) {
            $kill_check = config_process_control::check_kill(MAILBBEEZ_EVENTLOG_BATCH_ID);

            if ($kill_check) {
                mailbeez::update_process_lock('-1', 'mailhive_killed');
            }
        }

        $timestamp_lock = ($timestamp == '') ? time() : $timestamp;
        $module_lock = ($module == '' && method_exists($this, 'get_module_id')) ? $this->get_module_id() : $module;
        $data = array('lock_key' => 'RUN_LOCK_TIMESTAMP',
                      'lock_value' => ($module_lock . '|' . $timestamp_lock),
                      'batch_id' => MAILBBEEZ_EVENTLOG_BATCH_ID,
                      'date_added' => 'now()');
        mh_db_perform(TABLE_MAILBEEZ_PROCESS, $data);

        if ($timestamp == -1) {
            // killed
            echo 'process killed';
            exit();
        }

        if ($timestamp == 1) {
            // clean up
            return mh_db_query("delete from " . TABLE_MAILBEEZ_PROCESS . " where batch_id != '" . MAILBBEEZ_EVENTLOG_BATCH_ID . "'");
        }
    }


    function check_process_lock()
    {

        if (MAILBEEZ_MAILHIVE_PROCESS_CONTROL == 'False') {
            return false;
        }

        $lock_period = MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD;

        // see if there is a running process

        $check_query_sql = "select lock_value, batch_id
                                from " . TABLE_MAILBEEZ_PROCESS . "
                            where lock_key='RUN_LOCK_TIMESTAMP' order by lock_id desc ";

        $check_query = mh_db_query($check_query_sql);

        if (mh_db_num_rows($check_query) > 0) {
            $check = mh_db_fetch_array($check_query);

            list($module, $lock_timestamp) = explode('|', $check['lock_value']);

            if ($check['batch_id'] == MAILBBEEZ_EVENTLOG_BATCH_ID || $check['batch_id'] == 'mailhive_closed') {
                return false; // current batch or closed batch
            }

            if ($lock_timestamp + $lock_period > time()) {
                return $lock_timestamp + $lock_period; // process locked
            } else {
                return false; // lock not valid
            }
        }
        return false; // no lock yet
    }


    function get_process_lock_info($process_lock_timestamp)
    {
        return "MailHive process locked until " . date('Y-m-d G:i:s ', $process_lock_timestamp) . " (" . ($process_lock_timestamp - time()) . " secs left) ";
    }

    function get_do_run()
    {
        if (defined('MAILBEEZ_CRON_ADVANCED_STATUS') && MAILBEEZ_CRON_ADVANCED_STATUS == 'True') {
            require_once(DIR_FS_CATALOG . 'mailhive/configbeez/config_cron_advanced.php');
            return config_cron_advanced::check_do_run_module($this->module, $this->get_module_id());
        }
        return $this->do_run;
    }


    function apply_theme($theme_id = '', $template_id = '')
    {
        $this->is_preview = ($template_id == '' && $theme_id == '') ? false : true;
        $this->is_preview_template = ($template_id != '') ? true : false;
        $this->is_preview_theme = ($theme_id != '') ? true : false;

        if ($template_id == '') {
            $layout_id = (isset($this->layout_id)) ? $this->layout_id : 'default';
        } else {
            $layout_id = $template_id;
        }

        if (!isset($this->htmlBodyTemplateResource_default)) {
            $this->htmlBodyTemplateResource_default = $this->htmlBodyTemplateResource;
        }

        $template_layout = '';

        if ($layout_id != 'default' && $template_id != '' && $this->is_preview) {
            $template_layout = $layout_id . '_';
        }
        elseif ($layout_id != 'default' && !$this->is_preview) {
            $template_layout = $layout_id . '_';
        }


        $this->htmlBodyTemplateResource = $template_layout . $this->htmlBodyTemplateResource_default;


        $theme_layout = '';
        if ($theme_id != 'default' && $theme_id != '') {
            $theme_layout = $theme_id . '_';
        }
        if ($this->is_preview_theme && isset($this->htmlTemplateResource_default)) {
            $this->htmlTemplateResource = $this->htmlTemplateResource_default;
        }
        $this->htmlTemplateResource = $theme_layout . $this->htmlTemplateResource;
    }
}

// end of class	
?>