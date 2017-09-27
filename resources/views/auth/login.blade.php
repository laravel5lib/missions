@extends('site.layouts.default')

@section('title', 'Log Into Your Missions.Me Account')

@section('content')
    <div class="login-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="panel panel-default panel-hero">
                        <div class="panel-heading">
                            <h6 class="text-uppercase text-center">Welcome To Missions.Me</h6>
                        </div>
                        <div class="panel-body">
                            <form class="form" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <label for="email" class="control-label">E-Mail Address</label>

                                        <input id="email"
                                               type="email"
                                               class="form-control"
                                               name="email"
                                               value="{{ old('email') }}"
                                               required
                                               autofocus
                                        >

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <label for="password" class="control-label">Password</label>

                                        <input id="password" type="password" class="form-control" name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"
                                                       name="remember" {{ old('remember') ? 'checked' : '' }}
                                                > Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Log In
                                        </button>
                                        @if ($errors->has('email'))
                                            <a class="btn btn-link btn-block" href="{{ route('password.request') }}">
                                                Forgot Your Password?
                                            </a>
                                        @endif
                                        <hr class="divider inv">
                                        <p class="text-center small">
                                            Don't have an account?
                                            <a href="{{ route('register') }}" class="btn-link">Sign up for free!</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end vid-bg -->
@endsection
