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


function mh_define($const, $value)
{
    if (!defined($const))
        define($const, $value);
}

// include local configuration if available
$local_conf_dir = DIR_FS_CATALOG . 'mailhive/common/local/';
if ($dir = @dir($local_conf_dir)) {
    while ($local_conf_file = $dir->read()) {
        if (!is_dir($local_conf_dir . $local_conf_file)) {
            if (preg_match('/\.php$/', $local_conf_file) > 0) {
                require_once($local_conf_dir . $local_conf_file);
            }
        }
    }
    $dir->close();
}

mh_define('FILENAME_MAILBEEZ_BLOCKGUI', 'mailhive/gui/mailhive_block_gui.php'); // can be overwritten by local config file
mh_define('FILENAME_MAILBEEZ_UNBLOCKGUI', 'mailhive/gui/mailhive_unblock_gui.php'); // can be overwritten by local config file
mh_define('MH_AUTOINSTALL', false);


if (!defined('DB_PREFIX'))
    define('DB_PREFIX', ''); //  zencart only (?)

mh_define('TABLE_MAILBEEZ_TRACKING', DB_PREFIX . 'mailbeez_tracking');
mh_define('TABLE_MAILBEEZ_BLOCK', DB_PREFIX . 'mailbeez_block');
mh_define('TABLE_MAILBEEZ_PROCESS', DB_PREFIX . 'mailbeez_process');

mh_define('FILENAME_MAILBEEZ', 'mailbeez.php');
mh_define('FILENAME_HIVE', 'mailhive.php');

// check if called in admin or storefront context

if (defined('DIR_WS_ADMIN')) {
    if (preg_match('#' . DIR_WS_ADMIN . '#', $_SERVER['PHP_SELF'])) {
        mh_define('MH_CONTEXT', 'ADMIN');
    } else {
        mh_define('MH_CONTEXT', 'STORE');
    }
} else {
    mh_define('MH_CONTEXT', 'STORE');
}


if (defined('DIR_WS_HTTP_CATALOG')) {
    // oscmax
    mh_define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
}

// zencart, xtcommerce
mh_define('DIR_WS_HTTP_CATALOG', DIR_WS_CATALOG);

mh_define('HTTP_CATALOG_SERVER', HTTP_SERVER);
mh_define('HTTPS_CATALOG_SERVER', HTTPS_SERVER);


if (defined('ENABLE_SSL_ADMIN')) {
    // by default zencart
    mh_define('MH_CATALOG_SERVER', (ENABLE_SSL_ADMIN == 'true') ? HTTPS_CATALOG_SERVER : HTTP_CATALOG_SERVER);
} else {
    mh_define('MH_CATALOG_SERVER', (ENABLE_SSL == 'true') ? HTTPS_CATALOG_SERVER : HTTP_CATALOG_SERVER);
}

