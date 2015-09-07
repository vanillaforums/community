jQuery(document).ready(function($) {

    $("a[rel^='popable']").fancyZoom({closeOnClick: true});

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
