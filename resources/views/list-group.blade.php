<div class="list-group">
    @foreach($data as $key => $value)
    <div class="list-group-item">
        <div class="row">
            <div class="col-xs-4 text-muted text-right">
                {!! $key !!}
            </div>
            <div class="col-xs-8">
                @if(is_callable($value))
                    {{ $value() }}
                @else
                    {!! $value !!}
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>