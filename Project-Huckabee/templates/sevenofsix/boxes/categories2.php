<?php
/*
  $Id: categories2.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if ((defined('USE_CACHE') && USE_CACHE == 'true') && !defined('SID')) {
  echo tep_cache_categories_box2();
} else {
  ?>
  <!-- categories2 //-->

  <script>
  $(document).ready(function() {
    $('.box-manufacturers-select').addClass('form-input-width');
  });
  $('.box-manufacturers-selection').addClass('form-group full-width');
  $('.box-manufacturers-select').addClass('form-control');
</script>
     <div class="well"  style="text-transform:uppercase">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_CATEGORIES2 ; ?></div>

      <form role="form" class="form-inline no-margin-bottom"  action="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false)?>" method="get">
			  <?php
				  echo '<ul class="box-information_pages-ul list-unstyled list-indent-large"><li>' . tep_draw_pull_down_menu('cPath', tep_get_categories(array(array('id' => '', 'text' => PULL_DOWN_DEFAULT))), $cPath, 'onchange="this.form.submit();"class="box-manufacturers-select form-control form-input-width"') .'<li></ul>';

			  ?>

     </form>
</div>
  <!-- categories2_eof //-->
  <?php
}
?>