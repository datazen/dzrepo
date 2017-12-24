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

echo $MAILBEEZ_TABS;

?>
<link rel="stylesheet" type="text/css" media="print, projection, screen"
      href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/admin_application_plugins/main_mailbeez.css">

<?php
if (isset($_SESSION['mailbeez_new'][$tab])) {
    ?>

<div class="mb_inline_msg msg">
    <div class="mb_inline_msg img">
        <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG;?>/mailhive/common/images/mb_icon.png" width="32"
             height="32">
    </div>
    <div class="mb_inline_msg text">
        <strong><?php echo MAILBEEZ_VERSION_CHECK_MSG_INTRO; ?></strong>
        <br/>
        <?php echo $_SESSION['mailbeez_upd_msg'];?>
    </div>
</div>

<div class="mb_inline_msg new_modules">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <td class="mb_inline_msg_td">
            <?php echo sprintf(MH_VERSIONCHECK_INFO_NEW, $_SESSION['mailbeez_new_cnt'][$tab]); ?><br>

            <?php

            $toggle_premium = true;
            foreach ($_SESSION['mailbeez_new'][$tab] as $key => $module_info_array) {
                if ($toggle_premium && !$module_info_array['price']) {
                    echo '<br style="clear: left"><hr size="1" noshade style="color: #70b0e0">';
                    $toggle_premium = false;
                }
                ?>
                <div class="mb_inline_msg_inner item">
                    <div class="mb_inline_msg_inner item_content <?php echo ($module_info_array['price']) ? 'premium'
                            : '' ?> <?php echo ($module_info_array['is_promo']) ? 'promo' : '' ?>">
                        <div class="mb_inline_msg_inner item_img <?php echo ($module_info_array['price']) ? 'premium'
                                : '' ?>">
                            <?php echo mh_image($module_info_array['image'], '', '32', '32', 'align="absmiddle"'); ?>
                        </div>
                        <div class="mb_inline_msg_inner item_text <?php echo ($module_info_array['price']) ? 'premium'
                                : '' ?>">
                            <a href="http://www.mailbeez.com/documentation/<?php echo $tab . '/' . $key . '/' . MH_LINKID_1 ?>"
                               target="_blank">
                                <h1><?php echo $module_info_array['title'] ?></h1>
                                <?php echo ($module_info_array['price']) ? $module_info_array['teaser'] : ''; ?>
                                <div style="font-weight: bold;"><?php echo ($module_info_array['price'])
                                        ? 'Download: ' . $module_info_array['price'] : ''; ?></div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
        </td>
    </table>
</div>

            <?php

}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php if (defined('MAILBEEZ_MAILHIVE_STATUS')) { ?>
<tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULES; ?></td>
    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
    <td class="dataTableHeadingContent"><?php echo '&nbsp;'; ?></td>
    <td class="dataTableHeadingContent" align="right"><?php echo (MH_PLATFORM == 'gambio') ? ''
            : TABLE_HEADING_ACTION; ?>&nbsp;</td>
</tr>
    <?php } ?>
<?php

$directory_array = mh_read_module_directory($module_directory_current);
sort($directory_array);

$installed_modules = array();
$installed_modules_versions = array();
$all_modules = array();

for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
    $offset = '';
    $file = $directory_array[$i];
    $class = substr($file, 0, strrpos($file, '.'));

    mh_load_modules_language_files($module_directory_current, $class, $file_extension);

    // include class
    if (file_exists($module_directory_current . $file)) {
        include_once($module_directory_current . $file);
    }

    if (mh_class_exists($class)) {
        if (!is_object($GLOBALS[$class])) {
            $GLOBALS[$class] = new $class; // new object
        }
        $module = $GLOBALS[$class];
        $directory_submodules_array = array();
        $all_submodules = array();

        if ($module->check() < 1) {
            // not installed, push to end
            $offset = '99999999';
        }
        if ($module->sort_order > 0) {
            $all_modules_sortorder = $offset . ($module->sort_order * 10000);
        } else {
            $all_modules_sortorder = $offset;
        }

        if ($module->check() > 0 && !(isset($module->stealth) ? $module->stealth : false)) {
            // load submodules

            // show submodules when main module or submodule selected
            $show_submodules = preg_match('#' . $module->code . '#', $_GET['module']);

            //if ($module->has_submodules == true && $show_submodules) {
            if ($module->has_submodules == true) {
                list($all_submodules, $installed_modules) = mh_load_submodules($module_directory_current, $class, $all_modules_sortorder, $installed_modules);
            } elseif ($module->display_as_submodule_of != '') {
                $all_modules_sortorder += 900;
            } else {
                $all_modules_sortorder += 1000;
            }
            $installed_modules[$all_modules_sortorder . $class] = $file; // make key unique but sortable as integer
        }
        $all_modules[$all_modules_sortorder . $class] = $file;

        if (is_array($all_submodules)) {
            $all_modules = array_merge($all_submodules, $all_modules); // insert submodules
        }

        if (!(isset($module->stealth) ? $module->stealth : false)) {
            $installed_modules_versions[$class] = $module->code . '|' . $module->version . '|' . $module->check() . '|' . $module->enabled;
        }
    } // if exists
} // for
// sort all_modules
uksort($all_modules, "sortbyintvalue");
uksort($installed_modules, "sortbyintvalue");

