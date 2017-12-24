<?php
/*
  $Id: 1_easy_path_index_blockleft.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_EASY_PATH') && ADMIN_BLOCKS_EASY_PATH == 'true'){
  ?>
  <!-- begin easypath -->
  <div class="col-md-6">
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="table-basic-4">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="<?php echo tep_href_link(REMOVE_EASY_PATH_LINK, 'gID=23&selected_box=configuration&cID=10013', 'SSL'); ?>" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title"><?php cre_index_block_title(BLOCK_TITLE_EASY_PATH); ?></h4>
      </div>
      <div class="panel-body">


      </div>
    </div>
  </div>
  <!-- end easypath -->
  <?php
}
?>