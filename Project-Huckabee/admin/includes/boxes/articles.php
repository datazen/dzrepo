<?php
/*
  $Id: articles.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- articles //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_ARTICLES,
                   'link'  => tep_href_link(FILENAME_ARTICLES, 'selected_box=articles'));

  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('articles', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('articles', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 // NOTE: keep section= as last parameter   
                                 tep_admin_files_boxes(FILENAME_ARTICLES,  BOX_TOPICS_ARTICLES, 'SSL','selected_box=articles&section=articles','2')  .
                                 tep_admin_files_boxes(FILENAME_ARTICLES_CONFIG,  BOX_ARTICLES_CONFIG, 'SSL','selected_box=articles&section=articles_config','2')  .
                                 tep_admin_files_boxes(FILENAME_AUTHORS, BOX_ARTICLES_AUTHORS, 'SSL','selected_box=articles&section=authors','2')  .
                                 tep_admin_files_boxes(FILENAME_ARTICLE_REVIEWS,  BOX_ARTICLES_REVIEWS, 'SSL','selected_box=articles&section=article_reviews','2')  .
                                 tep_admin_files_boxes(FILENAME_ARTICLES_XSELL,  BOX_ARTICLES_XSELL, 'SSL','selected_box=articles&section=articles_xsell','2') .
                                 $returned_rci_bottom);

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
<!-- articles_eof //-->