@extends('layouts.minimal')

@section('content')

	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div>
				<img class="img img-responsive" src="/images/aimss-logo-web.png">
			</div>

			<h3>Welcome to AIMSS</h3>
			<p>Log in to get started</p>

			<form class="form-horizontal" role="form" method="POST" action="/login">
				{{ csrf_field() }}
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">

					@if ($errors->has('email'))
						<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
					@endif
				</div>
				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
					<input id="password" type="password" class="form-control" placeholder="Password" name="password">

					@if ($errors->has('password'))
						<span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
					@endif
				</div>

				<div class="form-group">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="remember"> Remember Me
						</label>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-btn fa-sign-in"></i> Login
					</button>
				</div>

				<a class="btn btn-link" href="/password/reset">Forgot Your Password?</a>
			</form>
		</div>
	</div>
@endsection
