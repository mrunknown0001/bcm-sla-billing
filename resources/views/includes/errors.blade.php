@if (count($errors) > 0)
	<br/>
    <div class="alert alert-warning alert-dismissible" role="alert">
	    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif