$(function () {

    var data = {
        video_id: 1,
        start_time: 1321654987654,
        end_time: 1321654987654,
        extras: "fdg sdfg dfg dfg df",
        published: 1,
        looping: 1,
    }

    var jqxhr = $.post("/setSnippet", data, function () {

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
