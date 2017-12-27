<?php
/*
  $Id: box.php,v 1.1.1.1 2004/03/04 23:39:44 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Example usage:

  $heading = array();
  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

  $contents = array();
  $contents[] = array('text'  => SOME_TEXT);

  $box = new box;
  echo $box->infoBox($heading, $contents);
*/

  class box extends tableBlock {
    function box() {
      $this->heading = array();
      $this->contents = array();
    }

    function showSidebar($heading, $contents) {
       $output  = '<div class="sidebar-container p-4">';
       $output .= '  <div class="sidebar-heading">';
       $output .= '    <span>' . $heading[0]['text'] . '</span>';
       $output .= '  </div>';
       foreach ($contents as $key => $value) {
         $align = 'text-left';
         if (isset($value['align'])) {
            if ($value['align'] == 'center') $align = 'text-center';
            if ($value['align'] == 'right') $align = 'text-right';
            if ($value['align'] == 'left') $align = 'text-left';
         }

         if (isset($value['form'])) {
           $output .= $value['form'];
         } else {
           $output .= '<div class="sidebar-row ' . $align . '">' . $value['text'] . '</div>';
         }
       }

       return $output;
    }

    function infoBox($heading, $contents) {
      $this->table_parameters = '';
      $this->table_row_parameters = 'class="infoBoxHeading"';
      $this->table_data_parameters = 'class="infoBoxHeading"';
      $this->heading = $this->tableBlock($heading);

      $this->table_parameters = '';
      $this->table_row_parameters = '';
      $this->table_data_parameters = 'class="infoBoxContent"';
      $this->contents = $this->tableBlock($contents);

      return '<table class="info-box-table" border="0" cellpadding="0" cellspacing="0" width="100%">' .
             '  <tr>' .
             '    <td class="info-box-head">' . $this->heading . '</td>' .
             '  </tr>' .
             '  <tr>' .
             '    <td class="info-box-body px-2">' . $this->contents . '</td>' .
             '  </tr>' .
             '</table>';
    }

    function menuBox($heading, $contents) {
      global $selected;              // add for dhtml_menu
   // populate $selected variable
    //trim everthing left selected box
      $selected1 = substr(strstr($heading[0]['link'], 'selected_box='), 13);
      //if sid is present remove it
      $selected = str_replace(strstr($selected1, '&osCAdminID='), '', $selected1 );
      $dhtml_contents = $contents[0]['text'];

      //$change_style = array ('<br>'=>' ','<br>'=>' ', 'a href='=> 'a class="menuItem" href=','class="menuBoxContentLink"'=>' ');
      $change_style = array ('<br>'=>' ','<br>'=>' ','class="menuBoxContentLink"'=>'', '<nobr>' => '', '</nobr>' => '');
      $dhtml_contents = strtr($dhtml_contents,$change_style);
      $dhtml_contents = '<ul id="'. $selected . 'Menu" class="sub-menu">' . "\n" .  $dhtml_contents . '</ul></li>';
      return $dhtml_contents;
    }
    
    function menuBox2($heading, $contents) {
        global $selected;              // add for dhtml_menu
   
        $this->table_data_parameters = 'class="menuBoxHeading1"';
        if ($heading[0]['link']) {
          $this->table_data_parameters .= ' onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . $heading[0]['link'] . '\'"';
          $heading[0]['text'] = '<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>';
        } else {
          $heading[0]['text'] = '' . $heading[0]['text'] . '';
        }
        $this->heading = $this->tableBlock($heading);
        $this->table_data_parameters = 'class="menuBoxContent1"';
        $this->contents = $this->tableBlock($contents);
        return $this->heading . $this->contents;
     }   
  }
?>