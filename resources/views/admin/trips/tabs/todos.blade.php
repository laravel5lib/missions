<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Todos</h5>
    </div>
    <div class="list-group">
        @foreach($trip->todos as $todo)
            <li class="list-group-item">{{$todo}}</li>
        @endforeach
    </div>
</div>