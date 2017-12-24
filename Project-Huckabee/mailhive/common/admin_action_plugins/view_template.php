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
// button for view mail template in html and txt format

$common_plugin_actions = mb_admin_button(MAILBEEZ_MAILHIVE_URL . 'view&format=html&module=' . mh_urlencode($mInfo->code), MAILBEEZ_BUTTON_VIEW_HTML) . '  ' . mb_admin_button(MAILBEEZ_MAILHIVE_URL . 'view&format=txt&module=' . mh_urlencode($mInfo->code), MAILBEEZ_BUTTON_VIEW_TXT);

$contents[] = array('text' => '<div class="mb_action_box">' . mh_image(DIR_WS_CATALOG . 'mailhive/common/images/icon_view_32.png', '', '32', '32', 'align="right" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"') . '
        <div class="mb_action_box headline">' . MAILBEEZ_ACTION_VIEW_TEMPLATE_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_ACTION_VIEW_TEMPLATE_TEXT . '</div>
        <div class="mb_action_box buttons" style="clear: both"><div align="center">' . $common_plugin_actions . '</div></div>
        </div>');
?>