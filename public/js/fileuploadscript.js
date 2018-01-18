

$(document).ready(function () {
    bootbox.setDefaults({locale: lang['locale-bootbox']});
  //  loadFolders();
    performLfmRequest('errors')
            .done(function (data) {
                var response = JSON.parse(data);
                for (var i = 0; i < response.length; i++) {
                    $('#alerts').append(
                            $('<div>').addClass('alert alert-warning')
                            .append($('<i>').addClass('fa fa-exclamation-circle'))
                            .append(' ' + response[i])
                            );
                }
            });

    $(window).on('dragenter', function () {
        $('#uploadModal').modal('show');
    });

    $('#upload').click(function () {
        $('#uploadModal').modal('show');
    });

    

});

// ======================
// ==  Navbar actions  ==
// ======================

$('#nav-buttons a').click(function (e) {
    e.preventDefault();
});


function performLfmRequest(url, parameter, type) {

    var data = defaultParameters();

    if (parameter != null) {
        $.each(parameter, function (key, value) {
            data[key] = value;
        });
    }

    return $.ajax({
        type: 'GET',
        dataType: type || 'text',
        url: lfm_route + '/' + url,
        data: data,
        cache: false
    }).fail(function (jqXHR, textStatus, errorThrown) {
        displayErrorResponse(jqXHR);
    });
}

function displayErrorResponse(jqXHR) {
    notify('<div style="max-height:50vh;overflow: scroll;">' + jqXHR.responseText + '</div>');
}

function displaySuccessMessage(data) {
    if (data == 'OK') {
        var success = $('<div>').addClass('alert alert-success')
                .append($('<i>').addClass('fa fa-check'))
                .append(' File Uploaded Successfully.');
        $('#alerts').append(success);
        setTimeout(function () {
            success.remove();
        }, 2000);
    }
}


function defaultParameters() {
    return {
        working_dir: $('#working_dir').val(),
        type: $('#type').val()
    };
}

function notImp() {
    notify('Not yet implemented!');
}

function notify(message) {
    bootbox.alert(message);
}
