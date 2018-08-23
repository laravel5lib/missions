@extends('dashboard.layouts.default')

@section('content')
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

            <fetch-json url="groups?manager={{ auth()->user()->id }}" v-cloak>
                <div class="panel panel-default" style="border-top: 5px solid #f6323e" slot-scope="{ json:groups, loading, pagination, filters, addFilter }">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-6">
                                <h4>Organizations <span class="badge badge-default">@{{ pagination.total }}</span></h4>
                            </div>
                            <div class="col-xs-6 text-right text-muted">
                                <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-warning" style="margin-bottom: 0">
                            <div class="row">
                                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                <div class="col-xs-11">Select an organization below to get started.</div>
                            </div>
                        </div>
                    </div>
                    <table class="table" v-if="groups && groups.length">
                        <thead>
                            <tr class="active">
                                <th>#</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(group, index) in groups" :key="group.id">
                                <td>@{{ index+1 }}</td>
                                <td>
                                    <strong><a :href="'/dashboard/groups/' + group.id">@{{ group.name }}</a></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fetch-json>
            </div>
        </div>
    </div>
<hr class="divider inv lg">
@endsection