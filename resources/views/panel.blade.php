<div class="panel panel-default">
    @if(isset($title))
        <div class="panel-heading">
            {{ $title }}
        </div>
    @endif
    
    {{ $slot }}

    @if(isset($body))
        <div class="panel-body">
            {{ $body }}
        </div>
    @endif

    @if(isset($footer))
        <div class="panel-footer">
            {{ $footer }}
        </div>
    @endif
</div>