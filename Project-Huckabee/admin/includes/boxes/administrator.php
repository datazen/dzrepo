<?php
/*
  $Id: administrator.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- administrator //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_ADMINISTRATOR,
                   'link'  => tep_href_link(FILENAME_ADMIN_MEMBERS, tep_get_all_get_params(array('selected_box')) . 'selected_box=administrator'));

  //RCI to include links 
  $returned_rci_top = $cre_RCI->get('administrator', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('administrator', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .                                                               
                                 // NOTE: keep section= as last parameter
                                 tep_admin_files_boxes(FILENAME_ADMIN_MEMBERS, BOX_ADMINISTRATOR_MEMBERS, 'NONSSL','selected_box=administrator&section=admin_members','2') .
                                 tep_admin_files_boxes(FILENAME_ADMIN_MEMBERS, BOX_ADMINISTRATOR_GROUPS,'NONSSL','gID=groups&selected_box=administrator&section=admin_groups','2') .
                                 tep_admin_files_boxes(FILENAME_ADMIN_ACCOUNT, BOX_ADMINISTRATOR_ACCOUNT_UPDATE, 'NONSSL','selected_box=administrator&section=update_account','2') .
                                 tep_admin_files_boxes(FILENAME_ADMIN_FILES, BOX_ADMINISTRATOR_BOXES, 'NONSSL','cID=1&selected_box=administrator&section=menu_file_access','2') .
                                 $returned_rci_bottom);

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
<!-- administrator_eof //-->