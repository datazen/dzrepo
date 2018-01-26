<?php
/*
  $Id: message_stack.php,v 1.1.1.1 2004/03/04 23:40:44 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('general', 'Error: Error 1', 'error');
  $messageStack->add('general', 'Error: Error 2', 'warning');
  if ($messageStack->size('general') > 0) echo $messageStack->output('general');
*/
//Lango Added for template mod: BOF

  class tableBoxMessagestack {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

    // class constructor
    function tableBoxMessagestack($contents, $direct_output = false) {
      $tableBox1_string = '<table class="table" border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox1_string .= ' ' . $this->table_parameters;
      $tableBox1_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox1_string .= $contents[$i]['form'] . "\n";
        $tableBox1_string .= '  <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox1_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox1_string .= ' ' . $contents[$i]['params'];
        $tableBox1_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox1_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox1_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox1_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox1_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox1_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox1_string .= $contents[$i][$x]['form'];
              $tableBox1_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox1_string .= '</form>';
              $tableBox1_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox1_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox1_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox1_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox1_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox1_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox1_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox1_string .= '</form>' . "\n";
      }

      $tableBox1_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox1_string;

      return $tableBox1_string;
    }
  }


  class messageStack extends tableBoxMessagestack {

    // class constructor
    function messageStack() {

      $this->messages = array();
      if (isset($_SESSION['messageToStack'])) {
        for ($i=0, $n=sizeof($_SESSION['messageToStack']); $i<$n; $i++) {
          $this->add($_SESSION['messageToStack'][$i]['class'], $_SESSION['messageToStack'][$i]['text'], $_SESSION['messageToStack'][$i]['type']);
        }
        unset($_SESSION['messageToStack']);
      }
    }

    // class methods
    function add($class, $message, $type = 'error') {
      $this->messages[] = array('type' => $type, 'class' => $class, 'text' => $message);
    }


    function add_session($class, $message, $type = 'error') {

      if (!isset($_SESSION['messageToStack'])) {
        $_SESSION['messageToStack'] = array();
      }

      $_SESSION['messageToStack'][] = array('class' => $class, 'text' => $message, 'type' => $type);
    }

    function reset() {
      $this->messages = array();
    }

    function output($class) {
   //   $this->table_data_parameters = 'class="messageBox table"';

      $output = array();   
      $list_error = '';   
      $list_warning = '';   
      $list_success = '';   
      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          switch($this->messages[$i]['type']) {
            case 'error':
              $output['error'][$i] = $this->messages[$i]['text'];
              break;
            case 'warning':
              $output['warning'][$i] = $this->messages[$i]['text'];
              break;
            case 'success':
              $output['success'][$i] = $this->messages[$i]['text'];
              break;
            default:
              $output['error'][$i] = $this->messages[$i]['text'];           
          }          

        }
      }

      if ($output['error']) {
        $list_error .= '<div id="alert-error" class="row fade-error mb-2"><div class="col p-0 mt-0 mb-2"><div class="note note-danger m-0"><h4 class="m-0">' . TEXT_ERROR . '</h4><ul class="list-unstyled mt-2 mb-0">';
        foreach($output['error'] as $text) {
          $list_error .= '<li>' . $text . '</li>';
        }
        $list_error .= '</ul></div></div></div>';
      }

      if ($output['warning']) {
        $list_warning .= '<div id="alert-warning" class="row fade-error mb-2"><div class="col p-0 mt-0 mb-2"><div class="note note-warning m-0"><h4 class="m-0">' . TEXT_WARNING . '</h4><ul class="list-unstyled mt-2 mb-0">';
        foreach($output['warning'] as $text) {
          $list_warning .= '<li>' . $text . '</li>';
        }
        $list_warning .= '</ul></div></div></div>';
      }  

      if ($output['success']) {
        $list_success .= '<div id="alert-success" class="row fade-error mb-2"><div class="col p-0 mt-0 mb-2"><div class="note note-success m-0"><h4 class="m-0">' . TEXT_SUCCESS . '</h4><ul class="list-unstyled mt-2 mb-0">';
        foreach($output['success'] as $text) {
          $list_success .= '<li>' . $text . '</li>';
        }
        $list_success .= '</ul></div></div></div>';
      }

      return ($list_error . $list_warning . $list_success);      

//      return $this->tableBoxMessagestack($output);
    }

    function size($class) {
      $count = 0;

      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $count++;
        }
      }

      return $count;
    }
  }
?>
