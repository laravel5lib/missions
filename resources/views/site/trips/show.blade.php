@extends('site.layouts.default')

@section('content')
<div class="dark-bg-primary">
    <div class="container">
        <hr class="divider inv xlg">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="text-capitalize"><img class="img-circle img-sm av-left" src="http://lorempixel.com/500/500/">Group Name <span class="hidden-xs small text-white text-capitalize">&middot; Full-week Missionary</span></h1>
            </div>
        </div>
        <hr class="divider inv xlg">
    </div>
</div>
<div class="container">
    <hr class="divider inv xlg hidden-xs">
    <hr class="divider inv lg visible-xs">
    <div class="row">
        <div class="visible-xs">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 text-center">
                <h4>Trip Name</h4>
                <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseDetails" aria-expanded="false" aria-controls="collapseDetails">Read Details</a>
                <hr class="divider inv xlg">
            </div>
            <div id="collapseDetails" class="collapse">
                <div class="col-xs-12">
                @foreach($trip->notes as $note)
                    <h3>{{ $note->subject }}</h3>
                    <p>{{ $note->content }}</p>
                    <br>
                @endforeach

                <ul class="list-group">
                    <li class="list-group-item">
                        <h5>Deadlines</h5>
                    </li>
                    @foreach($trip->deadlines as $dl)
                        <li class="list-group-item">
                            <h5 class="list-group-item-heading">{{ $dl->name }}</h5>
                            <p class="list-group-item-text">{{ date('F d, Y', strtotime($dl->date)) }}</p>
                        </li>
                    @endforeach
                </ul>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h5>Travel Requirements</h5>
                    </div>
                    <div class="panel-body">
                        <ul class="">
                            @foreach($trip->requirements as $req)
                                <li class="">{{ $req->item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <h4>Missionaries Registered</h4>
                <hr class="divider">
                <div class="row">
                    @foreach($trip->reservations as $key => $res)
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-default">
                            <img class="img-responsive" src="http://lorempixel.com/200/200/people/{{$key}}" alt="...">
                            <div class="panel-body text-center">
                                <h6>{{ $res->given_names }} {{ $res->surname }}</h6>
                                {{--<p>{{ $res->gender }}</p>--}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                </div><!-- end col -->
            </div><!-- end collapse -->
        </div><!-- end visible-xs -->
        <div class="col-sm-7 col-md-7 col-lg-8 hidden-xs">
            @foreach($trip->notes as $note)
                <h3>{{ $note->subject }}</h3>
                <p>{{ $note->content }}</p>
                <br>
            @endforeach

            <ul class="list-group">
                <li class="list-group-item">
                    <h5>Deadlines</h5>
                </li>
                @foreach($trip->deadlines as $dl)
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">{{ $dl->name }}</h5>
                        <p class="list-group-item-text">{{ date('F d, Y', strtotime($dl->date)) }}</p>
                    </li>
                @endforeach
            </ul>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h5>Travel Requirements</h5>
                </div>
                <div class="panel-body">
                    <ul class="">
                        @foreach($trip->requirements as $req)
                            <li class="">{{ $req->item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <h4>Missionaries Registered</h4>
            <hr class="divider">
            <div class="row">
                @foreach($trip->reservations as $key => $res)
                <div class="col-xs-6 col-md-3">
                    <div class="panel panel-default">
                        <img class="img-responsive" src="http://lorempixel.com/200/200/people/{{$key}}" alt="...">
                        <div class="panel-body text-center">
                            <h6>{{ $res->given_names }} {{ $res->surname }}</h6>
                            {{--<p>{{ $res->gender }}</p>--}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
            <div class="panel panel-default">
            	<div class="panel-body">
                    <a href="/trips/{{ $trip->id }}/register" class="btn btn-info btn-lg btn-block">Register Now</a>
                    <hr class="divider lg">
                    <h6 class="text-center text-uppercase small text-muted">Start Date</h6>
                    <h4 class="text-center">{{ date('F d, Y', strtotime($trip->started_at)) }}</h4>
                    <hr class="divider inv">
                    <h6 class="text-center text-uppercase small text-muted">End Date</h6>
                    <h4 class="text-center">{{ date('F d, Y', strtotime($trip->ended_at)) }}</h4>
                    <hr class="divider lg">

                    <h6 class="text-uppercase text-center"><img class="img-xs av-left" src="../images/why-mm/level1.png" alt=""> Difficulty</h6>
                    <!-- <div class="progress">
                        <?php
                            $difficultyNumber = 0;
                            $difficultyClass = '';
                            switch ($trip->difficulty) {
                                case 'level_3':
                                    $difficultyNumber = 100;
                                    $difficultyClass = 'progress-bar-primary';
                                    break;
                                case 'level_2':
                                    $difficultyNumber = 66;
                                    $difficultyClass = 'progress-bar-info';
                                    break;
                                case 'level_1':
                                    $difficultyNumber = 33;
                                    $difficultyClass = 'progress-bar-success';
                                    break;
                            }

                        ?>
                        <div class="progress-bar {{ $difficultyClass }} progress-bar-striped active" role="progressbar" aria-valuenow="{{ $difficultyNumber }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $difficultyNumber }}%">
                            <span class="sr-only">{{ $difficultyNumber }}% Difficulty</span>
                        </div>
                    </div> -->
                    <hr class="divider lg">
                    <ul class="list-group">
                        @foreach($trip->activeCosts as $cost)
                            <a   class="list-group-item">
                                <h5 class="list-group-item-heading">{{ $cost->name }}</h5>
                                <p class="list-group-item-text">${{ number_format($cost->amount, 2, '.', ',') }}</p>
                                {{--<p>{{ $cost->payments }}</p>--}}
                            </a>
                        @endforeach
                    </ul>
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="tripRegistration">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{--<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>--}}
            <div class="modal-body">

            </div>
            {{--<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>--}}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection