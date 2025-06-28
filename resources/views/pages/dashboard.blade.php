<!-- resources/views/dashboard.blade.php -->


<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard Page')

@section('content')

  <h1>Welcome, {{ Auth::user()->name }}</h1>
  <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">Logout</button>
  </form>

@endsection