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

if (MH_AUTOINSTALL) {
    mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=config&action=install'));
}

?>
<link rel="stylesheet" type="text/css" media="print, projection, screen"
      href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/admin_application_plugins/install.css">
<tr height="100px">
    <td class="dataTableContent">

        <div class="install_main">
            <div class="install_area"><br><br>
                <span style="float: left;">
                  <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/images/help.png"
                       width="128"
                       height="128" alt="" border="0" hspace="10">
                </span>

                <span style="float: left; width: 50%">
                  <?php echo MH_INSTALL_SUPPORT; ?>
                </span>
                <br>
                <br>
            </div>
            <div class="install_area">
                <b>MailBeez provides NO warranty</b><br><br>
                <br>

                <span style="float: left;">
                    <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/images/been_free.png"
                         width="93"
                         height="82" alt="" border="0" hspace="10">
                </span>
                <span style="float: left; width: 50%">
                <?php echo MH_INSTALL_INTRO; ?><br>
                <br>
                <a class="button" onClick="this.blur();"
                   href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'set=' . $set . '&module=config&action=install'); ?>">
                    <?php echo mh_image_button('button_module_install.gif', IMAGE_MODULE_INSTALL); ?>
                </a>
                <br>
                </span>
            </div>
        </div>
    </td>
</tr>