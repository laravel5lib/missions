<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Trip Requirements</h5>
    </div>
    <div class="panel-body">
    @foreach($trip->requirements as $req)
        <h5 class="list-group-item-heading">{{ $req->name }}</h5>
        <p class="list-group-item-text">{{ $req->short_desc }}</p>
        @if ( ! $loop->last)
            <hr class="divider">
        @endif
    @endforeach
    </div>
</div>