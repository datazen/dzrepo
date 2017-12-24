<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.1
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


$upd_cnt = $_SESSION['mailbeez_upd_cnt'];
$new_cnt = $_SESSION['mailbeez_new_cnt'];
?>
<div class="mailbeez_main_tabs">
    <link rel="stylesheet" type="text/css" media="print, projection, screen"
          href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/admin_application_plugins/tabs.css">
    <link rel="stylesheet" type="text/css" media="print, projection, screen"
          href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/admin_application_plugins/versioncheck.css">
    <ul class="tabs">
        <li><a <?php if ($tab == 'home'): ?>class="current"<?php endif; ?>
                href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=home'); ?>"><?php echo MH_TAB_HOME; ?><?php if ($upd_cnt['dashboardbeez'] > 0) { ?>
            <span class="upd_cnt"><?php echo $upd_cnt['dashboardbeez'] ?></span> <?php } ?><?php if ($new_cnt['dashboardbeez'] > 0) { ?>
            <span class="new_cnt"><?php echo $new_cnt['dashboardbeez'] ?></span><?php } ?></a></li>
        <li><a <?php if ($tab == 'mailbeez'): ?>class="current"<?php endif; ?>
                href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=mailbeez'); ?>"><?php echo MH_TAB_MAILBEEZ; ?><?php if ($upd_cnt['mailbeez'] > 0) { ?>
            <span class="upd_cnt"><?php echo $upd_cnt['mailbeez'] ?></span> <?php } ?><?php if ($new_cnt['mailbeez'] > 0) { ?>
            <span class="new_cnt"><?php echo $new_cnt['mailbeez'] ?></span><?php } ?></a></li>
        <li><a <?php if ($tab == 'filterbeez'): ?>class="current"<?php endif; ?>
                href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=filterbeez'); ?>"><?php echo MH_TAB_FILTER; ?><?php if ($upd_cnt['filterbeez'] > 0) { ?>
            <span class="upd_cnt"><?php echo $upd_cnt['filterbeez'] ?></span> <?php } ?><?php if ($new_cnt['filterbeez'] > 0) { ?>
            <span class="new_cnt"><?php echo $new_cnt['filterbeez'] ?></span><?php } ?></a></li>
        <li><a <?php if ($tab == 'reportbeez'): ?>class="current"<?php endif; ?>
                href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=reportbeez'); ?>"><?php echo MH_TAB_REPORT; ?><?php if ($upd_cnt['reportbeez'] > 0) { ?>
            <span class="upd_cnt"><?php echo $upd_cnt['reportbeez'] ?></span> <?php } ?><?php if ($new_cnt['reportbeez'] > 0) { ?>
            <span class="new_cnt"><?php echo $new_cnt['reportbeez'] ?></span><?php } ?></a></li>
        <li><a <?php if ($tab == 'configbeez'): ?>class="current"<?php endif; ?>
                href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=configbeez'); ?>"><?php echo MH_TAB_CONFIGURATION; ?><?php if ($upd_cnt['configbeez'] > 0) { ?>
            <span class="upd_cnt"><?php echo $upd_cnt['configbeez'] ?></span> <?php } ?><?php if ($new_cnt['configbeez'] > 0) { ?>
            <span class="new_cnt"><?php echo $new_cnt['configbeez'] ?></span><?php } ?></a></li>
        <li><a <?php if ($tab == 'about'): ?>class="current"<?php endif; ?>
                href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'tab=about'); ?>"><?php echo MH_TAB_ABOUT; ?></a></li>
    </ul>
</div>