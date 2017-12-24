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


class mailbeez_mailer
{

    var $subject, $ga;

    function mailbeez_mailer($mailbeeObj)
    {
        $this->mailBee = $mailbeeObj; // current mailBee to work on
        $this->copySentCounter = 0;
        $this->filter = array();
    }

    function sendBeez($mailbeez_module_path, $_iteration = 1, $mode = 'production')
    {
        global $_GET;
        $doCheck = true;
        if ($mode == 'test') {
            $doCheck = false;
        }
        $row = 0;
        $row_out = 0;

        $mailbeez_module = $this->get_module_name($mailbeez_module_path);

        // changed handling of iteration -> can be overwritten by resultset
        $iteration = $_iteration;

        $this->load_filter();

        while (list($key, $mail) = each($this->mailBee->audience)) {
            $row_out++;
            $order_id = 'none';
            if (isset($mail['order_id'])) {
                $order_id = $mail['order_id'];
            }

            list($mail) = @mhpi('mailbeez_mailer_1', $mail, $this->mailBee);

            // set dynamic iteration if exists
            $iteration = isset($mail['_iteration']) ? $mail['_iteration'] : $iteration;

            if ($doCheck == true) {
                $check_result = $this->check($mailbeez_module, $iteration, $mail['customers_id'], $order_id, $mail);

                if ($check_result != false) {
                    // result is an array
                    list($check_result, $date_sent, $_result_iteration, $date_block, $filter_block, $valid_block, $filter_result) = $check_result;
                }
            }

            if ($filter_result['STOP'] == 'true') {
                echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_FILTER_STOP;
                echo $filter_block;
                echo '</div>';
                return $row;
            }

            if ($doCheck == true && $check_result == true) {
                // was already sent
                if ($date_sent != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_ALREADY_SEND;
                    echo $date_sent . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                } elseif ($date_block != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_BLOCKED;
                    echo $date_block . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                } elseif ($filter_block != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_FILTER_BLOCKED;
                    echo $filter_block . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                } elseif ($valid_block != false) {
                    echo '<div class="w"><a name="1">&nbsp;</a>' . $row . ' ' . TEXT_EMAIL_VALID_BLOCKED;
                    echo $valid_block . ' ' . $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                }

                echo '</div>';
            } else {
                // send
                $row++;
                // $replace_variables = $mail;


                $smarty = new Smarty;
                $smarty->caching = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CACHING;
                $smarty->template_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEMPLATE_DIR; // root dir to templates
                $smarty->compile_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR;
                $smarty->config_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CONFIG_DIR;
                $smarty->compile_check = true;
                $smarty->compile_id = $this->mailBee->get_module_id();

                // some magic
                list($smarty, ,) = @mhpi('mailbeez_mailer_2', $smarty, $this, $mail);


                $mail = $this->mailBee->beforeFilter($mail, $mode);

                // add data
                $mail = $this->mailBee->beforeFilterData($mail, $mode);
                list($mail, $filter_result) = $this->process_data_filter($mailbeez_module_path, $iteration, $mail['customers_id'], $order_id, $mail);
                $this->mailBee->afterFilterData($mail, $mode);

                // insert content
                $mail = $this->mailBee->beforeFilterContent($mail, $mode);
                list($mail, $filter_result) = $this->process_content_filter($mailbeez_module_path, $iteration, $mail['customers_id'], $order_id, $mail);
                $mail = $this->mailBee->afterFilterContent($mail, $mode);

                // generate Email
                $mail = $this->mailBee->beforeGenerate($mail, $mode);
                list($output_subject, $output_content_html, $output_content_txt) = mh_smarty_generate_mail($this, $mail, $mailbeez_module_path, $smarty);
                $mail = $this->mailBee->afterGenerateMail($mail, $mode);

                // modifiy output
                $mail = $this->mailBee->beforeFilterModify($mail, $mode);
                list($output_subject, $output_content_html, $output_content_txt) = $this->process_modifier_filter($mailbeez_module_path, $iteration, $mail['customers_id'], $order_id, $output_subject, $output_content_html, $output_content_txt);
                $mail = $this->mailBee->afterFilterModify($mail, $mode);

                $mail = $this->mailBee->afterFilter($mail, $mode);

                if (MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL == 'True') {
                    echo "<hr noshade size='1'>";
                    echo $output_content_html;
                }
                //exit();
                // do things before sending

                if ((int)$this->mailBee->required_mb_version < 2 && (MAILBEEZ_MAILHIVE_MODE == 'production')) {
                    $this->mailBee->beforeSend($mail, $mode);
                } elseif ((int)$this->mailBee->required_mb_version >= 2) {
                    $mail = $this->mailBee->beforeSend($mail, $mode);
                }

                if (MAILBEEZ_SIMULATION == 'False') {
                    // send "real" email to customer
                    mh_sendEmail($mail, $mail['email_address'], $this->mailBee->sender_name, $this->mailBee->sender, $output_subject, $output_content_html, $output_content_txt);
                } else {
                    // send simulation
                    $output_subject = MAILBEEZ_SIMULATION_TAG . $output_subject;
                    mh_sendEmail($mail, MAILBEEZ_CONFIG_SIMULATION_EMAIL, $this->mailBee->sender_name, $this->mailBee->sender, $output_subject, $output_content_html, $output_content_txt);
                }
                // do things after sending
                if ((int)$this->mailBee->required_mb_version < 2 && (MAILBEEZ_MAILHIVE_MODE == 'production')) {
                    $this->mailBee->afterSend($mail, $mode);
                } elseif ((int)$this->mailBee->required_mb_version >= 2) {
                    $this->mailBee->afterSend($mail, $mode);
                }

                list($mail, $filter_result) = $this->process_afterSend_filter($this->mailBee, $iteration, $mail, $mode);

                if ((MAILBEEZ_MAILHIVE_COPY == 'True' && MAILBEEZ_SIMULATION == 'False') ||
                    (MAILBEEZ_CONFIG_SIMULATION_COPY == 'True' && MAILBEEZ_SIMULATION == 'True')
                ) {
                    // send copy
                    if ($this->copySentCounter < MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT) {
                        mh_sendEmail($mail, MAILBEEZ_MAILHIVE_EMAIL_COPY, $this->mailBee->sender_name, $this->mailBee->sender, $output_subject, $output_content_html, $output_content_txt);
                        $this->copySentCounter++;
                    }
                }

                if (($doCheck && (MAILBEEZ_SIMULATION == 'False')) ||
                    ($doCheck && (MAILBEEZ_SIMULATION == 'True' && MAILBEEZ_CONFIG_SIMULATION_TRACKING == 'True'))
                ) {
                    $this->track($mailbeez_module, $iteration, $mail);
                }



                echo '<div class="s"><a name="1">&nbsp;</a>' . $row . ' ' . ((MAILBEEZ_SIMULATION == 'False') ? ''
                        : MAILBEEZ_SIMULATION_TAG) . TEXT_EMAIL_SEND;
                echo $mail['customers_id'] . ' ' . $mail['firstname'] . ' ' . $mail['lastname'] . ' ' . $mail['email_address'];
                echo '</div>';
            }

            $this->mailBee->update_process_lock();

            if (($row_out % 100) == 0) {
                echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">  scrolldown(); </SCRIPT>';
                echo str_repeat(" ", 4096); // force a flush
            }
        }
        return $row;
    }

