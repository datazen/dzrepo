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


// disable Gzip compression
define('GZIP_COMPRESSION', 'false');
define('GZIP_LEVEL', '0');
// define('STRICT_ERROR_REPORTING', true); // zencart
/*
error_reporting(E_ALL & ~ E_NOTICE);
ini_set('display_errors', 1);
*/

require('includes/application_top.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');
require_once(DIR_FS_CATALOG . 'mailhive/common/classes/mailhive.php');

define('TEXT_EMAIL_ALREADY_SEND', 'was already sent: ');
define('TEXT_EMAIL_SEND', 'successfully sent: ');
define('TEXT_EMAIL_BLOCKED', 'blocked by customer: ');
define('TEXT_EMAIL_FILTER_BLOCKED', 'blocked by filter: ');
define('TEXT_EMAIL_VALID_BLOCKED', 'blocked by validation: ');
define('TEXT_EMAIL_FILTER_STOP', 'stopped by filter: ');
$cfg_gender_array = array('f' => 'Female', 'm' => 'Male', 'unknown' => 'unknown');
$cfg_languages_array = (function_exists('mh_get_language_list')) ? mh_get_language_list() : '';


if (!defined('MAILBEEZ_VERSION')) {
    include(DIR_FS_CATALOG . 'mailhive/install/install.php');
    exit();
}

$_GET['module'] = mh_urlencode($_GET['module']); // handling of submodule urls with "/"
//error_reporting(-1);

$mailHive = new mailHive;

$mailhive_token = MAILBEEZ_MAILHIVE_TOKEN;



// for cron simple plugin:
if (isset($_GET['cron_simple'])) {
    $inc_cron_path = DIR_FS_CATALOG . 'mailhive/configbeez/config_cron_simple/includes/cron_simple_run.php';
    if (file_exists($inc_cron_path)) {
        require_once($inc_cron_path);
    }
    exit();
}


// for cron advanced plugin:
if (isset($_GET['cron_advanced'])) {
    $inc_cron_path = DIR_FS_CATALOG . 'mailhive/configbeez/config_cron_advanced/includes/cron_advanced_run.php';
    if (file_exists($inc_cron_path)) {
        require_once($inc_cron_path);
    }
    exit();
}

// call external module action e.g. block
if (isset($_GET['ma'])) {
    $module_action = $_GET['ma'];
    $module = (isset($_GET['m'])) ? $_GET['m'] : false;
    $module_params = (isset($_GET['mp'])) ? $_GET['mp'] : false;
    $result = $mailHive->moduleAction($module, 'external_' . $module_action, $module_params);
}

if (isset($_GET[$mailhive_token])) {
    $mpaction = $_GET[$mailhive_token];
} elseif (isset($_POST[$mailhive_token])) {
    $mpaction = $_POST[$mailhive_token];
}

$silent_mode = (isset($_GET['silent'])) ? true : false;

$output_plain = false;

$title_type = 'default';

if ($mpaction == 'view') {
    if (isset($_GET['module']) && $_GET['module'] != '') {
        $output_plain = true;
    } else {
        $title_type = 'choose_module';
    }
}
if ($mpaction == 'listLangView' || $mpaction == 'listLangTest') {
    $title_type = 'choose_lang';
}

if ($mpaction == 'listGndView' || $mpaction == 'listGndTest') {
    $title_type = 'choose_gender';
}

if ($mpaction == 'test') {
    if (!isset($_GET['module'])) {
        $title_type = 'choose_module';
    }
}

if (defined('MAILBEEZ_CONFIG_TMPLMNGR_LNG_STATUS') && MAILBEEZ_CONFIG_TMPLMNGR_LNG_STATUS == 'True') {
    if (!isset($_GET['lng_id'])) {
        if ($mpaction == 'test') {
            mh_redirect(MAILBEEZ_MAILHIVE_URL . 'listLangTest&' . mh_get_all_get_params(array(MAILBEEZ_MAILHIVE_TOKEN)));
        } elseif ($mpaction == 'view') {
            mh_redirect(MAILBEEZ_MAILHIVE_URL . 'listLangView&' . mh_get_all_get_params(array(MAILBEEZ_MAILHIVE_TOKEN)));
        }
    }
}

