@extends('layouts.rackV1')

@section('content')
<div class="container">

    <div class="row">


        {{-- ACCOUNT SIDEBAR --}}
        @include('account.production.sidebar')


        {{-- ACCOUNT SECTION --}}
        <div class="col-sm-8 col-md-9 aw_acc_section">
            <div>
                <h2 class="text-center">Uploader</h2>

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

                </div>

            </div>
        </div>








    </div>

</div>
@endsection
