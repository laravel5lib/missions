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
                                    <label>Type</label>
                                    <p>{{ ucwords($referral->type) }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Name</label>
                                    <p>{{ $referral->name }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Referral Name</label>
                                    <p>{{ $referral->referral_name }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Referral Email</label>
                                    <p>{{ $referral->referral_email }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Referral Phone</label>
                                    <p>{{ $referral->referral_phone }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <p>{{ ucfirst($referral->status) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default panel-body text-center">
                                <label>Managing User</label>
                                <p>{{ $referral->user->name }}</p>
                                <label>Email</label>
                                <p>{{ $referral->user->email }}</p>
                                <label>Phone</label>
                                <p>{{ $referral->user->phone_one ? $referral->user->phone_one : $referral->user->phone_two }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>