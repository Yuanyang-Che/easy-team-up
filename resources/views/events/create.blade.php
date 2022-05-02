@extends('layouts.app')

@section('title', 'Creat an event')

@section('content')
    {{-- Can remove col-md-6 --}}
    <div id="map" style="width: 500px; height: 400px;"></div>

    <form class="form-horizontal" role="form" method="POST" action="{{ route('event.store') }}">
        @csrf

        <div class="form-group">
            <div class='row'>
                <label class="col-sm-3 control-label" for='lat'>Lat</label>
                <label class="col-sm-3 control-label" for='lng'>Long</label>
            </div>

            <div class='row'>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="lat" id='lat' readonly
                           value="{{ old('lat') ?? 34.0224 }}"/>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="lng" id='lng' readonly
                           value="{{ old('lng') ?? -118.2851 }}">
                </div>
            </div>
        </div>


        <div class="form-group">
            @error("title")
            <small class="text-danger">{{$message}}</small>
            @enderror
            <label class="col-md-4 control-label" for='title'>Title</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="title" id='title'
                       value="{{ old('title') }}"/>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label" for='description'>description</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="description" id='description'
                       value="{{ old('description') }}"/>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label" for='location'>Location</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="location" id='location'
                       value="{{ old('location') }}"/>
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
                       @if(old('is_public') == null)
                           checked
                       @elseif(old('is_public')=='on')
                           checked
                    @endif
                />
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Create Event
                </button>
            </div>
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
                document.querySelector('#lat').value = event.latLng.lat();
                document.querySelector('#lng').value = event.latLng.lng();
            });
        }

        window.initMap = initMap;
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmCOS75WCW3XVW4kqFdrDR623Ly1PzRM8&callback=initMap">
    </script>
@endsection
