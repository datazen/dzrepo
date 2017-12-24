<?php


  if(B2B_REQUIRE_LOGIN=='true')  {
  echo '<div class=\"main\">';

  $info_box_contents = array();
  $info_box_contents[] = array( 'text'  => TEXT_REQUIRE_LOGIN_MESSAGE_HEADING);
  new contentBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('text'  => TEXT_REQUIRE_LOGIN_MESSAGE);
  new contentBox($info_box_contents, true, true);
  if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){

 // new contentboxFooter($info_box_contents);
  }
  echo '</div>';
  }


//BOF: MaxiDVD Returning Customer Info SECTION
//===========================================================
$returning_customer_title = HEADING_RETURNING_CUSTOMER; // DDB - 040620 - PWA - change TEXT by HEADING

if ($setme != '')
{
$returning_customer_info = "
<!--Confirm Block-->

<td width=\"50%\" height=\"100%\" valign=\"top\"><table border=\"0\" width=\"100%\" height=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"infoBox\">
<tr class=\"infoBoxContents\">
<td>
<table border=\"0\" width=\"100%\" height=\"100%\" cellspacing=\"0\" cellpadding=\"2\">
 <tr>
   <td class=\"main\" colspan=\"2\">".TEXT_YOU_HAVE_TO_VALIDATE."</td>
 </tr>
 <tr>
   <td class=\"main\"><b>". ENTRY_EMAIL_ADDRESS."</b></td>
   <td class=\"main\">". tep_draw_input_field('email_address')."</td>
 </tr>
 <tr>
   <td class=\"main\"><b>". ENTRY_VALIDATION_CODE."</b></td>
   <td class=\"main\">".tep_draw_input_field('pass').tep_draw_input_field('password',$_POST['password'],'','hidden')."</td>
 </tr>
 <tr>
   <td class=\"smallText\" colspan=\"2\">". '<a href="' . tep_href_link('validate_new.php', '', 'SSL') . '">' . TEXT_NEW_VALIDATION_CODE . '</a>'."</td>
 </tr>
 <tr>
   <td colspan=\"2\">". tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
 </tr>
 <tr>
   <td colspan=\"2\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">
     <tr>
       <td width=\"10\">". tep_draw_separator('pixel_trans.gif', '10', '1')."</td>
       <td align=\"right\">".tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE)."</td>
       <td width=\"10\">".tep_draw_separator('pixel_trans.gif', '10', '1')."</td>
     </tr>
</table>
        </table></td>
      </tr>
    </table></form></td>

<!--Confirm Block END-->
";

}else
{
$returning_customer_info = "


<div class=\"row\">
    <h1 class=\"no-margin-top\"></h1>
    <div class=\"row\">
      <div class=\"col-sm-12 col-lg-12 large-padding-left margin-top\">
        <div class=\"well no-padding-top\"><br>
            <h3 class=\"no-margin-top\">" . MENU_TEXT_RETURNING_CUSTOMER . "</h3>
            <div class=\"form-group\">
			  <label class=\"sr-only\"></label>   " .tep_draw_input_field('email_address', '' , 'class="form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"') . "</br>
            </div>
            <div class=\"form-group\">
		       <label class=\"sr-only\"></label>      " . tep_draw_password_field('password', '' , 'class="form-control" placeholder="' . MENU_TEXT_PASSWORD . '"') . "
   			    <p class=\"help-block small-margin-left\">" . '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>' . "<br><br></p>
            </div>
          <div class=\"button-set clearfix\">
		  <button class=\"pull-right btn btn-lg btn-primary\" type=\"submit\">" .MENU_TEXT_SIGN_IN. "</button>
          </div>
          </form>

        </div>
      </div>
      <div class=\"col-sm-12 col-lg-12 margin-top create-account-div\">
        <div class=\"well no-padding-top\">
          <h3>" .TEXT_NEW_CUSTOMER."</h3>
          <p>" .TEXT_NEW_CUSTOMER_INTRODUCTION. "</p>
          <div class=\"buttons-set clearfix large-margin-top\">"
	       . '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '"> <form class=\"form-inline\" action=' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '><button class="pull-right btn btn-lg btn-primary">' . IMAGE_BUTTON_CREATE_ACCOUNT . '</button></a>' ."

          </div>
        </div>
      </div>
        </div>
      </div>





";
}
//===========================================================
// RCI code start
echo $cre_RCI->get('login', 'aboveloginbox');
// RCI code end
?>
<!-- login_pwa -->

    <tr>
     <td class="main" width=100% valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_title );
//  new contentBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_info);
 new contentBox($info_box_contents, true, true);
  if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='false'){
  //new contentboxFooter($info_box_contents);
  }
?>
  </td>
 </tr>

<?php
//EOF: MaxiDVD Returning Customer Info SECTION
//===========================================================

// RCI code start
echo $cre_RCI->get('login', 'belowloginbox');
// RCI code end

if(B2B_ALLOW_CREATE_ACCOUNT=="true")
{

//MaxiDVD New Account Sign Up SECTION
//===========================================================
$create_account_title = HEADING_NEW_CUSTOMER;
$create_account_info = "";
//===========================================================
?>
    <tr>
     <td class="main" width=100% valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $create_account_title );
//  new contentBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $create_account_info);
  new contentBox($info_box_contents, true, true);
  if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){

//  new contentboxFooter($info_box_contents);
  }
?>
  </td>
  </tr>

<?php
//EOF: MaxiDVD New Account Sign Up SECTION
//===========================================================
}


if (B2B_REQUIRE_LOGIN=='false')
{
//BOF: MaxiDVD Purchase With-Out An Account SECTION
//===========================================================
$pwa_checkout_title = HEADING_CHECKOUT;
$pwa_checkout_info = "<div class=\"row\">
  <div class=\"col-sm-12 col-lg-12 no-padding-left no-padding-right\">
  <div class=\"well no-padding-top\">
          <h3>" .MENU_TEXT_GUEST."</h3>

            <p>" .TEXT_CHECKOUT_INTRODUCTION. "</p>
          <div class=\"buttons-set clearfix large-margin-top\">"
			   . '<a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'SSL') . '" class="pull-right btn btn-lg btn-primary" type="button">Checkout</a>' ."

          </div>

   </div>
  </div>
 </div>";
// . '<a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'SSL') . '">' . tep_template_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a>' . "<br><br></td>

//===========================================================
?>

    <tr>
     <td class="main" width=100% valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $pwa_checkout_title );
 // new contentBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $pwa_checkout_info);
  new contentBox($info_box_contents, true, true);
  if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
//  new contentboxFooter($info_box_contents);
  }
?>
  </td>
  </tr>

  <?php
//EOF: MaxiDVD Purchase With-Out An Account SECTION
//===========================================================

}
?>
