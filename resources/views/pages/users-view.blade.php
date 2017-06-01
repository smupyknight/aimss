@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>{{ $user->first_name }} {{ $user->last_name }}</h5>
				<div class="ibox-tools">
					<a href="/users/edit/{{ $user->id }}" class="btn btn-default btn-xs">Edit User</a>
					@if ($user->status == 'pending')
						<a href="/users/accept/{{ $user->id }}" onclick="show_modal(this.href);return false" class="btn btn-default btn-xs">Accept User</a>
						<a href="/users/delete/{{ $user->id }}" onclick="show_modal(this.href);return false" class="btn btn-default btn-xs">Delete User</a>
					@elseif ($user->status == 'invited')
						<a href="/users/reinvite/{{ $user->id }}" onclick="show_modal(this.href);return false" class="btn btn-default btn-xs">Resend Invitation</a>
						<a href="/users/delete/{{ $user->id }}" onclick="show_modal(this.href);return false" class="btn btn-default btn-xs">Delete User</a>
					@elseif ($user->status == 'active')
						<a href="/users/disable/{{ $user->id }}" onclick="show_modal(this.href);return false" class="btn btn-default btn-xs">Disable User</a>
					@elseif ($user->status == 'disabled')
						<a href="/users/enable/{{ $user->id }}" onclick="show_modal(this.href);return false" class="btn btn-default btn-xs">Enable User</a>
					@endif
				</div>
			</div>
			<div class="ibox-content">
				<div class="form-horizontal">
					{{ csrf_field() }}

					<div class="row">
						<label class="col-lg-2 control-label">Name</label>
						<div class="col-lg-4">
							<p class="form-control-static">{{ $user->first_name .' '. $user->last_name }}</p>
						</div>

						<label class="col-lg-2 control-label">Email</label>
						<div class="col-lg-4">
							<p class="form-control-static">{{ $user->email }}</p>
						</div>
					</div>

					<div class="row">
						<label class="col-lg-2 control-label">Phone</label>
						<div class="col-lg-4">
							<p class="form-control-static">{{ $user->phone }}</p>
						</div>

						<label class="col-lg-2 control-label">Type</label>
						<div class="col-lg-4">
							<p class="form-control-static">{{ $user->type }}</p>
						</div>
					</div>

					<div class="row">
						<label class="col-lg-2 control-label">Subscribed</label>
						<div class="col-lg-4">
							<p class="form-control-static">{{ $user->is_subscribed == 1 ? 'Yes' : 'No' }}</p>
						</div>

						<label class="col-lg-2 control-label">Status</label>
						<div class="col-lg-4">
							<p class="form-control-static">
								@if ($user->status == 'pending')
									<span class="label label-danger">Pending</span>
								@elseif ($user->status == 'invited')
									<span class="label label-success">Invited</span>
								@elseif ($user->status == 'active')
									<span class="label label-primary">Active</span>
								@elseif ($user->status == 'disabled')
									<span class="label label-default">Disabled</span>
								@endif
							</p>
						</div>
					</div>
				</div>
				<hr>
				<h3>Images</h3>

				@if (count($user->images))
					<div class="row">
						@foreach ($user->images as $image)
							<div class="col-md-3">
								<img src="{{ $image->getUrl() }}" class="img img-responsive img-thumbnail" alt="{{ $image->name }}" title="{{ $image->name }}">
								@if ($image->type && $image->identification_number)
									<p class="text-center"><strong>{{ $image->type }}:</strong> {{ $image->identification_number }}</p>
								@endif
							</div>
						@endforeach
					</div>
				@else
					<p>No images have been uploaded.</p>
				@endif
			</div>
		</div>
	</div>
@endsection
