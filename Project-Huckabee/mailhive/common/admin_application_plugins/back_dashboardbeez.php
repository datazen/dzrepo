<?php 
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
	
	v2.1
*/

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

	echo mb_admin_button( mh_href_link(FILENAME_MAILBEEZ, 'module=config_dashboard'), MH_BUTTON_BACK_CONFIGURATION, '', 'link') . ' | ';
	echo mb_admin_button( mh_href_link(FILENAME_MAILBEEZ, 'tab=home'), MH_BUTTON_BACK_DASHBOARD, '', 'link') . '<br><br>';	
 ?>
  		 <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo MH_HEADER_DASHBOARD_MODULES ?></td>
          </tr>
        </table>