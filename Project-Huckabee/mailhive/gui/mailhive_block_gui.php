<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.5
 */


// this page is not in the context of the shopping system
// you can't use the shop systems functions
//
// if you would like to redirect to a shop page
// please specify you url here
// the target page needs to use the logic below 
// to display a confirmation message
/*
  $url = 'http://www.yourshop.com/block_confirmation_page.php'; // example
  header('Location: ' . $url . '?p=' . $_GET['p'] . '&result=' . $_GET['result'] . '&module=' . $_GET['module']);
  exit();
 */



list($customers_id, $email_address) = explode('|', base64_decode($_GET['p']));

$unblock_page_url = base64_decode($_GET['ub']);

$unblock_url = $unblock_page_url . '?ma=unblock&m=' . $_GET['module'] . '&mp=' . $_GET['p'];

$result = $_GET['result'];

$msg = 0;
switch ($result) {
    case 'ok':
        $msg = 1;
        break;
    case '-1':
        $msg = 2;
        break;
    case 'failed':
        $msg = 3;
        break;
}
?>
<html>
<head>
    <title>MailHive Block</title>
</head>

<style>

    .mailbeez_message {
        margin: auto;
        width: 500px;
        padding: 30px;
        font-family: arial;
        border: 1px solid red;

    }

</style>

<body>

<div class="mailbeez_message">
    Dear Customer,<br>
    <br>
    <?php if ($msg == 1) { ?>
    you will no longer receive any Email of type <b><?php echo $_GET['module']; ?></b>.
    <?php } ?>
    <?php if ($msg == 2) { ?>
    you will no longer receive any Email of type <b><?php echo $_GET['module']; ?></b>.
    <br>
    We have already blocked this module for you.
    <?php } ?>
    <?php if ($msg == 3) { ?>
    we can't find your email in our system, please contact our support.
    <?php } ?>
    <br>
    <br>
    your email: <?php echo $email_address ?>
    <br>
    <br>
    <br>
    <br>
    <a href="<?php echo $unblock_url;?>"> MISTAKE! Please un-block me!</a>
    <br>
    <br>
    <br>
    <br>
    <!-- please keep this link - or donate on www.mailbeez.com -->
    <img src="../common/images/been_tiny.gif" border="0" hspace="0" vspace="0" width="33" height="28" align="absmiddle">
    powered by <a href="http://www.MailBeez.com" target="_blank">MailBeez.com</a>

    <div>

</body>
</html>
