@extends('layouts.rackV1')

@section('content')

    <div class="container">
        <div class="row">

            {{-- ACCOUNT SIDEBAR --}}
            @include('user.account.production.sidebar')
            
            <div class="col-sm-8 col-md-9 aw_acc_section">
                <div>
                    hey
                </div>
            </div>
        </div>
    </div>

@endsection