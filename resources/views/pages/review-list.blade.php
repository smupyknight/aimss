@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="ibox">
			<div class="ibox-title float-e-margins">
				<h5>Incidents for Review</h5>
			</div>
			<div class="ibox-content">

				@if ($incidents->count())
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Event</th>
								<th>Incident</th>
								<th>Num Reports</th>
								<th>Status</th>
								<th>Last Report</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody class="table_body">
							@foreach ($incidents as $incident)
								<tr>
									<td>{{ $incident->event_name }}</td>
									<td>{{ $incident->name }}</td>
									<td>{{ $incident->num_reports }}</td>
									<td>{{ ucfirst($incident->status) }}</td>
									<td><span title="{{ (new Carbon\Carbon($incident->last_report_time))->setTimezone(Auth::user()->timezone)->format('l, j F Y, g:ia') }}">{{ (new Carbon\Carbon($incident->last_report_time))->diffForHumans() }}</span></td>
									<td>
										@if ($incident->status == 'open')
											<button type="button" class="btn btn-default btn-xs" onclick="show_modal('/review/start/{{ $incident->id }}')">Start Review</button>
										@else
											<a href="/review/do/{{ $incident->id }}" class="btn btn-default btn-xs">Continue Review</a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					{{ $incidents->links() }}
				@else
					<p>There are no incidents for review at this time.</p>
				@endif
			</div>
		</div>
	</div>
@endsection
