<?php
/*
  $Id: ckeditor.php,v 1.0.0.0 2011/07/20 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/


function tep_load_html_editor() {
  if ((HTML_EDITOR_ENABLE == 'Enable') && (file_exists(DIR_FS_ADMIN . 'editors/ckeditor/ckeditor.js'))){
  echo '<script type="text/javascript" src="editors/ckeditor/ckeditor.js"></script>';
  }else{
  echo '<!-- No editor loaded -->' . "\n";
  echo '<br>Error  HTML editor may be turn off or DIR_FS_ADMIN is incorrect<br>';
  }
} // end tep_insert_html_editor


function tep_insert_html_editor (){
    
}

function tep_insert_ckeditor ( $textarea, $tool_bar_set = 'BLURB', $editor_height = HTML_EDITOR_TINYMCE_HEIGHT ) {
 global $request_type, $extended_valid_elements;
 $mailscripts = array(FILENAME_NEWSLETTERS, FILENAME_MAIL);
 $template_css_path = DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . '/stylesheet.css';
 
 if(substr($textarea, -1, 1) == ','){
     $textarea = substr($textarea,0,-1);
 }
   
     echo '<script type="text/javascript">
            //<![CDATA[
                CKEDITOR.replace( \''.$textarea.'\', { 
                toolbar : \'' . $tool_bar_set . '\',
                height : \'' . $editor_height . '\',
                width : \'' . (defined('HTML_EDITOR_TINYMCE_WIDTH') ? HTML_EDITOR_TINYMCE_WIDTH : '750') . '\',
                extraPlugins : \'stylesheetparser\',
                contentsCss : \''.$template_css_path.'\',
                });

            //]]>
            </script>';
}
?>