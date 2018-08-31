<div class="list-group">
    @foreach($data as $key => $value)
    <div class="list-group-item">
        <div class="row">
            <div class="col-xs-4 text-muted text-right">
                {!! ucfirst(str_replace('_', ' ', $key)) !!}
            </div>
            <div class="col-xs-8">
                @if(is_object($value) && ($value instanceof Closure))
                    {{ $value() }}
                @elseif(is_bool($value))
                    {{ $value ? 'Yes' : 'No' }}
                @else
                    {!! $value !!}
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>