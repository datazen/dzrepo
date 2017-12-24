<?php
/*
  $Id: calendar.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (DOWN_FOR_MAINTENANCE != 'true') { // the iframe willnot work correctly if the site is down for maintenance
  ?>
  <!-- events_calendar //-->
     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_CALENDAR ; ?></div>
      <?php
      $_month = isset($_GET['_month']) ? (int)$_GET['_month'] : date('n');
      $_year = isset($_GET['_year']) ? (iNT)$_GET['_year'] : date('Y');
      	echo '<iframe name="calendar" id="calendar" align="middle" marginwidth="0" marginheight="0" src="'  . FILENAME_EVENTS_CALENDAR_CONTENT . '?_month=' . $_month .'&amp;_year='. $_year .'" frameborder="0" height="220" width="162" scrolling="no"> ' .IFRAME_ERROR.'</iframe> ';
      ?>

	 </div>
  <!-- events_calendar eof//-->
  <?php
  }
?>