if (function_exists('zen_redirect')) {
    define('MH_PLATFORM', 'zencart');
    // sorry zencart - didn't had the time to migrate everything to your DB-Class (might come later - its cool)
    // http://www.zen-cart.com/wiki/index.php/Developers_-_Porting_modules_from_osC
    require_once(DIR_FS_CATALOG . 'mailhive/common/functions/osc_database.php');
} elseif (function_exists('gm_get_conf')) {
    define('MH_PLATFORM', 'gambio');
    if (!function_exists('xtc_date_short')) {
        require_once(DIR_FS_INC . 'xtc_date_short.inc.php');
    }
    if (!function_exists('xtc_parse_input_field_data')) {
        require_once(DIR_FS_INC . 'xtc_parse_input_field_data.inc.php');
    }
    include_once(DIR_FS_CATALOG . 'release_info.php');
    //echo $gx_version;
    define('MH_PLATFORM_GAMBIO', substr($gx_version, 1, 1));
} elseif (function_exists('xtc_redirect')) {
    define('MH_PLATFORM', 'xtc');
    if (!function_exists('xtc_date_short')) {
        require_once(DIR_FS_INC . 'xtc_date_short.inc.php');
    }
    if (!function_exists('xtc_parse_input_field_data')) {
        require_once(DIR_FS_INC . 'xtc_parse_input_field_data.inc.php');
    }

    define('MH_PLATFORM_XTCM', preg_match('/xtcModified/', PROJECT_VERSION));
    define('MH_PLATFORM_XTC_SEO', preg_match('/commerce:SEO/', PROJECT_VERSION));

} elseif (defined('FILENAME_ADVANCED_MENU')) {
    define('MH_PLATFORM', 'digistore');
} elseif (preg_match('/CRE Loaded/', PROJECT_VERSION) || preg_match('/Loaded/', PROJECT_VERSION)) {
    // CRE Loaded PCI B2B
    define('MH_PLATFORM', 'creloaded');

    if (preg_match('/CRE Loaded PCI B2B/', PROJECT_VERSION) || preg_match('/Loaded Commerce B2B/', PROJECT_VERSION)) {
        define('MH_PLATFORM_CRE', 'B2B');
    } else {
        define('MH_PLATFORM_CRE', '');
    }

} else {
    define('MH_PLATFORM', 'oscommerce');

    if (function_exists('tep_get_version')) {
        define('MH_PLATFORM_OSC', (float)tep_get_version());
    } else {
        define('MH_PLATFORM_OSC', '2.2');
    }

    if (MH_PLATFORM_OSC > 2.2) {
        mh_define('MH_PLATFORM_OSC_23', true);
    }
    define('MH_PLATFORM_OSCMAX_25', preg_match('/osCmax v2.5/', PROJECT_VERSION));
}

mh_define('MH_PLATFORM_OSC', false);
mh_define('MH_PLATFORM_OSC_23', false);

mh_define('MH_ID', MH_PLATFORM);
mh_define('MH_LINKID_1', '?a=' . MH_ID);
mh_define('MH_LINKID_2', '&a=' . MH_ID);

mh_define('MAILBEEZ_MAILHIVE_URL', HTTP_CATALOG_SERVER . DIR_WS_CATALOG . FILENAME_HIVE . '?' . MAILBEEZ_MAILHIVE_TOKEN . '=');
// adjustments need to be done in mailbeez_check.php / versioncheck.php as well

//define('MAILBEEZ_VERSION_CHECK_SERVER', 'http://127.0.0.1/wordpress_mailbeez');

mh_define('MAILBEEZ_VERSION_CHECK_SERVER', 'http://www.mailbeez.com');

mh_define('PROJECT_VERSION', ''); // if not available

switch ($_SESSION['language']) {
    case "german":
        $lng_param = 'de';
        break;
    default:
        $lng_param = 'en';
}

mh_define('MAILBEEZ_VERSION_CHECK_URL', MAILBEEZ_VERSION_CHECK_SERVER . '/downloads/version_check_v2/?v=' . MAILBEEZ_VERSION . '&m=' . (defined('MAILBEEZ_INSTALLED_VERSIONS')
        ? MAILBEEZ_INSTALLED_VERSIONS : '') . '&c=' . (defined('MAILBEEZ_CONFIG_INSTALLED_VERSIONS')
        ? MAILBEEZ_CONFIG_INSTALLED_VERSIONS : '') . '&f=' . (defined('MAILBEEZ_FILTER_INSTALLED_VERSIONS')
        ? MAILBEEZ_FILTER_INSTALLED_VERSIONS : '') . '&r=' . (defined('MAILBEEZ_REPORT_INSTALLED_VERSIONS')
        ? MAILBEEZ_REPORT_INSTALLED_VERSIONS : '') . '&d=' . (defined('MAILBEEZ_DASHBOARD_INSTALLED_VERSIONS')
        ? MAILBEEZ_DASHBOARD_INSTALLED_VERSIONS
        : '') . MH_LINKID_2 . '&lang=' . $lng_param . '&p=' . urlencode(MH_PLATFORM . ' - ' . PROJECT_VERSION));


mh_define('MAILBEEZ_CONTACT_EMAIL', 'support-' . MH_PLATFORM . '@mailbeez.com');

if (isset($PHP_SELF) && $PHP_SELF != '') {
    // oscommerce, zencart
    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
} else {
    // xtc
    $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
}