while (list($sort_order_key, $file) = each($all_modules)) {
    $class = substr($file, 0, strrpos($file, '.'));
    if (is_object($GLOBALS[$class])) {
        $module = $GLOBALS[$class];
        $module->versioncheck_info = (($_SESSION['mailbeez_upd'][$module->code] > 0)
                ? '<span class="upd_cnt list"><a style="color: #fff" href="' . $module->documentation_root . $module->documentation_key . '?update=true' . MH_LINKID_2 . '" target="_blank">' . MH_VERSIONCHECK_INFO_NEWVERSION . ' ' . $_SESSION['mailbeez_upd'][$module->code] . '</a></span>'
                : null); // experimental

        $module->show_submodules = (preg_match('#' . $module->module . '#', $_GET['module']));

        if ($module->hidden != false) {
            continue;
        }
        if ($module->is_submodule_of != false && !$module->show_submodules) {
            continue;
        }


        if ((!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $class))) && !isset($mInfo)) {
            $module_info = array('code' => $module->get_module_id(),
                                 'module' => $module->module,
                                 'title' => $module->title,
                                 'version' => $module->version,
                                 'required_mb_version' => $module->required_mb_version,
                                 'removable' => isset($module->removable) ? $module->removable : 'true',
                                 'common_admin_action_plugins' => $module->common_admin_action_plugins,
                                 'admin_action_plugins_path' => $module->admin_action_plugins_path,
                                 'admin_action_plugins' => $module->admin_action_plugins,
                                 'description' => $module->description,
                                 'description_image_src' => $module->description_image,
                                 'documentation_root' => $module->documentation_root,
                                 'documentation_key' => $module->documentation_key,
                                 'icon' => (isset($module->icon) ? $module->icon : null),
                                 'has_submodules' => $module->has_submodules,
                                 'enabled' => $module->enabled,
                                 'status' => $module->check(),
                                 'is_editable' => $module->is_editable,
                                 'is_configurable' => $module->is_configurable,
                                 'signature' => (isset($module->signature) ? $module->signature : null));
            $module_keys = $module->keys();

            $keys_extra = array();
            for ($j = 0, $k = sizeof($module_keys); $j < $k; $j++) {
                if (MH_PLATFORM == 'xtc' || MH_PLATFORM == 'gambio') {
                    // no configuration_title, configuration_description in xtc
                    $key_value_query = mh_db_query("select configuration_value, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_keys[$j] . "'");

                    // if language constants exist take these
                    // backwards compatibility
                    if (!defined(strtoupper($module_keys[$j]) . '_TITLE')) {
                        if (!defined(strtoupper($module_keys[$j] . '_TITLE_DEPR'))) {
                            $key_value_title_query = mh_db_query("select configuration_value, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . strtoupper($module_keys[$j]) . '_TITLE_DEPR' . "'");
                            $key_value_title = mh_db_fetch_array($key_value_title_query);
                            $keys_extra[$module_keys[$j]]['title'] = $key_value_title['configuration_value'];
                        } else {
                            $keys_extra[$module_keys[$j]]['title'] = constant(strtoupper($module_keys[$j] . '_TITLE_DEPR'));
                        }
                    } else {
                        $keys_extra[$module_keys[$j]]['title'] = constant(strtoupper($module_keys[$j] . '_TITLE'));
                    }
                    if (!defined(strtoupper($module_keys[$j]) . '_DESC')) {
                        if (!defined(strtoupper($module_keys[$j] . '_DESC_DEPR'))) {
                            $key_value_desc_query = mh_db_query("select configuration_value, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . strtoupper($module_keys[$j]) . '_DESC_DEPR' . "'");
                            $key_value_desc = mh_db_fetch_array($key_value_desc_query);
                            $keys_extra[$module_keys[$j]]['description'] = $key_value_desc['configuration_value'];
                        } else {
                            $keys_extra[$module_keys[$j]]['description'] = constant(strtoupper($module_keys[$j] . '_DESC_DEPR'));
                        }
                    } else {
                        $keys_extra[$module_keys[$j]]['description'] = constant(strtoupper($module_keys[$j] . '_DESC'));
                    }
                } else {
                    $key_value_query = mh_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_keys[$j] . "'");
                }
                $key_value = mh_db_fetch_array($key_value_query);
                if (MH_PLATFORM != 'xtc' && MH_PLATFORM != 'gambio') {
                    // load language constants if available
                    // if not in language file take default from configuration db
                    $keys_extra[$module_keys[$j]]['title']
                            = (defined(strtoupper($module_keys[$j]) . '_TITLE'))
                            ? constant(strtoupper($module_keys[$j]) . '_TITLE') : $key_value['configuration_title'];
                    $keys_extra[$module_keys[$j]]['description']
                            = (defined(strtoupper($module_keys[$j]) . '_DESC'))
                            ? constant(strtoupper($module_keys[$j]) . '_DESC')
                            : $key_value['configuration_description'];
                }

                $keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'];
                $keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'];
                $keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'];
            }

            $module_info['keys'] = $keys_extra;

            $mInfo = new objectInfo($module_info);
        }
        if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code)) {
            if ($module->check() > 0 && $mInfo->is_configurable == true) {
                echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $class . '&action=edit') . '\'">' . "\n";
            } else {
                echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
            }
        } else {
            if (($module->is_submodule_of != false) && ($module->check() < 1)) {
                continue;
            } // don't show submodules when main module is not installed
            echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $class) . '\'">' . "\n";
        }
        ?>
    <td class="dataTableContent">

        <?php
        if ($module->check() < 1) {
        echo mh_image(DIR_WS_CATALOG . 'mailhive/common/images/install_bee.png', '', '', '', 'align="absmiddle" ');
    }
        ?>
        <?php
          if ($module->check() > 0 && ($module->is_submodule_of != false || $module->display_as_submodule_of != false)) {
        echo ($module->icon != '') ? '<span style="display: block; float: left; width: 17px">&nbsp;</span>' : ' - ';
    } else {
        echo '';
    };
        ?>
        <?php
        if ($module->has_submodules && $module->show_submodules && $module->check() > 0) {
        echo mh_image(DIR_WS_CATALOG . 'mailhive/common/images/arrow_open.png', '', '', '', 'align="absmiddle" style="margin-left: 4px;"');
    } elseif ($module->has_submodules && $module->check() > 0) {
        echo mh_image(DIR_WS_CATALOG . 'mailhive/common/images/arrow_closed.png', '', '', '', 'align="absmiddle" style="margin-left: 4px;"');
    } elseif ($module->check() > 0) {
        echo '<span style="display: block; float: left; width: 17px">&nbsp;</span>';

    }
        ?>

        <?php
          if ($module->icon != '') {
        echo mh_image($module_directory_current_ws . $module->module . '/' . $module->icon, '', '13', '13', 'align="absmiddle"');
    }
        ?>


        <?php echo $module->title; ?>
        <?php echo $module->versioncheck_info; // experimental
        ?>

    </td>
    <td class="dataTableContent" align="right">
        &nbsp;<?php if (is_numeric($module->sort_order) && (int)$module->sort_order > 0 && ($module->is_submodule_of == false && $module->display_as_submodule_of == false))
        echo $module->sort_order; ?></td>
    <td class="dataTableContent" align="right">&nbsp;<?php
        if ($module->check() < 1 || $module->is_submodule_of != false) {
        echo '&nbsp;';
    } else {
        if ($module->enabled) {
            echo (MAILBEEZ_MAILHIVE_STATUS == 'False')
                    ? mh_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10)
                    : mh_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10);
        } else {
            echo (MAILBEEZ_MAILHIVE_STATUS == 'False')
                    ? mh_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10)
                    : mh_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
        }
    }
        ?></td>
    <td class="dataTableContent" align="right"><?php
          if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code)) {
        echo mh_image(DIR_WS_IMAGES . 'icon_arrow_right.gif');
    } else {
        echo '<a href="' . mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $class) . '">' . mh_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
    }
        ?>&nbsp;</td>
    </tr>
        <?php

    }
}
?>
<?php
  if (sizeof($all_modules) == 0) {
    ?>
<tr class="dataTableRow">
    <td class="dataTableContent" align="center" colspan="4"><?php echo MH_NO_MODULE; ?></td>
</tr>

    <?php

}
?>

