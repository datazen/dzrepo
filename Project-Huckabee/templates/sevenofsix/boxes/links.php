<?php
/*
  $Id: links.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// check for link categoris to determine if there is anything to display
$link_categories_query = tep_db_query("select lc.link_categories_id, lcd.link_categories_name from " . TABLE_LINK_CATEGORIES . " lc, " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where lc.link_categories_id = lcd.link_categories_id and lc.link_categories_status = '1' and lcd.language_id = '" . (int)$languages_id . "' order by lcd.link_categories_name");
$number_of_categories = tep_db_num_rows($link_categories_query);
if ($number_of_categories > 0) {
  ?>
  <!-- links -->
   <div class="well"  style="text-transform:uppercase">
    <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_LINKS ; ?></div>
        <?php
              while($row = tep_db_fetch_array($link_categories_query)) {
		        $lPath_new = 'lPath=' . $row['link_categories_id'];
		        $informationString .= '<a href="' . tep_href_link(FILENAME_LINKS, $lPath_new) . '">' . $row['link_categories_name'] . '</a><br>';
		      }

        echo '<ul class="box-information_pages-ul list-unstyled list-indent-large"><li>' . $informationString . '<li></ul>';?>
    </div>
<!-- links eof//-->
  <?php
}
?>
