@extends('layouts.sidebar-section')

{{-- SIDEBAR --}}
@section('sidebar')
    {{-- ACCOUNT SIDEBAR --}}
    @include('layouts.user-sidebar')
@endsection

{{-- ACCOUNT SECTION --}}
@section('section')
    <h2 class="text-center">Home</h2>

    <div class="profile-content">

        <div class="card">
            <div class="card-header">Dashboard</div>
            
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                You are logged in as a <strong>USER</strong>
            </div>
        </div>

    </div>
@endsection