<?php if ($tab == 'mailbeez'): ?>
<tr class="dataTableRow">
    <td class="dataTableContent"><a href="http://www.mailbeez.com/download/<?php echo MH_LINKID_1;?>"
                                    target="_blank"><?php echo mh_image(DIR_WS_CATALOG . 'mailhive/common/images/more_beez.png', '', '', '', 'align="absmiddle"'); ?>
        <?php
        //echo mh_image(DIR_WS_CATALOG . 'mailhive/common/images/minibeez.png', '', '', '', 'align="absmiddle" style="margin-left: 0px;"');
        ?> <?php echo MH_DOWNLOAD_LINK_LIST; ?></a>
    </td>
    <td class="dataTableContent" align="right">&nbsp;</td>
    <td class="dataTableContent" align="right">&nbsp;</td>
    <td class="dataTableContent" align="right">&nbsp;</td>
</tr>
    <?php endif; ?>
<?php
  // update some settings
// installed modules
$check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_key_current . "'");

if (mh_db_num_rows($check_query)) {
    $check = mh_db_fetch_array($check_query);
    if ($check['configuration_value'] != implode(';', $installed_modules)) {
        mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $installed_modules) . "', last_modified = now() where configuration_key = '" . $module_key_current . "'");
    }
} else {
    if (defined('MAILBEEZ_MAILHIVE_STATUS')) {
        mh_insert_config_value(array('configuration_title' => 'Installed Modules',
                                    'configuration_key' => $module_key_current,
                                    'configuration_value' => implode(';', $installed_modules),
                                    'configuration_description' => 'This is automatically updated. No need to edit.',
                                    'set_function' => ''
                               ));
    }
}

