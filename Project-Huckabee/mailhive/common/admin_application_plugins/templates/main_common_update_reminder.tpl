{if $MAILBEEZ_UPDATE_REMINDER}
    <script type="text/javascript">
        <!--
        window.setTimeout("mbUpdateReminder()", 1000);
        {literal}
        function mbUpdateReminder() {
        {/literal}
            Check = confirm("Check mailbeez.com for Updates?\n(You can always use the button upper right)\n{if ($MAILBEEZ_MAILHIVE_POPUP_MODE == 'off') }(this will redirect to the mailbeez server){/if}");
        {literal}
            if (Check == true) {
        {/literal}

        {if ($MAILBEEZ_MAILHIVE_POPUP_MODE == 'CeeBox') }
             jQuery.fn.ceebox.popup("<a rel='width:600' href='{$MAILBEEZ_VERSION_CHECK_URL}'>link</a>",
                     {literal}
                     {overlayOpacity:0.0, animSpeed: 100, fadeIn: 0, titles: false	}
                     {/literal}
        );
        {else}
             document.location.href="{$MAILBEEZ_VERSION_CHECK_URL}";
        {/if}
        {literal}
         }
        }
        {/literal}
        //-->
    </script>
{/if}