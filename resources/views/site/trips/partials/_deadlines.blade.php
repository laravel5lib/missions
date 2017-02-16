<ul class="list-group">
    <li class="list-group-item">
        <h5>Additional Deadlines</h5>
    </li>
    @foreach($trip->deadlines as $dl)
        <li class="list-group-item">
            <h5 class="list-group-item-heading">{{ $dl->name }}</h5>
            <p class="list-group-item-text">{{ date('F d, Y', strtotime($dl->date)) }}</p>
        </li>
    @endforeach
</ul>