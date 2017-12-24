<?php
/*
  $Id: information.php,v 2.0 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information [modified for CDS]//-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_CDS_HEADING,
                   'link'  => tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'selected_box=information'));
if (defined('PROJECT_VERSION') && preg_match('/6.2/', PROJECT_VERSION)) {
  
    //RCI start
    $returned_rci_top = $cre_RCI->get('information', 'boxestop');
    $returned_rci_bottom = $cre_RCI->get('information', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_top . 
                                   tep_admin_files_boxes(FILENAME_CDS_PAGE_MANAGER, BOX_CDS_PAGE_MANAGER, 'SSL', 'selected_box=information&section=cds_page_manager', '0') . 
                                   tep_admin_files_boxes(FILENAME_CDS_CONFIGURATION, BOX_CDS_CONFIGUARION, 'SSL', 'selected_box=information&gID=480&section=cds_configuration', '0') .
                                   tep_admin_files_boxes(FILENAME_CDS_BACKUP_RESTORE, BOX_CDS_BACKUP_RESTORE, 'SSL' , 'selected_box=information&section=cds_backup_restore', '0') .
                                   tep_admin_files_boxes('', BOX_CDS_FAQ) .
                                   tep_admin_files_boxes(FILENAME_FAQ_MANAGER, BOX_FAQ_MANAGER, 'SSL' , 'selected_box=information&section=faq_manager', '2') .
                                   tep_admin_files_boxes(FILENAME_FAQ_CATEGORIES, BOX_FAQ_CATEGORIES, 'SSL' , 'selected_box=information&section=faq_categories', '2') .
                                   tep_admin_files_boxes(FILENAME_DEFINE_MAINPAGE, BOX_CATALOG_DEFINE_MAINPAGE, 'SSL', 'selected_box=information&section=define_mainpage', '0') .
                                   tep_admin_files_boxes(FILENAME_INFORMATION_MANAGER, BOX_INFORMATION_MANAGER, 'SSL', 'selected_box=information&section=information_manager', '0')  .
                                   $returned_rci_bottom);
    //RCI eof
      
} else {
    //RCI start
    $returned_rci_top = $cre_RCI->get('information', 'boxestop');
    $returned_rci_bottom = $cre_RCI->get('information', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_top . 
                                   tep_admin_files_boxes(FILENAME_CDS_PAGE_MANAGER, BOX_CDS_PAGE_MANAGER, 'SSL', 'selected_box=information', '2') . 
                                   tep_admin_files_boxes(FILENAME_CDS_CONFIGURATION, BOX_CDS_CONFIGUARION, 'SSL', 'selected_box=information&gID=480', '2') .
                                   tep_admin_files_boxes(FILENAME_CDS_BACKUP_RESTORE, BOX_CDS_BACKUP_RESTORE, 'SSL' , 'selected_box=information', '2') .
                                   tep_admin_files_boxes('', '&nbsp;&nbsp;' . BOX_CDS_FAQ) .
                                   tep_admin_files_boxes(FILENAME_FAQ_MANAGER, BOX_FAQ_MANAGER, 'SSL' , 'selected_box=information', '4') .
                                   tep_admin_files_boxes(FILENAME_FAQ_CATEGORIES, BOX_FAQ_CATEGORIES, 'SSL' , 'selected_box=information', '4') .
                                   tep_admin_files_boxes(FILENAME_DEFINE_MAINPAGE, BOX_CATALOG_DEFINE_MAINPAGE, 'SSL', 'selected_box=information', '2') .
                                   $returned_rci_bottom);
    //RCI eof
  } 
$box = new box;
echo $box->menuBox($heading, $contents);
?>
<!-- information_eof [modified for CDS]//-->