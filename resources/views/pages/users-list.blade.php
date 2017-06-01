@extends('layouts.default', [
	'title' => 'Users',
])

@section('content')
	<div class="wrapper wrapper-content">
		<div class="pull-right">
			<a href="/users/create" class="btn btn-default btn-xs">Create User</a>
			<a href="/users/invite" class="btn btn-default btn-xs">Invite User</a>
			<a href="/users/export-subscribers" class="btn btn-default btn-xs">Export Subscribers</a>
		</div>
		<div class="tabs-container">
			<ul class="nav nav-tabs">
				<li{!! $status == 'pending' ? ' class="active"' : '' !!}><a href="/users/list/pending">Pending @if ($num_pending)<span class="label label-default">{{ $num_pending }}</span>@endif</a></li>
				<li{!! $status == 'invited' ? ' class="active"' : '' !!}><a href="/users/list/invited">Invited @if ($num_invited)<span class="label label-default">{{ $num_invited }}</span>@endif</a></li>
				<li{!! $status == 'active' ? ' class="active"' : '' !!}><a href="/users/list/active">Active</a></li>
				<li{!! $status == 'disabled' ? ' class="active"' : '' !!}><a href="/users/list/disabled">Disabled</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active">
					<div class="panel-body">
						<form method="GET" action="/users/list/{{ $status }}">
							<div class="input-group">
								<input type="text" placeholder="Search Users" class="input form-control" name="search" value="{{ Request::get('search') }}" >
								<span class="input-group-btn">
									<button type="submit" class="btn btn btn-default"><i class="fa fa-search"></i> Search</button>
								</span>
							</div>
						</form>
						<br>

						@if (count($users) > 0)
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Name</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Type</th>
										<th>State</th>
										<th>Created At</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($users as $user)
										<tr>
											<td>{{ $user->first_name . ' ' . $user->last_name }}</td>
											<td>{{ $user->email }}</td>
											<td>{{ ucfirst($user->phone) }}</td>
											<td>{{ ucfirst($user->type) }}</td>
											<td>{{ $user->state }}</td>
											<td>{{ $user->created_at->setTimezone(Auth::user()->timezone)->format('j M Y') }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/users/view/{{ $user->id }}">View</a></li>
														<li><a href="/users/edit/{{ $user->id }}">Edit</a></li>
														<li><a href="" onclick="reset_password('{{ $user->email }}');return false;">Reset Password</a></li>
														@if ($user->status == 'pending')
															<li><a href="/users/accept/{{ $user->id }}" onclick="show_modal(this.href);return false">Accept</a></li>
															<li class="divider"></li>
															<li><a href="/users/delete/{{ $user->id }}" onclick="show_modal(this.href);return false">Delete</a></li>
														@elseif ($user->status == 'invited')
															<li><a href="/users/reinvite/{{ $user->id }}" onclick="show_modal(this.href);return false">Resend Invitation</a></li>
															<li class="divider"></li>
															<li><a href="/users/delete/{{ $user->id }}" onclick="show_modal(this.href);return false">Delete</a></li>
														@elseif ($user->status == 'active')
															<li><a href="/users/disable/{{ $user->id }}" onclick="show_modal(this.href);return false">Disable</a></li>
														@elseif ($user->status == 'disabled')
															<li><a href="/users/enable/{{ $user->id }}" onclick="show_modal(this.href);return false">Enable</a></li>
														@endif
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>

							{{ $users->render() }}
						@else
							<div class="text-center">
								<p>There are no users matching your search criteria.</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="password-reset-modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Success</h4>
				</div>
				<div class="modal-body">
					<p>An email has been sent to the selected user.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		function reset_password(email) {
			$.ajax({
				url: '/password/email',
				type: 'post',
				data:
				{
					'_token':'{{ csrf_token() }}',
					'email': email,
				},
				success: function() {
					$('#password-reset-modal').modal();
				}
			});
		}
	</script>
@endsection
