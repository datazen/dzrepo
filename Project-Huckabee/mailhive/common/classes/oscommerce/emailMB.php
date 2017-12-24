<?php 
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

class emailMailBeez extends email {
		// no converting of linefeeds into <br>
    function add_html($html, $text = NULL, $images_dir = NULL) {
      $this->html = $html; //tep_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>', $html);
      $this->html_text = tep_convert_linefeeds(array("\r\n", "\n", "\r"), $this->lf, $text);

      if (isset($images_dir)) $this->find_html_images($images_dir);
    }
}


 ?>
