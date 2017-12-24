<?php
/*
  $Id: articles.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$articles = new box_articles();
?>
<!-- articles //-->
  <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo BOX_HEADING_ARTICLES; ?></div>

    <?php
    echo  $articles->new_articles_string . $articles->all_articles_string . $articles->topics_string;
?>
</div>
<!-- articles_eof //-->