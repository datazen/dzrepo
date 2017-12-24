<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.2
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

echo $MAILBEEZ_TABS;

?>
<link rel="stylesheet" type="text/css" media="print, projection, screen"
      href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/admin_application_plugins/main_mailbeez.css">
<link rel="stylesheet" type="text/css" media="print, projection, screen"
      href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/admin_application_plugins/dashboard.css">


<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td width="100%" valign="top" class="smallText ">
                        <?php if (isset($_SESSION['mailbeez_upd_msg'])) { ?>
                        <div class="mb_inline_msg msg">
                            <div class="mb_inline_msg img">
                                <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG;?>/mailhive/common/images/mb_icon.png"
                                     width="32"
                                     height="32">
                            </div>
                            <div class="mb_inline_msg text">
                                <strong><?php echo MAILBEEZ_VERSION_CHECK_MSG_INTRO; ?></strong>
                                <br/>
                                <?php echo $_SESSION['mailbeez_upd_msg'];?>
                            </div>
                        </div>
                        <?php } ?>

<?php
            if (isset($_SESSION['mailbeez_new']['dashboardbeez']) || isset($_SESSION['mailbeez_upd']['dashboardbeez'])) {
                        ?>
                        <div class="mb_inline_msg new_modules">
                            <div class="mb_inline_msg_inner">
                                <?php echo MH_VERSIONCHECK_INFO_DASHBOARD; ?><br>

                                <a href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=dashboardbeez'); ?>"><?php echo MH_DASHBOARD_CONFIG_BUTTON; ?></a>
                            </div>
                        </div>
                        <?php

                    }
                        ?>

                        <div class="dashboard_main">
                            <table border="0" width="100%" cellspacing="5" cellpadding="2">
<?php
// thanks to osCommerce ms2.3
    if (defined('MAILBEEZ_DASHBOARD_INSTALLED') && mh_not_null(MAILBEEZ_DASHBOARD_INSTALLED)) {
        $adm_array = explode(';', MAILBEEZ_DASHBOARD_INSTALLED);

        $col = 0;

        for ($i = 0, $n = sizeof($adm_array); $i < $n; $i++) {
            $adm = $adm_array[$i];

            $class = substr($adm, 0, strrpos($adm, '.'));

            if (!class_exists($class)) {
                if (file_exists($module_directory_current . $class . '/languages/' . $_SESSION['language'] . $file_extension)) {
                    // try to load language file
                    include_once($module_directory_current . $class . '/languages/' . $_SESSION['language'] . $file_extension);
                } elseif (file_exists($module_directory_current . $class . '/languages/english' . $file_extension)) {
                    // .. or english file as default if available
                    include_once($module_directory_current . $class . '/languages/english' . $file_extension);
                } else {
                    // no language file found!
                }

                if (file_exists($module_directory_current . $class . $file_extension)) {
                    include_once($module_directory_current . $class . $file_extension);
                }
            }


            $dashboard_module = new $class;
            if (($dashboard_module->sort_order > 0) && !isset($installed_dashboard_modules[$dashboard_module->sort_order])) {
                $installed_dashboard_modules[$dashboard_module->sort_order] = $class;
            } else {
                $installed_dashboard_modules[] = $class;
            }
        }

        uksort($installed_dashboard_modules, "sortbyintvalue");

        $adm_array = array_values($installed_dashboard_modules);

        for ($i = 0, $n = sizeof($adm_array); $i < $n; $i++) {

            $class = $adm_array[$i];
            $ad = new $class();

            if ($ad->isEnabled()) {
                if ($col < 1) {
                    echo '          <tr>' . "\n";
                }

                $col++;

                if ($col <= 2) {
                    echo '            <td width="50%" valign="top" class="smallText"><div class="dashboard_area_top"><div class="dashboard_area">' . "\n";
                }

                echo $ad->getOutput();

                $config_disable_link = mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . '../common/admin_application_plugins/disable_dashboardbeez.php' . '&key=' . $ad->status_key);
                $config_link = mh_href_link(FILENAME_MAILBEEZ, '&action=edit&module=' . $ad->module);

                if ($col <= 2) {
                    echo '            </div>';
                    echo '<div align="right"><a href="' . $config_link . '">' . MH_DASHBOARD_CONFIG . '</a>  |  <a href="' . $config_disable_link . '">' . MH_DASHBOARD_REMOVE . '</a></div>';
                    echo '</div></td>' . "\n";
                }

                if (!isset($adm_array[$i + 1]) || ($col == 2)) {
                    if (!isset($adm_array[$i + 1]) && ($col == 1)) {
                        ?>
                        <td width="50%" valign="top">
                            <div class="dashboard_area_empty">
                                <div class="dashboard_area_empty_content"><?php echo MH_MSG_EMPTY_DASHBOARD_AREA ?><br>
                                    <a href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=dashboardbeez'); ?>"><?php echo MH_DASHBOARD_CONFIG_BUTTON; ?></a>
                                </div>
                            </div>
                        </td><?php

                    }

                    $col = 0;

                    echo '  </tr>' . "\n";
                }
            }
        }
    }
    ?>
                            </table>

                            <a href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=dashboardbeez'); ?>"><?php echo MH_DASHBOARD_CONFIG_BUTTON; ?></a>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <br>
                        <?php echo $MAILBEEZ_FOOTER; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>