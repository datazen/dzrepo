<?php
/*
  $Id: footer.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('footer', 'top');
if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') {
  require(DIR_WS_INCLUDES . 'counter.php');
  ?>
  <style>
.list-unstyled{font-family: roboto;}
  </style>
<div id="footer" class="container">
  <hr>
  <div class="row">
    <div class="col-sm-3 col-lg-3">
      <h4 class="line3 center standart-h4title"><span><?php echo MENU_TEXT_HOME; ?></span></h4>
      <ul class="footer-links list-indent list-unstyled">
        <li><a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'SSL'); ?>"><?php echo MENU_TEXT_HOME; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_PAGES, 'pID=2', 'SSL');?>"><?php echo MENU_TEXT_PRIVACY; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_PAGES, 'pID=3', 'SSL');?>"><?php echo MENU_TEXT_SHIPPING; ?></a></li>
      </ul>
    </div>
    <div class="col-sm-3 col-lg-3">
      <h4 class="line3 center standard-h4title"><span><?php echo MENU_TEXT_SPECIALS; ?></span></h4>
      <ul class="footer-links list-indent list-unstyled">
        <li><a href="<?php echo tep_href_link(FILENAME_SPECIALS, '', 'SSL'); ?>"><?php echo MENU_TEXT_SPECIALS; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'); ?>"><?php echo MENU_TEXT_CART; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo MENU_TEXT_CHECKOUT; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_CONTACT_US, '', 'SSL');?>"><?php echo MENU_TEXT_CONTACTUS; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_PAGES, 'pID=1', 'SSL');?>"><?php echo MENU_TEXT_TERMS; ?></a></li>
      </ul>
    </div>
    <div class="col-sm-3 col-lg-3">
      <h4 class="line3 center standard-h4title"><span><?php echo MENU_TEXT_MEMBERS; ?></span></h4>
      <ul class="footer-links list-indent list-unstyled">
        <li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo MENU_TEXT_MEMBERS; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'); ?>"><?php echo MENU_TEXT_PASS; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'); ?>"><?php echo IMAGE_BUTTON_HISTORY; ?></a></li>
        <li><a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'); ?>"><?php echo IMAGE_BUTTON_ADDRESS_BOOK; ?></a></li>
        <li><a href="#"><?php echo IMAGE_BUTTON_UPDATE; ?></a></li>
      </ul>
    </div>
    <div class="col-sm-3 col-lg-3 large-margin-bottom">
      <h4 class="line3 center standard-h4title"><span><?php echo MENU_TEXT_CALLUS; ?></span></h4>
      <address class="margin-left">
      <?php
      if($site_brand_info['store_brand_address'] != "") {
      	echo nl2br($site_brand_info['store_brand_address']);      
      } else {
      ?>
        <strong><?php echo STORE_NAME; ?></strong><br>
        <?php echo nl2br(STORE_NAME_ADDRESS); ?><br>
      <?php   } ?>
      </address>

    </div>
    <div class="margin-left small-padding-left margin-right small-padding-right"></div>
  </div>
  <p class="margin-top">
    <span class="float-right">
		<?php /*echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'cards.gif');*/?>
	</span>
  </p>
</div>
<?php
}/*
//if (!(getenv('HTTPS') == 'on')){
  if ($banner = tep_banner_exists('dynamic', 'googlefoot')) {
    ?>
    <br>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="footer_banner">
      <tr>
        <td align="center"><?php echo tep_display_banner('static', $banner); ?></td>
      </tr>
    </table>
    <?php
  }
//}
if (SITE_WIDTH!='100%') {
  ?>
      </td>
    </tr>
  </table>
  <?php
}
// RCI bottom*/
echo $cre_RCI->get('footer', 'bottom');
?>