@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="col-md-4 control-label">Email</label>

                    <div class="col-md-6">
                        <input type="email"
                               class="form-control"
                               name="email"
                               value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input type="password"
                               class="form-control"
                               name="password">
                    </div>
                </div>

                {{--<div class="form-group">--}}
                {{--    <div class="col-md-6 col-md-offset-4">--}}
                {{--        <a href="{{ route('auth.password.reset') }}">@lang('global.app_forgot_password')</a>--}}
                {{--    </div>--}}
                {{--</div>--}}


                {{--<div class="form-group">--}}
                {{--    <div class="col-md-6 col-md-offset-4">--}}
                {{--        <label>--}}
                {{--            <input type="checkbox"--}}
                {{--                   name="remember"> @lang('global.app_remember_me')--}}
                {{--        </label>--}}
                {{--    </div>--}}
                {{--</div>--}}

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit"
                                class="btn btn-primary"
                                style="margin-right: 15px;">
                            Login App
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