// load common language resources
if (file_exists(DIR_FS_CATALOG . 'mailhive/common/languages/' . $_SESSION['language'] . $file_extension)) {
    include_once(DIR_FS_CATALOG . 'mailhive/common/languages/' . $_SESSION['language'] . $file_extension);
} else {
    include_once(DIR_FS_CATALOG . 'mailhive/common/languages/english' . $file_extension);
}


//coming soon: MailBeez Professional
if (file_exists(DIR_FS_CATALOG . 'mailhive/pro/pro.php')) {
    include_once(DIR_FS_CATALOG . 'mailhive/pro/pro.php');
}

require_once(DIR_FS_CATALOG . 'mailhive/common/functions/advanced_simulations.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/class_loader.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/email_engine.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/event_log.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/price.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/template_engine.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/update.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/versioncheck.php');


// include the list of functions plugins
$function_plugins_dir = DIR_FS_CATALOG . 'mailhive/common/functions/function_plugins/';
if ($dir = @dir($function_plugins_dir)) {
    while ($function_plugins_file = $dir->read()) {
        if (!is_dir($function_plugins_dir . $function_plugins_file)) {
            if (preg_match('/\.php$/', $function_plugins_file) > 0) {
                require_once($function_plugins_dir . $function_plugins_file);
            }
        }
    }
    $dir->close();
}

function mh_db_fetch_array()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_fetch_array', $args);
            break;
        case 'zencart':
            return call_user_func_array('tep_db_fetch_array', $args);
            break;
        case 'xtc':
        case 'gambio':
            $args[0] = &$args[0];
            return call_user_func_array('xtc_db_fetch_array', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_query()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_query', $args);
            break;
        case 'zencart':
            return call_user_func_array('tep_db_query', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_query', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_perform()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_perform', $args);
            break;
        case 'zencart':
            return call_user_func_array('tep_db_perform', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_perform', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_num_rows()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_num_rows', $args);
            break;
        case 'zencart':
            return call_user_func_array('tep_db_num_rows', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_num_rows', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_insert_id()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_insert_id', $args);
            break;
        case 'zencart':
            return call_user_func_array('tep_db_insert_id', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_insert_id', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_prepare_input()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_prepare_input', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_db_prepare_input', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_prepare_input', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_db_input()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_db_input', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_db_input', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_db_input', $args);
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_output_string()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_output_string', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_output_string', $args);
            break;
        case 'xtc':
        case 'gambio':
            $args[1] = array('"' => '&quot;');
            return call_user_func_array('xtc_parse_input_field_data', $args);
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_get_languages_directory($code)
{
    $language_query = mh_db_query("select languages_id, directory, name from " . TABLE_LANGUAGES . " where code = '" . mh_db_input($code) . "'");
    if (mh_db_num_rows($language_query)) {
        $language = mh_db_fetch_array($language_query);
        $languages_id = $language['languages_id'];
        return array("directory" => $language['directory'],
                     "name" => $language['name'],
                     "languages_id" => (int)$languages_id);
    } else {
        return false;
    }
}

function mh_get_languages()
{
    $languages_query = mh_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    while ($languages = mh_db_fetch_array($languages_query)) {
        $languages_array[] = array('id' => $languages['languages_id'],
                                   'name' => $languages['name'],
                                   'code' => $languages['code'],
                                   'image' => $languages['image'],
                                   'directory' => $languages['directory']);
    }

    return $languages_array;
}

function mh_get_customers_language_id()
{
    $args = func_get_args();
    if (function_exists('mh_lng_get_id')) {
        ob_start();
        $language_id = mh_lng_get_id($args[0], $args[1], $args[2]);
        ob_end_clean();
        return $language_id;
    } else {
        return $_SESSION['languages_id'];
    }
}

function mh_href_link()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_href_link', $args);
            break;
        case 'zencart':
            $args[3] = true;
            $args[4] = true;
            $args[5] = true; // set static link
            return call_user_func_array('zen_href_link', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_href_link', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_href_link_plain($link, $param_string = '')
{
    // just an alias to keep things clen
    return mh_href_email_link($link, $param_string, true);
}

function mh_href_email_link($link, $param_string = '', $static = false)
{
    // output links
    switch (MH_PLATFORM) {
        case 'zencart':
            $link_out = zen_href_link($link, $param_string, 'NONSSL', false, true, $static);
            break;
        default:
            $link_out = mh_href_link($link, $param_string, 'NONSSL', false);
            break;
    }
    return $link_out;
}

function mh_urlencode($url)
{
    // replace / with --
    //return urlencode($url);
    return $url;
}

function mh_urldecode($url)
{
    // replace -- with /
    //return urldecode($url);
    return $url;
}


function mh_draw_form()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_form', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_form', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_form', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_draw_hidden_field()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_hidden_field', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_hidden_field', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_hidden_field', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_draw_input_field()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_input_field', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_input_field', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_input_field', $args);
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_draw_textarea_field()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
            if (MH_PLATFORM_OSCMAX_25) {
                return tep_draw_textarea_field($args[0], $args[2], $args[3], $args[4], $args[5], $args[6]);
                break;
            }
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_textarea_field', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_textarea_field', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_textarea_field', $args);
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_image_submit()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_image_submit', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_image_submit', $args);
            break;
        case 'xtc':
        case 'gambio':
            $button_text = 'Submit';
            if ($args[0] == 'button_update.gif')
                $button_text = BUTTON_UPDATE;
            return '<input type="submit" class="button" onClick="this.blur();" value="' . $button_text . '"/>';
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_image_button()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_image_button', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_image_button', $args);
            break;
        case 'xtc':
        case 'gambio':
            if ($args[0] == 'button_module_install.gif')
                return BUTTON_MODULE_INSTALL;
            if ($args[0] == 'button_module_remove.gif')
                return BUTTON_MODULE_REMOVE;
            if ($args[0] == 'button_edit.gif')
                return BUTTON_EDIT;
            if ($args[0] == 'button_cancel.gif')
                return BUTTON_CANCEL;

            break;
        default:
            echo 'platform not supported';
    }
}


