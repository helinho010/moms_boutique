@extends('layouts.plantillabase')

@section('title','Inicio')
@section('h-title','Bienvenido a Mom\'s Boutique')
@section('card-title','Reportes')

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

      <div class="alert alert-primary" role="alert">
        A simple primary alert—check it out!
      </div>
      <div class="alert alert-secondary" role="alert">
        A simple secondary alert—check it out!
      </div>
      <div class="alert alert-success" role="alert">
        A simple success alert—check it out!
      </div>
      
@endsection


@push('scripts')
  <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function(){
        $("#home").addClass('active');
    });   
  </script>
      
@endpush