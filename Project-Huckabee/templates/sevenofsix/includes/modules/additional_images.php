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
<?php
    echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_1'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';



/* echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"class="img-responsive"'); */?>
?>
</div>
</div>
<?php
}
elseif (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_sm_1'] != '')) {
?>
<div class="slider-item">
<div class="product-block">
<?php
    echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_1'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
?>
</div>
</div>
<?php
} elseif
(($products_info['products_image_sm_1'] == '') && ($product_info['products_image_xl_1'] != '')) {
?>
<div class="slider-item">
<div class="product-block">
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_1'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_1'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>

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
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_2'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_2'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>
</div>
</div>
<?php
} elseif
(($product_info['products_image_sm_2'] != '') && ($product_info['products_image_sm_2'] != '')) {
?>
<div class="slider-item">
<div class="product-block">
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_2'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>
</div>
</div>
<?php
} elseif
(($products_info['products_image_sm_2'] == '') && ($product_info['products_image_xl_2'] != '')) {
?>
<div class="slider-item">
<div class="product-block">
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_2'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_2'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>

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
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_sm_3'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>
</div>
</div>
<?php
} elseif
(($product_info['products_image_sm_3'] != '') && ($product_info['products_image_sm_3'] != '')) {
?>
<div class="slider-item">
<div class="8 product-block "  >
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_3'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>

</div>
</div>
<?php
} elseif
(($products_info['products_image_sm_3'] == '') && ($product_info['products_image_xl_3'] != '')) {
?>
<div class="slider-item">
<div class="9 product-block "  >
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_3'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_3'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>

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
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_sm_4'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
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
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_4'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
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
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_4'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_4'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
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
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_5'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_5'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>

</div>
</div>
<?php
} elseif
(($product_info['products_image_sm_5'] != '') && ($product_info['products_image_sm_5'] != '')) {
?>
<div class="slider-item">
<div class="14 product-block"  >
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_5'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
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
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_5'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_5'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
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
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>

</div>
<?php
} elseif
(($product_info['products_image_sm_6'] != '') && ($product_info['products_image_sm_6'] != '')) {
?>
<div class="slider-item">
<div class="17 product-block"  >
  <?php
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
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
      echo '<a rel="'.$product_info['products_name'] .'" class="thumbnail elevatezoom-gallery fancybox" title="'.$product_info['products_name'].'" href="'.DIR_WS_IMAGES . $product_info['products_image_xl_6'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], '', '','class="img-responsive"').'</a>';
  ?>
</div>
</div>
<?php
}
?>

<?php
} // end of initial IF
?>