<?php
//check to see if there is actually anything to be done here
if ( ($product_info['products_image_sm_1'] != '') || ($product_info['products_image_xl_1'] != '') ||
     ($product_info['products_image_sm_2'] != '') || ($product_info['products_image_xl_2'] != '') ||
     ($product_info['products_image_sm_3'] != '') || ($product_info['products_image_xl_3'] != '') ||
     ($product_info['products_image_sm_4'] != '') || ($product_info['products_image_xl_4'] != '') ||
     ($product_info['products_image_sm_5'] != '') || ($product_info['products_image_xl_5'] != '') ||
     ($product_info['products_image_sm_6'] != '') || ($product_info['products_image_xl_6'] != '') ) {
?>
<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! //-->
<div style="text-align:center">
<ul style="list-style:none">
<?php
if (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_xl_1'] == '')) {
?>
<li class="1 add_img_li" style="margin-bottom: 5px;">
<?php					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_1'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

/* echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"class="img-responsive"'); */?>
</li>
<?php
}
elseif (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_sm_1'] != '')) {
?>
<li class="add_img_li" style="margin-bottom: 5px;">
<?php

					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_1'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

                   /* echo '<a data-toggle="modal" href="#popup-image-modal1" class="">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="5" vspace="5"class="img-responsive"') . '</a><br><div class="modal fade" id="popup-image-modal1">
							  <div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">'.$product_info['products_name'] .'</h4>
								  </div>
								  <div class="modal-body">'. tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_1'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="5" vspace="5"class="img-responsive"') .'
								  </div>
								  <div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">close</button>
								  </div>
								</div>
							  </div>
							</div>
						';
						*/



/*echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_1']) . '" rel="prettyPhoto[Product]">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], $image_width, $image_height, 'hspace="5" vspace="5"class="img-responsive"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>';*/ ?>
</li>
<?php
} elseif
(($products_info['products_image_sm_1'] == '') && ($product_info['products_image_xl_1'] != '')) {
?>
<li class="2 add_img_li">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_1'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
}
?>
<?php
if (($product_info['products_image_sm_2'] != '') && ($product_info['products_image_xl_2'] == '')) {
?>
<li class="12 add_img_li" style="margin-bottom: 5px;" >
<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_2'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

/*echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"');*/ ?>
</li>
<?php
} elseif
(($product_info['products_image_sm_2'] != '') && ($product_info['products_image_sm_2'] != '')) {
?>
<li class="add_img_li" style="margin-bottom: 5px;">
<?php

					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_2'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';
?>
</li>
<?php
} elseif
(($products_info['products_image_sm_2'] == '') && ($product_info['products_image_xl_2'] != '')) {
?>
<li class="3 add_img_li" style="margin-bottom: 5px;">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_2'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
}
?>
<?php
if (($product_info['products_image_sm_3'] != '') && ($product_info['products_image_xl_3'] == '')) {
?>
<li  class="4 add_img_li" style="margin-bottom: 5px;">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
} elseif
(($product_info['products_image_sm_3'] != '') && ($product_info['products_image_sm_3'] != '')) {
?>
<li class="add_img_li" style="margin-bottom: 5px;">
<?php

					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_3'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
				     . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
				    .'</a>';
?>
</li>
<?php
} elseif
(($products_info['products_image_sm_3'] == '') && ($product_info['products_image_xl_3'] != '')) {
?>
<li class="5 add_img_li" style="margin-bottom: 5px;">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_3'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
}
?>
</tr>
<tr>
<?php
if (($product_info['products_image_sm_4'] != '') && ($product_info['products_image_xl_4'] == '')) {
?>
<li class="9 add_img_li" style="margin-bottom: 5px;">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
} elseif
(($product_info['products_image_sm_4'] != '') && ($product_info['products_image_sm_4'] != '')) {
?>
<li class="add_img_li" style="margin-bottom: 5px;">
<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_4'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

?>
</li>
<?php
} elseif
(($products_info['products_image_sm_4'] == '') && ($product_info['products_image_xl_4'] != '')) {
?>
<li class="6 add_img_li" style="margin-bottom: 5px;">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_4'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
}
?>
<?php
if (($product_info['products_image_sm_5'] != '') && ($product_info['products_image_xl_5'] == '')) {
?>
<li class="10 add_img_li" style="margin-bottom: 5px;">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
} elseif
(($product_info['products_image_sm_5'] != '') && ($product_info['products_image_sm_5'] != '')) {
?>
<li class="add_img_li" style="margin-bottom: 5px;">
<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_5'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';
?>
</li>
<?php
} elseif
(($products_info['products_image_sm_5'] == '') && ($product_info['products_image_xl_5'] != '')) {
?>
<li class="7 add_img_li" style="margin-bottom: 5px;">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_5'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
}
?>
<?php
if (($product_info['products_image_sm_6'] != '') && ($product_info['products_image_xl_6'] == '')) {
?>
<li class="11 add_img_li" style="margin-bottom: 5px;">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
} elseif
(($product_info['products_image_sm_6'] != '') && ($product_info['products_image_sm_6'] != '')) {
?>
<li class="add_img_li" style="margin-bottom: 5px;">
<?php
 					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';
?>
</li>
<?php
} elseif

(($products_info['products_image_sm_6'] == '') && ($product_info['products_image_xl_6'] != '')) {
?>
<li class="8 add_img_li" style="margin-bottom: 5px;" >
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_6'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</li>
<?php
}
?>
</ul>
</div>
<div class="clearfix"></div>
<!--<div class="col-sm-12 col-lg-12 small-padding-bottom" style="color:#ff0000;font-family:roboto;font-size:12px;">*Click To Enlarge the images</div>
<div class="clearfix"></div>-->

<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! //-->
<?php
} // end of initial IF
?>