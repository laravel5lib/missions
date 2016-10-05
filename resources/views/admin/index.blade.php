@extends('admin.layouts.default')
@inject('interests', 'App\Models\v1\TripInterest')
@inject('reservations', 'App\Models\v1\Reservation')
@inject('campaigns', 'App\Models\v1\Campaign')

@section('content')
  <div class="white-header-bg">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h3>Dashboard</h3>
        </div>
      </div>
    </div>
  </div>
  <hr class="divider inv lg">
  <div class="container">
    <div class="row">
      <div class="col-xs-12"><h3>Current Numbers</h3></div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h5 class="panel-header">Reservations</h5>
          </div>
          <table class="table table-hover">
            <tbody>
            @foreach($campaigns->active()->get() as $campaign)
              <tr>
                <td>{{ $campaign->name }}</td>
                <td class="text-right">{{ $campaign->reservationsCount() }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-xs-12"><h3>Latest Records</h3></div>

      <div class="col-md-6">

        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6">
                <h5 class="panel-header">Newest Prospects</h5>
              </div>
              <div class="col-xs-6 text-right">
                <a href="{{ url('/admin/reservations/prospects') }}" class="btn btn-white-hollow btn-xs">See All</a>
              </div>
            </div>
          </div>
          <table class="table table-hover">
            <tbody>
            @foreach($interests->latest()->take(5)->get() as $interest)
              <tr>
                <td>{{ $interest->name }}</td>
                <td>
                  {{ $interest->trip->campaign->name }}
                  <br /><small>{{ $interest->trip->group->name }}</small>
                </td>
                <td>
                  <small class="text-muted">{{ $interest->created_at->diffForHumans() }}</small>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-md-6">

        <div class="panel panel-success">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6">
                <h5 class="panel-header">Newest Reservations</h5>
              </div>
              <div class="col-xs-6 text-right">
                <a href="{{ url('/admin/reservations/current') }}" class="btn btn-white-hollow btn-xs">See All</a>
              </div>
            </div>
          </div>
          <table class="table table-hover">
            <tbody>
            @foreach($reservations->current()->latest()->take(5)->get() as $reservation)
              <tr>
                <td>{{ $reservation->given_names }}</td>
                <td>
                  {{ $reservation->trip->campaign->name }}
                  <br /><small>{{ $reservation->trip->group->name }}</small>
                </td>
                <td>
                  <small class="text-muted">{{ $reservation->created_at->diffForHumans() }}</small>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
@endsection