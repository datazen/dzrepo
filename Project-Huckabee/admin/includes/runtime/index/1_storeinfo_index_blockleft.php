<?php
/*
  $Id: 1_storeinfo_index_blockleft.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_STORE_INFO_STATUS') && ADMIN_BLOCKS_STORE_INFO_STATUS == 'true'){
  // template check
  $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  $template = tep_db_fetch_array($template_query);
  $store_template = $template['configuration_value'];
  // store status check 
  if (DOWN_FOR_MAINTENANCE == 'false'){
    $store_status = '<font color="#009900">Active</font>';
  } else {
    $store_status = '<font color="#FF0000">Maintenance</font>';
  }
  // language count
  $langcount_query = tep_db_query("select count(languages_id ) as langcnt from " . TABLE_LANGUAGES);
  $langcount = tep_db_fetch_array($langcount_query);
  define('LANGUAGE_COUNT',$langcount['langcnt']);
  // currencies count
  $currcount_query = tep_db_query("select count(currencies_id) as currcnt from " . TABLE_CURRENCIES);
  $currcount = tep_db_fetch_array($currcount_query);
  define('CURRENCIES_COUNT',$currcount['currcnt']);
  // backup count
  if ($handle = @opendir(DIR_FS_BACKUP)) {
    $count = 0;
    //loop through the directory
    $year="1900"; //please dont change this value
    $dayofyear="0"; //please dont change this value
    $lastbackupdate="";
    while (($filename = readdir($handle)) !== false) {
      //evaluate each entry, removing the . & .. entries
      if (($filename != ".") && ($filename != "..")) {
        $fileyear=date("Y", filemtime(DIR_FS_BACKUP.$filename));  
        if($fileyear > $year) {
          $filedayofyear=date("z", filemtime(DIR_FS_BACKUP.$filename));   
          $year=$fileyear;
          $dayofyear=$filedayofyear;
          $lastbackupdate=date("m/d/Y", filemtime(DIR_FS_BACKUP.$filename));
        } elseif($fileyear==$year) {
          $filedayofyear=date("z", filemtime(DIR_FS_BACKUP.$filename));   
          if($filedayofyear > $dayofyear) {     
            $lastbackupdate=date("m/d/Y", filemtime(DIR_FS_BACKUP.$filename));      
            $dayofyear=$filedayofyear;
          } 
        } 
      $count++;
      }
    }
  } else {
    $count=0;$lastbackupdate="";
  }
  define('BACKUP_COUNT',$count);
  define('LAST_BACKUP_DATE',$lastbackupdate);
  ?>
  <!-- begin storeinfo -->
  <div class="col-md-6">
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="table-basic-4">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title"><?php echo BLOCK_TITLE_STORE_INFO; ?></h4>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_NAME . ' : ' . STORE_NAME;?> </li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_STATUS;?> : <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_STATUS;?>', this, event, '250px'); return false"><strong><?php echo $store_status;?></strong></a> </li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_EMAIL . ' : ' . STORE_OWNER_EMAIL_ADDRESS;?> </li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_TEMPLATE . ' : ' . $store_template;?></li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_LANGUAGE . ' : ' . DEFAULT_LANGUAGE.' ('.LANGUAGE_COUNT;?>  Installed) </li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_CURRENCY . ' : ' . DEFAULT_CURRENCY.' ('.CURRENCIES_COUNT;?>  Installed) </li>              
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_BACKUPS.' : '. BACKUP_COUNT;?>  (Latest <?php echo LAST_BACKUP_DATE?>) <a href="<?php echo tep_href_link(FILENAME_BACKUP);?>" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_BACKUP;?>', this, event, '180px'); return false"><font color="#FF0000">[!]</font></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- end storeinfo -->
  <?php
  }
?>