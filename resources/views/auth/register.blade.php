@extends('site.layouts.default')

@section('content')
    <div class="login-bg">
        <div class="container">
            <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="text-uppercase text-center">Sign Up for a Free Account</h6>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <input name="timezone" id="timezone" type="hidden" value="">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Gender</label>

                            <div class="col-md-6">
                                <label class="radio-inline lightcolor">
                                    <input name="gender" type="radio" value="male" id="gender" @if(old('gender') == 'male') checked @endif required>
                                    Male
                                </label>
                                <label class="radio-inline lightcolor">
                                    <input name="gender" type="radio" value="female" id="gender" @if(old('gender') == 'female') checked @endif>Female
                                </label>

                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">

                            <label class="col-md-4 control-label">Birthday</label>

                            <div class="col-md-6">
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
                            <label class="col-md-4 control-label">Country</label>

                            <div class="col-md-6">
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

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm"
                                       type="password"
                                       class="form-control"
                                       name="password_confirmation"
                                       required
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Sign Up
                                </button>
                                <hr class="divider inv">
                                <p class="text-center small">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="btn-link">Log into your account.</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
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
