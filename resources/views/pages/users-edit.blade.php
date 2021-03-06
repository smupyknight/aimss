@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Edit User</h5>
						<div class="ibox-tools">

						</div>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/users/edit/{{$user->id}}">
							{{csrf_field()}}

							<div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">First Name</label>
								<div class="col-lg-10"><input type="text" placeholder="First Name" name="first_name" class="form-control" value="{{ old('first_name',$user->first_name) }}">
									@if ($errors->has('first_name'))
										<span class="help-block"><strong>{{ $errors->first('first_name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Last Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Last Name" name="last_name" class="form-control" value="{{ old('last_name',$user->last_name) }}">
									@if ($errors->has('last_name'))
										<span class="help-block"><strong>{{ $errors->first('last_name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Email</label>
								<div class="col-lg-10">
									<input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email',$user->email) }}">
									@if ($errors->has('email'))
										<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Phone</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Phone" name="phone" value="{{ old('phone',$user->phone) }}">
									@if ($errors->has('phone'))
										<span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Type</label>
								<div class="col-lg-10">
									<select class='form-control' placeholder='Type' name="type" id="type">
										<option disabled selected="selected">Select Type</option>
										<option value="Admin" @if(old('type',$user->type) == 'Admin') selected @endif>Admin</option>
										<option value="Scrutineer" @if(old('type',$user->type) == 'Scrutineer') selected @endif>Scrutineer</option>
										<option value="Organizer" @if(old('type',$user->type) == 'Organizer') selected @endif>Organizer</option>
										<option value="Crew" @if(old('type',$user->type) == 'Crew') selected @endif>Crew</option>
										<option value="Medical" @if(old('type',$user->type) == 'Medical') selected @endif>Medical</option>
										<option value="Spectator" @if(old('type',$user->type) == 'Spectator') selected @endif>Spectator</option>
									</select>
									@if ($errors->has('type'))
										<span class="help-block"><strong>{{ $errors->first('type') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}"><label class="col-lg-2 control-label">Password</label>
								<div class="col-lg-10"><input type="password" placeholder="Password" class="form-control" name="password" value="">
									@if ($errors->has('password'))
										<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Confirm Password</label>
								<div class="col-lg-10">
									<input type="password" class="form-control" placeholder="Password" name="password_confirmation">
									@if ($errors->has('password_confirmation'))
										<span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('address_1') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Address 1</label>
								<div class="col-lg-10">
								<textarea class="form-control" placeholder="Address 1" name="address_1">{{ old('address_1',$user->address_1) }}</textarea>
									@if ($errors->has('address_1'))
										<span class="help-block"><strong>{{ $errors->first('address_1') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('address_2') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Address 2</label>
								<div class="col-lg-10">
								<textarea class="form-control" placeholder="Address 2" name="address_2">{{ old('address_2',$user->address_2) }}</textarea>
									@if ($errors->has('address_2'))
										<span class="help-block"><strong>{{ $errors->first('address_2') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('suburb') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Suburb</label>
								<div class="col-lg-10">
								<input type="text" class="form-control" placeholder="Suburb" name="suburb" value="{{ old('suburb',$user->suburb) }}">
									@if ($errors->has('suburb'))
										<span class="help-block"><strong>{{ $errors->first('suburb') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('postcode') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Postcode</label>
								<div class="col-lg-10">
								<input type="text" class="form-control" placeholder="Postcode" name="postcode" value="{{ old('postcode',$user->postcode) }}">
									@if ($errors->has('postcode'))
										<span class="help-block"><strong>{{ $errors->first('postcode') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">State</label>
								<div class="col-lg-10">
								<input type="text" class="form-control" placeholder="State" name="state" value="{{ old('state',$user->state) }}">
									@if ($errors->has('state'))
										<span class="help-block"><strong>{{ $errors->first('state') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('timezone') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Timezone</label>
								<div class="col-lg-10">
									<select name="timezone" class="form-control">
										<option value="">Select timezone</option>
										@foreach (\DateTimeZone::listIdentifiers(\DateTimeZone::AUSTRALIA) as $timezone)
											<option {{ old('timezone',$user->timezone) == $timezone ? 'selected' : '' }} value="{{ $timezone }}">{{ preg_replace('%.*/%', '', str_replace('_', ' ', $timezone)) }} ({{ (new DateTime('now', new DateTimeZone($timezone)))->format('g:ia') }})</option>
										@endforeach
									</select>
									@if ($errors->has('timezone'))
										<span class="help-block"><strong>{{ $errors->first('timezone') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('subscribed') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Subscribed</label>

								<div class="col-lg-8">
									<label class="radio-inline"><input type="radio" name="subscribed" value="1" {{ old('subscribed' ,$user->is_subscribed ) == '1' ? 'checked' : '' }} >Yes</label>
									<label class="radio-inline"><input type="radio" name="subscribed" value="0" {{ old('subscribed', $user->is_subscribed) == '0' ? 'checked' : '' }} >No</label>
								</div>
						   </div>

							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button class="btn btn-sm btn-white" type="submit">Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