    function track($mailbeez_module, $iteration = 1, $mail)
    {
        // TABLE_MAILBEEZ_TRACKING

        $customers_id = $mail['customers_id'];
        $customers_email = $mail['email_address'];
        $order_id = isset($mail['order_id']) ? $mail['order_id'] : 0;


        $sql_data_array = array('module' => $mailbeez_module,
                                'date_sent' => 'now()',
                                'iteration' => (int)$iteration,
                                'customers_id' => $customers_id,
                                'customers_email' => $customers_email,
                                'orders_id' => $order_id,
                                'simulation' => MAILBEEZ_SIMULATION_ID,
                                'batch_id' => MAILBBEEZ_EVENTLOG_BATCH_ID
        );

        list($sql_data_array,) = @mhpi('mailbeez_mailer_3', $sql_data_array, $mail);


        mh_db_perform(TABLE_MAILBEEZ_TRACKING, $sql_data_array);
    }

    function block($mailbeez_module, $customers_id, $email_address, $source = 0, $docheck = true)
    {
        // TABLE_MAILBEEZ_BLOCK
        // block module for customer
        // check if combination of customer_id and email_adress is valid

        $check_result = true;
        if ($docheck) {
            $check_result = $this->check_track($mailbeez_module, false, $customers_id);
        }

        if ($check_result) {
            // this customer has received an email
            if (!$this->check_block($mailbeez_module, $customers_id)) {
                // not yet blocked
                $sql_data_array = array('module' => $mailbeez_module,
                                        'date_block' => 'now()',
                                        'customers_id' => $customers_id,
                                        'source' => $source,
                                        'simulation' => MAILBEEZ_SIMULATION_ID);

                mh_db_perform(TABLE_MAILBEEZ_BLOCK, $sql_data_array);
                return 'ok';
            }
            return '-1';
        }
        return 'failed';
    }

