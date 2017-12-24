<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Version 2.5
 */


///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////
// define('STRICT_ERROR_REPORTING', true); // zencart

require('includes/application_top.php');

if (!defined('MAILBEEZ_MAILHIVE_STATUS')) {
    if (!class_exists('Smarty')) {
        // first run - take default settings.
        define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH', 'Smarty_2.6.26');
    }
}

require_once(DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');

if (!defined('MAILBEEZ_MAILHIVE_STATUS') || (int)MAILBEEZ_VERSION < 2) {

    if (!mh_template_check_writeable()) {
        echo "<h1>Please update your configuration:</h1>The Folder <blockquote><b>" . MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR . "</b></blockquote> must be writeable - but it is not. <br><br><font color='red'>Make sure this folder is writeable by giving the right permissions with your FTP tool.</font> <br><br>Then reload this page to install MailBeez.";
        exit();
    }
}


// include the modules language translations
// load language file for modules.php
if (MH_PLATFORM == 'zencart') {
    $current_page = basename(FILENAME_MODULES . '.php');
    if (file_exists(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . $current_page)) {
        include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . $current_page);
    }
} elseif (MH_PLATFORM == 'xtc' || MH_PLATFORM == 'gambio') {
    $current_page = basename(FILENAME_MODULES);
    if (file_exists(DIR_FS_LANGUAGES . $_SESSION['language'] . '/admin/' . $current_page)) {
        include(DIR_FS_LANGUAGES . $_SESSION['language'] . '/admin/' . $current_page);
    }
} else {
    if (file_exists(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_MODULES)) {
        include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_MODULES);
    }
}


$set = (isset($_GET['set']) ? $_GET['set'] : '');

$module_type = '';

$module_directory = DIR_FS_CATALOG . 'mailhive/mailbeez/';
$module_directory_ws = DIR_WS_CATALOG . 'mailhive/mailbeez/';
$config_module_directory = DIR_FS_CATALOG . 'mailhive/configbeez/';
$config_module_directory_ws = DIR_WS_CATALOG . 'mailhive/configbeez/';
$filter_module_directory = DIR_FS_CATALOG . 'mailhive/filterbeez/';
$filter_module_directory_ws = DIR_WS_CATALOG . 'mailhive/filterbeez/';
$report_module_directory = DIR_FS_CATALOG . 'mailhive/reportbeez/';
$report_module_directory_ws = DIR_WS_CATALOG . 'mailhive/reportbeez/';
$dashboard_module_directory = DIR_FS_CATALOG . 'mailhive/dashboardbeez/';
$dashboard_module_directory_ws = DIR_WS_CATALOG . 'mailhive/dashboardbeez/';

$module_key = 'MAILBEEZ_INSTALLED';
$module_version_key = 'MAILBEEZ_INSTALLED_VERSIONS';
$config_module_key = 'MAILBEEZ_CONFIG_INSTALLED';
$config_module_version_key = 'MAILBEEZ_CONFIG_INSTALLED_VERSIONS';
$filter_module_key = 'MAILBEEZ_FILTER_INSTALLED';
$filter_module_version_key = 'MAILBEEZ_FILTER_INSTALLED_VERSIONS';
$report_module_key = 'MAILBEEZ_REPORT_INSTALLED';
$report_module_version_key = 'MAILBEEZ_REPORT_INSTALLED_VERSIONS';
$dashboard_module_key = 'MAILBEEZ_DASHBOARD_INSTALLED';
$dashboard_module_version_key = 'MAILBEEZ_DASHBOARD_INSTALLED_VERSIONS';


$trustpilot_evaluate = (isset($trustpilot_evaluate)) ? $trustpilot_evaluate
        : 'http://www.trustpilot.com/evaluate/www.mailbeez.com';

$action = (isset($_GET['action']) ? $_GET['action'] : '');

$custom_app_include = '';
$config_cache_detected = false;
$config_cache_refreshed = false;
$config_updated = false;
$default_action = '';

if (file_exists($config_module_directory . 'config.php')) {
    if (!mh_class_exists('config')) {
        include_once($config_module_directory . 'config.php');
        $config = new config();
    }
} else {
    echo "fatal error: can't find config.php";
}

