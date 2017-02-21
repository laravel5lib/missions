<ul class="list-group">
    <li class="list-group-item">
        <h5>Other Important Dates</h5>
    </li>
    @foreach($trip->deadlines as $dl)
        <li class="list-group-item">
            <h5 class="list-group-item-heading">{{ $dl->name }}</h5>
            <p class="list-group-item-text">{{ $dl->date->format('F j, Y') }}</p>
        </li>
    @endforeach
</ul>