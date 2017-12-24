<html>
<!--
{*
    MailBeez V2.5 default template
    Please customize the layout and content to your needs
*}
-->
<head>
    <title>{$subject}</title>
{literal}
    <style type="text/css">
        td {
            font-family: Arial, Helvetica;
            font-size: 12px;
        }
    </style>
{/literal}
</head>
<body bgcolor="#ffffff" text="#000000" vLink="#3a6ea5" aLink="#cc0000" link="#3a6ea5">
<!-- start: you can remove this -->
<table border="0" cellpadding="0" cellspacing="0" align="center" width="600">
    <tr valign="top">
        <td><font face="Verdana, Arial" size="1">
            please adjust the templates to your needs, you find the templates:<br>
            template: [SHOPROOT]/mailhive/common/templates/email_html.tpl<br>
            header picture: [SHOPROOT]/mailhive/common/images/default_emailheader.gif<br>
            <br>
            Upgrade to the <b><a
                href="http://www.mailbeez.com/documentation/configbeez/config_tmplmngr/?a={$smarty.const.MH_ID}"
                target="_blank">MailBeez Template Manager</a></b> to edit all your MailBeez Templates in your MailBeez
            Admin
            <br><br>
        </font></td>
    </tr>
</table>
<!-- end: you can remove this -->

<!-- change the picture  -->
<table border="0" cellpadding="0" cellspacing="0" align="center" width="600">
    <tr valign="top">
        <td height="90"><a href="{$catalog_server}"><img
                src="{$catalog_server}mailhive/common/images/default_emailheader.gif" border="0" hspace="0" vspace="0"
                width="600"></a></td>
    </tr>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="1" align="center" bgcolor="#c0c0c0">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
                <tr valign="top">
                    <td width="15"><img src="{$blank_img}" border="0" hspace="0" vspace="0" width="15" height="0"></td>
                    <td>{$body}</td>
                    <td width="15"><img src="{$blank_img}" border="0" hspace="0" vspace="0" width="15" height="0"></td>
                </tr>
                <tr valign="top">
                    <td colspan="3" height="1" bgcolor="#c0c0c0"><img src="{$blank_img}" border="0" hspace="0" vspace="0" width="1" height="1"></td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="15" align="center" bgcolor="#ffffff">
                <tr valign="top">
                    <td align="center">

                        <b><a href="http://www.facebook.com/yoursite"
                              style="font-family: Arial; font-weight: bold; font-size: 15px; text-decoration: none; color: #363636"><img
                                src="{$catalog_server}mailhive/common/images/footer_fb.png" border="0"
                                hspace="0"
                                vspace="0"
                                width="32" height="32" align="absmiddle">&nbsp; find us on facebook</a></b>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <b><a href="http://www.twitter.com/yourtweets"
                              style="font-family: Arial; font-weight: bold; font-size: 15px; text-decoration: none; color: #363636">tweet
                            with us &nbsp;<img
                                    src="{$catalog_server}mailhive/common/images/footer_tw.png" border="0"
                                    hspace="0" vspace="0"
                                    width="32" height="32" align="absmiddle"></a></b>
                        <div style="color: #898989; font-size: 12px; font-family: Arial">
                            <br>
                            <br>
                            <br>
                            <b>{$storename} | Street address | State /County | Country</b>
                            <br>
                            Tax id: XXXXX | Company Registration Number: XXXXX
                            <br>
                            <br>
                            <hr width="490" size="1" style="color:#cccccc">
                            <br>

                            You are recieving this email on {$email_address} because you subscribed through
                            <br>
                            registering with {$storename}<br>
                            If you feel that you have received this email in error, then please <a
                                href="{$page_contact_us}" style="color: #53535c">contact us</a>.
                            <br>
                            <br>
                            <a href="{$block_url}" style="color: #53535c">click to unsubscribe</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
<!--
{*

MailBeez uses the template-engine Smarty, visit www.smarty.net for more information

Common Template Variables are:

{$storename}                            Storename
{$storeurl}                             URL to Store
{$catalog_server}                       URL to shoproot, e.g. for image path

{$page_contact_us}                      contact us
{$page_customer_support}                contact us
{$page_my_account}                      my account
{$page_password}                        retrieve password
{$blank_img}                            URL to blank image

{$block_url}                            Opt-Out URL


These Variables can be use in this Theme-Template and/or the modules Body-Content Templates

*}

-->