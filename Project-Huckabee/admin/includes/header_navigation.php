<?php
/*
  $Id: header_navigation.php,v 1.1.1.1 2004/03/04 23:39:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

    Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2006 Chain Reaction Works, Inc.

  Last Modified by $Author$
  Last Modifed on : $Date$
  Latest Revision : $Revision: 4210 $

  Released under the GNU General Public License
*/

if (MENU_DHTML == True) {
  $box_files_list1a = array(array('administrator', 'administrator.php', BOX_HEADING_ADMINISTRATOR),
                            array('configuration', 'configuration.php', BOX_HEADING_CONFIGURATION),
                            array('catalog', 'catalog.php', BOX_HEADING_CATALOG),
                            array('customers', 'customers.php' , BOX_HEADING_CUSTOMERS)
                            );
  $box_files_list1b = array(array('marketing', 'marketing.php', BOX_HEADING_MARKETING),
                            array('gv_admin', 'gv_admin.php' , BOX_HEADING_GV_ADMIN),
                            array('reports', 'reports.php' , BOX_HEADING_REPORTS),
                            array('data', 'data.php' , BOX_HEADING_DATA)
                            );
  $box_files_list2a = array(array('information', 'information.php', BOX_HEADING_INFORMATION),
                            array('articles', 'articles.php' , BOX_HEADING_ARTICLES));

  $box_files_list2b = array(array('design_controls' , 'design_controls.php' , BOX_HEADING_DESIGN_CONTROLS),
                            array('links', 'links.php' , BOX_HEADING_LINKS),
                            array('modules', 'modules.php' , BOX_HEADING_MODULES),
                            array('taxes', 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES),
                            array('localization', 'localization.php' , BOX_HEADING_LOCALIZATION),
                            array('tools', 'tools.php', BOX_HEADING_TOOLS)
                            );

  // RCI start
  $returned_rci_first_menu = $cre_RCI->get('boxes', 'dhtmlmenufirst', false);
  $new = str_replace(ord(60), "", $returned_rci_first_menu);
  $box_files_rci_first_menu = array(explode(", ", $new));

  $returned_rci_second_menu = $cre_RCI->get('boxes', 'dhtmlmenusecond', false);
  $new = str_replace(ord(60), "", $returned_rci_second_menu);
  $box_files_rci_second_menu = array(explode(", ", $new));

  if ($returned_rci_first_menu == '') {
    $box_files_list1 = array_merge($box_files_list1a, $box_files_list1b);
  } else {
    $box_files_list1 = array_merge($box_files_list1a, $box_files_rci_first_menu, $box_files_list1b);
  }
  if ($returned_rci_second_menu  == '') {
    $box_files_list2 = array_merge($box_files_list2a, $box_files_list2b);
  } else {
    $box_files_list2 = array_merge($box_files_list2a, $box_files_rci_second_menu, $box_files_list2b);
  }

  // RCI eof

  echo '<!-- Menu bar #1. --> <div class="menuBar" style="width:100%;">';
  foreach($box_files_list1 as $item_menu) {
    if (tep_admin_check_boxes($item_menu[1]) == true) {
      echo "<a class=\"menuButton\" onmouseover=\"buttonMouseover(event, '".$item_menu[0]."Menu');\">".$item_menu[2]."</a>";
      require(DIR_WS_BOXES . $item_menu[1]);
    }
  }
  echo "</div>";
  
  echo '<!-- Menu bar #2. --> <div class="menuBar" style="width:100%;">';
  foreach($box_files_list2 as $item_menu) {
    if (tep_admin_check_boxes($item_menu[1]) == true) {
      echo "<a class=\"menuButton\" onmouseover=\"buttonMouseover(event, '".$item_menu[0]."Menu');\">".$item_menu[2]."</a>";
      require(DIR_WS_BOXES . $item_menu[1]);
    }
  }
  echo "</div>";
}
?>