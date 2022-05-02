@extends('layouts.email')

@section('content')
    Hello {{ $user->name }},
    <br/><br/>
    {{ $event->user->name }} just invited you to event {{ $event->title }}.
    <br/><br/>
    Have a nice day!
@endsection
