@extends('layouts.plantillabase')

@section('title','Inicio')
@section('h-title','Bienvenido a Mom\'s Boutique')
@section('card-title','Bienvenidos a Mom\'s Boutique')

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
      <h1>Example heading <span class="badge bg-secondary">New</span></h1>
@endsection
