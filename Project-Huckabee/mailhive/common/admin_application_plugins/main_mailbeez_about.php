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

<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td width="100%" valign="top" class="smallText">
                        <div style="border: 1px solid #909090; padding: 30px; margin-top: 10px; background-color: #e9e9e9; min-height: 300px;">
                            <div style="float: right; width: 230px; border: 1px solid #909090; padding: 25px; margin-top: 0px; background-color: #ffffff;  min-height: 260px; background:url(../mailhive/common/images/love128.png) 160px 200px no-repeat #ffffff;	">
                                <div style="float: right; padding-top: 2px;">
                                    <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/images/been_tiny_love.png"
                                         width="49" height="31" alt="" border="0" align="left" hspace="1" style="">
                                </div>
                                <div style="font-size: 20px; font-weight: bold; margin-top: 7px;"><?php echo MH_MAILBEEZ_LOVE ?></div>
                                <br>

                                <?php echo MH_MAILBEEZ_LOVE_TEXT; ?>
                                <br>
                                <br>
                                <br>

                                <a href="http://www.mailbeez.com/thank-you/" target="_blank"><img
                                        src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG . '/mailhive/common/languages/' . MH_MAILBEEZ_LOVE_BTN; ?>"
                                        width="98" height="26" alt="" border="0" hspace="20"></a>

                            </div>
                            <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/images/been_free.png"
                                 width="93" height="82" alt="" border="0" align="left" hspace="1"
                                 style="margin-right: 20px;"><?php echo MH_ABOUT; ?><br>

                            <br>

                            <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/images/chat32.png"
                                 width="32" height="32" alt="" border="0" align="absmiddle" hspace="5" vspace="10"> <a
                                href="http://mailbeez.uservoice.com/forums/58312"
                                target="_blank"><?php echo MH_ABOUT_BUTTONS_FEATURE ?></a> <br>
                            <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/images/star32.png"
                                 width="32" height="32" alt="" border="0" align="absmiddle" hspace="5" vspace="10"> <a
                                href="http://www.trustpilot.com/review/www.mailbeez.com"
                                target="_blank"><?php echo MH_ABOUT_BUTTONS_RATE_READ ?></a> <br>
                            <img src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/common/images/star_add32.png"
                                 width="32" height="32" alt="" border="0" align="absmiddle" hspace="5" vspace="10"> <a
                                href="<?php echo $trustpilot_evaluate ?>"
                                target="_blank"><?php echo MH_ABOUT_BUTTONS_RATE_RATE ?></a>

                            <br>
                            <br>
                            <br>
                            MailBeez - easy automated email marketing<br>
                            Version <?php echo ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '') ?>
                            , <?php echo PROJECT_VERSION;?> <br>
                            http://www.mailbeez.com<br>
                            <br>
                            Compatible with: osCommerce and its forks like<br>
                            osCMax, Zen-Cart, CRE Loaded, xt:commerce, xtc-modified, Gambio, DigiStore<br>

                            <br>
                            Copyright (c) 2010 MailBeez inspired and in parts based on<br>
                            Copyright (c) 2003 osCommerce by Harald Ponce de Leon | http://www.oscommerce.com/<br>
                            <br>
                            Released under the GNU General Public License<br>


                            <br><br>
                            <b>Individual Modules and Add-ons might be released under other license.</b>
                            <br><br>
                            Domain for Certification:
                            <br>
                            <div style="border: 1px solid #ff9090; background: #fff; padding: 15px; margin: 10px; text-align: center; min-width: 200px; float: left;"><?php echo $_SERVER['SERVER_NAME']?></div>

                            <br clear="all"><br>
                            <b>MailBeez provides no warranty.</b>
                            <br><br>
                            Released under the GNU General Public License
                            <br><br>
                            This program is distributed in the hope that it will be useful, but <b>WITHOUT ANY
                            WARRANTY</b> without even the implied warranty of <b>MERCHANTABILITY</b> or <b>FITNESS FOR A
                            PARTICULAR PURPOSE</b>.<br>
                            See the GNU General Public License for more details. You should have received a copy of the
                            GNU General Public License along with this program; if not, write to theFree Software
                            Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.<br><br>
                            See <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">http://www.gnu.org/copyleft/gpl.html</a>
                            for details.


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