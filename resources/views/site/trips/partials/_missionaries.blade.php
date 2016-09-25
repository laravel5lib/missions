<div class="col-xs-6 col-md-3">
    <div class="panel panel-default">
        <img class="img-responsive" src="{{ image($res->avatar->source . '?q=25') }}" alt="{{ $res->name }}">
        <div class="panel-body text-center">
            <h6>{{ $res->name }}</h6>
        </div>
    </div>
</div>