<div class="row">
	<div class="col-md-6 offset-3">
		<p>Password Must Have:</p>
		<ul>
			<li>Minimum of 8 Characters in Length</li>
			<li>1 Capital Letter</li>
			<li>1 Small Letter</li>
			<li>1 Special Character</li>
		</ul>
		@include('includes.success')
		@include('includes.errors')
		@include('includes.error')
		<form class="form" action="{{ route('post.change.password') }}" method="POST" autocomplete="off">
			@csrf
			<div class="form-group">
				<label for="old_password">Old Password</label>
				<input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
			</div>
			<div class="form-group">
				<label for="new_password">New Password</label>
				<input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
			</div>
			<div class="form-group">
				<label for="new_password_confirmation">Confirm New Password</label>
				<input type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm New Password" class="form-control">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Change Password</button>
			</div>
		</form>
	</div>
</div>