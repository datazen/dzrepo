<?php
/*
  $Id: main_page.tpl.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2016 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <base href="<?php echo (($request_type == 'SSL' || (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == 'https')) ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . 'index.php'; ?>">
    <?php
    if ( file_exists(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS) ) {
      require(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS);
    } else {
      ?>
      <title><?php echo TITLE ?></title>
      <?php
    }
    ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="shortcut icon" href="templates/cre65_rspv/favicon.ico">
    <link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <?php
    // RCI code start
    //echo $cre_RCI->get('stylesheet', 'cre65ats');
    echo $cre_RCI->get('global', 'head');
    // RCI code eof
    if (isset($javascript) && file_exists(DIR_WS_JAVASCRIPT . basename($javascript))) { require(DIR_WS_JAVASCRIPT . basename($javascript)); }
    if (defined('PRODUCT_INFO_TAB_ENABLE') && PRODUCT_INFO_TAB_ENABLE == 'True' && basename($PHP_SELF) == FILENAME_PRODUCT_INFO) {
      ?>
      <script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>tabs/webfxlayout.js"></script>
      <link type="text/css" rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME;?>/tabs/tabpane.css">
      <script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>tabs/tabpane.js"></script>
      <?php
    } 
    ?>   
    <!-- New Responsive section start CSS -->
    <!-- link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/bootstrap/css/bootstrap.css" -->
    <link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/css/template.css?v=<?php echo rand();?>">
    <link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/font-awesome.css" rel="stylesheet">    
    <link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/stylesheet.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/lightbox.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/carousel.css" />
    <link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/bootstrap/css/bootstrap-datepicker.css">
    <link rel="stylesheet" type="text/css" href="includes/javascript/fancyBox/jquery.fancybox.css?v=2.1.5" media="screen" />
  </head>
  <body>
    <h1>Hello, world!</h1>






    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/bootstrap/bootstrap-datepicker.js"></script>
    <script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>css/bootstrap/js/bootstrap-fileinput.js"></script>
    <script src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>jquery/jquery.loadmask.js"></script>
    <!-- Megnor www.templatemela.com - Start -->
    <script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>jquery/custom.js"></script>
    <script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>jquery/jstree.min.js"></script>
    <script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>jquery/carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>jquery/megnor.min.js"></script>
    <script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>jquery/jquery.custom.min.js"></script>
    <script type="text/javascript" src="includes/javascript/fancyBox/jquery.fancybox.js?v=2.1.5"></script>    
  </body>
</html>



<!-- warning_eof //-->
<div id="loaded7" class="loadedcommerce-main-wrapper">

<?php
// RCI top
echo $cre_RCI->get('mainpage', 'top');
if(DISPLAY_COLUMN_LEFT == 'yes' && DISPLAY_COLUMN_RIGHT == 'yes') {
	$left_col_div = (BOX_WIDTH_LEFT > 0)?BOX_WIDTH_LEFT:2;
	$right_col_div = (BOX_WIDTH_RIGHT > 0)?BOX_WIDTH_RIGHT:2;
	$content_col_div = (12 - ($left_col_div + $right_col_div));
} elseif(DISPLAY_COLUMN_LEFT == 'yes' || DISPLAY_COLUMN_RIGHT == 'yes') {
	if(DISPLAY_COLUMN_LEFT == 'yes')
		$left_col_div = (BOX_WIDTH_LEFT > 0)?BOX_WIDTH_LEFT:3;
	else
		$left_col_div = 0;
	if(DISPLAY_COLUMN_RIGHT == 'yes')
		$right_col_div = (BOX_WIDTH_RIGHT > 0)?BOX_WIDTH_RIGHT:3;
	else
		$right_col_div = 0;
	$content_col_div = (12 - ($left_col_div + $right_col_div));
} else {
	$content_col_div = 12;
}
?>
<!-- header //-->
<?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . FILENAME_HEADER); ?>
<!-- header_eof //-->
<!-- body //-->
  <div class="container" id="content-container">
	 <?php
	 if (DISPLAY_COLUMN_LEFT == 'yes' && (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false'))  {
		 $column_location = 'Left_';
	 ?>
   <div class="col-sm-<?php echo $left_col_div; ?> col-lg-<?php echo $left_col_div; ?> hide-on-mobile no-padding-left" id="content-left-container">

		 <?php ob_start();?>
		   <!-- left_navigation //-->
		   <?php require(DIR_WS_INCLUDES . FILENAME_COLUMN_LEFT); ?>
		   <!-- left_navigation_eof //-->
		   <?php  $var = ob_get_clean();
			 echo $var;
		   ?>
	</div>

	   <?php
	   $column_location = '';
	 }
	 ?>

   <div id="content-center-container" class="col-sm-<?php echo $content_col_div; ?> col-lg-<?php echo $content_col_div; ?>">
    <!-- content //-->
      <?php //echo (DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'. $content . '.tpl.php');
      if (isset($content_template) && file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'.  basename($content_template))) {
        require(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . $content . '.tpl.php');
      } else if (file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . $content . '.tpl.php')) {
        require(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'. $content . '.tpl.php');
      } else if (isset($content_template) && file_exists(DIR_WS_CONTENT . basename($content_template)) ){
        require(DIR_WS_CONTENT . basename($content_template));
      } else {
        require(DIR_WS_CONTENT . $content . '.tpl.php');
      }
      ?>
   </div>

	   <?php
	   if (DISPLAY_COLUMN_LEFT == 'yes' && (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false'))  {
		   $column_location = 'Left_';
	   ?>
		<div class="col-sm-<?php echo $left_col_div; ?> col-lg-<?php echo $left_col_div; ?> large-margin-top show-on-mobile" id="content-left-container">
		   <?php echo $var;   ?>
		</div>
		 <?php
		 $column_location = '';
	   }
	   ?>

    <!-- content_eof //-->
    <?php
    if (DISPLAY_COLUMN_RIGHT == 'yes' && (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false'))  {
        $column_location = 'Right_';
      ?>
       <div class="col-sm-<?php echo $right_col_div; ?> col-lg-<?php echo $right_col_div; ?> no-padding-right" id="content-right-container">
        <!-- right_navigation //-->
        <?php require(DIR_WS_INCLUDES . FILENAME_COLUMN_RIGHT); ?>
        <!-- right_navigation_eof //-->
      </div>

      <?php
      $column_location = '';
    }
    ?>


   </div>

    <!-- content_eof //-->
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME .'/'.FILENAME_FOOTER); ?>
<!-- footer_eof //-->
<br>
<?php
// RCI global footer
echo $cre_RCI->get('global', 'footer');
?>
</div>
<?php
//echo $content;
$arr_contents = array('index_products', 'featured_products', 'specials','products_new');
if(in_array($content, $arr_contents))
{
?>
<script language="javascript">
/*
  Thanks to CSS Tricks for pointing out this bit of jQuery
  http://css-tricks.com/equal-height-blocks-in-rows/
  It's been modified into a function called at page load and then each time the page is resized.
  One large modification was to remove the set height before each new calculation.
 */
equalheight = function(container) {
  var currentTallest = 0,
      currentRowStart = 0,
      rowDivs = new Array(),
      $el,
      topPosition = 0;
  $(container).each(function() {
    $el = $(this);
    //$($el).height('auto')
    topPostion = $el.position().top;
    if (currentRowStart != topPostion) {
      for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
        rowDivs[currentDiv].height(currentTallest);
      }
      rowDivs.length = 0; // empty the array
      currentRowStart = topPostion;
      currentTallest = $el.height();
      rowDivs.push($el);
    } else {
      rowDivs.push($el);
      currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
    }
    for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
      rowDivs[currentDiv].height(currentTallest);
    }
  });
}

$(window).load(function() {
  equalheight('.product-listing-module-container .itembox');
});

$(window).resize(function(){
  equalheight('.product-listing-module-container .itembox');
});
</script>
<?php
}
?>
</body>
</html>