<?php
  /*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

  Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

  Released under the GNU General Public License
  */
  
  require('includes/languages/' . $language . '/upgrade_8.php');

  include('../includes/configure.php');
?>

  <h1 align="center"><?php echo TEXT_INSTALL_1; ?></h1>
  <?php
    echo TEXT_INSTALL_3;
    echo TEXT_INSTALL_4;
  ?>
  <table width="300" align="center">
    <tr>
      <td align="center"><a href="<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'index.php'; ?>" target="_blank"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo TEXT_INSTALL_5 ;?></span><span class="installation-button-right">&nbsp;</span></a></td>
      <td align="center"><a href="<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'admin/index.php'; ?>" target="_blank"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo TEXT_INSTALL_6 ;?></span><span class="installation-button-right">&nbsp;</span></a>
    </tr>
  </table>