@extends('layouts.sidebar-section')

{{-- SIDEBAR --}}
@section('sidebar')
    {{-- ACCOUNT SIDEBAR --}}
    @include('layouts.user-sidebar')
@endsection

{{-- ACCOUNT SECTION --}}
@section('section')
    <h2 class="text-center">Settings</h2>

    <div class="profile-content">
        {{-- Account Infobox --}}
        <div class="acc-home-infobox">
            <p class="steam-binder">
                Steam status:
                @if (Auth::user()->steamid == null)
                    <span class="text-warning">undefined. </span><a href="{{ route('acc-steam-bind') }}" class="aw-link">Bind Steam</a>
                @else
                    <a href="https://steamcommunity.com/profiles/{{Auth::user()->steamid}}" class="text-success aw-link"><span>{{Auth::user()->steamid}}</span></a>
                @endif
            </p>
        </div>

    </div>
@endsection