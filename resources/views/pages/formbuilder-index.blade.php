@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Form Builder</h5>
						<div class="ibox-tools">
							<a href="javascript:create_category()" class="btn btn-primary btn-xs">Create Category</a>
							<a href="javascript:create_question()" class="btn btn-primary btn-xs">Create Question</a>
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Num</th>
									<th>Question</th>
									<th>Type</th>
									<th>Reference Image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody class="table_body">
								@foreach ($categories as $category)
									<tr data-id="{{ $category->id }}" data-name="{{ $category->name }}">
										<th colspan="4">{{ $category->name }}</th>
										<th>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="#" onclick="edit_category(this);return false">Edit</a></li>
													<li class="divider"></li>
													<li><a href="#" onclick="delete_category(this);return false">Delete</a></li>
												</ul>
											</div>
										</th>
									</tr>
									@foreach ($category->questions as $question)
										<tr data-id="{{ $question->id }}"
											data-category-id="{{ $category->id }}"
											data-conditional-question-id="{{ $question->conditional_question_id }}"
											data-question="{{ $question->question }}"
											data-type="{{ $question->type }}"
											data-options="{{ $question->options }}"
											data-conditional-question-answer="{{ $question->conditional_question_answer }}"
											data-show-to-spectator="{{ $question->show_to_spectator }}"
											data-show-to-crew="{{ $question->show_to_crew }}"
											data-show-to-medical="{{ $question->show_to_medical }}"
											data-show-to-organiser="{{ $question->show_to_organiser }}"
											data-show-to-scrutineer="{{ $question->show_to_scrutineer }}">

											<td>{{ $question->num }}</td>
											<td>
												@if ($question->conditionalQuestion)
													<em class="text-muted">(Shown if question #{{ $question->conditionalQuestion->num }}'s answer is {{ $question->conditional_question_answer ? $question->conditional_question_answer : 'N/A' }})</em>
													<br>
												@endif
												{{ $question->question }}
											</td>
											<td>
												@if ($question->type == 'boolean')
													Yes/No
												@elseif ($question->type == 'boolean-null')
													Yes/No/NA
												@elseif ($question->type == 'shorttext')
													Short Text
												@elseif ($question->type == 'longtext')
													Long Text
												@elseif ($question->type == 'checkboxes')
													Checkboxes
												@elseif ($question->type == 'select')
													Select
												@elseif ($question->type == 'datetime')
													Date and Time
												@else
													Image
												@endif
											</td>
											<td>
												@if ($question->reference_image)
													<a href="/storage/question-reference-images/{{ $question->reference_image }}">View</a>
												@else
													<span class="text-muted">None</span>
												@endif
											</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" onclick="edit_question(this);return false">Edit</a></li>
														<li class="divider"></li>
														<li><a href="#" onclick="delete_question(this);return false">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="hidden" id="category-modal-content">
		<form action="/formbuilder/create-category" method="post" class="form-horizontal">
			<div class="form-group">
				<label class="col-md-4 control-label">Position</label>
				<div class="col-md-8">
					<select name="after" class="form-control">
						<option value="0">First category</option>
						@foreach ($categories as $category)
							<option value="{{ $category->id }}">After &quot;{{ $category->name }}&quot;</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">Name</label>
				<div class="col-md-8"><input type="text" name="name" class="form-control"></div>
			</div>
			{!! csrf_field() !!}
		</form>
	</div>

	<div class="hidden" id="question-modal-content">
		<form action="/formbuilder/create-question" method="post" enctype="multipart/form-data">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-md-4 control-label">Category</label>
					<div class="col-md-8">
						<select name="category_id" class="form-control">
							@foreach ($categories as $category)
								<option value="{{ $category->id }}">{{ $category->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Position</label>
					<div class="col-md-8">
						<select name="after" class="form-control">
							<option value="0">First question</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Question</label>
					<div class="col-md-8"><input type="text" name="question" class="form-control"></div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Type</label>
					<div class="col-md-8">
						<select name="type" class="form-control">
							<option value="shorttext">Short Text</option>
							<option value="longtext">Long Text</option>
							<option value="boolean">Yes/No</option>
							<option value="boolean-null">Yes/No/NA</option>
							<option value="checkboxes">Checkboxes</option>
							<option value="select">Select (one only)</option>
							<option value="datetime">Date and Time</option>
							<option value="image">Image</option>
						</select>
					</div>
				</div>
				<div class="form-group" id="options">
					<label class="col-md-4 control-label">Options</label>
					<div class="col-md-8">
						<textarea name="options" class="form-control" rows="4"></textarea>
						<span class="help-block">Put one option on each line.</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Reference Image</label>
					<div class="col-md-8">
						<input type="file" name="reference_image" class="form-control">
						<label class="checkbox-inline"><input type="checkbox" name="remove_reference_image" value="1"> Remove existing image</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Show this question</label>
					<div class="col-md-8">
						<select name="conditional_question_id" class="form-control">
							<option value="">Always</option>
						</select>
					</div>
				</div>
				<div class="form-group answer-fields">
					<div class="col-md-8 col-md-offset-4">
						<div class="input-group">
							<input type="text" name="conditional_question_answer" class="form-control">
							<span class="input-group-btn">
								<button type="button" class="btn btn-default" data-value="yes">Yes</button>
							</span>
							<span class="input-group-btn">
								<button type="button" class="btn btn-default" data-value="no">No</button>
							</span>
							<span class="input-group-btn">
								<button type="button" class="btn btn-default" data-value="">N/A</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">Availability</label>
				<table class="table">
					<tr>
						<td>Spectator</td>
						<td>
							<label class="checkbox-inline"><input type="radio" name="show_to_spectator" value="required"> Required</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_spectator" value="optional"> Optional</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_spectator" value="no"> Don't show</label>
						</td>
					</tr>
					<tr>
						<td>Crew</td>
						<td>
							<label class="checkbox-inline"><input type="radio" name="show_to_crew" value="required"> Required</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_crew" value="optional"> Optional</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_crew" value="no"> Don't show</label>
						</td>
					</tr>
					<tr>
						<td>Medical</td>
						<td>
							<label class="checkbox-inline"><input type="radio" name="show_to_medical" value="required"> Required</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_medical" value="optional"> Optional</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_medical" value="no"> Don't show</label>
						</td>
					</tr>
					<tr>
						<td>Organiser</td>
						<td>
							<label class="checkbox-inline"><input type="radio" name="show_to_organiser" value="required"> Required</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_organiser" value="optional"> Optional</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_organiser" value="no"> Don't show</label>
						</td>
					</tr>
					<tr>
						<td>Scrutineer</td>
						<td>
							<label class="checkbox-inline"><input type="radio" name="show_to_scrutineer" value="required"> Required</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_scrutineer" value="optional"> Optional</label>
							<label class="checkbox-inline"><input type="radio" name="show_to_scrutineer" value="no"> Don't show</label>
						</td>
					</tr>
				</table>
			</div>
			{!! csrf_field() !!}
		</form>
	</div>
@endsection

@section('js')
	<script src="/js/modalform.js"></script>
	<script>
	var category_modal_html = $('#category-modal-content').html();
	$('#category-modal-content').remove();

	var question_modal_html = $('#question-modal-content').html();
	$('#question-modal-content').remove();

	function create_category()
	{
		modalform.dialog({
			bootbox: {
				title: 'Create Category',
				message: category_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Create Category',
						className: 'btn-primary'
					}
				}
			},
			autofocus: 'name'
		});

		var select = $('select[name="after"]');
		select.val(select.find('option').last().val());
	}

	function create_question()
	{
		modalform.dialog({
			bootbox: {
				title: 'Create Question',
				message: question_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Create Question',
						className: 'btn-primary'
					}
				}
			},
			autofocus: 'question'
		});

		$('.modal [name="remove_reference_image"]').closest('label').remove();

		$('select[name="category_id"]').on('change', function() {
			update_position_list();
		}).trigger('change');

		$('select[name="type"]').on('change', function() {
			var type = $(this).val();

			if (type == 'checkboxes' || type == 'select') {
				$('#options').show().focus();
			} else {
				$('#options').hide();
			}
		}).trigger('change');

		$('select[name="conditional_question_id"]').on('change', function() {
			if ($(this).val() == '') {
				$('.answer-fields').hide();
			} else {
				$('.answer-fields').show();
			}
		}).trigger('change');
	}

	function edit_category(anchor)
	{
		modalform.dialog({
			bootbox: {
				title: 'Edit Category',
				message: category_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Save Changes',
						className: 'btn-primary'
					}
				}
			}
		});

		var tr = $(anchor).closest('tr');

		$('.modal form').attr('action', '/formbuilder/edit-category/' + tr.data('id'));
		$('select[name="after"] option[value="' + tr.data('id') + '"]').remove();
		$('select[name="after"]').val(tr.prev().data('id') || 0);
		$('input[name="name"]').val(tr.data('name'));
	}

	function edit_question(anchor)
	{
		modalform.dialog({
			bootbox: {
				title: 'Edit Question',
				message: question_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Save Changes',
						className: 'btn-primary'
					}
				}
			}
		});

		var tr = $(anchor).closest('tr');

		$('.modal form').attr('action', '/formbuilder/edit-question/' + tr.data('id'));
		$('select[name="category_id"]').val(tr.data('category-id'));
		$('select[name="conditional_question_id"]').val(tr.data('conditional-question-id'));

		$('select[name="category_id"]').on('change', function() {
			update_position_list(tr.prev().data('id'), tr.data('conditional-question-id'), tr.data('id'));
		}).trigger('change');

		$('input[name="question"]').val(tr.data('question'));
		$('select[name="type"]').val(tr.data('type'));
		$('textarea[name="options"]').val(tr.data('options'));
		$('input[name="conditional_question_answer"]').val(tr.data('conditional-question-answer'));
		$('input[name="show_to_spectator"][value="' + tr.data('show-to-spectator') + '"]').attr('checked', 'checked');
		$('input[name="show_to_crew"][value="' + tr.data('show-to-crew') + '"]').attr('checked', 'checked');
		$('input[name="show_to_medical"][value="' + tr.data('show-to-medical') + '"]').attr('checked', 'checked');
		$('input[name="show_to_organiser"][value="' + tr.data('show-to-organiser') + '"]').attr('checked', 'checked');
		$('input[name="show_to_scrutineer"][value="' + tr.data('show-to-scrutineer') + '"]').attr('checked', 'checked');

		$('select[name="type"]').on('change', function() {
			var type = $(this).val();

			if (type == 'checkboxes' || type == 'select') {
				$('#options').show().focus();
			} else {
				$('#options').hide();
			}
		}).trigger('change');

		$('select[name="conditional_question_id"]').on('change', function() {
			if ($(this).val() == '') {
				$('.answer-fields').hide();
			} else {
				$('.answer-fields').show();
			}
		}).trigger('change');
	}

	function update_position_list(selected_after_id, selected_conditional_id, exclude_question_id)
	{
		var after_select = $('[name="after"]');
		var conditional_select = $('[name="conditional_question_id"]');
		after_select.find('option + option').remove();
		conditional_select.find('option + option').remove();

		$.ajax({
			url: '/formbuilder/questions/' + $('[name="category_id"]').val(),
			method: 'GET',
			success: function(response) {
				$.each(response, function(index, question) {
					var text = 'After #' + question.num + ': ' + question.question;
					after_select.append($('<option/>').val(question.id).text(text));

					var text = 'If the answer to #' + question.num + ': ' + question.question + ' is...';
					conditional_select.append($('<option/>').val(question.id).text(text));
				});

				after_select.find('option[value="' + exclude_question_id + '"]').remove();
				after_select.val(selected_after_id || after_select.find('option').last().val());

				if (after_select.val() == null) {
					after_select.val(0);
				}

				conditional_select.find('option[value="' + exclude_question_id + '"]').remove();
				conditional_select.val(selected_conditional_id || '');

				$('.answer-fields').toggle(conditional_select.val() != '');
			}
		});
	}

	function delete_category(anchor)
	{
		var tr = $(anchor).closest('tr');

		modalform.dialog({
			bootbox: {
				title: 'Are you sure you want to delete this category?',
				message: ''+
					'<form action="/formbuilder/delete-category/' + tr.data('id') + '" method="post">'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Category',
						className: 'btn-danger'
					}
				}
			}
		});
	}

	function delete_question(anchor)
	{
		var tr = $(anchor).closest('tr');

		modalform.dialog({
			bootbox: {
				title: 'Are you sure you want to delete this question?',
				message: ''+
					'<form action="/formbuilder/delete-question/' + tr.data('id') + '" method="post">'+
						'<p>The question will still appear on existing forms, but not on new ones.</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Question',
						className: 'btn-danger'
					}
				}
			}
		});
	}

	$(document).on('click', '.answer-fields button', function() {
		console.log('here');
		$(this).closest('.answer-fields').find('input').val($(this).data('value'));
	});
	</script>
@endsection
