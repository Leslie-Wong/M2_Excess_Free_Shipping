define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/cart/totals-processor/default'
    ],
    function ($, ko, Component, quote, totalsDefaultProvider) {
        var quoteData = quote;
        return Component.extend({
            getFreevalue:ko.observable(window.checkoutConfig.excessFreeShipping.freevalue),
            isDisplayed: function () {
                let total_segments = quoteData.totals().total_segments;
                if(total_segments.find(function(segment){ return segment.code == "excessfreeshipping"; }))
                    return true;
                else
                    return false;
            },
            getTitle: function () {
                return window.checkoutConfig.excessFreeShipping.title
            },
            getShippingcharge: function () {
                let total_segments = quoteData.totals().total_segments;
                segments = total_segments.find(function(segment){ return segment.code == "excessfreeshipping"; });
                return '-' + quoteData.totals().quote_currency_code + '$' + parseFloat(Math.abs(segments.value)).toFixed(2) ;
            }
        });
    }
);