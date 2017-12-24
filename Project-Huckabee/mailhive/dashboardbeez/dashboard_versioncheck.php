<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');

class dashboard_versioncheck extends dashboardbeez
{
    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_versioncheck()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_versioncheck';
        $this->module = 'dashboard_versioncheck';
        $this->version = '1.1'; // float value
        $this->required_mb_version = 2.1;
        $this->title = MAILBEEZ_DASHBOARD_VERSIONCHECK_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_VERSIONCHECK_DESCRIPTION;
        $this->removable = false; // can't be removed
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_DASHBOARD_VERSIONCHECK_STATUS';

        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/dashboardbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = 'check.php;clear_check.php';

        $this->sort_order = MAILBEEZ_DASHBOARD_VERSIONCHECK_SORT_ORDER;
    }

    function getOutput()
    {
        $check_active = false;
        $text_curl = '';
        if (!extension_loaded('curl')) {
            $button = mb_admin_button(MAILBEEZ_VERSION_CHECK_URL, MH_BUTTON_VERSION_CHECK, 'mbUpd');
            $text_curl = MAILBEEZ_DASHBOARD_VERSIONCHECK_TEXT_NCURL;
        } else {
            if (isset($_SESSION['mailbeez_upd_cnt_sum']) || isset($_SESSION['mailbeez_new_cnt_sum'])) {
                $check_active = true;
                $button = mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . '../dashboardbeez/dashboard_versioncheck/admin_application_plugins/clear_check.php'), MH_BUTTON_VERSION_CHECK_CLEAR, '', 'link');
            } else {
                $button = mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . '../dashboardbeez/dashboard_versioncheck/admin_application_plugins/check.php'), MH_BUTTON_VERSION_CHECK, '', 'link');
            }
        }

        ob_start();

        ?>
    <link rel="stylesheet" type="text/css" media="print, projection, screen"
          href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_versioncheck/dashboard_versioncheck.css">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr valign="top">
            <td width="48%">

                <div id="WidgetTitle"><?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_TITLE ?></div>
                <div id="WidgetSubTitle"><?php echo sprintf(MAILBEEZ_DASHBOARD_VERSIONCHECK_TEXT, (defined('MAILBEEZ_MAILHIVE_UPDATE_CHECK_TIMESTAMP')
                            ? mh_date_short(date('Y-m-d H:i:s', (MAILBEEZ_MAILHIVE_UPDATE_CHECK_TIMESTAMP - 7 * 24 * 60 * 60)))
                            : '?')) ?></div>
                <?php if ($text_curl == '') { ?>
                <ul class="versioncheck">
                    <li class="versioncheck_item result <?php echo (($check_active) ? ''
                            : 'inactive') ?>"><b><?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT ?></b><br>
                        <?php
                        if (isset($_SESSION['mailbeez_upd_cnt_sum'])) {
                            if ($_SESSION['mailbeez_upd_cnt_sum'] > 0) {
                                ?>
                                <span class="upd_cnt db"><?php echo $_SESSION['mailbeez_upd_cnt_sum'] ?></span>
                                &nbsp;<?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_UPD_CNT ?>
                                <?php

                            } else {
                                ?>
                                <span class="upd_cnt db ok">0</span>
                                &nbsp;<?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_UPD_OK; ?>
                                <?php

                            }
                        } else {
                            ?>
                            <span class="upd_cnt db_inactive">?</span>
                            &nbsp;<?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_UPD_CNT; ?>
                            <?php

                        }
                        ?>

                        <br><br>

                        <?php

                        if (isset($_SESSION['mailbeez_new_cnt_sum'])) {
                            if ($_SESSION['mailbeez_new_cnt_sum'] > 0) {
                                ?>
                                <span class="new_cnt db"><?php echo $_SESSION['mailbeez_new_cnt_sum'] ?></span>
                                &nbsp;<?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_NEW_CNT; ?>
                                <?php

                            } else {
                                ?><span class="new_cnt db ok">0</span>
                                &nbsp;<?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_NEW_OK; ?>
                                <?php

                            }
                        } else {
                            ?>
                            <span class="new_cnt db_inactive">?</span>
                            &nbsp;<?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_NEW_CNT; ?>
                            <?php

                        }
                        ?>
                    </li>

                </ul>
                <?php } else { ?>
                <ul class="versioncheck">
                    <li class="versioncheck_item">
                        <?php echo $text_curl; ?>
                    </li>
                </ul>

                <?php } ?>
                <br clear="all">

                <div align="center" style="margin-top: 3px;">
                    <?php echo $button; ?>
                </div>

            </td>
            <td width="52%" style="border-left: 1px solid #c0c0c0; padding-left: 7px;">
                <div id="WidgetTitle"><?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_TITLE; ?></div>
                <div id="WidgetSubTitle"><?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_TEXT; ?></div>
                <ul class="versioncheck">
                    <li class="versioncheck_item mode <?php echo MAILBEEZ_MAILHIVE_MODE; ?>">
                        <?php // echo MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_TITLE?>

                        <a style="float: left; margin-top: 8px; margin-left: 5px;"
                           href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . '../common/admin_application_plugins/toggle_mode.php')?>"><img
                                src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_versioncheck/button_status_<?php echo MAILBEEZ_MAILHIVE_MODE; ?>.png"></a>

                        <div style="text-align: center;
    font-weight: bold;
    font-size: 14px; margin-top: 30px;">

                            <?php echo (MAILBEEZ_MAILHIVE_MODE == 'simulate')
                                ? MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_TEXT
                                : MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_PRODUCTION_TEXT; ?>
                        </div>
                    </li>
                </ul>
                <div class="versioncheck_item mode_desc">

                    <?php echo (MAILBEEZ_MAILHIVE_MODE == 'simulate')
                        ? MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_DESC
                        : MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_PRODUCTION_DESC; ?>

                    <?php if (MAILBEEZ_MAILHIVE_MODE == 'simulate') { ?>
                    <a href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'module=config_simulation'); ?>"><?php echo MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_CONFIG; ?></a>
                    <?php } ?>


                </div>
            </td>
        </tr>

    </table>




    <?php
                                            $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }


    function check()
    {
        return true;
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show MailBeez Versioncheck',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_VERSIONCHECK_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to show the MailBeez Actions on the dashboard? (recommended)',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_VERSIONCHECK_SORT_ORDER',
                                    'configuration_value' => '25',
                                    'configuration_description' => 'Sort order of display. Lowest is displayed first.',
                                    'set_function' => ''
                               ));
    }

    function remove()
    {
        return false;
    }

    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_VERSIONCHECK_SORT_ORDER');
    }
}

?>
