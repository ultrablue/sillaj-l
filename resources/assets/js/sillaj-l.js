$(document).ready(function () {
    let today = new Date();

    if (!$('#date').attr('value')) {
        let ISODate = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');
        $('#date').val(ISODate);
    }

    if (!$('#start').attr('value')) {
        let currentTime = today.getHours().toString().padStart(2, '0') + ':' + today.getMinutes().toString().padStart(2, '0');
        $('#start').val(currentTime);
    }


    $('#project').change(function () {


        $.ajax({
            url: 'api/projects/' + $('#project').val() + '/tasks',
            dataType: 'json',
            type: 'GET',
            // This is query string i.e. project_id=123
            // data: {country_id : $('#country').val()},
            success: function (data) {
                console.log(data);
                $('#task').empty(); // clear the current elements in select box
                for (row in data) {
                    $('#task').append($('<option></option>').attr('value', data[row].id).text(data[row].name));
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(errorThrown);
            }
        });
    });
});



