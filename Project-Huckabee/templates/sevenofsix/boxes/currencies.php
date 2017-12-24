<?php
/*
  $Id: currencies.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (isset($currencies) && is_object($currencies)) {
  ?>
  <!-- currencies //-->
     <div class="well"  style="text-transform:uppercase">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_CURRENCIES ; ?></div>

      <form role="form" class="form-inline no-margin-bottom" name="currencies" action="<?php echo  tep_href_link(basename($PHP_SELF), '', $request_type, false) ?>" method="get">
      <?php
      while (list($key, $value) = each($currencies->currencies)) {
        $currencies_array[] = array('id' => $key, 'text' => $value['title']);
      }
      $hidden_get_variables = '';
      reset($_GET);
      while (list($key, $value) = each($_GET)) {
        if ( ($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y') ) {
          $hidden_get_variables .= tep_draw_hidden_field($key, $value);
        }
      }
				  echo '<ul class="box-information_pages-ul list-unstyled list-indent-large"><li>' . tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onChange="this.form.submit();" class="box-manufacturers-select form-control form-input-width style="width: 80%"') . $hidden_get_variables . tep_hide_session_id() .'<li></ul>';
      ?>

     </form>
</div>



  <tr>
    <td>
    </td>
  </tr>
  <!-- currencies eof//-->
  <?php
}
?>