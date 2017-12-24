<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('addressbook', 'top');
// RCI code eof
?>
<div class="row">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <div class="col-sm-12 col-lg-12">
	<h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

<?php
  if ($messageStack->size('addressbook') > 0) {
?>
<div class="message-success-container alert alert-success">
   <?php echo $messageStack->output('addressbook'); ?></div>
<?php
  }
?>
    <div class="row">
      <div class="col-sm-6 col-lg-6">
        <h3 class="small-margin-top"><?php echo PRIMARY_ADDRESS_TITLE; ?></h3>
        <div class="well">
          <address class="small-margin-bottom small-margin-top"><?php echo tep_address_label($_SESSION['customer_id'], (isset($_SESSION['customer_default_address_id']) ? (int)$_SESSION['customer_default_address_id'] : 0), true, ' ', '<br>'); ?></address>
        </div>
      </div>
      <div class="col-sm-6 col-lg-6">
        <h3 class="small-margin-top">&nbsp;</h3>
        <div class="well">
          <p> <?php echo PRIMARY_ADDRESS_DESCRIPTION; ?></p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 col-lg-12">
        <h3 class="no-margin-top"><?php echo ADDRESS_BOOK_TITLE; ?></h3>

<?php
  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' order by firstname, lastname");
  while ($addresses = tep_db_fetch_array($addresses_query)) {
    $format_id = tep_get_address_format_id($addresses['country_id']);
?>
 <div class="well relative"><address class="no-margin-bottom" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL'); ?>'">
<?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></b><?php if (isset($_SESSION['customer_default_address_id']) && $addresses['address_book_id'] == $_SESSION['customer_default_address_id'] ) echo '&nbsp;<small class="margin-left"><i>' . PRIMARY_ADDRESS . '</i></small>'; ?>
</address>
<div class="btn-group clearfix absolute-top-right-large-padding">
            <a href ="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS,  'edit='. $addresses['address_book_id'], 'SSL'); ?>" class="display-inline" method="post"><button  type="button" class="btn btn-default btn-xs">Edit</button></a>
            <a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS,  'delete='. $addresses['address_book_id'], 'SSL'); ?>" class="display-inline" method="post"><button  type="button" class="btn btn-default btn-xs"><?php echo SMALL_IMAGE_BUTTON_DELETE;?></button></a>


</div><?php echo tep_address_format($format_id, $addresses, true, ' ', '<br>'); ?>


</div>
<?php
  }
?>

			</div>
           </div>
          </div>
<?php
// RCI code start
echo $cre_RCI->get('addressbook', 'menu');
// RCI code eof
?>
    <div class="row">
      <div class="col-sm-12 col-lg-12">
        <div class="btn-set small-margin-top clearfix">
    <h5 class="small-margin-top">  <?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></h5>
				<a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL') ?>"><button class="pull-left btn btn-lg btn-default" type="button"><?php echo IMAGE_BUTTON_BACK; ?></button></a>

<?php
  if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
?>
				<a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') ?>"><button class="pull-right btn btn-lg btn-primary" type="button"><?php echo IMAGE_BUTTON_ADD_ADDRESS; ?></button></a>
<?php
  }
?>


      </div>
     </div>
   </div>

    </div>
    <?php
    // RCI code start
    echo $cre_RCI->get('addressbook', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>