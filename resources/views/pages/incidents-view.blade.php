@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="ibox">
			<div class="ibox-title float-e-margins">
				<h5>{{ $incident->name }} at {{ $incident->event->name }}</h5>
			</div>
			<div class="ibox-content">
				<h3>Incident Information</h3>

				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-2 control-label">Time of incident</label>
						<div class="col-md-10 form-control-static">{{ $incident->incident_time->setTimezone($incident->event->timezone)->format('j M Y, g:ia') }}</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">Status</label>
						<div class="col-md-10 form-control-static">{{ ucfirst($incident->status) }}</div>
					</div>
				</div>

				<hr>
				<h3>Reports</h3>

				@if (count($incident->formSubmissions))
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>User</th>
								<th>Created At</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($incident->formSubmissions as $submission)
								<tr>
									<td>{{ $submission->user->first_name }} {{ $submission->user->last_name }}</td>
									<td>{{ $submission->created_at->setTimezone(Auth::user()->timezone)->format('j M Y, g:ia') }}</td>
									<td><a href="/submissions/view/{{ $submission->id }}" class="btn btn-default btn-xs">View</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					<p>There are no reports yet.</p>
				@endif

				<a href="/events/view/{{ $incident->event_id }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to event</a>
			</div>
		</div>
	</div>
@endsection