if (defined('MAILBEEZ_FILTER_ADD_GENDER_STATUS') && MAILBEEZ_FILTER_ADD_GENDER_STATUS == 'True') {
    if (!isset($_GET['gnd'])) {
        if ($mpaction == 'test') {
            mh_redirect(MAILBEEZ_MAILHIVE_URL . 'listGndTest&' . mh_get_all_get_params(array(MAILBEEZ_MAILHIVE_TOKEN)));
        }
        /*
         elseif ($mpaction == 'view') {
         mh_redirect( MAILBEEZ_MAILHIVE_URL . 'listGndView&' . mh_get_all_get_params(array(MAILBEEZ_MAILHIVE_TOKEN)));
         }
        */
    }
}

if ($silent_mode == true) {
    ob_start();
}

if ($output_plain == false) {
    ?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
  <html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
        <link rel="stylesheet" type="text/css" media="print, projection, screen"
              href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/common.css">
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
      <script type="text/javascript">
          function scrolldown() {
              var a = document.anchors.length;
              var b = document.anchors[a - 1];
              var y = b.offsetTop;
              window.scrollTo(0, y + 120);
          }
      </script>

    <?php switch ($title_type) {
        case 'choose_module':
            ?>
            <div class="pageHeading">Template Manager</div>
            Choose Module:<br><br>
                <?php
                          break;
        case 'choose_lang':
            ?>
            <div class="pageHeading">Template Manager</div>
            Choose Language:<br><br>
                <?php
                          break;
        case 'choose_gender':
            ?>
            <div class="pageHeading">Template Manager</div>
            Choose Gender:<br><br>
                <?php
                          break;
        default:
            ?>
            <div class="pageHeading">MailBeez - Mode: <?php echo MAILBEEZ_MAILHIVE_MODE ?>
                <!-- (platform: <?php echo MH_PLATFORM; ?>) --></div>
                <?php echo (defined('PROJECT_VERSION')) ? PROJECT_VERSION : ''; ?>

            <hr size="1" noshade>
                <?php
    } ?>

    <?php

}

