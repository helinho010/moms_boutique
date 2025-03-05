@extends('layouts.plantillabase')

@section('title', "Cierre Caja")
@section('card-title', "Cierre de Caja")

@section('content')
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus, ratione? Deserunt ratione eos ut facere exercitationem magni nam ipsa labore odio alias explicabo rerum in repellat dolore eligendi, dolores perspiciatis.
@endsection


@push('scripts')
    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("#caja").addClass('active');
        });
    </script>
@endpush