function mh_review_button()
{
    //     global $language; // temporarily set to user language, set back before return
    // find the review-button
    switch (MH_PLATFORM) {
        case 'zencart':
            $review_button = zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT, 'border="0"');
            break;
        case 'creloaded':
            $review_button = tep_template_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS, 'align="middle" border="0"');
            break;
        default:
            $review_button = mh_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW, 'border="0"');
            break;
    }
    return mh_rewriteImgSrc($review_button, HTTP_SERVER . DIR_WS_HTTP_CATALOG);
}

function mh_redirect()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_redirect', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_redirect', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_redirect', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_call_function()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_call_function', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_call_function', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_call_function', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_not_null()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_not_null', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_not_null', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_not_null', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_draw_separator()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_separator', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_separator', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_separator', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_class_exists()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_class_exists', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_class_exists', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_class_exists', $args);
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_image()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
            if (MH_PLATFORM_OSCMAX_25) {
                $args[0] = preg_replace('#' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . '#', '', $args[0]);
            }
            return call_user_func_array('tep_image', $args);
            break;
        case 'creloaded':
            if (MH_CONTEXT == 'STORE') {
                $args[0] = preg_replace('#' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . '#', '', $args[0]);
            }
            return call_user_func_array('tep_image', $args);
            break;
        case 'digistore':
            return call_user_func_array('tep_image', $args);
            break;
        case 'zencart':
            if (MH_CONTEXT == 'STORE') {
                return call_user_func_array('zen_image_OLD', $args);
            }
            return call_user_func_array('zen_image', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_image', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_product_image($products_array)
{
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'digistore':
        case 'creloaded':
        case 'zencart':
            $img_folder = DIR_WS_IMAGES;
            $img_folder .= (defined('DYNAMIC_MOPICS_THUMBS_DIR')) ? DYNAMIC_MOPICS_THUMBS_DIR : ''; // osCMax
            break;
        case 'xtc':
        case 'gambio':
            // $img_folder = DIR_WS_INFO_IMAGES; // full size images
            $img_folder = DIR_WS_THUMBNAIL_IMAGES;
            break;
        default:
            echo 'platform not supported';
    }

    $image = mh_image($img_folder . $products_array['products_image'], $products_array['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="10" vspace="0" align="left" border="0"');
    return mh_rewriteImgSrc($image, HTTP_SERVER . DIR_WS_HTTP_CATALOG);
}

function mh_rewriteImgSrc($input, $server)
{
    return preg_replace('#<img src="#', '<img src="' . $server, $input);
}

function mh_product_image_src($image)
{
    $match_result = preg_match("#src=\"([a-zA-Z0-9%?&.;:/=+_-]*)\"#", $image, $match);
    return $match[1];
}


function mh_date_short()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_date_short', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_date_short', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_date_short', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_cfg_select_option()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_cfg_select_option', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_cfg_select_option', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_cfg_select_option', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_cfg_select_multioption($select_array, $key_value, $key = '')
{
    // thanks to zencart
    $string = '';
    for ($i = 0; $i < sizeof($select_array); $i++) {

        if (!isset($select_array[$i]))
            continue; // in case of label entries the size is 2x

        $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
        $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
        $key_values = explode(", ", $key_value);
        if (in_array($select_array[$i], $key_values))
            $string .= ' CHECKED';
        $string .= ' id="' . strtolower($select_array[$i] . '-' . $name) . '"> ' . '<label for="' . strtolower($select_array[$i] . '-' . $name) . '" class="inputSelect">';
        $string .= ($select_array['label' . $i] != '') ? $select_array[$i] . ' - ' . $select_array['label' . $i]
                : $select_array[$i];

        $string .= '</label>' . "\n";
    }
    $string .= '<input type="hidden" name="' . $name . '" value="--none--">';
    return $string;
}

function mh_cfg_pull_down_order_statuses()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_cfg_pull_down_order_statuses', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_cfg_pull_down_order_statuses', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_cfg_pull_down_order_statuses', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_get_order_status_name()
{
    if (MH_CONTEXT == 'STORE') return; // not defined in storefront

    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_order_status_name', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_get_order_status_name', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_order_status_name', $args);
            break;
        default:
            echo 'platform not supported';
    }
}


