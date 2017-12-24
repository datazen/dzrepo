<?php
/*
  $Id: idealm_error.tpl.php v2.1 - CRELOADED v6.4.1 version

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
*/

  // RCI code start
  echo $cre_RCI->get('global', 'top');
  // RCI code eof

  // session_unregister('paymentid'); // Arthur: function is removed in CRE v6.3, use unset !
  unset($_SESSION['paymentid']);
  $cart = $cart_contents;
  $GLOBALS['cart'] = $cart;
?>
<table border="0" width="82%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <tr>
    <td class="pageHeading">
	<!-- < ?php echo HEADING_TITLE; ?></td> -->
    
  </tr>
  <tr>
     <td class="main">

<!-- start -->
<table border="0" width="99%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="2%" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

    </table></td>
<!-- body_text //-->
    <td width="96%" valign="top">
      <table border="0" width="99%" cellspacing="0" cellpadding="0">
<?php
          $url = tep_href_link(FILENAME_CHECKOUT_PAYMENT, $cartId, 'NONSSL', false);
          echo "<meta http-equiv='Refresh' content='10; Url=\"$url\"'>";
?>
          <tr>
            <td width="80%" height="194" colspan="2" align="center" class="pageHeading"><div align="left"></div>
              <div align="center"><img src="http://ideal.nl/img/100x100.gif" alt="IDEAL" width="100" height="100" hspace="30" /></div><br />
              <div align="right"></div>
              <?php echo WP_TEXT_FAILURE ?></td>
          </tr>
           <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '80%', '15'); ?></td>
          </tr> 
<?php 
	if (isset($_GET['errormsg']) && $_GET['errormsg'] != '') {
?>
          <tr>
            <td align="center"><table border="2" bordercolor="#FF0000" width="55%" cellspacing="0" cellpadding="2">
                
                
                  <td class="main" align="center"><p><?php echo WP_TEXT_HEADING; ?></p><br><br><p><?php echo $_GET['errormsg']; ?></p></td>
              </table>            </td>
          </tr>
<?php
}
?>                 
          <tr>
            <td class="pageHeading" width="80%" colspan="2" align="center"><br /><h3><?php echo WP_TEXT_FAILURE_WAIT; ?></h3></td>
          </tr>
          <tr align="center">
            <td><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', false) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '80%', '50'); ?></td>
          </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="2%" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
    </table></td>
  </tr>
</table>

<br>

<!-- stop -->
    </td>
  </tr>
</table>
<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>