    function unblock($mailbeez_module, $customers_id, $email_address, $source = 0, $docheck = false)
    {
        // TABLE_MAILBEEZ_BLOCK
        // block module for customer
        // check if combination of customer_id and email_adress is valid
        $check_result = true;
        if ($docheck) {
            $check_result = $this->check_track($mailbeez_module, false, $customers_id);
        }

        if ($check_result) {
            // this customer has received an email
            if ($this->check_block($mailbeez_module, $customers_id)) {
                mh_db_query("delete from " . TABLE_MAILBEEZ_BLOCK . "
                             where module = '" . $mailbeez_module . "'
                                and customers_id = '" . $customers_id . "'");
                return 'ok';
            }
            return '-1';
        }
        return 'failed';
    }


    function check_last($customers_id)
    {
        return false;
    }

    function check_track($mailbeez_module, $iteration, $customers_id, $order_id = 'none')
    {
        $sql = '';
        if ($order_id != 'none') {
            // check for orders_id (e.g. when blocking)
            $sql .= " and orders_id='" . $order_id . "' ";
        }

        if ($iteration) {
            // check for iteration ( e.g. when sending)
            $sql .= " and iteration='" . $iteration . "' ";
        }

        $check_query_raw = "select date_sent, iteration
					        from " . TABLE_MAILBEEZ_TRACKING . "
    					where module='" . $mailbeez_module . "'
							" . $sql . "
							" . MAILBEEZ_SIMULATION_SQL . "
							and customers_id='" . $customers_id . "'
						order by date_sent DESC";

        $check_query = mh_db_query($check_query_raw);

        if (mh_db_num_rows($check_query) > 0) {
            $check = mh_db_fetch_array($check_query);
            return array($check['date_sent'], $check['iteration']);
        }
        return array(false, false);
    }

    function check_block($mailbeez_module, $customers_id)
    {
        // TABLE_MAILBEEZ_BLOCK

        $check_query = mh_db_query("select date_block
                                    from " . TABLE_MAILBEEZ_BLOCK . "
        						where module='" . $mailbeez_module . "'
								    and customers_id='" . $customers_id . "'
								    " . MAILBEEZ_SIMULATION_SQL . "
								order by date_block DESC");

        if (mh_db_num_rows($check_query) > 0) {
            $check = mh_db_fetch_array($check_query);
            return $check['date_block'];
        }
        return false;
    }

    function load_filter()
    {
        if (defined('MAILBEEZ_FILTER_INSTALLED') && mh_not_null(MAILBEEZ_FILTER_INSTALLED)) {
            $filter_modules = explode(';', MAILBEEZ_FILTER_INSTALLED);
            while (list(, $filterbee) = each($filter_modules)) {
                include_once('mailhive/filterbeez/' . $filterbee);
                $class = substr($filterbee, 0, strrpos($filterbee, '.'));
                $class_name = mh_get_class_name($class);
                $GLOBALS[$class] = new $class_name;
                $this->filter[] = $class; // register filter
            }
        }
    }

    function process_data_filter($mailbeez_module, $iteration, $customers_id, $order_id, $mail)
    {
        $mail_backup = $mail;

        // run through active data filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'data') {
                list($mail, $result) = $GLOBALS[$filterClass]->processFilter($mailbeez_module, $iteration, $customers_id, $order_id, $mail);
            }
        }
        if (!is_array($mail)) {
            // something went wrong, avoid breaking the system
            $mail = $mail_backup;
        }

        return array($mail, $result);
    }

