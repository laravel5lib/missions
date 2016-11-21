@extends('admin.layouts.default')
@inject('interests', 'App\Models\v1\TripInterest')
@inject('reservations', 'App\Models\v1\Reservation')
@inject('campaigns', 'App\Models\v1\Campaign')

@section('content')
  <div class="white-header-bg">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h3>Admin Dashboard</h3>
        </div>
      </div>
    </div>
  </div>
  <hr class="divider inv lg">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <h4>Current Numbers</h4>
        <hr class="divider lg">
      </div>
    </div>
    <div class="row">
      @foreach($campaigns->active()->get() as $campaign)
      <div class="col-lg-3 col-sm-6">
        <div class="circle-tile ">
          <a href="admin/campaigns/{{ $campaign->id }}"><div class="circle-tile-heading"><img class="img-responsive img-circle" src="{{ image($campaign->avatar->source) }}"></div></a>
          <div class="circle-tile-content">
            <div class="circle-tile-description">Reservations</div>
            <div class="circle-tile-name">{{ $campaign->name }}</div>
            <div class="circle-tile-number">{{ $campaign->reservationsCount() }}</div>
            <a class="circle-tile-footer" href="admin/reservations/current?campaign={{ $campaign->id }}">View All</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="row">
      <hr class="divider inv">
      <div class="col-xs-12">
        <h4>Latest Records</h4>
        <hr class="divider lg">
      </div>
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h5>Newest Prospects</h5>
          </div>
          <table class="table table-hover">
            <tbody>
            @foreach($interests->latest()->take(5)->get() as $interest)
              <tr onclick="window.location.href = 'admin/interests/{{ $interest->id }}'" style="cursor: pointer;">
                <td style="padding:5px 15px;vertical-align:middle;font-size:12px;">{{ $interest->name }}</td>
                <td style="padding:5px 15px;vertical-align:middle;font-size:12px;">
                  {{ $interest->trip->campaign->name }}
                  <br /><small class="text-muted">{{ $interest->trip->group->name }}</small>
                </td>
                <td style="padding:5px 15px;vertical-align:middle;line-height:12px;">
                  <small class="text-muted" style="font-size:10px;">{{ $interest->created_at->diffForHumans() }}</small>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="panel-footer text-center" style="padding:10px;">
            <a class="small" style="color:#bcbcbc;" href="{{ url('/admin/reservations/prospects') }}">View All Prospects</a>
          </div><!-- end panel-footer -->
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h5>Newest Reservations</h5>
          </div>
          <table class="table table-hover">
            <tbody>
            @foreach($reservations->current()->latest()->take(5)->get() as $reservation)
              <tr onclick="window.location.href = 'admin/reservations/{{ $reservation->id }}'" style="cursor: pointer;">
                <td style="padding:5px 15px;vertical-align:middle;font-size:12px;">{{ $reservation->given_names }}</td>
                <td style="padding:5px 15px;vertical-align:middle;font-size:12px;">
                  {{ $reservation->trip->campaign->name }}
                  <br /><small class="text-muted">{{ $reservation->trip->group->name }}</small>
                </td>
                <td style="padding:5px 15px;vertical-align:middle;line-height:12px;">
                  <small class="text-muted" style="font-size:10px;">{{ $reservation->created_at->diffForHumans() }}</small>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
          <div class="panel-footer text-center" style="padding:10px;">
            <a class="small" style="color:#bcbcbc;" href="{{ url('/admin/reservations/current') }}">View All Reservations</a>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection