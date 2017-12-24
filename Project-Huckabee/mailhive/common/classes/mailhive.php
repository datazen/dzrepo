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


class mailHive
{

    var $modules, $selected_module;

// class constructor
    function mailHive($module = '')
    {
        global $PHP_SELF, $file_extension;

        if (defined('MAILBEEZ_INSTALLED') && mh_not_null(MAILBEEZ_INSTALLED)) {
            $this->modules = explode(';', MAILBEEZ_INSTALLED);

            reset($this->modules);
            while (list(, $mailbee) = each($this->modules)) {
                //if ($mailbee == 'config.php') {
                if (preg_match('/^config/', $mailbee)) {
                    // exclude config modules
                    continue;
                }
                include('mailhive/mailbeez/' . $mailbee);
                $module = substr($mailbee, 0, strrpos($mailbee, '.'));
                $class = mh_get_class_name($mailbee);
                $module_directory_current = DIR_FS_CATALOG . 'mailhive/mailbeez/';
                $module_path = mh_get_class_path($class);
                mh_load_modules_language_files($module_directory_current, $module_path, $file_extension);
                $GLOBALS[$module] = new $class;
            }
        }
    }

// class methods
    function update_status()
    {
        if (is_array($this->modules)) {
            if (is_object($GLOBALS[$this->selected_module])) {
                if (function_exists('method_exists')) {
                    if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
                        $GLOBALS[$this->selected_module]->update_status();
                    }
                }
            }
        }
    }

    function process($call_module = '')
    {
        $process_lock_timestamp = $this->check_process_lock();

        if ($process_lock_timestamp != false) {
            echo $this->get_process_lock_info($process_lock_timestamp);
            return false;
        }

        $call_module = mh_urldecode($call_module);
        if (is_array($this->modules)) {
            reset($this->modules);
            while (list(, $value) = each($this->modules)) {
                $module_id = substr($value, 0, strrpos($value, '.'));
                if ($call_module != '' && $module_id != $call_module) {
                    continue;
                }
                $this->output_flush();
                $this->update_process_lock();
                if ($GLOBALS[$module_id]->enabled && $GLOBALS[$module_id]->do_process && $GLOBALS[$module_id]->get_do_run()) {
                    echo '<a name="1">&nbsp;</a>';
                    // call process method of module
                    $result[$module_id] = $GLOBALS[$module_id]->process();
                    $this->output_result($result[$module_id]);
                } elseif ($GLOBALS[$module_id]->enabled && $GLOBALS[$module_id]->do_process && !$GLOBALS[$module_id]->get_do_run()) {
                    echo '<div class="i">MailBeez Module ' . $GLOBALS[$module_id]->get_module_id() . ' not scheduled for this run</div>';
                } elseif (!$GLOBALS[$module_id]->enabled && $GLOBALS[$module_id]->do_process) {
                    if ($GLOBALS[$module_id]->code) {
                        echo '<div class="w"><h2>MailBeez Module ' . $GLOBALS[$module_id]->get_module_id() . ' is disabled - please enable to send emails</h2></div>';
                    }
                } else {
                    // nothing to do, module is not active

                }
            }
        }
    }

    function run($call_module = '')
    {

        $process_lock_timestamp = $this->check_process_lock();

        if ($process_lock_timestamp != false) {
            echo $this->get_process_lock_info($process_lock_timestamp);
            return false;
        }

        $call_module = mh_urldecode($call_module);
        // run all active modules
        // log trigger of mailhive
        $this->start_process_lock();
        mh_event_log('MAILHIVE_RUN_INIT', 'mailhive run initiated', 'mailhive'); // todo: event type
        $result = $this->process($call_module);
        // log trigger of mailhive
        mh_event_log('MAILHIVE_RUN_COMPLETE', 'mailhive run completed', 'mailhive'); // todo: event type
        $this->clear_process_lock();
    }

    function listAudience($call_module = '')
    {
        $call_module = mh_urldecode($call_module);
        if (is_array($this->modules)) {
            reset($this->modules);
            while (list(, $value) = each($this->modules)) {
                $module_id = substr($value, 0, strrpos($value, '.'));
                if ($call_module != '' && $module_id != $call_module) {
                    continue;
                }
                echo '<a name="1">&nbsp;</a>';
                // call process method of module
                $result[$module_id] = $GLOBALS[$module_id]->listAudience();
                $this->output_flush();
                $this->output_result($result[$module_id]);
            }
        }
    }

    function sendTest($email, $call_module = '')
    {
        $call_module = mh_urldecode($call_module);
        mh_event_log('MAILHIVE_TEST_INIT', 'mailhive initiated', 'mailhive'); // todo: event type
        if (is_array($this->modules)) {
            reset($this->modules);
            while (list(, $value) = each($this->modules)) {
                $module_id = substr($value, 0, strrpos($value, '.'));
                if ($call_module != '' && $module_id != $call_module) {
                    continue;
                }
                echo '<a name="1">&nbsp;</a>';
                // call process method of module
                $result[$module_id] = $GLOBALS[$module_id]->sendTest($email);
            }
        }
        // log trigger of mailhive
        mh_event_log('MAILHIVE_TEST_COMPLETE', 'mailhive test completed', 'mailhive'); // todo: event type
        return $result;
    }

    function viewMail($call_module = '', $format = 'html', $theme_id = '', $template_id = '')
    {
        $call_module = mh_urldecode($call_module);
        if (is_array($this->modules)) {
            reset($this->modules);
            while (list(, $value) = each($this->modules)) {
                $module_id = substr($value, 0, strrpos($value, '.'));

                if ($call_module != '' && $module_id != $call_module) {
                    continue;
                }
                if ($format == 'html') {
                    if ($theme_id != '' || $template_id != '') {
                        $GLOBALS[$module_id]->apply_theme($theme_id, $template_id);
                    }
                }
                $result[$module_id] = $GLOBALS[$module_id]->viewMail($format);
            }
        }
        return $result;
    }

    // call an action on the module
    function moduleAction($call_module = '', $module_action, $module_params)
    {
        $call_module = mh_urldecode($call_module);
        if (is_array($this->modules)) {
            reset($this->modules);
            while (list(, $value) = each($this->modules)) {
                $module_id = substr($value, 0, strrpos($value, '.'));
                if ($call_module != '' && $module_id != $call_module) {
                    continue;
                }
                // call process method of module
                if (method_exists($GLOBALS[$module_id], $module_action)) {
                    $result[$module_id] = $GLOBALS[$module_id]->$module_action($module_params);
                    return $result;
                }
                return false;
            }
        }
    }

    function output_flush()
    {
        echo str_repeat(" ", 4096); // force a flush
        echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
      <!--
      scrolldown();
      //-->
      </SCRIPT>';
        flush();
    }

    function output_result($result)
    {
        echo 'Result:' . $result . ' items';
        if ($result == 0) {
            echo '<br>
				no matching customers or all items have been sent';
        }
    }


    function start_process_lock()
    {
        $GLOBALS['MAILBEEZ_MAILHIVE_PROCESS_CONTROL_INIT'] = true;
        return mailbeez::update_process_lock('', 'mailhive_open');
    }

    function update_process_lock()
    {
        return mailbeez::update_process_lock('', 'mailhive_running');
    }

    function clear_process_lock()
    {
        return mailbeez::update_process_lock(1, 'mailhive_closed');
    }

    function check_process_lock()
    {
        return mailbeez::check_process_lock();
    }

    function get_process_lock_info($process_lock_timestamp)
    {
        return mailbeez::get_process_lock_info($process_lock_timestamp);
    }

}

?>
