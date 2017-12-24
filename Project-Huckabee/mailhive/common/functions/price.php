<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.1

  price functions
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


function mh_price($value) {
  switch (MH_PLATFORM) {
    case 'oscommerce':
    case 'creloaded':
    case 'digistore':
    case 'zencart':
      global $currencies;
      return $currencies->format($value);
      break;
    case 'xtc':
    case 'gambio':
      global $xtPrice;
      return $xtPrice->xtcFormat($value, true, false);
      break;
    default:
      echo 'platform not supported';
  }
}

?>