$(document).ready(function () {

    // All of this is heavily based upon https://codewithmark.com/easily-edit-html-table-rows-or-cells-with-jquery.
    // Very useful!!! 🙂

    $(document).find('.btn_save').hide();
    $(document).find('.btn_cancel').hide();

    let background_highlight_class = 'bg-light';

    $(document).on('click', '.row_data', function (event) {
        // console.info(event);

        event.preventDefault();

        if ($(this).attr('edit_type') == 'button') {
            return false;
        }

        //make div editable
        $(this).closest('div').attr('contenteditable', 'true');

        //add bg css
        $(this).addClass('bg-light');

        $(this).focus();
    });


    //--->button > edit > start
    $(document).on('click', '.btn_edit', function (event) {
        event.preventDefault();
        let tbl_row = $(this).closest('tr');

        let row_id = tbl_row.attr('row_id');

        tbl_row.find('.btn_save').show();
        tbl_row.find('.btn_cancel').show();

        //hide edit button
        tbl_row.find('.btn_edit').hide();

        //make the whole row editable
        tbl_row.find('.row_data')
            .attr('contenteditable', 'true')
            .attr('edit_type', 'button')
            .addClass('bg-light')

        //--->add the original entry > start
        tbl_row.find('.row_data').each(function (index, val) {
            //this will help in case user decided to click on cancel button
            $(this).attr('original_entry', $(this).html());
        });
        //--->add the original entry > end

    });

    //--->button > edit > end


    //--->button > cancel > start
    $(document).on('click', '.btn_cancel', function (event) {
        event.preventDefault();

        let tbl_row = $(this).closest('tr');

        let row_id = tbl_row.attr('row_id');

        //hide save and cacel buttons
        tbl_row.find('.btn_save').hide();
        tbl_row.find('.btn_cancel').hide();

        //show edit button
        tbl_row.find('.btn_edit').show();

        //make the whole row editable
        tbl_row.find('.row_data')
            .attr('edit_type', 'click')
            .removeClass('bg-light')

        tbl_row.find('.row_data').each(function (index, val) {
            $(this).html($(this).attr('original_entry'));
        });
    });
    //--->button > cancel > end


    //--->save whole row > start
    $(document).on('click', '.btn_save', function (event) {
        event.preventDefault();
        let tbl_row = $(this).closest('tr');

        let row_id = tbl_row.attr('row_id');


        //hide Save and Cancel buttons
        tbl_row.find('.btn_save').hide();
        tbl_row.find('.btn_cancel').hide();

        //show edit button
        tbl_row.find('.btn_edit').show();


        //make the whole row editable
        tbl_row.find('.row_data')
            .attr('edit_type', 'click')
            .removeClass('bg-light')

        //--->get row data > start
        let arr = {};
        tbl_row.find('.row_data').each(function (index, val) {
            let col_name = $(this).attr('col_name');
            let col_val = $(this).html();
            arr[col_name] = col_val;
        });
        //--->get row data > end

        console.info('Saving whole row [' + row_id + ']');
        console.table(arr);

        //use the "arr"	object for your ajax call
        $.extend(arr, {row_id: row_id});

        //output to show
        $('.post_msg').html('<pre class="bg-success">' + JSON.stringify(arr, null, 2) + '</pre>')


    });
    //--->save whole row > end


    //--->save single field data > start
    $(document).on('focusout', '.row_data', function (event) {
        event.preventDefault();

        if ($(this).attr('edit_type') == 'button') {
            return false;
        }

        let row_id = $(this).closest('tr').attr('row_id');


        let row_div = $(this)
            .removeClass(background_highlight_class) // Restore bg css

        let col_name = row_div.attr('col_name');
        let col_val = row_div.html();

        let arr = {};
        arr[col_name] = col_val;

        console.info('Saving single field [' + row_id + '] ' + 'field: ' + col_name)
        console.table(arr);

        //use the "arr"	object for your ajax call
        $.extend(arr, {row_id: row_id});

        //output to show
        $('.post_msg').html('<pre class="bg-success">' + JSON.stringify(arr, null, 2) + '</pre>');

    })
    //--->save single field data > end


    //////////////////////////////////////////////////////////////////

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



