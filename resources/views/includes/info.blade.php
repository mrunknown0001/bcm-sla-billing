@if(session('info'))
	<br/>
	<div class="alert alert-info text-center top-space">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<b>{{ session('info') }}</b>
	</div>
@endif