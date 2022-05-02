@extends('layouts.email')

@section('content')
    Hello {{ $user->name }},

    {{ $event->user->name }} just invited you to event {{ $event->title }}.

    Have a nice day!
@endsection
