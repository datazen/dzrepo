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


<?php
if (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_xl_1'] == '')) {
?>
<div class="slider-item">
<div class="product-block">
<?php					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_1'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

/* echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"class="img-responsive"'); */?>
</div>
</div>
<?php
}
elseif (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_sm_1'] != '')) {
?>
<div class="slider-item">
<div class="product-block">
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
</div>
</div>
<?php
} elseif
(($products_info['products_image_sm_1'] == '') && ($product_info['products_image_xl_1'] != '')) {
?>
<div class="slider-item">
<div class="product-block">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_1'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>

</div>
</div>
<?php
}
?>
<?php
if (($product_info['products_image_sm_2'] != '') && ($product_info['products_image_xl_2'] == '')) {
?>
<div class="slider-item">
<div class="product-block">
<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_2'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

/*echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"');*/ ?>
</div>
</div>
<?php
} elseif
(($product_info['products_image_sm_2'] != '') && ($product_info['products_image_sm_2'] != '')) {
?>
<div class="slider-item">
<div class="product-block">
<?php

					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_2'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';
?>
</div>
</div>
<?php
} elseif
(($products_info['products_image_sm_2'] == '') && ($product_info['products_image_xl_2'] != '')) {
?>
<div class="slider-item">
<div class="product-block">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_2'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>

</div>
</div>
<?php
}
?>
<?php
if (($product_info['products_image_sm_3'] != '') && ($product_info['products_image_xl_3'] == '')) {
?>
<div class="slider-item">
<div  class="7 product-block "  >
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</div>
</div>
<?php
} elseif
(($product_info['products_image_sm_3'] != '') && ($product_info['products_image_sm_3'] != '')) {
?>
<div class="slider-item">
<div class="8 product-block "  >
<?php

					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_3'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
				     . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
				    .'</a>';
?>

</div>
</div>
<?php
} elseif
(($products_info['products_image_sm_3'] == '') && ($product_info['products_image_xl_3'] != '')) {
?>
<div class="slider-item">
<div class="9 product-block "  >
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_3'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>

</div>
</div>
<?php
}
?>
<?php
if (($product_info['products_image_sm_4'] != '') && ($product_info['products_image_xl_4'] == '')) {
?>
<div class="slider-item">
<div class="10 product-block "  >
<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_4'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

?>
</div>
</div>
<?php
} elseif
(($product_info['products_image_sm_4'] != '') && ($product_info['products_image_sm_4'] != '')) {
?>
<div class="slider-item">
<div class="11 product-block "  >

<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_4'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

?>


</div>
</div>
<?php
} elseif
(($products_info['products_image_sm_4'] == '') && ($product_info['products_image_xl_4'] != '')) {
?>
<div class="slider-item">
<div class="12 product-block "  >
<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_4'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';

?>
</div>
</div>
<?php
}
?>
<?php
if (($product_info['products_image_sm_5'] != '') && ($product_info['products_image_xl_5'] == '')) {
?>
<div class="slider-item">
<div class="13 product-block "  >
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>


</div>
<?php
} elseif
(($product_info['products_image_sm_5'] != '') && ($product_info['products_image_sm_5'] != '')) {
?>
<div class="slider-item">
<div class="14 product-block"  >
<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_5'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';
?>
</div>
</div>
<?php
} elseif
(($products_info['products_image_sm_5'] == '') && ($product_info['products_image_xl_5'] != '')) {
?>
<div class="slider-item">
<div class="15 product-block"  >
<?php
					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_5'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';
?>
</div>
</div>
<?php
}
?>
<?php
if (($product_info['products_image_sm_6'] != '') && ($product_info['products_image_xl_6'] == '')) {
?>
<div class="slider-item">
<div class="16 product-block"  >
  <?php
      echo '<a data-zoom-image="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'" data-image="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'" class="thumbnail elevatezoom-gallery" title="Aliquam erat volutpat" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>

</div>
<?php
} elseif
(($product_info['products_image_sm_6'] != '') && ($product_info['products_image_sm_6'] != '')) {
?>
<div class="slider-item">
<div class="17 product-block"  >
<?php
 					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';
?>

</div>
</div>
<?php
} elseif

(($products_info['products_image_sm_6'] == '') && ($product_info['products_image_xl_6'] != '')) {
?>
<div class="slider-item">
<div class="18 product-block"  >
<?php
 					echo '<a href="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'" class="fancybox" rel="'.$product_info['products_name'] .'"  >'
					 . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT,'hspace="5" vspace="5" class="img-responsive"')
					.'</a>';
?>
</div>
</div>
<?php
}
?>

<?php
} // end of initial IF
?>