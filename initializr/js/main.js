$(function () {

    var domain = 'http://snippet.knedl.si';
    domain = '';

    var data = {
        video_id: 1,
        start_time: 1321654987654,
        end_time: 1321654987654,
        extras: "fdg sdfg dfg dfg df",
        published: 1,
        looping: 1,
    }

    var jqxhr = $.post(domain + "/setSnippet", data, function () {

    })
        .done(function () {

        })
        .fail(function () {

        })
        .always(function () {

        });

    jqxhr.always(function () {

    });


    var data = {
        name: "my playstlie",
        snippets: "16,18,20",
        published: 1,
        video_id: 1
    }

    var jqxhr = $.post(domain + "/setPlaylist", data, function () {

    })
        .done(function () {

        })
        .fail(function () {

        })
        .always(function () {

        });

    jqxhr.always(function () {

    });

});
