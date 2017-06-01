@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="ibox">
			<div class="ibox-title float-e-margins">
				<h5>Review Incident</h5>
			</div>
			<div class="ibox-content">

				@if ($errors->count())
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
							{{ $error }}<br>
						@endforeach
					</div>
				@endif

				<form action="/review/do/{{ $incident->id }}" method="post">

					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Question and given answers</th>
								<th>Accepted answer</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($categories as $category)
								<tr class="active">
									<td colspan="2"><strong>{{ $category->name }}</strong></td>
								</tr>
								@foreach ($category->questions as $question)
									<?php
									$default = $defaults[$question->id] ? $defaults[$question->id] : [''];
									?>
									<tr data-type="{{ $question->type }}" data-question-id="{{ $question->id }}"{!! $question->conditional_question_id ? ' data-conditional-question-id="' . $question->conditional_question_id . '" data-conditional-question-answer="' . e($question->conditional_question_answer) . '"' : '' !!}>
										<td>
											<strong>{{ $question->num }}. {{ $question->question }}</strong>
											<div>
												@if (isset($answers[$question->id]))
													@if ($question->type == 'image')
														@foreach ($answers[$question->id] as $submission)
															<img src="{{ $submission->answers[0] }}" class="img-thumbnail">
														@endforeach
													@else
														@foreach ($answers[$question->id] as $submission)
															<button type="button" class="btn btn-default btn-xs" data-answers="{{ implode('___', $submission->answers) }}">{{ implode(', ', $submission->answers) }} <i class="fa fa-arrow-right"></i></button>
														@endforeach
													@endif
												@endif
											</div>
										</td>
										<td>
											@if ($question->type == 'boolean')
												<label class="radio-inline"><input type="radio" name="answers[{{ $question->id }}][]" value="Yes"{{ $default[0] == 'Yes' ? ' checked' : '' }}> Yes</label>
												<label class="radio-inline"><input type="radio" name="answers[{{ $question->id }}][]" value="No"{{ $default[0] == 'No' ? ' checked' : '' }}> No</label>
											@elseif ($question->type == 'boolean-null')
												<label class="radio-inline"><input type="radio" name="answers[{{ $question->id }}][]" value="Yes"{{ $default[0] == 'Yes' ? ' checked' : '' }}> Yes</label>
												<label class="radio-inline"><input type="radio" name="answers[{{ $question->id }}][]" value="No"{{ $default[0] == 'No' ? ' checked' : '' }}> No</label>
												<label class="radio-inline"><input type="radio" name="answers[{{ $question->id }}][]" value=""{{ $default[0] == '' ? ' checked' : '' }}> N/A</label>
											@elseif ($question->type == 'checkboxes')
												@foreach (explode("\r\n", $question->options) as $option)
													<div class="checkbox"><label class="checkbox-inline"><input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option }}"{{ in_array($option, $default) ? ' checked' : '' }}> {{ $option }}</label>
												@endforeach
											@elseif ($question->type == 'select')
												<select name="answers[{{ $question->id }}][]" class="form-control">
													@foreach (explode("\r\n", $question->options) as $option)
														<option value="{{ $option }}"{{ in_array($option, $default) ? ' selected' : '' }}>{{ $option }}</option>
													@endforeach
												</select>
											@elseif ($question->type == 'shorttext')
												<input type="text" name="answers[{{ $question->id }}][]" value="{{ $default[0] }}" class="form-control">
											@elseif ($question->type == 'longtext')
												<textarea name="answers[{{ $question->id }}][]" class="form-control" rows="6">{{ $default[0] }}</textarea>
											@elseif ($question->type == 'datetime')
												<div class="input-group">
													<span class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</span>
													<input type="text" name="answers[{{ $question->id }}][]" value="{{ $default[0] }}" class="form-control datetime">
												</div>
											@elseif ($question->type == 'image')
												All images are always included.
											@endif
										</td>
									</tr>
								@endforeach
							@endforeach
						</tbody>
					</table>

					<div class="text-right">
						<button type="submit" name="action" value="continue" class="btn btn-info">Save and Continue</button>
						<button type="submit" name="action" value="finish" class="btn btn-primary">Save and Finish</button>
					</div>
					{{ csrf_field() }}
				</form>

			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		// Make clicking buttons on the left set the answer on the right
		$('button[data-answers]').on('click', function(event) {
			var type = $(this).closest('tr').data('type');
			var answers = $(this).data('answers').split('___');
			var cell = $(this).closest('td').next('td');

			switch (type) {
				case 'boolean':
				case 'boolean-null':
					cell.find('input[value="' + answers[0] + '"]').click();
					break;
				case 'checkboxes':
					cell.find('input').prop('checked', '');
					answers.forEach(function(answer) {
						cell.find('input[value="' + answer + '"]').prop('checked', 'checked');
					});
					break;
				case 'select':
					cell.find('select').val(answers[0]);
					break;
				case 'shorttext':
				case 'datetime':
					cell.find('input').val(answers[0]);
					break;
				case 'longtext':
					cell.find('textarea').val(answers[0]);
					break;
			}

			update_dependants($(this).closest('tr'));
		});

		/**
		 * Show or hide child questions of the given row recursively.
		 */
		function update_dependants(parent_row)
		{
			var parent_id = $(parent_row).data('question-id');
			var parent_answer = $(parent_row).find('input').first().val();
			var parent_is_visible = $(parent_row).is(':visible');

			$('tr[data-conditional-question-id="' + parent_id + '"]').each(function() {
				var answer_matches = $(this).data('conditional-question-answer') == parent_answer;
				$(this).toggle(parent_is_visible && answer_matches);
				update_dependants(this);
			});
		}

		$('tr[data-question-id]').each(function() {
			update_dependants(this);
		});

		$('table input, table select, table textarea').on('change', function() {
			update_dependants($(this).closest('tr'));
		});

		// Make date fields show a calendar
		$('.datetime').closest('td').css('position', 'relative');
		$('.datetime').datetimepicker({
			format: 'DD/MM/YYYY HH:mm',
			stepping: 30,
			useCurrent: false
		});
	</script>
@endsection
