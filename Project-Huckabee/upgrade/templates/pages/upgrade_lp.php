<?php
  /*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

  Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

  Released under the GNU General Public License
  */
  
  require('includes/languages/' . $language . '/upgrade_lp.php');

  include('../includes/configure.php');
?>

  <h1 align="center"><?php echo TEXT_INSTALL_1; ?></h1>
  <?php echo TEXT_INSTALL_3; ?>
  <?php echo TEXT_INSTALL_4; ?>
  <table width="300" align="center">
    <tr>
      <td align="center">
        <form name="upgrade_lp" action="upgrade.php" method="post">
          <input type="hidden" name="step" value="8">
          <a href="javascript:void(0)" onclick="document.upgrade_lp.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo TEXT_INSTALL_2 ;?></span><span class="installation-button-right">&nbsp;</span></a>
        </form>
      </td>
    </tr>
  </table>