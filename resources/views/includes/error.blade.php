@if(session('error'))
	<br/>
	<div class="alert alert-danger text-center top-space">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<b>{{ session('error') }}</b>
	</div>
@endif