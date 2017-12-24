<?php
/*
  $Id: asearch.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- asearch //-->
  <script>
  $(document).ready(function() {
    $('.box-manufacturers-select').addClass('form-input-width');
  });
  $('.box-manufacturers-selection').addClass('form-group full-width');
  $('.box-manufacturers-select').addClass('form-control');
</script>
     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_ASEARCH ; ?></div>
		<?php
		$hide = tep_hide_session_id();
		?>
		 <form role="form" class="form-inline no-margin-bottom" name="quick_find_article" action="<?php echo tep_href_link(FILENAME_ARTICLE_SEARCH, '', 'NONSSL', false) ?>" method="get">
		 <?php
			  echo $hide . '<input class="form-control" type="text" name="keywords" size="10" maxlength="30" value="' . htmlspecialchars(StripSlashes(@$_GET["akeywords"])) . '">&nbsp;
			  <div class="buttons-set clearfix large-margin-top">
    			   <input type="submit" value="Search" class="btn btn-sm cursor-pointer small-margin-right btn-success">
			 </div> <br><div style="text-align:center"><input type="checkbox" name="description">' . BOX_ASEARCH_TEXT . '<br></a></div>';
		 ?>

     </form>
    </div>
<!-- asearch eof//-->