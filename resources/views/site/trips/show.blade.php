@extends('site.layouts.default')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <h3>Todos</h3>
            <ul class="list-group">
                @foreach($trip->todos as $todo)
                    <a class="list-group-item">
                        <p class="list-group-item-text">{{ $todo }}</p>
                    </a>
                @endforeach
            </ul>

            @foreach($trip->notes as $note)
                <h3>{{ $note->subject }}</h3>
                <p>{{ $note->content }}</p>
                <br>
            @endforeach

            <ul class="list-group">
                <li class="list-group-item">
                    <h4 class="list-group-item-heading">Deadlines</h4>
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
                    <h3 class="panel-title">Travel Requirements</h3>
                </div>
                <div class="panel-body">
                    <ul class="">
                        @foreach($trip->requirements as $req)
                            <li class="">{{ $req->item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <h3>Missionaries Registered</h3>
            <div class="row">
                @foreach($trip->reservations as $key => $res)
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="http://lorempixel.com/200/200/people/{{$key}}" alt="...">
                        <div class="caption">
                            <h5>{{ $res->given_names }} {{ $res->surname }}</h5>
                            {{--<p>{{ $res->gender }}</p>--}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{json_encode($trip->deadlines)}}
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="panel panel-default">
            	<div class="panel-body">
                    <a href="/trips/{{ $trip->id }}/register" class="btn btn-primary btn-block">Register Now</a>
                    <hr>

                    <h4>Dates</h4>
                    <h5>{{ date('F d, Y', strtotime($trip->started_at)) }}</h5>
                    <h6>Start Date</h6>
                    <h5>{{ date('F d, Y', strtotime($trip->ended_at)) }}</h5>
                    <h6>End Date</h6>
                    <hr>

                    <h4>Difficulty: {{ $trip->difficulty }}</h4>
                    <div class="progress">
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
                    </div>

                    <hr>

                    <ul class="list-group">
                        @foreach($trip->costs as $cost)
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