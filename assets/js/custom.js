(function ($) {
    // Disable product link clicks on woo thank you page
    $('h3.product-name a').on("click", function (e) {
        e.preventDefault();
    });
    $('td.download-product a').on("click", function (e) {
        e.preventDefault();
    });
})(jQuery);