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
                                    <span class="help-block">{{ $content['q'] }}</span>
                                        @if (isset($content['id']) && $content['id'] === 'role')
                                            <ul class="list-group">
                                                @foreach($content['options'] as $answer)
                                                    @if($answer['value'] === true)
                                                    <li class="list-group-item">
                                                        {{ $answer['name'] }} <small class="pull-right text-info">{{ $answer['proficiency'] }}</small>
                                                    </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @elseif (isset($content['id']) && $content['id'] === 'equipment')
                                            <ul class="list-group">
                                                @foreach($content['options'] as $answer)
                                                    @if($answer['value'] === true)
                                                    <li class="list-group-item">
                                                        {{ $answer['name'] }}<br />
                                                        <small>Model/Brand: {{ $answer['brand'] ? $answer['brand'] : 'N/A' }}</small>
                                                    </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @elseif (isset($content['id']) && $content['id'] === 'files')
                                            @foreach($content['a'] as $key => $array)
                                                @foreach($array as $answer)
                                                    <label>{{ $key }}</label><br>
                                                    {{ $answer['name'] }}
                                                    <br>
                                                @endforeach
                                            @endforeach
                                        @else
                                            @if($content['type'] === 'checkbox')
                                                <p>{{ $content['a'] ? 'Yes' : 'No' }}</p>
                                            @else
                                                <p>{{ $content['a'] }}</p>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-xs-12 help-block">
                                    <label>Disclaimer</label>
                                    <p class="help-block">Missions.Me requires that Media Professionals provide their own equipment. Rental equipment will not be provided.</p>
                                </div>
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