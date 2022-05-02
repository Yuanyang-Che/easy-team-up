@extends('layouts.app')

@section('title', 'Events')

@section('content')
    @if($events->isEmpty())
        <h3>There are no events yet. <a href='{{ route('event.create') }}'>Create your event.</a></h3>
    @else
        <table class='table'>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Location</th>
                <th>Time Span</th>
                <th>Creator</th>
                <th>Public</th>
                <th>Details</th>
            </tr>

            @forelse($events as $event)
                <tr>
                    <td>{{ $event->id }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->location }}</td>
                    <td>{{ $event->datetime }}</td>
                    <td>{{ $event->user->name }}</td>
                    <td>{{ $event->is_public? 'public' : 'private' }}</td>
                    @can('view', $event)
                        @can('update', $event)
                            <td><a href='{{ route('event.edit',['id' => $event->id]) }}'>Details(Editable)</a></td>
                        @else
                            <td><a href='{{ route('event.show',['id' => $event->id]) }}'>Details</a></td>
                        @endcan
                    @else
                        <td><a href='{{ route('invitation.request',['id' => $event->id]) }}'>Ask for invitation</a>
                    @endcan
                </tr>
            @empty
                <h3>There is no events now, create one yourself.</h3>
            @endforelse
        </table>
    @endempty
@endsection
