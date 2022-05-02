@extends('layouts.app')

@section('title', 'About event')

@section('content')

    <div id="map" style="width: 500px; height: 400px;"></div>

    <form role='form' method='POST' action='{{ route('favorite.update', ['id' => $favorite->id]) }}'>
        @csrf
        @if ($favorite->favorite)
            <button type='submit' class='btn btn-warning'>Unfavorite</button>
        @else
            <button type='submit' class='btn btn-primary'>Favorite</button>
        @endif
    </form>

    @if (session('favorite-success'))
        <div class='alert alert-success' role='alert'>
            {{ session('favorite-success') }}
        </div>
    @endif


    <input type='hidden' id='event_id' value='{{ $event->id}}'/>
    @if (session('event-success'))
        <div class='alert alert-success' role='alert'>
            {{ session('event-success') }}
        </div>
    @endif

    <form class="form-horizontal" role="form" method="POST">
        @csrf
        <div class="form-group">
            <div class='row'>
                <label class="col-sm-3 control-label" for='lat'>Lat</label>
                <label class="col-sm-3 control-label" for='lng'>Long</label>
            </div>

            <div class='row'>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="lat" id='lat'
                           {{ Auth::user()->cannot('update', $event) ? '' : 'readonly' }}
                           value="{{ old('lat') ?? $event->lat }}"/>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="lng" id='lng'
                           {{ Auth::user()->cannot('update', $event) ? '' : 'readonly' }}
                           value="{{ old('lng') ?? $event->lng }}">
                </div>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label" for='title'>Title</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="title" id='title'
                       value="{{ old('title') ?? $event->title }}"/>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label" for='description'>description</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="description" id='description'
                       value="{{ old('description') ?? $event->description }}"/>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label" for='location'>Location</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="location" id='location'
                       value="{{ old('location') ?? $event->location }}"/>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label" for='datetime'>Datetime</label>
            <div class="col-md-6 flatpickr">
                <input id="datetime" name='datetime' type="text" placeholder="Select Start Date Time.." data-input
                       value='{{ old('datetime') ?? "2022-04-26 12:00 to 2022-05-03 12:00" }}'/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for='is_public'>Is public</label>
            <div class='col-md-1'>
                <input id="is_public" name='is_public' type="checkbox"
                       @if($event->is_public)
                           checked
                       @elseif(old('is_public')=='on')
                           checked
                    @endif
                />
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary"
                        formaction='{{ route('event.update', ['id' => $event->id]) }}'>
                    Update Event
                </button>

                <button type="submit" class="btn btn-danger"
                        formaction='{{ route('event.delete', ['id' => $event->id]) }}'>
                    Delete Event
                </button>
            </div>
        </div>
    </form>

    @if (session('comment-success'))
        <div class='alert alert-success' role='alert'>
            {{ session('comment-success') }}
        </div>
    @endif

    <hr/>
    @forelse ($comments as $comment)
        <form class="mt-3" method='POST'>
            @csrf

            <h1>{{ $comment->body }}</h1>
            <input name='comment'
                   value='{{ $comment->body }}' {{ Auth::user()->cannot('update', $comment) ? 'readonly' : ''}}>

            @can ('update', $comment)
                <button type='submit' class='btn btn-primary'
                        formaction='{{ route('comment.update', ['id' => $comment->id]) }}'>Edit
                </button>
            @endcan
            @can ('delete', $comment)
                <button type='submit' class='btn btn-danger'
                        formaction='{{ route('comment.delete', ['id' => $comment->id]) }}'>Delete
                </button>
            @endcan

            <div class="border-bottom mt-3 pb-3 mb-3">
                <em>
                    Updated on {{ date_format($comment->updated_at,'n/j/Y') }} at
                    {{ date_format($comment->updated_at,'G:i A') }}
                </em>
            </div>
        </form>
    @empty
        <p class="border-bottom pb-3 font-weight-bold">
            No comments yet! Be the first to comment.
        </p>
    @endforelse


    <form class="mt-3" action="{{ route('comment.create') }}" method="POST">
        @csrf
        <input type="hidden" name="event" value="{{ $event->id }}">
        <div class="form-group">
            <textarea name="comment" class="form-control">{{ old('comment') }}</textarea>
            @error('comment')
            <small class="text-danger">
                {{ $message }}
            </small>
            @enderror
        </div>

        <div class="text-right mt-3">
            <button class="btn btn-primary" type="submit">
                Post Comment
            </button>
        </div>
    </form>
@endsection

@section('script')
    <script>
        flatpickr('#datetime', {
            // inline: true,
            mode: "range",
            minDate: "today",

            altInput: true,
            altFormat: "F j, Y H:i",

            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        function initMap() {
            const myLatLng = {
                lat: document.querySelector('#lat').value * 1.0,
                lng: document.querySelector('#lng').value * 1.0
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: myLatLng,
            });

            const marker = new google.maps.Marker({
                position: myLatLng,
                map,
                draggable: true,
                title: document.querySelector('#location').innerText,
            });

            marker.addListener('drag', function (event) {
                document.getElementById('lat').value = event.latLng.lat();
                document.getElementById('lng').value = event.latLng.lng();
            });

        }

        window.initMap = initMap;
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmCOS75WCW3XVW4kqFdrDR623Ly1PzRM8&callback=initMap">
    </script>
@endsection
