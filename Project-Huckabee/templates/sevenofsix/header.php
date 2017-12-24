<?php
/*
  $Id: header.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$site_branding_query = tep_db_query("SELECT * FROM " . TABLE_BRANDING_DESCRIPTION . " WHERE language_id = " . $languages_id);
$site_brand_info = tep_db_fetch_array($site_branding_query);
?>
<!-- header //-->
<?php
if (DOWN_FOR_MAINTENANCE_HEADER_OFF == 'false') {
  ?>
<!--header.php start-->
<div class="topnav mid-margin-bottom">
  <div class="container topnav-container">
    <div class="pull-right margin-right">

      <ul class="cart-menu nav-item pull-right no-margin-bottom">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
             <span class="hide-on-mobile-portrait"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo MENU_TEXT_CHECKOUT ?></a></span>
          </a>
        </li>
      </ul>

      <ul class="cart-menu nav-item pull-right no-margin-bottom">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-shopping-cart white small-margin-right"></i> (<?php echo $cart->count_contents(); ?>) <span class="hide-on-mobile-portrait"><?php echo MENU_TEXT_ITEMS ?></span> <b class="caret"></b>
          </a>
          <ul class="dropdown-menu cart-dropdown" style="margin-left:-31px">
            <!-- going to change -->
            <li style="white-space: nowrap;">
              <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'); ?>"><?php  echo MENU_TEXT_ITEMS; ?> | <?php echo total; ?>: <?php echo $currencies->format($cart->show_total()) ;?> (<?php echo $cart->count_contents(); ?>)</a>
            </li>
          </ul>
        </li>
      </ul>

          <ul class="account-menu nav-item pull-right no-margin-bottom">
	        <li class="dropdown">
	          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
	            <i class="fa fa-user white small-margin-right"></i> <i class="fa fa-bars fa-bars-mobile"></i><span class="hide-on-mobile-portrait"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></span> <b class="caret"></b>
	          </a>
	          <ul class="dropdown-menu account-dropdown">
	            <?php
			  if (isset($_SESSION['customer_id'])) {
	              echo '<li><a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') , '">' . MENU_TEXT_LOGOUT . '</a></li>';
	            } else {
	              echo '<li><a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . MENU_TEXT_LOGIN . '</a></li>';
	            }
	            ?>
	            <li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a></li>
				<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'); ?>">My Order</a></li>
				<li><a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'); ?>"><?php echo IMAGE_BUTTON_ADDRESS_BOOK; ?></a></li>
				<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'); ?>">Change Password</a></li>

	          </ul>
	        </li>
	      </ul>
    </div>
   </div>
  </div>
<div class="page-header" style="margin-top: -24px;">
  <div class="container">
   <div class="row no-margin-bottom">
      <div class="col-xs-12 col-sm-6 col-lg-6" style="margin-top: 22px">
         <?php echo cre_site_branding_rspv('logo') . cre_site_branding_rspv('slogan'); ?>
     </div>
			<div class="col-xs-5 col-sm-6 col-lg-6 branding-sps pull-right">
					<div class="float-right mid-margin-right">
					  <span class="hide-on-mobile">
						<?php if(trim($site_brand_info['store_brand_support_phone']) != "") { ?><span class="mid-margin-right"><i class="fa fa-phone"></i> <?php echo $site_brand_info['store_brand_support_phone']; ?></span><?php } if(trim($site_brand_info['store_brand_support_email']) != "") { ?><span><i class="fa fa-envelope"></i> <?php echo $site_brand_info['store_brand_support_email']; ?></span><?php } ?></span>
						<?php if(trim($site_brand_info['store_brand_support_phone']) != "") { ?><span class="show-on-mobile header-fa-icons"><i data-content="<?php echo $site_brand_info['store_brand_support_phone']; ?>" data-toggle="popover" data-placement="left" title="" class="fa fa-phone fa-lg fa-sales-phone small-margin-bottom cursor-pointer" data-original-title="Sales Phone" id="example2"></i></span><?php } ?>
						<?php if(trim($site_brand_info['store_brand_support_email']) != "") { ?><span class="show-on-mobile header-fa-icons"><i data-content="<?php echo $site_brand_info['store_brand_support_email']; ?>" data-toggle="popover" data-placement="left" title="" style="margin-top:3px;" class="fa fa-envelope fa-lg fa-sales-email small-margin-bottom cursor-pointer" data-original-title="Sales Email" id="example1"></i></span><?php } ?>
						<span class="hide-on-mobile" style="font-size:21px;">	<?php echo $wholesale_catalog; ?></span>
					</div>
				  </div>
      <div class="col-xs-12 col-sm-6 col-lg-6 branding-sps">
       <div class="float-right mid-margin-right">
          <span class="hide-on-mobile"></span>
       </div>
      </div>
   </div>

    <div class="navbar navbar-inverse small-margin-bottom mobile-expand" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="text-right show-on-mobile" style="margin-top:8px">
         <form role="form" class="form-inline" name="mobile-search" id="mobile-search" action="<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, null, 'NONSSL', false); ?>" method="get">
            <span class="text-right">
            <button class="btn btn-sm cursor-pointer small-margin-right<?php echo (($cart->count_contents() > 0) ? ' btn-success' : ' btn-default disabled'); ?>" type="button" onclick="window.location.href='<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>'">Checkout</button>
              <i class="fa fa-search navbar-search-icon cursor-pointer" onclick="window.location.href='<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL'); ?>'"></i>
              <input type="text" class="navbar-search" name="keywords" placeholder="<?php echo IMAGE_BUTTON_SEARCH; ?>"><?php echo tep_hide_session_id(); ?>
			</span>
		   </form>
          <div class="mobile-portrait-search-input-cover"></div>

         </div>
        </div>

			<div class="no-margin-bottom">
          	<div class="collapse navbar-collapse navbar-ex1-collapse">
          	<ul class="nav navbar-nav col-lg-7 z-index-1">
		  		<li><a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>"><?php echo MENU_TEXT_HOME;?></a></li>
		  		<li><a href="<?php echo tep_href_link(FILENAME_FEATURED_PRODUCTS); ?>"><?php echo MENU_TEXT_FEATURED; ?></a></li>
		  		<li><a href="<?php echo tep_href_link(FILENAME_SPECIALS); ?>"><?php echo MENU_TEXT_SPECIALS;?></a></li>
		  		<li><a href="<?php echo tep_href_link(FILENAME_PRODUCTS_NEW); ?>"><?php echo MENU_TEXT_NEW_PRODUCTS; ?></a></li>
		  		<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo MENU_TEXT_MEMBERS; ?></a></li>
		  	</ul>
		  <div class="text-right small-margin-top small-margin-bottom col-lg-5">
			   <form role="form" class="form-inline hide-on-mobile" name="search" id="search" action="<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, null, 'NONSSL', false); ?>" method="get">
			    <span class="text-right">
            <span class="text-right">
              <?php
                if (trim($site_brand_info['facebook_link']) != '') {
                  echo '<a href="'.$site_brand_info['facebook_link'].'" target="_blank"><img class="small-margin-right social-nav-fb" title="Facebook" alt="Facebook" src="templates/'. TEMPLATE_NAME .'/images/icons/fb-ico.png"></a>';
                }
                if (trim($site_brand_info['twitter_link']) != '') {
                   echo '<a href="'.$site_brand_info['twitter_link'].'" target="_blank"><img class="small-margin-right social-nav-tw" title="Twitter" alt="Twitter" src="templates/'. TEMPLATE_NAME .'/images/icons/tw-ico.png"></a>';
                }
                if (trim($site_brand_info['pinterest_link']) != '') {
                   echo '<a href="'.$site_brand_info['pinterest_link'].'" target="_blank"><img class="small-margin-right social-nav-pn" title="Pinterest" alt="Pinterest" src="templates/'. TEMPLATE_NAME .'/images/icons/pn-ico.png"></a>';
                }
                if (trim($site_brand_info['google_link']) != '') {
                   echo '<a href="'.$site_brand_info['google_link'].'" target="_blank"><img class="small-margin-right social-nav-gp" title="Google+" alt="Google+" src="templates/'. TEMPLATE_NAME .'/images/icons/gp-ico.png"></a>';
                }
                if (trim($site_brand_info['youtube_link']) != '') {
                   echo '<a href="'.$site_brand_info['youtube_link'].'" target="_blank"><img class="small-margin-right social-nav-yt" title="YouTube" alt="YouTube" src="templates/'. TEMPLATE_NAME .'/images/icons/yt-ico.png"></a>';
                }
                if (trim($site_brand_info['linkedin_link']) != '') {
                   echo '<a href="'.$site_brand_info['linkedin_link'].'" target="_blank"><img class="small-margin-right social-nav-in" title="Linkedin" alt="Linkedin" src="templates/'. TEMPLATE_NAME .'/images/icons/in-ico.png"></a>';
                }
              ?>

 		           <button class="btn btn-sm cursor-pointer small-margin-right<?php echo (($cart->count_contents() > 0) ? ' btn-success' : ' btn-default disabled'); ?>" type="button" onclick="window.location.href='<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>'">Checkout</button>
				  <i class="fa fa-search navbar-search-icon"></i>
				  <input type="text" class="navbar-search" name="keywords" placeholder="<?php echo IMAGE_BUTTON_SEARCH; ?>"><?php echo tep_hide_session_id(); ?>
			    </span>
			   </form>
          </div>
		 </div>
		</div>
	   </div>
		<div class="small-margin-top hide-on-mobile">
			  <?php

				echo '<ol class="breadcrumb"><li class="active">' . $breadcrumb->trail('&nbsp;&nbsp;/&nbsp;&nbsp;') . '</li></ol>' . "\n";
			  ?>
	    </div>
	   </div>
</div>
<script>
$('#example2').popover('hide');
$('#example2').click(function(){

    $('#example').popover('show');});
$('#example1').popover('hide');
$('#exampleq').click(function(){

    $('#example1').popover('show');});

</script>
  <?php
}
?>
<!-- header_eof //-->