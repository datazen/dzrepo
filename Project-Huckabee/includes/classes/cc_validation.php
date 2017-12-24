<?php
/**
  @name       cc_validation.php   
  @version    6.5.1 | 05-21-2012 | datazen
  @author     Loaded Commerce Core Team
  @copyright  (c) 2012 loadedcommerce.com
  @license    GPL2
*/
class cc_validation {
  /**
  * public variables
  * 
  * @var  string  $cc_number        The credit card number
  * @var  string  $ cc_expiry_month The card expiration month
  * @var  string  $cc_expiry_year   The card expiraiton year
  * @var  string  $cvv              The CVV code
  * @var  string  $ccType           The card type (not used - kept for backwards compatibility)
  */
  var $cc_number, $cc_expiry_month, $cc_expiry_year, $ccv, $cc_type;
 /**
  * Validate the payment data
  *
  * @param  string  $cc_number        The credit card number
  * @param  string  $ cc_expiry_month The card expiration month
  * @param  string  $cc_expiry_year   The card expiraiton year
  * @param  string  $cvv              The CVV code
  * @param  string  $ccType           The card type (not used - kept for backwards compatibility
  * @access public
  * @return mixed
  */  
  function validate($ccNumber, $ccExpMonth, $ccExpYear, $ccCVV = NULL, $ccType = NULL) {
    /*
     * commented out to always return true and let processor do the validation.  uncomment to use validation.
     * 
    $this->cc_number = preg_replace('/[^0-9]/', '', $ccNumber);
    $this->cc_type   = $this->card_type($this->card_number);
    if ($this->cc_type == 'Unknown') return -1; // unknown card
    if ($this->isBlacklisted($this->cc_number)) return -7; // blacklisted card
    if (!$this->isGoodExpMonth($ccExpMonth)) return -2; // invalid exp month
    if (!$this->isGoodExpYear($ccExpYear)) return $this->isGoodExpYear($ccExpYear); // invalid exp year // invalid date
    if (!$this->is_valid()) return -9; // failes mod10 check
    if ($cvv != NULL) {
      if (!$this->isGoodCVV($cvv)) return $this->isGoodCVV($cvv);  // check cvv 
    }
    */    
    return TRUE;
  }
 /**
  * Get the card type
  *
  * @param  string  $card_number  The credit card number
  * @access public
  * @return string
  */    
  function card_type($cardNumber) {
    // check the card type
    if(preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/", $cardNumber)) {
      $ccType = 'Visa';
    } else if(preg_match("/^5[1-5][0-9]{14}$/", $cardNumber)) {
      $ccType = 'Mastercard';
    } else if(preg_match("/^3[47][0-9]{13}$/", $cardNumber))  {
      $ccType = 'American Express';
    } else if(preg_match("/^6(?:011|5[0-9]{2})[0-9]{12}$/", $cardNumber)) {
      $ccType = 'Discover';
    } else if(preg_match("/^(?:2131|1800|35\d{3})\d{11}$/", $cardNumber)) {
      $ccType = 'JCB';
    } else if(preg_match("/^3[0,6,8]\d{12}$/", $cardNumber)){
      $ccType = 'Diners Club';
    } else if(preg_match("/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/", $cardNumber)){
      $ccType = 'Enroute';
    } else if(preg_match("/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/", $cardNumber)){
      $ccType = 'Switch';
    }
    if (!$ccType) {
      $ccType = 'Unknown';    
    } 
    
    return $ccType; 
  } 
 /**
  * Check to see if the card number is blacklisted
  *
  * @access public
  * @return boolean
  */    
  function isBlacklisted() {
    if ( strtolower(CC_BLACK) == 'true' ) {      
      // Blacklist check
      $card_info = tep_db_query("select c.blacklist_card_number from " . TABLE_BLACKLIST . " c where c.blacklist_card_number = '" . $this->cc_number . "'");
      if (tep_db_num_rows($card_info) > 0) { // card found in database
        return TRUE;
      }
    }
    return FALSE;  
  }
 /**
  * Check the expiration month
  *
  * @param  string  $expiry_m The card expiration month
  * @access public
  * @return boolean
  */  
  function isGoodExpMonth($expiry_m) {
    if (is_numeric($expiry_m) && ($expiry_m > 0) && ($expiry_m < 13)) {
      $this->cc_expiry_month = $expiry_m;
    } else {
      return FALSE;;
    }
    return TRUE;
  }
 /**
  * Check the expiration year
  *
  * @param  string  $expiry_y The card expiration year
  * @access public
  * @return mixed
  */    
  function isGoodExpYear($expiry_y) {
    $current_year = date('Y');
    $expiry_y = substr($current_year, 0, 2) . $expiry_y;
    if (is_numeric($expiry_y) && ($expiry_y >= $current_year) && ($expiry_y <= ($current_year + 10))) {
      $this->cc_expiry_year = $expiry_y;
    } else {
      return -3;
    }
    if ($expiry_y == $current_year) {
      if ($expiry_m < date('n')) {
        return -4;
      }
    }
    
    return TRUE;
  }  
 /**
  * Check to see if CVV length is valid
  *
  * @param  string  $cvv  The card CVV code
  * @access public
  * @return mixed
  */      
  function isGoodCVV($cvv) {
    if($ccv != '') {
      $l = strlen($ccv);
      //This sets length if select card type is not used
      if ($this->cc_type != '') {
        if (($this->cc_type == 'Amex') || ($this->cc_type == 'American Express') || ($this->cc_type == 'American_Express') ){
          $len = 4;
        } else {
          $len = 3;  
        }
        if ($len != $l) {
          return -6;
        }
      }  
    } else {
      return -6;
    }
    return TRUE ;
  }  
 /**
  * Perform a mod10 check on the card number
  *
  * @access public
  * @return boolean
  */  
  function is_valid() {
    $cardNumber = strrev($this->cc_number);
    $numSum = 0;
    for ($i=0; $i<strlen($cardNumber); $i++) {
      $currentNum = substr($cardNumber, $i, 1);
      // Double every second digit
      if ($i % 2 == 1) {
        $currentNum *= 2;
      }
      // Add digits of 2-digit numbers together
      if ($currentNum > 9) {
        $firstNum = $currentNum % 10;
        $secondNum = ($currentNum - $firstNum) / 10;
        $currentNum = $firstNum + $secondNum;
      }
      $numSum += $currentNum;
    }
    // If the total has no remainder it's OK
    if ($numSum % 10 == 0) { 
      return true;
    } else {
      return false;
    }
  }
} 
?>