if (file_exists($config_module_directory . 'config_template_selector.php')) {
    include_once($config_module_directory . 'config_template_selector.php');
}

if (mh_not_null($action)) {
    if (function_exists('tep_reset_config_cache_block')) {
        tep_reset_config_cache_block('includes/config-cache.php');
        $config_cache_detected = true;
    }

    if (function_exists('updateConfiguration')) {
        updateConfiguration();
        $config_cache_detected = true;
    }

    if (file_exists('includes/configuration_cache.php')) {
        require ('includes/configuration_cache.php');
        $config_cache_detected = true;
    }

    if ($config_cache_detected == true) {
        $default_action .= '&action=update_cache';
    }

    switch ($action) {
        case 'save':
            while (list($key, $value) = each($_POST['configuration'])) {
                if (is_array($value)) {
                    $value = implode(", ", $value);
                    $value = preg_replace("/, --none--/", "", $value);
                }
                mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
            }


            $class = basename($_GET['module']);
            if (file_exists($module_directory . $class . $file_extension)) {
                include_once($module_directory . $class . $file_extension);
            } elseif (file_exists($config_module_directory . $class . $file_extension)) {
                include_once($config_module_directory . $class . $file_extension);
            } elseif (file_exists($filter_module_directory . $class . $file_extension)) {
                include_once($filter_module_directory . $class . $file_extension);
            } elseif (file_exists($report_module_directory . $class . $file_extension)) {
                include_once($report_module_directory . $class . $file_extension);
            } elseif (file_exists($dashboard_module_directory . $class . $file_extension)) {
                include_once($dashboard_module_directory . $class . $file_extension);
            }

            if (mh_class_exists($class)) {
                $module = new $class;
                if ($module->on_cfg_save_clear_template_c == true) {
                    mh_smarty_clear_compile_dir();
                }
            }

            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $_GET['module'] . $default_action));
            break;
        case 'install':
        case 'remove':
            if (isset($PHP_SELF) && $PHP_SELF != '') {
                // oscommerce, zencart
                $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
            } else {
                // xtc
                $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
            }

            $class = basename($_GET['module']);
            if (file_exists($module_directory . $class . $file_extension)) {
                include_once($module_directory . $class . $file_extension);
            } elseif (file_exists($config_module_directory . $class . $file_extension)) {
                include_once($config_module_directory . $class . $file_extension);
            } elseif (file_exists($filter_module_directory . $class . $file_extension)) {
                include_once($filter_module_directory . $class . $file_extension);
            } elseif (file_exists($report_module_directory . $class . $file_extension)) {
                include_once($report_module_directory . $class . $file_extension);
            } elseif (file_exists($dashboard_module_directory . $class . $file_extension)) {
                include_once($dashboard_module_directory . $class . $file_extension);
            }

            if (mh_class_exists($class)) {
                $module = new $class;
                if ($action == 'install') {
                    $module->install();
                } elseif ($action == 'remove') {
                    $module->remove();
                }
            }
            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $class . $default_action));
            break;
        case 'update_cache':
            // reload to make updated cache visible
            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $_GET['module'] . '&action=config_cache_refreshed'));
            break;
        case 'config_cache_refreshed':
            // show message
            $config_cache_refreshed = true;
            break;
        case 'config_update_ok':
            // show message
            $config_updated = true;
            break;
    }
}


$tab = defined('MAILBEEZ_CONFIG_DASHBOARD_START') ? MAILBEEZ_CONFIG_DASHBOARD_START : 'home';


// tab controll
if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
} elseif (preg_match('/^config/', $_GET['module'])) {
    $tab = 'configbeez';
} elseif (preg_match('/^filter/', $_GET['module'])) {
    $tab = 'filterbeez';
} elseif (preg_match('/^report/', $_GET['module'])) {
    $tab = 'reportbeez';
} elseif (preg_match('/^dashboard/', $_GET['module'])) {
    $tab = 'dashboardbeez';
} elseif (isset($_GET['module'])) {
    $tab = 'mailbeez';
}

