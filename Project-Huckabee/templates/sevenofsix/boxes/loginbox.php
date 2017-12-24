  <div class="well">
  <?php
/*
  $Id: loginbox.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/'.FILENAME_LOGINBOX);
if ( ( ! strstr($PHP_SELF,'login.php')) && ( ! strstr($PHP_SELF,'create_account.php')) && ! isset($_SESSION['customer_id']) )  {
  if ( !isset($_SESSION['customer_id']) ) {
    ?>
    <!-- loginbox //-->
      <div class="box-header small-margin-bottom small-margin-left">My Account Info</div>

        <?php
        $loginboxcontent = "
        <form name=\"login\" method=\"post\" action=\"" . tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . "\">
             <div class=\"form-group\">
                   <label class=\"sr-only\"></label>
                   <input type=\"text\" name=\"email_address\" maxlength=\"96\" size=\"20\" value=\"\" placeholder=\"Email-Address\"  class=\"form-control\">
             </div>
             <div class=\"form-group\">
                   <label class=\"sr-only\"></label>
                      " . tep_draw_password_field('password','' , 'class="form-control" maxlength="40" size="20" autocomplete="off"placeholder="' . MENU_TEXT_PASSWORD . '"') . "
             </div>
			 <div class=\"buttons-set clearfix large-margin-top\">
			   <input type=\"submit\" value=\"Sign In\" class=\"pull-right btn btn-lg btn-primary\">
			 </div>
        </form>";
      echo '<div style="margin-left:5px">' . $loginboxcontent . '</div>';

        ?>
    <!-- loginbox eof//-->
    <?php
  } else {
    // If you want to display anything when the user IS logged in, put it
    // in here...  Possibly a "You are logged in as :" box or something.
  }
} else {
  if ( isset($_SESSION['customer_id']) ) {
    $pwa_query = tep_db_query("select purchased_without_account from " . TABLE_CUSTOMERS . " where customers_id = '" . $_SESSION['customer_id'] . "'");
    $pwa = tep_db_fetch_array($pwa_query);
    if ($pwa['purchased_without_account'] == '0') {
      ?>
      <!-- loginbox //-->

      <div class="box-header small-margin-bottom small-margin-left">My Account Info</div>
          <?php



        echo                                      '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . LOGIN_BOX_MY_ACCOUNT . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_EDIT . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_HISTORY . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . LOGIN_BOX_ADDRESS_BOOK . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'NONSSL') . '">' . LOGIN_BOX_PRODUCT_NOTIFICATIONS . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '">' . LOGIN_BOX_LOGOFF . '</a>'
                                      ;
          ?>

<!-- loginbox eof//-->
      <?php
    }
  }
}
?>
</div>