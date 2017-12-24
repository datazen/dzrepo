<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html {$HTML_PARAMS}>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$SESSION_CHARSET}"/>
    <title>{$TITLE}</title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css"/>
    <script language="javascript" src="includes/general.js"></script>
    <script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/jquery.tools.min-1.1.2.js"></script>
    <script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>

{include file="main_common_js.tpl"}
{include file="main_common_ceebox.tpl"}

    <link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/css/ceebox_mh.css"/>
    <link rel="stylesheet" type="text/css"
          href="{$MH_CATALOG_URL}/mailhive/common/admin_application_plugins/common.css">

    <script type="text/javascript">
        {literal}
        function rowOverEffect(object) {
            if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
        }

        function rowOutEffect(object) {
            if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
        }
        {/literal}
    </script>
</head>
<body bgcolor="#FFFFFF">
<!-- header //-->
{$ADMIN_HEADER}
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td width="{$ADMIN_BOX_WIDTH}" valign="top"
            {if $MH_PLATFORM_XTCMODIFIED}class="columnLeft2"{/if} >
            <table border="0" width="{$ADMIN_BOX_WIDTH}" cellspacing="1" cellpadding="1" class="columnLeft">
                <!-- left_navigation //-->
            {$ADMIN_COLUMN_LEFT}
                <!-- left_navigation_eof //-->
            </table>
        </td>
        <!-- body_text //-->
        <td width="100%" valign="top">
        {include file="main_common_content.tpl"}
        </td>
        <!-- body_text_eof //-->
    </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
{$ADMIN_FOOTER}
<!-- footer_eof //-->

{include file="main_common_update_reminder.tpl"}
</body>
</html>
{$ADMIN_APPLICATION_BOTTOM}
