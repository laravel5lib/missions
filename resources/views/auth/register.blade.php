@extends('site.layouts.default')

@section('content')
<div class="login-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">

                <div class="panel panel-default panel-hero">
                    <div class="panel-heading">
                        <h5 class="text-uppercase text-center">Sign Up for a Free Account <br /> <small>and start changing the world!</small></h5>
                    </div>
                    <div class="panel-body">
                            {{ csrf_field() }}

                            <input name="timezone" id="timezone" type="hidden" value="America/Los_Angeles">

                                <div class="row">
                                    <div class="col-sm-4">
                                        <h5>Account</h5>
                                        <p class="text-muted">Please enter a valid email and password that you will use to log into your account.</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <div class="col-sm-8">
                                                <label for="email" class="control-label"><i class="fa fa-envelope-o"></i> E-Mail Address</label>
                                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <div class="col-sm-8">
                                                <label for="password" class="control-label"><i class="fa fa-key"></i> Password</label>
                                                <input id="password" type="password" class="form-control" name="password" required>

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-8">
                                                <label for="password-confirm" class="control-label"><i class="fa fa-hand-o-right"></i> Please Re-enter Password</label>
                                                <input id="password-confirm"
                                                       type="password"
                                                       class="form-control"
                                                       name="password_confirmation"
                                                       required
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="divider">

                                <div class="row">
                                    <div class="col-sm-4">
                                        <h5>About You</h5>
                                        <p class="text-muted">We'd like to get to know you better. This section is all about you.</p>
                                    </div>
                                    <div class="col-sm-8">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                                    <div class="col-xs-12">
                                                        <label for="first_name" class="control-label">First Name</label>
                                                        <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                                        @if ($errors->has('first_name'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('first_name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                                    <div class="col-xs-12">
                                                        <label for="last_name" class="control-label">Last Name</label>
                                                        <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus>

                                                        @if ($errors->has('last_name'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('last_name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                            <div class="col-xs-12">
                                                <label class="control-label">Gender</label>
                                                <br />
                                                <label class="radio-inline lightcolor">
                                                    <input name="gender" type="radio" value="male" id="gender" @if(old('gender') == 'male') checked @endif required>
                                                    Male <i class="fa fa-mars"></i>
                                                </label>
                                                <label class="radio-inline lightcolor">
                                                    <input name="gender" type="radio" value="female" id="gender" @if(old('gender') == 'female') checked @endif>Female <i class="fa fa-venus"></i>
                                                </label>

                                                @if ($errors->has('gender'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('gender') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                            <div class="col-xs-12">
                                                <label class="control-label"><i class="fa fa-gift"></i> Birthday</label>

                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <select class="form-control" name="dob_month" required>
                                                            @foreach($months as $key => $value)
                                                                <option value="{{ $key }}"
                                                                        @if(old('dob_month') == $key) selected @endif>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block lightcolor">Month</span>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <select class="form-control" name="dob_day" required>
                                                            @foreach($days as $key => $value)
                                                                <option value="{{ $key }}"
                                                                        @if(old('dob_day') == $key) selected @endif>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block lightcolor">Day</span>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <select class="form-control" name="dob_year">
                                                            @foreach($years as $key => $value)
                                                                <option value="{{ $key }}"
                                                                        @if(old('dob_year') == $key) selected @elseif($key == '1990') @endif>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block lightcolor">Year</span>
                                                    </div>
                                                </div>

                                                @if ($errors->has('birthday'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('birthday') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                                            <div class="col-sm-6">
                                                <label class="control-label"><i class="fa fa-map-marker"></i> Country</label>
                                                <select name="country" class="form-control">
                                                    @foreach($countries as $key => $value)
                                                        <option value="{{ $value }}"
                                                                @if(old('country') == $value) selected @endif>
                                                            {{ $key }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                 @if ($errors->has('country'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('country') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                    </div>
                    <div class="panel-footer">
                        <div class="form-group" style="padding-top: 1em;">
                            <div class="col-xs-6">
                                <p class="small">
                                    Already have an account?<br />
                                    <a href="{{ route('login') }}" class="btn-link">Log into your account.</a>
                                </p>
                            </div>
                            <div class="col-xs-6 text-right">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Sign Up
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById("timezone").value = timezone.tz.guess();
</script>
@endpush
