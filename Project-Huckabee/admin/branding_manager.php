<?php
/*
  $Id: logo_manager.php,v 1.1.1.1 2007/07/07 23:38:34 wa4u Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

      if(!function_exists('cre_db_update')){
    function cre_db_update($table, $data, $link = 'db_link') {
    reset($data);
     $query = 'REPLACE INTO ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) ;

    return tep_db_query($query, $link);
  }
}

  //destination for uploaded logos
  define('DIR_FS_LOGO',DIR_FS_CATALOG_IMAGES . 'logo/');
  define('DIR_WS_LOGO',HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'logo/');
    // get languages
  $languages = tep_get_languages();

    if (isset($_GET['action'])) {
    $action = $_GET['action'] ;
    }else if (isset($_POST['action'])){
    $action = $_POST['action'] ;
    } else {
    $action = '' ;
   }

   if (isset($action) && $action == 'upload') {

       for ($i=0; $i<sizeof($languages); $i++) {
           $store_brand_telephone = (isset($_POST['store_brand_telephone' . $languages[$i]['id']]) ? $_POST['store_brand_telephone' . $languages[$i]['id']] : '');
           $store_brand_fax = (isset($_POST['store_brand_fax' . $languages[$i]['id']]) ? $_POST['store_brand_fax' . $languages[$i]['id']] : '');
           $store_brand_homepage = (isset($_POST['store_brand_homepage' . $languages[$i]['id']]) ? $_POST['store_brand_homepage' . $languages[$i]['id']] : '');
           $store_brand_image = (isset($_POST['store_brand_image' . $languages[$i]['id']]) ? $_POST['store_brand_image' . $languages[$i]['id']] : '');
           $store_brand_name = (isset($_POST['store_brand_name' . $languages[$i]['id']]) ? $_POST['store_brand_name' . $languages[$i]['id']] : '');
           $store_brand_slogan = (isset($_POST['store_brand_slogan' . $languages[$i]['id']]) ? $_POST['store_brand_slogan' . $languages[$i]['id']] : '');
           $store_brand_support_email = (isset($_POST['store_brand_support_email' . $languages[$i]['id']]) ? $_POST['store_brand_support_email' . $languages[$i]['id']] : '');
           $store_brand_support_phone = (isset($_POST['store_brand_support_phone' . $languages[$i]['id']]) ? $_POST['store_brand_support_phone' . $languages[$i]['id']] : '');
           $store_brand_image_existing = (isset($_POST['store_brand_image_existing' . $languages[$i]['id']]) ? $_POST['store_brand_image_existing' . $languages[$i]['id']] : '');
           $delete_image = (isset($_POST['delete_image' . $languages[$i]['id']]) ? $_POST['delete_image' . $languages[$i]['id']] : '');

           $store_brand_favicon = (isset($_POST['store_brand_favicon' . $languages[$i]['id']]) ? $_POST['store_brand_favicon' . $languages[$i]['id']] : '');
           $store_brand_favicon_existing = (isset($_POST['store_brand_favicon_existing' . $languages[$i]['id']]) ? $_POST['store_brand_favicon_existing' . $languages[$i]['id']] : '');
           $delete_favicon = (isset($_POST['delete_favicon' . $languages[$i]['id']]) ? $_POST['delete_favicon' . $languages[$i]['id']] : '');

           $store_og_image = (isset($_POST['store_og_image' . $languages[$i]['id']]) ? $_POST['store_og_image' . $languages[$i]['id']] : '');
           $store_og_image_existing = (isset($_POST['store_og_image_existing' . $languages[$i]['id']]) ? $_POST['store_og_image_existing' . $languages[$i]['id']] : '');
           $delete_store_og_image = (isset($_POST['delete_store_og_image' . $languages[$i]['id']]) ? $_POST['delete_store_og_image' . $languages[$i]['id']] : '');

           $facebook_link = (isset($_POST['facebook_link' . $languages[$i]['id']]) ? $_POST['facebook_link' . $languages[$i]['id']] : '');
           $twitter_link = (isset($_POST['twitter_link' . $languages[$i]['id']]) ? $_POST['twitter_link' . $languages[$i]['id']] : '');
           $pinterest_link = (isset($_POST['pinterest_link' . $languages[$i]['id']]) ? $_POST['pinterest_link' . $languages[$i]['id']] : '');
           $google_link = (isset($_POST['google_link' . $languages[$i]['id']]) ? $_POST['google_link' . $languages[$i]['id']] : '');
           $youtube_link = (isset($_POST['youtube_link' . $languages[$i]['id']]) ? $_POST['youtube_link' . $languages[$i]['id']] : '');
           $linkedin_link = (isset($_POST['linkedin_link' . $languages[$i]['id']]) ? $_POST['linkedin_link' . $languages[$i]['id']] : '');
           $store_brand_address = (isset($_POST['store_brand_address' . $languages[$i]['id']]) ? $_POST['store_brand_address' . $languages[$i]['id']] : '');
           $custom_css = (isset($_POST['custom_css' . $languages[$i]['id']]) ? $_POST['custom_css' . $languages[$i]['id']] : '');

           //delete image
           $deleted_image = false;
           if($delete_image !='' && $store_brand_image_existing !='' || $store_brand_image != ''){
             $image_query = tep_db_query("SELECT store_brand_image FROM " . TABLE_BRANDING_DESCRIPTION . " WHERE store_brand_image = '" . $store_brand_image_existing . "' AND language_id <> '" . $languages[$i]['id'] . "'");
             if (tep_db_num_rows($image_query) == 0) {
               unlink(DIR_FS_LOGO . $store_brand_image_existing);
             }
               $delete_logo = "Update " . TABLE_BRANDING_DESCRIPTION . " set store_brand_image = '' where language_id = '" . $languages[$i]['id'] . "'";
               tep_db_query($delete_logo);
               $deleted_image = true;
           }

           //delete favicon
           $deleted_favicon = false;
           if($delete_favicon !='' && $store_brand_favicon_existing !='' || $store_brand_favicon != ''){
             $favicon_query = tep_db_query("SELECT store_brand_image FROM " . TABLE_BRANDING_DESCRIPTION . " WHERE store_brand_favicon = '" . $store_brand_favicon_existing . "' AND language_id <> '" . $languages[$i]['id'] . "'");
             if (tep_db_num_rows($favicon_query) == 0) {
               unlink(DIR_FS_LOGO . $store_brand_favicon_existing);
             }
               $delete_favicon = "Update " . TABLE_BRANDING_DESCRIPTION . " set store_brand_favicon = '' where language_id = '" . $languages[$i]['id'] . "'";
               tep_db_query($delete_favicon);
               $deleted_favicon = true;
           }

           //delete og image
           $deleted_store_og_image = false;
           if($delete_store_og_image !='' && $store_og_image_existing !='' || $store_og_image != ''){
             $favicon_query = tep_db_query("SELECT store_brand_image FROM " . TABLE_BRANDING_DESCRIPTION . " WHERE store_og_image = '" . $store_og_image_existing . "' AND language_id <> '" . $languages[$i]['id'] . "'");
             if (tep_db_num_rows($favicon_query) == 0) {
               unlink(DIR_FS_LOGO . $store_og_image_existing);
             }
               $delete_store_og_image = "Update " . TABLE_BRANDING_DESCRIPTION . " set store_og_image = '' where language_id = '" . $languages[$i]['id'] . "'";
               tep_db_query($delete_store_og_image);
               $deleted_store_og_image = true;
           }

           @unlink(DIR_FS_LOGO.'custom.css');
           if(trim($custom_css) != "")
           {
           	 $fp = fopen(DIR_FS_LOGO.'custom.css', 'w');
           	 fwrite($fp, $custom_css);
           	 fclose($fp);
           }

           //upload image
               $store_brand_image_tmp = '';
               $store_brand_image_name = '';
               $store_brand_image_tmp = new upload('store_brand_image' . $languages[$i]['id']);
               $store_brand_image_tmp->set_destination(DIR_FS_LOGO);
               #$store_brand_image_tmp->set_extensions('gif','jpg','png');
               if ($store_brand_image_tmp->parse() && $store_brand_image_tmp->save()) {
                   $store_brand_image_name = $store_brand_image_tmp->filename;
               } else if (is_file(DIR_FS_LOGO . $store_brand_image)){
                   $store_brand_image_name = $store_brand_image;
               } else {
                   $store_brand_image_name = $store_brand_image_existing;
               }
               if($deleted_image) $store_brand_image_name = '';

           //upload favicon
               $store_brand_favicon_tmp = '';
               $store_brand_favicon_name = '';
               $store_brand_favicon_tmp = new upload('store_brand_favicon' . $languages[$i]['id']);
               $store_brand_favicon_tmp->set_destination(DIR_FS_LOGO);
               #$store_brand_favicon_tmp->set_extensions('gif','jpg','png');
               if ($store_brand_favicon_tmp->parse() && $store_brand_favicon_tmp->save()) {
                   $store_brand_favicon_name = $store_brand_favicon_tmp->filename;
               } else if (is_file(DIR_FS_LOGO . $store_brand_favicon)){
                   $store_brand_favicon_name = $store_brand_favicon;
               } else {
                   $store_brand_favicon_name = $store_brand_favicon_existing;
               }
               if($deleted_favicon) $store_brand_favicon_name = '';

           //upload og image
               $store_og_image_tmp = '';
               $store_og_image_name = '';
               $store_og_image_tmp = new upload('store_og_image' . $languages[$i]['id']);
               $store_og_image_tmp->set_destination(DIR_FS_LOGO);
               #$store_og_image_tmp->set_extensions('gif','jpg','png');
               if ($store_og_image_tmp->parse() && $store_og_image_tmp->save()) {
                   $store_og_image_name = $store_og_image_tmp->filename;
               } else if (is_file(DIR_FS_LOGO . $store_og_image)){
                   $store_og_image_name = $store_og_image;
               } else {
                   $store_og_image_name = $store_og_image_existing;
               }
               if($deleted_store_og_image) $store_og_image_name = '';

               $sql_data_array = array( 'store_brand_image' =>   $store_brand_image_name,
               							'store_brand_favicon' =>   $store_brand_favicon_name,
               							'store_og_image' =>   $store_og_image_name,
                                        'store_brand_slogan' =>    tep_db_prepare_input($store_brand_slogan),
                                        'store_brand_telephone' =>  tep_db_prepare_input($store_brand_telephone),
                                        'store_brand_fax' => tep_db_prepare_input($store_brand_fax),
                                        'store_brand_homepage' => tep_db_prepare_input($store_brand_homepage),
                                        'store_brand_name' => tep_db_prepare_input($store_brand_name),
                                        'store_brand_support_email' => tep_db_prepare_input($store_brand_support_email),
                                        'store_brand_support_phone' => tep_db_prepare_input($store_brand_support_phone),
                                        'store_brand_address' => tep_db_prepare_input($store_brand_address),
                                        'custom_css' => tep_db_prepare_input($custom_css),
                                        'facebook_link' => tep_db_prepare_input($facebook_link),
                                        'twitter_link' => tep_db_prepare_input($twitter_link),
                                        'pinterest_link' => tep_db_prepare_input($pinterest_link),
                                        'google_link' => tep_db_prepare_input($google_link),
                                        'youtube_link' => tep_db_prepare_input($youtube_link),
                                        'linkedin_link' => tep_db_prepare_input($linkedin_link),
                                        'language_id' => tep_db_input($languages[$i]['id'])
                                        );
               cre_db_update(TABLE_BRANDING_DESCRIPTION, $sql_data_array);

   }// language loop end
    tep_redirect(tep_href_link(FILENAME_BRANDING_MANAGER));
 }// action end

  //check directory is exists and writable
  $error = false;
  if (is_dir(DIR_FS_LOGO)) {
    if (!is_writeable(DIR_FS_LOGO)) {
      $messageStack->add('search',ERROR_LOGO_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
       $error = true;
    }
  } else {
    $messageStack->add('search',ERROR_LOGO_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
    $error = true;
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
<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}

.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}

.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->
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
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
        <tr>
          <td><?php echo tep_draw_form('frm_upload', FILENAME_BRANDING_MANAGER, 'action=upload', 'post', 'enctype="multipart/form-data" class="form-horizontal"'); ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="4" align="center">
              <tr>
                <td><div class="tab-pane" id="tabPane1">
                    <script type="text/javascript">
               tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
              </script>
                    <?php
for ($i=0; $i<sizeof($languages); $i++) {
//get existing brand info
$store_brand_info_qry = tep_db_query("SELECT * from " . TABLE_BRANDING_DESCRIPTION ." where language_id = '" . $languages[$i]['id'] . "'");
$store_brand_info = tep_db_fetch_array($store_brand_info_qry);

?>
                    <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                      <h2 class="tab"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '', '', 'valign="middle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></h2>
                      <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
<table width="100%" border="0" cellspacing="3" cellpadding="3">
      <tr>
        <td><h5><?php echo sprintf(TITLE_STORE_BRAND,$languages[$i]['name']); ?></h5></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2" class="data-table">
          <tr>
            <td><?php echo STORE_BRAND_TELEPHONE_NUMBER; ?></td>
            <td><?php echo tep_draw_input_field('store_brand_telephone' . $languages[$i]['id'], $store_brand_info['store_brand_telephone'], 'maxlength="32" class="form-control"'); ?></td>
          </tr>
          <tr>
            <td><?php echo STORE_BRAND_FAX_NUMBER; ?></td>
            <td><?php echo tep_draw_input_field('store_brand_fax' . $languages[$i]['id'], $store_brand_info['store_brand_fax'], 'maxlength="32" class="form-control"'); ?></td>
          </tr>
          <tr>
            <td><?php echo STORE_BRAND_HOMEPAGE; ?></td>
            <td><?php echo tep_draw_input_field('store_brand_homepage' . $languages[$i]['id'], $store_brand_info['store_brand_homepage'], 'maxlength="64" class="form-control"'); ?></td>
          </tr>
          <tr>
            <td><?php echo STORE_BRAND_COMPANY_LOGO; ?></td>
            <td><?php echo tep_draw_file_field('store_brand_image' . $languages[$i]['id']);  if (tep_not_null($store_brand_info['store_brand_image']) && file_exists( DIR_FS_LOGO . $store_brand_info['store_brand_image'] )) { echo '<br>' . DIR_FS_LOGO . $store_brand_info['store_brand_image']; }?>
            </td>
          </tr>
          <?php
          if (tep_not_null($store_brand_info['store_brand_image']) ) {
          ?>
          <tr>
            <td></td>
            <td valign="middle"><?php
            if( file_exists( DIR_FS_LOGO . $store_brand_info['store_brand_image'] )) {
                echo tep_image(DIR_WS_LOGO . $store_brand_info['store_brand_image']) ;
            } else {
                echo '<span class="errorText">' . BRANDING_ERROR_IMAGE_MISSING .'</span>';
            }
            echo ' &nbsp; ' . tep_draw_hidden_field('store_brand_image_existing' . $languages[$i]['id'], $store_brand_info['store_brand_image']) . tep_draw_checkbox_field('delete_image' . $languages[$i]['id'],'yes') . '&nbsp;' .  DELETE_STORE_BRANDING_COMPANY_LOGO;?></td>
          </tr>
          <?php
          }
          ?>
          <tr>
            <td><?php echo STORE_BRAND_BRANDING_COMPANY_NAME; ?></td>
            <td><?php echo tep_draw_input_field('store_brand_name' . $languages[$i]['id'], $store_brand_info['store_brand_name'],'class="form-control"'); ?></td>
          </tr>
          <tr>
            <td><?php echo STORE_BRAND_BRANDING_SLOGAN; ?></td>
            <td><?php echo tep_draw_input_field('store_brand_slogan' . $languages[$i]['id'], $store_brand_info['store_brand_slogan'],'class="form-control"'); ?></td>
          </tr>
          <tr>
            <td><?php echo STORE_BRAND_BRANDING_SUPPORT_EMAIL; ?></td>
            <td><?php echo tep_draw_input_field('store_brand_support_email' . $languages[$i]['id'],$store_brand_info['store_brand_support_email'],'class="form-control"'); ?></td>
          </tr>
          <tr>
            <td><?php echo STORE_BRAND_BRANDING_SUPPORT_PHONE; ?></td>
            <td><?php echo tep_draw_input_field('store_brand_support_phone' . $languages[$i]['id'],$store_brand_info['store_brand_support_phone'],'class="form-control"'); ?></td>
          </tr>

          <tr>
            <td><?php echo STORE_BRAND_COMPANY_FAVICON; ?></td>
            <td><?php echo tep_draw_file_field('store_brand_favicon' . $languages[$i]['id']);  if (tep_not_null($store_brand_info['store_brand_favicon']) && file_exists( DIR_FS_LOGO . $store_brand_info['store_brand_favicon'] )) { echo '<br>' . DIR_FS_LOGO . $store_brand_info['store_brand_favicon']; }?>
            </td>
          </tr>
          <?php
          if (tep_not_null($store_brand_info['store_brand_favicon']) ) {
          ?>
          <tr>
            <td></td>
            <td valign="middle"><?php
            if( file_exists( DIR_FS_LOGO . $store_brand_info['store_brand_favicon'] )) {
                echo tep_image(DIR_WS_LOGO . $store_brand_info['store_brand_favicon']) ;
            } else {
                echo '<span class="errorText">' . BRANDING_ERROR_FAVICON_MISSING .'</span>';
            }
            echo ' &nbsp; ' . tep_draw_hidden_field('store_brand_favicon_existing' . $languages[$i]['id'], $store_brand_info['store_brand_favicon']) . tep_draw_checkbox_field('delete_favicon' . $languages[$i]['id'],'yes') . '&nbsp;' .  DELETE_STORE_BRANDING_COMPANY_FAVICON;?></td>
          </tr>
          <?php
          }
          ?>
          <tr>
            <td class="main"><?php echo STORE_BRAND_OG_IMGE; ?></td>
            <td class="main"><?php echo tep_draw_file_field('store_og_image' . $languages[$i]['id']);  if (tep_not_null($store_brand_info['store_og_image']) && file_exists( DIR_FS_LOGO . $store_brand_info['store_og_image'] )) { echo '<br>' . DIR_FS_LOGO . $store_brand_info['store_og_image']; }?>
            </td>
          </tr>
          <?php
          if (tep_not_null($store_brand_info['store_og_image']) ) {
          ?>
          <tr>
            <td class="main"></td>
            <td class="main" valign="middle"><?php
            if( file_exists( DIR_FS_LOGO . $store_brand_info['store_og_image'] )) {
                echo tep_image(DIR_WS_LOGO . $store_brand_info['store_og_image']) ;
            } else {
                echo '<span class="errorText">' . BRANDING_ERROR_OG_IMAGE_MISSING .'</span>';
            }
            echo ' &nbsp; ' . tep_draw_hidden_field('store_og_image_existing' . $languages[$i]['id'], $store_brand_info['store_og_image']) . tep_draw_checkbox_field('delete_store_og_image' . $languages[$i]['id'],'yes') . '&nbsp;' .  DELETE_STORE_BRANDING_OG_IMAGE;?></td>
          </tr>
          <?php
          }
          ?>

          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_ADDRESS; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('store_brand_address' . $languages[$i]['id'],true, 40, 10, $store_brand_info['store_brand_address']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_CUSTOM_CSS; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('custom_css' . $languages[$i]['id'],true, 40, 10, $store_brand_info['custom_css']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_FACEBOOK_LINK; ?></td>
            <td class="main"><?php echo tep_draw_input_field('facebook_link' . $languages[$i]['id'],$store_brand_info['facebook_link']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_TWITTER_LINK; ?></td>
            <td class="main"><?php echo tep_draw_input_field('twitter_link' . $languages[$i]['id'],$store_brand_info['twitter_link']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_PINTEREST_LINK; ?></td>
            <td class="main"><?php echo tep_draw_input_field('pinterest_link' . $languages[$i]['id'],$store_brand_info['pinterest_link']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_GOOGLEPLUS_LINK; ?></td>
            <td class="main"><?php echo tep_draw_input_field('google_link' . $languages[$i]['id'],$store_brand_info['google_link']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_YOUTUBE_LINK; ?></td>
            <td class="main"><?php echo tep_draw_input_field('youtube_link' . $languages[$i]['id'],$store_brand_info['youtube_link']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_LINKEDIN_LINK; ?></td>
            <td class="main"><?php echo tep_draw_input_field('linkedin_link' . $languages[$i]['id'],$store_brand_info['linkedin_link']); ?></td>
          </tr>

          </table>


  </td>
  </tr>
</table>
                    </div>
                    <?php
} //end language loop
?>
                  </div>
                  <script type="text/javascript">
         //<![CDATA[
         setupAllTabs();
         //]]>
         </script>
                </td>
              </tr>
              <tr>
                <td align="right" colspan="2"><?php
        if (isset($_GET[tep_session_name()])) {
          echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
        }
if (!$error) {
  echo tep_image_submit('button_update.gif', IMAGE_UPDATE_STORE_BRANDING);
}
?></td>
              </tr>
            </table>
            </form>
          </td>
        </tr>
        <tr>
          <td><?php tep_draw_separator('pixel_trans.gif','100%','10');?></td>
        </tr>
      </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>  </div></div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
