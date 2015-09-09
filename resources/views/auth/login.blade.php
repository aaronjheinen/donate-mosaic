@extends('login')
@section('title', 'Login')

@section('content')
<div class="row">
	<div class="col s6 offset-s3">
		<form class="login-form" role="form" method="POST" action="{{ url('/auth/login') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
		    <input type="text" class="form-control input-lg" name="email" placeholder="Email"  value="{{ old('email') }}">
		  </div>
		  <div class="form-group">
		    <input type="password" class="form-control input-lg" name="password" placeholder="Password">
		  </div>
		  <button type="submit" class="btn btn-primary btn-block btn-lg">Sign In</button>
		  <p></p>

			@if (count($errors) > 0)
				<div class="red-text">
					<strong>Whoops!</strong> There were some problems with your input.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
		  <div class="form-group">
		    <p class="help-block"><a href="{{ url('/password/email') }}">Forgot Password?</a></p>
		  </div>
		</form>
	</div>
</div>
@endsection

