<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Details</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Number</label>
                                    <p>{{ $passport->number }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Given Names</label>
                                    <p>{{ $passport->given_names }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Surname</label>
                                    <p>{{ $passport->surname }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Birth Country</label>
                                    <p>{{ country($passport->birth_country) }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Citizenship</label>
                                    <p>{{ country($passport->citizenship) }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Issued At</label>
                                    <p>{{ $passport->issued_at->format('M d, Y') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Expires At</label>
                                    <p>{{ $passport->expires_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default panel-body text-center">
                                <label>Managing User</label>
                                <p>{{ $passport->user->name }}</p>
                                <label>Email</label>
                                <p>{{ $passport->user->email }}</p>
                                <label>Phone</label>
                                <p>{{ $passport->user->phone_one ? $passport->user->phone_one : $passport->user->phone_two }}</p>
                            </div>
                        </div>
                    </div>
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
                    @if($passport->upload)
                    <img class="img-responsive" src="{{ image($passport->upload->source) }}" />
                    @else
                    <p class="text-center text-muted"><em>No photo copy provided</em></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>