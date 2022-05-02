@extends('layouts.app')

@section('title', 'Favorites')

@section('content')
    @if($favorites->isEmpty())
        <h3>You have no favorites yet. <a href='{{ route('event.index') }}'>Find some event to favorite.</a></h3>
    @else
        <table class='table'>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Creator</th>
                <th>Public</th>
                <th>Details</th>
                <th>Favorite Date</th>
            </tr>

            @foreach($favorites as $favorite)
                <tr>
                    <td>{{ $favorite->event->id }}</td>
                    <td>{{ $favorite->event->title }}</td>
                    <td>{{ $favorite->event->user->name }}</td>
                    <td>{{ $favorite->event->is_public? 'public' : 'private' }}</td>
                    @can('view', $favorite->event)
                        @can('update', $favorite->event)
                            <td><a href='{{ route('event.edit',['id' => $favorite->event->id]) }}'>Details(Editable)</a>
                            </td>
                        @else
                            <td><a href='{{ route('event.show',['id' => $favorite->event->id]) }}'>Details</a></td>
                        @endcan
                    @else
                        <td><a href='{{ route('invitation.request',['id' => $favorite->event->id]) }}'>Ask for
                                                                                                       invitation</a>
                    @endcan

                    <td>{{ $favorite->updated_at }}</td>
                </tr>

            @endforeach
        </table>
    @endif
@endsection
