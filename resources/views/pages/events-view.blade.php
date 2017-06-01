@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="ibox">
			<div class="ibox-title float-e-margins">
				<h5>{{ $event->name }}</h5>
				<div class="ibox-tools">
					<a href="/events/edit/{{ $event->id }}" class="btn btn-default btn-xs">Edit Event</a>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-lg-6">
						<h3>Main Information</h3>

						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-4 control-label">Event Name</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->name }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">CAMMS ID</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->camms_id }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Status</label>
								<div class="col-lg-8">
									<p class="form-control-static">
										@if ($event->status == 'pending')
											<span class="label label-info">Pending</span>
										@else
											<span class="label label-success">Accepted</span>
										@endif
									</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Event Type</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->type }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Location</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->location }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Description</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->description }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Start Date</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->start_date->setTimezone($event->timezone)->format('d/m/Y H:i:s') }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">End Date</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->end_date->setTimezone($event->timezone)->format('d/m/Y H:i:s') }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Time Zone</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->timezone }}</p>
								</div>
							</div>
						</div>

						<hr>
						<h3>Detailed Information</h3>

						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-4 control-label">Style of Event</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->style }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Competition Service</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->competition_service }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">How is the competitor progression through each stage monitored?</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->competitor_progression }} {{ $event->competitor_progression_other ? ",".$event->competitor_progression_other : '' }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Medical services at park</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->medical_park_firstaid ? 'First Aid' : 'No First Aid' }}, {{ $event->medical_park_ambulance ? 'Ambulance' : 'No Ambulance' }}{{ $event->medical_park_other ? ', '.$event->medical_park_other : '' }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Medical services on competition route</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->medical_route_firstaid ? 'First Aid' : 'No First Aid' }}, {{ $event->medical_route_ambulance ? 'Ambulance' : 'No Ambulance' }}{{ $event->medical_route_other ? ', '.$event->medical_route_other : '' }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Does event have dedicated spectator areas along route supervised by officials?</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->spectator }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Competitive Distance?</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->competitive_distance }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Number of competitors starting event? </label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $event->competitor_number }}</p>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<h3>Stages</h3>

						@if (count($event->stages))
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Stage Number</th>
										<th>Distance</th>
										<th>Fastest Time</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody class="table_body">
									@foreach ($event->stages as $stage)
										<tr data-id="{{ $stage->id }}" data-stage_number="{{ $stage->stage_number }}" data-distance="{{ $stage->distance }}" data-fastest_time="{{ $stage->getFastestTimeForHumans() }}">
											<td>{{ $stage->stage_number }}</td>
											<td>{{ $stage->distance }}</td>
											<td>{{ $stage->getFastestTimeForHumans() }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Actions <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="btn-edit-stage">Edit</a></li>
														<li><a href="#" class="btn-delete-stage">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<p>No stages have been added yet.</p>
						@endif

						<button type="button" class="btn btn-default btn-sm m-y-10 btn-create-stage">Create New Stage</button>
						<hr>

						<h3>Incidents</h3>

						@if (count($incidents))
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Name</th>
										<th>Time</th>
										<th>Number of Reports</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($incidents as $incident)
										<tr>
											<td>{{ $incident->name }}</td>
											<td>{{ $incident->incident_time->setTimezone($event->timezone)->format('j M Y, g:ia') }}</td>
											<td>{{ $incident->num_reports }}</td>
											<td>{{ ucfirst($incident->status) }}</td>
											<td>
												<a href="/incidents/view/{{ $incident->id }}" class="btn btn-default btn-xs">View</a>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<p>No incidents have been recorded yet.</p>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script src="/js/modalform.js"></script>
	<script>
	var stage_modal_html = ''+
		'<form action="/events/add-stage" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Stage Number</label>'+
				'<div class="col-md-9"><input type="text" name="stage_number" class="form-control"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Distance</label>'+
				'<div class="col-md-9"><input type="text" name="distance" class="form-control"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Fastest Time</label>'+
				'<div class="col-md-9"><input type="text" name="fastest_time" class="form-control" placeholder="hh:mm:ss.xxxx"></div>'+
			'</div>'+
			'<input type="hidden" name="event_id" value="{{ $event->id }}">'+
			'{{ csrf_field() }}'+
		'</form>';

	$('.btn-create-stage').on('click', function() {
		modalform.dialog({
			bootbox: {
				title: 'Create New Stage',
				message: stage_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Create Stage',
						className: 'btn-primary'
					}
				}
			}
		});
	});

	$('.btn-edit-stage').on('click', function(event) {
		event.preventDefault();

		var tr = $(this).closest('tr');

		modalform.dialog({
			bootbox: {
				title: 'Edit Stage',
				message: stage_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default',
					},
					submit: {
						label: 'Save Changes',
						className: 'btn-primary'
					}
				}
			},
			after_init: function() {
				$('.modal input[name="stage_number"]').val(tr.data('stage_number'));
				$('.modal input[name="distance"]').val(tr.data('distance'));
				$('.modal input[name="fastest_time"]').val(tr.data('fastest_time'));
				$('.modal form').attr('action', '/events/edit-stage/' + tr.data('id'));
			}
		});
	});

	$('.btn-delete-stage').on('click', function(event) {
		event.preventDefault();
		var event_stage_id = $(this).closest('tr').data('id');

		modalform.dialog({
			bootbox : {
				title: 'Delete Stage',
				message: ''+
					'<form action="/events/delete-stage/' + event_stage_id + '" method="post" class="form-horizontal">'+
						'<p>Are you sure you want to delete this  entry?</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Stage',
						className: 'btn-danger'
					}
				}
			}
		});
	});


	</script>
@endsection
