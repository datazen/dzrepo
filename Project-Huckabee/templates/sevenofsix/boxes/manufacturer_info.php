<?php
/*
  $Id: manufacturer_info.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$info = new box_manufacturer_info();
if (count($info->rows) > 0) {
  foreach ($info->rows as $manufacturer) {
?>
    <!-- manufacturer_info //-->

       <div class="well">
        <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_MANUFACTURER_INFO ; ?></div>

        <?php
        $manufacturer_info_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
        if (tep_not_null($manufacturer['manufacturers_image'])) $manufacturer_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2">' . tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name']) . '</td></tr>';
        if (tep_not_null($manufacturer['manufacturers_url'])) $manufacturer_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&amp;manufacturers_id=' . $manufacturer['manufacturers_id']) . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a></td></tr>';
        $manufacturer_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manf_id']) . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . ' ' . $manufacturer['manufacturers_name'] . '</a></td></tr></table>';  
        echo $manufacturer_info_string;
        ?>
	  </div>
    <!-- manufacturer_info eof//-->
    <?php
  }
}
?>