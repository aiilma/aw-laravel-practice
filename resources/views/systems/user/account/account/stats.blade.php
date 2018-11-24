@extends('layouts.sidebar-section')

{{-- SIDEBAR --}}
@section('sidebar')
    {{-- ACCOUNT SIDEBAR --}}
    @include('layouts.user-sidebar')
@endsection

{{-- ACCOUNT SECTION --}}
@section('section')
    <h2 class="text-center">Stats</h2>

    <div class="profile-content">

    </div>

@endsection