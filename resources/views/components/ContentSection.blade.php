<div 
    @isset($class)class="{{ $class }}" @endif
    @isset($style)style="{{ $style }}" @endif
>
    <div class="container">
        @isset($small)
            {{ $slot }}
        @else
        <div class="content-section">
            {{ $slot }}
        </div>
        @endif
    </div>
</div>