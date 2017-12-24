{$ADMIN_TEMPLATE_TOP}
<script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/jquery.tools.min-1.2.5.js"></script>
<script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>

{include file="main_common_js.tpl"}
{include file="main_common_ceebox.tpl"}

<link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/css/ceebox_mh.css"/>
<link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}/mailhive/common/admin_application_plugins/common.css">
<!--
<table border="0" width="100%" cellspacing="2" cellpadding="2">
-->
<tr>
    <!-- body_text //-->
    <td width="100%" valign="top">

    {include file="main_common_content.tpl"}
    </td>

{include file="main_common_update_reminder.tpl"}
{$ADMIN_TEMPLATE_BOTTOM}
{$ADMIN_APPLICATION_BOTTOM}
