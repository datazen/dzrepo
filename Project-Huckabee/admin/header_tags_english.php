<?php
/*
  $Id: header_tags_controller.php,v 1.0 2005/04/08 22:50:52 hpdl Exp $
  Originally Created by: Jack York - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 
  require('includes/application_top.php');
  require_once('includes/functions/header_tags.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_HEADER_TAGS_CONTROLLER);
  $filename = DIR_FS_CATALOG. DIR_WS_LANGUAGES . $language . '/header_tags.php';
  if (GetPermissions(DIR_FS_CATALOG_IMAGES) != Getpermissions($filename))
   $messageStack->add("Permissions settings for the $filename file appear to be incorrect. Change to " . Getpermissions(DIR_WS_IMAGES));  
   
  $formActive = false;
  
  /****************** READ IN FORM DATA ******************/
  $action = (isset($_POST['action']) ? $_POST['action'] : '');
  
  if (tep_not_null($action)) 
  {
      $main = array();
      $main['title'] = htmlspecialchars($_POST['main_title']);  //read in the knowns
      $main['desc'] = htmlspecialchars($_POST['main_desc']);
      $main['keyword'] = htmlspecialchars($_POST['main_keyword']);

      $formActive = true;
      $args_new = array();
      $c = 0;
      $pageCount = TotalPages($filename);
      for ($t = 0, $c = 0; $t < $pageCount; ++$t, $c += 3) //read in the unknowns
      {
         $args_new['title'][$t] = htmlspecialchars(stripslashes($_POST[$c]), ENT_QUOTES);
         $args_new['desc'][$t] = htmlspecialchars(stripslashes($_POST[$c+1]), ENT_QUOTES);
         $args_new['keyword'][$t] = htmlspecialchars(stripslashes($_POST[$c+2]), ENT_QUOTES);
        
         $boxID = sprintf("HTTA_%d", $t); 
         $args_new['HTTA'][$t] = $_POST[$boxID];
         $boxID = sprintf("HTDA_%d", $t); 
         $args_new['HTDA'][$t] = $_POST[$boxID];
         $boxID = sprintf("HTKA_%d", $t); 
         $args_new['HTKA'][$t] = $_POST[$boxID];
         $boxID = sprintf("HTCA_%d", $t); 
         $args_new['HTCA'][$t] = $_POST[$boxID];
         $boxID = sprintf("HTPA_%d", $t); 
         $args_new['HTPA'][$t] = $_POST[$boxID];
      }   
  }

  /***************** READ IN DISK FILE ******************/
  $main_title = '';
  $main_desc = '';
  $main_key = '';
  $sections = array();      //used for unknown titles
  $args = array();          //used for unknown titles
  $ctr = 0;                 //used for unknown titles
  $findTitles = false;      //used for unknown titles
  $fp = file($filename);  

  for ($idx = 0; $idx < count($fp); ++$idx)
  { 
      if (strpos($fp[$idx], "define('HEAD_TITLE_TAG_ALL'") !== FALSE)
      {
//      echo 'SEND TITLE '.$main_title.' '. ' - '.$main['title'].' - '.$formActive.'<br>';
          $main_title = GetMainArgument($fp[$idx], $main['title'], $formActive);
      } 
      else if (strpos($fp[$idx], "define('HEAD_DESC_TAG_ALL'") !== FALSE)
      {
     // echo 'SEND DESC '.$main['desc']. ' '.$formActive.'<br>';
          $main_desc = GetMainArgument($fp[$idx], $main['desc'], $formActive);
      } 
      else if (strpos($fp[$idx], "define('HEAD_KEY_TAG_ALL'") !== FALSE)
      { 
          $main_key = GetMainArgument($fp[$idx], $main['keyword'], $formActive);
          $findTitles = true;  //enable next section            
      } 
      else if ($findTitles)
      {
          if (($pos = strpos($fp[$idx], '.php')) !== FALSE) //get the section titles
          {
              $sections['titles'][$ctr] = GetSectionName($fp[$idx]);   
              $ctr++; 
          }
          else                                   //get the rest of the items in this section
          {
              if (! IsComment($fp[$idx])) // && tep_not_null($fp[$idx]))
              {
                  $c = $ctr - 1;
                  if (IsTitleSwitch($fp[$idx]))
                  {
                     if ($formActive)
                     {
                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTTA'][$c]);
                     }                      
                     $args['title_switch'][$c] = GetSwitchSetting($fp[$idx]);
                     $args['title_switch_name'][$c] = sprintf("HTTA_%d",$c);                     
                  }
                  else if (IsDescriptionSwitch($fp[$idx]))
                  {
                     if ($formActive)
                     {
                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTDA'][$c]);
                     } 
                     $args['desc_switch'][$c] = GetSwitchSetting($fp[$idx]);
                     $args['desc_switch_name'][$c] = sprintf("HTDA_%d",$c);  
                  }
                  if (IsKeywordSwitch($fp[$idx]))
                  {
                     if ($formActive)
                     {
                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTKA'][$c]);
                     }   
                     $args['keyword_switch'][$c] = GetSwitchSetting($fp[$idx]);
                     $args['keyword_switch_name'][$c] = sprintf("HTKA_%d",$c);
                  }
                  else if (IsCatSwitch($fp[$idx]))
                  {
                     if ($formActive)
                     {
                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTCA'][$c]); 
                     }  
                     $args['cat_switch'][$c] = GetSwitchSetting($fp[$idx]);
                     $args['cat_switch_name'][$c] = sprintf("HTCA_%d",$c);
                  }
                  else if (IsCatProdSwitch($fp[$idx]))     //special case - for product_info only
                  {
                     if ($formActive)
                     {
                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTPA'][$c]); 
                     }  
                     $args['catprod_switch'][$c] = GetSwitchSetting($fp[$idx]);
                     $args['catprod_switch_name'][$c] = sprintf("HTPA_%d",$c);
                  }
                  else if (IsTitleTag($fp[$idx]))
                  {
                     $args['title'][$c] = GetArgument($fp[$idx], $args_new['title'][$c], $formActive);
                  } 
                  else if (IsDescriptionTag($fp[$idx])) 
                  {
                     $args['desc'][$c] = GetArgument($fp[$idx], $args_new['desc'][$c], $formActive);                   
                  }
                  else if (IsKeywordTag($fp[$idx])) 
                  {
                    $args['keyword'][$c] = GetArgument($fp[$idx], $args_new['keyword'][$c], $formActive);
                  }                                   
              }
          }
      }
  }

  /***************** WRITE THE FILE ******************/
  if ($formActive)
  {      
     WriteHeaderTagsFile($filename, $fp);  
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
      <h1 class="page-header"><?php echo HEADING_TITLE_ENGLISH; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
     <tr>
      <td class="HTC_subHead"><?php echo TEXT_ENGLISH_TAGS; ?></td>
     </tr>
     <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
     </tr>
     
     <!-- Begin of Header Tags -->
     <tr>
      <td align="right"><?php echo tep_draw_form('header_tags', FILENAME_HEADER_TAGS_ENGLISH, '', 'post') . tep_draw_hidden_field('action', 'process'); ?></td>
       <tr>
        <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
         <tr>
          <td class="smallText" width="20%" style="font-weight: bold;"><?php echo HEADING_TITLE_CONTROLLER_DEFAULT_TITLE; ?></td>
          <td class="smallText" ><?php echo tep_draw_input_field('main_title', tep_not_null($main_title) ? $main_title : '', 'maxlength="255", size="60"', false); ?> </td>
         <tr> 
         <tr>
          <td class="smallText" width="20%" style="font-weight: bold;"><?php echo HEADING_TITLE_CONTROLLER_DEFAULT_DESCRIPTION; ?></td>
          <td class="smallText" ><?php echo tep_draw_input_field('main_desc', tep_not_null($main_desc) ? $main_desc : '', 'maxlength="255", size="60"', false); ?> </td>
         <tr> 
         <tr>
          <td class="smallText" width="20%" style="font-weight: bold;"><?php echo HEADING_TITLE_CONTROLLER_DEFAULT_KEYWORDS; ?></td>
          <td class="smallText" ><?php echo tep_draw_input_field('main_keyword', tep_not_null($main_key) ? $main_key : '', 'maxlength="255", size="60"', false); ?> </td>
         <tr> 
         
         <?php for ($i = 0, $id = 0; $i < count($sections['titles']); ++$i, $id += 3) { ?>
         <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
         </tr>         
         <tr>
          <td colspan="3" ><table border="0" width="100%">
         <tr>
          <td colspan="3" class="smallText" width="20%" style="font-weight: bold; color: <?php echo getcolor($sections['titles'][$i]); ?>;"><?php echo $sections['titles'][$i]; ?></td>
          <td class="smallText">HTTA: </td>
          <td align="left"><?php echo tep_draw_checkbox_field($args['title_switch_name'][$i], '', $args['title_switch'][$i], ''); ?> </td>
          <td class="smallText">HTDA: </td>
          <td align="left"><?php echo tep_draw_checkbox_field($args['desc_switch_name'][$i], '', $args['desc_switch'][$i], ''); ?> </td>
          <td class="smallText">HTKA: </td>
          <td align="left"><?php echo tep_draw_checkbox_field($args['keyword_switch_name'][$i], '', $args['keyword_switch'][$i], ''); ?> </td>
          <td class="smallText">HTCA: </td>
          <td align="left"><?php echo tep_draw_checkbox_field($args['cat_switch_name'][$i], '', $args['cat_switch'][$i], ''); ?> </td>
         
          <?php if ($sections['titles'][$i] == "product_info") { ?>
          <td class="smallText">HTPA: </td>
          <td align="left"><?php echo tep_draw_checkbox_field($args['catprod_switch_name'][$i], '', $args['catprod_switch'][$i], ''); ?> </td>
          <?php } ?>
         
          <td width="50%" class="smallText"> <script>document.writeln('<a style="cursor:hand" onclick="javascript:popup=window.open('
                                           + '\'<?php echo tep_href_link('header_tags_popup_help.php'); ?>\',\'popup\','
                                           + '\'scrollbars,resizable,width=520,height=550,left=50,top=50\'); popup.focus(); return false;">'
                                           + '<font color="red"><u><?php echo HEADING_TITLE_CONTROLLER_EXPLAIN; ?></u></font></a>');
         </script> </td>
     
         </tr>
          </table></td>
         </tr>
         
         <tr>
          <td colspan="3" ><table border="0" width="100%">
           <tr>
            <td width="2%">&nbsp;</td>
            <td class="smallText" width="12%"><?php echo HEADING_TITLE_CONTROLLER_TITLE; ?></td>
            <td class="smallText" ><?php echo tep_draw_input_field($id, $args['title'][$i], 'maxlength="255", size="60"', false, 300); ?> </td>
           </tr>
           <tr>
            <td width="2%">&nbsp;</td>
            <td class="smallText" width="12%"><?php echo HEADING_TITLE_CONTROLLER_DESCRIPTION; ?></td>
            <td class="smallText" ><?php echo tep_draw_input_field($id+1, $args['desc'][$i], 'maxlength="255", size="60"', false); ?> </td>
           </tr>
           <tr>
            <td width="2%">&nbsp;</td>
            <td class="smallText" width="12%"><?php echo HEADING_TITLE_CONTROLLER_KEYWORDS; ?></td>
            <td class="smallText" ><?php echo tep_draw_input_field($id+2, $args['keyword'][$i], 'maxlength="255", size="60"', false); ?> </td>
           </tr>
          </table></td>
         </tr>
         <?php } ?> 
        </table>
        </td>
       </tr>  
       <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
       </tr>
       <tr> 
        <td align="center"><?php echo (tep_image_submit('button_update.gif', IMAGE_UPDATE) ) . ' <a href="' . tep_href_link(FILENAME_HEADER_TAGS_ENGLISH, tep_get_all_get_params(array('action'))) .'">' . '</a>'; ?></td>
       </tr>
      </form>
      </td>
     </tr>
     <!-- end of Header Tags -->

         
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table></div></div>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
