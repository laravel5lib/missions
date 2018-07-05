@component('panel')
    @slot('title')<h5>Your Group</h5> @endslot
    @if(!$membership->group)
        @slot('body')
        <p class="text-muted text-center">You have not been assigned to a group.</p>
        @endslot
    @else
    @slot('body')
        @if($groupMembers->count())
        <div class="row">
            @foreach($groupMembers as $member)
                <div class="col-xs-6 text-center" style="margin-bottom: 2em;">
                    <img class="img-circle" src="{{ image($member->reservation->user->getAvatar()->source) }}" width="100" height="100"><br />
                    <strong>{{ $member->reservation->name }}</strong>
                    <br />{{ teamRole($member->reservation->desired_role) }}
                </div>
            @endforeach
        </div>
        @else
            <p class="text-muted text-center">There are no other members in your group.</p>
        @endif
    @endslot
    @endif
@endcomponent