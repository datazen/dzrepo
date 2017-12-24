<?php
/*
  $Id: server_info.php,v 1.1.1.1 2004/03/04 23:38:58 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
if (function_exists(tep_get_system_information)) {
  $system = tep_get_system_information();
} else {
  $system = '';  
}
if (!defined('TEXT_FAILED')) define('TEXT_FAILED', 'Failed');
if (!defined('TEXT_SUCCESS')) define('TEXT_SUCCESS', 'Success');
if (!defined('TEXT_ON')) define('TEXT_ON', 'On');
if (!defined('TEXT_OFF')) define('TEXT_OFF', 'Off');

function checkINI($option) {
  $value = strtolower( trim( ini_get( $option ) ) );
  if ( $value == 'on' ) $value = TEXT_ON;
  elseif ( $value == 'off' )  $value = TEXT_OFF;
  elseif ( $value == '1' )  $value = TEXT_ON;
  elseif ( $value == '0' ) $value = TEXT_OFF;
  else $value = TEXT_OFF;
 
  return $value;
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>


  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
                                                             <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/style.min.css" rel="stylesheet" />
  <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
  <link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
  <!-- ================== END BASE CSS STYLE ================== -->
  
  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  <link href="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />  
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
  <!-- ================== END PAGE LEVEL STYLE ================== -->
  <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
</head>
<body>
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed gradient-enabled">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
      
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
      
    <!-- begin #content -->
    <div id="content" class="content">
      <!-- begin breadcrumb -->
      <ol class="breadcrumb pull-right">
        <li>Create &nbsp; <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ACCOUNT;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-user"></i><span class="label">+</span></a> <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ORDER;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ORDER,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-shopping-cart"></i><span class="label">+</span></a></li>
        <li>Search &nbsp; <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="ProductsPopover">Products</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="CustomerPopover">Customers</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="OrdersPopover">Orders</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="PagesPopover">Pages</a></li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header"><?php echo HEADING_TITLE; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse" style="background-color:#FFFFFF !important"> <table cellpadding="2" cellspacing="2" border="0" width="100%">
            <tr><td colspan="3"><a href="http://www.loadedcommerce.com/" target="_blank"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . STORE_LOGO ,STORE_NAME,'','','style="height:64px"');?></a><h1 class="p"><?php echo PROJECT_VERSION . ' at revision:' . INSTALLED_VERSION_REVISION; ?></h1></td></tr>
            <?php
            // RCI include version files
            $returned_rci = $cre_RCI->get('serverinfo', 'version');
            ?>
            <tr><td colspan="3"><?php echo $returned_rci; ?></td></tr> 
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
            </tr>  
            <tr>
              <td colspan="3"><table border="0" cellspacing="0" cellpadding="3">
                <tr>
                  <td class="smallText" width="90"><b><?php echo TITLE_SERVER_HOST; ?></b></td>
                  <td class="smallText"><?php echo $system['host'] . ' (' . $system['ip'] . ')'; ?></td>
                  <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_HOST; ?></b></td>
                  <td class="smallText"><?php echo $system['db_server'] . ' (' . $system['db_ip'] . ')'; ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_SERVER_OS; ?></b></td>
                  <td class="smallText"><?php echo $system['system'] . ' ' . $system['kernel']; ?></td>
                  <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE; ?></b></td>
                  <td class="smallText"><?php echo $system['db_version']; ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_SERVER_DATE; ?></b></td>
                  <td class="smallText"><?php echo $system['date']; ?></td>
                  <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_DATE; ?></b></td>
                  <td class="smallText"><?php echo $system['db_date']; ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_SERVER_UP_TIME; ?></b></td>
                  <td colspan="3" class="smallText"><?php echo $system['uptime']; ?></td>
                </tr>
                <tr>
                  <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_HTTP_SERVER; ?></b></td>
                  <td colspan="3" class="smallText"><?php echo $system['http_server']; ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_PHP_VERSION; ?></b></td>
                  <td colspan="3" class="smallText"><?php echo $system['php'] . ' (' . TITLE_ZEND_VERSION . ' ' . $system['zend'] . ')'; ?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
            </tr>
            <tr>
              <td align="center"><b>Server Requirements</b><br><?php echo TEXT_PCI; ?>
              <td align="center"><b>Required PHP Settings</b></td>
              <td align="center"><b>Writable Folder Permissions</b></td>
            </tr>        
            <tr>
              <td align="center" valign="top"><table border="1" cellspacing="0" cellpadding="3" style="border: 0px; border-color: #000000;">
                <tr>
                  <td><a href="http://www.php.net/downloads.php" target="php">PHP >= 5.2.0</a></td>
                  <td align="left">
                    <?php echo ( phpversion() < '5.2.0' ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.mysql.php" target="php">PHP MySQL</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('mysql') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <?php
                if ( phpversion() > '5.0' ) {
                  ?>
                  <tr>
                    <td><a href="http://www.php.net/manual/en/ref.mysqli.php" target="php">PHP MySQLi</a></td>
                    <td align="left">
                      <?php echo ( !extension_loaded('mysqli') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php
                }
                ?>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.pcre.php" target="php">PHP PCRE</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('pcre') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.zlib.php" target="php">PHP ZLIB</a></td>
                  <td align="left">
                    <?php echo ( !function_exists( 'gzencode' ) ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.curl.php" target="php">PHP cURL</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('curl') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.exif.php" target="php">PHP EXIF</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('exif') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.image.php" target="php">PHP GD</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('gd') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.mcrypt.php" target="php">PHP Mcrypt</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('mcrypt') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.openssl.php" target="php">PHP OPENSSL</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('openssl') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.ftp.php" target="php">PHP FTP</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('ftp') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
              </table></td>
              <td align="center" valign="top"><table border="1" cellspacing="0" cellpadding="3" style="border: 0px; border-color: #000000;">
                <tr>
                  <td>safe_mode</td>
                  <td><?php echo ($ini_setting = checkINI('safe_mode')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting == TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td>file_uploads</td>
                  <td><?php echo ($ini_setting = checkINI('file_uploads')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting != TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td>session.auto_start</td>
                  <td><?php echo ($ini_setting = checkINI('session.auto_start')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting == TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td>magic_quotes</td>
                  <td><?php echo ($ini_setting = checkINI('magic_quotes')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting == TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td>allow_url_fopen</td>
                  <td><?php echo ($ini_setting = checkINI('allow_url_fopen')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting != TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
              </table></td>
              <td align="center" valign="top"><table border="1" cellspacing="0" cellpadding="3" style="border: 0px; border-color: #000000;">
                <?php 
                if (file_exists(DIR_FS_CATALOG . 'tmp/')) { 
                  ?>
                  <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'tmp/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'tmp/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_CATALOG . 'temp/')) { 
                  ?>
                  <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'temp/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'temp/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_CATALOG . 'cache/')) { 
                  ?>
                  <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'cache/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'cache/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_CATALOG . 'images/')) { 
                  ?>
                    <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'images/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'images/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_CATALOG . 'debug/')) { 
                  ?>
                  <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'debug/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'debug/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_ADMIN . 'backups/')) { ?>
                  <tr>                   
                    <td><?php echo DIR_FS_ADMIN . 'backups/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_ADMIN . 'backups/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                } ?>
              </table></td>
            </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td>
              <?php
              if (function_exists('ob_start')) {
                ?>
                <style type="text/css">
                  .p {text-align: left;}
                  .e {background-color: #ccccff; font-weight: bold;}
                  .h {background-color: #9999cc; font-weight: bold;}
                  .v {background-color: #cccccc;}
                   i {color: #666666;}
                  hr {display: none;}
                </style>
                <?php
                ob_start();
                phpinfo();
                $phpinfo = ob_get_contents();
                ob_end_clean();
                $phpinfo = str_replace('border: 1px', '', $phpinfo);
                preg_match('/<body>(.*)</body>/', $phpinfo, $regs);
                echo $regs[1];
              } else {
                phpinfo();
              }
              ?>
              </td>
            </tr>
          </table>
         </td> </tr>
          
          </table>
          
          
        
        
      </div>
    </div>
    <!-- end panel -->
    </div>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>