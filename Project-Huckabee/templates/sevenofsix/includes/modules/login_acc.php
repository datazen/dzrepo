<div class="row">

<?php

if(B2B_REQUIRE_LOGIN=='true') {
echo  TEXT_REQUIRE_LOGIN_MESSAGE_HEADING;

echo TEXT_REQUIRE_LOGIN_MESSAGE;
 }

?>

<?php
//BOF: MaxiDVD Returning Customer Info SECTION
//===========================================================
$returning_customer_title = HEADING_RETURNING_CUSTOMER; // DDB - 040620 - PWA - change TEXT by HEADING
if ($setme != '')
{
$returning_customer_info = "
<div class=\"col-sm-12 col-lg-12 well no-padding-top\">
          <h3>" .TEXT_YOU_HAVE_TO_VALIDATE."</h3>
          <p>" .TEXT_NEW_CUSTOMER_INTRODUCTION. "</p>
            <div class=\"form-group\">
			  <label class=\"sr-only\"></label>   " .tep_draw_input_field('email_address', '' , 'class="form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"') . "</br>
            </div>
            <div class=\"form-group\">
		       <label class=\"sr-only\"></label>      " . tep_draw_input_field('pass','','class="form-control" placeholder="' . ENTRY_VALIDATION_CODE . '"').tep_draw_input_field('password',$_POST['password'],'','hidden') . "
   			    <p class=\"help-block small-margin-left\">" . '<a href="' . tep_href_link('validate_new.php', '', 'SSL') . '">' . TEXT_NEW_VALIDATION_CODE . '</a>' . "<br><br></p>

            </div>

          <div class=\"button-set clearfix\">
   			    <input type=\"submit\" value=\"Continue\" class=\"pull-right btn btn-lg btn-primary\">
          </div>

</div>
</form>

<!--Confirm Block END-->
";

}else
{
$returning_customer_info = "
  <div class=\"col-sm-12 col-lg-12 well no-padding-top\">
    <h1 class=\"no-margin-top\"></h1>
            <h3 class=\"no-margin-top\">" . MENU_TEXT_RETURNING_CUSTOMER . "</h3>
            <div class=\"form-group\">
			  <label class=\"sr-only\"></label>   " .tep_draw_input_field('email_address', '' , 'class="form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"') . "</br>
            </div>
            <div class=\"form-group\">
		       <label class=\"sr-only\"></label>      " . tep_draw_password_field('password', '' , 'class="form-control" placeholder="' . MENU_TEXT_PASSWORD . '"') . "
   			    <p class=\"help-block small-margin-left\">" . '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>' . "<br><br></p>
            </div>
          </form>
          <div class=\"button-set clearfix\">
		  <button class=\"pull-right btn btn-lg btn-primary\" type=\"submit\">" .MENU_TEXT_SIGN_IN. "</button>
          </div>
        </div>




";
}
//===========================================================
// RCI code start
echo $cre_RCI->get('login', 'aboveloginbox');
// RCI code end
?>
<!-- login_acc -->
<?php

echo $returning_customer_info;
?>
<?php
//EOF: MaxiDVD Returning Customer Info SECTION
//===========================================================

// RCI code start
echo $cre_RCI->get('login', 'belowloginbox');
// RCI code end

if(B2B_ALLOW_CREATE_ACCOUNT=="true") {

//MaxiDVD New Account Sign Up SECTION
//===========================================================
$create_account_title = HEADING_NEW_CUSTOMER;
$create_account_info = "
<div class=\"col-sm-12 col-lg-12 well no-padding-top margin-top create-account-div\">
          <h3>" .TEXT_NEW_CUSTOMER."</h3>
          <p>" .TEXT_NEW_CUSTOMER_INTRODUCTION. "</p>
          <div class=\"buttons-set clearfix large-margin-top\">"
	       . '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '"> <form class=\"form-inline\" action=' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '><button class="pull-right btn btn-lg btn-primary">' . IMAGE_BUTTON_CREATE_ACCOUNT . '</button></a>' ."

          </div>
        </div>
";
//===========================================================
?>
<?php
echo $create_account_info ;
?>
<?php
//EOF: MaxiDVD New Account Sign Up SECTION
//===========================================================
}
?>
</div>