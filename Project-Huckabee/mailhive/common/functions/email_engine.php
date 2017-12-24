<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

/*

  abstraction layer for email-engine

  v2.5

 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


if (MAILBEEZ_CONFIG_EMAIL_BUGFIX_1 == 'True') {
    $GLOBALS['mailbeez_bugfix_1_pattern'] = array();
    $GLOBALS['mailbeez_bugfix_1_replace'] = array();

    $GLOBALS['mailbeez_bugfix_1_pattern'][0] = '/\..php/';
    $GLOBALS['mailbeez_bugfix_1_replace'][0] = '.php';

    $GLOBALS['mailbeez_bugfix_1_pattern'][1] = '/\..png/';
    $GLOBALS['mailbeez_bugfix_1_replace'][1] = '.png';

    $GLOBALS['mailbeez_bugfix_1_pattern'][2] = '/\..jpeg/';
    $GLOBALS['mailbeez_bugfix_1_replace'][2] = '.jpeg';

    $GLOBALS['mailbeez_bugfix_1_pattern'][3] = '/\..jpg/';
    $GLOBALS['mailbeez_bugfix_1_replace'][3] = '.jpg';

    $GLOBALS['mailbeez_bugfix_1_pattern'][4] = '/\..gif/';
    $GLOBALS['mailbeez_bugfix_1_replace'][4] = '.gif';

}

switch (MH_PLATFORM) {
    case 'oscommerce':
    case 'creloaded':
    case 'digistore':
        require_once(DIR_FS_CATALOG . 'mailhive/common/classes/oscommerce/emailMB.php');
        break;
    case 'zencart':
        return false;
        break;
    case 'xtc':
    case 'gambio':
        return false;
        break;
    default:
        echo 'platform not supported';
}

// switch between systems
function mh_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt)
{
    if (MAILBEEZ_MAILHIVE_STATUS == 'False') {
        return;
    }


    if (MAILBEEZ_CONFIG_EMAIL_BUGFIX_1 == 'True') {
        // try to fix that wired '..' issue
        // where e.g. file.php becomes file..php, image.png becomes image..png
        // could be already in the generated output (not very likely)

        $output_content_html = preg_replace($GLOBALS['mailbeez_bugfix_1_pattern'], $GLOBALS['mailbeez_bugfix_1_replace'], $output_content_html);
    }

    if (isset($mail['mailengine'])) {

        $args = func_get_args();

        if (preg_match('/->/', $mail['mailengine'])) {
            // e.g. 'configbeez->mymodule->myMailEngine'
            $class_method = explode('->', $mail['mailengine']);
            if (!is_object(${$class_method[1]})) {
                include_once(DIR_FS_CATALOG . 'mailhive/' . $class_method[0] . '/' . $class_method[1] . '.php');
                ${$class_method[1]} = new $class_method[1]();
            }
            call_user_func(array(${$class_method[1]}, $class_method[2]), $args);
        } elseif (function_exists($mail['mailengine'])) {
            call_user_func_array($mail['mailengine'], $args);
        } else {
            unset($mail['mailengine']);
            mh_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt);
        }
    } else {
        // default mailengine by platform
        switch (MH_PLATFORM) {
            case 'oscommerce':
            case 'creloaded':
            case 'digistore':
                return osc_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt);
                break;
            case 'zencart':
                return zencart_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt);
                break;
            case 'xtc':
            case 'gambio':
                return xtc_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt);
                break;
            default:
                echo 'platform not supported';
        }
    }
}

// function for osCommerce
function osc_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt)
{
    $mimemessage = new emailMailBeez(array('X-Mailer: mailbeez.com'));
    // add html and alternative text version
    $mimemessage->add_html($output_content_html, $output_content_txt);
    $mimemessage->build_message(); // encoding -> 76 character linebreak, replacements must be done before

    if (MAILBEEZ_CONFIG_EMAIL_BUGFIX_1 == 'True') {
        // try to fix that wired '..' issue
        // where e.g. file.php becomes file..php, image.png becomes image..png
        // ..or in the mimeclass (more likely)
        $mimemessage->output = preg_replace($GLOBALS['mailbeez_bugfix_1_pattern'], $GLOBALS['mailbeez_bugfix_1_replace'], $mimemessage->output);
    }
    $mimemessage->send($mail['firstname'] . ' ' . $mail['lastname'], $email_address, $sender_name, $sender, $output_subject, '');
}

// function for ZenCart
function zencart_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt)
{
    if (defined('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE') && MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE == 'False') {
        $html_msg = array('EMAIL_SUBJECT' => $output_subject,
                          'EMAIL_MESSAGE_HTML' => $output_content_html); // currently complete HTML mail
    } else {
        $html_msg = $output_content_html;
    }

    zen_mail($mail['firstname'] . ' ' . $mail['lastname'], $email_address, $output_subject, $output_content_txt, $sender_name, $sender, $html_msg, 'mailbeez');

    // email-format is determined by look-up of email-adress in customer base
}

// function for xtCommerce
function xtc_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt)
{
    // xtc_php_mail($from_email_address, $from_email_name, $to_email_address, $to_name, $forwarding_to, $reply_address, $reply_address_name, $path_to_attachement, $path_to_more_attachements, $email_subject, $message_body_html, $message_body_plain)
    return xtc_php_mail($sender, $sender_name, $email_address, $mail['firstname'] . ' ' . $mail['lastname'], '', $sender, $sender_name, '', '', $output_subject, $output_content_html, $output_content_txt);
}

?>