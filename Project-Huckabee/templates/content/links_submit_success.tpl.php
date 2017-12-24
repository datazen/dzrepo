<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('linkssubmitsuccess', 'top');
// RCI code eof
?>
<div class="row">
  <div class="col-sm-12 col-lg-12">
       <h1><?php echo HEADING_TITLE; ?></h1>
		<div class="well no-padding-top">
			<h1></h1>
            <p> <?php echo TEXT_LINK_SUBMITTED; ?></p>
        </div>

      <?php
      // RCI code start
      echo $cre_RCI->get('linkssubmitsuccess', 'menu');
      // RCI code eof
      ?>

    <div class="btn-set small-margin-top clearfix">
		<?php echo '<a href="' . $origin_href . '"><button class="pull-right btn btn-lg btn-primary">' .  IMAGE_BUTTON_CONTINUE . '</button></a>'; ?>
   </div>

 </div>
</div>
<?php
// RCI code start
echo $cre_RCI->get('linkssubmitsuccess', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>