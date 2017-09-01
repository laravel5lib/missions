<div class="{{ $class or 'dark-bg-primary' }}">
    <div class="container">
        @if(! isset($cta))
            <div class="col-sm-12 text-center">
                <hr class="divider inv sm">
                <hr class="divider inv sm">
                {{ $message or '' }}
                <hr class="divider inv sm">
                <hr class="divider inv sm hidden-xs">
            </div>
        @else
            <div class="col-sm-8 text-center">
                <hr class="divider inv sm">
                <hr class="divider inv sm">
                {{ $message or '' }}
                <hr class="divider inv sm">
                <hr class="divider inv sm hidden-xs">
            </div>
            <div class="col-sm-4 text-center">
                <hr class="divider inv sm hidden-xs">
                {{ $cta or '' }}
                <hr class="divider inv sm">
            </div>
        @endif
    </div><!-- end container -->
</div><!-- end dark-bg-primary -->