// version of modules
$check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_version_key_current . "'");
if (mh_db_num_rows($check_query)) {
    $check = mh_db_fetch_array($check_query);
    if ($check['configuration_value'] != implode(';', $installed_modules_versions)) {
        mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $installed_modules_versions) . "', last_modified = now() where configuration_key = '" . $module_version_key_current . "'");
    }
} else {
    if (defined('MAILBEEZ_MAILHIVE_STATUS')) {
        mh_insert_config_value(array('configuration_title' => 'Installed Modules|Versions',
                                    'configuration_key' => $module_version_key_current,
                                    'configuration_value' => implode(';', $installed_modules_versions),
                                    'configuration_description' => 'This is automatically updated. No need to edit.',
                                    'set_function' => ''
                               ));
    }
}
?>
<tr>
    <td colspan="3" class="smallText"><?php echo TEXT_MODULE_DIRECTORY . ' ' . $module_directory_current; ?>
        <br>
        <br>
<?php
      if ($mInfo->code == 'config') {
            ?>
            <br>
            <br>
            <?php
            if (!defined('MAILBEEZ_CRON_INSTALLED')) {
                echo MAILBEEZ_CONFIG_AUTOMATIC_TEXT_DESCRIPTION_LONG;
            }
            ?>
            <hr size="1" noshade>
            <?php echo MH_SECURE_URL ?>:<br>
            <div style="border: 1px dotted #909090; padding: 10px; margin-top: 10px; background-color: #e9e9e9">
            <?php
                                      echo MAILBEEZ_MAILHIVE_URL . 'run<br>';
                echo 'Silent-Mode: ' . MAILBEEZ_MAILHIVE_URL . 'run&silent=1';
                ?>
            </div>
            <?php } ?>
        <br>
        <br>
        <?php echo $MAILBEEZ_FOOTER; ?>
    </td>
</tr>
</table>
</td>
<?php
$heading = array();
$contents = array();

$module_icon = '';
if ($mInfo->icon != '') {
    $module_icon = mh_image($module_directory_current_ws . $mInfo->code . '/' . $mInfo->icon, '', '', '', 'align="right"') . ' ';
}

