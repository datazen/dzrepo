<style>
.toolbar { 
  position:absolute; 
  right:32px; 
  top:25px; 
  z-index:1000; 
}
@media (max-width: 767px) {
  .toolbar {
    position: relative;
    display:block;
    right:0;
    top:0;
    margin: 0 5px 10px 5px;
  }
  .page-header {
    margin: 0 0 10px;
  }
}
.popover-body {
  padding:0;
}

.input-search {
  width:74% !important;
}
</style>
<div class="toolbar text-right">

  <!-- begin breadcrumb -->
  <ol class="breadcrumb pull-right">
    <li><span class="hidden-xs">Create &nbsp; </span><a title="<?php echo BOX_MANUAL_ORDER_CREATE_ACCOUNT;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL');?>" class="btn btn-xs btn-inverse"><i class="fa fa-user"></i><span class="label">+</span></a> <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ORDER;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ORDER,'','SSL');?>" class="btn btn-xs btn-inverse"><i class="fa fa-shopping-cart"></i><span class="label">+</span></a></li>
    <li><span class="hidden-xs">Search &nbsp; </span><a href="javascript:;" class="btn btn-inverse btn-xs header-popover" id="ProductsPopover">Products</a> <a href="javascript:;" class="btn btn-inverse btn-xs header-popover" id="CustomerPopover">Cust<span class="hidden-xs">omers</span></a> <a href="javascript:;" class="btn btn-inverse btn-xs header-popover" id="OrdersPopover">Orders</a> <a href="javascript:;" class="btn btn-inverse btn-xs header-popover hidden-xs" id="PagesPopover">Pages</a></li>
    <li>
      <div class="btn-group theme-switch">
        <button onclick="changeTheme('dark');" class="btn btn-inverse btn-xs button-dark" title="Yea give me the dark side!"><i class="fa fa-moon-o"></i></button>
        <button onclick="changeTheme('light');" class="btn btn-inverse btn-xs button-light" title="I'm afraid of the dark!"><i class="fa fa-sun-o"></i></button>
      </div>
    </li>
  </ol>

  <div id="popoverProductsSearch" class="hide">
    <form role="form" id="ProductSearch" method="POST" action="<?php echo tep_href_link(FILENAME_CATEGORIES); ?>">
      <div class="input-group">
        <input type="text" class="form-control input-search" id="search" name="search" placeholder="Search Products">
        <button type="submit" class="btn btn-success input-group-append"><?php echo strtoupper(TEXT_GO); ?></button> 
      </div>
    </form>    
  </div>

  <div id="popoverCustomerSearch" class="hide">
    <form role="form" id="CustomerSearch" method="POST" action="<?php echo tep_href_link(FILENAME_CUSTOMERS); ?>">
      <div class="input-group">
        <input type="text" class="form-control input-search" id="search" name="search" placeholder="Search Customers">
        <button type="submit" class="btn btn-success input-group-append"><?php echo strtoupper(TEXT_GO); ?></a> 
      </div>
    </form>
  </div>

  <div id="popoverOrderSearch" class="hide">
    <form role="form" id="OrderSearch" method="GET" action="<?php echo tep_href_link(FILENAME_ORDERS); ?>">
      <div class="input-group">
        <input type="text" class="form-control input-search" id="search" name="SoID" placeholder="Search Orders">
        <button type="submit" class="btn btn-success input-group-append"><?php echo strtoupper(TEXT_GO); ?></a> 
      </div>
    </form>
  </div>

  <div id="popoverPagesSearch" class="hide">
    <form role="form" id="PageSearch" method="GET" action="<?php echo tep_href_link(FILENAME_PAGES); ?>">
      <div class="input-group">
        <input type="text" class="form-control input-search" id="search" name="search" placeholder="Search Pages">
        <button type="submit" class="btn btn-success input-group-append"><?php echo strtoupper(TEXT_GO); ?></a> 
      </div>
    </form>
  </div>

</div>
<script>
$(document).ready(function(){
  var mode = '<?php echo (isset($_SESSION['theme_mode'])) ? $_SESSION['theme_mode'] : 'dark'; ?>';
  changeTheme(mode);
});

// search popovers
$('#ProductsPopover').popover({
    html: true,
    placement : 'bottom',
    content: $('#popoverProductsSearch').html(),
}).on('shown.bs.popover', function() {
    closeOtherSearchPopovers('popoverProductsSearch');
    $('#ProductsPopover').parent().find('input').focus();
});
$('#CustomerPopover').popover({
    html: true,
    placement : 'bottom',
    content: $('#popoverCustomerSearch').html(),
}).on('shown.bs.popover', function() {
    closeOtherSearchPopovers('popoverCustomerSearch'); 
    $('#CustomerPopover').parent().find('input').focus();
});
$('#OrdersPopover').popover({
    html: true,
    placement : 'bottom',
    content: $('#popoverOrderSearch').html(),
}).on('shown.bs.popover', function() {
    closeOtherSearchPopovers('popoverOrderSearch'); 
    $('#OrdersPopover').parent().find('input').focus();
});
$('#PagesPopover').popover({
    html: true,
    placement : 'bottom',
    content: $('#popoverPagesSearch').html(),
}).on('shown.bs.popover', function() {
    closeOtherSearchPopovers('popoverPagesSearch');  
    $('#PagesPopover').parent().find('input').focus();
});

function closeOtherSearchPopovers(exclude) {
  if (exclude != 'popoverProductsSearch') $('#ProductsPopover').popover('hide'); 
  if (exclude != 'popoverCustomerSearch') $('#CustomerPopover').popover('hide');; 
  if (exclude != 'popoverOrderSearch') $('#OrdersPopover').popover('hide');; 
  if (exclude != 'popoverPagesSearch') $('#PagesPopover').popover('hide'); 
}

// change theme mode light/dark
function changeTheme(mode) {
  if (mode == 'light') {
    $('.dark').addClass('light').removeClass('dark');  
    $('.button-light').addClass('active');
    $('.button-dark').removeClass('active');
  } else {
    $('.light').addClass('dark').removeClass('light');   
    $('.button-dark').addClass('active');
    $('.button-light').removeClass('active');
  }
  setThemeModeSession(mode);
}

function setThemeModeSession(mode) {
  var sessid = '<?php echo session_id(); ?>';
  var rpcUrl = "./rpc.php?action=changeThemeMode&mode=MODE&osCAdminID=SESSID";
  $.get( rpcUrl.replace('MODE', mode).replace('SESSID', sessid), function( data ) {
    console.log(print_r(data, true));
  });  

}
</script>