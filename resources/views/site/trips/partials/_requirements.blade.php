<div class="panel panel-primary">
    <div class="panel-heading">
        <h5>Travel Requirements</h5>
    </div>
    <div class="panel-body">
        <ul class="">
            @foreach($trip->requirements as $req)
                <li class="">{{ $req->name }}</li>
            @endforeach
        </ul>
    </div>
</div>