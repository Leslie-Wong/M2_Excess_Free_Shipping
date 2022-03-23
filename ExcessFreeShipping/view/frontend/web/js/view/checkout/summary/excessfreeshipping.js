define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote'
    ],
    function ($, ko, Component, quote) {
        var quoteData = quote;
        return Component.extend({
            isDisplayed: function () {
                if(window.checkoutConfig.excessFreeShipping.freevalue != null)
                    return true;
                else
                    return false;
            },
            getTitle: function () {
                return window.checkoutConfig.excessFreeShipping.title
            },
            getShippingcharge: function () {
                return '-' + quoteData.totals().quote_currency_code + '$' + parseFloat(Math.abs(window.checkoutConfig.excessFreeShipping.freevalue)).toFixed(2) ;
            }
        });
    }
);