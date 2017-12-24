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
<tr>
<td><table width="100%">
<tr>

<?php
if (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_xl_1'] == '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
} elseif
(($product_info['products_image_sm_1'] != '') && ($product_info['products_image_sm_1'] != '')) {
?>
<td align="center" class="smallText">

<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_1']) . '" rel="prettyPhoto[Product]">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
</td>
<?php
} elseif
(($products_info['products_image_sm_1'] == '') && ($product_info['products_image_xl_1'] != '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_1'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
}
?>
<?php
if (($product_info['products_image_sm_2'] != '') && ($product_info['products_image_xl_2'] == '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
} elseif
(($product_info['products_image_sm_2'] != '') && ($product_info['products_image_sm_2'] != '')) {
?>
<td align="center" class="smallText">
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_2']) . '" rel="prettyPhoto[Product]">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
</td>
<?php
} elseif
(($products_info['products_image_sm_2'] == '') && ($product_info['products_image_xl_2'] != '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_2'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
}
?>
</tr>
<tr>

<?php
if (($product_info['products_image_sm_3'] != '') && ($product_info['products_image_xl_3'] == '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
} elseif
(($product_info['products_image_sm_3'] != '') && ($product_info['products_image_sm_3'] != '')) {
?>
<td align="center" class="smallText">
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_3']) . '" rel="prettyPhoto[Product]">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
</td>
<?php
} elseif
(($products_info['products_image_sm_3'] == '') && ($product_info['products_image_xl_3'] != '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_3'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
}
?>
<?php
if (($product_info['products_image_sm_4'] != '') && ($product_info['products_image_xl_4'] == '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
} elseif
(($product_info['products_image_sm_4'] != '') && ($product_info['products_image_sm_4'] != '')) {
?>
<td align="center" class="smallText">
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_4']) . '" rel="prettyPhoto[Product]">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
</td>
<?php
} elseif
(($products_info['products_image_sm_4'] == '') && ($product_info['products_image_xl_4'] != '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_4'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
}
?>

</tr>
<tr>
<?php
if (($product_info['products_image_sm_5'] != '') && ($product_info['products_image_xl_5'] == '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
} elseif
(($product_info['products_image_sm_5'] != '') && ($product_info['products_image_sm_5'] != '')) {
?>
<td align="center" class="smallText">
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_5']) . '" rel="prettyPhoto[Product]">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
</td>
<?php
} elseif
(($products_info['products_image_sm_5'] == '') && ($product_info['products_image_xl_5'] != '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_5'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
}
?>
<?php
if (($product_info['products_image_sm_6'] != '') && ($product_info['products_image_xl_6'] == '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
} elseif
(($product_info['products_image_sm_6'] != '') && ($product_info['products_image_sm_6'] != '')) {
?>
<td align="center" class="smallText">
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_6']) . '" rel="prettyPhoto[Product]">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
</td>
<?php
} elseif
(($products_info['products_image_sm_6'] == '') && ($product_info['products_image_xl_6'] != '')) {
?>
<td align="center" class="smallText">
<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_6'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
</td>
<?php
}
?>


</tr>
</table></td>
</tr>
<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! //-->
<?php
} // end of initial IF
?>