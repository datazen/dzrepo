<div class="mailbeez_main">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td width="100%">
                <div class="mailbeez_main_header">
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="pageHeading">
                            {if ($MAILBEEZ_MAILHIVE_STATUS == 'False')}
                                <span style="color: #ff0000">OFFLINE</span>
                            {/if}
                            {$HEADING_TITLE}
                            {if (defined('MAILBEEZ_MAILHIVE_MODE')) }
                                V. {$MAILBEEZ_VERSION}
                                <br/>

                            <!--
                                <div class="smallText">Mode: {$MAILBEEZ_MAILHIVE_MODE} (<a
                                        href="{$MAILBEEZ_MAILHIVE_MODE_SWITCH_URL}">{$MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT}</a>)
                                </div>
                                -->
                            {/if}
                            </td>
                            <td class="pageHeading"
                                align="right">{$ADMIN_PAGE_HEADING_SEPARATOR}
                            {if (defined('MAILBEEZ_VERSION')) }
                                <div class="mode_info {$MAILBEEZ_MAILHIVE_MODE}">{*$MAILBEEZ_VERSION_CHECK_BUTTON*}

                                <a href="{$MAILBEEZ_MAILHIVE_MODE_SWITCH_URL}"><img src="{$MH_CATALOG_URL}mailhive/common/images/button_status_{$MAILBEEZ_MAILHIVE_MODE}.png" width="24" style="float: left"></a> <div style="margin-top: 5px;">{$MAILBEEZ_MAILHIVE_MODE_TEXT|lower}</div>
                                </div>
                            {/if}
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="mailbeez_main_content">
                {$MAILBEEZ_MAIN_CONTENT}
                </div>
            </td>
        </tr>
    </table>
</div>