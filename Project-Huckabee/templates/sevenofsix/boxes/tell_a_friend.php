<?php
/*
  $Id: tell_a_friend.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (isset($_GET['products_id'])) {
  ?>
  <!-- tell_a_friend //-->
     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_TELL_A_FRIEND ; ?></div>
      <?php

			echo  tep_draw_input_field('to_email_address', '', 'class="form-control" size="10" placeholder="Email-Address"') . '&nbsp; <div class="buttons-set clearfix large-margin-top">
    			                       <input type="submit" value="Tell A Friend" class="btn btn-sm cursor-pointer small-margin-right btn-success">
			                            </div>'. tep_draw_hidden_field('products_id', (int)$_GET['products_id']) . tep_hide_session_id() . '<div style="text-align:center">' . BOX_TELL_A_FRIEND_TEXT .'</div>' ;
      ?>

	 </div>

  <!-- tell_a_friend eof//-->
  <?php
}
?>