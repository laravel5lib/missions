@extends('master')

@section('layout')
    <div class="container">
        <div class="content-section">
            <div class="row">
                <div class="col-sm-5 col-sm-offset-1">
                    <h1 class="text-hero">Yikes!</h1>
                    <h3>There seems to be an internal server error.</h3>
                    <p>Error code 500</p>
                    @if(app()->bound('sentry') && !empty(Sentry::getLastEventID()))
                        <div class="subtitle">Error ID: {{ Sentry::getLastEventID() }}</div>

                        <!-- Sentry JS SDK 2.1.+ required -->
                        <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

                        <script>
                            Raven.showReportDialog({
                                eventId: '{{ Sentry::getLastEventID() }}',
                                // use the public DSN (dont include your secret!)
                                dsn: 'https://e9ebbd88548a441288393c457ec90441@sentry.io/3235',
                                user: {
                                    'name': 'Jane Doe',
                                    'email': 'jane.doe@example.com',
                                }
                            });
                        </script>
                    @else
                        <p class="small">Please contact the server administrator and inform them of the time the error occured. If possible, please explain the steps you took leading up to the error. We apologize for any inconvience.</p>
                    @endif
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end content-section -->
    </div><!-- end container -->
@endsection