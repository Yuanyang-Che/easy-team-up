@extends('layouts.app')

@section('title', 'Invite someone')

@section('content')
    @empty($events)
        <h3>You have no event to invite. </h3>
    @else

        <form role='form' method='post' action='{{ route('invitation.send') }}'>
            @csrf

            <label class="col-md-4 control-label" for='user'>User</label>
            <select name='user' id='user' class='form-select'>
                <option value=''>-- Select User --</option>
                @foreach($users as $user)
                    @if($user->id != Auth::user()->id)
                        <option
                            value='{{ $user->id }}' {{ (string)$user->id === (string)old('user') ? 'selected' : ''}}>
                            {{ $user->email }}
                        </option>
                    @endif
                @endforeach
            </select>

            @error('user')
            <p class='text-danger'> {{ $message }}</p>
            @enderror

            <hr/>
            <label class="col-md-4 control-label" for='event'>User</label>
            <select name='event' id='event' class='form-select'>
                <option value=''>-- Select Event --</option>
                @foreach($events as $event)
                    <option
                        value='{{ $event->id }}' {{ (string)$event->id === (string)old('event') ? 'selected' : ''}}>
                        {{ $event->title }}
                    </option>
                @endforeach
            </select>

            @error('event')
            <p class='text-danger'> {{ $message }}</p>
            @enderror

            <br/>
            <button class='btn btn-primary' type='submit'>Send Invitation</button>

        </form>
    @endempty
@endsection
