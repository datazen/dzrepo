<!doctype html>
<html lang="en">
  <head>
    <title><?php echo TITLE; ?></title>
    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="assets/img/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/img/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="Create Great Looking Mobile Responsive E-commerce Sites. Designed not just for the challenges of today, but tomorrow. Open source. Unlimited Free Edition." name="description" />
    <meta content="Loaded Commerce" name="author" />
    <?php
    // themes: black, blue, default, red, orange, purple
    if (!isset($_SESSION['theme'])) $_SESSION['theme'] = (defined('ADMIN_THEME') && in_array(ADMIN_THEME, array("red","blue","default","black","purple","orange"))) ? ADMIN_THEME : 'default';
    if (isset($_GET['theme']) && in_array($_GET['theme'], array("red","blue","default","black","purple","orange"))) {
      $_SESSION['theme'] = $_GET['theme'];
    }
    // modes: dark, light
    $_SESSION['theme_mode'] = getThemeMode();

    function getThemeMode() {
      $query = tep_db_query("SELECT * FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'ADMIN_THEME_MODE'");
      $cArr = tep_db_fetch_array($query);

      return $cArr['configuration_value'];
    }    
    ?>
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <!-- link href="assets/plugins/bootstrap4/css/bootstrap.min.css" rel="stylesheet" / -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link href="assets/css/style-bs4.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/style-responsive.css" rel="stylesheet" />
    <link href="assets/css/dark.css" rel="stylesheet" />
    <link href="assets/css/light.css" rel="stylesheet" />
    <link href="assets/css/theme/<?php echo $_SESSION['theme']; ?>.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="assets/plugins/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
    <!-- link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" / -->  
    <link href="assets/plugins/morris/morris.css" rel="stylesheet" />    
    <!-- ================== END PAGE LEVEL STYLE ================== -->    

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="assets/plugins/tether/js/tether.min.js"></script>
    <!-- script src="assets/plugins/bootstrap4/js/bootstrap.min.js"></script -->
    <!--[if lt IE 9]>
      <script src="assets/crossbrowserjs/html5shiv.js"></script>
      <script src="assets/crossbrowserjs/respond.min.js"></script>
      <script src="assets/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="assets/plugins/morris/raphael.min.js"></script>
    <script src="assets/plugins/morris/morris.js"></script>    
    <!-- script src="assets/plugins/gritter/js/jquery.gritter.js"></script -->
    <script src="assets/plugins/flot/jquery.flot.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.pie.min.js"></script>
    <script src="assets/plugins/sparkline/jquery.sparkline.js"></script>
    <script src="assets/plugins/jquery-jvectormap/jquery-jvectormap.min.js"></script>
    <script src="assets/plugins/jquery-jvectormap/jquery-jvectormap-world-merc-en.js"></script>
    <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>   
    <!--script src="assets/js/dashboard.js"></script -->
    <script src="assets/js/dashboard-v2.js"></script>
    <script src="assets/js/apps.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

  </head>
  <body>
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <!-- end #page-loader -->

    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">