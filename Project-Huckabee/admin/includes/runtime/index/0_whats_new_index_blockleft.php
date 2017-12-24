<?php
/*
  $Id: 0_whats_new_index_blockleft.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_WHATS_NEW') && ADMIN_BLOCKS_WHATS_NEW == 'true'){
  ?>
  <!-- begin whats new -->
  <div class="col-md-6">
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="table-basic-4">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title"><?php cre_index_block_title(BLOCK_TITLE_WHATS_NEW); ?></h4>
      </div>
      <div class="panel-body">
        <script language='JavaScript' type='text/javascript' src='https://adserver.authsecure.com/adx.js'></script>
        <script language='JavaScript' type='text/javascript'>
        <!--
        if (!document.phpAds_used) document.phpAds_used = ',';
        phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
        document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
        document.write ("https://adserver.authsecure.com/adjs.php?n=" + phpAds_random);
        document.write ("&amp;what=zone:110");
        document.write ("&amp;exclude=" + document.phpAds_used);
        if (document.referrer)
          document.write ("&amp;referer=" + escape(document.referrer));
        document.write ("'><" + "/script>");
        //-->
        </script><noscript><a href='https://adserver.authsecure.com/adclick.php?n=a0fe9c07' target='_blank'><img src='https://adserver.authsecure.com/adview.php?what=zone:110&amp;n=a0fe9c07' border='0' alt=''></a></noscript>
        <a href="<?php echo tep_href_link(REMOVE_WHATS_NEW_LINK, 'gID=23&selected_box=configuration', 'SSL'); ?>" style="font-size:smaller"> Click to remove this block</a>  

      </div>
    </div>
  </div>
  <!-- end whats new -->
  <?php
}
?>