// set modul paths
switch ($tab) {
    case 'mailbeez':
        $module_key_current = $module_key;
        $module_version_key_current = $module_version_key;
        $module_directory_current = $module_directory;
        $module_directory_current_ws = $module_directory_ws;
        break;
    case 'configbeez':
        $module_key_current = $config_module_key;
        $module_version_key_current = $config_module_version_key;
        $module_directory_current = $config_module_directory;
        $module_directory_current_ws = $config_module_directory_ws;
        break;
    case 'filterbeez':
        $module_key_current = $filter_module_key;
        $module_version_key_current = $filter_module_version_key;
        $module_directory_current = $filter_module_directory;
        $module_directory_current_ws = $filter_module_directory_ws;
        break;
    case 'reportbeez':
        $module_key_current = $report_module_key;
        $module_version_key_current = $report_module_version_key;
        $module_directory_current = $report_module_directory;
        $module_directory_current_ws = $report_module_directory_ws;
        break;
    case 'dashboardbeez':
    case 'home':
        $module_key_current = $dashboard_module_key;
        $module_version_key_current = $dashboard_module_version_key;
        $module_directory_current = $dashboard_module_directory;
        $module_directory_current_ws = $dashboard_module_directory_ws;
        break;
}


$app_include = (isset($_GET['app']) ? $_GET['app'] : '');

if ($app_include == 'load_app') {
    $custom_app_include = (isset($_GET['app_path'])) ? $_GET['app_path'] : '';
    $class = basename($_GET['module']);

    if (file_exists($module_directory_current . $class . '/languages/' . $_SESSION['language'] . $file_extension)) {
        // try to load language file
        include_once($module_directory_current . $class . '/languages/' . $_SESSION['language'] . $file_extension);
    } elseif (file_exists($module_directory_current . $class . '/languages/english' . $file_extension)) {
        // .. or english file as default if available
        include_once($module_directory_current . $class . '/languages/english' . $file_extension);
    } else {
        // no language file found!
    }
}

if (!function_exists('sortbyintvalue')) {
    function sortbyintvalue($a, $b)
    {
        $aint = (int)$a;
        $bint = (int)$b;

        //echo "$aint $bint<br>";

        if ($aint == $bint)
            $r = 0;
        if ($aint < $bint)
            $r = -1;
        if ($aint > $bint)
            $r = 1;
        return $r;
    }
}

if (MAILBEEZ_MAILHIVE_MODE == 'simulate') {
    $messageStack->reset();
    $messageStack->add(WARNING_SIMULATE, 'warning');
    mh_setDefaultMessage($messageStack);
}

if (MAILBEEZ_MAILHIVE_STATUS == 'False') {
    $messageStack->reset();
    //$messageStack->add(WARNING_OFFLINE, 'warning');
    mh_setDefaultMessage($messageStack);
}
if ($config_cache_refreshed == true) {
    $messageStack->add('config cache refreshed', 'success');
    mh_setDefaultMessage($messageStack);
}

if ($config_updated == true) {
    $messageStack->add('MailBeez updated!', 'success');
    mh_setDefaultMessage($messageStack);
}

mh_update_reminder_timestamp();


$smarty_admin = new Smarty;
$smarty_admin->caching = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CACHING;
$smarty_admin->template_dir = DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/templates/'; // root dir to templates
$smarty_admin->compile_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR;
$smarty_admin->config_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_CONFIG_DIR;
$smarty_admin->compile_check = true;
$smarty_admin->compile_id = 'admin_main' . $_SESSION['language'];

$ADMIN_TEMPLATE_TOP = '';
if (MH_PLATFORM_OSC_23) {
    ob_start();
    require(DIR_WS_INCLUDES . 'template_top.php');
    $ADMIN_TEMPLATE_TOP = ob_get_contents();
    ob_end_clean();
}


ob_start();
require(DIR_WS_INCLUDES . 'header.php');
$ADMIN_HEADER = ob_get_contents();
ob_end_clean();

if (MH_PLATFORM != 'zencart' && MH_PLATFORM != 'digistore' && !MH_PLATFORM_OSC_23) {
    // no column left
    ob_start();
    require(DIR_WS_INCLUDES . 'column_left.php');
    $ADMIN_COLUMN_LEFT = ob_get_contents();
    ob_end_clean();
}

