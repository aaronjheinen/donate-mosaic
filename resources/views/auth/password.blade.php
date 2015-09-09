@extends('app')

@section('content')
<h1 class="text-center">Reset Password</h1>
<form class="form-horizontal" role="form" method="POST" action="<% url('/password/email') %>">
	<input type="hidden" name="_token" value="<% csrf_token() %>">
	
	<div class="form-group">
    <input type="text" class="form-control input-lg" name="email" placeholder="Email"  value="<% old('email') %>">
  </div>

	@if (count($errors) > 0)
		<div class="form-group">
			<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br><br>
				<ul>
					@foreach ($errors->all() as $error)
						<li><% $error %></li>
					@endforeach
				</ul>
			</div>
		</div>
	@endif

	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block btn-lg">
			Send Password Reset Link
		</button>
	</div>

	@if (session('status'))
	<div class="form-group">
		<div class="alert alert-success">
			<% session('status') %>
		</div>
	</div>
	@endif
</form>
@endsection