function mh_get_order_status_name_multiple()
{
    $args = func_get_args();
    $list_of_order_status = $args[0];

    $array_of_order_status = explode(',', $list_of_order_status);

    while (list(, $value) = each($array_of_order_status)) {
        $args[0] = $value;
        $array_of_order_status_names[] = call_user_func_array('mh_get_order_status_name', $args);
    }


    $list_of_order_status_names = implode(',', $array_of_order_status_names);

    return $list_of_order_status_names;
}


function mh_insert_config_value($config_array, $upate = false)
{
    $check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $config_array['configuration_key'] . "'");
    if (mh_db_num_rows($check_query) > 0 && !$upate) {
        // config already exists, do not update
        return false;
    } elseif (mh_db_num_rows($check_query) > 0 && $upate) {
        // config already exists, perform update
        return mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . addslashes($config_array['configuration_value']) . "', last_modified = now() where configuration_key = '" . $config_array['configuration_key'] . "'");
    }

    // no config value yet, insert:
    // otherwise insert config
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
        case 'zencart':
            return mh_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('" . addslashes($config_array['configuration_title']) . "', '" . $config_array['configuration_key'] . "', '" . addslashes($config_array['configuration_value']) . "', '" . addslashes($config_array['configuration_description']) . "', '6', '1', '" . addslashes($config_array['set_function']) . "', '" . addslashes($config_array['use_function']) . "', now())");
            break;
        case 'xtc':
        case 'gambio':
            // no configuration_title, configuration_description
            // add extra values for storing these fields to avoid language-files (they are stil optional)
            xtc_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('" . $config_array['configuration_key'] . '_TITLE_DEPR' . "', '" . addslashes($config_array['configuration_title']) . "',  '6', '1', '', now())");
            xtc_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('" . $config_array['configuration_key'] . '_DESC_DEPR' . "', '" . addslashes($config_array['configuration_description']) . "',  '6', '1', '', now())");

            return xtc_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, use_function, date_added) values ('" . $config_array['configuration_key'] . "', '" . addslashes($config_array['configuration_value']) . "',  '6', '1', '" . addslashes($config_array['set_function']) . "', '" . addslashes($config_array['use_function']) . "', now())");

            break;
        default:
            echo 'platform not supported';
    }
}

