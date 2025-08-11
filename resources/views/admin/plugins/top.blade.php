<meta charset="utf-8">
<meta name="author" content="Softnio">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description"
    content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
<!-- Fav Icon  -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
<!-- Page Title  -->
<title>Classroom</title>

<style>
    .erp {
        margin-bottom: 0px !important;
    }

    .modal-overflow {
        overflow-y: scroll;
        max-height: 400px;
    }

    .asal {}

    #message {
        display: none;
    }

    #message p {
        font-size: 12px;
    }

    .valid {
        color: green;
    }

    .valid:before {
        content: "✔";
        /* font-family: Verdana, Tahoma, "DejaVu Sans", sans-serif; */
    }

    .invalid {
        color: red;
    }

    .invalid:before {
        content: "✖";
        /* font-family: Verdana, Tahoma, "DejaVu Sans", sans-serif; */
    }
</style>
<!-- StyleSheets  -->

<link rel="stylesheet" href="{{ asset('admin/assets/css/dashlite.css?ver=3.0.3') }}">
<link id="skin-default" rel="stylesheet" href="{{ asset('admin/assets/css/theme.css?ver=3.0.3') }}">
<link id="skin-default" rel="stylesheet" href="{{ asset('admin/assets/css/editors/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('admin/trix/trix.css') }}">
<link href="{{ asset('admin/filepond/filepond.css') }}" rel="stylesheet">
<script src="{{ asset('admin/filepond/filepond.js') }}"></script>
