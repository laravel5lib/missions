@extends('dashboard.layouts.default')

@section('content')
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                            
                <fetch-json url="reservations?filter[user_id]={{ auth()->user()->id }}&include=trip.campaign,trip.group" :parameters="{filter: {current: true}}" cache-key="userReservations.{{ auth()->user()->id }}" v-cloak>
                    <div class="panel panel-default" style="border-top: 5px solid #f6323e" slot-scope="{ json:reservations, loading, pagination, filters, addFilter, removeFilter }">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h4>Reservations <span class="badge badge-default">@{{ pagination.total }}</span></h4>
                                </div>
                                <div class="col-xs-6 text-right text-muted">
                                    <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-warning">
                                <div class="row">
                                    <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                    <div class="col-xs-11">Select a reservation below to get started.</div>
                                </div>
                            </div>
                            <ul class="nav nav-pills nav-justified">
                                <li role="presentation" :class="{'active' : filters.filter.current}">
                                    <a role="button" @click="addFilter('current', true); removeFilter('past')"><i class="fa fa-ticket"></i> Current Trips</a>
                                </li>
                                <li role="presentation" :class="{'active' : filters.filter.past}">
                                    <a role="button" @click="addFilter('past', true); removeFilter('current')"><i class="fa fa-archive"></i> Past Trips</a>
                                </li>
                            </ul>
                        </div>
                        <table class="table" v-if="reservations && reservations.length">
                            <thead>
                            <tr class="active">
                                <th>#</th>
                                <th>Name</th>
                                <th>Trip</th>
                                <th>Team</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(reservation, index) in reservations" :key="reservation.id">
                                <td>@{{ index+1 }}</td>
                                <td>
                                    <strong><a :href="'/dashboard/reservations/' + reservation.id + '/{{ request()->get('target')}}'">@{{ reservation.given_names }} @{{ reservation.surname }}</a></strong>
                                    <br><em>@{{ reservation.desired_role.name }}</em>
                                </td>
                                <td>
                                    @{{ reservation.trip.campaign.name | capitalize }}
                                    <br><em>@{{ reservation.trip.type | capitalize }}</em>
                                </td>
                                <td>
                                    @{{ reservation.trip.group.name }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="panel-body text-center" v-else>
                            <span class="lead">No Reservations</span>
                            <p>You have no reservations yet. Sign up for a trip to get started.</p>
                        </div>
                        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                            <pager :pagination="pagination"></pager>
                        </div>
                    </div>
                </fetch-json>
            </div>
        </div>
        <hr class="divider inv lg">
    </div>

@endsection