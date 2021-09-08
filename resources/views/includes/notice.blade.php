@if(session('notice'))
	<br/>
	<div class="alert alert-warning text-center top-space">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<b>{!! session('notice') !!}</b>
	</div>
@endif