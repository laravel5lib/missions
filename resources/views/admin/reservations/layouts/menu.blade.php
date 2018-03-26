<div class="panel panel-default">
    <div class="panel-heading">
        <div style="display:inline-block;">
            <img class="img-circle img-sm" src="{{ image($reservation->getAvatar()->source.'?w=50&h=50') }}">
        </div>
        <div style="display:inline-block;vertical-align:middle;margin:0 0 0 10px;">
            <label style="margin-bottom:0px;font-size:10px;">Missionary</label>
            <h5 style="margin:3px 0 6px;">{{ $reservation->name }}</h5>
            <p style="font-size:10px;margin-top:3px;"><i class="fa fa-phone"></i> <a href="tel:{{ $reservation->phone_one }}">{{ $reservation->phone_one }}</a> / <i class="fa fa-envelope"></i> <a href="mailto:{{ $reservation->email }}">{{ $reservation->email }}</a></p>
            <p style="margin-top:0;">
                <span class="text-success">${{ number_format($reservation->totalRaisedInDollars(),2) }}</span>
                Raised <span class="text-info">({{ $reservation->getPercentRaised() }}%)</span>
            </p>
        </div>
    </div><!-- end panel-heading -->

    <div class="panel-heading">
        <div style="display:inline-block;">
            <img class="img-circle img-sm" src="{{ image($reservation->user->getAvatar()->source.'?w=50&h=50') }}">
        </div>
        <div style="display:inline-block;vertical-align:middle;margin:0 0 0 10px;">
            <label style="margin-bottom:0px;font-size:10px;">Managing User</label>
            <h5 style="margin:3px 0 6px;"><a href="{{ url('admin/users/' . $reservation->user->id) }}">{{ $reservation->user->name }}</a></h5>
            <p style="font-size:10px;margin-top:3px;"><i class="fa fa-phone"></i> <a href="tel:{{ $reservation->user->phone_one }}">{{ $reservation->user->phone_one }}</a> / <i class="fa fa-envelope"></i> <a href="mailto:{{ $reservation->user->email }}">{{ $reservation->user->email }}</a></p>
        </div>
    </div><!-- end panel-heading -->

    @if($rep)
    <div class="panel-heading">
        <div style="display:inline-block;">
            <img class="img-circle img-sm" src="{{ $rep->avatar_url }}" width="50" height="50">
        </div>
        <div style="display:inline-block;vertical-align:middle;margin:0 0 0 10px;">
            <label style="margin-bottom:0px;font-size:10px;">Assigned Trip Rep</label>
            <h5 style="margin:3px 0 6px;">{{ $rep->name }}</h5>
            <p style="font-size:10px;margin-top:3px;"><i class="fa fa-phone"></i> <a href="tel:{{ $rep->phone }}">{{ $rep->phone }}</a> / <i class="fa fa-envelope"></i> <a href="mailto:{{ $rep->email }}">{{ $rep->email }}</a></p>
        </div>
    </div><!-- end panel-heading -->
    @endif

</div>

<div class="panel panel-default">
    <ul class="nav nav-pills nav-stacked">
        @foreach($links as $link)
            <li class="{{ $tab == $link['url'] ? 'active' : '' }}">
                <a href="{{ url('admin/reservations', [$reservation->id, $link['url']]) }}">
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div><!-- end panel -->