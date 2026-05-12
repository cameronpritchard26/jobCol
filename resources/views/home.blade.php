@extends('layouts.app')

@section('content')
<div class="px-4 pt-16 text-center">
    @if ($profile)
        <h1 class="text-4xl font-bold text-gray-900">Welcome, {{ $profile->first_name }}!</h1>
    @else
        <h1 class="text-4xl font-bold text-gray-900">Welcome to JobCol!</h1>
    @endif
</div>
@endsection
