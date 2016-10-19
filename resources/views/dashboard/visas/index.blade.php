@extends('dashboard.records.index')

@section('header')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>My Visas</h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="visas/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Visa</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('tab')

<visas-list></visas-list>

@endsection