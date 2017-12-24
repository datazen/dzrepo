<?php 
 /**
  @name       loadedpayments-relay.php   
  @version    1.0.0 | 05-21-2012 | datazen
  @author     Loaded Commerce Core Team
  @copyright  (c) 2012 loadedcommerce.com
  @license    GPL2
*/
require('includes/application_top.php');
include(DIR_WS_LANGUAGES . $language . '/loadedpayments.php');

$Success  = trim($_POST["success"]); 
$Error    = trim($_POST["error"]); 
$PNRef    = trim($_POST["pnref"]); 
$AuthCode = trim($_POST["authcode"]); 
$Amount   = trim($_POST["amount"]); 
$sessName = trim($_POST["customField1"]); 
$sessID   = trim($_POST["customField2"]); 
$orderID  = (tep_not_null($_POST["customField3"])?$_POST["customField3"]:$_SESSION['order_id']); 
$ccOwner  = trim($_POST["firstname"]) . ' ' . trim($_POST["lastname"]); 
$ccLast4  = trim($_POST["card"]);
$ccExp    = trim($_POST["expdate"]);
$ccType   = trim($_POST["cardType"]);
// parse the response 
$error = '0';
if ($Success === "T") { 
   $code = '000';
   $msg = 'Success';
} else if ($Success === "F") {
   $code = '101'; 
   $msg = $Error;
   $error = '1';
} else if ($Success === "") { 
   $code = '901';
   $msg = TEXT_ERROR_PROCESSING_TRANSACTION;
   $error = '1';
}
?>
<html>
<head>
<title>Loaded Payments</title>
</head>
<body onload="document.frmResultPage.submit(); setTimeout(function() {hideLoader();},1250);"></body>
<form name="frmResultPage" method="POST" action="<?php echo tep_href_link(FILENAME_CHECKOUT_PROCESS, $sessName . '=' . $sessID . '&order_id=' . $orderID, 'SSL', false, false); ?>" target="_parent">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<input type="hidden" name="message" value="<?php echo $msg; ?>">
<input type="hidden" name="pnref" value="<?php echo $PNRef; ?>">
<input type="hidden" name="authcode" value="<?php echo $AuthCode; ?>">
<input type="hidden" name="orderid" value="<?php echo $orderID; ?>">
<input type="hidden" name="ccOwner" value="<?php echo $ccOwner; ?>">
<input type="hidden" name="ccLast4" value="<?php echo $ccLast4; ?>">
<input type="hidden" name="ccExp" value="<?php echo $ccExp; ?>">
<input type="hidden" name="ccType" value="<?php echo $ccType; ?>">
<input type="hidden" name="error" value="<?php echo $error; ?>">
<input type="hidden" name="payment" value="loadedpayments">
<div id="container" style="position:relative;">
  <div id="loadingContainer"  style="position: absolute; left:220px; top:80px;"><p><img border="0" src="images/lp-loading.png"></p></div>
</div>
<noscript>
    <br><br>
    <center>
    <font color="red">
    <h1>Processing Your Transaction</h1>
    <h2>JavaScript is currently disabled or is not supported by your browser.<br></h2>
    <h3>Please click Submit to continue the processing of your transaction.</h3>
    </font>
    <input type="submit" value="Submit">
    </center>
</noscript>
<script>
function hideLoader() {  
  var loadDiv = document.getElementById("loadingContainer"); 
  loadDiv.style.display = "none"; 
}
</script>
</form>
</html>