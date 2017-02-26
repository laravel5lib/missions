<ul class="list-group">
    <li class="list-group-item">
        <h5>Travel Requirements</h5>
    </li>
    @foreach($trip->requirements as $req)
        <li class="list-group-item">
            <h5 class="list-group-item-heading">{{ $req->name }}</h5>
            <p class="list-group-item-text">{{ $req->short_desc }}</p>
        </li>
    @endforeach
</ul>