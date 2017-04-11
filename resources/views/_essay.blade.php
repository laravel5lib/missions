<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Details</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Subject</label>
                                    <p>{{ ucwords($essay->subject) }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Author</label>
                                    <p>{{ $essay->author_name }}</p>
                                </div>
                            </div>
                            <div class="row">
                                @foreach($essay->content as $content)
                                    <div class="col-xs-12">
                                        <hr class="divider">
                                    </div>
                                    <div class="col-sm-2 small">
                                        <p class="help-block">Q:</p>
                                    </div>
                                    <div class="col-sm-10">
                                        <p class="help-block">{{ $content['q'] }}</p>
                                    </div>
                                    <div class="col-sm-2 small">
                                        <p>A:</p>
                                    </div>
                                    <div class="col-sm-10">
                                        @if (isset($content['type']) and $content['type'] === 'file')
                                            {{ $content['a'] ? 'Attached' : 'Not attached' }}
                                        @else
                                            <p>{{ $content['a'] }}</p>
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
                <p>{{ $essay->user->name }}</p>
                <label>Email</label>
                <p>{{ $essay->user->email }}</p>
                <label>Phone</label>
                <p>{{ $essay->user->phone_one ? $essay->user->phone_one : $essay->user->phone_two }}</p>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Attachments</h5>
                </div>
                <div class="panel-body">
                    @if($essay->uploads->count())
                        <ul class="list-group">
                            @foreach($essay->uploads as $upload)
                            <li class="list-group-item">
                                <a href="{{ display_file($upload->source) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i> {{ $upload->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center">No attachments found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>