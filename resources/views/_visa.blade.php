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
                                <div class="col-sm-6">
                                    <label>Number</label>
                                    <p>{{ $visa->number }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Country</label>
                                    <p>{{ country($visa->country_code) }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Given Names</label>
                                    <p>{{ $visa->given_names }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Surname</label>
                                    <p>{{ $visa->surname }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Issued At</label>
                                    <p>{{ $visa->issued_at->format('M d, Y') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Expires At</label>
                                    <p>{{ $visa->expires_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default panel-body text-center">
                                <label>Managing User</label>
                                <p>{{ $visa->user->name }}</p>
                                <label>Email</label>
                                <p>{{ $visa->user->email }}</p>
                                <label>Phone</label>
                                <p>{{ $visa->user->phone_one ? $visa->user->phone_one : $visa->user->phone_two }}</p>
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
                    <img class="img-responsive" src="{{ image($visa->upload->source) }}" />
                </div>
            </div>
        </div>
    </div>
</div>