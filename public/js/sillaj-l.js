$(document).ready(function () {

    // All of this is heavily based upon https://codewithmark.com/easily-edit-html-table-rows-or-cells-with-jquery.
    // Very useful!!! 🙂

    var ajax_url = "api/events/";

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
        tbl_row.find('.row_data').each(function (index,
            val) {
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

        tbl_row.find('.row_data').each(function (index,
            val) {
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
        tbl_row.find('.row_data').each(function (index,
            val) {
            let col_name = $(this).attr('col_name');
            let col_val = $(this).html();
            arr[col_name] = col_val;
        });
        //--->get row data > end

        $.ajax({
            url: ajax_url + row_id,
            type: 'PUT',
            dataType: 'json',
            data: arr,
            beforeSend: function (xhr,
                settings) {
                // console.info('Saving single field [' + row_id + '] ');
                // console.table(arr);
            },
            success: function (data,
                status,
                xhr) {
                // console.log("Save was successful! 😊 " + status);
                // console.table(data);
                // TODO Can we make this a function or something? See Issue #16.
                $('#success-toast-header > strong').html("Event updated.");
                $('#success-toast-body').html("Event ID " + row_id + " was updated.");
                $('#toast-success').toast({ delay: 2500, animation: true }).toast('show');
            },
            error: function (xhr,
                status,
                error) {
                // console.warn("PATCH failed! " + status + " " + error);

                // TODO Can we make this a function or something? See Issue #16.
                $('#failure-toast-header > strong').html("☹ Something went wrong.");
                $('#failure-toast-body').html("Event ID " + row_id + " was not updated. You might want to try again.");
                $('#toast-failure').toast({ autohide: false, animation: true }).toast('show');

            },
        }
        );


    });
    //--->save whole row > end


    //--->save single field data > start
    $(document).on('focusout', '.row_data', function (event) {
        event.preventDefault();

        if ($(this).attr('edit_type') == 'button') {
            return false;
        }

        //get the original entry
        var original_entry = $(this).attr('original_entry');

        let row_id = $(this).closest('tr').attr('row_id');


        let row_div = $(this)
            .removeClass(background_highlight_class) // Restore bg css

        let col_name = row_div.attr('col_name');
        let col_val = row_div.html();

        let arr = {};
        //get the col name and value
        arr[col_name] = col_val;
        //get row id value
        arr['row_id'] = row_id;

        if (original_entry != col_val) {
            console.log("Valid change detected, moving on with PUT.");
            //remove the attr so that new entry can take place
            $(this).removeAttr('original_entry');

            //ajax api json data

            var data_obj =
            {
                id: row_id,
                col_name: col_name,
                col_val: col_val,
                call_type: 'single_entry',
            };
            data_obj[col_name] = col_val;
            console.table(data_obj);

            //call ajax api to update the database record
            $.ajax({
                url: ajax_url + row_id,
                type: 'PUT',
                dataType: 'json',
                data: data_obj,
                beforeSend: function (xhr,
                    settings) {
                    console.info('Saving single field [' + row_id + '] ' + 'field: ' + col_name);
                },
                success: function (data,
                    status,
                    xhr) {
                    console.log("Save was successful! 😊 " + status);
                    // console.table(data);

                    // TODO Can we make this a function or something? See Issue #16.
                    $('#success-toast-header > strong').html("Event updated.");
                    $('#success-toast-body').html("Event ID " + row_id + " was updated.");
                    $('#toast-success').toast({ delay: 2500, animation: true }).toast('show');


                },
                error: function (xhr,
                    status,
                    error) {
                    // console.warn("PATCH failed! " + status + " " + error);
                    // TODO Can we make this a function or something? See Issue #16.
                    $('#failure-toast-header > strong').html("☹ Something went wrong.");
                    $('#failure-toast-body').html("Event ID " + row_id + " was not updated. You might want to try again.");
                    $('#toast-failure').toast({ autohide: false, animation: true }).toast('show');
                },
            }
            );
        } else {
            console.log("Nothing changed, nothing to PUT.");
            $('.post_msg').html('');
        }


        //use the "arr"	object for your ajax call
        $.extend(arr, { row_id: row_id });

        //output to show
        $('.post_msg').html('<pre class="bg-success">' + JSON.stringify(arr, null, 2) + '</pre>');

    })
    //--->save single field data > end


    //////////////////////////////////////////////////////////////////

    let today = new Date();

    // This is part of issue-47.
    if (!$('#event_date').attr('value')) {
        let ISODate = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');
        $('#event_date').val(ISODate);
    }

    if (!$('#time_start').attr('value')) {
        // Round the current time's minutes to the nearest 5 (or whatever).
        let roundTo = 5;
        let currentMinutes = Math.round(today.getMinutes() / roundTo) * roundTo;
        // console.log(currentMinutes);
        // Create a string with the current time in it and put it in the start time as a courtesy for the user.
        let currentTime = today.getHours().toString().padStart(2, '0') + ':' + currentMinutes.toString().padStart(2, '0');
        $('#time_start').val(currentTime);
        // Move the cursor to the end time field.
        $('#time_start').focus().select();
    }

    $('#time_start').on('focus', function () {
        // $(this)[0].setSelectionRange(3, 4);
    });

    $('#project_id').change(function () {


        $.ajax({
            url: 'api/projects/' + $('#project_id').val() + '/tasks',
            dataType: 'json',
            type: 'GET',
            // This is query string i.e. project_id=123
            // data: {country_id : $('#country').val()},
            success: function (data) {
                //console.log(data);
                $('#task_id').empty(); // clear the current elements in select box
                for (row in data) {
                    $('#task_id').append($('<option></option>').attr('value', data[row].id).text(data[row].name));
                }
            },
            error: function (jqXHR,
                textStatus,
                errorThrown) {
                console.error(errorThrown);
            }
        });
    });
})
    ;



