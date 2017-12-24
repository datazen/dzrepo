<?php
/*
  $Id: googlead.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
//if (!(getenv('HTTPS')=='on')){
  if ($banner = tep_banner_exists('dynamic', 'googlebox')) {
    ?>
    <!-- googlead //-->
     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_GOOGLE_AD_BANNER_HEADING ; ?></div>
		<?php echo $bannerstring;?>
    </div>
    <?php
  }
//}
?>
<!-- googlead eof//-->