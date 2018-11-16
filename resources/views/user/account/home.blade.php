@extends('layouts.rackV1')

@section('content')
<div class="container">

    <div class="row">


        {{-- ACCOUNT SIDEBAR --}}
        @include('user.account.production.sidebar')


        {{-- ACCOUNT SECTION --}}
        <div class="col-sm-8 col-md-9 aw_acc_section">
            <div>
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
                            {{-- @if (Auth::guard('admin')->check())
                                <p>as admin</p>
                            @endif --}}
                        </div>
                    </div>

                    {{-- Account Infobox --}}
                    <div class="acc-home-infobox">
                        <p class="steam-binder">
                            Steam status:
                            @if (Auth::user()->steamid == null)
                                <span class="text-warning">undefined. </span><a href="{{ route('acc-steam-bind') }}" class="aw-link">Bind Steam</a>
                            @else
                                <span class="text-success">{{Auth::user()->steamid}}</span>
                            @endif
                        </p>
                    </div>

                </div>

            </div>
        </div>








    </div>

</div>
@endsection