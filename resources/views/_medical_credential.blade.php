@inject('date', 'Carbon\Carbon')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Details</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Credential Holder's Name</label>
                                    <p>{{ $credential->applicant_name }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                @foreach($credential->content as $content)
                                    <div class="col-sm-12">
                                    <label>{{ $content['q'] }}</label>
                                        @if (isset($content['certifiedOptions']))
                                            <p>
                                                <span class="help-block">Certification</span>
                                                <ul class="list-group">
                                                    @foreach($content['certifiedOptions'] as $answer)
                                                        @if($answer['value'] === true)
                                                        <li class="list-group-item">
                                                            <i class="fa fa-check"></i> {{ $answer['name'] }}
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                                <span class="help-block">Participation</span>
                                                <ul class="list-group">
                                                    @foreach($content['allOptions'] as $answer)
                                                        @if($answer['value'] === true)
                                                            <li class="list-group-item">
                                                                <i class="fa fa-check"></i> {{ $answer['name'] }}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </p>
                                        @elseif (isset($content['id']) && $content['id'] === 'files')
                                        <label>Attached Documents</label>
                                        <ul class="list-group">
                                            @foreach($content['a'] as $key => $array)
                                                @foreach($array as $answer)
                                                    <li class="list-group-item">
                                                        <span class="help-block">{{ ucwords($key) }}</span>
                                                        @include('_file_link', ['id' => $answer['id'], 'name' => $answer['name']])
                                                        @if($answer['expires'])
                                                        <span class="pull-right text-primary">
                                                            Expires: {{ $date->parse($answer['expires'])->format('F d, Y') }}
                                                        </span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                        @elseif(is_array($content['a']))
                                            <p>
                                                @foreach($content['a'] as $answer)
                                                    {{ $answer }}
                                                    <hr class="divider">
                                                @endforeach
                                            </p>
                                        @else
                                            @if($content['type'] === 'radio')
                                                <p>{{ $content['a'] ? 'Yes' : 'No' }}</p>
                                                <hr class="divider">
                                            @else
                                                <p>{{ $content['a'] }}</p>
                                                <hr class="divider">
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default panel-body text-center">
                <label>Managing User</label>
                <p>{{ $credential->holder->name }}</p>
                <label>Email</label>
                <p>{{ $credential->holder->email }}</p>
                <label>Phone</label>
                <p>{{ $credential->holder->phone_one ? $credential->holder->phone_one : $credential->holder->phone_two }}</p>
            </div>
        </div>
    </div>
</div>