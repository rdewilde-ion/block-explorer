
$( document ).ready(function() {
    jQuery("time.timeago").timeago();

    $.ajax({
        url: "/api/latestblocks?limit=1",
        context: document.body
    }).done(function(data) {

            blockHeight = data.data[0].height;
            $("#outstanding").text(addCommas((data.data[0]['outstanding']*1).toString()) + ' ION');

        });

    (function poll() {
        setTimeout(function() {
            $.ajax({
                url: "/api/latestblocks",
                type: "GET",
                data: { height: blockHeight },
                success: function(data) {
                    blockHeight = data.data[0].height; // Store Blockheight
                    $("#outstanding").text(addCommas((data.data[0]['outstanding']*1).toString()) + ' ION');

                },
                dataType: "json",
                complete: poll,
                timeout: 2000
            })
        }, 55000);
    })();
});