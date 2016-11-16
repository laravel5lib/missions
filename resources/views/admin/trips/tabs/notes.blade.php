<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Description</h5>
    </div>
    <div class="panel-body">
        {% $trip->description %}
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Notes</h5>
    </div>
    <div class="list-group">
        @foreach($trip->notes as $note)
            <div class="list-group-item">
                <div class="list-group-item-heading"><b>{{$note->subject}}</b> by <b>{{ $note->user->name }}</b> on <b>{{ $note->updated_at->format('F j, Y h:i:s a') }}</b></div>
                <div class="list-group-item-text">{{ $note->content }}</div>
            </div>
        @endforeach
    </div>
</div>