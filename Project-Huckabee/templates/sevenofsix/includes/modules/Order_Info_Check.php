<?php
/*
  $Id: Order_Info_Check.php,v 0.52 2002/09/21 hpdl Exp $
  by Cheng

        OSCommerce v2.2 CVS (09/17/02)

   Modified versions of create_account.php and related
  files.  Allowing 'purchase without account'.


  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<?php

if (!isset($is_read_only)) {
  $is_read_only = false;
}
if (!isset($error )) {
  $error = false;
}
if (!isset($processed)) {
  $processed = false;
}

?>
<div class="row">
      <div class="col-sm-6 col-lg-6">
	   <div style="padding-left:10px">
		<h3 class="small-margin-top"><?php echo CATEGORY_PERSONAL; ?></h3>
<?php
if (!isset($account['customers_gender'])) {
  $account['customers_gender'] = '';
}
if (!isset($is_read_only)) {
  $is_read_only = false;
}
if (!isset($error)) {
  $error = false;
}
  if (ACCOUNT_GENDER == 'true') {
    $male = ($account['customers_gender'] == 'm') ? true : false;
    $female = ($account['customers_gender'] == 'f') ? true : false;
?>
<?php echo ENTRY_GENDER; ?>
<?php
    if ($is_read_only) {
      echo ($account['customers_gender'] == 'm') ? MALE : FEMALE;
    } elseif ($error) {
      if ($entry_gender_error) {
        echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        echo ($gender == 'm') ? MALE : FEMALE;
        echo tep_draw_hidden_field('gender');
      }
    } else {
      echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_TEXT;
    }
?>
<?php
  }
?>
<div class="form-group">
<?php
  if ($is_read_only) {
    echo $account['customers_firstname'];
  } elseif ($error) {
    if ($entry_firstname_error) {
      echo tep_draw_input_field('firstname') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $firstname . tep_draw_hidden_field('firstname');
    }
  } else {
    echo '<label class="sr-only"></label>' . tep_draw_input_field('firstname','','class="form-control" placeholder="' . ENTRY_FIRST_NAME . '"', (isset($account['customers_firstname']) ? $account['customers_firstname'] : ''));
  }
?>
  </div>
<div class="form-group">
<?php
  if ($is_read_only) {
    echo $account['customers_lastname'];
  } elseif ($error) {
    if ($entry_lastname_error) {
      echo tep_draw_input_field('lastname') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
    } else {
      echo $lastname . tep_draw_hidden_field('lastname');
    }
  } else {
    echo '<label class="sr-only"></label>' .  tep_draw_input_field('lastname','','class="form-control" placeholder="' . ENTRY_LAST_NAME . '"', (isset($account['customers_lastname']) ? $account['customers_lastname'] : ''));
  }
?>

  </div>

<?php
  if (ACCOUNT_DOB == 'true') {
?>
<div class="form-group">
<?php
    if ($is_read_only) {
      echo tep_date_short($account['customers_dob']);
    } elseif ($error) {
      if ($entry_date_of_birth_error) {
        echo tep_draw_input_field('dob') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
      } else {
        echo $dob . tep_draw_hidden_field('dob');
      }
    } else {
      echo '<label class="sr-only"></label>' . tep_draw_input_field('dob', (isset($account['customers_dob']) ? tep_date_short($account['customers_dob']) : '')) . '&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT;
    }
?>
</div>
<?php
  }
?>

<div class="form-group">
<?php
  if ($is_read_only) {
    echo $account['customers_email_address'];
  } elseif ($error) {
    if ($entry_email_address_error) {
      echo tep_draw_input_field('email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_email_address_check_error) {
      echo tep_draw_input_field('email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } elseif ($entry_email_address_exists) {
      echo tep_draw_input_field('email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $email_address . tep_draw_hidden_field('email_address');
    }
  } else {
    echo '<label class="sr-only"></label>' . tep_draw_input_field('email_address','','class="form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"', (isset($account['customers_email_address']) ? $account['customers_email_address'] : ''));
  }
?>
  </div>
 </div>
</div>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
      <div class="col-sm-6 col-lg-6">
	   <div style="padding-right:10px;">

    <h3 class="small-margin-top"><?php echo CATEGORY_COMPANY; ?></h3>
<div class="form-group">
<?php
    if ($is_read_only) {
      echo $account['entry_company'];
    } elseif ($error) {
      if ($entry_company_error) {
        echo tep_draw_input_field('company') . '&nbsp;' . ENTRY_COMPANY_ERROR;
      } else {
        echo $company . tep_draw_hidden_field('company');
      }
    } else {
      echo '<label class="sr-only"></label>' . tep_draw_input_field('company','','class="form-control" placeholder="' . ENTRY_COMPANY . '"', (isset($account['entry_company']) ? $account['entry_company'] : '')) . '&nbsp;' . ENTRY_COMPANY_TEXT;
    }
?>
  </div>
 </div>
</div>

<?php
  }
?>
</div><!--row1 end-->
<div class="row">
  <div class="col-sm-6 col-lg-6">
	   <div style="padding-left:10px;">
         <h3 class="small-margin-top"><?php echo CATEGORY_ADDRESS; ?></h3>
<div class="form-group">
<?php
  if ($is_read_only) {
    echo $account['entry_street_address'];
  } elseif ($error) {
    if ($entry_street_address_error) {
      echo tep_draw_input_field('street_address') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
    } else {
      echo $street_address . tep_draw_hidden_field('street_address');
    }
  } else {
    echo '<label class="sr-only"></label>' . tep_draw_input_field('street_address','','class="form-control" placeholder="' . ENTRY_STREET_ADDRESS . '"', (isset($account['entry_street_address']) ? $account['entry_street_address']: ''));
  }
?>
 </div>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
<div class="form-group">
<?php
    if ($is_read_only) {
      echo $account['entry_suburb'];
    } elseif ($error) {
      if ($entry_suburb_error) {
        echo tep_draw_input_field('suburb') . '&nbsp;' . ENTRY_SUBURB_ERROR;
      } else {
        echo $suburb . tep_draw_hidden_field('suburb');
      }
    } else {
      echo '<label class="sr-only"></label>' . tep_draw_input_field('suburb','','class="form-control" placeholder="' . ENTRY_SUBURB . '"', (isset($account['entry_suburb']) ? $account['entry_suburb'] : ''));
    }
?>
</div>
<?php
  }
?>
<div class="form-group">
<?php
  if ($is_read_only) {
    echo $account['entry_city'];
  } elseif ($error) {
    if ($entry_city_error) {
      echo tep_draw_input_field('city') . '&nbsp;' . ENTRY_CITY_ERROR;
    } else {
      echo $city . tep_draw_hidden_field('city');
    }
  } else {
    echo '<label class="sr-only"></label>' . tep_draw_input_field('city','','class="form-control" placeholder="' . ENTRY_CITY . '"', (isset($account['entry_city']) ? $account['entry_city'] : ''));
  }
?> </div>


<?php
  if (ACCOUNT_STATE == 'true') {
?>
<div class="form-group">
<?php
      if (!isset($country)) {
    $country = '';
  }
  if (!isset($zone_id)) {
    $zone_id = '';
  }
  if (!isset($state)) {
    $state = '';
  }
    $state = tep_get_zone_name($country, $zone_id, $state);
    if ($is_read_only) {
      echo tep_get_zone_name($account['entry_country_id'], $account['entry_zone_id'], $account['entry_state']);
    } elseif ($error) {
      if ($entry_state_error) {
        if ($entry_state_has_zones) {
          $zones_array = array();
          $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
          while ($zones_values = tep_db_fetch_array($zones_query)) {
            $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
          }
          echo tep_draw_pull_down_menu('state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
        } else {
          echo tep_draw_input_field('state') . '&nbsp;' . ENTRY_STATE_ERROR;
        }
      } else {
        echo $state . tep_draw_hidden_field('zone_id') . tep_draw_hidden_field('state');
      }
    } else {
       echo '<label class="sr-only"></label>' .  tep_draw_input_field('state','','class="form-control" placeholder="' . ENTRY_STATE . '"', tep_get_zone_name($account['entry_country_id'], (isset($account['entry_zone_id']) ? $account['entry_zone_id'] : 0), (isset($account['entry_state']) ? $account['entry_state'] : 0)));
    }
?>
</div>
<?php
  }
?>
<div class="form-group">
<?php
  if ($is_read_only) {
    echo $account['entry_postcode'];
  } elseif ($error) {
    if ($entry_post_code_error) {
      echo tep_draw_input_field('postcode','','maxlength="10"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
    } else {
      echo $postcode . tep_draw_hidden_field('postcode');
    }
  } else {
    echo '<label class="sr-only"></label>' . tep_draw_input_field('postcode','','class="form-control" placeholder="' . ENTRY_POST_CODE . '"', (isset($account['entry_postcode']) ? $account['entry_postcode'] : ''),'maxlength="10"');
  }
?>
</div>
<div class="form-group">
<?php
  if ($is_read_only) {
    echo tep_get_country_name($account['entry_country_id']);
  } elseif ($error) {
    if ($entry_country_error) {
      echo tep_get_country_list('country') . '&nbsp;' . ENTRY_COUNTRY_ERROR;
    } else {
      echo tep_get_country_name($country) . tep_draw_hidden_field('country');
    }
  } else {
    echo '<label class="sr-only"></label>' .  tep_get_country_list('country','','class="form-control" placeholder="' . ENTRY_COUNTRY . '"', (isset($account['entry_country_id']) ? $account['entry_country_id'] : ''));
  }
?></div>
  </div>
 </div>

  <div class="col-sm-6 col-lg-6">
    <div style="padding-right:10px;">

<h3 class="small-margin-top"><?php echo CATEGORY_CONTACT; ?></h3>

<div class="form-group">
<?php
  if ($is_read_only) {
    echo $account['customers_telephone'];
  } elseif ($error) {
    if ($entry_telephone_error) {
      echo tep_draw_input_field('telephone') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
    } else {
      echo $telephone . tep_draw_hidden_field('telephone');
    }
  } else {
    echo '<label class="sr-only"></label>' .  tep_draw_input_field('telephone','','class="form-control" placeholder="' . ENTRY_TELEPHONE_NUMBER . '"',  (isset($account['customers_telephone']) ? $account['customers_telephone'] : ''));
  }
?>
</div>
<?php/*<div class="form-group">
<?php
  if ($is_read_only) {
    echo $account['customers_fax'];
  } elseif ($processed) {
    echo $fax . tep_draw_hidden_field('fax');
  } else {
    echo '<label class="sr-only"></label>' .  tep_draw_input_field('fax','','class="form-control" placeholder="' . ENTRY_FAX_NUMBER . '"',  (isset($account['customers_fax']) ? $account['customers_fax'] : '')) . '&nbsp;' . ENTRY_FAX_NUMBER_TEXT;
  }
?></div>*/?>
 </div>
</div>

</div><!--row2end-->
