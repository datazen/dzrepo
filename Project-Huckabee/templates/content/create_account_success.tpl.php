<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('createaccountsuccess', 'top');
// RCI code eof
?>
<!--content/account/create_success.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
    <div class="well">
                  <?php
                  if ((defined(ACCOUNT_EMAIL_CONFIRMATION)) && (ACCOUNT_EMAIL_CONFIRMATION == 'true')) {
                    echo TEXT_ACCOUNT_CREATED_NEEDS_VALIDATE;
                  } else {
                    echo TEXT_ACCOUNT_CREATED_NO_VALIDATE;
                  }
                  ?>
      <?php
      // RCI code start
      echo $cre_RCI->get('createaccountsuccess', 'menu');
      // RCI code eof ?>
</div>
    <div class="btn-set small-margin-top clearfix">
      <form action="<?php echo $origin_href ; ?>" method="post"><button   class="pull-right btn btn-lg btn-primary" type="submit"><?php echo IMAGE_BUTTON_CONTINUE; ?></button></form>
    </div>

<?php
// RCI code start
echo $cre_RCI->get('createaccountsuccess', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>

</div>
</div>