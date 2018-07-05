@component('panel')
    @slot('title')<h5>Leadership</h5> @endslot
    @slot('body')
        @if($leaders->count())
        <div class="row">
            @foreach($leaders as $leader)
                <div class="col-xs-6 text-center" style="margin-bottom: 2em;">
                    <img class="img-circle" src="{{ image($leader->reservation->user->getAvatar()->source) }}" width="100" height="100"><br />
                    <strong>{{ $leader->reservation->name }}</strong>
                    <br />{{ teamRole($leader->reservation->desired_role) }}
                </div>
            @endforeach
        </div>
        @else
            <p class="text-muted text-center">Leaders have not been assigned to your squad yet.</p>
        @endif
    @endslot
@endcomponent