if (MAILBEEZ_MAILHIVE_STATUS == 'False') {
    ?>
MailHive inactive - please activate MailHive and MailBeez in Basic Configuration
    <?php

} else {

    if ($mpaction == 'test') {
        if (isset($_GET['module']) && $_GET['module'] != '') {
            ?>
        <blockquote><br><br>
            <?php echo mh_draw_form('test', mh_href_link_plain(FILENAME_HIVE, mh_get_all_get_params(array('module', $mailhive_token)), 'NONSSL'), 'post', '') .
                       mh_draw_hidden_field($mailhive_token, 'sendTest') .
                       mh_draw_hidden_field('module', $_GET['module']); ?>
            <?php echo mh_draw_input_field('email', MAILBEEZ_MAILHIVE_EMAIL_COPY) ?>
            <input type="Submit" value="Send Test-Mail">
            </form>
            <br>
            <br>
            <?php if (MAILBEEZ_MAILHIVE_COPY == 'True') {
                echo 'Send Copy to: ' . MAILBEEZ_MAILHIVE_EMAIL_COPY;
            } ?>
        </blockquote>
            <?php

        } else {
            if (!is_array($mailHive->modules)) {
                ?>
            No Modules installed
                <?php

            } else {
                reset($mailHive->modules);
                while (list(, $mailbee_obj) = each($mailHive->modules)) {
                    $obj_code = substr($mailbee_obj, 0, strrpos($mailbee_obj, '.'));
                    $mailbeez_obj = $GLOBALS[$obj_code];
                    if (!$mailbeez_obj->do_process) continue;
                    ?>
                <div class="mh_choose">
                    <div class="mh_choose_module_send"><a
                            href="<?php echo MAILBEEZ_MAILHIVE_URL . 'test&module=' . urlencode($mailbeez_obj->get_module_id())  . '&' . mh_get_all_get_params(array( $mailhive_token)); ?>"><?php echo $mailbeez_obj->title; ?>
                        <br/>
                        <?php echo $_GET['module']; ?></a>
                    </div>
                </div>
                    <?php

                }
            }
        }
    } elseif ($mpaction == 'sendTest') {
        $mailHive->sendTest($_POST['email'], $_POST['module']);
    } elseif ($mpaction == 'listAudience') {
        $result = $mailHive->listAudience($_GET['module']);
    } elseif ($mpaction == 'listLangView') {
        foreach ($cfg_languages_array as $cfg_lng_item) {
            ?>
        <div class="mh_choose">
            <div class="mh_choose_item"><a
                    href="<?php echo MAILBEEZ_MAILHIVE_URL . 'view&lng_id=' . $cfg_lng_item['id'] . '&' . mh_get_all_get_params(array( $mailhive_token)); ?>"><?php echo mh_language_image($cfg_lng_item); ?> <?php echo $cfg_lng_item['text']; ?></a>
            </div>
        </div>
            <?php

        }
    } elseif ($mpaction == 'listLangTest') {
        foreach ($cfg_languages_array as $cfg_lng_item) {
            ?>
        <div class="mh_choose">
            <div class="mh_choose_item"><a
                    href="<?php echo MAILBEEZ_MAILHIVE_URL . 'test&lng_id=' . $cfg_lng_item['id'] . '&' . mh_get_all_get_params(array( $mailhive_token)); ?>"><?php echo mh_language_image($cfg_lng_item); ?> <?php echo $cfg_lng_item['text']; ?></a>
            </div>
        </div>
            <?php

        }
    } elseif ($mpaction == 'listGndTest') {
        foreach ($cfg_gender_array as $cfg_gnd_item => $cfg_gnd_item_text) {
            ?>
        <div class="mh_choose">
            <div class="mh_choose_item"><a
                    href="<?php echo MAILBEEZ_MAILHIVE_URL . 'test&gnd=' . $cfg_gnd_item . '&' . mh_get_all_get_params(array($mailhive_token)); ?>">
                <?php  echo mh_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG . 'mailhive/filterbeez/filter_add_gender/' . $cfg_gnd_item . '_b.png', $cfg_gnd_item, '24', '24', 'align="absmiddle" width="24" height="24"') . ' ';
                echo $cfg_gnd_item_text;
                ?></a>
            </div>
        </div>
            <?php

        }
    } elseif ($mpaction == 'view') {
        if (isset($_GET['module']) && $_GET['module'] != '') {
            $out = $mailHive->viewMail($_GET['module'], $_GET['format'], $_GET['mh_theme'], $_GET['mh_template']);
            if ($_GET['format'] == 'txt') {
                echo '<pre>';
            }
            echo $out[urldecode($_GET['module'])];
        } else {
            if (!is_array($mailHive->modules)) {
                ?>
            No Modules installed
                <?php

            } else {
                reset($mailHive->modules);
                while (list(, $mailbee_obj) = each($mailHive->modules)) {
                    $obj_code = substr($mailbee_obj, 0, strrpos($mailbee_obj, '.'));
                    $mailbeez_obj = $GLOBALS[$obj_code];
                    if (!$mailbeez_obj->do_process) continue;
                    ?>
                <div class="mh_choose">
                    <div class="mh_choose_module_preview"><a
                            href="<?php echo MAILBEEZ_MAILHIVE_URL . 'view&module=' . urlencode($mailbeez_obj->get_module_id()) . '&' . mh_get_all_get_params(array('module', $mailhive_token)); ?>"><?php echo $mailbeez_obj->title; ?>
                        <br/></a>
                    </div>
                </div>
                    <?php

                }
            }
        }
    } elseif ($mpaction == 'run') {
        $result = $mailHive->run($_GET['module']);
    } elseif ($mpaction == 'runconfirm') {
        ?>
    <blockquote><br><br>
        <button type="button"
                onclick="window.location.href='<?php echo mh_href_link_plain(FILENAME_HIVE, $mailhive_token . '=run&module=' . $_GET['module'], 'NONSSL') ?>'">
            RUN NOW - Mode:<?php echo MAILBEEZ_MAILHIVE_MODE ?></button>

        <br>
        <br>
        <br>
        Send Copy: <?php echo MAILBEEZ_MAILHIVE_COPY ?><br>
        <?php if (MAILBEEZ_MAILHIVE_COPY == 'True') {
            echo 'to: ' . MAILBEEZ_MAILHIVE_EMAIL_COPY;
        } ?>
    </blockquote>
        <?php

    } elseif ($mpaction == '') {
        // 
    }
    ?>
    <?php
          if ($output_plain == false) {
        ?>
        <?php echo str_repeat(" ", 4096); ?>
      </body>
    </html>
    <?php

    }
}
require(DIR_WS_INCLUDES . 'application_bottom.php');
if ($silent_mode == true) {
    $output = ob_get_contents();
    ob_end_clean();
    ?>
done.
<?php

}
?>