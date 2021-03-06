@extends('layouts.app')

@section('title', "About $event->name")

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


    <div class="form-group">
        <div class='row'>
            <label class="col-sm-3 control-label" for='lat'>Lat</label>
            <label class="col-sm-3 control-label" for='lng'>Long</label>
        </div>

        <div class='row'>
            <div class="col-md-3">
                <h6 class='form-control'>{{ $event->lat }}</h6>
                <input style='display:none;' class="form-control" name='lat' id='lat'
                       value="{{ $event->lat }}"/>
            </div>
            <div class="col-md-3">
                <h6 class='form-control'>{{ $event->lng }}</h6>
                <input style='display:none;' class="form-control" name='lng' id='lng'
                       value="{{ $event->lng }}"/>
            </div>
        </div>
    </div>


    <div class="form-group">
        <label class="col-md-4 control-label" for=' title'>Title</label>
        <div class="col-md-6">
            <h6 class='form-control'>{{ $event->title }}</h6>
            {{--<input type="text" class="form-control" name="title" id='title' readonly--}}
            {{--       value="{{ $event->title }}"/>--}}
        </div>
    </div>


    <div class="form-group">
        <label class="col-md-4 control-label" for='description'>description</label>
        <div class="col-md-6">
            <h6 class='form-control'>{{ $event->description }}</h6>
            {{--<input type="text" class="form-control" name="description" id='description' readonly--}}
            {{--       value="{{ $event->description }}"/>--}}
        </div>
    </div>


    <div class="form-group">
        <label class="col-md-4 control-label" for='location'>Location</label>
        <div class="col-md-6">
            <h6 class='form-control'>{{ $event->location }}</h6>
            {{--<input type="text" class="form-control" name="location" id='location' readonly--}}
            {{--       value="{{ $event->location }}"/>--}}
        </div>
    </div>


    <div class="form-group">
        <label class="col-md-4 control-label" for='datetime'>Datetime</label>
        <div class="col-md-6">
            <h6 class='form-control'>{{ $event->datetime }}</h6>
        </div>

    </div>

    <div class="form-group">
        <div class="col-md-6">
            <h6 class='form-control'>{{ $event->is_public ? 'Public Event' : 'Private Event' }}</h6>
        </div>

    </div>

    @if (session('comment-success'))
        <div class='alert alert-success' role='alert'>
            {{ session('comment-success') }}
        </div>
    @endif

    @forelse ($comments as $comment)
        <form class="mt-3" method='POST'>
            @csrf

            @can ('update', $comment)
                <input name='comment' value='{{ $comment->body }}'>

                <button type='submit' class='btn btn-primary'
                        formaction='{{ route('comment.update', ['id' => $comment->id]) }}'>Edit Comment
                </button>

                <button type='submit' class='btn btn-danger'
                        formaction='{{ route('comment.delete', ['id' => $comment->id]) }}'>Delete Comment
                </button>
            @else
                <h5>{{ $comment->body }}</h5>
            @endcan
            <div class="border-bottom mt-3 pb-3 mb-3">
                <em>
                    Updated on {{ date_format($comment->updated_at,'n/j/Y') }} at
                    {{ date_format($comment->updated_at,'G:i A') }} by <b>{{ $comment->user->name }}</b>
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
        function initMap() {
            const myLatLng = {
                lat: document.querySelector('#lat').value * 1.0,
                lng: document.querySelector('#lng').value * 1.0
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: myLatLng,
            });

            new google.maps.Marker({
                position: myLatLng,
                map,
            });
        }

        window.initMap = initMap;
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initMap">
    </script>
@endsection
