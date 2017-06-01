@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">

		<div class="tabs-container">
			<div class="tab-content">
				<div id="tab-recent" class="tab-pane active">
					<div class="panel-body">
						<div class="row">
							<form method="GET" action="/events/pending">
								<div class="col-sm-12">
									<div class="input-group">
										<input type="text" placeholder="Search Events" class="input form-control" name="search" value="{{ Request::get('search') }}">
										<span class="input-group-btn">
											<button type="submit" class="btn btn btn-default"> <i class="fa fa-search"></i> Search</button>
										</span>
									</div>
								</div>
							</form>
						</div>

						@if ($events->count())
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Name</th>
										<th>Submitter</th>
										<th>Start</th>
										<th>End</th>
										<th>Num Incidents</th>
										<th>Num Submissions</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody class="table_body">
									@foreach ($events as $event)
										<tr>
											<td>{{ $event->name }}</td>
											<td>{{ $event->user_name }}</td>
											<td>{{ $event->start_date->setTimezone($event->timezone)->format('l j M Y, g:ia T') }}</td>
											<td>{{ $event->end_date->setTimezone($event->timezone)->format('l j M Y, g:ia T') }}</td>
											<td>{{ $event->num_incidents }}</td>
											<td>{{ $event->num_submissions }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/events/view/{{ $event->id }}">Manage</a></li>
														<li><a href="/events/edit/{{ $event->id }}">Edit</a></li>
														<li class="divider"></li>
														<li><a href="/events/delete/{{ $event->id }}">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>

							{!! $events->links() !!}
						@else
							<div class="text-center">
								<p>No pending events found in the system.</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
