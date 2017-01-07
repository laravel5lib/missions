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
                                    <label>Subject</label>
                                    <p>{{ ucwords($essay->subject) }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Author</label>
                                    <p>{{ $essay->author_name }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-12">
                                    @foreach($essay->content as $content)
                                        <label>{{ $content['q'] }}</label>
                                        <p>{{ $content['a'] }}</p>
                                    @endforeach
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>