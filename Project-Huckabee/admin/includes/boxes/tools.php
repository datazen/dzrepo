<?php
/*
  $Id: tools.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_TOOLS,
                   'link'  => tep_href_link(FILENAME_BACKUP_MYSQL, 'selected_box=tools'));

  //RCI to include links
  $returned_rci_top = $cre_RCI->get('tools', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('tools', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_BACKUP_MYSQL, BOX_TOOLS_MYSQL_BACKUP, 'SSL','selected_box=tools&section=mysql_backup','2') .
                                 tep_admin_files_boxes(FILENAME_BACKUP,BOX_TOOLS_BACKUP, 'SSL','selected_box=tools&section=backup','2') .
                                 tep_admin_files_boxes(FILENAME_CACHE, BOX_TOOLS_CACHE, 'SSL','selected_box=tools&section=cache','2') .
                                 tep_admin_files_boxes(FILENAME_EDIT_LANGUAGES, BOX_TOOLS_DEFINE_LANGUAGE, 'SSL','selected_box=tools&section=define_language','2') .
                                 tep_admin_files_boxes(FILENAME_EMAIL_SUBJECTS, BOX_TOOLS_EMAIL_SUBJECTS, 'SSL','selected_box=tools&section=email_subjects','2') .
                                 tep_admin_files_boxes(FILENAME_MAIL, BOX_TOOLS_MAIL, 'SSL','selected_box=tools&section=mail_tools','2') .
                                 tep_admin_files_boxes(FILENAME_SERVER_INFO, BOX_TOOLS_SERVER_INFO, 'SSL','selected_box=tools&section=server_info','2') . 
                                 tep_admin_files_boxes(FILENAME_WHOS_ONLINE, BOX_TOOLS_WHOS_ONLINE, 'SSL','selected_box=tools&section=whos_online','2') .
                                 $returned_rci_bottom);

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
<!-- tools_eof //-->