switch ($action) {
    case 'edit':
        $keys = '';
        reset($mInfo->keys);
        while (list($key, $value) = each($mInfo->keys)) {
            if ($key == $module_key || $key == $config_module_key || $key == $filter_module_key ||
                $key == $module_version_key || $key == $config_module_version_key || $key == $filter_module_version_key
            ) {
                // hide module-key value
                continue;
            }

            $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';

            if ($value['set_function']) {
                //eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");

                $set_function = $value['set_function'];
                if (preg_match('/->/', $set_function)) {

                    // e.g. 'mailbeez->mymodule->mySetMethod'
                    $class_method = explode('->', $set_function);
                    if (!is_object(${$class_method[1]})) {
                        include_once(DIR_FS_CATALOG . 'mailhive/' . $class_method[0] . '/' . $class_method[1] . '.php');
                        ${$class_method[1]} = new $class_method[1]();
                    }
                    $keys .= mh_call_function($class_method[2], array($value['value'], $key), ${$class_method[1]});
                } else {
                    eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
                }


            } else {
                $keys .= mh_draw_input_field('configuration[' . $key . ']', $value['value'], 'style="width:85%"');
            }
            $keys .= '<br><br>';
        }
        $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
        $heading[] = array('text' => '<b>' . $module_icon . $mInfo->title . '</b>');
        $contents = array('form' => mh_draw_form('modules', FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $_GET['module'] . '&action=save'));
        $contents[] = array('text' => $keys);

        if (MH_PLATFORM_OSC_23) {
            $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $_GET['module'])));
        } else {
            $contents[] = array('align' => 'center', 'text' => '<br><div align="center">' . mh_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a class="button" onClick="this.blur();" href="' . mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $_GET['module']) . '">' . mh_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></div>');
        }
        break;
    default:
        $heading[] = array('text' => '<b>' . $module_icon . $mInfo->title . ' V' . $mInfo->version . '</b>');

        if ($mInfo->status == '1') {
            $keys = '';
            reset($mInfo->keys);
            while (list($key, $value) = each($mInfo->keys)) {
                if ($key == $module_key || $key == $config_module_key || $key == $filter_module_key ||
                    $key == $module_version_key || $key == $config_module_version_key || $key == $filter_module_version_key
                ) {
                    // hide module-key value
                    continue;
                }

                $keys .= '<b>' . $value['title'] . '</b><br>';
                if ($value['use_function']) {
                    $use_function = $value['use_function'];
                    if (preg_match('/->/', $use_function)) {
                        // e.g. 'mailbeez->mymodule->myUseMethod'
                        $class_method = explode('->', $use_function);
                        if (!is_object(${$class_method[1]})) {
                            include_once(DIR_FS_CATALOG . 'mailhive/' . $class_method[0] . '/' . $class_method[1] . '.php');
                            ${$class_method[1]} = new $class_method[1]();
                        }

                        $keys .= mh_call_function($class_method[2], $value['value'], ${$class_method[1]});
                    } else {
                        $keys .= mh_call_function($use_function, $value['value']);
                    }
                } else {
                    $keys .= wordwrap($value['value'], 30, "<br>", true);
                }
                $keys .= '<br><br>';
            }
            $keys = substr($keys, 0, strrpos($keys, '<br><br>'));

            if (MH_PLATFORM_OSC_23) {
                $remove_button = tep_draw_button(IMAGE_MODULE_REMOVE, 'minus', tep_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $mInfo->code . '&action=remove'));
                $edit_button = tep_draw_button(IMAGE_EDIT, 'document', tep_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $mInfo->code . '&action=edit'));
            } else {
                $remove_button = '<a class="button" onClick="this.blur();" href="' . mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $mInfo->code . '&action=remove') . '">' . mh_image_button('button_module_remove.gif', IMAGE_MODULE_REMOVE) . '</a> ';
                $edit_button = '<a class="button" onClick="this.blur();" href="' . mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $mInfo->code . '&action=edit') . '">' . mh_image_button('button_edit.gif', IMAGE_EDIT) . '</a>';
            }

            if ($mInfo->removable == false) {
                $remove_button = '';
            }

            if ($mInfo->code == 'config' && !(MAILBEEZ_INSTALLED == 'config.php' || MAILBEEZ_INSTALLED == '')) {
                $remove_button = '';
            }

            if ($mInfo->is_configurable == false) {
                $edit_button = '';
            }

            $contents[] = array('align' => 'center', 'text' => '<div align="center">' . $edit_button . ' ' . $remove_button . '</div>');

            if (isset($mInfo->documentation_key)) {
                $contents[] = array('text' => '<br>' . mh_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '&nbsp;<b>' . TEXT_DOCUMENTATION . '</b> ' . $sversion . ' (<a href="' . $mInfo->documentation_root . $mInfo->documentation_key . MH_LINKID_1 . '" target="_blank">' . TEXT_VIEW_ONLINE . '</a>)');
            }
            /*
             if (isset($mInfo->signature) && (list($scode, $smodule, $sversion, $soscversion) = explode('|', $mInfo->signature))) {
             $contents[] = array('text' => '<br>' . mh_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '&nbsp;<b>' . TEXT_INFO_VERSION . '</b> ' . $sversion . ' (<a href="http://sig.oscommerce.com/' . $mInfo->signature . '" target="_blank">' . TEXT_INFO_ONLINE_STATUS . '</a>)');
             }
            */
            $description_image = '';

            if ($mInfo->description_image_src != '') {
                $description_image = mh_image($module_directory_current_ws . $mInfo->code . '/' . $mInfo->description_image_src, '', '64', '64', 'align="right" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"') . ' ';
            }

            $contents[] = array('text' => '<br>' . $description_image . $mInfo->description . '<br><br>');

            $contents[] = array('text' => '<div class="mb_action_box"></div>');

            if ($mInfo->code != 'config' && $mInfo->common_admin_action_plugins != '') {
                // load common admin GUI plugins
                $common_admin_action_plugins_includes = explode(';', $mInfo->common_admin_action_plugins);
                foreach ($common_admin_action_plugins_includes as $common_admin_action_plugins_includes) {
                    require_once(DIR_FS_CATALOG . 'mailhive/common/admin_action_plugins/' . $common_admin_action_plugins_includes);
                }
            }
            // load module admin gui plugins
            if ($mInfo->admin_action_plugins != '') {
                $admin_action_plugins_includes = explode(';', $mInfo->admin_action_plugins);
                foreach ($admin_action_plugins_includes as $admin_action_plugins_item) {
                    require_once($mInfo->admin_action_plugins_path . $mInfo->module . '/admin_action_plugins/' . $admin_action_plugins_item);
                }
            }

            if (defined('MAILBEEZ_CONFIG_TMPLMNGR_STATUS') || defined('MAILBEEZ_CONFIG_TMPLMNGR_LNG_STATUS')) {
                @mhpi('module_edit_content');
            } else {
                if ($mInfo->is_editable == true) {
                    require_once($config_module_directory . 'config_edit_promo/admin_action_plugins/edit.php');
                }
            }

            $contents[] = array('text' => '<br>' . $keys);
        } else {

            if ($mInfo->required_mb_version > MAILBEEZ_VERSION) {
                $contents[] = array('text' => sprintf(TEXT_UPGRADE_MAILBEEZ, $mInfo->required_mb_version));
            } else {
                if (MH_PLATFORM_OSC_23) {
                    $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_MODULE_INSTALL, 'plus', tep_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $mInfo->code . '&action=install')));
                } else {
                    $contents[] = array('align' => 'center', 'text' => '<div align="center"><a class="button" onClick="this.blur();" href="' . mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=' . $mInfo->code . '&action=install') . '">' . mh_image_button('button_module_install.gif', IMAGE_MODULE_INSTALL) . '</a></div>');
                }
            }
            $contents[] = array('text' => '<br>' . $mInfo->description);
        }
        break;
}


if ((sizeof($all_modules) > 0) && defined('MAILBEEZ_MAILHIVE_STATUS') && (mh_not_null($heading)) && (mh_not_null($contents))) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);
    ?>
<div class="smallText" style="float: right; border: 0px solid #c0c0c0; padding: 5px;">
    <div style="float: left; padding-right: 10px; padding-top: 10px;"><a
            href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=about'); ?>"><?php echo MH_MAILBEEZ_LOVE; ?></a></div>
    <div style="float: left; padding-top: 2px;">
        <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/images/been_tiny_love.png" width="49"
             height="31" alt="" border="0" align="left" hspace="1" style=""></div>
</div>
    <?php
      echo '            </td>' . "\n";
}
?>
</tr>
</table>