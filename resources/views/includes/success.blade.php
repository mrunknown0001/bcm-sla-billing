@if(session('success'))
	<br/>
	<div class="alert alert-success text-center top-space">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<b>{{ session('success') }}</b>
	</div>
@endif