$smarty_admin->assign(array('trustpilot_evaluate' => $trustpilot_evaluate));
$smarty_admin->assign(array('MH_CATALOG_URL' => MH_CATALOG_SERVER . DIR_WS_CATALOG,
                           'MH_RATE_TRUSTPILOT_LINK' => MH_RATE_TRUSTPILOT_LINK));

$MAILBEEZ_FOOTER = $smarty_admin->fetch('main_footer.tpl'); // smarty template

ob_start();
if (!defined('MAILBEEZ_MAILHIVE_STATUS')) {
    // install screen
    $MAILBEEZ_TABS = '';
    require_once(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/install.php');
} else {
    // start of admin screen main area
    // $_GET['module']
    if ($custom_app_include != '') {
        // load custom application
        switch ($_GET['module']) {
            case (preg_match('/^config/', $_GET['module']) > 0):
                require_once(DIR_FS_CATALOG . 'mailhive/configbeez/' . $custom_app_include);
                break;
            case (preg_match('/^filter/', $_GET['module']) > 0):
                require_once(DIR_FS_CATALOG . 'mailhive/filterbeez/' . $custom_app_include);
                break;
            case (preg_match('/^report/', $_GET['module']) > 0):
                require_once(DIR_FS_CATALOG . 'mailhive/reportbeez/' . $custom_app_include);
                break;
            default:
                require_once(DIR_FS_CATALOG . 'mailhive/mailbeez/' . $custom_app_include);
                break;
        }
    } else {
        switch ($tab) {
            case 'mailbeez':
            case 'configbeez':
            case 'filterbeez':
            case 'reportbeez':
                $MAILBEEZ_TABS = mh_tabs(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/tabs.php', $tab);
                require_once(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/main_mailbeez.php');
                break;
            case 'dashboardbeez':
                $MAILBEEZ_TABS = mh_tabs(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/back_dashboardbeez.php', $tab);
                require_once(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/main_mailbeez.php');
                break;

            case 'about':
                $MAILBEEZ_TABS = mh_tabs(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/tabs.php', $tab);
                require_once(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/main_mailbeez_' . $tab . '.php');
                break;
            default:
                $MAILBEEZ_TABS = mh_tabs(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/tabs.php', $tab);
                require_once(DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/main_mailbeez_' . $tab . '.php');
                break;
        }
    }
}

$MAILBEEZ_MAIN_CONTENT = ob_get_contents();
ob_end_clean();

ob_start();
require(DIR_WS_INCLUDES . 'footer.php');
$ADMIN_FOOTER = ob_get_contents();
ob_end_clean();

$ADMIN_TEMPLATE_BOTTOM = '';
if (MH_PLATFORM_OSC_23) {
    ob_start();
    require(DIR_WS_INCLUDES . 'template_bottom.php');
    $ADMIN_TEMPLATE_BOTTOM = ob_get_contents();
    ob_end_clean();
}

ob_start();
require(DIR_WS_INCLUDES . 'application_bottom.php');
$ADMIN_APPLICATION_BOTTOM = ob_get_contents();
ob_end_clean();


$smarty_admin->assign(array('MAILBEEZ_MAILHIVE_POPUP_MODE' => MAILBEEZ_MAILHIVE_POPUP_MODE,
                           'MAILBEEZ_MAILHIVE_STATUS' => MAILBEEZ_MAILHIVE_STATUS,
                           'HTML_PARAMS' => HTML_PARAMS,
                           'TITLE' => TITLE,
                           'HEADING_TITLE' => HEADING_TITLE,
                           'MAILBEEZ_VERSION' => MAILBEEZ_VERSION,
                           'MAILBEEZ_VERSION_CHECK_URL' => MAILBEEZ_VERSION_CHECK_URL,
                           'MAILBEEZ_MAILHIVE_MODE' => MAILBEEZ_MAILHIVE_MODE,
                           'MAILBEEZ_MAILHIVE_MODE_TEXT' => (MAILBEEZ_MAILHIVE_MODE == 'simulate')
                                   ? MAILBEEZ_MODE_SET_SIMULATE_TEXT : MAILBEEZ_MODE_SET_PRODUCTION_TEXT,
                           'MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT' => MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT,
                           'MAILBEEZ_MAILHIVE_MODE_SWITCH_URL' => mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . '../common/admin_application_plugins/toggle_mode.php')
                      ));

$MAILBEEZ_UPDATE_REMINDER = false;
if (MAILBEEZ_MAILHIVE_UPDATE_REMINDER == 'True' && MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP < time()) {
    // fire updatereminder
    $MAILBEEZ_UPDATE_REMINDER = true;
}


$smarty_admin->assign(array('MAILBEEZ_FOOTER' => $MAILBEEZ_FOOTER));
$smarty_admin->assign(array('MAILBEEZ_MAIN_CONTENT' => $MAILBEEZ_MAIN_CONTENT,
                           'ADMIN_TEMPLATE_TOP' => $ADMIN_TEMPLATE_TOP,
                           'ADMIN_TEMPLATE_BOTTOM' => $ADMIN_TEMPLATE_BOTTOM,
                           'MAILBEEZ_VERSION_CHECK_BUTTON' => mb_admin_button(MAILBEEZ_VERSION_CHECK_URL, MH_BUTTON_VERSION_CHECK, 'mbUpd'),
                           'ADMIN_BOX_WIDTH' => BOX_WIDTH,
                           'ADMIN_HEADER' => $ADMIN_HEADER,
                           'ADMIN_COLUMN_LEFT' => $ADMIN_COLUMN_LEFT,
                           'ADMIN_PAGE_HEADING_SEPARATOR' => mh_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT),
                           'ADMIN_APPLICATION_BOTTOM' => $ADMIN_APPLICATION_BOTTOM,
                           'ADMIN_FOOTER' => $ADMIN_FOOTER,
                           'GAMBIO_SCREEN' => $_SESSION['screen_width'],
                           'SESSION_CHARSET' => (isset($_SESSION['language_charset']) ? $_SESSION['language_charset']
                                   : CHARSET),
                           'GAMBIO_COUNTER_ACTION_LINK' => mh_href_link('gm_counter_action.php'),
                           'MH_PLATFORM_XTCMODIFIED' => MH_PLATFORM_XTCM));

$admin_template = 'main_osc.tpl';
mh_define('MH_BYPASS_TEMPLATE', false); // might have been set in admin_application_plugins

switch (MH_PLATFORM) {
    case 'oscommerce':
        if (MH_PLATFORM_OSC_23) {
            $admin_template = 'main_osc23.tpl';
        }
        if (MH_PLATFORM_OSCMAX_25) {
            $admin_template = 'main_oscmax25.tpl';
        }
        break;
    case 'creloaded':
        if (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) {
            $admin_template = 'main_creloaded_62.tpl';
        } else {
            $admin_template = 'main_creloaded.tpl';
        }
        break;
    case 'digistore':
        $admin_template = 'main_digistore.tpl';
        break;
    case 'zencart':
        $admin_template = 'main_zc.tpl';
        break;
    case 'xtc':
        // if (MH_PLATFORM_XTCM) {}
        $admin_template = 'main_xtc.tpl';

        if (MH_PLATFORM_XTC_SEO) {
            $admin_template = 'main_xtc_seo.tpl';

            ob_start();
            require(DIR_WS_INCLUDES . 'metatag.php');
            $CSEO_METATAG = ob_get_contents();
            ob_end_clean();
            $smarty_admin->assign(array('CSEO_METATAG' => $CSEO_METATAG));
        }
        break;
    case 'gambio':
        if (MH_PLATFORM_GAMBIO == 1) {
            $admin_template = 'main_gambiogx.tpl';
        } elseif (MH_PLATFORM_GAMBIO == 2) {
            $admin_template = 'main_gambiogx2.tpl';
        }
        break;
    default:
}

if (MH_BYPASS_TEMPLATE) {
    $admin_template = 'main_direct.tpl';

}

echo $smarty_admin->fetch($admin_template); // smarty template

// end of admin screen main area
?>