function mb_admin_button($href, $text, $id = '', $mode = 'popup', $type = 'button', $parameters = '')
{
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
        case 'zencart':
        case 'xtc':
        case 'gambio':
        default:
            if ($mode == 'popup') {
                if ($type == 'button') {
                    if (MAILBEEZ_MAILHIVE_POPUP_MODE == 'CeeBox') {
                        if (MH_PLATFORM_OSC_23) {
                            return tep_draw_button($text, 'document', $href, '', array('type' => 'submit', 'params' => ' class="ceebox" rel="iframe width:650"'));
                        } else {
                            return '<a id="' . $id . '" class="ceebox button_mailbeez" rel="iframe width:650" href="' . $href . '" target="_blank"><input class="button mailbeez" type="Button" onclick="return false;" value="' . $text . '"></a>';
                        }
                    } else {
                        return '<a id="' . $id . '" style="border: 1px solid black; padding: 3px; margin: 5px; line-height: 25px; " href="' . $href . '" target="_blank">' . $text . '</a>';
                    }
                } elseif ($type == 'link') {
                    return '<a id="' . $id . '" ' . $parameters . '  class="ceebox " rel="iframe width:650" href="' . $href . '" target="_blank">' . $text . '</a>';
                }
            } elseif ($mode == "link") {
                if ($type == 'button') {
                    if (MAILBEEZ_MAILHIVE_POPUP_MODE == 'CeeBox') {
                        if (MH_PLATFORM_OSC_23) {
                            return tep_draw_button($text, 'document', $href, '', array('type' => 'submit', 'params' => ''));
                        } else {
                            return '<a id="' . $id . '" class="button_mailbeez"  href="' . $href . '"><input class="button mailbeez" type="Button" onclick="document.location.href=\'' . $href . '\'; return false;" value="' . $text . '"></a>';
                        }
                    } else {
                        return '<a id="' . $id . '" style="border: 1px solid black; padding: 3px; margin: 5px; line-height: 25px; " href="' . $href . '" >' . $text . '</a>';
                    }
                } elseif ($type == 'link') {
                    return '<a id="' . $id . '" ' . $parameters . ' " href="' . $href . '">' . $text . '</a>';
                }
            }
    }
}

function mh_get_category_name()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_category_name', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_get_category_name', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_category_name', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_get_category_tree()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_category_tree', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_get_category_tree', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_category_tree', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_get_products_name()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_products_name', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_get_products_name', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_products_name', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_get_all_get_params()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_get_all_get_params', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_get_all_get_params', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_get_all_get_params', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_draw_pull_down_menu()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_draw_pull_down_menu', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_draw_pull_down_menu', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_draw_pull_down_menu', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_hide_session_id()
{
    $args = func_get_args();
    switch (MH_PLATFORM) {
        case 'oscommerce':
        case 'creloaded':
        case 'digistore':
            return call_user_func_array('tep_hide_session_id', $args);
            break;
        case 'zencart':
            return call_user_func_array('zen_hide_session_id', $args);
            break;
        case 'xtc':
        case 'gambio':
            return call_user_func_array('xtc_hide_session_id', $args);
            break;
        default:
            echo 'platform not supported';
    }
}

function mh_tabs($filename, $tab)
{
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}

if (!function_exists('sortbykeylength')) {

    function sortbykeylength($a, $b)
    {
        $alen = strlen($a);
        $blen = strlen($b);
        $r = '';
        if ($alen == $blen)
            $r = 0;
        if ($alen < $blen)
            $r = 1;
        if ($alen > $blen)
            $r = -1;
        return $r;
    }

}

function mhpi()
{
    $args = func_get_args();
    if (function_exists('mh_pro_inc')) {
        return call_user_func_array('mh_pro_inc', $args);
    }
    $id = array_shift($args); // remove args[0]
    return $args;
}

function mh_setDefaultMessage($messageStack)
{
    if (!mh_template_check_writeable()) {
        $messageStack->add('<b>mailhive/common/template_c</b> needs to be writeable (it is not!)', 'error');
    }
}

?>