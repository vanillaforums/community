jQuery(document).ready(function($) {

    $("a[rel^='popable']").prettyPhoto({
        animationSpeed: 'fast', /* fast/slow/normal */
        padding: 40, /* padding for each side of the picture */
        opacity: 0.35, /* Value betwee 0 and 1 */
        showTitle: true, /* true/false */
        allowresize: true, /* true/false */
        counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
        theme: 'dark_square', /* light_rounded / dark_rounded / light_square / dark_square */
        callback: function(){}
    });

    // hijack addon deletes
    $('li.DeleteAddon a, li.ApproveAddon a').popup({
        confirm: true,
        followConfirm: true
    });

    // Set up paging
    if ($.morepager)
        $('.MorePager').morepager({
            pageContainerSelector: '#Discussion:last'
        });

});