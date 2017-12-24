<?php
/*
  $Id: xml.php,v 1.0.0.0 2008/05/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

class XMLParser {
  var $xml_data;
  var $xml;
  var $data;

  function XMLParser($xml_data) {
    $this->xml_data = $xml_data;
    $this->xml = xml_parser_create();
    xml_set_object($this->xml, $this);
    xml_set_element_handler($this->xml, 'startHandler', 'endHandler');
    xml_set_character_data_handler($this->xml, 'dataHandler');
    $this->parse($xml_data);
  }

  function parse($xml_data) {
    $parse = xml_parse($this->xml, $xml_data, sizeof($xml_data));
    if (!$parse) {
      xml_parser_free($this->xml);
      die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($this->xml)), xml_get_current_line_number($this->xml)));      
    }
    return true;
  }

  function startHandler($parser, $name, $attributes) {
    $data['name'] = $name;
    if ($attributes) { 
      $data['attributes'] = $attributes; 
    }
    $this->data[] = $data;
  }

  function dataHandler($parser, $data) {
    if ($data = trim($data)) {
      $index = count($this->data) - 1;
      if (!isset($this->data[$index]['content'])) {
        $this->data[$index]['content'] = '';
      }
      $this->data[$index]['content'] .= $data;
    }
  }

  function endHandler($parser, $name) {
    if (count($this->data) > 1) {
      $data = array_pop($this->data);
      $index = count($this->data) - 1;
      $this->data[$index]['child'][] = $data;
    }
  }
  
  function __destruct() {
    $this->xml_data = NULL;
    $this->xml = NULL;
    $this->data = NULL;
  }
}
?>