    function process_content_filter($mailbeez_module, $iteration, $customers_id, $order_id, $mail)
    {
        $mail_backup = $mail;

        // run through active data filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'content') {
                list($mail, $result) = $GLOBALS[$filterClass]->processFilter($mailbeez_module, $iteration, $customers_id, $order_id, $mail);
            }
        }
        if (!is_array($mail)) {
            // something went wrong, avoid breaking the system
            $mail = $mail_backup;
        }

        return array($mail, $result);
    }

    function process_check_filter($mailbeez_module, $iteration, $customers_id, $order_id)
    {
        // run through active check filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'check') {
                $filter_result = $GLOBALS[$filterClass]->processFilter($mailbeez_module, $iteration, $customers_id, $order_id);
                if ($filter_result != false) {
                    return $filter_result;
                }
            }
        }
        return false;
    }

    function process_modifier_filter($mailbeez_module, $iteration, $customers_id, $order_id, $output_subject, $output_content_html, $output_content_txt)
    {
        // run through active modifier filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'modifier') {
                list($output_subject, $output_content_html, $output_content_txt) = $GLOBALS[$filterClass]->processFilter($mailbeez_module, $iteration, $customers_id, $order_id, $output_subject, $output_content_html, $output_content_txt);
            }
        }
        return array($output_subject, $output_content_html, $output_content_txt);
    }

    function process_afterSend_filter($mailbeez_obj, $iteration, $mail, $mode)
    {
        $mail_backup = $mail;
        
        // run through active modifier filters
        reset($this->filter);
        while (list(, $filterClass) = each($this->filter)) {
            if ($GLOBALS[$filterClass]->enabled && $GLOBALS[$filterClass]->filter_type == 'afterSend') {
                $result = $GLOBALS[$filterClass]->processFilter($mailbeez_obj, $iteration, $mail, $mode);
            }
        }
        if (!is_array($mail)) {
            // something went wrong, avoid breaking the system
            $mail = $mail_backup;
        }
        return array($mail, $result);
    }

    function check_valid($mailbeez_module, $iteration, $customers_id, $order_id, $mail)
    {
        // check if data is valid
        if (!is_array($mail)) {
            return false;
        }
        if ($mail['email_address'] == '') {
            return 'email_address empty - data not valid sending';
        }
        if ($mail['firstname'] == '' && $mail['lastname'] == '') {
            return 'both firstname and lastname empty - data not valid for sending';
        }

        return false;
    }

    function check($mailbeez_module, $iteration, $customers_id, $order_id = 'none', $mail = '')
    {
        // check 1: already sent?
        list($date_sent, $iteration_result) = $this->check_track($mailbeez_module, $iteration, $customers_id, $order_id);

        // check 2: blocked?
        if (!$date_sent) {
            $date_block = $this->check_block($mailbeez_module, $customers_id);
        } else {
            $date_block = false;
        }

        // check 3: filter?
        if (!$date_sent && !$date_block) {
            list($filter_block, $filter_result) = $this->process_check_filter($mailbeez_module, $iteration, $customers_id, $order_id);
        } else {
            $filter_block = false;
        }

        // check 4: valid?
        if (!$date_sent && !$date_block && !$filter_block) {
            $valid_block = $this->check_valid($mailbeez_module, $iteration, $customers_id, $order_id, $mail);
        } else {
            $valid_block = false;
        }

        $check_result = false;
        if ($date_sent || $date_block || $filter_block || $valid_block) {
            $check_result = true; // no email to this customer
            return array($check_result, $date_sent, $iteration_result, $date_block, $filter_block, $valid_block, $filter_result);
        }
        return false;
    }

    function early_check($module, $iteration, $customers_id)
    {
        // early check enabled.
        if (MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED == 'True') {
            $chk_result = $this->check($module, $iteration, $customers_id);
            return $chk_result;
        }
        return false;
    }

    function buildBlockUrl($mail, $module_path)
    {
        $block_token = base64_encode($mail['customers_id'] . '|' . $mail['email_address']);

        //might run through URL rewrite
        //$block_url = mh_href_email_link(FILENAME_HIVE, 'ma=block&m=' . $module . '&mp=' . $block_token , 'NONSSL', false);
        // plain url
        $block_url = HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_HIVE . '?ma=block&m=' . $this->get_module_name($module_path) . '&mp=' . $block_token;
        return $block_url;
    }

    function buildUnBlockUrl($mail, $module_path)
    {
        $block_token = base64_encode($mail['customers_id'] . '|' . $mail['email_address']);
        $block_url = HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_HIVE . '?ma=unblock&m=' . $this->get_module_name($module_path) . '&mp=' . $block_token;
        return $block_url;
    }

    function dbdate($day)
    {
        $rawtime = strtotime("-" . $day . " days");
        $ndate = date("Ymd", $rawtime);
        return $ndate;
    }


    function get_module_name($module_path)
    {
        return mh_get_module_name($module_path);
    }

}

?>