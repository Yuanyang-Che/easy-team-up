@extends('layouts.email')

@section('content')
    Hello {{ $event->user->name }},

    <a href='mailto:$user->email'>{{ $user->name }}</a> wants to participate in your event {{ $event->title }}

    Have a nice day!
@endsection
