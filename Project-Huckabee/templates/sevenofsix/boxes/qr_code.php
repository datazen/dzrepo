<?php
/*
  $Id: qr_code.php 1739 2011-11-02 23:36:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


?>
<!-- QR Codes //-->
<div class="well" >
	<div class="box-header small-margin-bottom small-margin-left">QR CODE FOR THIS PAGE</div>
	<div style="text-align:center">
	<?php
	echo  '<img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '" width="100" height="100"><br>';
	?>
    </div>
</div>

<!-- QR Codes //-->
  <?php

?>