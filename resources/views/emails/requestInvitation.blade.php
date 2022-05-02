@extends('layouts.email')

@section('content')
    Hello {{ $event->user->name }},
    <br/><br/>
    {{ $user->name }} wants to participate in your event {{ $event->title }}
    <br/><br/>
    Have a nice day!
@endsection
