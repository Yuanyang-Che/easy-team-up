@extends('layouts.app')

@section('title', 'Sign Up')


@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <form class="form-horizontal" role="form" method="POST" action="{{ route('auth.signup') }}">
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
                    <label class="col-md-4 control-label">Username</label>

                    <div class="col-md-6">
                        <input type="text"
                               class="form-control"
                               name="username"
                               value="{{ old('username') }}">
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

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit"
                                class="btn btn-primary"
                                style="margin-right: 15px;">
                            Sign Me Up
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
