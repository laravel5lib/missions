@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>My Passports <small>&middot; {{ $passport->number }}</small></h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="{{ url('dashboard/records/passports/' . $passport->id . '/edit') }}" class="btn btn-primary"><i class="fa fa-edit icon-left"></i> Edit</a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv">
   <div class="container">
       <div class="row">
           <div class="col-md-offset-2 col-md-8">
               <div class="panel panel-default">
                   <div class="panel-heading">
                       <h5>Details</h5>
                   </div>
                   <div class="panel-body">
                       hello
                   </div>
               </div>
           </div>
       </div>

       <div class="row">
           <div class="col-md-offset-2 col-md-8">
               <div class="panel panel-default">
                   <div class="panel-heading">
                       <h5>Photo Copy</h5>
                   </div>
                   <div class="panel-body">
                       <img class="img-responsive" src="{{ image($passport->upload->source) }}" />
                   </div>
               </div>
           </div>
       </div>
   </div>
@endsection