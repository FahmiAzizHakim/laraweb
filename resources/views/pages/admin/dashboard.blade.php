@extends('layouts.app')

@section('title', 'CMS Dashboard')

@section('content')
<div class="container mt-5">
    <h1>CMS Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}</p>
</div>
@endsection