function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function convertUTCDateToLocalDate(date) {
    var newDate = new Date(date.getTime()+date.getTimezoneOffset()*60*1000);

    var offset = date.getTimezoneOffset() / 60;
    var hours = date.getHours();

    newDate.setHours(hours - offset);

    return newDate;
}

function ucwords(str) {
    //  discuss at: http://phpjs.org/functions/ucwords/
    // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // improved by: Waldo Malqui Silva
    // improved by: Robin
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Onno Marsman
    //    input by: James (http://www.james-bell.co.uk/)
    //   example 1: ucwords('kevin van  zonneveld');
    //   returns 1: 'Kevin Van  Zonneveld'
    //   example 2: ucwords('HELLO WORLD');
    //   returns 2: 'HELLO WORLD'

    return (str + '')
        .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
            return $1.toUpperCase();
        });
}


$( document ).ready(function() {
    var btcprice = 0;//parseFloat($.jStorage.get('btc-price'));
    var usdprice = parseFloat($.jStorage.get('usd-price'));
    var capUsd = parseFloat($.jStorage.get('marketcap-usd'));
    // var btcUsd = parseFloat($.jStorage.get('btc-usd'));

    (function marketPoll() {
        btcprice = parseFloat($.jStorage.get('btc-price'));

        // if (capUsd > 0 && btcprice > 0 && usdprice > 0) {
        //     setTimeout(marketPoll, 10000);
        // } else {
            $.ajax({
                url: '/api/coinmarketcap/ion',
                cache: false,
                success: function (data) {
                    capUsd = data.data.market_cap_usd;
                    capUsd = addCommas(parseFloat(capUsd).toFixed(2));
                    btcprice = parseFloat(data.data.price_btc).toFixed(8);
                    usdprice = parseFloat(data.data.price_usd).toFixed(2);

                    $.jStorage.set('marketcap-usd', capUsd, {TTL: 60000});
                    $.jStorage.set('usd-price', usdprice, {TTL: 60000});
                    $.jStorage.set('btc-price', btcprice, {TTL: 60000});

                    $("#market-cap").text("$" + capUsd + " USD");
                    $("#price-usd").text("$" + usdprice + " USD");
                    $("#price-btc").text(btcprice + " BTC");

                    setTimeout(marketPoll, 60000);
                },
                dataType: "json",
                timeout: 2000
            });
        // }
    })();
    //
    // if () {
    //     (function coinmarketcapIon() {
    //         $.ajax({
    //             url: '/api/coinmarketcap/ion',
    //             cache: false,
    //             success: function (data) {
    //                 capUsd = data.data.market_cap.usd;
    //                 capUsd = addCommas(parseFloat(capUsd).toFixed(2));
    //
    //                 $.jStorage.set('marketcap-usd', capUsd, {TTL: 60000});
    //
    //                 $("#market-cap").text("$" + capUsd + " USD");
    //                 $("#price-usd").text("$" + usdprice + " USD");
    //                 $("#price-btc").text(btcprice + " BTC");
    //             },
    //             dataType: "json",
    //             timeout: 2000
    //         })
    //     })();
    // }
    //
    // if (btcUsd > 0) {
    //     marketPoll();
    // } else {
    //     (function coinmarketcapBtc() {
    //         $.ajax({
    //             url: '/api/coinmarketcap/price/btc',
    //             cache: false,
    //             success: function (data) {
    //                 btcUsd = parseFloat(data.data.price.usd).toFixed(4);
    //
    //                 $.jStorage.set('btc-usd', btcUsd, {TTL: 60000});
    //
    //                 marketPoll();
    //             },
    //             dataType: "json",
    //             timeout: 2000
    //         })
    //     })();
    // }

    $(function () {

    });
});