<?php
/*
  $Id: footer.php,v 1.1.1.1 2004/03/04 23:39:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Chain Reaction Works, Inc
  
  Copyright &copy; 2003-2006
  
  Last Modified By : $Author$
  Last Modified On : $LastChangeDate$
  Latest Revision :  $Revision: 3529 $
  
*/
// RCI top
echo $cre_RCI->get('footer', 'top');
?>      
      <!-- begin #footer -->
      <div id="footer" class="footer">
        <?php echo FOOTER_TEXT_BODY;?>
      </div>
      <!-- end #footer -->
      
      <!-- begin scroll to top btn -->
      <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top">
        <i class="fa fa-angle-up"></i>
      </a>
      <!-- end scroll to top btn -->
    </div>
    <!-- end page container -->
       
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="assets/crossbrowserjs/html5shiv.js"></script>
        <script src="assets/crossbrowserjs/respond.min.js"></script>
        <script src="assets/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="assets/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script>
$('.data-table').addClass('table dataTable no-footer dtr-inline');
$('.box-categories-ul').addClass('list-unstyled list-indent-large');
</script>
  
  <!-- customer search -->
      <div id="popoverProductsSearch" class="hide">
        <form role="form" id="ProductSearch" method="post" action="<?php echo tep_href_link(FILENAME_CATEGORIES,'','SSL');?>">
            <div class="input-group">
              <input type="text" class="form-control" id="search" name="search" placeholder="Search Products">
              <a href="javascript:document.getElementById('ProductSearch').submit();" class="btn btn-default input-group-addon">GO</a>
            </div>
            <?php
             if (isset($_GET[tep_session_name()])) {
              echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
            ?>
        </form>
    </div>
    <div id="popoverCustomerSearch" class="hide">
        <form role="form" id="CustomerSearch" method="GET" action="<?php echo tep_href_link(FILENAME_CUSTOMERS,'','SSL');?>">
            <div class="input-group">
              <input type="text" class="form-control" id="search" name="search" placeholder="Search Customer">
              <a href="javascript:document.getElementById('CustomerSearch').submit();" class="btn btn-default input-group-addon">GO</a>
            </div>
            <?php
             if (isset($_GET[tep_session_name()])) {
              echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
            ?>
        </form>
    </div>
    <div id="popoverOrderSearch" class="hide">
        <form role="form" id="OrderSearch" method="GET" action="<?php echo tep_href_link(FILENAME_ORDERS,'','SSL');?>">
            <div class="input-group">
              <input type="text" class="form-control" id="SoID" name="SoID" placeholder="Search Orders">
              <a href="javascript:document.getElementById('OrderSearch').submit();" class="btn btn-default input-group-addon">GO</a>
            </div>
            <?php
             if (isset($_GET[tep_session_name()])) {
              echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
             tep_draw_hidden_field('action', 'edit');
            ?>
        </form>
    </div>
    <div id="popoverPagesSearch" class="hide">
        <form role="form" id="PageSearch" method="post" action="<?php echo tep_href_link(FILENAME_CDS_PAGE_MANAGER,'','SSL');?>">
            <div class="input-group">
              <input type="text" class="form-control" id="search" name="search" placeholder="Search Pages">
              <a href="javascript:document.getElementById('PageSearch').submit();" class="btn btn-default input-group-addon">GO</a>
            </div>
            <?php
             if (isset($_GET[tep_session_name()])) {
              echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
             tep_draw_hidden_field('action', 'edit');
            ?>
        </form>
    </div>
    <script>
    $('#ProductsPopover').popover({
        html: true,
        placement : 'auto bottom',
        content: $('#popoverProductsSearch').html(),
    }).on('shown.bs.popover', function() {
        $('#ProductsPopover').parent().find('input').focus();
    });
    $('#CustomerPopover').popover({
        html: true,
        placement : 'auto bottom',
        content: $('#popoverCustomerSearch').html(),
    }).on('shown.bs.popover', function() {
        $('#CustomerPopover').parent().find('input').focus();
    });
    $('#OrdersPopover').popover({
        html: true,
        placement : 'auto bottom',
        content: $('#popoverOrderSearch').html(),
    }).on('shown.bs.popover', function() {
        $('#OrdersPopover').parent().find('input').focus();
    });
    $('#PagesPopover').popover({
        html: true,
        placement : 'auto bottom', 
        content: $('#popoverPagesSearch').html(),
    }).on('shown.bs.popover', function() {
        $('#PagesPopover').parent().find('input').focus();
    });

//$('[data-toggle="popover"]').popover();

$('body').on('click', function (e) {
    $('.header-popover').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
})

    </script>
  <!-- customer search -->
<?php
// RCI bottom
echo $cre_RCI->get('footer', 'bottom');
?>