

$(function() {

    $('#project').change(function() {


	$.ajax({
	    url: 'api/projects/' + $('#project').val() + '/tasks',
	    dataType: 'json',
	    type: 'GET',
	    // This is query string i.e. project_id=123
	    // data: {country_id : $('#country').val()},
	    success: function(data) {
		console.log(data);		
		$('#task').empty(); // clear the current elements in select box
		for (row in data) {
		    $('#task').append($('<option></option>').attr('value', data[row].id).text(data[row].name));
		}
	    },
	    error: function(jqXHR, textStatus, errorThrown) {
		console.error(errorThrown);
	    }
	});
    });
});



