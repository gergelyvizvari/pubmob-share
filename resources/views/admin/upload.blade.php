@extends('layouts.app')

@section('header.scripts')

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dropzone/dropzone.min.css') }}">

    <!-- JS -->
    <script src="{{ asset('dropzone/dropzone.min.js') }}" type="text/javascript"></script>

    <style>
        .dz-success > .dz-image {
            background: green !important;
        }
        .dz-error > .dz-image {
            background: red !important;
        }
    </style>

@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Fájl(ok) feltöltése</div>

                    <div class="card-body">
                        <form action="{{ route('json.fileupload') }}" class="dropzone" id="jsonDropzone">
                        <div class="dz-message" data-dz-message><span>Húzza ide a fájlokat feltöltéshez</span></div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer.scripts')
    <script>
        var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        
        Dropzone.options.jsonDropzone = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 50, // MB
            timeout: 600000
        };

    </script>
@endsection
