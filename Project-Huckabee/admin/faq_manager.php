<?php
/*
  $Id: faq_manager.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/faq.php');
require(DIR_WS_FUNCTIONS . '/faq.php');
// RCI code start
echo $cre_RCI->get('global', 'top', false);
echo $cre_RCI->get('faqmanager', 'top', false); 
// RCI code eof  
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
      <h1 class="page-header"><?php echo 'FAQ Manager'; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table">
        <?php
        $v_order  = isset($_POST['v_order']) ? $_POST['v_order'] : '';
        $answer   = isset($_POST['answer']) ? $_POST['answer'] : '';
        $question = isset($_POST['question']) ? $_POST['question'] : '';

        $faq_action = (isset($_GET['faq_action'])) ? $_GET['faq_action'] : '';
        
$faq_id =  0;
if (isset($_GET['faq_id'])) {
  $faq_id = (int)$_GET['faq_id'];
} else if (isset($_POST['faq_id'])) {
  $faq_id = (int)$_POST['faq_id'];
}
        switch($faq_action) {
          case "Added":
            $data = browse_faq($language, $_GET);
            $no = 1;
            if (sizeof($data) > 0) {
              while (list($key, $val) = each($data)) {
                $no++;
              }
            }
            $title = FAQ_ADD . ' #' . $no;
            echo tep_draw_form('',FILENAME_FAQ_MANAGER, 'faq_action=AddSure');
            include('faq_form.php');
            break;
          case "AddSure":                        
            function add_faq ($data) {
              $query = "INSERT INTO " . TABLE_FAQ . " VALUES(null, '$data[visible]', '$data[v_order]', '$data[question]', '$data[answer]', NOW(''),'$data[faq_language]')";
              tep_db_query($query);
              $fID = tep_db_insert_id();
              tep_db_query("insert into " . TABLE_FAQ_TO_CATEGORIES . " (faq_id, categories_id) values ('" . (int)$fID . "', '" . (int)$data['faq_category'] . "')");
              unset($_POST);
            }
             if (isset($_POST['v_order']) && isset($_POST['answer']) && isset($_POST['question'])) {
              if ( (int)$_POST['v_order'] ) {
                add_faq($_POST);
                $data = browse_faq($language,$_GET);
                $title = FAQ_CREATED . ' ' . FAQ_ADD_QUEUE . ' ' . $v_order;
                include('faq_list.php');
              } else {
                $error = 20;
              }
            } else {
              $error = 80;
            }
            break;
          case "Edit":
           if (isset($_GET['faq_id'])) {
              $edit = read_data($_GET['faq_id']);
              $data = browse_faq($language,$_GET);
              $button = array("Update");
              $title = FAQ_EDIT_ID . ' ' . $_GET['faq_id'];
              echo tep_draw_form('',FILENAME_FAQ_MANAGER, 'faq_action=Update');
              echo tep_draw_hidden_field('faq_id', $_GET['faq_id']);
              include('faq_form.php');
            } else {
              $error = 80;
            }
            break;
          case "Update":
            function update_faq ($data) {
              tep_db_query("UPDATE " . TABLE_FAQ . " SET question='$data[question]', answer='$data[answer]', visible='$data[visible]', v_order=$data[v_order], date = now() WHERE faq_id=$data[faq_id]");
              $category_check_query = tep_db_query("select categories_id from " . TABLE_FAQ_TO_CATEGORIES . " where faq_id = '" . (int)$data['faq_id'] . "'");
              if (tep_db_fetch_array($category_check_query)) { // if category exists
                // update category info
                tep_db_query("update " . TABLE_FAQ_TO_CATEGORIES . " set categories_id = '" . (int)$data['faq_category'] . "' where faq_id = '" . (int)$data['faq_id'] . "'");
              } else { 
                tep_db_query("insert into " . TABLE_FAQ_TO_CATEGORIES . " (faq_id, categories_id) values ('" . (int)$data['faq_id'] . "', '" . (int)$data['faq_category'] . "')");
              }
            }
           

            if (isset($_POST['faq_id']) && isset($_POST['question']) && isset($_POST['answer']) && isset($_POST['v_order'])) {
              if ( (int)$_POST['v_order'] ) {
                update_faq($_POST);
                $data = browse_faq($language,$_GET);
                $title = FAQ_UPDATED_ID . ' ' . $_POST['faq_id'];
                include('faq_list.php');
              } else {
                $error = 20;
              } 
            } else {
              $error = 80;
            }
            break;
          case 'Visible':
            function tep_set_faq_visible($faq_id) {
              if ($_GET['visible'] == 1) {
                return tep_db_query("update " . TABLE_FAQ . " set visible = '0', date = now() where faq_id = '" . $faq_id . "'");
              } else{
                return tep_db_query("update " . TABLE_FAQ . " set visible = '1', date = now() where faq_id = '" . $faq_id . "'");
              } 
            }
            tep_set_faq_visible($_GET['faq_id'], $_GET);
            $data = browse_faq($language,$_GET);
            if ($_GET['visible'] == 1) {
              $vivod = FAQ_DEACTIVATED_ID;
            } else {
              $vivod = FAQ_ACTIVATED_ID;
            }
            $title = $vivod . ' ' . $_GET['faq_id'];
            include('faq_list.php');
            break;
          case "Delete":
            
            if (isset($_GET['faq_id'])) {
              $delete = read_data($_GET['faq_id']);
              $data = browse_faq($language,$_GET);
              $title = FAQ_DELETE_CONFITMATION_ID . ' ' . $_GET['faq_id'];
              echo '
              <tr class="pageHeading"><td>' . $title . '</td></tr>
              <tr><td class="dataTableContent"><b>' . FAQ_QUESTION . ':</b></td></tr>
              <tr><td class="dataTableContent">' . $delete[question] . '</td></tr>
              <tr><td class="dataTableContent"><b>' . FAQ_ANSWER . ':</b></td></tr>
              <tr><td class="dataTableContent">' . $delete[answer] . '</td></tr>
              <tr><td align="right">
              ';
              echo tep_draw_form('',FILENAME_FAQ_MANAGER, 'faq_action=DelSure&faq_id='.$_GET['faq_id']);
              echo tep_draw_hidden_field('faq_id', $_GET['faq_id']);
              echo tep_image_submit('button_delete.gif', IMAGE_DELETE);
              echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
              echo '</form></td></tr>';
            } else {
              $error = 80;
            }
            break;
          case "DelSure":
            function delete_faq ($faq_id) {
              tep_db_query("DELETE FROM " . TABLE_FAQ . " WHERE faq_id=$faq_id");
              tep_db_query("delete from " . TABLE_FAQ_TO_CATEGORIES . " where faq_id = '" . (int)$faq_id . "'");
            }
           if (isset($_GET['faq_id'])) {
              delete_faq($faq_id);
              $data = browse_faq($language,$_GET);
              $title = FAQ_DELETED_ID . ' ' . $_GET['faq_id'];
              include('faq_list.php');
            } else {
              $error = 80;
            }
            break;
          default:
            $data = browse_faq($language,$_GET);
            $title = FAQ_MANAGER;
            include('faq_list.php');
            break;
        }
        if (!isset($error)) {
          $error = false;
        }
        if ($error) {
          $content = error_message($error);
          echo $content;
          $data = browse_faq($language,$_GET);
          $no = 1;
          if (sizeof($data) > 0) {
            while (list($key, $val) = each($data)) {
              $no++; 
            }
          }
          $title = FAQ_ADD_QUEUE . ' ' . $no;
          echo tep_draw_form('',FILENAME_FAQ_MANAGER, 'faq_action=AddSure');
          include('faq_form.php');
        }
        // RCI code start
        echo $cre_RCI->get('faqmanager', 'bottom'); 
        echo $cre_RCI->get('global', 'bottom');                                      
        // RCI code eof
        ?>
      </table>
    </div></div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>