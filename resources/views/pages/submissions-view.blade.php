@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="ibox">
			<div class="ibox-title float-e-margins">
				<h5>Form Submission</h5>
			</div>
			<div class="ibox-content">
				<h3>Submission Information</h3>

				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-2 control-label">Submitted By</label>
						<div class="col-md-10 form-control-static">{{ $submission->user->name() }}</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">Submission Date</label>
						<div class="col-md-10 form-control-static">{{ $submission->created_at->setTimezone(Auth::user()->timezone)->format('l, j M Y, g:ia') }}</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">Incident</label>
						<div class="col-md-10 form-control-static">{{ $submission->incident->name }}</div>
					</div>
				</div>

				<hr>

				@foreach ($categories as $category)
					<h3>{{ $category->name }}</h3>

					@foreach ($category->questions as $question)
						<p>
							<strong>{{ $question->num }}. {{ $question->question }}</strong><br>
							@if (isset($answers[$question->id]))
								@if ($question->type == 'image')
									@foreach ($answers[$question->id] as $answer)
										<img src="{{ $answer }}" class="img-thumbnail">
									@endforeach
								@else
									@foreach ($answers[$question->id] as $answer)
										{{ $answer }}
									@endforeach
								@endif
							@endif
						</p>
					@endforeach
				@endforeach

				<a href="/incidents/view/{{ $submission->incident_id }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to incident</a>
			</div>
		</div>
	</div>
@endsection
