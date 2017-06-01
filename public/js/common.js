function show_modal(url)
{
	$.get(url)
		.done(function(response) {
			$('body').append(response);
		})
		.fail(function(jqxhr, settings, exception) {
			alert(exception);
		});
}

