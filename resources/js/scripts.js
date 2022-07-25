$(function () {
    var edited_element, result_table;
    $(document).on('click', '.edit', function () {
        console.log('test');
        var id = $(this).attr('id');
        id = id.split('_')[1];
        $('#title_yt_' + id).hide();
        $('#input_title_yt_' + id).attr('type', 'text');
        $(this).parents().eq(3).addClass('active');
        if (edited_element != null) {
            edited_element = null;
        }
    })
    $('.input_yt').on('keyup', function (e) {
        console.log(e);
        e.preventDefault();
        var data = {
            'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            'value': $(this).val()
        };
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/similarSongs",
            data: {_token: $('input[name="_token"]').val(), data: JSON.stringify(data)}
        }).done(function (data) {
            var result = data;
            var toAppend = '';
            result.forEach(function (item) {
                console.log(item);
                toAppend += '<div class="row">\n' +
                    '   <div class="offset-1  col-1">\n' +
                    '      <input type="checkbox" class="" name="songs[]" value="' + item.uri + '"\n' +
                    '      />\n' +
                    '   </div>\n' +
                    '   <div class="col-4">\n' +
                    '      <p id="title_spotify_xx">' + item.title + '</p>\n' +
                    '      <input type="hidden" name="title_spotify" class="form-control" id="input_title_spotify_xx" value="' + item.title + '"/>\n' +
                    '   </div>\n' +
                    '<div class="col-1">\n' +
                    '<img src=' + item.image + ' class="img-fluid" />' +
                    '   </div>\n' +
                    '</div>'
            })
            console.log(edited_element);
            if (edited_element == null) {
                edited_element = 'resultSongs_' + (Math.floor(Math.random() * (1000 - 1)) + 1);
                var clone_result = $('#result_songs').clone();
                var result_place = $(clone_result).find('.to_copy');
                $(result_place).append(toAppend);
                clone_result.removeClass('invisible').attr('id', edited_element);
                $('.active').find('input[type="checkbox"]').removeAttr('checked');
                $('.active').append(clone_result);
                $('.active').removeClass('active');
            }
            else {
                $('#' + edited_element).find('.to_copy').html(toAppend);
            }
        }).fail(function (msg) {
            console.log(msg)
        });
    });

    $('#create_playlist').on('click', function () {
        var data = [];
        $('#songs_to_playlist').find('input[type="checkbox"]').each(function () {
            if ($(this).attr('checked') == 'checked') {
                data.push($(this).val());
            }
        });
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/createSpotifyPlaylist",
            data: {_token: $('input[name="_token"]').val(), data: JSON.stringify(data), name: $('#playlistName').val()}
        }).done(function (msg) {
            console.log(msg);
        }).fail(function (msg) {
            console.log(msg)
        })
    })
});
