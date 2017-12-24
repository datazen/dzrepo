<?php
/*
  $Id: header_tags_fill_tags.php,v 1.0 2005/08/25
  Originally Created by: Jack York - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 
  require('includes/application_top.php'); 
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_HEADER_TAGS_CONTROLLER);
 
  /****************** READ IN FORM DATA ******************/
  $categories_fill = isset($_POST['group1']) ? $_POST['group1'] : '';
  $manufacturers_fill = isset($_POST['group2']) ? $_POST['group2'] : '';
  $products_fill = isset($_POST['group3']) ? $_POST['group3'] : '';
  $productsMetaDesc = isset($_POST['group4']) ? $_POST['group4'] : '';
  $productsMetaDescLength = isset($_POST['fillMetaDescrlength']) ? $_POST['fillMetaDescrlength'] : '';
 
  $checkedCats = array();
  $checkedManuf = array();
  $checkedProds = array();
  $checkedMetaDesc = array();
  
  $languages = tep_get_languages();
  $languages_array = array();
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['id'], // $i + 1, 
                               'text' => $languages[$i]['name']);
  }
  $langID = $languages_id; 
  $updateDB = false;
  $updateTextCat = '';
  $updateTextManuf = '';
  $updateTextProd = '';
    
  /****************** FILL THE CATEGORIES ******************/
   
  if ($categories_fill != '')
  {
    $langID = $_POST['fill_language'];
    
    if ($categories_fill == 'none') 
    {
       $checkedCats['none'] = 'Checked';
    }
    else
    { 
      $categories_tags_query = tep_db_query("select categories_name, categories_id, categories_htc_title_tag, categories_htc_desc_tag, categories_htc_keywords_tag, language_id from  " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . $langID . "'");
      while ($categories_tags = tep_db_fetch_array($categories_tags_query))
      {
        $updateDB = false;
        
        if ($categories_fill == 'empty')
        {
           if (! tep_not_null($categories_tags['categories_htc_title_tag']))
           {
             $updateDB = true;
             $updateTextCat = 'Empty Category tags have been filled.';
           }  
           $checkedCats['empty'] = 'Checked';
        }
        else if ($categories_fill == 'full')
        {
           $updateDB = true;
           $updateTextCat = 'All Category tags have been filled.';
           $checkedCats['full'] = 'Checked';
        }
        else      //assume clear all
        {
           tep_db_query("update " . TABLE_CATEGORIES_DESCRIPTION . " set categories_htc_title_tag='', categories_htc_desc_tag = '', categories_htc_keywords_tag = '' where categories_id = '" . $categories_tags['categories_id']."' and language_id  = '" . $langID . "'");
           $updateTextCat = 'All Category tags have been cleared.';
           $checkedCats['clear'] = 'Checked';
        }      
             
        if ($updateDB)
          tep_db_query("update " . TABLE_CATEGORIES_DESCRIPTION . " set categories_htc_title_tag='".addslashes($categories_tags['categories_name'])."', categories_htc_desc_tag = '". addslashes($categories_tags['categories_name'])."', categories_htc_keywords_tag = '". addslashes($categories_tags['categories_name']) . "' where categories_id = '" . $categories_tags['categories_id']."' and language_id  = '" . $langID . "'");
      }
    }
  }
  else
    $checkedCats['none'] = 'Checked';
   
  /****************** FILL THE MANUFACTURERS ******************/
   
  if ($manufacturers_fill != '')
  {
    $langID = $_POST['fill_language'];
    
    if ($manufacturers_fill == 'none') 
    {
       $checkedManuf['none'] = 'Checked';
    }
    else
    { 
      $manufacturers_tags_query = tep_db_query("select m.manufacturers_name, m.manufacturers_id, mi.languages_id, mi.manufacturers_htc_title_tag, mi.manufacturers_htc_desc_tag, mi.manufacturers_htc_keywords_tag from " . TABLE_MANUFACTURERS . " m, " . TABLE_MANUFACTURERS_INFO . " mi where mi.languages_id = '" . $langID . "'");
      while ($manufacturers_tags = tep_db_fetch_array($manufacturers_tags_query))
      {
        $updateDB = false;
        
        if ($manufacturers_fill == 'empty')
        {
           if (! tep_not_null($manufacturers_tags['manufacturers_htc_title_tag']))
           {
             $updateDB = true;
             $updateTextManuf = 'Empty Manufacturers tags have been filled.';
           }  
           $checkedManuf['empty'] = 'Checked';
        }
        else if ($manufacturers_fill == 'full')
        {
           $updateDB = true;
           $updateTextManuf = 'All Manufacturers tags have been filled.';
           $checkedManuf['full'] = 'Checked';
        }
        else      //assume clear all
        {
           tep_db_query("update " . TABLE_MANUFACTURERS_INFO . " set manufacturers_htc_title_tag='', manufacturers_htc_desc_tag = '', manufacturers_htc_keywords_tag = '' where manufacturers_id = '" . $manufacturers_tags['manufacturers_id']."' and languages_id  = '" . $langID . "'");
           $updateTextManuf = 'All Manufacturers tags have been cleared.';
           $checkedManuf['clear'] = 'Checked';
        }      
             
        if ($updateDB)
          tep_db_query("update " . TABLE_MANUFACTURERS_INFO . " set manufacturers_htc_title_tag='".addslashes($manufacturers_tags['manufacturers_name'])."', manufacturers_htc_desc_tag = '". addslashes($manufacturers_tags['manufacturers_name'])."', manufacturers_htc_keywords_tag = '". addslashes($manufacturers_tags['manufacturers_name']) . "' where manufacturers_id = '" . $manufacturers_tags['manufacturers_id']."' and languages_id  = '" . $langID . "'");
      }
    }
  }
  else
    $checkedManuf['none'] = 'Checked';
       
  /****************** FILL THE PRODUCTS ******************/  
  
  if ($products_fill != '')
  {
    $langID = $_POST['fill_language'];
    
    if ($products_fill == 'none') 
    {
       $checkedProds['none'] = 'Checked';
    }
    else
    { 
      $products_tags_query = tep_db_query("select products_name, products_description, products_id, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, language_id from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . $langID . "'");
      while ($products_tags = tep_db_fetch_array($products_tags_query))
      {
        $updateDB = false;
        
        if ($products_fill == 'empty')
        {
          if (! tep_not_null($products_tags['products_head_title_tag']))
          {
            $updateDB = true;
            $updateTextProd = 'Empty Product tags have been filled.';
          }  
          $checkedProds['empty'] = 'Checked';
        }
        else if ($products_fill == 'full')
        {
          $updateDB = true;
          $updateTextProd = 'All Product tags have been filled.';
          $checkedProds['full'] = 'Checked';
        }
        else      //assume clear all
        {
          tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_head_title_tag='', products_head_desc_tag = '', products_head_keywords_tag =  '' where products_id = '" . $products_tags['products_id'] . "' and language_id='". $langID ."'");
          $updateTextProd = 'All Product tags have been cleared.';
          $checkedProds['clear'] = 'Checked';
        }
               
        if ($updateDB)
        {
          if ($productsMetaDesc == 'fillMetaDesc_yes')          //fill the description with all or part of the 
          {                                                     //product description
            if (! empty($products_tags['products_description']))
            {
              if (isset($productsMetaDescLength) && (int)$productsMetaDescLength > 3 && (int)$productsMetaDescLength < strlen($products_tags['products_description']))
                $desc = substr($products_tags['products_description'], 0, (int)$productsMetaDescLength);
              else                                              //length not entered or too small    
                $desc = $products_tags['products_description']; //so use the whole description
            }   
            else
              $desc = $products_tags['products_name'];  

            $checkedMetaDesc['no'] = '';
            $checkedMetaDesc['yes'] = 'Checked';
          }  
          else
          {        
            $desc = $products_tags['products_name'];           
            $checkedMetaDesc['no'] = 'Checked';
            $checkedMetaDesc['yes'] = '';
          }  

          tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_head_title_tag='".addslashes($products_tags['products_name'])."', products_head_desc_tag = '". addslashes(strip_tags($desc))."', products_head_keywords_tag =  '" . addslashes($products_tags['products_name']) . "' where products_id = '" . $products_tags['products_id'] . "' and language_id='". $langID ."'");
        } 
      }  
    }
  }
  else
  { 
    $checkedProds['none'] = 'Checked';
    $checkedMetaDesc['no'] = 'Checked';
    $checkedMetaDesc['yes'] = '';
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
      <h1 class="page-header"><?php echo HEADING_TITLE_FILL_TAGS; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">

     <tr>
      <td class="HTC_subHead"><?php echo TEXT_FILL_TAGS; ?></td>
     </tr>
     
     <!-- Begin of Header Tags -->      
     
     <tr>
      <td align="right"><?php echo tep_draw_form('header_tags', FILENAME_HEADER_TAGS_FILL_TAGS, '', 'post') . tep_draw_hidden_field('action', 'process'); ?></td>
       <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
     </tr>
     <tr>
      <td><table width="100%" border="0">
       <tr>
        <td class="main" width="12%"><?php echo TEXT_LANGUAGE; ?>&nbsp;</td>
        <td><?php echo tep_draw_pull_down_menu('fill_language', $languages_array, $langID);?></td>
       </tr>
      </table> 

      <table width="80%" border="0">
       <tr class="main"> 
        <td><?php echo TEXT_FILL_WITH_DESCIPTION; ?></td>
        <td align=left><INPUT TYPE="radio" NAME="group4" VALUE="fillMetaDesc_yes"<?php echo $checkedMetaDesc['yes']; ?>> <?php echo TEXT_YES; ?>&nbsp;</td>
        <td align=left><INPUT TYPE="radio" NAME="group4" VALUE="fillmetaDesc_no"<?php echo $checkedMetaDesc['no']; ?>>&nbsp;<?php echo TEXT_NO; ?></td>
        <td align="right" class="main"><?php echo TEXT_LIMIT_TO . '&nbsp;' . tep_draw_input_field('fillMetaDescrlength', '', 'maxlength="255", size="5"', false) . '&nbsp;' . TEXT_CHARACTERS; ?> </td>
       </tr>
      </table></td> 
     </tr>     
       <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
       </tr>
       
       <tr>
        <td><table border="0" width="50%">
         <tr class="smallText">
          <th><?php echo HEADING_TITLE_CONTROLLER_CATEGORIES; ?></th>
          <th><?php echo HEADING_TITLE_CONTROLLER_MANUFACTURERS; ?></th>          
          <th><?php echo HEADING_TITLE_CONTROLLER_PRODUCTS; ?></th>
         </tr> 
         <tr class="smallText">          
          <td align=left><INPUT TYPE="radio" NAME="group1" VALUE="none" <?php echo $checkedCats['none']; ?>> <?php echo HEADING_TITLE_CONTROLLER_SKIPALL; ?></td>
          <td align=left><INPUT TYPE="radio" NAME="group2" VALUE="none" <?php echo $checkedManuf['none']; ?>> <?php echo HEADING_TITLE_CONTROLLER_SKIPALL; ?></td>
          <td align=left><INPUT TYPE="radio" NAME="group3" VALUE="none" <?php echo $checkedProds['none']; ?>> <?php echo HEADING_TITLE_CONTROLLER_SKIPALL; ?></td>
         </tr>
         <tr class="smallText"> 
          <td align=left><INPUT TYPE="radio" NAME="group1" VALUE="empty"<?php echo $checkedCats['empty']; ?> > <?php echo HEADING_TITLE_CONTROLLER_FILLONLY; ?></td>
          <td align=left><INPUT TYPE="radio" NAME="group2" VALUE="empty" <?php echo $checkedManuf['empty']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLONLY; ?></td>
          <td align=left><INPUT TYPE="radio" NAME="group3" VALUE="empty" <?php echo $checkedProds['empty']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLONLY; ?></td>
         </tr>
         <tr class="smallText"> 
          <td align=left><INPUT TYPE="radio" NAME="group1" VALUE="full" <?php echo $checkedCats['full']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLALL; ?></td>
          <td align=left><INPUT TYPE="radio" NAME="group2" VALUE="full" <?php echo $checkedManuf['full']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLALL; ?></td>
          <td align=left><INPUT TYPE="radio" NAME="group3" VALUE="full" <?php echo $checkedProds['full']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLALL; ?></td>
         </tr>
         <tr class="smallText"> 
          <td align=left><INPUT TYPE="radio" NAME="group1" VALUE="clear" <?php echo $checkedCats['clear']; ?>> <?php echo HEADING_TITLE_CONTROLLER_CLEARALL; ?></td>
          <td align=left><INPUT TYPE="radio" NAME="group2" VALUE="clear" <?php echo $checkedManuf['clear']; ?>> <?php echo HEADING_TITLE_CONTROLLER_CLEARALL; ?></td>
          <td align=left><INPUT TYPE="radio" NAME="group3" VALUE="clear" <?php echo $checkedProds['clear']; ?>> <?php echo HEADING_TITLE_CONTROLLER_CLEARALL; ?></td>
         </tr>
        </table></td>
       </tr> 
       
       <tr>
        <td><table border="0" width="40%">
         <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
         </tr>
         <tr> 
          <td align="center"><?php echo (tep_image_submit('button_update.gif', IMAGE_UPDATE) ) . ' <a href="' . tep_href_link(FILENAME_HEADER_TAGS_ENGLISH, tep_get_all_get_params(array('action'))) .'">' . '</a>'; ?></td>
         </tr>
         <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
         </tr>         
         <?php if (tep_not_null($updateTextCat)) { ?>
          <tr>
           <td class="HTC_subHead"><?php echo $updateTextCat; ?></td>
          </tr> 
          <?php }  
           if (tep_not_null($updateTextManuf)) { ?>
          <tr>
           <td class="HTC_subHead"><?php echo $updateTextManuf; ?></td>
          </tr>
         <?php } 
           if (tep_not_null($updateTextProd)) { ?>
          <tr>
           <td class="HTC_subHead"><?php echo $updateTextProd; ?></td>
          </tr>
         <?php } ?> 
        </table></td>
       </tr>
      </form>
      </td>
     </tr>
     <!-- end of Header Tags -->

         
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
