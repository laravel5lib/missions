@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Funds</h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    <a href="#" class="btn btn-primary pull-right">New <i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @include('admin.financials.partials._tabs')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Balance</th>
                        <th><i class="fa fa-cog"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($funds as $fund)
                        <tr>
                            <td>{{ $fund->name }}</td>
                            <td><span class="label label-default">{{ ucwords(str_singular($fund->fundable_type)) }}</span></td>
                            <td>
                                @if($fund->balance > 0)
                                    <span class="text-success">$ {{ $fund->balance }}</span>
                                @else
                                    <span class="text-danger">$ {{ $fund->balance }}</span>
                                @endif
                            </td>
                            <td><a href="{{ url('/admin/funds/' . $fund->id) }}"><i class="fa fa-cog"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $funds->links() }}
            </div>
        </div>
    </div>
@stop